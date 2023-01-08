<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SupplierCompany;
use App\Product;
use App\Category;
use DB;
use App\PurchaseRecord;
use  App\Libraries\MyobCurl;
use App\myobtokens;
use App\PurchaseRecordProduct;
use Session;
use App\TransactionAllocation;
use Image;
use DataTables;
use App\Exports\purchaseexport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Carbon;

class PurchaseController extends Controller

{

    public function __construct()
    {
        $this->middleware('superadmin');
    }




    public function export(Request $request)
    {


        $end_date = $start_date = null;
        $search = request()->input('search');


        if ($request->end_date != null) {
            $end_date =   Carbon::parse($request->end_date)->format('Y-m-d');
        }
        if ($request->start_date != null) {
            $start_date = Carbon::parse($request->start_date)->format('Y-m-d');
        }
        return Excel::download(new purchaseexport($search, $start_date, $end_date), 'Records.xlsx');
    }
    public function index(Request $request)
    {
        $companies = \DB::table('supplier_companies')->select('id', 'name')->get();

        if ($request->ajax()) {
            $type = request()->input('type');
            if ($type == 'Custom Range') {
                $from = Carbon::parse(request()->input('start_date'))->format('Y-m-d');
                $to = Carbon::parse(request()->input('end_date'))->format('Y-m-d');
                $records = PurchaseRecord::whereBetween('datetime', [$from, $to])
                    ->when(request('company') != '', function ($q) {
                        return $q->where('supplier_company_id', request('company'));
                    });
            } elseif ($type == 'Today') {
                $records = PurchaseRecord::whereRaw('Date(datetime) = CURDATE()')
                    ->when(request('company') != '', function ($q) {
                        return $q->where('supplier_company_id', request('company'));
                    });
            } elseif ($type == 'Yesterday') {
                $yesterday = date("Y-m-d", strtotime('-1 days'));
                $records = PurchaseRecord::where('datetime', $yesterday)
                    ->when(request('company') != '', function ($q) {
                        return $q->where('supplier_company_id', request('company'));
                    });
            } elseif ($type == 'Last 7 Days') {
                $date = Carbon::today()->subDays(7);
                $records = PurchaseRecord::where('datetime', '>=', $date)
                    ->when(request('company') != '', function ($q) {
                        return $q->where('supplier_company_id', request('company'));
                    });
            } elseif ($type == 'Last 30 Days') {
                $date = Carbon::today()->subDays(30);
                $records = PurchaseRecord::where('datetime', '>=', $date)
                    ->when(request('company') != '', function ($q) {
                        return $q->where('supplier_company_id', request('company'));
                    });
            } elseif ($type == 'This Month') {
                $records = PurchaseRecord::where('datetime', '>', Carbon::now()->startOfMonth())->where('datetime', '<', Carbon::now()->endOfMonth())
                    ->when(request('company') != '', function ($q) {
                        return $q->where('supplier_company_id', request('company'));
                    });
            } elseif ($type == 'Last Month') {
                $records = PurchaseRecord::where('datetime', '=', Carbon::now()->subMonth()->month)
                    ->when(request('company') != '', function ($q) {
                        return $q->where('supplier_company_id', request('company'));
                    });
            } else {
                $records = PurchaseRecord::when(request()->input('company') != '', function ($q) {
                    return $q->where('supplier_company_id', request('company'));
                });
            }

            $records->join('categories', 'purchase_records.category_id', '=', 'categories.id')
                ->join('supplier_companies', 'purchase_records.supplier_company_id', '=', 'supplier_companies.id')
                ->select([
                    'purchase_records.*',
                    DB::raw('categories.name as category_name'),
                    DB::raw('supplier_companies.name as fuelCompany_name'),
                ]);





            return Datatables::eloquent($records)
                ->addColumn('category_name', function (PurchaseRecord $record) {
                    return $record->category->name;
                })
                ->addColumn('fuelCompany_name', function (PurchaseRecord $record) {
                    return $record->fuelCompany->name;
                })
                ->addColumn('datetime', function (PurchaseRecord $record) {

                    return Carbon::parse($record->datetime)->format('d-m-Y');
                })
                ->addColumn('total_amount', function ($record) {
                    $amount = '<span >$</span>' . Thousandsep($record->total_amount);
                    return  $amount;
                })

                ->addColumn('paid_status', function (PurchaseRecord $record) {
                    if ($record->paid_status == 0) {
                        $unpaid = '<span class="badge badge-danger">unpaid</span>';
                        return  $unpaid;
                    } else {
                        $paid = '<span class="badge badge-success">paid</span>';
                        return $paid;
                    }
                })
                ->filterColumn('category_name', function ($query, $keyword) {
                    $query->whereHas('category', function ($q) use ($keyword) {
                        $q->whereRaw("categories.name like ?", ["%{$keyword}%"]);
                    });
                })

                ->filterColumn('fuelCompany_name', function ($query, $keyword) {
                    $query->whereHas('fuelCompany', function ($q) use ($keyword) {
                        $q->whereRaw("supplier_companies.name like ?", ["%{$keyword}%"]);
                    });
                })
                ->addIndexColumn()

                ->addColumn('action', function ($data) {
                    $button = '<div class="text-center"><a href="' . route('purchases.show', $data->id) . '"><button  type="button" name="edit" id="' . $data->id . '" class="btn btn-sm btn-primary">View</button></a></div>';
                    return $button;
                })
                ->rawColumns(['action', 'total_amount'])
                ->rawColumns(['action', 'paid_status', 'total_amount'])

                ->orderColumn('datetime', function ($query, $order) {
                    $query->orderBy('datetime', $order);
                })
                ->orderColumn('fuelCompany_name', function ($query, $order) {
                    $query->orderBy('supplier_companies.name', $order);
                })
                ->orderColumn('category_name', function ($query, $order) {
                    $query->orderBy('categories.name', $order);
                })
                ->orderColumn('total_amount', function ($query, $order) {
                    $query->orderBy('total_amount', $order);
                })
                ->orderColumn('paid_status', function ($query, $order) {
                    $query->orderBy('paid_status', $order);
                })

                ->smart(true)
                ->make(true);
        }


        return view('admin.purchases.index', compact('companies'));
    }

