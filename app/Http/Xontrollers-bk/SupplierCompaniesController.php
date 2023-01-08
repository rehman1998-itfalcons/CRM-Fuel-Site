<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\SupplierCompany;
use Session;
use Str;
use App\myobtokens;

use  App\Libraries\MyobCurl;

class SupplierCompaniesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('superadmin');
    }  
 
  
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = SupplierCompany::get();
        return view('admin.supplier-companies.index', compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
        ]);

        SupplierCompany::create([
            'name' => $request->name
        ]);

        Session::flash('success','Supplier Company added successfully.');
        return redirect('/supplier-companies');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string',
        ]);

        $role = SupplierCompany::find($request->id);
        $role->update([
          'name' => $request->name
        ]);

        Session::flash('success','Supplier Company updated successfully.');
        return redirect('/supplier-companies');
    }
    //sync funtion

    public function syncSupplierCompany(){
        // Getl all product list === db_product
        $db_suppliercompany =SupplierCompany::get();
        
           if (coupon_status() == false){
            }
            $myob = new MyobCurl;
          $token = myobtokens::find(1)->access_token;
            
                     
                        $supplier_uid='';
                        $get_data =     $myob->FileGetContents(company_uri().'Contact/Supplier', 'get','', $token);
                        $supplier_list = json_decode($get_data['response'],true);//ture due to array
                        //  dd($supplier_list);
                        foreach($supplier_list as $suppliers){
                          if (is_array($suppliers) || is_object($suppliers))
                            {
                            foreach($suppliers as $supplier) { 
                              foreach ($db_suppliercompany as $scompany){
                                if ($scompany->display_id === $supplier['DisplayID']){
                                  $supplier_uid = $supplier['UID'];
                                  // Update Query $product->id;
                                  \DB::table('supplier_companies')->where('display_id',$scompany->display_id)->update([
                                    'supplier_uid' => $supplier_uid
                                    
                          
                                  ]);
                                  
                                  
                                }                         
                              }
                            }
                          }
                        }
      }
      


      public function syncSupplierAccount(){
        // Getl all product list === db_product
        $db_suppliercompany =SupplierCompany::get();
        
           if (coupon_status() == false){
            }
            $myob = new MyobCurl;
          $token = myobtokens::find(1)->access_token;
            
                     
                        $supplier_uid='';
                        $get_data =     $myob->FileGetContents(company_uri().'GeneralLedger/Account', 'get','', $token);
                        $supplier_list = json_decode($get_data['response'],true);//ture due to array
                        //  dd($supplier_list);
                        foreach($supplier_list as $suppliers){
                          if (is_array($suppliers) || is_object($suppliers))
                            {
                            foreach($suppliers as $supplier) { 
                              foreach ($db_suppliercompany as $scompany){
                                if ($scompany->account_id === $supplier['DisplayID']){
                                  $ac_uid = $supplier['UID'];
                                  // Update Query $product->id;
                                  \DB::table('supplier_companies')->where('account_id',$scompany->account_id)->update([
                                    'ac_uid' => $ac_uid
                                    
                          
                                  ]);
                                  
                                  
                                }                         
                              }
                            }
                          }
                        }
      }
      


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
      
    }
}
