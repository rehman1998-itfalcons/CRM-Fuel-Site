<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\SubCompany;
use App\SubCompanyEmail;
use Session;
use DB;
use App\Company;
use App\Category;
use App\Product;
use App\SubcompanyField;
use App\SubCompanyRateArea;
use  App\Libraries\MyobCurl;
use App\myobtokens;
class SubCompanyController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index()
  	{
    }

    public function store(Request $request)
    {
        dd($request);
          $this->validate($request,[
              	'company_id' => 'required',
              	'name' => 'required',
          ]);

          $myob = new MyobCurl;
          if (coupon_status() == false){
             return redirect('/myob');
         }

        $myob1 = DB::table('myob')->first();
        dd($myob1);
       if($myob1->status == 1){


         $token = myobtokens::find(1)->access_token;
           $this->validate($request,[
               'name' => 'required'
           ]);
           $DisplayID  = "9-".rand(1234,9999);
         $post_json_data = [
           "Name" => $request->name,
           "DisplayID" => $DisplayID,
           "Classification" => "Income",
           "Type" => "Income",
           "Number" => substr($DisplayID,2,4),
           "Description" => $request->name,
           "IsActive" => true,
           "TaxCode" => [
                 "UID" => "be778956-e739-4e23-8786-c2f63aa4fa28",
              ],
           "ParentAccount" => [
                    "UID" => "393e5d7d-b0ca-4b48-8105-c8fb90488eee",
                 ]
           ];
           //  dd(json_encode($post_json_data));

             $post_data =     $myob->FileGetContents(
                 company_uri().'GeneralLedger/Account',
                 'post',
                 json_encode($post_json_data)
             ,
             $token
             );


        //dd($post_data);
        sleep(5);
        $post_json_data_to_customer = [
        "LastName" => "",
        "FirstName" => $request->name,
        "CompanyName" => $request->name,
        "IsIndividual" => false,
        "DisplayID" => $DisplayID,
        "SellingDetails" => [
                "SaleLayout" => "NoDefault",
                "PrintedForm" => "Pre-Printed Invoice",
                "InvoiceDelivery" => "Print",
                "TaxCode" => [
                    "UID" => "be778956-e739-4e23-8786-c2f63aa4fa28",
                    "Code" => "GST"
                ],
                "FreightTaxCode" => [
                    "UID" => "be778956-e739-4e23-8786-c2f63aa4fa28",
                    "Code" => "GST"
                    ]
            ]
        ];

               $post_data_to_customer =     $myob->FileGetContents(
                   company_uri().'Contact/Customer',
                   'post',
                   json_encode($post_json_data_to_customer)
               ,
               $token

               );
               // dd($post_json_data_to_customer);
         $subcompany = SubCompany::create([
                'company_id' => $request->company_id,
                'category_id' => $request->category_id,
                "display_id"=>$DisplayID,
                'name' => $request->name,
            	'status' => 1
          ]);

          Session::flash('success','Sub Company created');
          //dd('here');
           return redirect()->route('companies.index');

      	//   return redirect('/subcompanies/'.$subcompany->id.'/edit');
    }
    else{

            Session::flash('error','Myob Disabled');
              return redirect()->route('companies.index');

        }
    }
    public function syncSubCompany(){
      // Getl all product list === db_product
      $db_subcompany =SubCompany::get();

         if (coupon_status() == false){
          }
          $myob = new MyobCurl;
        $token = myobtokens::find(1)->access_token;


                      $customer_uid='';
                      $get_data =     $myob->FileGetContents(company_uri().'Contact/Customer', 'get','', $token);
                      $customer_list = json_decode($get_data['response'],true);//ture due to array
                      // dd($item_list);
                      foreach($customer_list as $customers){
                        if (is_array($customers) || is_object($customers))
                          {
                          foreach($customers as $customer) {
                            foreach ($db_subcompany as $sub){
                              if ($sub->display_id === $customer['DisplayID']){
                                $customer_uid = $customer['UID'];
                                // Update Query $product->id;
                                DB::table('sub_companies')->where('display_id',$sub->display_id)->update([
                                  'co_uid' => $customer_uid


                                ]);


                              }
                            }
                          }
                        }
                      }
    }
    public function syncCustomerAccount(){
      // Getl all product list === db_product
      $db_suppliercompany =SubCompany::get();

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
                                \DB::table('sub_companies')->where('account_id',$scompany->account_id)->update([
                                  'ac_uid' => $ac_uid


                                ]);


                              }
                            }
                          }
                        }
                      }
    }


  	public function edit($id)
    {
      	$products = Product::where('status',1)->get();
       $subcompany = SubCompany::find($id);
       $categories = Category::select('id','name')->where('status',1)->orderBy('name','asc')->get();
       $companies = Company::select('id','name')->where('status',1)->orderBy('name','asc')->get();
       return view('admin.subcompanies.edit', compact('subcompany','companies','categories','products'));
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
      $this->validate($request,[
         'name' => 'string|required',
         'phone_no' => 'numeric|required',
         'address' => 'string|required',
         'due_date' => 'numeric'
    	]);

      $inv_type = $request->inv_type;
      if($inv_type == -1)
      {
      $due_date = -1*$request->due_date;

      }
      else
      {
            $due_date = $request->due_date;
      }

      $sub = SubCompany::find($id);
	$sub->update([
    'company_id' => $request->company_id,
    'category_id' => $request->category_id,
    'name' => $request->name,
    'split_load' => $request->split_load,
    'gst_status' => $request->gst_status,
    'gst' => $request->gst,
    'phone_no' => $request->phone_no,
    'address' => $request->address,
    'inv_due_days' => $due_date
		]);

        $count = $request->counter;
        if ($count >= 1) {
            for ($i = 1; $i <= $count; $i++) {
                $email = $request->input('email_' . $i);
                if ($email != '') {
                  	$check = SubCompanyEmail::where('sub_company_id',$id)->where('email_address',$email)->first();
                  	if (!$check) {
                      SubCompanyEmail::create([
                          'sub_company_id' => $id,
                          'email_address' => $email
                      ]);
                    }
                }
            }
        }

      /* Categories Products */
      $products = Product::where('status',1)->get();

      foreach ($products as $product) {

        $whole_sale = ($request->input('whole_sale_' . $product->id) ?? 0);
        $discount_rate = ($request->input('discount_' . $product->id) ?? 0);
        $delivery_rate = ($request->input('delivery_rate_' . $product->id) ?? 0);
        $brand_charges = ($request->input('brand_charges_' . $product->id) ?? 0);
        $credit_limit =  ($request->input('cost_of_credit_' . $product->id) ?? 0);

        $check = SubCompanyRateArea::where('sub_company_id',$id)
          ->where('product_id',$product->id)
          ->first();
        if ($check) {
			$check->update([
              'whole_sale' => $whole_sale,
              'discount' => $discount_rate,
              'delivery_rate' => $delivery_rate,
              'brand_charges' => $brand_charges,
              'cost_of_credit' => $credit_limit
        	]);
        } else {
          SubCompanyRateArea::create([
            'sub_company_id' => $id,
            'product_id' => $product->id,
            'whole_sale' => $whole_sale,
            'discount' => $discount_rate,
            'delivery_rate' => $delivery_rate,
            'brand_charges' => $brand_charges,
            'cost_of_credit' => $credit_limit
          ]);
        }
      }


      	Session::flash('success','Sub Company updated');
    	return redirect('/companies');
    }

  	public function changeStatus(Request $request)
    {

          DB::table('sub_companies')->where('id',$request->id)->update([
            'status' => $request->status,
          ]);

          Session::flash('success','Status changed.');

      	return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }

  	public function deleteEmail(Request $request)
    {
      $email = SubCompanyEmail::find($request->id);
      $email->delete();

      return '1';
    }

   public function updateCategory(Request $request)
    {
      $subcompany = SubCompany::find($request->subcompany_id);
     $subcompany->category_id = $request->category_id;
     $subcompany->update();

      return back();
    }
}
