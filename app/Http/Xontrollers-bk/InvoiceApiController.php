<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Record;
use App\PurchaseRecord;
use Session;
use App\Smtp;
use App\SubAccount;
use App\InvoiceSetting;
use Carbon\Carbon;
use App\TransactionAllocation;
use PDF;                                             

class InvoiceApiController extends Controller
{
    	public function invoiceDetails($id)
    {
             // $invoice_record = Record::select('*')->where('invoice_number', $id)->get();
       $invoice_record = Record::select('*')->where('invoice_number',$id)->get();
       $record = Record::find($invoice_record[0]->id);
       $products = $record->products;
       $product_items = [];
       
       foreach ($products as $product) {
         if ($product->qty > 0) {
            $unit = ($product->whole_sale + $product->delivery_rate + $product->brand_charges + $product->cost_of_credit) - $product->discount;
            $amount = $unit * $product->qty;
            $product_items[] = ['name' => $product->product->name, 'p_uid' => $product->product->item_uid, 'p_uid' => $product->product->item_uid, 'qty' => $product->qty, 'rate' => number_format(round($unit, 4), 4), 'amount' => number_format(round($amount, 4), 4)];
       }
       $product_name['record']=$invoice_record;
       $product_name['billto']=$record->subCompany->company->name;
       $product_name['shopto']=$record->subCompany->name ;
       $product_name['supplier']=$record->supplierCompany->name ;      }
      }
      $date = '-';
      $due = '-';
      if ($record->subCompany->inv_due_days > 0) {
         $date = \Carbon\Carbon::parse($record->datetime)->format('d-m-Y');
         $due = date('d-m-Y', strtotime($date . ' + ' . $record->subCompany->inv_due_days . ' days'));
      } elseif ($record->subCompany->inv_due_days < 0) {
         $timestamp = strtotime(\Carbon\Carbon::parse($record->datetime)->format('d-m-Y H:i'));
         $daysRemaining = (int)date('t', $timestamp) - (int)date('j', $timestamp);
         $positive_value =  abs($record->subCompany->inv_due_days);
         $origional_date = $positive_value + $daysRemaining;
         $date = \Carbon\Carbon::parse($record->datetime)->format('d-m-Y');
         $due = date('d-m-Y', strtotime($date . ' + ' . $origional_date . ' days'));
      }
      $product_name['items'] = $product_items;
      $product_name['record'] = $invoice_record;
      $product_name['duedate'] = ($due == '-') ? \Carbon\Carbon::parse($record->datetime)->format('d-m-Y') : $due;
      $product_name['billto'] = $record->subCompany->company->name;
      $product_name['account_uid'] = $record->subCompany->ac_uid;
      $product_name['co_uid'] = $record->subCompany->co_uid;
      $product_name['shopto'] = $record->subCompany->name;
      $product_name['supplier'] = $record->supplierCompany->name;
       return response()->json($product_name, 200);
    }
    
    public function PurchaseInvoiceDetail($id)
    {
      $invoice_record = PurchaseRecord::select('*')->where('invoice_number', $id)->get();
      if (!empty($invoice_record) && isset($invoice_record['0'])) {
       $record = PurchaseRecord::find($invoice_record[0]->id);
       $products = $record->products;
       $product_detail = [];
       $product_name['record'] = $invoice_record;
       $product_name['supplier'] = $record->fuelCompany->name;
       return response()->json($product_name, 200);
    } else {
       return response()->json([], 200);
    }
       }
    }
