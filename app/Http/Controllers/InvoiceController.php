<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Record;
use Session;
use App\Smtp;
use App\SubAccount;
use App\InvoiceSetting;
use App\TransactionAllocation;
use PDF;
use  App\Libraries\MyobCurl;
use App\myobtokens;
use DB;
use DataTables;
use Illuminate\Support\Carbon;
use App\PurchaseRecord;
use App\Exports\invoicesexport;
use App\Exports\paidexport;
use App\Exports\unpaidexport;
use App\Exports\purchaseallInvoicesexport;
use App\Exports\purchasepaidinvoices;
use App\Exports\purchaseunpaidinvoices;
use Maatwebsite\Excel\Facades\Excel;



class InvoiceController extends Controller
{

    public function __construct()
    {
        $this->middleware('superadmin');
    }


   public function testInvoice()
    {
		return view('admin.invoices.test_invoice');
    }
     public function exportallinvoices(Request $request)
    {


        $end_date=$start_date=null;
        $search = request()->input('search');

        if ($request->end_date != null){
    $end_date =   Carbon::parse($request->end_date)->format('Y-m-d');
        }
        if ($request->start_date != null){
    $start_date = Carbon::parse($request->start_date)->format('Y-m-d');
        }
    return Excel::download(new invoicesexport($search,$start_date, $end_date ), 'invoice.xlsx');


    }

    public function paidexport(Request $request)
    {



        $end_date=$start_date=null;
        $search = request()->input('search');


        if ($request->end_date != null){
    $end_date =   Carbon::parse($request->end_date)->format('Y-m-d');
        }
        if ($request->start_date != null){
    $start_date = Carbon::parse($request->start_date)->format('Y-m-d');
        }
    return Excel::download(new paidexport($search,$start_date, $end_date ), 'paidexport.xlsx');


    }
    public function unpaidexport(Request $request)
    {



        $end_date=$start_date=null;
        $search = request()->input('search');


        if ($request->end_date != null){
    $end_date =   Carbon::parse($request->end_date)->format('Y-m-d');
        }
        if ($request->start_date != null){
    $start_date = Carbon::parse($request->start_date)->format('Y-m-d');
        }
    return Excel::download(new unpaidexport($search,$start_date, $end_date ), 'unpaidexport.xlsx');


    }
    public function purchaseallInvoicesexport(Request $request)
    {



        $end_date=$start_date=null;
        $search = request()->input('search');


        if ($request->end_date != null){
    $end_date =   Carbon::parse($request->end_date)->format('Y-m-d');
        }
        if ($request->start_date != null){
    $start_date = Carbon::parse($request->start_date)->format('Y-m-d');
        }
    return Excel::download(new purchaseallInvoicesexport($search,$start_date, $end_date ), 'purchaseallInvoicesexport.xlsx');


    }
    public function purchasepaidinvoicesex(Request $request)
    {



        $end_date=$start_date=null;
        $search = request()->input('search');


        if ($request->end_date != null){
    $end_date =   Carbon::parse($request->end_date)->format('Y-m-d');
        }
        if ($request->start_date != null){
    $start_date = Carbon::parse($request->start_date)->format('Y-m-d');
        }
    return Excel::download(new purchasepaidinvoices($search,$start_date, $end_date ), 'purchasepaidinvoices.xlsx');


    }
    public function purchaseunpaidinvoicesex(Request $request)
    {



        $end_date=$start_date=null;
        $search = request()->input('search');


        if ($request->end_date != null){
    $end_date =   Carbon::parse($request->end_date)->format('Y-m-d');
        }
        if ($request->start_date != null){
    $start_date = Carbon::parse($request->start_date)->format('Y-m-d');
        }
    return Excel::download(new purchaseunpaidinvoices($search,$start_date, $end_date ), 'purchaseunpaidinvoices.xlsx');


    }
//14aug abdul
  	public function allInvoices(Request $request)
    {
        if ($request->ajax()) {
            $start_date = request()->input('start_date');
            $end_date = request()->input('end_date');
            $search = request()->input('search');
              $data = Record::
                   join('companies','records.company_id','=','companies.id')
                   ->join('users','records.user_id','=','users.id')
                   ->join('sub_companies','records.sub_company_id','=','sub_companies.id')
                   ->select(['records.*',
                   DB::raw('companies.name as company_name'),
                   DB::raw('sub_companies.name as subCompany_name'),
                   DB::raw('users.name as username')])
                   ->where('records.status', 1 && 'records.deleted_status', 0);

                   if ($start_date != null  && $end_date != null) {
                    $start_date =   Carbon::parse($start_date)->format('Y-m-d');
                    $end_date =   Carbon::parse($end_date)->format('Y-m-d');

                    $data->whereBetween('datetime', array($start_date, $end_date));
                    }

                    //abdul
        //             $local_db = new Record;

        // if (coupon_status() == false) {
        //     return redirect('/myob');
        // }
        // $myob1 = \DB::table('myob')->first();
        // // dump($myob1->status);
        // if ($myob1->status == 1) {
        //     $myob = new MyobCurl;
        //     $token = myobtokens::find(1)->access_token;

        //  $get_data = $myob->FileGetContents(company_uri() . 'Sale/Invoice/Item', 'get', '', $token);
        // $sale_record_list_final = json_decode($get_data['response'], true);


        // $sale_record_list[]['Items'] = $sale_record_list_final['Items'];

        // $total_pages = $sale_record_list_final['Count'] / 400;
        // for ($x = 1; $x <= $total_pages; $x++) {
        //     $page_count = $x * 400;
        //     $get_data =     $myob->FileGetContents(company_uri() . 'Sale/Invoice/Item?$top=400&$skip=' . $page_count, 'get', '', $token);
        //     $sale_record_list_final_2 = json_decode($get_data['response'], true);
        //     $sale_record_list[]['Items'] = $sale_record_list_final_2['Items'];
        // }
        // // dd($sale_record_list);

        //     foreach ($local_db as $local) {
        //         foreach ($sale_record_list as $item) {
        //             if (isset($item['Number']) && isset($local->invoice_number) && $local->invoice_number == $item['Number']) {
        //                 $paid_amount = $item['TotalAmount'] - $item['BalanceDueAmount'];
        //                 \DB::table('records')->where('id', $local->id)->update([
        //                     'paid_status' => $item['Status'],
        //                     'paid_amount' => $paid_amount,

        //                 ]);
        //             }
        //         }
        //     }

        // }
                    // endabdul



            return Datatables::eloquent($data)
                ->addColumn('username', function (Record $record) {
                    return $record->user->name;
                })
                ->addColumn('company', function (Record $record) {
                    return $record->subCompany->company->name;
                })
                ->addColumn('subcompany', function (Record $record) {
                    return $record->subCompany->name;
                })
                ->addColumn('datetime', function ( Record $record) {

                    return Carbon::parse($record->datetime)->format('d-m-Y');

                })
                 ->addColumn('total_amount', function ( Record $record) {

                    $amount = '<span >$</span>'.Thousandsep($record->total_amount);
                    return  $amount;

                })

                ->addColumn('email', function (Record $record) {
                    if ($record->email_status == 1){
                        return 'Sent';
                    }
                    return 'Not Sent';
                })
                ->addColumn('paid_status', function (Record $record) {
                    if ($record->paid_status == 0){
                        $unpaid = '<span class="badge badge-danger">unpaid</span>';
                        return  $unpaid;
                    }else{
                    $paid = '<span class="badge badge-success">paid</span>';
                    return $paid;
                    }
                })


                ->filterColumn('company', function($query, $keyword) {
                    $query->whereHas('company', function($q) use ($keyword){
                        $q->whereRaw("companies.name like ?", ["%{$keyword}%"]);
                    });
                })

                ->filterColumn('subcompany', function($query, $keyword) {
                    $query->whereHas('subCompany', function($q) use ($keyword){
                        $q->whereRaw("sub_companies.name like ?", ["%{$keyword}%"]);
                    });
                })
                ->filterColumn('username', function($query, $keyword) {
                $query->whereHas('user', function($q) use ($keyword){
                    $q->whereRaw("users.username like ?", ["%{$keyword}%"]);
                });
            })

            ->filterColumn('paid_status', function($query, $keyword) {

                $keywords = 3;
                if ($keyword == 'paid'){
                    $keywords = 1;
                } else if($keyword == 'unpaid'){
                    $keywords = 0;
                }
                if ($keywords != 3){
                    $query->whereRaw("records.paid_status like ?", ["%{$keywords}%"]);
                }
            })

                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                 $button = '<div class="text-center"> <a href="'.route('invoice.details',$data->id).'"><button  type="button" name="view" id="' . $data->id . '" class="btn btn-sm btn-primary">View</button></a></div>';
                 return $button;
             })
                ->rawColumns(['action','paid_status','total_amount'])