    public function updatePaymentRecord(Request $request)
    {
        $this->validate($request, [
            'payment_amount' => 'required',
            'account_id' => 'required',
            'sub_account_id' => 'required',
        ]);

        $paid_status = 1;
        $item = PurchaseRecord::where('id', $request->record_id)->first();
        $request->payment_amount;
        if ($item->total_amount >= $request->payment_amount) {

            $new_total_amount = $item->total_amount - $request->payment_amount;
            $new_paid_amount =  $item->paid_amount + $request->payment_amount;
            if ($new_total_amount == 0) {

                \DB::table('purchase_records')->where('id', $item->id)->update([
                    'paid_status' => $paid_status,
                    'paid_amount' => $new_paid_amount
                ]);
            } else {

                \DB::table('purchase_records')->where('id', $item->id)->update([
                    'paid_amount' => $new_paid_amount,
                ]);
            }

            $transaction_alloc = new TransactionAllocation();
            $transaction_alloc->sub_account_id = $request->sub_account_id;
            $transaction_alloc->purchase_record_id = $request->record_id;
            $transaction_alloc->amount = $request->payment_amount;
            $transaction_alloc->payment_date = \Carbon\Carbon::parse($request->payment_datetime)->format('Y-m-d H:i');
            $transaction_alloc->save();

            return redirect()->back()->with('success', 'Record Updated Successfully');
        } else {
            return redirect()->back()->with('success', 'Your Amount is not Sufficient');
        }
    }

    public function create()
    {
        $categories = Category::where('status', 1)->orderBy('name', 'asc')->get();
        $products = Product::where('status', 1)->get();
        $companies = SupplierCompany::orderBy('name', 'asc')->get();
        return view('admin.purchases.create', compact('companies', 'products', 'categories'));
    }

