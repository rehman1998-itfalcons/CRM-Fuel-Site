<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Record;
use Session;
use App\Company;
use App\SubCompany;
use App\Category;
use App\SubCompanyRateArea;
use Image;
use  App\Libraries\MyobCurl;
use App\myobtokens;
use DB;
use App\PurchaseRecord;
use App\Smtp;
use App\SubAccount;
use App\InvoiceSetting;
use Carbon\Carbon;
use App\TransactionAllocation;
use PDF;

class CustomerPaymentController extends Controller
{  
   
   
  
  	
  
  	public function CustomerPayment($id)
    {
      $myob = new MyobCurl;
      
   
      $invoice_record = Record::select('*')->where('id', $id)->get();
        
      $record = Record::find($invoice_record[0]->id);
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
                 "Description"=>$product->product->name,
                 "ShipQuantity" => $product->qty, 
                 "UnitOfMeasure"=>"ltr",
                 "UnitPrice" => number_format(round($unit, 4), 4), 
                 "DiscountPercent" => 0, 
                 "Account" => [
                  "UID"=>$product_name['account_uid'],
                ],
                 "TaxCode" => [
                    "UID" => "be778956-e739-4e23-8786-c2f63aa4fa28" 
                 ], 
                 "Item" => [
                       "UID" => $product->product->item_uid
                     
                    ] 
              ] 
                 ;
         }
      }
      $date = '-';
      $due = '-';
      if ($record->subCompany->inv_due_days > 0) {
         $date = \Carbon\Carbon::parse($record->datetime)->format('d-m-Y');
         $origional_date =$record->subCompany->inv_due_days;
         $due = date('Y-m-d h:m:s', strtotime($date . ' + ' . $record->subCompany->inv_due_days . ' days'));
      } elseif ($record->subCompany->inv_due_days < 0) {
         $timestamp = strtotime(\Carbon\Carbon::parse($record->datetime)->format('d-m-Y H:i'));
         $daysRemaining = (int)date('t', $timestamp) - (int)date('j', $timestamp);
         $positive_value =  abs($record->subCompany->inv_due_days);
         $origional_date = $positive_value + $daysRemaining;
         $date = \Carbon\Carbon::parse($record->datetime)->format('d-m-Y');
         $due = date('Y-m-d h:m:s', strtotime($date . ' + ' . $origional_date . ' days'));
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
                "PaymentIsDue"=> "InAGivenNumberOfDays",
                 "BalanceDueDate" => $product_name['duedays'], 
               
              ], 
        "IsTaxInclusive" => ($record->gst_status=='Excluded') ? false : true, 
        "IsReportable" => false, 
        "Lines" => $product_lines,
        
        
     ]; 
      
      if (coupon_status() == false){
        
    }
    $token = myobtokens::find(1)->access_token;
     $post_data =     $myob->FileGetContents(
      company_uri().'Sale/Invoice/Item',
      'post',
      json_encode($post_json_data)
  ,
  $token
  );
  $post_data ['post'] = $post_json_data;
  dd($post_data);
  $record->update([
    'status' => 1
  ]);
      
      	
      	$this->deleteNotification($id);
      
      	Session::flash('success','Application approved successfully.');
      	return redirect('/accountant/dashboard');
    }


   
  
  
}
