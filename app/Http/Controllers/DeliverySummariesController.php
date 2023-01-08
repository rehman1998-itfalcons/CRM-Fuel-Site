<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Record;
use App\Product;
use DB;

class DeliverySummariesController extends Controller
{
   
    public function __construct()
    {
        $this->middleware('superadmin');
    }
 
  
   
    public function deliveriesSummary()
    {
		return view('admin.reports.deliveries_summary');
    }
  
  	public function deliveriesSummaryResult(Request $request)
    {
        $type = $request->type;
		$from = Carbon::parse($request->from)->format('Y-m-d');
        $to = Carbon::parse($request->to)->format('Y-m-d');

      	if ($request->company_id == 'all_companies') {
          
          	$records = Record::select('id')->selectRaw('SUBSTRING(`datetime`, 1, 10) as `datetime`')->whereBetween('datetime', [$from, $to])->where('category_id',$request->category_id)->where('status',1)->where('deleted_status',0)->get()->groupBy('datetime');
          
        } else if($request->sub_company_id == 'all_subcompanies') {
          
          	$records = Record::select('id')->selectRaw('SUBSTRING(`datetime`, 1, 10) as `datetime`')->whereBetween('datetime', [$from, $to])->where('category_id',$request->category_id)->where('company_id',$request->company_id)->where('status',1)->where('deleted_status',0)->get()->groupBy('datetime');
          
       	} else {
          
       		$records = Record::select('id')->selectRaw('SUBSTRING(`datetime`, 1, 10) as `datetime`')->whereBetween('datetime', [$from, $to])->where('category_id',$request->category_id)->whereIn('sub_company_id',$request->sub_company_id)->where('status',1)->where('deleted_status',0)->get()->groupBy('datetime');
          
       }
      	return view('admin.reports.deliveries_summary_result', compact('records'));

    }
  
  	public function deliveriesSummaryResultDetails($date)
    {
      	$products = Product::select('id','name')->get();
      	$dfrom = $date. ' 00:00:00';
        $dto = $date. ' 23:59:59';
      	$from = Carbon::parse($dfrom)->format('Y-m-d H:i');
      	$to = Carbon::parse($dto)->format('Y-m-d H:i');
      	$records = Record::whereBetween('datetime', [$from, $to])->where('status',1)->where('deleted_status',0)->get(); 
    	return view('admin.reports.deliveries_summary_result_details', compact('records','products','date'));
    }
}
