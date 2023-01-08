<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Libraries\MyobCurl;
use App\myobtokens;
use Session;
use DB;

class DeleteController extends Controller
{

    public function __construct()
    {
        set_time_limit(8000000);
    }
    public function delete(){
        return view('delete.contacts.index');
    }

    public function deleteContacts(){
        if (coupon_status() == false){
            return redirect('/myob');
        }
        $myob = new MyobCurl;
      $token = myobtokens::find(1)->access_token;



                    $get_data =     $myob->FileGetContents(company_uri().'Contact/Customer', 'get','', $token);
                    $customer_list = json_decode($get_data['response'],true);//true due to array
                    //   dd($customer_list);
                    foreach($customer_list as $customers){
                      if (is_array($customers) || is_object($customers))
                        {
                            foreach($customers as $customer){

                            $myob->FileGetContents(company_uri().'Contact/Customer/'.$customer['UID'], 'delete','', $token);

                            }
                      }
                    }
                Session::flash('success',' Contact Customer Deleted from Myob');
              return redirect()->back();
    }


    public function deleteSupplier(){
        if (coupon_status() == false){
            return redirect('/myob');
        }
        $myob = new MyobCurl;
      $token = myobtokens::find(1)->access_token;



                    $get_data =     $myob->FileGetContents(company_uri().'Contact/Supplier', 'get','', $token);
                    $supplier_list = json_decode($get_data['response'],true);//true due to array

                    foreach($supplier_list as $suppliers){
                      if (is_array($suppliers) || is_object($suppliers))
                        {
                            foreach($suppliers as $supplier){

                                $myob->FileGetContents(company_uri().'Contact/Supplier/'.$supplier['UID'], 'delete','', $token);

                            }

                      }
                    }
                Session::flash('success',' Contact Supplier Deleted from Myob');
              return redirect()->back();
    }

     public function getTaxcode(){
        if (coupon_status() == false){
            return redirect('/myob');
        }
        $myob = new MyobCurl;
      $token = myobtokens::find(1)->access_token;
                     $get_data = $myob->FileGetContents(company_uri().'GeneralLedger/TaxCode', 'get','', $token);
                      $customer_list = json_decode($get_data['response'],true);
                      dd($customer_list);
    }


    public function deleteEmployees(){
        if (coupon_status() == false){
            return redirect('/myob');
        }
        $myob = new MyobCurl;
      $token = myobtokens::find(1)->access_token;



                    $get_data =     $myob->FileGetContents(company_uri().'Contact/Employee', 'get','', $token);
                    $employee_list = json_decode($get_data['response'],true);//true due to array

                    foreach($employee_list as $employees){
                      if (is_array($employees) || is_object($employees))
                        {

                            foreach($employees as $employee){
                            $myob->FileGetContents(company_uri().'Contact/Employee/'.$employee['UID'], 'delete','', $token);
                            }
                      }
                    }
                Session::flash('success',' Contact Employees Deleted from Myob');
              return redirect()->back();
    }
    public function deleteCOA(){
        if (coupon_status() == false){
            return redirect('/myob');
        }
        $myob = new MyobCurl;
      $token = myobtokens::find(1)->access_token;



      $get_data =     $myob->FileGetContents(company_uri() . 'GeneralLedger/Account', 'get', '', $token);
      $contact_list = json_decode($get_data['response'], true);

                    foreach($contact_list as $contacts){
                      if (is_array($contacts) || is_object($contacts))
                        {

                            foreach($contacts as $contact){
                            // $myob->FileGetContents(company_uri().'Contact/Employee'.$employee['UID'], 'delete','', $token);
                             $myob->FileGetContents(company_uri().'GeneralLedger/Account/'.$contact['UID'], 'delete','', $token);
                             dump('done');
                            }
                      }
                    }
                Session::flash('success',' Contact Employees Deleted from Myob');
              return redirect()->back();
    }


    public function deleteItems(){
        if (coupon_status() == false){
            return redirect('/myob');
        }
        $myob = new MyobCurl;
      $token = myobtokens::find(1)->access_token;
      dump($token);



                     $get_data =     $myob->FileGetContents(company_uri().'Inventory/Item', 'get','', $token);
                      $item_list = json_decode($get_data['response'],true);

                    foreach($item_list as $items){
                      if (is_array($items) || is_object($items))
                        {

                            foreach($items as $item){
                            // $myob->FileGetContents(company_uri().'Contact/Employee'.$employee['UID'], 'delete','', $token);
                             $myob->FileGetContents(company_uri().'Inventory/Item/'.$item['UID'], 'delete','', $token);
                             dump('done');
                            }
                      }
                    }
                    dd('done');
                \Session::flash('success',' Contact Employees Deleted from Myob');
              return redirect()->back();
    }



    public function deletePersonal(){
        if (coupon_status() == false){
            return redirect('/myob');
        }
        $myob = new MyobCurl;
      $token = myobtokens::find(1)->access_token;



                    $get_data =     $myob->FileGetContents(company_uri().'Contact/Personal', 'get','', $token);
                    $personal_list = json_decode($get_data['response'],true);//true due to array

                    foreach($personal_list as $personals){
                      if (is_array($personals) || is_object($personals))
                        {
                            foreach($personals as $personal){

                                $myob->FileGetContents(company_uri().'Contact/Personal'.$personal['UID'], 'get','', $token);
                            }

                      }
                    }
                Session::flash('success',' Contact Personal Deleted from Myob');
              return redirect()->back();
    }
}
