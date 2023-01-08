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

    public function __construct()
    {
        set_time_limit(8000000);
    }
    public function index()
    {
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'company_id' => 'required',
            'name' => 'required',
        ]);
        $myob = new MyobCurl;
        if (coupon_status() == false) {
            return redirect('/myob');
        }
        $DisplayID  = "9-" . rand(1234, 9999);
        $subcompany = SubCompany::create([
            'company_id' => $request->company_id,
            'category_id' => $request->category_id,
            "display_id" => $DisplayID,
            'name' => $request->name,
            'status' => 1
        ]);
        $myob1 = \DB::table('myob')->first();
        if ($myob1->status == 1) {
            $token = myobtokens::find(1)->access_token;
            $this->validate($request, [
                'name' => 'required'
            ]);

            $post_json_data = [
                "Name" => $request->name,
                "DisplayID" => $DisplayID,
                "Classification" => "Income",
                "Type" => "Income",
                "Number" => substr($DisplayID, 2, 4),
                "Description" => $request->name,
                "IsActive" => true,
                "TaxCode" => [
                    "UID" => "2ab1cf79-bce8-4da0-986c-a3bbdd1d02bc",
                ],
                "ParentAccount" => [
                    "UID" => "393e5d7d-b0ca-4b48-8105-c8fb90488eee",
                ]
            ];
            //  dd(json_encode($post_json_data));

            $post_data =     $myob->FileGetContents(
                company_uri() . 'GeneralLedger/Account',
                'post',
                json_encode($post_json_data),
                $token
            );
            //dd($post_data);


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
                         //live uid
                            // "UID" => "4afae618-116c-4b16-ba56-6486cfaafef0",
                            //sanaullah developer api UID
                        "UID" => "be778956-e739-4e23-8786-c2f63aa4fa28",
                        "Code" => "GST"
                    ],
                    "FreightTaxCode" => [
                         //live uid
                            // "UID" => "4afae618-116c-4b16-ba56-6486cfaafef0",
                            //sanaullah developer api UID
                        "UID" => "be778956-e739-4e23-8786-c2f63aa4fa28",
                        "Code" => "GST"
                    ]
                ]
            ];

            $post_data_to_customer =     $myob->FileGetContents(
                company_uri() . 'Contact/Customer',
                'post',
                json_encode($post_json_data_to_customer),
                $token

            );
            // dd($post_json_data_to_customer);


            Session::flash('success', 'Sub Company created');
            //dd('here');
            return redirect()->back();

            //   return redirect('/subcompanies/'.$subcompany->id.'/edit');
        } else {

            Session::flash('error', 'Myob Disabled Data only stored into Database');
            return redirect()->back();
        }
    }
    // public function syncSubCompany()
    // {
    //     $db_subcompany = SubCompany::get();

    //     if (coupon_status() == false) {
    //         return redirect('/myob');
    //     }
    //     $myob = new MyobCurl;
    //     $token = myobtokens::find(1)->access_token;


    //     $customer_uid = '';
    //     $get_data =     $myob->FileGetContents(company_uri() . 'Contact/Customer', 'get', '', $token);
    //     $customer_list = json_decode($get_data['response'], true);

    //     foreach ($customer_list as $customers) {
    //         if (is_array($customers) || is_object($customers)) {
    //             foreach ($customers as $customer) {
    //                 foreach ($db_subcompany as $sub) {
    //                     if ($sub->display_id != $customer['DisplayID']) {
    //                         $customer_uid = $customer['UID'];
    //                         $display_id = $customer['DisplayID'];

    //                         $post_json_data_to_customer = [
    //                             "LastName" => "",
    //                             "FirstName" => $sub->name,
    //                             "CompanyName" => $sub->name,
    //                             "IsIndividual" => false,
    //                             "DisplayID" => $sub->display_id,
    //                             "SellingDetails" => [
    //                                 "SaleLayout" => "NoDefault",
    //                                 "PrintedForm" => "Pre-Printed Invoice",
    //                                 "InvoiceDelivery" => "Print",
    //                                 "TaxCode" => [
    //                                     "UID" => "be778956-e739-4e23-8786-c2f63aa4fa28",
    //                                     "Code" => "GST"
    //                                 ],
    //                                 "FreightTaxCode" => [
    //                                     "UID" => "be778956-e739-4e23-8786-c2f63aa4fa28",
    //                                     "Code" => "GST"
    //                                 ]
    //                             ]
    //                         ];

    //                         $post_data_to_customer =     $myob->FileGetContents(
    //                             company_uri() . 'Contact/Customer',
    //                             'post',
    //                             json_encode($post_json_data_to_customer),
    //                             $token

    //                         );
    //                         dump($post_data_to_customer);

    //                         DB::table('sub_companies')->where('display_id', $sub->display_id)->update([
    //                             'co_uid' => $customer_uid,
    //                             'display_id' => $display_id,
    //                             'myob_status' => 1


    //                         ]);
    //                     }
    //                     //   else{
    //                     //     Session::flash('success', 'Customer  synced');
    //                     //     return redirect('/sync-to-myob');
    //                     // }
    //                 }
    //             }
    //         }
    //     }

    //     // $db_subcompany = SubCompany::get();

    //     // if (coupon_status() == false) {
    //     // }
    //     // $myob = new MyobCurl;
    //     // $token = myobtokens::find(1)->access_token;


    //     // $customer_uid = '';
    //     // $get_data =     $myob->FileGetContents(company_uri() . 'Contact/Customer', 'get', '', $token);
    //     // $customer_list = json_decode($get_data['response'], true);

    //     // foreach ($customer_list as $customers) {
    //     //     if (is_array($customers) || is_object($customers)) {
    //     //         foreach ($customers as $customer) {
    //     //             foreach ($db_subcompany as $sub) {
    //     //                 if ($sub->display_id === $customer['DisplayID']) {
    //     //                     $customer_uid = $customer['UID'];

    //     //                     DB::table('sub_companies')->where('display_id', $sub->display_id)->update([
    //     //                         'co_uid' => $customer_uid


    //     //                     ]);
    //     //                 }
    //     //             }
    //     //         }
    //     //     }
    //     // }
    // }
    public function syncSubCompany()
    {
        \Session::flash('success', 'Customer syncing started');
         return redirect()->back();
        // dd('here');


        $db_subcompany = SubCompany::where('myob_status', 0)->limit(5)->get();

        if (coupon_status() == false) {
            return redirect('/myob');
        }
        $myob1 = \DB::table('myob')->first();
        if ($myob1->status == 1) {
            $myob = new MyobCurl;
            $token = myobtokens::find(1)->access_token;

            $get_data =     $myob->FileGetContents(company_uri() . 'Contact/Customer', 'get', '', $token);
            $customer_list = json_decode($get_data['response'], true);

            //  dd($customer_list);
            // dd($db_subcompany);
            $not_sync = [];
            $sync = [];
            foreach ($db_subcompany as $customer_comp) {
                $not_sync[$customer_comp->id] = $customer_comp;
                // dd($customer_comp->display_id);
                foreach ($customer_list['Items'] as $slist) {
                    //dd($slist);
                    // dd($customer_list['Items']);
                    // $not_sync[$customer_comp->id] = $customer_comp;
                    // dd($slist['DisplayID']);
                    //  dd($customer_comp->display_id);
                    if (isset($slist['DisplayID']) && isset($customer_comp->display_id) && $customer_comp->display_id == $slist['DisplayID']) {
                        $sync[$customer_comp->id] = $customer_comp;
                    }
                }
            }
            //   dump($sync);
            //  dd($not_sync);
            foreach ($sync as $key => $all_key) {
                unset($not_sync[$key]);
            }
            // dd('do');
            //dump($not_sync);
            foreach ($not_sync as $subcompany_notsync) {
                $display = $subcompany_notsync->display_id;
                $post_json_data_to_customer = [
                    "LastName" => "",
                    "FirstName" => $subcompany_notsync->name,
                    "CompanyName" => $subcompany_notsync->name,
                    "IsIndividual" => false,
                    "DisplayID" => $subcompany_notsync->display_id,
                    "SellingDetails" => [
                        "SaleLayout" => "NoDefault",
                        "PrintedForm" => "Pre-Printed Invoice",
                        "InvoiceDelivery" => "Print",
                        "TaxCode" => [
                            //live uid
                            // "UID" => "4afae618-116c-4b16-ba56-6486cfaafef0",
                            //sanaullah developer api UID
                            "UID" => "be778956-e739-4e23-8786-c2f63aa4fa28",
                            "Code" => "GST"
                        ],
                        "FreightTaxCode" => [
                             //live uid
                            // "UID" => "4afae618-116c-4b16-ba56-6486cfaafef0",
                            //sanaullah developer api UID
                            "UID" => "be778956-e739-4e23-8786-c2f63aa4fa28",
                            "Code" => "GST"
                        ]
                    ]
                ];

                // dump($post_json_data_to_customer);


                $post_data_to_customer =     $myob->FileGetContents(
                    company_uri() . 'Contact/Customer',
                    'post',
                    json_encode($post_json_data_to_customer),
                    $token

                );
                  dump($post_data_to_customer);
            }

            $get_data =  $myob->FileGetContents(company_uri() . 'Contact/Customer', 'get', '', $token);
            $customer_list1 = json_decode($get_data['response'], true);
            //    dump($customer_list1);

            foreach ($db_subcompany as $customer_comp) {
                foreach ($customer_list1['Items'] as $slist) {


                    if (isset($slist['DisplayID']) && isset($customer_comp->display_id) && $customer_comp->display_id == $slist['DisplayID']) {
                        //   dump('updation code');
                        \DB::table('sub_companies')->where('id', $customer_comp->id)->update([
                            'co_uid' => $slist['UID'],
                            'myob_status' => 1

                        ]);
                         dump('update');
                    }
                }
            }
            // dd('synced all');
            //  dump($sync);
            //  dd($not_sync);

            \Session::flash('success', 'Customer COA Synced');
            // return redirect()->back();
        }
    }


    public function syncCustomerAccount()
    {
        \Session::flash('success', 'Customer COA syncing started');
        return redirect()->back();


        $db_subcompany = SubCompany::where('coa_status', 0)->limit(10)->get();

        if (coupon_status() == false) {
            return redirect('/myob');
        }
        $myob1 = \DB::table('myob')->first();
        if ($myob1->status == 1) {
            $myob = new MyobCurl;
            $token = myobtokens::find(1)->access_token;

            $get_data =     $myob->FileGetContents(company_uri() . 'GeneralLedger/Account', 'get', '', $token);
            $customer_list = json_decode($get_data['response'], true);
            $not_sync = [];
            $sync = [];
            foreach ($db_subcompany as $customer_comp) {
                foreach ($customer_list['Items'] as $slist) {

                    $not_sync[$customer_comp->id] = $customer_comp;
                    // dd($slist['DisplayID']);
                    // dd($customer_comp->display_id);
                    if (isset($slist['DisplayID']) && isset($customer_comp->display_id) && $customer_comp->display_id == $slist['DisplayID']) {
                        $sync[$customer_comp->id] = $customer_comp;
                    }
                }
            }
            //dd($sync);
            foreach ($sync as $key => $all_key) {
                unset($not_sync[$key]);
            }
            //dump($not_sync);
            foreach ($not_sync as $subcompany_notsync) {
                $display = $subcompany_notsync->display_id;
                $post_json_data = [
                    "Name" => $subcompany_notsync->name,
                    "DisplayID" => $display,
                    "Classification" => "Income",
                    "Type" => "Income",
                    "Number" => substr($display, 2, 4),
                    "Description" => $subcompany_notsync->name,
                    "IsActive" => true,
                    "TaxCode" => [

                        // INCOME tax code DEMO developer UID gst code
                         "UID" => "be778956-e739-4e23-8786-c2f63aa4fa28",
                        // INCOME taxcode live UID
                        // "UID" => "f9338d31-b33f-425a-bbf7-da4bd8cd53e1",
                    ],
                    "ParentAccount" => [
                        // live parent account UID
                        // "UID" => "13f54671-7a4b-4e1e-9de6-0440da9ec25d",
                        // DEMO parent uid TYPE INCODE
                        "UID" => "393e5d7d-b0ca-4b48-8105-c8fb90488eee",
                    ]
                ];



                $post_data =     $myob->FileGetContents(
                    company_uri() . 'GeneralLedger/Account',
                    'post',
                    json_encode($post_json_data),
                    $token
                );
                dump($post_data);
            }

            $get_data =     $myob->FileGetContents(company_uri() . 'GeneralLedger/Account', 'get', '', $token);
            $customer_list1 = json_decode($get_data['response'], true);
            //  dd($customer_list1);

            foreach ($db_subcompany as $customer_comp) {
                foreach ($customer_list1['Items'] as $slist) {


                    if (isset($slist['DisplayID']) && isset($customer_comp->display_id) && $customer_comp->display_id == $slist['DisplayID']) {
                        //   dump('updation code');
                        \DB::table('sub_companies')->where('id', $customer_comp->id)->update([
                            'ac_uid' => $slist['UID'],
                            'account_id' => $slist['DisplayID'],
                            'coa_status' => 1

                        ]);
                        //   dump('update');
                    }
                }
            }
            // dd('synced all');
            //  dump($sync);
            //  dd($not_sync);

            \Session::flash('success', 'Customer COA Synced');
            // return redirect()->back();
        }
    }
    // public function syncCustomerAccount()
    // {



    //     $db_suppliercompany = SubCompany::get();

    //     if (coupon_status() == false) {
    //     }
    //     $myob = new MyobCurl;
    //     $token = myobtokens::find(1)->access_token;


    //     $supplier_uid = '';
    //     $get_data =     $myob->FileGetContents(company_uri() . 'GeneralLedger/Account', 'get', '', $token);
    //     $supplier_list = json_decode($get_data['response'], true);

    //     foreach ($supplier_list as $suppliers) {
    //         if (is_array($suppliers) || is_object($suppliers)) {
    //             foreach ($suppliers as $supplier) {
    //                 foreach ($db_suppliercompany as $scompany) {
    //                     if ($scompany->account_id != $supplier['DisplayID']) {
    //                         $ac_uid = $supplier['UID'];
    //                         $display = $scompany->display_id;
    //                         $post_json_data = [
    //                             "Name" => $scompany->name,
    //                             "DisplayID" => $display,
    //                             "Classification" => "Income",
    //                             "Type" => "Income",
    //                             "Number" => substr($display, 2, 4),
    //                             "Description" => $scompany->name,
    //                             "IsActive" => true,
    //                             "TaxCode" => [
    //                                 "UID" => "be778956-e739-4e23-8786-c2f63aa4fa28",
    //                             ],
    //                             "ParentAccount" => [
    //                                 "UID" => "393e5d7d-b0ca-4b48-8105-c8fb90488eee",
    //                             ]
    //                         ];
    //                         //  dd(json_encode($post_json_data));

    //                         $post_data =     $myob->FileGetContents(
    //                             company_uri() . 'GeneralLedger/Account',
    //                             'post',
    //                             json_encode($post_json_data),
    //                             $token
    //                         );


    //                         dd($post_data);

    //                         \DB::table('sub_companies')->where('account_id', $scompany->account_id)->update([
    //                             'ac_uid' => $ac_uid


    //                         ]);
    //                     }
    //                     // else{
    //                     //     Session::flash('success', 'Customer Account synced');
    //                     //     return redirect('/sync-to-myob');
    //                     // }
    //                 }
    //             }
    //         }
    //     }
    //     // $db_suppliercompany = SubCompany::get();

    //     // if (coupon_status() == false) {
    //     // }
    //     // $myob = new MyobCurl;
    //     // $token = myobtokens::find(1)->access_token;


    //     // $supplier_uid = '';
    //     // $get_data =     $myob->FileGetContents(company_uri() . 'GeneralLedger/Account', 'get', '', $token);
    //     // $supplier_list = json_decode($get_data['response'], true);

    //     // foreach ($supplier_list as $suppliers) {
    //     //     if (is_array($suppliers) || is_object($suppliers)) {
    //     //         foreach ($suppliers as $supplier) {
    //     //             foreach ($db_suppliercompany as $scompany) {
    //     //                 if ($scompany->account_id === $supplier['DisplayID']) {
    //     //                     $ac_uid = $supplier['UID'];

    //     //                     \DB::table('sub_companies')->where('account_id', $scompany->account_id)->update([
    //     //                         'ac_uid' => $ac_uid


    //     //                     ]);
    //     //                 }
    //     //             }
    //     //         }
    //     //     }
    //     // }
    // }


    public function edit($id)
    {
        $products = Product::where('status', 1)->get();
        $subcompany = SubCompany::find($id);
        $categories = Category::select('id', 'name')->where('status', 1)->orderBy('name', 'asc')->get();
        $companies = Company::select('id', 'name')->where('status', 1)->orderBy('name', 'asc')->get();
        return view('admin.subcompanies.edit', compact('subcompany', 'companies', 'categories', 'products'));
    }


    public function update(Request $request, $id)
    {
        //  dump($request->company_id);
        //  dump($request->category_id);
        //  dump($request->name);
        //  dd($request);
        $this->validate($request, [
            'name' => 'string|required',
            'phone_no' => 'numeric|required',
            'address' => 'string|required',
            'due_date' => 'numeric'
        ]);

        $inv_type = $request->inv_type;
        if ($inv_type == -1) {
            $due_date = -1 * $request->due_date;
        } else {
            $due_date = $request->due_date;
        }

        $sub = SubCompany::find($id);
        $sub->update([
            // 'company_id' => $request->company_id,
            // 'category_id' => $request->category_id,
            // 'name' => $request->name,
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
                    $check = SubCompanyEmail::where('sub_company_id', $id)->where('email_address', $email)->first();
                    if (!$check) {
                        SubCompanyEmail::create([
                            'sub_company_id' => $id,
                            'email_address' => $email
                        ]);
                    }
                }
            }
        }


        $products = Product::where('status', 1)->get();

        foreach ($products as $product) {

            $whole_sale = ($request->input('whole_sale_' . $product->id) ?? 0);
            $discount_rate = ($request->input('discount_' . $product->id) ?? 0);
            $delivery_rate = ($request->input('delivery_rate_' . $product->id) ?? 0);
            $brand_charges = ($request->input('brand_charges_' . $product->id) ?? 0);
            $credit_limit =  ($request->input('cost_of_credit_' . $product->id) ?? 0);

            $check = SubCompanyRateArea::where('sub_company_id', $id)
                ->where('product_id', $product->id)
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


        Session::flash('success', 'Sub Company updated');
        return redirect('/companies');
    }

    public function changeStatus(Request $request)
    {

        DB::table('sub_companies')->where('id', $request->id)->update([
            'status' => $request->status,
        ]);

        Session::flash('success', 'Status changed.');

        return back();
    }


    public function destroy($id)
    {
        //
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
