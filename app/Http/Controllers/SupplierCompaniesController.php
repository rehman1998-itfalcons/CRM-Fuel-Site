<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\SupplierCompany;
use Session;
use Str;
use DB;
use App\myobtokens;

use  App\Libraries\MyobCurl;

class SupplierCompaniesController extends Controller
{


    public function __construct()
    {
        set_time_limit(8000000);
        $this->middleware('superadmin');
    }



    public function index()
    {
        $companies = SupplierCompany::get();
        return view('admin.supplier-companies.index', compact('companies'));
    }


    public function store(Request $request)
    {
        // dd('hello');
        $this->validate($request, [
            'name' => 'required|string',
        ]);

        $myob = new MyobCurl;
        if (coupon_status() == false) {
            return redirect('/myob');
        }
        SupplierCompany::create([
            'name' => $request->name
        ]);
        $myob1 = \DB::table('myob')->first();
        if ($myob1->status == 1) {
            $token = myobtokens::find(1)->access_token;
            $this->validate($request, [
                'name' => 'required|string',
            ]);

            // $unique_number = substr(md5($request->name),1,14);
            $DisplayID  = "7-" . rand(1234, 9999);


            $post_json_data = [
                "Name" => $request->name,
                "DisplayID" => $DisplayID,
                "Classification" => "Liability",
                "Type" => "OtherLiability",
                "Number" => substr($DisplayID, 2, 4),
                "Description" => $request->name,
                "IsActive" => true,
                "TaxCode" => [
                    "UID" => "488e7b93-6b28-4eb2-80f0-72a6f3ab3428",
                ],
                "ParentAccount" => [
                    "UID" => "ae5ed194-e01c-473f-ae84-5c85fcae4236",
                ]
            ];
            //   dd(json_encode($post_json_data));

            $post_data =     $myob->FileGetContents(
                company_uri() . 'GeneralLedger/Account',
                'post',
                json_encode($post_json_data),
                $token
            );
            //    dd($post_data);

            sleep(5);

            $post_json_data = [

                'CompanyName' => $request->name,
                'IsIndividual' => false,
                'DisplayID' => $DisplayID,
                "BuyingDetails" => [
                    "TaxCode" => [
                        "UID" => "be778956-e739-4e23-8786-c2f63aa4fa28",
                    ],
                    "FreightTaxCode" => [
                        "UID" => "be778956-e739-4e23-8786-c2f63aa4fa28",
                    ]
                ]
            ];


            $post_data =     $myob->FileGetContents(company_uri() . 'Contact/Supplier', 'post', json_encode($post_json_data), $token);
            // dd($post_data);


            \Session::flash('success', 'Supplier Company added successfully.');
            return redirect('/supplier-companies');
        } else {

            \Session::flash('error', 'Myob Disabled Data only stored into Database');
            return redirect('/supplier-companies');
        }
    }



    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string',
        ]);

        $role = SupplierCompany::find($request->id);
        $role->update([
            'name' => $request->name
        ]);

        \Session::flash('success', 'Supplier Company updated successfully.');
        return redirect('/supplier-companies');
    }


    public function syncSupplierCompany()
    {
        \Session::flash('success', 'Supplier Synced');
        return redirect()->back();


        $db_suppliercompany = SupplierCompany::get();

        if (coupon_status() == false) {
            return redirect('/myob');
        }
        $myob1 = \DB::table('myob')->first();
        if ($myob1->status == 1) {
            $myob = new MyobCurl;
            $token = myobtokens::find(1)->access_token;
            $get_data =     $myob->FileGetContents(company_uri() . 'Contact/Supplier', 'get', '', $token);
            $supplier_list = json_decode($get_data['response'], true);
            $not_sync = [];
            $sync = [];
            foreach ($db_suppliercompany as $supplier_comp) {
                $not_sync[$supplier_comp->id] = $supplier_comp;
                foreach ($supplier_list['Items'] as $slist) {


                    if (isset($slist['DisplayID']) && isset($supplier_comp->display_id) && $supplier_comp->display_id == $slist['DisplayID']) {
                        $sync[$supplier_comp->id] = $supplier_comp;
                    }
                }
            }

            foreach ($sync as $key => $all_key) {
                unset($not_sync[$key]);
            }
            foreach ($not_sync as $scompany_notsync) {
                $post_json_data = [

                    'CompanyName' => $scompany_notsync->name,
                    'IsIndividual' => false,
                    'DisplayID' => $scompany_notsync->display_id,
                    "BuyingDetails" => [
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

                $post_data =     $myob->FileGetContents(company_uri() . 'Contact/Supplier', 'post', json_encode($post_json_data), $token);
                dump($post_data);
            }

            $get_data =     $myob->FileGetContents(company_uri() . 'Contact/Supplier', 'get', '', $token);
            $supplier_list1 = json_decode($get_data['response'], true);
            // dd($supplier_list1);

            foreach ($db_suppliercompany as $supplier_comp) {
                foreach ($supplier_list1['Items'] as $slist) {


                    if (isset($slist['DisplayID']) && isset($supplier_comp->display_id) && $supplier_comp->display_id == $slist['DisplayID']) {
                        // dump('updation code');
                        \DB::table('supplier_companies')->where('id', $supplier_comp->id)->update([
                            'supplier_uid' => $slist['UID'],
                            'display_id' => $slist['DisplayID'],
                            'myob_status' => 1
                        ]);
                        dump('update');
                    }
                }
            }
            // dd('synced all');
            //  dump($sync);
            //  dd($not_sync);

            \Session::flash('success', 'Supplier Synced');
            // return redirect()->back();
        }
    }



    public function syncSupplierAccount()
    {

        \Session::flash('success', 'Supplier COA Synced');
        return redirect()->back();

        $db_suppliercompany = SupplierCompany::where('coa_status', 0)->get();

        if (coupon_status() == false) {
            return redirect('/myob');
        }
        $myob1 = \DB::table('myob')->first();
        if ($myob1->status == 1) {
            $myob = new MyobCurl;
            $token = myobtokens::find(1)->access_token;
            $get_data =     $myob->FileGetContents(company_uri() . 'GeneralLedger/Account', 'get', '', $token);
            $contact_list = json_decode($get_data['response'], true);
            $not_sync = [];
            $sync = [];
            foreach ($db_suppliercompany as $supplier_comp) {
                $not_sync[$supplier_comp->id] = $supplier_comp;
                foreach ($contact_list['Items'] as $slist) {


                    if (isset($slist['DisplayID']) && isset($supplier_comp->display_id) && $supplier_comp->display_id == $slist['DisplayID']) {
                        $sync[$supplier_comp->id] = $supplier_comp;
                    }
                }
            }

            foreach ($sync as $key => $all_key) {
                unset($not_sync[$key]);
            }
            foreach ($not_sync as $scompany_notsync) {
                $display = $scompany_notsync->display_id;
                $post_json_data = [
                    "Name" => $scompany_notsync->name,
                    "DisplayID" => $scompany_notsync->display_id,
                    "Classification" => "Liability",
                    "Type" => "OtherLiability",
                    "Number" => substr($display, 2, 4),
                    "Description" => $scompany_notsync->name,
                    "IsActive" => true,
                    "TaxCode" => [


                        // LIVE N-T code
                        // "UID" => "f9338d31-b33f-425a-bbf7-da4bd8cd53e1",
                        //demo N-T code
                        "UID" => "488e7b93-6b28-4eb2-80f0-72a6f3ab3428",
                    ],
                    "ParentAccount" => [
                        //live liabilities account
                        // "UID" => "e6d0fbfc-c7e9-4019-9684-7239019df145",

                        // demo libilities account
                        "UID" =>  " ae5ed194-e01c-473f-ae84-5c85fcae4236",
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
            $contact_list1 = json_decode($get_data['response'], true);
            // dd($supplier_list1);

            foreach ($db_suppliercompany as $supplier_comp) {
                foreach ($contact_list1['Items'] as $slist) {


                    if (isset($slist['DisplayID']) && isset($supplier_comp->display_id) && $supplier_comp->display_id == $slist['DisplayID']) {
                        // dump('updation code');
                        \DB::table('supplier_companies')->where('id', $supplier_comp->id)->update([
                            'ac_uid' => $slist['UID'],
                            'account_id' => $slist['DisplayID'],
                            'coa_status' => 1
                        ]);
                        // dump('update');
                    }
                }
            }
            // dd('synced all');
            //  dump($sync);
            //  dd($not_sync);

            \Session::flash('success', 'Supplier COA Synced');
            // return redirect()->back();
        }
    }
    // public function syncSupplierAccount()
    // {

    //     $db_suppliercompany = SupplierCompany::get();

    //     if (coupon_status() == false) {
    //         return redirect('/myob');
    //     }
    //     $myob = new MyobCurl;
    //     $token = myobtokens::find(1)->access_token;
    //     $account_id = '';
    //     $acc_uid = '';
    //     $get_data =     $myob->FileGetContents(company_uri() . 'GeneralLedger/Account', 'get', '', $token);
    //     $contact_list = json_decode($get_data['response'], true);

    //     foreach ($contact_list as $suppliers) {
    //         if (is_array($suppliers) || is_object($suppliers)) {
    //             foreach ($suppliers as $supplier) {
    //                 foreach ($db_suppliercompany as $scompany) {
    //                     if ($scompany->account_id != $supplier['DisplayID']) {
    //                         $display = $scompany->display_id;


    //                         $post_json_data = [
    //                             "Name" => $scompany->name,
    //                             "DisplayID" => $display,
    //                             "Classification" => "Liability",
    //                             "Type" => "OtherLiability",
    //                             "Number" => substr($display, 2, 4),
    //                             "Description" => $scompany->name,
    //                             "IsActive" => true,
    //                             "TaxCode" => [
    //                                 "UID" => "488e7b93-6b28-4eb2-80f0-72a6f3ab3428",
    //                             ],
    //                             "ParentAccount" => [
    //                                 "UID" => "ae5ed194-e01c-473f-ae84-5c85fcae4236",
    //                             ]
    //                         ];


    //                         $post_data =     $myob->FileGetContents(
    //                             company_uri() . 'GeneralLedger/Account',
    //                             'post',
    //                             json_encode($post_json_data),
    //                             $token
    //                         );
    //                         $account_id = $supplier['DisplayID'];
    //                         $acc_uid = $supplier['UID'];

    //                         dump($post_data);


    //                         \DB::table('supplier_companies')->where('account_id', $scompany->account_id)->update([
    //                             'account_id' => $account_id,
    //                             'ac_uid' => $acc_uid
    //                         ]);
    //                     }
    //                     // else{
    //                     //     Session::flash('success', 'Supplier Account synced');
    //                     //     return redirect('/sync-to-myob');
    //                     // }
    //                 }
    //             }
    //         }
    //     }
    //     // $db_suppliercompany = SupplierCompany::get();

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

    //     //                     \DB::table('supplier_companies')->where('account_id', $scompany->account_id)->update([
    //     //                         'ac_uid' => $ac_uid


    //     //                     ]);
    //     //                 }
    //     //             }
    //     //         }
    //     //     }
    //     // }
    // }




    public function destroy(Request $request, $id)
    {
    }
}
