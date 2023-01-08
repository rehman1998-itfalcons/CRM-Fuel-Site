<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\SupplierCompany;
use App\Record;
use App\Product;
use Carbon\Carbon;
use DB;

class SupplierReportsController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('superadmin');
    }  
 
  
  
  	public function supplierReport()
    {
      	return view('admin.reports.supplier_report');      
    }
  
  	public function supplierReportGeneration(Request $request)
    {
      	$companies = SupplierCompany::select('id','name')->get();
        $type = $request->type;
		$from = Carbon::parse($request->from)->format('Y-m-d');
        $to = Carbon::parse($request->to)->format('Y-m-d');        

            if ($request->company_id == 'all_companies') {
                $records = Record::select('id','supplier_company_id','status','datetime')->whereBetween('datetime', [$from, $to])->where('category_id',$request->category_id)->where('status',1)->where('deleted_status',0)->get()->groupBy('supplier_company_id');
                }
            else if($request->sub_company_id == 'all_subcompanies'){
                $records = Record::select('id','supplier_company_id','status','datetime')->whereBetween('datetime', [$from, $to])->where('category_id',$request->category_id)->where('company_id',$request->company_id)->where('status',1)->where('deleted_status',0)->get()->groupBy('supplier_company_id');
                }
            else {
                $records = Record::select('id','supplier_company_id','status','datetime')->whereBetween('datetime', [$from, $to])->where('category_id',$request->category_id)->whereIn('sub_company_id',$request->sub_company_id)->where('status',1)->where('deleted_status',0)->get()->groupBy('supplier_company_id');
                }

		$products = Product::select('id','name')->get();
      	return view('admin.reports.supplier_report_result', compact('companies','records','products'));
    }
  
  	public function supplierReportIndividual($id)
    {
		$products = Product::select('id','name')->get();
      	$company = SupplierCompany::find($id);
      	return view('admin.reports.supplier_report_result_details', compact('company','products'));  
    }


}