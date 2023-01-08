<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Record;
use Session;
use  App\Libraries\MyobCurl;
use App\myobtokens;
use App\PurchaseRecord;
use DB;

class PaginationsController extends Controller
{
    public function salePagination()
    {
        $sale_record_list = [];
        $invoice_data = [];
        $sale_record = Record::get();
        if (coupon_status() == false) {
        }
        $myob = new MyobCurl;
        $token = myobtokens::find(1)->access_token;

        $get_data =     $myob->FileGetContents(company_uri() . 'Sale/Invoice/Item', 'get', '', $token);
        $sale_record_list_final = json_decode($get_data['response'], true);


        $sale_record_list[]['Items'] = $sale_record_list_final['Items'];

        $total_pages = $sale_record_list_final['Count'] / 400;
        for ($x = 1; $x <= $total_pages; $x++) {
            $page_count = $x * 400;
            $get_data =     $myob->FileGetContents(company_uri() . 'Sale/Invoice/Item?$top=400&$skip=' . $page_count, 'get', '', $token);
            $sale_record_list_final_2 = json_decode($get_data['response'], true);
            $sale_record_list[]['Items'] = $sale_record_list_final_2['Items'];
        }
        // dd($sale_record_list[]['Items'] );



    }


    public function purchasepagination()
    {

        $purchase_record_list = [];
        $invoice_data = [];
        $purchase_record = PurchaseRecord::get();

        if (coupon_status() == false) {
        }
        $myob = new MyobCurl;
        $token = myobtokens::find(1)->access_token;
        $get_data =     $myob->FileGetContents(company_uri() . 'Purchase/Bill/Item', 'get', '', $token);
        $purchase_record_list_final = json_decode($get_data['response'], true);
        $purchase_record_list[]['Items'] = $purchase_record_list_final['Items'];

        $total_pages = $purchase_record_list_final['Count'] / 400;
        for ($x = 1; $x <= $total_pages; $x++) {
            $page_count = $x * 400;
            $get_data =     $myob->FileGetContents(company_uri() . 'Purchase/Bill/Item?$top=400&$skip=' . $page_count, 'get', '', $token);
            $purchase_record_list_final_2 = json_decode($get_data['response'], true);
            $purchase_record_list[]['Items'] = $purchase_record_list_final_2['Items'];
        }




    }
}
