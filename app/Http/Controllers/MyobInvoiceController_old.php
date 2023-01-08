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
      
      $myob = new MyobCurl;


      
      $invoice_record = Record::where('myob_status', 0)->whereNotNull('sub_company_id')->limit(100)->get();
     

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
                        "UID" => "186b5079-be9e-4848-96b4-c6fc332f7161"
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
            
         }
         $token = myobtokens::find(1)->access_token;
         $post_data =     $myob->FileGetContents(
            company_uri() . 'Sale/Invoice/Item',
            'post',
            json_encode($post_json_data),
            $token
         );

         $final_response = json_decode($post_data['response'],true);
         $post_data['post'] = $post_json_data;
$post_json_data = [];
         $product_lines=[];
         if (empty($final_response)){
         
            $record->update([
               'myob_status' => 1
            ]);
         }
         
        echo  $record->invoice_number."<br>";
      }
      return 'done';
     
   }


 

  public function uplaodMyobPurchaseInvoices(){
       $myob = new MyobCurl;
         $purchase_record = PurchaseRecord::where('myob_status', 0)->limit(100)->get();
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
                     "UID" => "186b5079-be9e-4848-96b4-c6fc332f7161"
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
               "UID" =>  $product_name['supplier_uid'] , 
               ], 
            "ShipToAddress" => $product_name['shipto'], 
            "Terms" => [
                  
                  
              
                  "Discount" => 0, 
                  
               ], 
            "IsTaxInclusive" => ($record->gst_status=='Tax Exclusive') ? false : true,
            "IsReportable" => false, 
            "Lines" => $product_lines,
            
            "FreightTaxCode" => [
               "UID" => "ff576147-d16b-4b9f-9491-44d3ed03f8b0",

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
            $final_response = json_decode($post_data['response'],true);
            $post_data['post'] = $post_json_data;
            $post_json_data = [];
            $product_lines=[];
            if (empty($final_response)){
          
            $record->update([
               'myob_status' => 1
            ]);
            }
         }
            

            return 'done';


               
                  Session::flash('success','Purchase record added successfully.');
               return redirect('/purchases');
                  
         
      }
}
