<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Record;
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
use App\TransactionAllocation;
use PDF;

class MyobInvoiceController extends Controller
{

    public function __construct()
    {

        set_time_limit(8000000);
    }

    public function uplaodMyobInvoices()
    {
        // \Session::flash('success', 'invoices synced ');
        //  return redirect()->back();

        $myob = new MyobCurl;
        $invoice_record = Record::where('myob_status', 0)->whereNotNull('sub_company_id')->limit(5)->get();


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
                                // "UID" => "be778956-e739-4e23-8786-c2f63aa4fa28"

                            ],
                            "Item" => [
                                "UID" => $product->product->item_uid

                            ]
                        ];
                }
            }
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
            $myob1 = \DB::table('myob')->first();
            if ($myob1->status == 1) {
                $token = myobtokens::find(1)->access_token;
                $post_data =     $myob->FileGetContents(
                    company_uri() . 'Sale/Invoice/Item',
                    'post',
                    json_encode($post_json_data),
                    $token
                );
                //   dump($post_data);

                $final_response = json_decode($post_data['response'], true);
                $post_data['post'] = $post_json_data;
                $post_json_data = [];
                $product_lines = [];
                if (empty($final_response)) {
                    DB::table('records')->where('id', $record->id)->update([
                        'myob_status' => 1
                    ]);
                // \Cache::flush();

                    // $record->update([
                    //     'myob_status' => 1
                    // ]);
                }

                $get_data = $myob->FileGetContents(company_uri() . 'Sale/Invoice/Item', 'get', '', $token);
                $invoice_list1 = json_decode($get_data['response'], true);
                    //  dump($invoice_list1);

                    $paid_status ="";
                foreach ($invoice_record as $inv_record) {
                    // dd($inv_record);
                    foreach ($invoice_list1['Items'] as $slist) {
                        if (isset($slist['Number']) && isset($inv_record->invoice_number) && $inv_record->invoice_number == $slist['Number']) {
                            //   dump('updation code');
                            $myob_t_amount = $slist['TotalAmount'];
                            $myob_dueAmount = $slist['BalanceDueAmount'];
                            $paid_amount = $myob_t_amount -  $myob_dueAmount;
                            if(strtolower($slist['Status']) === 'open'){
                                $paid_status = 0;
                            }
                            elseif(strtolower($slist['Status']) === 'closed'){
                                $paid_status = 1;
                            }
                            // dd($paid_status);

                            \DB::table('records')->where('id', $inv_record->id)->update([
                                'invoice_uid' => $slist['UID'],
                                'paid_status' => $paid_status,
                                'paid_amount' => $paid_amount,
                                'myob_status' => 1

                            ]);
                            //    dump('update');
                        }
                    }
                }

                \Session::flash('success', 'invoices synced ');
                // return redirect()->back();
            }
        }
    }







    public function uplaodMyobPurchaseInvoices()
    {

        // \Session::flash('success', 'Purchase invoices synced ');
        //  return redirect()->back();

        if (coupon_status() == false) {
            return redirect('/myob');
        }
        $myob1 = \DB::table('myob')->first();
        if ($myob1->status == 1) {
            $myob = new MyobCurl;
            $purchase_record = PurchaseRecord::where('myob_status', 0)->limit(3)->get();
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
                                    //    "UID" => "488e7b93-6b28-4eb2-80f0-72a6f3ab3428"

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
                        // "UID" => "488e7b93-6b28-4eb2-80f0-72a6f3ab3428",

                    ]



                ];
                //  dd($post_json_data);

                if (coupon_status() == false) {
                }
                $token = myobtokens::find(1)->access_token;
                $post_data =     $myob->FileGetContents(
                    company_uri() . 'Purchase/Bill/Item',
                    'post',
                    json_encode($post_json_data),
                    $token
                );
                  dump($post_data);
                $final_response = json_decode($post_data['response'], true);
                $post_data['post'] = $post_json_data;
                $post_json_data = [];
                $product_lines = [];


                //sync uid here
                if (empty($final_response)) {

                    $record->update([
                        'myob_status' => 2
                    ]);
                }
            }
                $get_data = $myob->FileGetContents(company_uri() . 'Purchase/Bill/Item', 'get', '', $token);
                $purchase_invoice_list1 = json_decode($get_data['response'], true);
                // dd($purchase_invoice_list1);


            //  dump($purchase_invoice_list1);
            $paid_status ='';
            foreach ($purchase_record as $purchase_inv_record) {
                foreach ($purchase_invoice_list1['Items'] as $slist) {


                    if (isset($slist['Number']) && isset($purchase_inv_record->invoice_number) && $purchase_inv_record->invoice_number == $slist['Number']) {
                        //    dump('updation code');
                        $myob_t_amount = $slist['TotalAmount'];
                            $myob_dueAmount = $slist['BalanceDueAmount'];
                            $paid_amount = $myob_t_amount -  $myob_dueAmount;
                            if(strtolower($slist['Status']) === 'open'){
                                $paid_status = 0;
                            }
                            elseif(strtolower($slist['Status']) === 'closed'){
                                $paid_status = 1;
                            }
                        \DB::table('purchase_records')->where('id', $purchase_inv_record->id)->update([
                            'invoice_uid' => $slist['UID'],
                            'paid_status' => $paid_status,
                            'paid_amount' => $paid_amount,
                            'myob_status' => 1
                        ]);
                           dump('update');
                    }
                }
            }
            //end of sync
            \Session::flash('success', 'Purchase invoices synced ');
            // return redirect()->back();
        }
    }
}