                ->orderColumn('datetime', function ($query, $order) {
                     $query->orderBy('datetime', $order);
                 })
                 ->orderColumn('username', function ($query, $order) {
                     $query->orderBy('users.username', $order);
                 })
                   ->orderColumn('company', function ($query, $order) {
                     $query->orderBy('companies.name', $order);
                 })
                 ->orderColumn('subcompany', function ($query, $order) {
                     $query->orderBy('sub_companies.name', $order);
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


        return view('admin.invoices.all_invoices');

}



        public function purchaseallInvoices(Request $request)
        {
            $companies = \DB::table('supplier_companies')->select('id','name')->get();

               if ($request->ajax()) {

                $type = request()->input('type');
            if ($type == 'Custom Range') {
                $from = Carbon::parse(request()->input('start_date'))->format('Y-m-d');
                  $to = Carbon::parse(request()->input('end_date'))->format('Y-m-d');
                  $records = PurchaseRecord::whereBetween('datetime', [$from, $to])
                          ->when(request('company') != '', function ($q) {
                            return $q->where('supplier_company_id', request('company'));
                          })
                          ;
            } elseif ($type == 'Today') {
                $records = PurchaseRecord::whereRaw('Date(datetime) = CURDATE()')
                          ->when(request('company') != '', function ($q) {
                            return $q->where('supplier_company_id', request('company'));
                          })
                          ;
            } elseif ($type == 'Yesterday') {
                  $yesterday = date("Y-m-d", strtotime( '-1 days' ));
                $records = PurchaseRecord::where('datetime', $yesterday)
                          ->when(request('company') != '', function ($q) {
                            return $q->where('supplier_company_id', request('company'));
                          })
                          ;
            } elseif ($type == 'Last 7 Days') {
                  $date = Carbon::today()->subDays(7);
                $records = PurchaseRecord::where('datetime', '>=', $date)
                          ->when(request('company') != '', function ($q) {
                            return $q->where('supplier_company_id', request('company'));
                          })
                          ;
            } elseif ($type == 'Last 30 Days') {
                  $date = Carbon::today()->subDays(30);
                $records = PurchaseRecord::where('datetime', '>=', $date)
                          ->when(request('company') != '', function ($q) {
                            return $q->where('supplier_company_id', request('company'));
                          })
                          ;
            } elseif ($type == 'This Month') {
                $records = PurchaseRecord::where('datetime', '>', Carbon::now()->startOfMonth()) ->where('datetime', '<', Carbon::now()->endOfMonth())
                          ->when(request('company') != '', function ($q) {
                            return $q->where('supplier_company_id', request('company'));
                          })
                          ;
            } elseif ($type == 'Last Month') {
                $records = PurchaseRecord::where('datetime', '=', Carbon::now()->subMonth()->month)
                          ->when(request('company') != '', function ($q) {
                            return $q->where('supplier_company_id', request('company'));
                          })
                          ;
            } else {
                $records = PurchaseRecord::
                          when(request()->input('company') != '', function ($q) {
                            return $q->where('supplier_company_id', request('company'));
                          })
                          ;
            }

           $records->join('categories','purchase_records.category_id','=','categories.id')
            ->join('supplier_companies','purchase_records.supplier_company_id','=','supplier_companies.id')
            ->select(['purchase_records.*',
                   DB::raw('categories.name as category_name'),
                   DB::raw('supplier_companies.name as fuelCompany_name'),
                   ])
                   ->where('purchase_records.status', 1 && 'purchase_records.deleted_status', 0);







                   return Datatables::eloquent($records)
                       ->addColumn('category_name', function (PurchaseRecord $record) {
                           return $record->category->name;
                       })
                       ->addColumn('fuelCompany_name', function (PurchaseRecord $record) {
                           return $record->fuelCompany->name;
                       })
                         ->addColumn('total_amount', function ( PurchaseRecord $record) {

                            $amount = '<span >$</span>'.Thousandsep($record->total_amount);
                            return  $amount;

                         })
                       ->addColumn('datetime', function (PurchaseRecord $record) {

                        return Carbon::parse($record->datetime)->format('d-m-Y');


                    })
                    ->addColumn('paid_status', function (PurchaseRecord $record) {
                        if ($record->paid_status == 0){
                            $unpaid = '<span class="badge badge-danger">unpaid</span>';
                            return  $unpaid;
                        }else{
                        $paid = '<span class="badge badge-success">paid</span>';
                        return $paid;
                        }
                    })
                    ->filterColumn('category_name', function($query, $keyword) {
                        $query->whereHas('category', function($q) use ($keyword){
                            $q->whereRaw("categories.name like ?", ["%{$keyword}%"]);
                        });
                        })

                        ->filterColumn('fuelCompany_name', function($query, $keyword) {
                            $query->whereHas('fuelCompany', function($q) use ($keyword){
                                $q->whereRaw("supplier_companies.name like ?", ["%{$keyword}%"]);
                            });
                        })
                       ->addIndexColumn()
                       ->addColumn('action', function ($data) {
                        $button = '<div class="text-center"><a href="'.route('purchases.show',$data->id).'"><button  type="button" name="edit" id="' . $data->id . '" class="btn btn-sm btn-primary">View</button></a></div>';
                        return $button;
                    })
                       ->rawColumns(['action','paid_status','total_amount'])
                        ->rawColumns(['action','paid_status','total_amount'])

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
                       ->make(true);


               }


               return view('admin.invoices.purchase_all_invoices',compact('companies'));
           }



   public function invoiceFollowsNote(Request $request)
    {
      	$record = Record::find($request->record_id);
        $record->follows_note = $request->note;
        $record->update();
		return back()->with('success','Note Added Successfully.');
    }

    public function allInvoicesFilter(Request $request)
    {
    	 $type = $request->type;
      	if ($type == 'Custom Range') {
            $from = Carbon::parse($request->from)->format('Y-m-d');
          	$to = Carbon::parse($request->to)->format('Y-m-d');

          	$records = Record::whereBetween('datetime', [$from, $to])->where('status',1)->where('deleted_status',0)->get();
        } elseif ($type == 'Today') {
        	$records = Record::whereRaw('Date(datetime) = CURDATE()')->where('status',1)->where('deleted_status',0)->get();
        } elseif ($type == 'Yesterday') {
          	$yesterday = date("Y-m-d", strtotime( '-1 days' ));
        	$records = Record::where('datetime', $yesterday)->where('status',1)->where('deleted_status',0)->get();
        } elseif ($type == 'Last 7 Days') {
          	$date = Carbon::today()->subDays(7);
        	$records = Record::where('datetime', '>=', $date)->where('status',1)->where('deleted_status',0)->get();
        } elseif ($type == 'Last 30 Days') {
          	$date = Carbon::today()->subDays(30);
        	$records = Record::where('datetime', '>=', $date)->where('status',1)->where('deleted_status',0)->get();
        } elseif ($type == 'This Month') {
        	$records = Record::where('datetime', '>', Carbon::now()->startOfMonth()) ->where('datetime', '<', Carbon::now()->endOfMonth())->where('status',1)->where('deleted_status',0)->get();
        } elseif ($type == 'Last Month') {
        	$records = Record::where('datetime', '=', Carbon::now()->subMonth()->month)->where('status',1)->where('deleted_status',0)->get();
        }
		return view('admin.invoices.all_invoices_filter', compact('records'));
    }


    public function paidInvoices(Request $request)
    {
        if ($request->ajax()) {

            // $start_date = request()->input('start_date');
            // $end_date = request()->input('end_date');


            // if ($start_date != null  && $end_date != null) {
            //     $start_date =   Carbon::parse($start_date)->format('Y-m-d');
            //     $end_date =   Carbon::parse($end_date)->format('Y-m-d');

            //     $data = Record::where('status', 1 && 'deleted_status', 0 )->whereBetween('datetime', array($start_date, $end_date));
            // } else {
            //     $data = Record::where('status', 1)->where('deleted_status', 0)->where('paid_status', 1);
            // }
            // $data = Record::

            //       join('companies','records.company_id','=','companies.id')
            //       ->join('users','records.user_id','=','users.id')
            //       ->join('sub_companies','records.sub_company_id','=','sub_companies.id')
            //       ->select(['records.*',
            //       DB::raw('companies.name as company_name'),
            //       DB::raw('sub_companies.name as subCompany_name'),
            //       DB::raw('users.name as username')])
            //       ->where('records.status', 1 && 'records.deleted_status', 0)->where('records.paid_status',1);
              $start_date = request()->input('start_date');
            $end_date = request()->input('end_date');
            $search = request()->input('search');


              $data = Record::

                   join('companies','records.company_id','=','companies.id')
                   ->join('users','records.user_id','=','users.id')
                   ->join('sub_companies','records.sub_company_id','=','sub_companies.id')
                   ->select(['records.*',
                   DB::raw('companies.name as company_name'),
                   DB::raw('sub_companies.name as subCompany_name'),
                   DB::raw('users.name as username')])
                   ->where('records.status', 1 && 'records.deleted_status', 0)->where('records.paid_status',1);
                // dd($data);
                   if ($start_date != null  && $end_date != null) {
                    $start_date =   Carbon::parse($start_date)->format('Y-m-d');
                    $end_date =   Carbon::parse($end_date)->format('Y-m-d');

                    $data->whereBetween('datetime', array($start_date, $end_date));
                    }


            return Datatables::eloquent($data)
                ->addColumn('username', function (Record $record) {
                    return $record->user->name;
                })
                ->addColumn('company', function (Record $record) {
                    return $record->subCompany->company->name;
                })
                ->addColumn('datetime', function (Record $record) {

                    return Carbon::parse($record->datetime)->format('d-m-Y');


                })
                 ->addColumn('total_amount', function ( Record $record) {

                    $amount = '<span >$</span>'.Thousandsep($record->total_amount);
                    return  $amount;

                })
                ->addColumn('subcompany', function (Record $record) {
                    return $record->subCompany->name;
                })
                ->addColumn('email', function (Record $record) {
                    if ($record->email_status == 1){
                        return 'Sent';
                    }
                    return 'Not Sent';
                })
                ->addColumn('paid_status', function (Record $record) {
                    if ($record->paid_status == 0){
                        $unpaid = '<span class="badge badge-danger">unpaid</span>';
                        return  $unpaid;
                    }else{
                    $paid = '<span class="badge badge-success">paid</span>';
                    return $paid;
                    }
                })

                ->filterColumn('company', function($query, $keyword) {
                    $query->whereHas('company', function($q) use ($keyword){
                        $q->whereRaw("companies.name like ?", ["%{$keyword}%"]);
                    });
                })

                ->filterColumn('subcompany', function($query, $keyword) {
                    $query->whereHas('subCompany', function($q) use ($keyword){
                        $q->whereRaw("sub_companies.name like ?", ["%{$keyword}%"]);
                    });
                })
                ->filterColumn('username', function($query, $keyword) {
                $query->whereHas('user', function($q) use ($keyword){
                    $q->whereRaw("users.username like ?", ["%{$keyword}%"]);
                });
            })

            ->filterColumn('paid_status', function($query, $keyword) {

                $keywords = 3;
                if ($keyword == 'paid'){
                    $keywords = 1;
                } else if($keyword == 'unpaid'){
                    $keywords = 0;
                }
                if ($keywords != 3){
                    $query->whereRaw("records.paid_status like ?", ["%{$keywords}%"]);
                }
            })
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $button = '<div class="text-center"><a href="'.route('invoice.details',$data->id).'"><button  type="button" name="edit" id="' . $data->id . '" class="btn btn-sm btn-primary">View</button></a></div>';
                    return $button;

             })
                ->rawColumns(['action','paid_status','total_amount'])

                ->orderColumn('datetime', function ($query, $order) {
                     $query->orderBy('datetime', $order);
                 })
                 ->orderColumn('username', function ($query, $order) {
                     $query->orderBy('users.username', $order);
                 })
                   ->orderColumn('company', function ($query, $order) {
                     $query->orderBy('companies.name', $order);
                 })
                 ->orderColumn('subcompany', function ($query, $order) {
                     $query->orderBy('sub_companies.name', $order);
                 })

                  ->orderColumn('total_amount', function ($query, $order) {
                     $query->orderBy('total_amount', $order);
                 })
                 ->orderColumn('paid_status', function ($query, $order) {
                     $query->orderBy('paid_status', $order);
                 })

                ->make(true);


        }


