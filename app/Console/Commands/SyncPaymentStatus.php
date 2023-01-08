<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Session;
use DB;
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
use App\Record;
use Illuminate\Support\Facades\Log;
use App\TransactionAllocation;
use PDF;

class SyncPaymentStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:Sync-payment-status-from-myob-against-sale-invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'myob sync sale invoices payment status';

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
        // Cronjob #2
        // $myob = new MyobCurl;
        // $invoice_record = Record::get();
        // $token = myobtokens::find(1)->access_token;
        // $get_data = $myob->FileGetContents(company_uri() . 'Sale/Invoice/Item', 'get', '', $token);
        // $invoice_list1 = json_decode($get_data['response'], true);
        // var_dump($invoice_list1);
        // \Log::info($invoice_list1);
        // // foreach ($invoice_record as $inv_record) {
        //     foreach ($invoice_list1['Items'] as $slist) {
        //         if (isset($slist['Number']) && isset($slist->invoice_number) && $slist->invoice_number == $slist['Number']) {
        //             if ($slist['Status'] == "Open") {
        //                 $slist['Status'] == 0;
        //             } else {
        //                 $slist['Status'] == 1;
        //             }
        //             $paid_amount = $slist['TotalAmount'] - $slist['BalanceDueAmount'];
        //             \DB::table('records')->where('id', $slist->id)->update([
        //                 'paid_status' => $slist['Status'],
        //                 'paid_amount' => $paid_amount

        //             ]);
        //         }
        //     }
        // }

        // \Session::flash('success', 'invoices synced ');
        // // return redirect()->back();
    }
}
