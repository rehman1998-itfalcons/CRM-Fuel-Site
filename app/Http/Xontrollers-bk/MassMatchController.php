<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PurchaseRecord;
use Carbon\Carbon;
use App\Record;
use App\MassMatch;
use Session;
use DB;

class MassMatchController extends Controller
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
    public function index(Request $request)
    {
      	$company = $request->company;
      	$date_range = $request->date_range;
      	$field_text = $request->field_text;
        $from = Carbon::parse($request->from_date)->format('Y-m-d');
        $to = Carbon::parse($request->to_date)->format('Y-m-d');
      
      	if ($company == '' && $date_range == '' && $field_text == '') {
          
      		$purchases = PurchaseRecord::where('match_status',0)->orderBy('datetime','desc')->paginate(20);
          
        } elseif ($company != '' && $date_range != '' && $field_text == '') {

          	if ($date_range == 'Custom Range') {
              
              	$purchases = PurchaseRecord::select('*')->selectRaw('SUBSTRING(`datetime`, 1, 10) as `datetime`')->where('supplier_company_id',$company)->whereBetween('datetime', [$from, $to])->where('status',1)->paginate(20);
              
            } elseif ($date_range == 'Today') {
              
              	$purchases = PurchaseRecord::select('*')->selectRaw('SUBSTRING(`datetime`, 1, 10) as `datetime`')->where('supplier_company_id',$company)->whereBetween('datetime', [$from, $to])->where('status',1)->paginate(20);
              
            } elseif ($date_range == 'Yesterday') {
              
                $yesterday = date("Y-m-d", strtotime( '-1 days' ));
              	$purchases = PurchaseRecord::select('*')->selectRaw('SUBSTRING(`datetime`, 1, 10) as `datetime`')->where('supplier_company_id',$company)->whereBetween('datetime', [$from, $to])->where('status',1)->paginate(20);
              
            } elseif ($date_range == 'Last 7 Days') {
              
                $date = Carbon::today()->subDays(7);
              	$purchases = PurchaseRecord::select('*')->selectRaw('SUBSTRING(`datetime`, 1, 10) as `datetime`')->where('supplier_company_id',$company)->whereBetween('datetime', [$from, $to])->where('status',1)->paginate(20);
                          
            } elseif ($date_range == 'Last 30 Days') {
              
                $date = Carbon::today()->subDays(30);
              	$purchases = PurchaseRecord::select('*')->selectRaw('SUBSTRING(`datetime`, 1, 10) as `datetime`')->where('supplier_company_id',$company)->whereBetween('datetime', [$from, $to])->where('status',1)->paginate(20);

            } elseif ($date_range == 'This Month') {
              
              $purchases = PurchaseRecord::select('*')->selectRaw('SUBSTRING(`datetime`, 1, 10) as `datetime`')->where('supplier_company_id',$company)->whereBetween('datetime', [$from, $to])->where('status',1)->paginate(20);
              
            } elseif ($date_range == 'Last Month') {
              
              $purchases = PurchaseRecord::select('*')->selectRaw('SUBSTRING(`datetime`, 1, 10) as `datetime`')->where('supplier_company_id',$company)->whereBetween('datetime', [$from, $to])->where('status',1)->paginate(20);

            }                    
          
        } elseif ($company == '' && $date_range != '' && $field_text == '') {
           
            if ($date_range == 'Custom Range') {
              
              	$purchases = PurchaseRecord::select('*')->selectRaw('SUBSTRING(`datetime`, 1, 10) as `datetime`')->whereBetween('datetime', [$from, $to])->where('status',1)->paginate(20);
              
            } elseif ($date_range == 'Today') {
              
              	$purchases = PurchaseRecord::select('*')->selectRaw('SUBSTRING(`datetime`, 1, 10) as `datetime`')->whereBetween('datetime', [$from, $to])->where('status',1)->paginate(20);
              
            } elseif ($date_range == 'Yesterday') {
              
                $yesterday = date("Y-m-d", strtotime( '-1 days' ));
              	$purchases = PurchaseRecord::select('*')->selectRaw('SUBSTRING(`datetime`, 1, 10) as `datetime`')->whereBetween('datetime', [$from, $to])->where('status',1)->paginate(20);
              
            } elseif ($date_range == 'Last 7 Days') {
              
                $date = Carbon::today()->subDays(7);
              	$purchases = PurchaseRecord::select('*')->selectRaw('SUBSTRING(`datetime`, 1, 10) as `datetime`')->whereBetween('datetime', [$from, $to])->where('status',1)->paginate(20);
                          
            } elseif ($date_range == 'Last 30 Days') {
              
                $date = Carbon::today()->subDays(30);
              	$purchases = PurchaseRecord::select('*')->selectRaw('SUBSTRING(`datetime`, 1, 10) as `datetime`')->whereBetween('datetime', [$from, $to])->where('status',1)->paginate(20);

            } elseif ($date_range == 'This Month') {
              
              $purchases = PurchaseRecord::select('*')->selectRaw('SUBSTRING(`datetime`, 1, 10) as `datetime`')->whereBetween('datetime', [$from, $to])->where('status',1)->paginate(20);
              
            } elseif ($date_range == 'Last Month') {
              
              $purchases = PurchaseRecord::select('*')->selectRaw('SUBSTRING(`datetime`, 1, 10) as `datetime`')->whereBetween('datetime', [$from, $to])->where('status',1)->paginate(20);

            }
          
        } elseif ($company != '' && $date_range != '' && $field_text != '') {

      		$purchases = PurchaseRecord::select('*')
              							->selectRaw('SUBSTRING(`datetime`, 1, 10) as `datetime`')
              							->where('supplier_company_id',$company)
              							->orWhere('invoice_number','LIKE',$field_text.'%')
                                        ->orWhere('purchase_no','LIKE',$field_text.'%')
                                        ->orWhere('load_number','LIKE',$field_text.'%')
                                        ->orWhere('order_number','LIKE',$field_text.'%')
                                        ->orWhere('gst_status','LIKE',$field_text.'%')
                                        ->orWhere('total_quantity','LIKE',$field_text.'%')
                                        ->orWhere('total_amount','LIKE',$field_text.'%')
                                        ->orWhere('paid_amount','LIKE',$field_text.'%')
              							->where('match_status',0)
              							->orderBy('datetime','desc')->paginate(20);

        } elseif ($company != '' && $date_range == '' && $field_text == '') {
          
      		$purchases = PurchaseRecord::select('*')->selectRaw('SUBSTRING(`datetime`, 1, 10) as `datetime`')->where('supplier_company_id',$company)->where('match_status',0)->orderBy('datetime','desc')->paginate(20);

        } elseif ($company == '' && $date_range == '' && $field_text != '') {

      		$purchases = PurchaseRecord::orWhere('invoice_number','LIKE',$field_text.'%')
              			->orWhere('purchase_no','LIKE',$field_text.'%')
              			->orWhere('load_number','LIKE',$field_text.'%')
              			->orWhere('order_number','LIKE',$field_text.'%')
              			->orWhere('gst_status','LIKE',$field_text.'%')
              			->orWhere('total_quantity','LIKE',$field_text.'%')
              			->orWhere('total_amount','LIKE',$field_text.'%')
              			->orWhere('paid_amount','LIKE',$field_text.'%')
              			->where('match_status',0)
              			->orderBy('datetime','desc')
              			->paginate(20);
        }
      
        $purchases->appends(['company' => $request->company,'date_range' => $request->date_range,'field_text' => $request->field_text,'from_date' => $request->from_date,'to_date' => $request->to_date]);
      	return view('admin.mass-match.index', compact('purchases'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::table('records')->where('id',$request->record_id)->update(['mass_match_status' => 1]);
        DB::table('purchase_records')->where('id',$request->purchase_record_id)->update(['match_status' => 1]);
      
      	MassMatch::create([
          'record_id' => $request->record_id,
          'purchase_record_id' => $request->purchase_record_id,
        ]);
      
      	Session::flash('success','Purchase order and invoices matched.');
      	return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
      	if ($request->has('fuel_company') || $request->has('keywords')) {

        	$purchases = PurchaseRecord::where('match_status',1)
              	->when(request('fuel_company') != '', function ($q) {
                    return $q->where('supplier_company_id',request('fuel_company'));
                })
              	->when(request('keywords') != '', function ($q) {
                    return $q->where('invoice_number','LIKE',request('keywords').'%');
                })
              	->when(request('keywords') != '', function ($q) {
                    return $q->where('purchase_no','LIKE',request('keywords').'%');
                })
              	->when(request('keywords') != '', function ($q) {
                    return $q->where('datetime','LIKE',request('keywords').'%');
                })
              	->when(request('keywords') != '', function ($q) {
                    return $q->where('load_number','LIKE',request('keywords').'%');
                })
              	->when(request('keywords') != '', function ($q) {
                    return $q->where('order_number','LIKE',request('keywords').'%');
                })
              	->when(request('keywords') != '', function ($q) {
                    return $q->where('gst_status','LIKE',request('keywords').'%');
                })
              	->when(request('keywords') != '', function ($q) {
                    return $q->where('status','LIKE',request('keywords').'%');
                })
              	->when(request('keywords') != '', function ($q) {
                    return $q->where('purchaseinvoices','LIKE',request('keywords').'%');
                })
              	->when(request('keywords') != '', function ($q) {
                    return $q->where('total_quantity','LIKE',request('keywords').'%');
                })
              	->when(request('keywords') != '', function ($q) {
                    return $q->where('total_amount','LIKE',request('keywords').'%');
                })
              	->when(request('keywords') != '', function ($q) {
                    return $q->where('paid_amount','LIKE',request('keywords').'%');
                })
              	->orderBy('created_at','desc')
              	->paginate(10);

        } else {
        	$purchases = PurchaseRecord::where('match_status',1)->orderBy('created_at','desc')->paginate(10);
        }
      	
        return view('admin.mass-match.show', compact('purchases'));
    }

  	public function edit($id)
    {
		//
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
        $matches = MassMatch::where('purchase_record_id',$request->purchase_record_id)->get();
      	foreach ($matches as $match) {
          DB::table('records')->where('id',$match->record_id)->update(['mass_match_status' => 0]);  
          $match->delete();
        }

      	DB::table('purchase_records')->where('id',$request->purchase_record_id)->update(['match_status' => 0]);
      
      	Session::flash('success','Purchase order and invoices re-consiled.');
      	return back();
    }

}
