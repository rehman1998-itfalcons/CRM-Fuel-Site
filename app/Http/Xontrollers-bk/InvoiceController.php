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
use App\PurchaseRecord;



class InvoiceController extends Controller
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


   public function testInvoice()
    {
		return view('admin.invoices.test_invoice');
    }

  	public function allInvoices(Request $request)
    {




        if ($request->ajax()) {

            $start_date = request()->input('start_date');
            $end_date = request()->input('end_date');


            if ($start_date != null  && $end_date != null) {
                $start_date =   Carbon::parse($start_date)->format('Y-m-d');
                $end_date =   Carbon::parse($end_date)->format('Y-m-d');

                $data = Record::where('status', 1 && 'deleted_status', 0)->whereBetween('datetime', array($start_date, $end_date));
            } else {
                $data = Record::where('status', 1)->where('deleted_status', 0);
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
                ->addColumn('email', function (Record $record) {
                    if ($record->email_status == 1){
                        return 'Sent';
                    }
                    return 'Not Sent';
                })
                ->addColumn('paid_status', function (Record $record) {
                    if ($record->paid_status == 0){
                        return 'Unpaid';
                    }
                    return 'Paid';
                })
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                 $button = '<div class="text-center"> <a href="'.route('invoice.details',$data->id).'"><button style="min-width: 44px; padding:0px;" type="button" name="view" id="' . $data->id . '" class="status_btn btn_1 ">View</button></a></div>';
                 return $button;
             })
                ->rawColumns(['action'])
                ->make(true);
            // ->toJson();

        }


        return view('admin.invoices.all_invoices');

//       	$records = Record::where('status',1)->where('deleted_status',0)->get();

//         if (coupon_status() == false){
//           return redirect('/myob');
//        }
//        $myob = new MyobCurl;
//      $token = myobtokens::find(1)->access_token;
//    $get_data =     $myob->FileGetContents(company_uri().'Sale/Invoice/Item', 'get','', $token);
//    $item_invoice = json_decode($get_data['response'],true);

//   ($item_invoice);
//    //$unique_number = substr(md5($request->name),1,25);
//    foreach($item_invoice as $invoices){

//      if (is_array($invoices) || is_object($invoices))
//        {
//        foreach($invoices as $invoice) {
//           foreach ($records as $record){
//          //dd($records->invoice_number);
//            if ($record->invoice_number === $invoice['Number']){
//              if($invoice['Status'] ==='Closed' && $invoice['BalanceDueAmount'] <= '0.000000'){
//                //dd($invoice['BalanceDueAmount']);

//               DB::table('records')->where('invoice_number',$record->invoice_number)->update([
//                 'paid_status' => '1',
//                 'myob_payment_status'=>'1',
//                 'paid_amount'=>$record->total_amount


//              ]);
//              }
//              //dd($invoice['Status']);

//            }

//          }

