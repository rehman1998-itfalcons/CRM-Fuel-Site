<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use  App\Libraries\MyobCurl;
use App\PurchaseRecord;
use App\myobtokens;
use Illuminate\Support\Facades\DB;
use App\TransactionAllocation;
// use Cache;

class SyncPurchases extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:Sync-purchases-to-myob';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'purchases sync here';

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
        $cron_job =  DB::table('cron_jobs')->where('type', 'sync_purchases')->first();
        if ($cron_job->type == 'sync_purchases' && $cron_job->status == 1) {

             if (\Cache::has('purchases_list')) {

                $purchase_record_list = \Cache::get('purchases_list');
                $purchase_record1 = PurchaseRecord::where('myob_status', 2)->get();

                $paid_status = '';
                foreach ($purchase_record1 as $purchase_inv_record) {
                    foreach ($purchase_record_list as $purchase_list) {
                    foreach ($purchase_list as $slist) {


                        if (isset($slist['UID']) && isset($slist['Number']) && isset($purchase_inv_record->invoice_number) && $purchase_inv_record->invoice_number == $slist['Number']) {

                            DB::table('purchase_records')->where('id', $purchase_inv_record->id)->update([
                                'invoice_uid' => $slist['UID'],
                                'myob_status' => 1
                            ]);

                            // $cron_job->status = 2;
                        }
                    }
                }
                }



                $purchase_record2 = PurchaseRecord::where('myob_status', 1)->where('deleted_status',0)->where('status' ,1)->get();
                foreach ($purchase_record2 as $purchase_inv_record) {
                           foreach ($purchase_record_list as $purchase_list) {
                    foreach ($purchase_list as $slist) {
                        if (isset($slist['UID'])  && isset($purchase_inv_record->invoice_uid) && $purchase_inv_record->invoice_uid == $slist['UID']) {

                            $myob_t_amount = $slist['TotalAmount'];
                            $myob_dueAmount = $slist['BalanceDueAmount'];
                            $paid_amount = $myob_t_amount -  $myob_dueAmount;
                            if (strtolower($slist['Status']) === 'open') {
                                $paid_status = 0;
                            } elseif (strtolower($slist['Status']) === 'closed') {
                                $paid_status = 1;
                            }
                            DB::table('purchase_records')->where('id', $purchase_inv_record->id)->update([

                                'paid_status' => $paid_status,
                                'paid_amount' => $paid_amount

                            ]);


                        }
                    }
                }
                }

                $purchase_record3 = PurchaseRecord::where('myob_payment_status', 0)->where('deleted_status',0)->get();
                foreach ($purchase_record3 as $purchase_inv_record) {
                 if($purchase_inv_record->paid_status == 1){
                             $acc = \App\ChartAccount::where('title','Purchase')->first();
                            $transaction_alloc = new TransactionAllocation();
                            foreach($acc->subaccounts as $account){
                                $transaction_alloc->sub_account_id = $account->id;
                            }
                            $transaction_alloc->purchase_record_id = $purchase_inv_record->id;
                            $transaction_alloc->amount = $purchase_inv_record->total_amount;
                            $transaction_alloc->payment_date = \Carbon\Carbon::parse($purchase_inv_record->datetime)->format('Y-m-d H:i');
                            $transaction_alloc->save();
                            DB::table('purchase_records')->where('id', $purchase_inv_record->id)->update([

                                'myob_payment_status' => 1,


                            ]);
                            // $cron_job->status = 2;
                            }
            }


            if (coupon_status() == false) {
                return redirect('/myob');
            }
            $myob1 = DB::table('myob')->first();
            if ($myob1->status == 1) {
                $myob = new MyobCurl;
                $purchase_record = PurchaseRecord::where('myob_status', 0)->where('deleted_status', 0)->where('status', 1)->limit(3)->get();
                foreach ($purchase_record as $record) {

                    $products = $record->products;


                    $product_items = [];
                    $product_name['account_uid'] = $record->fuelCompany->ac_uid;
                    $product_name['supplier_uid'] = $record->fuelCompany->supplier_uid;

                    foreach ($products as $product) {
                        if ($product->qty > 0) {

                            $unit = $product->rate;
                            $amount = $unit * $product->qty;

                            $product_lines[] =
                                [
                                    "Type" => "Transaction",
                                    "Description" => $product->product->name,
                                    "BillQuantity" => $product->qty,
                                    "UnitPrice" => number_format(round($unit, 4), 4),
                                    "DiscountPercent" => 0,
                                    "Account" => [
                                        "UID" => $product_name['account_uid'],
                                    ],
                                    "TaxCode" => [
                                        //N-T live
                                         "UID" => "4afae618-116c-4b16-ba56-6486cfaafef0"
                                        //N-T demo developer account
                                        //  "UID" => "488e7b93-6b28-4eb2-80f0-72a6f3ab3428"

                                    ],
                                    "Item" => [
                                        "UID" => $product->product->item_uid
                                    ]
                                ];
                        }
                    }


                    $date = '-';
                    $due = '-';

                    $product_name['items'] = $product_items;

                    $product_name['billto'] = $record->fuelCompany->name;
                    $product_name['co_uid'] = $record->fuelCompany->co_uid;
                    $product_name['ac_uid'] = $record->fuelCompany->ac_uid;
                    $product_name['shipto'] = $record->fuelCompany->name;
                    $product_name['supplier'] = $record->fuelCompany->name;

                    $post_json_data = [
                        "Number" => $record->invoice_number,
                        "Date" => $record->datetime,
                        "SupplierInvoiceNumber" => null,
                        "Supplier" => [
                            "UID" =>  $product_name['supplier_uid'],
                        ],
                        "ShipToAddress" => $product_name['shipto'],
                        "Terms" => [



                            "Discount" => 0,

                        ],
                        "IsTaxInclusive" => ($record->gst_status == 'Tax Exclusive') ? false : true,
                        "IsReportable" => false,
                        "Lines" => $product_lines,

                        "FreightTaxCode" => [
                            //LIVE
                             "UID" => "4afae618-116c-4b16-ba56-6486cfaafef0",
                            //DEMO developer account
                            //  "UID" => "488e7b93-6b28-4eb2-80f0-72a6f3ab3428",

                        ]



                    ];


                    if (coupon_status() == false) {
                    }
                    $token = myobtokens::find(1)->access_token;
                    $post_data =     $myob->FileGetContents(
                        company_uri() . 'Purchase/Bill/Item',
                        'post',
                        json_encode($post_json_data),
                        $token
                    );
                    $final_response = json_decode($post_data['response'], true);
                    if (empty($final_response)) {
                            DB::table('purchase_records')->where('id', $record->id)->update([
                            'myob_status' => 2
                        ]);
                        \Cache::flush();
                    }
                    // $post_data['post'] = $post_json_data;
                    $post_json_data = [];
                    $product_lines = [];
                }


            }

            $cron_job->total_run = $cron_job->total_run + 1;
            DB::table('cron_jobs')->where('type', $cron_job->type)->update([
                'status' => 0,
                'total_run' => $cron_job->total_run
            ]);
            DB::table('cron_jobs')->where('type', 'items')->update([
                'status' => 1
            ]);
        }
    }
}
}
