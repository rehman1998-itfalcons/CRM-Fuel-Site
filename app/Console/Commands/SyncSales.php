<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use App\Record;
use Session;
use Illuminate\Support\Facades\DB;
use App\Company;
use App\SubCompany;
use App\Category;
use App\SubCompanyRateArea;
use Image;
use  App\Libraries\MyobCurl;
use App\myobtokens;
use App\PurchaseRecord;
use App\Smtp;
use App\SubAccount;
use App\InvoiceSetting;
use Carbon\Carbon;
use App\TransactionAllocation;
use PDF;

use Illuminate\Support\Facades\Log;
// use Cache;

class SyncSales extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:Sync-sales-to-myob';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Sales invoices to myob';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $cron_job =  DB::table('cron_jobs')->where('type', 'sync_sales')->first();
        if ($cron_job->type == 'sync_sales' && $cron_job->status == 1) {



              if (\Cache::has('sale_list')) {

                $sale_record_list = \Cache::get('sale_list');
                //  Log::info($sale_record_list);dd();



                $paid_status = "";
                $invoice_record2 = Record::where('myob_status', 2)->get();
                //   Log::info($invoice_record2);
                foreach ($invoice_record2 as $inv_record) {
                    foreach ($sale_record_list as $sale_single_page) {
                        foreach ($sale_single_page as $slist) {

                        if (isset($slist['UID']) && isset($slist['Number']) && isset($inv_record->invoice_number) && $inv_record->invoice_number == $slist['Number']) {
                            //   Log::info($slist); dd();

                            DB::table('records')->where('id', $inv_record->id)->update([
                                'invoice_uid' => $slist['UID'],
                                'myob_status' => 1
                            ]);
                            }
                        }
                    }
                }

                //check payment status


                $invoice_record3 = Record::where('myob_status', 1)->where('deleted_status' , 0)->where('status' ,1)->get();

                foreach ($invoice_record3 as $inv_record) {

                    foreach ($sale_record_list as $sale_single_page) {
                        foreach ($sale_single_page as $slist) {

                        if (isset($slist['UID']) && isset($inv_record->invoice_uid) && $inv_record->invoice_uid == $slist['UID']) {
                            //   Log::info($slist); dd();

                            $myob_t_amount = $slist['TotalAmount'];
                            $myob_dueAmount = $slist['BalanceDueAmount'];
                            $paid_amount = $myob_t_amount -  $myob_dueAmount;
                            if (strtolower($slist['Status']) === 'open') {
                                $paid_status = 0;
                            } elseif (strtolower($slist['Status']) === 'closed') {
                                $paid_status = 1;

                            }
                            DB::table('records')->where('id', $inv_record->id)->update([

                                'paid_status' => $paid_status,
                                'paid_amount' => $paid_amount

                            ]);



                        }
                    }
                    }
                }

                  $invoice_record4 = Record::where('myob_payment_status', 0)->where('deleted_status',0)->get();

                foreach ($invoice_record4 as $inv_record) {
                 if($inv_record->paid_status == 1){
                            //transaction allocation table update
                            $acc = \App\ChartAccount::where('title','Sale')->first();
                            $transaction_alloc = new TransactionAllocation();
                            foreach($acc->subaccounts as $account){
                                $transaction_alloc->sub_account_id = $account->id;
                            }
                            $transaction_alloc->record_id = $inv_record->id;
                            $transaction_alloc->amount = $inv_record->total_amount;
                            $transaction_alloc->payment_date = \Carbon\Carbon::parse($inv_record->datetime)->format('Y-m-d H:i');
                            $transaction_alloc->save();
                            // $cron_job->status = 2;
                              DB::table('records')->where('id', $inv_record->id)->update([
                                'myob_payment_status' => 1

                            ]);
                            }
                }
            }//end of cache

            $myob = new MyobCurl;
            $invoice_record = Record::where('myob_status', 0)->whereNotNull('sub_company_id')->where('deleted_status', 0)->where('status', 1)->limit(2)->get();
            foreach ($invoice_record as $record) {
                $products = $record->products;
                $product_items = [];
                $product_name['account_uid'] = $record->subCompany->ac_uid;
                foreach ($products as $product) {
                    if ($product->qty > 0) {
                        $unit = ($product->whole_sale + $product->delivery_rate + $product->brand_charges + $product->cost_of_credit) - $product->discount;
                        $amount = $unit * $product->qty;
                        $product_lines[] =
                            [
                                "Type" => "Transaction",
                                "Description" => $product->product->name,
                                "ShipQuantity" => $product->qty,
                                "UnitOfMeasure" => "ltr",
                                "UnitPrice" => number_format(round($unit, 4), 4),
                                "DiscountPercent" => 0,

                                "Account" => [
                                    "UID" => $product_name['account_uid'],
                                ],
                                "TaxCode" => [
                                    //live uid gst
                                      "UID" => "4afae618-116c-4b16-ba56-6486cfaafef0"


                                    //demo uid gst
                                    //   "UID" => "be778956-e739-4e23-8786-c2f63aa4fa28"

                                ],
                                "Item" => [
                                    "UID" => $product->product->item_uid

                                ]
                            ];
                    }
                }
                // Log::debug($product_lines);
                $date = '-';
                $due = '-';

                if ($record->subCompany->inv_due_days > 0) {
                    $date = \Carbon\Carbon::parse($record->datetime)->format('d-m-Y');
                    $origional_date = $record->subCompany->inv_due_days;
                    $due = date('Y-m-d h:m:s', strtotime($date . ' + ' . $record->subCompany->inv_due_days . ' days'));
                } elseif ($record->subCompany->inv_due_days < 0) {
                    $timestamp = strtotime(\Carbon\Carbon::parse($record->datetime)->format('d-m-Y H:i'));
                    $daysRemaining = (int)date('t', $timestamp) - (int)date('j', $timestamp);
                    $positive_value =  abs($record->subCompany->inv_due_days);
                    $origional_date = $positive_value + $daysRemaining;
                    $date = \Carbon\Carbon::parse($record->datetime)->format('d-m-Y');
                    $due = date('Y-m-d h:m:s', strtotime($date . ' + ' . $origional_date . ' days'));
                } else {
                    $origional_date = 14;
                }
                $product_name['items'] = $product_items;
                $product_name['duedays'] = $origional_date;
                $product_name['record'] = $invoice_record;
                $product_name['duedate'] = ($due == '-') ? \Carbon\Carbon::parse($record->datetime)->format('Y-m-d h:m:s') : $due;
                $product_name['billto'] = $record->subCompany->company->name;
                $product_name['co_uid'] = $record->subCompany->co_uid;
                $product_name['shipto'] = $record->subCompany->name;
                $product_name['supplier'] = $record->supplierCompany->name;
                $post_json_data = [
                    "Number" => $record->invoice_number,
                    "Date" => $record->datetime,
                    "SupplierInvoiceNumber" => null,
                    "Customer" => [
                        "UID" => $product_name['co_uid'],
                    ],
                    "ShipToAddress" => $product_name['shipto'],
                    "Terms" => [
                        "PaymentIsDue" => "InAGivenNumberOfDays",
                        "DueDate" => $product_name['duedate'],
                        "BalanceDueDate" => $product_name['duedays'],

                    ],
                    "IsTaxInclusive" => ($record->gst_status == 'Excluded') ? false : true,
                    "IsReportable" => false,
                    "Lines" => $product_lines,

                ];

                if (coupon_status() == false) {
                    return redirect('/myob');
                }
                $myob1 = DB::table('myob')->first();
                if ($myob1->status == 1) {
                    $token = myobtokens::find(1)->access_token;
                    $post_data =     $myob->FileGetContents(
                        company_uri() . 'Sale/Invoice/Item',
                        'post',
                        json_encode($post_json_data),
                        $token
                    );
                    $final_response = json_decode($post_data['response'], true);

                    if(empty($final_response)){

                        DB::table('records')->where('id', $record->id)->update([
                            'myob_status' => 2
                        ]);
                    \Cache::flush();


                    }



                }
                $post_json_data = [];
                $product_lines = [];
            }

                    // $post_data['post'] = $post_json_data;




            $cron_job->total_run = $cron_job->total_run + 1;
            DB::table('cron_jobs')->where('type', $cron_job->type)->update([
                'status' => 0,
                'total_run' => $cron_job->total_run
            ]);
            DB::table('cron_jobs')->where('type', 'sync_p_cache')->update([
                'status' => 1
            ]);
        }
    }
}