//         }
//       }
//      }
// 		return view('admin.invoices.all_invoices', compact('records'));


}

      	// $purchases = \App\PurchaseRecord::where('status',1)->where('deleted_status',0)->get();
      	// $companies = \DB::table('supplier_companies')->select('id','name')->get();
		// return view('admin.invoices.purchase_all_invoices', compact('purchases', 'companies')); here

        public function purchaseallInvoices(Request $request)
        {
            $companies = \DB::table('supplier_companies')->select('id','name')->get();
               // dd( $request);
               // RecordDataTable $dataTable
               if ($request->ajax()) {

                        // $data = \DB::table('purchase_records');
                       $data = PurchaseRecord::with('category','fuelCompany');



                   return Datatables::eloquent($data)
                       ->addColumn('category_name', function (PurchaseRecord $record) {
                           return $record->category->name;
                       })
                       ->addColumn('fuelCompany_name', function (PurchaseRecord $record) {
                           return $record->fuelCompany->name;
                       })
                       ->addColumn('paid_status', function (PurchaseRecord $record) {
                        if ($record->paid_status == 0){
                            return 'Unpaid';
                        }
                        return 'Paid';
                    })
                       ->addIndexColumn()
                       ->addColumn('action', function ($data) {
                        $button = '<div class="text-center"><a href="'.route('purchases.edit',$data->id).'"><button style="min-width: 44px; padding:0px;" type="button" name="edit" id="' . $data->id . '" class="status_btn" >Edit</button></a> <a href="'.route('purchases.show',$data->id).'"><button style="min-width: 44px; padding:0px;" type="button" name="edit" id="' . $data->id . '" class="status_btn btn-primary ">View</button></a></div>';
                        return $button;
                    })
                       ->rawColumns(['action'])
                       ->make(true);
                   // ->toJson();

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

  	// public function paidInvoices()
    // {
    //   	// $records = Record::where('status',1)->where('paid_status',1)->where('deleted_status',0)->get();
	// 	// return view('admin.invoices.paid_invoices', compact('records'));
    // }
    public function paidInvoices(Request $request)
    {
        if ($request->ajax()) {

            $start_date = request()->input('start_date');
            $end_date = request()->input('end_date');


            if ($start_date != null  && $end_date != null) {
                $start_date =   Carbon::parse($start_date)->format('Y-m-d');
                $end_date =   Carbon::parse($end_date)->format('Y-m-d');

                $data = Record::where('status', 1 && 'deleted_status', 0)->whereBetween('datetime', array($start_date, $end_date));
            } else {
                $data = Record::where('status', 1)->where('deleted_status', 0)->where('paid_status',1);
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
                ->addColumn('email', function (Record $record) {
                    if ($record->email_status == 1){
                        return 'Sent';
                    }
                    return 'Not Sent';
                })
                ->addColumn('paid_status', function (Record $record) {
                    if ($record->paid_status == 0){
                        return 'Unpaid';
                    }
                    return 'Paid';
                })
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                 $button = '<div class="text-center"> <a href="'.route('invoice.details',$data->id).'"><button style="min-width: 44px; padding:0px;" type="button" name="view" id="' . $data->id . '" class="status_btn btn_1 ">View</button></a></div>';
                 return $button;
             })
                ->rawColumns(['action'])
                ->make(true);
            // ->toJson();

        }


        return view('admin.invoices.paid_invoices');
    }

    public function purchasepaidInvoices(Request $request)
    {

        $companies = \DB::table('supplier_companies')->select('id','name')->get();
               // dd( $request);
               // RecordDataTable $dataTable
               if ($request->ajax()) {

                        // $data = \DB::table('purchase_records');
                       $data = PurchaseRecord::where('paid_status',1)->where('deleted_status',0)->whereRaw('total_amount-paid_amount = 0');



                   return Datatables::eloquent($data)
                       ->addColumn('category_name', function (PurchaseRecord $record) {
                           return $record->category->name;
                       })
                       ->addColumn('fuelCompany_name', function (PurchaseRecord $record) {
                           return $record->fuelCompany->name;
                       })
                       ->addColumn('paid_status', function (PurchaseRecord $record) {
                        if ($record->paid_status == 0){
                            return 'Unpaid';
                        }
                        return 'Paid';
                    })
                       ->addIndexColumn()
                       ->addColumn('action', function ($data) {
                        $button = '<div class="text-center"><a href="'.route('purchases.edit',$data->id).'"><button style="min-width: 44px; padding:0px;" type="button" name="edit" id="' . $data->id . '" class="status_btn" >Edit</button></a> <a href="'.route('purchases.show',$data->id).'"><button style="min-width: 44px; padding:0px;" type="button" name="edit" id="' . $data->id . '" class="status_btn btn-primary ">View</button></a></div>';
                        return $button;
                    })
                       ->rawColumns(['action'])
                       ->make(true);
                   // ->toJson();

               }


               return view('admin.invoices.purchase_paid_invoices',compact('companies'));
      	// $purchases = \App\PurchaseRecord::where('status',1)->where('deleted_status',0)->whereRaw('total_amount-paid_amount = 0')->get();
      	// $companies = \DB::table('supplier_companies')->select('id','name')->get();
		// return view('admin.invoices.purchase_paid_invoices', compact('purchases','companies'));
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

  	// public function unpaidInvoices()
    // {
    //   	$records = Record::where('status',1)->where('paid_status',0)->where('deleted_status',0)->get();
	// 	return view('admin.invoices.unpaid_invoices', compact('records'));
    // }
    public function unpaidInvoices(Request $request)
    {
        if ($request->ajax()) {

            $start_date = request()->input('start_date');
            $end_date = request()->input('end_date');


            if ($start_date != null  && $end_date != null) {
                $start_date =   Carbon::parse($start_date)->format('Y-m-d');
                $end_date =   Carbon::parse($end_date)->format('Y-m-d');

                $data = Record::where('status', 1 && 'deleted_status', 0)->whereBetween('datetime', array($start_date, $end_date));
            } else {
                $data = Record::where('status', 1)->where('deleted_status', 0)->where('paid_status',0);
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
                ->addColumn('email', function (Record $record) {
                    if ($record->email_status == 1){
                        return 'Sent';
                    }
                    return 'Not Sent';
                })
                ->addColumn('paid_status', function (Record $record) {
                    if ($record->paid_status == 0){
                        return 'Unpaid';
                    }
                    return 'Paid';
                })
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                 $button = '<div class="text-center"> <a href="'.route('invoice.details',$data->id).'"><button style="min-width: 44px; padding:0px;" type="button" name="view" id="' . $data->id . '" class="status_btn btn_1 ">View</button></a></div>';
                 return $button;
             })
                ->rawColumns(['action'])
                ->make(true);
            // ->toJson();

        }


        return view('admin.invoices.unpaid_invoices');
    }
  	public function purchaseunpaidInvoices(Request $request)
    {
        $companies = \DB::table('supplier_companies')->select('id','name')->get();
        // dd( $request);
        // RecordDataTable $dataTable
        if ($request->ajax()) {

                 // $data = \DB::table('purchase_records');
                $data = PurchaseRecord::where('paid_status',0)->where('deleted_status',0)->whereRaw('total_amount-paid_amount != 0');



            return Datatables::eloquent($data)
                ->addColumn('category_name', function (PurchaseRecord $record) {
                    return $record->category->name;
                })
                ->addColumn('fuelCompany_name', function (PurchaseRecord $record) {
                    return $record->fuelCompany->name;
                })
                ->addColumn('paid_status', function (PurchaseRecord $record) {
                 if ($record->paid_status == 0){
                     return 'Unpaid';
                 }
                 return 'Paid';
             })
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                 $button = '<div class="text-center"><a href="'.route('purchases.edit',$data->id).'"><button style="min-width: 44px; padding:0px;" type="button" name="edit" id="' . $data->id . '" class="status_btn" >Edit</button></a> <a href="'.route('purchases.show',$data->id).'"><button style="min-width: 44px; padding:0px;" type="button" name="edit" id="' . $data->id . '" class="status_btn btn-primary ">View</button></a></div>';
                 return $button;
             })
                ->rawColumns(['action'])
                ->make(true);
            // ->toJson();

        }


        return view('admin.invoices.purchase_unpaid_invoices',compact('companies'));
      	// $purchases = \App\PurchaseRecord::where('status',1)->where('deleted_status',0)->whereRaw('total_amount-paid_amount > 0')->get();
      	// $companies = \DB::table('supplier_companies')->select('id','name')->get();
		// return view('admin.invoices.purchase_unpaid_invoices', compact('purchases','companies'));
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

  	// public function overdueInvoice()
    // {
    //     $records = Record::where('status',1)->where('paid_status',0)->where('deleted_status',0)->get();
	// 	return view('admin.invoices.overdue_invoices', compact('records'));
    // }
    public function overdueInvoice(Request $request)
    {
        if ($request->ajax()) {

            $start_date = request()->input('start_date');
            $end_date = request()->input('end_date');


            if ($start_date != null  && $end_date != null) {
                $start_date =   Carbon::parse($start_date)->format('Y-m-d');
                $end_date =   Carbon::parse($end_date)->format('Y-m-d');

                $data = Record::where('status', 1 && 'deleted_status', 0)->whereBetween('datetime', array($start_date, $end_date));
            } else {
                $data = Record::where('status',1)->where('paid_status',0)->where('deleted_status',0);
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
                ->addColumn('email', function (Record $record) {
                    if ($record->email_status == 1){
                        return 'Sent';
                    }
                    return 'Not Sent';
                })
                ->addColumn('paid_status', function (Record $record) {
                    if ($record->paid_status == 0){
                        return 'Unpaid';
                    }
                    return 'Paid';
                })
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                 $button = '<div class="text-center"> <a href="'.route('invoice.details',$data->id).'"><button style="min-width: 44px; padding:0px;" type="button" name="view" id="' . $data->id . '" class="status_btn btn_1 ">View</button></a></div>';
                 return $button;
             })
                ->rawColumns(['action'])
                ->make(true);
            // ->toJson();

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
        //return view('invoice', compact('record'));
      	$file = 'Atlas Fuel - Invoice '.$record->invoice_number.' - '. date('d-m-Y').'.pdf';
    //   	return view('invoice', compact('record'));
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
         /***   myob payment code*/
         $myob = new MyobCurl;
         $account_uid['account_uid'] = $record->subCompany->ac_uid;
         $customer_uid['costomer_uid'] = $record->subCompany->co_uid;


         $date = \Carbon\Carbon::parse($request->payment_datetime)->format('Y-m-d h:m:s ');

         // $uid = Record::where('id', $request->record_id)->value('invoice_uid');
         // dd($uid);
       $invoice_uid = Record::find($request->record_id)->invoice_uid;
     // dd($invoice_uid);
         $post_json_data = [


           "PayFrom" => "6f390784-b456-412d-bff9-957cb5b8702a",
           "Account" => [
                 "UID" => $account_uid['account_uid'],

              ],
           "Customer" => [
                    "UID" => $customer_uid['costomer_uid'],
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

     //  dd($post_json_data);

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
     $post_data ['post'] = $post_json_data;
    // dd($post_data);


       //end of myob payment code
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