        return view('admin.invoices.paid_invoices');
    }

    public function purchasepaidInvoices(Request $request)
    {

        $companies = \DB::table('supplier_companies')->select('id','name')->get();

               if ($request->ajax()) {
                $type = request()->input('type');
                if ($type == 'Custom Range') {
                    $from = Carbon::parse(request()->input('start_date'))->format('Y-m-d');
                      $to = Carbon::parse(request()->input('end_date'))->format('Y-m-d');
                      $records = PurchaseRecord::whereBetween('datetime', [$from, $to])
                              ->when(request('company') != '', function ($q) {
                                return $q->where('supplier_company_id', request('company'));
                              })
                              ;
                } elseif ($type == 'Today') {
                    $records = PurchaseRecord::whereRaw('Date(datetime) = CURDATE()')
                              ->when(request('company') != '', function ($q) {
                                return $q->where('supplier_company_id', request('company'));
                              })
                              ;
                } elseif ($type == 'Yesterday') {
                      $yesterday = date("Y-m-d", strtotime( '-1 days' ));
                    $records = PurchaseRecord::where('datetime', $yesterday)
                              ->when(request('company') != '', function ($q) {
                                return $q->where('supplier_company_id', request('company'));
                              })
                              ;
                } elseif ($type == 'Last 7 Days') {
                      $date = Carbon::today()->subDays(7);
                    $records = PurchaseRecord::where('datetime', '>=', $date)
                              ->when(request('company') != '', function ($q) {
                                return $q->where('supplier_company_id', request('company'));
                              })
                              ;
                } elseif ($type == 'Last 30 Days') {
                      $date = Carbon::today()->subDays(30);
                    $records = PurchaseRecord::where('datetime', '>=', $date)
                              ->when(request('company') != '', function ($q) {
                                return $q->where('supplier_company_id', request('company'));
                              })
                              ;
                } elseif ($type == 'This Month') {
                    $records = PurchaseRecord::where('datetime', '>', Carbon::now()->startOfMonth()) ->where('datetime', '<', Carbon::now()->endOfMonth())
                              ->when(request('company') != '', function ($q) {
                                return $q->where('supplier_company_id', request('company'));
                              })
                              ;
                } elseif ($type == 'Last Month') {
                    $records = PurchaseRecord::where('datetime', '=', Carbon::now()->subMonth()->month)
                              ->when(request('company') != '', function ($q) {
                                return $q->where('supplier_company_id', request('company'));
                              })
                              ;
                } else {
                    $records = PurchaseRecord::
                              when(request()->input('company') != '', function ($q) {
                                return $q->where('supplier_company_id', request('company'));
                              })
                              ;
                }

                 $records->join('categories','purchase_records.category_id','=','categories.id')
            ->join('supplier_companies','purchase_records.supplier_company_id','=','supplier_companies.id')
            ->select(['purchase_records.*',
                   DB::raw('categories.name as category_name'),
                   DB::raw('supplier_companies.name as fuelCompany_name'),
                   ])
                   ->where('purchase_records.status', 1 && 'purchase_records.deleted_status', 0)->where('purchase_records.paid_status',1);




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
                     ->addColumn('total_amount', function ( PurchaseRecord $record) {

                        $amount = '<span >$</span>'.Thousandsep($record->total_amount);
                        return  $amount;

                })

                    ->addColumn('paid_status', function (PurchaseRecord $record) {
                        if ($record->paid_status == 0){
                            $unpaid = '<span class="badge badge-danger">unpaid</span>';
                            return  $unpaid;
                        }else{
                        $paid = '<span class="badge badge-success">paid</span>';
                        return $paid;
                        }
                    })
                    ->filterColumn('category_name', function($query, $keyword) {
                        $query->whereHas('category', function($q) use ($keyword){
                            $q->whereRaw("categories.name like ?", ["%{$keyword}%"]);
                        });
                        })

                        ->filterColumn('fuelCompany_name', function($query, $keyword) {
                            $query->whereHas('fuelCompany', function($q) use ($keyword){
                                $q->whereRaw("supplier_companies.name like ?", ["%{$keyword}%"]);
                            });
                        })
                       ->addIndexColumn()
                       ->addColumn('action', function ($data) {
                        $button = '<div class="text-center"> <a href="'.route('purchases.show',$data->id).'"><button  type="button" name="view" id="' . $data->id . '" class="btn btn-sm btn-primary ">View</button></a></div>';
                        return $button;
                    })

                       ->rawColumns(['action','paid_status','total_amount'])

                         ->rawColumns(['action','paid_status','total_amount'])

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
                       ->make(true);

               }


               return view('admin.invoices.purchase_paid_invoices',compact('companies'));

    }

   public function paidInvoicesFilter(Request $request)
    {
    	$type = $request->type;
      	if ($type == 'Custom Range') {
            $from = Carbon::parse($request->from)->format('Y-m-d');
          	$to = Carbon::parse($request->to)->format('Y-m-d');
          	$records = Record::whereBetween('datetime', [$from, $to])->where('status',1)->where('paid_status',1)->where('deleted_status',0)->get();
        } elseif ($type == 'Today') {
        	$records = Record::whereRaw('Date(datetime) = CURDATE()')->where('status',1)->where('paid_status',1)->where('deleted_status',0)->get();
        } elseif ($type == 'Yesterday') {
          	$yesterday = date("Y-m-d", strtotime( '-1 days' ));
        	$records = Record::where('datetime', $yesterday)->where('status',1)->where('paid_status',1)->where('deleted_status',0)->get();
        } elseif ($type == 'Last 7 Days') {
          	$date = Carbon::today()->subDays(7);
        	$records = Record::where('datetime', '>=', $date)->where('status',1)->where('paid_status',1)->where('deleted_status',0)->get();
        } elseif ($type == 'Last 30 Days') {
          	$date = Carbon::today()->subDays(30);
        	$records = Record::where('datetime', '>=', $date)->where('status',1)->where('paid_status',1)->where('deleted_status',0)->get();
        } elseif ($type == 'This Month') {
        	$records = Record::where('datetime', '>', Carbon::now()->startOfMonth())->where('datetime', '<', Carbon::now()->endOfMonth())->where('paid_status',1)->where('status',1)->where('deleted_status',0)->get();
        } elseif ($type == 'Last Month') {
        	$records = Record::where('datetime', '=', Carbon::now()->subMonth()->month)->where('status',1)->where('paid_status',1)->where('deleted_status',0)->get();
        }
		return view('admin.invoices.paid_invoices_filter', compact('records'));
    }


    public function unpaidInvoices(Request $request)
    {
        if ($request->ajax()) {

            // $start_date = request()->input('start_date');
            // $end_date = request()->input('end_date');


            // if ($start_date != null  && $end_date != null) {
            //     $start_date =   Carbon::parse($start_date)->format('Y-m-d');
            //     $end_date =   Carbon::parse($end_date)->format('Y-m-d');

            //     $data = Record::where('status', 1 && 'deleted_status', 0)->whereBetween('datetime', array($start_date, $end_date));
            // } else {
            //     $data = Record::where('status', 1)->where('deleted_status', 0)->where('paid_status',0);
            // }

            // $data = Record::

            //       join('companies','records.company_id','=','companies.id')
            //       ->join('users','records.user_id','=','users.id')
            //       ->join('sub_companies','records.sub_company_id','=','sub_companies.id')
            //       ->select(['records.*',
            //       DB::raw('companies.name as company_name'),
            //       DB::raw('sub_companies.name as subCompany_name'),
            //       DB::raw('users.name as username')])
            //       ->where('records.status', 1 && 'records.deleted_status', 0 )->where('records.paid_status',0);
              $start_date = request()->input('start_date');
            $end_date = request()->input('end_date');
            $search = request()->input('search');


              $data = Record::

                   join('companies','records.company_id','=','companies.id')
                   ->join('users','records.user_id','=','users.id')
                   ->join('sub_companies','records.sub_company_id','=','sub_companies.id')
                   ->select(['records.*',
                   DB::raw('companies.name as company_name'),
                   DB::raw('sub_companies.name as subCompany_name'),
                   DB::raw('users.name as username')])
                   ->where('records.status', 1 && 'records.deleted_status', 0)->where('records.paid_status',0);

                   if ($start_date != null  && $end_date != null) {
                    $start_date =   Carbon::parse($start_date)->format('Y-m-d');
                    $end_date =   Carbon::parse($end_date)->format('Y-m-d');

                    $data->whereBetween('datetime', array($start_date, $end_date));
                    }

            return Datatables::eloquent($data)
                ->addColumn('username', function (Record $record) {
                    return $record->user->name;
                })
                ->addColumn('company', function (Record $record) {
                    return $record->subCompany->company->name;
                })
                ->addColumn('subcompany', function (Record $record) {
                    return $record->subCompany->name;
                })
                ->addColumn('datetime', function (Record $record) {

                    return Carbon::parse($record->datetime)->format('d-m-Y');


                })
                 ->addColumn('total_amount', function ( Record $record) {

                    $amount = '<span >$</span>'.Thousandsep($record->total_amount);
                    return  $amount;

                })
                ->addColumn('email', function (Record $record) {
                    if ($record->email_status == 1){
                        return 'Sent';
                    }
                    return 'Not Sent';
                })
                ->addColumn('paid_status', function (Record $record) {
                    if ($record->paid_status == 0){
                        $unpaid = '<span class="badge badge-danger">unpaid</span>';
                        return  $unpaid;
                    }else{
                    $paid = '<span class="badge badge-success">paid</span>';
                    return $paid;
                    }
                })
                ->filterColumn('company', function($query, $keyword) {
                    $query->whereHas('company', function($q) use ($keyword){
                        $q->whereRaw("companies.name like ?", ["%{$keyword}%"]);
                    });
                })

                ->filterColumn('subcompany', function($query, $keyword) {
                    $query->whereHas('subCompany', function($q) use ($keyword){
                        $q->whereRaw("sub_companies.name like ?", ["%{$keyword}%"]);
                    });
                })
                ->filterColumn('username', function($query, $keyword) {
                $query->whereHas('user', function($q) use ($keyword){
                    $q->whereRaw("users.username like ?", ["%{$keyword}%"]);
                });
            })

            ->filterColumn('paid_status', function($query, $keyword) {

                $keywords = 3;
                if ($keyword == 'paid'){
                    $keywords = 1;
                } else if($keyword == 'unpaid'){
                    $keywords = 0;
                }
                if ($keywords != 3){
                    $query->whereRaw("records.paid_status like ?", ["%{$keywords}%"]);
                }
            })
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                 $button = '<div class="text-center"> <a href="'.route('invoice.details',$data->id).'"><button  type="button" name="view" id="' . $data->id . '" class="btn btn-sm btn-primary">View</button></a></div>';
                 return $button;
             })
                ->rawColumns(['action','paid_status','total_amount'])


                ->orderColumn('datetime', function ($query, $order) {
                     $query->orderBy('datetime', $order);
                 })
                 ->orderColumn('username', function ($query, $order) {
                     $query->orderBy('users.username', $order);
                 })
                   ->orderColumn('company', function ($query, $order) {
                     $query->orderBy('companies.name', $order);
                 })
                 ->orderColumn('subcompany', function ($query, $order) {
                     $query->orderBy('sub_companies.name', $order);
                 })

                  ->orderColumn('total_amount', function ($query, $order) {
                     $query->orderBy('total_amount', $order);
                 })

                ->make(true);


        }


        return view('admin.invoices.unpaid_invoices');
    }
  	public function purchaseunpaidInvoices(Request $request)
    {
        $companies = \DB::table('supplier_companies')->select('id','name')->get();

        if ($request->ajax()) {
            $type = request()->input('type');
            if ($type == 'Custom Range') {
                $from = Carbon::parse(request()->input('start_date'))->format('Y-m-d');
                  $to = Carbon::parse(request()->input('end_date'))->format('Y-m-d');
                  $records = PurchaseRecord::whereBetween('datetime', [$from, $to])
                          ->when(request('company') != '', function ($q) {
                            return $q->where('supplier_company_id', request('company'));
                          })
                          ;
            } elseif ($type == 'Today') {
                $records = PurchaseRecord::whereRaw('Date(datetime) = CURDATE()')
                          ->when(request('company') != '', function ($q) {
                            return $q->where('supplier_company_id', request('company'));
                          })
                          ;
            } elseif ($type == 'Yesterday') {
                  $yesterday = date("Y-m-d", strtotime( '-1 days' ));
                $records = PurchaseRecord::where('datetime', $yesterday)
                          ->when(request('company') != '', function ($q) {
                            return $q->where('supplier_company_id', request('company'));
                          })
                          ;
            } elseif ($type == 'Last 7 Days') {
                  $date = Carbon::today()->subDays(7);
                $records = PurchaseRecord::where('datetime', '>=', $date)
                          ->when(request('company') != '', function ($q) {
                            return $q->where('supplier_company_id', request('company'));
                          })
                          ;
            } elseif ($type == 'Last 30 Days') {
                  $date = Carbon::today()->subDays(30);
                $records = PurchaseRecord::where('datetime', '>=', $date)
                          ->when(request('company') != '', function ($q) {
                            return $q->where('supplier_company_id', request('company'));
                          })
                          ;
            } elseif ($type == 'This Month') {
                $records = PurchaseRecord::where('datetime', '>', Carbon::now()->startOfMonth()) ->where('datetime', '<', Carbon::now()->endOfMonth())
                          ->when(request('company') != '', function ($q) {
                            return $q->where('supplier_company_id', request('company'));
                          })
                          ;
            } elseif ($type == 'Last Month') {
                $records = PurchaseRecord::where('datetime', '=', Carbon::now()->subMonth()->month)
                          ->when(request('company') != '', function ($q) {
                            return $q->where('supplier_company_id', request('company'));
                          })
                          ;
            } else {
                $records = PurchaseRecord::
                          when(request()->input('company') != '', function ($q) {
                            return $q->where('supplier_company_id', request('company'));
                          })
                          ;
            }

             $records->join('categories','purchase_records.category_id','=','categories.id')
            ->join('supplier_companies','purchase_records.supplier_company_id','=','supplier_companies.id')
            ->select(['purchase_records.*',
                   DB::raw('categories.name as category_name'),
                   DB::raw('supplier_companies.name as fuelCompany_name'),
                   ])
                   ->where('purchase_records.status', 1 && 'purchase_records.deleted_status', 0)->where('purchase_records.paid_status',0);





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
                 ->addColumn('total_amount', function ( PurchaseRecord $record) {

                    $amount = '<span >$</span>'.Thousandsep($record->total_amount);
                    return  $amount;

                })
                ->addColumn('paid_status', function (PurchaseRecord $record) {
                    if ($record->paid_status == 0){
                        $unpaid = '<span class="badge badge-danger">unpaid</span>';
                        return  $unpaid;
                    }else{
                    $paid = '<span class="badge badge-success">paid</span>';
                    return $paid;
                    }
                })
                ->filterColumn('category_name', function($query, $keyword) {
                    $query->whereHas('category', function($q) use ($keyword){
                        $q->whereRaw("categories.name like ?", ["%{$keyword}%"]);
                    });
                    })

                    ->filterColumn('fuelCompany_name', function($query, $keyword) {
                        $query->whereHas('fuelCompany', function($q) use ($keyword){
                            $q->whereRaw("supplier_companies.name like ?", ["%{$keyword}%"]);
                        });
                    })
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $button = '<div class="text-center"> <a href="'.route('purchases.show',$data->id).'"><button  type="button" name="view" id="' . $data->id . '" class="btn btn-sm btn-primary">View</button></a></div>';
                    return $button;
                })

                ->rawColumns(['action','paid_status','total_amount'])

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
                ->make(true);


        }


        return view('admin.invoices.purchase_unpaid_invoices',compact('companies'));

    }

    public function unPaidInvoicesFilter(Request $request)
    {
    	$type = $request->type;
      	if ($type == 'Custom Range') {
            $from = Carbon::parse($request->from)->format('Y-m-d');
          	$to = Carbon::parse($request->to)->format('Y-m-d');
          	$records = Record::whereBetween('datetime', [$from, $to])->where('status',1)->where('paid_status',0)->where('deleted_status',0)->get();
        } elseif ($type == 'Today') {
        	$records = Record::whereRaw('Date(datetime) = CURDATE()')->where('status',1)->where('paid_status',0)->where('deleted_status',0)->get();
        } elseif ($type == 'Yesterday') {
          	$yesterday = date("Y-m-d", strtotime( '-1 days' ));
        	$records = Record::where('datetime', $yesterday)->where('status',1)->where('paid_status',0)->where('deleted_status',0)->get();
        } elseif ($type == 'Last 7 Days') {
          	$date = Carbon::today()->subDays(7);
        	$records = Record::where('datetime', '>=', $date)->where('status',1)->where('paid_status',0)->where('deleted_status',0)->get();
        } elseif ($type == 'Last 30 Days') {
          	$date = Carbon::today()->subDays(30);
        	$records = Record::where('datetime', '>=', $date)->where('status',1)->where('paid_status',0)->where('deleted_status',0)->get();
        } elseif ($type == 'This Month') {
        	$records = Record::where('datetime', '>', Carbon::now()->startOfMonth())->where('datetime', '<', Carbon::now()->endOfMonth())->where('paid_status',0)->where('status',1)->where('deleted_status',0)->get();
        } elseif ($type == 'Last Month') {
        	$records = Record::where('datetime', '=', Carbon::now()->subMonth()->month)->where('status',1)->where('paid_status',0)->where('deleted_status',0)->get();
        }
		return view('admin.invoices.unpaid_invoices_filter', compact('records'));
    }


    public function overdueInvoice(Request $request)
    {
        if ($request->ajax()) {

            // $start_date = request()->input('start_date');
            // $end_date = request()->input('end_date');


            // if ($start_date != null  && $end_date != null) {
            //     $start_date =   Carbon::parse($start_date)->format('Y-m-d');
            //     $end_date =   Carbon::parse($end_date)->format('Y-m-d');

            //     $data = Record::where('status', 1 && 'deleted_status', 0)->whereBetween('datetime', array($start_date, $end_date));
            // } else {
            //     $data = Record::where('status',1)->where('paid_status',0)->where('deleted_status',0);
            // }
            // $data = Record::

            //       join('companies','records.company_id','=','companies.id')
            //       ->join('users','records.user_id','=','users.id')
            //       ->join('sub_companies','records.sub_company_id','=','sub_companies.id')
            //       ->select(['records.*',
            //       DB::raw('companies.name as company_name'),
            //       DB::raw('sub_companies.name as subCompany_name'),
            //       DB::raw('users.name as username')])
            //       ->where('records.status', 1 && 'records.deleted_status', 0 )->where('records.paid_status',0);
              $start_date = request()->input('start_date');
            $end_date = request()->input('end_date');
            $search = request()->input('search');


              $data = Record::

                   join('companies','records.company_id','=','companies.id')
                   ->join('users','records.user_id','=','users.id')
                   ->join('sub_companies','records.sub_company_id','=','sub_companies.id')
                   ->select(['records.*',
                   DB::raw('companies.name as company_name'),
                   DB::raw('sub_companies.name as subCompany_name'),
                   DB::raw('users.name as username')])
                   ->where('records.status', 1 && 'records.deleted_status', 0)->where('records.paid_status',0);

                   if ($start_date != null  && $end_date != null) {
                    $start_date =   Carbon::parse($start_date)->format('Y-m-d');
                    $end_date =   Carbon::parse($end_date)->format('Y-m-d');

                    $data->whereBetween('datetime', array($start_date, $end_date));
                    }

            return Datatables::eloquent($data)
                ->addColumn('username', function (Record $record) {
                    return $record->user->name;
                })
                ->addColumn('company', function (Record $record) {
                    return $record->subCompany->company->name;
                })
                ->addColumn('subcompany', function (Record $record) {
                    return $record->subCompany->name;
                })
                ->addColumn('datetime', function (Record $record) {

                    return Carbon::parse($record->datetime)->format('d-m-Y');

                })
                 ->addColumn('total_amount', function ( Record $record) {

                    $amount = '<span >$</span>'.Thousandsep($record->total_amount);
                    return  $amount;

                })

                ->addColumn('email', function (Record $record) {
                    if ($record->email_status == 1){
                        return 'Sent';
                    }
                    return 'Not Sent';
                })
                ->addColumn('paid_status', function (Record $record) {
                    if ($record->paid_status == 0){
                        $unpaid = '<span class="badge badge-danger">unpaid</span>';
                        return  $unpaid;
                    }else{
                    $paid = '<span class="badge badge-success">paid</span>';
                    return $paid;
                    }
                })
                ->filterColumn('company', function($query, $keyword) {
                    $query->whereHas('company', function($q) use ($keyword){
                        $q->whereRaw("companies.name like ?", ["%{$keyword}%"]);
                    });
                })

                ->filterColumn('subcompany', function($query, $keyword) {
                    $query->whereHas('subCompany', function($q) use ($keyword){
                        $q->whereRaw("sub_companies.name like ?", ["%{$keyword}%"]);
                    });
                })
                ->filterColumn('username', function($query, $keyword) {
                $query->whereHas('user', function($q) use ($keyword){
                    $q->whereRaw("users.username like ?", ["%{$keyword}%"]);
                });
            })

            ->filterColumn('paid_status', function($query, $keyword) {

                $keywords = 3;
                if ($keyword == 'paid'){
                    $keywords = 1;
                } else if($keyword == 'unpaid'){
                    $keywords = 0;
                }
                if ($keywords != 3){
                    $query->whereRaw("records.paid_status like ?", ["%{$keywords}%"]);
                }
            })
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                 $button = '<div class="text-center"> <a href="'.route('invoice.details',$data->id).'"><button  type="button" name="view" id="' . $data->id . '" class="btn btn-sm btn-primary">View</button></a></div>';
                 return $button;
             })
                ->rawColumns(['action','paid_status','total_amount'])
                ->orderColumn('datetime', function ($query, $order) {
                     $query->orderBy('datetime', $order);
                 })
                 ->orderColumn('username', function ($query, $order) {
                     $query->orderBy('users.username', $order);
                 })
                   ->orderColumn('company', function ($query, $order) {
                     $query->orderBy('companies.name', $order);
                 })
                 ->orderColumn('subcompany', function ($query, $order) {
                     $query->orderBy('sub_companies.name', $order);
                 })

                  ->orderColumn('total_amount', function ($query, $order) {
                     $query->orderBy('total_amount', $order);
                 })
                ->make(true);


        }


        return view('admin.invoices.overdue_invoices');
    }

   public function overdueInvoicesFilter(Request $request)
    {
    	$type = $request->type;
      	if ($type == 'Custom Range') {
            $from = Carbon::parse($request->from)->format('Y-m-d');
          	$to = Carbon::parse($request->to)->format('Y-m-d');
          	$records = Record::whereBetween('datetime', [$from, $to])->where('status',1)->where('paid_status',0)->where('deleted_status',0)->get();
        } elseif ($type == 'Today') {
        	$records = Record::whereRaw('Date(datetime) = CURDATE()')->where('status',1)->where('paid_status',0)->where('deleted_status',0)->get();
        } elseif ($type == 'Yesterday') {
          	$yesterday = date("Y-m-d", strtotime( '-1 days' ));
        	$records = Record::where('datetime', $yesterday)->where('status',1)->where('paid_status',0)->where('deleted_status',0)->get();
        } elseif ($type == 'Last 7 Days') {
          	$date = Carbon::today()->subDays(7);
        	$records = Record::where('datetime', '>=', $date)->where('status',1)->where('paid_status',0)->where('deleted_status',0)->get();
        } elseif ($type == 'Last 30 Days') {
          	$date = Carbon::today()->subDays(30);
        	$records = Record::where('datetime', '>=', $date)->where('status',1)->where('paid_status',0)->where('deleted_status',0)->get();
        } elseif ($type == 'This Month') {
        	$records = Record::where('datetime', '>', Carbon::now()->startOfMonth())->where('datetime', '<', Carbon::now()->endOfMonth())->where('paid_status',0)->where('status',1)->where('deleted_status',0)->get();
        } elseif ($type == 'Last Month') {
        	$records = Record::where('datetime', '=', Carbon::now()->subMonth()->month)->where('status',1)->where('paid_status',0)->where('deleted_status',0)->get();
        }
		return view('admin.invoices.overdue_invoices_filter', compact('records'));
    }

  	public function invoiceDetails($id)
    {
      $record = Record::find($id);
      $smtp = Smtp::select('body')->first();
      $invoicedata = InvoiceSetting::first();
      return view('admin.invoices.invoice_details', compact('record','smtp','invoicedata'));
    }


	public function invoice($id)
    {
      	$record = Record::find($id);
        $customPaper = array(0,0,680,920);

      	$file = 'Atlas Fuel - Invoice '.$record->invoice_number.' - '. date('d-m-Y').'.pdf';

		$pdf = PDF::loadView('invoice', compact('record'))->setPaper($customPaper);
		return $pdf->download($file);

    }

    public function subAccounts(Request $request){
        $subaccounts = SubAccount::select('id','title')->where('chart_account_id',$request->id)->get();
      	return json_encode($subaccounts);
    }

  	public function payInvoicePayment(Request $request)
    {
      $this->validate($request, [
          	'payment_amount' => 'required',
          	'sub_account_id' => 'required',
        ]);
      	$record = Record::find($request->record_id);
      	$pay = $request->payment_amount;
      	$total_paid = $pay + round($record->paid_amount,2);
      	$total_remaining = round($record->total_amount,2) - $total_paid;

         $myob = new MyobCurl;
         $account_uid['account_uid'] = $record->subCompany->ac_uid;
         $customer_uid['customer_uid'] = $record->subCompany->co_uid;


         $date = \Carbon\Carbon::parse($request->payment_datetime)->format('Y-m-d h:m:s ');



       $invoice_uid = Record::find($request->record_id)->invoice_uid;

         $post_json_data = [


           "PayFrom" => "6f390784-b456-412d-bff9-957cb5b8702a",
           "Account" => [
                 "UID" => $account_uid['account_uid'],

              ],
           "Customer" => [
                    "UID" => $customer_uid['customer_uid'],
                 ],

           "Date" => $date,
           "PaymentMethod" => "Other",


           "Invoices" => [
                       [

                          "UID" => $invoice_uid,
                          "AmountApplied" => $request->payment_amount,
                          "Type" => "Invoice"
                       ]
                    ],
            "DeliveryStatus"=> "Print",
           "ForeignCurrency" => null
        ];



        if (coupon_status() == false){

       }
        $token = myobtokens::find(1)->access_token;
        $post_data =     $myob->FileGetContents(
         company_uri().'Sale/CustomerPayment',
         'post',
         json_encode($post_json_data)
     ,
     $token
     );
    //  dd($post_data);
     $post_data ['post'] = $post_json_data;




      	$paid_status = 0;
      	if ($total_remaining <= 0){
        	$paid_status = 1;
        }
  	    $record->update([
          'paid_amount' => $pay,
          'paid_status' => $paid_status
        ]);
       $transaction_alloc = new TransactionAllocation();
       $transaction_alloc->sub_account_id = $request->sub_account_id;
       $transaction_alloc->record_id = $request->record_id;
       $transaction_alloc->amount = $request->payment_amount;
       $transaction_alloc->payment_date = \Carbon\Carbon::parse($request->payment_datetime)->format('Y-m-d H:i');
       $transaction_alloc->save();

      	Session::flash('success','Amount paid successfully.');
      	return back();
    }

  	public function sendMail(Request $request)
    {
    	$this->validate($request, [
			'subject' => 'required',
			'body' => 'required',
        ]);


      	if ($request->companies == '' && $request->more_emails == '') {
        	Session::flash('warning','Failed');
          	return back();
        }

      	$smtp = \DB::table('smtps')->first();
      	if ($smtp)
        {
          $record = Record::find($request->record_id);
          $smtp = \App\Smtp::first();
          $data = array('record_id' => $request->record_id,'subject' => $request->subject, 'body' => $request->body,'sdd_status' => $request->sdd_status, 'bol_status' => $request->bol_status, 'invoice_status' => $request->invoice_status);

          $companies_emails = $request->companies;
          $smtp_bcc = explode("::",$smtp->bcc);
          if (!empty($companies_emails)) {
	          $array_merged = array_merge($companies_emails,$smtp_bcc);
          } else
          		$array_merged = $smtp_bcc;

          if ($request->more_emails) {
            $array_of_more = json_decode($request->more_emails);
            $emails = array_merge($array_merged,$array_of_more);
          } else {
          	$emails = $array_merged;
          }

          if(!empty($emails)){
            try {
             \Mail::to($smtp->primary_mail)->bcc($emails)->send(new \App\Mail\SendInvoiceMail($data));
              } catch (\Exception $e) {
              	Session::flash('danger', 'E-Mail is not send please review e-mail settings');
              	return back();
              }
        }
          else{
            Session::flash('warning','Failed');
            return back();
          }

          $record->update([
            'email_status' => 1
          ]);

          Session::flash('success','E-mail sent successfully.');
        } else {
        	Session::flash('danger','Failed');
        }

      	return back();
    }
}
