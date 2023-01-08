<?php

namespace App\Http\Controllers;
use Carbon\Carbon;

use Illuminate\Http\Request;
use App\Product;
use App\Record;
use App\PurchaseRecord;

class DashboardController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
      	$role = \Auth::user()->role->name;
      	if ($role == 'Driver')
          	return redirect('/driver');
      	else {
        	return view('admin.index');
        }
    }
  
  	public function monthlyReport($series,$month)
    {
	    $m = $this->monthCode($month);
      	$products = \App\Product::get();
    	if ($series == 'sales')  {
        	$records = Record::whereMonth('datetime', $m)->whereYear('datetime',\Carbon\Carbon::now()->year)->where('status',1)->where('deleted_status',0)->get();  
        } else {
        	$records = PurchaseRecord::whereMonth('datetime', $m)->whereYear('datetime',\Carbon\Carbon::now()->year)->where('status',1)->where('deleted_status',0)->get(); 
        }
      
      	return view('admin.dashboard_filters.monthly_reports', compact('records','products','series','month'));
    }
  
  	public function monthlyReportFilter($series,$month,$day)
    {
		$m = $this->monthCode($month);
      
      	$products = Product::get();
    	if ($series == 'sales')  {
        	$records = Record::whereDay('datetime', $day)->whereMonth('datetime', $m)->whereYear('datetime',\Carbon\Carbon::now()->year)->where('status',1)->where('deleted_status',0)->get();  
        } else {
        	$records = PurchaseRecord::whereDay('datetime', $day)->whereMonth('datetime', $m)->whereYear('datetime',\Carbon\Carbon::now()->year)->where('status',1)->where('deleted_status',0)->get(); 
        }
      
      	return view('admin.dashboard_filters.monthly_report_filter', compact('records','products','series','month'));
    }
  
  	public function monthCode($month)
    {
      	switch ($month) {
        	case 'january':
          	$m = '01';
          	break;
        	case 'febuary':
          	$m = '02';
          	break;
        	case 'march':
          	$m = '03';
          	break;
        	case 'april':
          	$m = '04';
          	break;
        	case 'may':
          	$m = '05';
          	break;
        	case 'june':
          	$m = '06';
          	break;
        	case 'july':
          	$m = '07';
          	break;
        	case 'august':
          	$m = '08';
          	break;
        	case 'september':
          	$m = '09';
          	break;
        	case 'october':
          	$m = '10';
          	break;
        	case 'november':
          	$m = '11';
          	break;
        	case 'december':
          	$m = '12';
          	break;
        }
      	return $m;
    }
  
  	public function overdueSalesReport($encrypt,$date)
    {
      	$decrypt = decrypt($encrypt);
      	$array = explode("::",$decrypt);
      	$records = Record::whereIn('id',$array)->get();
    	return view('admin.dashboard_filters.overdue_sales_report', compact('records','date'));  
    }  
  
    public function searchMonthlyReport(Request $request)
    {
            
      if($request->name == '00'){
          $date = \Carbon\Carbon::today()->subDays(7);
      
        $records = Record::where('status',1)->where('deleted_status',0)->where('datetime','>=',\Carbon\Carbon::now()->subdays(7))->get()->groupBy('datetime');
      }
      else{
        $records = Record::whereMonth('datetime', $request->name)->whereYear('datetime',\Carbon\Carbon::now()->year)->where('status',1)->where('deleted_status',0)->get()->groupBy('datetime');
      }
             
      
      return view('admin.ajax.report_result', compact('records','date'));
      
    }
   public function searchPurchaseMonthlyReport(Request $request)
    {
            
      if($request->name == '00'){
        $records = PurchaseRecord::where('status',1)->where('deleted_status',0)->where('datetime','>=',\Carbon\Carbon::now()->subdays(7))->get();
      }
      else{
        $records = PurchaseRecord::whereMonth('datetime', $request->name)->whereYear('datetime',\Carbon\Carbon::now()->year)->where('status',1)->where('deleted_status',0)->get();
      }
      
      return view('admin.ajax.purchase_report_result', compact('records','date'));
    }
  
  	public function monthReport($series,$month)
    {
    	$m = $this->monthCode($month);
      	$products = Product::get();
      
      	return view('admin.dashboard_filters.month_report_filter',compact('series','month','products'));
    }
  
  	public function supplierCompany($series,$company)
    {
   		$supplier = \App\SupplierCompany::where('name',$company)->first();
      	$products = \App\Product::get();
      	if ($series == 'sales') {
        	$records = Record::where('supplier_company_id',$supplier->id)->where('status',1)->where('deleted_status',0)->get();
        } else {
        	$records = PurchaseRecord::where('supplier_company_id',$supplier->id)->where('status',1)->where('deleted_status',0)->get();
        }
      	return view('admin.dashboard_filters.supplier_report',compact('records','series','company','products'));
    }
  
  	public function mainCompany($id)
    {
      	$sales = Record::where('company_id',$id)->get()->groupBy('sub_company_id');
        $sales_count = Record::where('sub_company_id',$id)->get();
      	$company = \App\Company::find($id);
      	return view('admin.dashboard_filters.main_company_report',compact('sales','company','sales_count'));
    }
  
  	public function subCompany($id)
    {
        $sales = Record::where('sub_company_id',$id)->get();
      	$subcompany = \App\SubCompany::find($id);
      	return view('admin.dashboard_filters.sub_company_report',compact('sales','subcompany'));
    }

  	public function totalDeliveries()
    {
    	return view('admin.dashboard_filters.total_deliveries');
    }
}