    public function submit(Request $request)
    {
        //dd($request->datetime);
        $purchaseinvoices = '';
        if ($request->has('purchaseinvoices')) {
            $arr = [];
            foreach ($request->file('purchaseinvoices') as $file) {
                $ext = $file->getClientOriginalExtension();
                if ($ext != 'pdf') {
                    $obj = Image::make($file);
                    $obj->resize(1024, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $name = str_replace(" ", "", time() . $file->getClientOriginalName());
                    $obj->save('public/uploads/purchases/' . $name);
                    array_push($arr, $name);
                } else {
                    $name = str_replace(" ", "", time() . $file->getClientOriginalName());
                    $file->move('public/uploads/purchases', $name);
                    array_push($arr, $name);
                }
            }
            $purchaseinvoices = implode("::", $arr);
        }

        switch ($request->tax_type) {
            case '1':
                $status = 'Tax Inclusive';
                break;
            case '2':
                $status = 'Tax Exclusive';
                break;
            case '3':
                $status = 'No Tax';
                break;
        }

        $latest = PurchaseRecord::orderBy('created_at', 'desc')->first();
        if ($latest) {
            $purchase_number = $latest->purchase_no;
            $no = str_replace("P", "", $purchase_number);
            $purchase_no = (int)$no + 1;
        } else {
            $purchase_no = 'P4000';
        }

        $p_no = 'P' . $purchase_no;

        $record = PurchaseRecord::create([
            'supplier_company_id' => $request->supplier_company_id,
            'category_id' => $request->category_id,
            'invoice_number' => $request->invoice_no,
            'purchase_no' => $p_no,
            'datetime' => Carbon::parse($request->datetime)->format('Y-m-d H:i'),
            'gst_status' => $status,
            'purchaseinvoices' => $purchaseinvoices,
            'total_quantity' => $request->total_calculation,
            'total_amount' => $request->total_amount,
        ]);

        $products = Product::where('status', 1)->get();
        foreach ($products as $product) {
            $qty = $request->input('product_qty_' . $product->id);
            $rate = $request->input('product_rate_' . $product->id);
            $gst = $request->input('product_gst_' . $product->id);
            $sub_amount = $request->input('subtotal_' . $product->id);
            $gst_amount = $request->input('gst_amount_' . $product->id);
            $total_amount = $request->input('total_amount_' . $product->id);

            PurchaseRecordProduct::create([
                'purchase_record_id' => $record->id,
                'product_id' => $product->id,
                'qty' => $qty,
                'rate' => $rate,
                'gst' => $gst,
                'sub_amount' => $sub_amount,
                'gst_amount' => $gst_amount,
                'total_amount' => $total_amount,
            ]);
        }

        $myob = new MyobCurl;
        //here is if myob condition
        $myob1 = DB::table('myob')->first();

        if ($myob1->status == 1) {

            $record = PurchaseRecord::find($record->id);



            $products = $record->products;

            $product_items = [];
            $product_name['account_uid'] = $record->fuelCompany->ac_uid;
            $product_name['supplier_uid'] = $record->fuelCompany->supplier_uid;

            // dd($product_name['supplier_uid']);

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
                                "UID" => "488e7b93-6b28-4eb2-80f0-72a6f3ab3428"
                            ],
                            "Item" => [
                                "UID" => $product->product->item_uid
                            ]
                        ];
                }
            }


            $date = '-';
            $due = '-';
            //   dd ( $product_name['ac_uid']);
            $product_name['items'] = $product_items;

            $product_name['billto'] = $record->fuelCompany->name;
            $product_name['co_uid'] = $record->fuelCompany->co_uid;
            $product_name['ac_uid'] = $record->fuelCompany->ac_uid;
            // dd ( $product_name['ac_uid']);
            $product_name['shipto'] = $record->fuelCompany->name;
            // dd($product_name['shipto']);
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
                "IsTaxInclusive" => false,
                "IsReportable" => false,
                "Lines" => $product_lines,

                "FreightTaxCode" => [
                    "UID" => "41fb0821-50b1-4805-bc4a-ab08ae17a49d",

                ]


            ];
            // dd($record->datetime);

            if (coupon_status() == false) {
                dump('token failed');
            }
            $token = myobtokens::find(1)->access_token;
            $post_data =     $myob->FileGetContents(
                company_uri() . 'Purchase/Bill/Item',
                'post',
                json_encode($post_json_data),
                $token
            );
            // dd($post_data);
            $final_response = json_decode($post_data['response'], true);
            $post_data['post'] = $post_json_data;
            if (empty($final_response)) {
                $post_json_data = [];
                $record->update([
                    'myob_status' => 1
                ]);
            }

            Session::flash('success', 'Purchase record added successfully.');
            return redirect('/purchases');
        } else {
            Session::flash('success', 'Purchase record added successfully. only into DB, Myob Didabled');
            return redirect()->back();
        }
    }




    public function syncPurchaseRecorduid()
    {

        $purchase_record = PurchaseRecord::get();

        if (coupon_status() == false) {
        }
        $myob = new MyobCurl;
        $token = myobtokens::find(1)->access_token;


        $record_uid = '';
        $get_data =     $myob->FileGetContents(company_uri() . 'Purchase/Bill/Item', 'get', '', $token);
        $purchase_record_list = json_decode($get_data['response'], true);

        foreach ($purchase_record_list as $pr_list) {
            if (is_array($pr_list) || is_object($pr_list)) {
                foreach ($pr_list as $list) {
                    foreach ($purchase_record as $prl) {
                        if ($prl->invoice_number === $list['Number']) {
                            $record_uid = $list['UID'];

                            DB::table('purchase_records')->where('invoice_number', $prl->invoice_number)->update([
                                'invoice_uid' => $record_uid


                            ]);

                            Session::flash('success', 'Purchase record added successfully.');
                            return redirect('/purchases');
                        }
                    }
                }
            }
        }
    }

    public function show($id)
    {
        $purchase = PurchaseRecord::find($id);
        return view('admin.purchases.show', compact('purchase'));
    }

    public function edit($id)
    {
        $purchase = PurchaseRecord::find($id);
        $categories = Category::where('status', 1)->orderBy('name', 'asc')->get();
        $companies = SupplierCompany::orderBy('name', 'asc')->get();
        return view('admin.purchases.edit', compact('companies', 'categories', 'purchase'));
    }

    public function update(Request $request)
    {
        $record = PurchaseRecord::find($request->purchase_id);
        $purchaseinvoices = $record->purchaseinvoices;
        if ($request->has('purchaseinvoices')) {
            $arr = explode("::", $purchaseinvoices);
            foreach ($request->file('purchaseinvoices') as $file) {
                $obj = Image::make($file);
                $obj->resize(1024, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $name = str_replace(" ", "", time() . $file->getClientOriginalName());
                $obj->save('public/uploads/purchases/' . $name);
                array_push($arr, $name);
            }
            $purchaseinvoices = implode("::", $arr);
        }

        switch ($request->tax_type) {
            case '1':
                $status = 'Tax Inclusive';
                break;
            case '2':
                $status = 'Tax Exclusive';
                break;
            case '3':
                $status = 'No Tax';
                break;
        }

        $record->update([
            'supplier_company_id' => $request->supplier_company_id,
            'category_id' => $request->category_id,
            'invoice_number' => $request->invoice_no,
            'datetime' => \Carbon\Carbon::parse($request->datetime)->format('Y-m-d H:i'),
            'gst_status' => $status,
            'purchaseinvoices' => $purchaseinvoices,
            'total_quantity' => $request->total_calculation,
            'total_amount' => $request->total_amount,
        ]);

        foreach ($record->products as $product) {
            $qty = $request->input('product_qty_' . $product->product_id);
            $rate = $request->input('product_rate_' . $product->product_id);
            $gst = $request->input('product_gst_' . $product->product_id);
            $sub_amount = $request->input('subtotal_' . $product->product_id);
            $gst_amount = $request->input('gst_amount_' . $product->product_id);
            $total_amount = $request->input('total_amount_' . $product->product_id);

            $product->update([
                'qty' => $qty,
                'rate' => $rate,
                'gst' => $gst,
                'sub_amount' => $sub_amount,
                'gst_amount' => $gst_amount,
                'total_amount' => $total_amount,
            ]);
        }

        Session::flash('success', 'Purchase record updated successfully.');
        return redirect('/purchases');
    }

    public function syncPurchaseuid()
    {

        $purchase_record_list = [];
        $invoice_data = [];
        $purchase_record = PurchaseRecord::get();

        if (coupon_status() == false) {
        }
        $myob = new MyobCurl;
        $token = myobtokens::find(1)->access_token;


        $record_uid = '';
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



        foreach ($purchase_record_list as $record) {
            foreach ($record['Items'] as $bill) {
                $invoice_data[] = ['UID' => $bill['UID'], 'Number' => $bill['Number']];

                DB::table('purchase_records')->where('invoice_number', $bill['Number'])->update([
                    'invoice_uid' => $bill['UID']


                ]);
            }
        }
    }




    public function destroy($id)
    {
        $purchase = PurchaseRecord::find($id);

        foreach ($purchase->products as $product) {
            $product->delete();
        }

        foreach ($purchase->transactionHistory as $history) {
            $history->delete();
        }

        $images = explode("::", $purchase->purchaseinvoices);
        if ($purchase->purchaseinvoices) {
            foreach ($images as $image) {
                if (file_exists('public/uploads/purchases/' . $image))
                    unlink('public/uploads/purchases/' . $image);
            }
        }
        
        if (coupon_status() == false) {
            return redirect('/myob');
        }
        $myob = new MyobCurl;
        $token = myobtokens::find(1)->access_token;



        $get_data =     $myob->FileGetContents(company_uri() . 'Purchase/Bill/Item', 'get', '', $token);
        $purchase_record_list = json_decode($get_data['response'], true);

        foreach ($purchase_record_list as $pr_list) {
            if (is_array($pr_list) || is_object($pr_list)) {
                foreach ($pr_list as $list) {

                    if ($purchase->invoice_number === $list['Number']) {


                        $delete_data =     $myob->FileGetContents(company_uri() . 'Purchase/Bill/Item/' . $list['UID'], 'delete', '', $token);
                       
                    }
                }
            }
        }
        
        $purchase->update([
                            'deleted_status' => 1,
                            'myob_status' => 0,
                            'invoice_uid' => ''
                        ]);
                        
                         $purchase->delete();
        
       
        Session::flash('success', 'Purchase record deleted successfully.');
        return redirect('/purchases');
    }
}
