<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Record;
use App\Product;

class IncomeReportController extends Controller
{
    public function index()
    {
    	return view('admin.reports.income_statistics');
    }
  
  	public function incomeStatisticsReport(Request $request)
    {
        $type = $request->type;
		$from = Carbon::parse($request->from)->format('Y-m-d');
        $to = Carbon::parse($request->to)->format('Y-m-d');
      	if ($type == 'Custom Range') {
          	$records = Record::select('*')->selectRaw('SUBSTRING(`datetime`, 1, 10) as `datetime`')->whereBetween('datetime', [$from, $to])->where('status',1)->where('deleted_status',0)->get()->groupBy('datetime');
        } elseif ($type == 'Today') {
        	$records = Record::select('*')->selectRaw('SUBSTRING(`datetime`, 1, 10) as `datetime`')->whereBetween('datetime', [$from, $to])->where('status',1)->where('deleted_status',0)->get()->groupBy('datetime');  
        } elseif ($type == 'Yesterday') {
          	$yesterday = date("Y-m-d", strtotime( '-1 days' ));
        	$records = Record::select('*')->selectRaw('SUBSTRING(`datetime`, 1, 10) as `datetime`')->whereBetween('datetime', [$from, $to])->where('status',1)->where('deleted_status',0)->get()->groupBy('datetime');  
       	} elseif ($type == 'Last 7 Days') {
        	$date = Carbon::today()->subDays(7); 
          	$records = Record::select('*')->selectRaw('SUBSTRING(`datetime`, 1, 10) as `datetime`')->whereBetween('datetime', [$from, $to])->where('status',1)->where('deleted_status',0)->get()->groupBy('datetime');  
       	} elseif ($type == 'Last 30 Days') {
        	$date = Carbon::today()->subDays(30);
          	$records = Record::select('*')->selectRaw('SUBSTRING(`datetime`, 1, 10) as `datetime`')->whereBetween('datetime', [$from, $to])->where('status',1)->where('deleted_status',0)->get()->groupBy('datetime'); 
        } elseif ($type == 'This Month') {
        	$records = Record::select('*')->selectRaw('SUBSTRING(`datetime`, 1, 10) as `datetime`')->whereBetween('datetime', [$from, $to])->where('status',1)->where('deleted_status',0)->get()->groupBy('datetime');  
        } elseif ($type == 'Last Month') {
        	$records = Record::select('*')->selectRaw('SUBSTRING(`datetime`, 1, 10) as `datetime`')->whereBetween('datetime', [$from, $to])->where('status',1)->where('deleted_status',0)->get()->groupBy('datetime'); 
        }
      
      	return view('admin.reports.income_statistics_report', compact('records'));
    }
  
  	public function incomeStatisticsReportDetails($date)
    {
      	$dfrom = $date. ' 00:00:00';
        $dto = $date. ' 23:59:59';
      	$from = Carbon::parse($dfrom)->format('Y-m-d H:i');
      	$to = Carbon::parse($dto)->format('Y-m-d H:i');
      	$records = Record::whereBetween('datetime', [$from, $to])->where('status',1)->where('deleted_status',0)->get();
      	$products = Product::select('id','name')->get();
    	return view('admin.reports.income_statistics_report_details', compact('records','date','products'));
    }
}
