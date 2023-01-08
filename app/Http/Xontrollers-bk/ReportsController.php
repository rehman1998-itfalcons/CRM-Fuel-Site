<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Company;
use App\Category;
use App\SubCompany;
use App\SupplierCompany;
use App\PurchaseRecord;
use App\Record;
use App\Product;
use Carbon\Carbon;
use PDF;
use Db;

class ReportsController extends Controller
{

    /* Client Statement */

    public function clientStatment()
    {
        $categories = Category::select('id','name')->where('status',1)->orderBy('name','asc')->get();
        $companies = Company::select('id','name')->where('status',1)->orderBy('name','asc')->get();
        return view('admin.reports.client_statment', compact('categories','companies'));
    }

    public function fetchSubCompanies(Request $request)
    {
        $sub_companies = SubCompany::select('id','name')->where('company_id',$request->company_id)->where('category_id',$request->category_id)->where('status',1)->orderBy('name','asc')->get();
        return json_encode($sub_companies);
    }

    public function clientStatmentReport(Request $request)
    {
      
        $type = $request->type;
        $from = Carbon::parse($request->from)->format('Y-m-d');
        $to = Carbon::parse($request->to)->format('Y-m-d');
        if ($type == 'Custom Range') {
            if ($request->company_id == 'all_companies') {
                $records = Record::where('category_id',$request->category_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else if($request->sub_company_id == 'all_subcompanies'){
                $records = Record::where('category_id',$request->category_id)->where('company_id',$request->company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else {
                $records = Record::where('category_id',$request->category_id)->where('company_id',$request->company_id)->where('sub_company_id',$request->sub_company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }

        } elseif ($type == 'Today') {
            if ($request->company_id == 'all_companies') {
                $records = Record::where('category_id',$request->category_id)->whereRaw('Date(datetime) = CURDATE()')->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else if($request->sub_company_id == 'all_subcompanies'){
                $records = Record::where('category_id',$request->category_id)->where('company_id',$request->company_id)->whereRaw('Date(datetime) = CURDATE()')->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else {
                $records = Record::where('category_id',$request->category_id)->where('sub_company_id',$request->sub_company_id)->whereRaw('Date(datetime) = CURDATE()')->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
        } elseif ($type == 'Yesterday') {
            if ($request->company_id == 'all_companies') {
                $records = Record::where('category_id',$request->category_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else if($request->sub_company_id == 'all_subcompanies'){
                $records = Record::where('category_id',$request->category_id)->where('company_id',$request->company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else {
                $records = Record::where('category_id',$request->category_id)->where('sub_company_id',$request->sub_company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
        } elseif ($type == 'Last 7 Days') {
            if ($request->company_id == 'all_companies') {
                $records = Record::where('category_id',$request->category_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else if($request->sub_company_id == 'all_subcompanies'){
                $records = Record::where('category_id',$request->category_id)->where('company_id',$request->company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else {
                $records = Record::where('category_id',$request->category_id)->where('sub_company_id',$request->sub_company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
        } elseif ($type == 'Last 30 Days') {
            if ($request->company_id == 'all_companies') {
                $records = Record::where('category_id',$request->category_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else if($request->sub_company_id == 'all_subcompanies'){
                $records = Record::where('category_id',$request->category_id)->where('company_id',$request->company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else {
                $records = Record::where('category_id',$request->category_id)->where('sub_company_id',$request->sub_company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
        } elseif ($type == 'This Month') {
            if ($request->company_id == 'all_companies') {
                $records = Record::where('category_id',$request->category_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else if($request->sub_company_id == 'all_subcompanies'){
                $records = Record::where('category_id',$request->category_id)->where('company_id',$request->company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            } else {
                $records = Record::where('category_id',$request->category_id)->where('sub_company_id',$request->sub_company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
        } elseif ($type == 'Last Month') {
            if ($request->company_id == 'all_companies') {
                $records = Record::where('category_id',$request->category_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            } else if($request->sub_company_id == 'all_subcompanies'){
                $records = Record::where('category_id',$request->category_id)->where('company_id',$request->company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            } else {
                $records = Record::where('category_id',$request->category_id)->where('sub_company_id',$request->sub_company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
        }
        $category = Category::where('id',$request->category_id)->first();
        $customPaper = array(0,0,680,920);
        //return view('admin.reports.client_statement_report',compact('records','category','type','from','to'));
        $file = 'Client Ledger Report - '.date('d-m-Y').'.pdf';
        $pdf = PDF::loadView('admin.reports.client_statement_report',compact('records','category','type','from','to'));
        return $pdf->download($file);
        return $pdf->stream($file, array("Attachment" => 0));
    }

    /* Client Statement */
  
    /* Purchase Statment*/
  	public function purchaseStatment()
    {
        $sup_companies = SupplierCompany::select('id','name')->orderBy('name','asc')->get();
        return view('admin.reports.purchase_statment', compact('sup_companies'));
    }
    public function purchaseStatmentReport(Request $request)
      {
          $type = $request->type;
          $from = Carbon::parse($request->from)->format('Y-m-d');
          $to = Carbon::parse($request->to)->format('Y-m-d');
          if ($type == 'Custom Range') {
              if ($request->company_id == 'all_companies') {
                  $records = PurchaseRecord::whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('supplier_company_id');
                
              }else {
                  $records = PurchaseRecord::where('supplier_company_id',$request->category_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('supplier_company_id');
              }

          } elseif ($type == 'Today') {
              if ($request->company_id == 'all_companies') {
                  $records = PurchaseRecord::whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('supplier_company_id');
                
              }else {
                  $records = PurchaseRecord::where('supplier_company_id',$request->category_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('supplier_company_id');
              }
          } elseif ($type == 'Yesterday') {
              if ($request->company_id == 'all_companies') {
                  $records = PurchaseRecord::whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('supplier_company_id');
                
              }else {
                  $records = PurchaseRecord::where('supplier_company_id',$request->category_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('supplier_company_id');
              }
             
          } elseif ($type == 'Last 7 Days') {
              if ($request->company_id == 'all_companies') {
                  $records = PurchaseRecord::whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('supplier_company_id');
                
              }else {
                  $records = PurchaseRecord::where('supplier_company_id',$request->category_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('supplier_company_id');
              }
          } elseif ($type == 'Last 30 Days') {
              if ($request->company_id == 'all_companies') {
                  $records = PurchaseRecord::whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('supplier_company_id');
                
              }else {
                  $records = PurchaseRecord::where('supplier_company_id',$request->category_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('supplier_company_id');
              }
          } elseif ($type == 'This Month') {
              if ($request->company_id == 'all_companies') {
                  $records = PurchaseRecord::whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('supplier_company_id');
                
              }else {
                  $records = PurchaseRecord::where('supplier_company_id',$request->category_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('supplier_company_id');
              }
          } elseif ($type == 'Last Month') {
             if ($request->company_id == 'all_companies') {
                  $records = PurchaseRecord::whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('supplier_company_id');
                
              }else {
                  $records = PurchaseRecord::where('supplier_company_id',$request->category_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('supplier_company_id');
              }
          }
          $customPaper = array(0,0,680,920);
          //return view('admin.reports.purchase_statment_report',compact('records','type','from','to'));
          $file = 'Product-Ledger-Report-'.date('d-m-Y').'.pdf';
          $pdf = PDF::loadView('admin.reports.purchase_statment_report',compact('records','type','from','to'));
          //return $pdf->download($file);
          return $pdf->stream($file, array("Attachment" => 0));
      }
  	/* Purchase Statment*/
    public function incomeReport()
    {
        $categories = Category::select('id','name')->where('status',1)->orderBy('name','asc')->get();
        $companies = Company::select('id','name')->where('status',1)->orderBy('name','asc')->get();
        return view('admin.reports.sales_report', compact('categories','companies'));
    } 
  
    public function incomeReportGet(Request $request)
    {  
        $type = $request->type;
        $from = Carbon::parse($request->from)->format('Y-m-d');
        $to = Carbon::parse($request->to)->format('Y-m-d');
        if ($type == 'Custom Range') {
            if ($request->company_id == 'all_companies') {
                $records = Record::where('category_id',$request->category_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else if($request->sub_company_id == 'all_subcompanies'){
                $records = Record::where('category_id',$request->category_id)->where('company_id',$request->company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else {
                $records = Record::where('category_id',$request->category_id)->where('sub_company_id',$request->sub_company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }

        } elseif ($type == 'Today') {
            if ($request->company_id == 'all_companies') {
                $records = Record::where('category_id',$request->category_id)->whereRaw('Date(datetime) = CURDATE()')->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else if($request->sub_company_id == 'all_subcompanies'){
                $records = Record::where('category_id',$request->category_id)->where('company_id',$request->company_id)->whereRaw('Date(datetime) = CURDATE()')->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else {
                $records = Record::where('category_id',$request->category_id)->where('sub_company_id',$request->sub_company_id)->whereRaw('Date(datetime) = CURDATE()')->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
        } elseif ($type == 'Yesterday') {
            $yesterday = date("Y-m-d", strtotime( '-1 days' ));
            if ($request->company_id == 'all_companies') {
                $records = Record::where('category_id',$request->category_id)->where('datetime', $yesterday)->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else if($request->sub_company_id == 'all_subcompanies'){
                $records = Record::where('category_id',$request->category_id)->where('company_id',$request->company_id)->where('datetime', $yesterday)->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else {
                $records = Record::where('category_id',$request->category_id)->where('sub_company_id',$request->sub_company_id)->where('datetime', $yesterday)->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
        } elseif ($type == 'Last 7 Days') {
            $date = Carbon::today()->subDays(7);
            if ($request->company_id == 'all_companies') {
                $records = Record::where('category_id',$request->category_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else if($request->sub_company_id == 'all_subcompanies'){
                $records = Record::where('category_id',$request->category_id)->where('company_id',$request->company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else {
                $records = Record::where('category_id',$request->category_id)->where('sub_company_id',$request->sub_company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
        } elseif ($type == 'Last 30 Days') {
            if ($request->company_id == 'all_companies') {
                $records = Record::where('category_id',$request->category_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else if($request->sub_company_id == 'all_subcompanies'){
                $records = Record::where('category_id',$request->category_id)->where('company_id',$request->company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else {
                $records = Record::where('category_id',$request->category_id)->where('sub_company_id',$request->sub_company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
        } elseif ($type == 'This Month') {
            if ($request->company_id == 'all_companies') {
                $records = Record::where('category_id',$request->category_id)->whereBetween('datetime', [$from, $to])->where('status',1)->get()->groupBy('sub_company_id');
            }
            else if($request->sub_company_id == 'all_subcompanies'){
                $records = Record::where('category_id',$request->category_id)->where('company_id',$request->company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else {
                $records = Record::where('category_id',$request->category_id)->where('sub_company_id',$request->sub_company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
        } elseif ($type == 'Last Month') {
            if ($request->company_id == 'all_companies') {
                $records = Record::where('category_id',$request->category_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else if($request->sub_company_id == 'all_subcompanies'){
                $records = Record::where('category_id',$request->category_id)->where('company_id',$request->company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else {
                $records = Record::where('category_id',$request->category_id)->where('sub_company_id',$request->sub_company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
        }

        $file = 'Sales Report - '.date('d-m-Y').'.pdf';
        $pdf = PDF::loadView('admin.reports.sales_report_pdf',compact('records','type','from','to'));
        //return $pdf->download($file);
        return $pdf->stream($file, array("Attachment" => 0));
    }
  
  //purchase Report
  
   	public function purchaseReport()
    {
        $sup_companies = SupplierCompany::select('id','name')->orderBy('name','asc')->get();
        return view('admin.reports.purchase_report', compact('sup_companies'));
    } 

  	public function purchaseReportGet(Request $request)
    {
        $type = $request->type;
        $from = Carbon::parse($request->from)->format('Y-m-d');
        $to = Carbon::parse($request->to)->format('Y-m-d');
      
        $records = \DB::table('purchase_records')
          ->join('supplier_companies', 'purchase_records.supplier_company_id', '=', 'supplier_companies.id')
          ->join('categories', 'purchase_records.category_id', '=', 'categories.id')
          ->whereBetween('purchase_records.datetime', [$from, $to])
          ->when(request('company_id') != 'all_companies', function ($q) {
              return $q->where('purchase_records.supplier_company_id', request('company_id'));
          })
          ->where('purchase_records.status',1)
          ->where('purchase_records.deleted_status',0)
          ->select('purchase_records.total_amount', 'supplier_companies.name as supplier_company', 'categories.name as category_name')
          ->get();
          ini_set('max_execution_time', 3000);
    
        $file = 'Purchase Report - '.date('d-m-Y').'.pdf';
        $pdf = PDF::loadView('admin.reports.purchase_report_pdf',compact('records','type','from','to'));
        return $pdf->stream($file, array("Attachment" => 0));
    }
  
  
    public function accountSummary()
    {
        $categories = Category::select('id','name')->where('status',1)->orderBy('name','asc')->get();
        $companies = Company::select('id','name')->where('status',1)->orderBy('name','asc')->get();
        return view('admin.reports.accountant_summary', compact('categories','companies'));
    }

    public function accountSummaryReportGet(Request $request)
    {
      
        $type = $request->type;
        $from = Carbon::parse($request->from)->format('Y-m-d');
        $to = Carbon::parse($request->to)->format('Y-m-d');
        if ($type == 'Custom Range') {
            if ($request->company_id == 'all_companies') {

                $records = Record::where('category_id',$request->category_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else if($request->sub_company_id == 'all_subcompanies'){
                $records = Record::where('category_id',$request->category_id)->where('company_id',$request->company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else {
                $records = Record::where('category_id',$request->category_id)->where('sub_company_id',$request->sub_company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }

        } elseif ($type == 'Today') {
            if ($request->company_id == 'all_companies') {
                $records = Record::where('category_id',$request->category_id)->whereRaw('Date(datetime) = CURDATE()')->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else if($request->sub_company_id == 'all_subcompanies'){
                $records = Record::where('category_id',$request->category_id)->where('company_id',$request->company_id)->whereRaw('Date(datetime) = CURDATE()')->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else {
                $records = Record::where('category_id',$request->category_id)->where('sub_company_id',$request->sub_company_id)->whereRaw('Date(datetime) = CURDATE()')->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
        } elseif ($type == 'Yesterday') {
            $yesterday = date("Y-m-d", strtotime( '-1 days' ));
            if ($request->company_id == 'all_companies') {
                $records = Record::where('category_id',$request->category_id)->where('datetime', $yesterday)->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else if($request->sub_company_id == 'all_subcompanies'){
                $records = Record::where('category_id',$request->category_id)->where('company_id',$request->company_id)->where('datetime', $yesterday)->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else {
                $records = Record::where('category_id',$request->category_id)->where('sub_company_id',$request->sub_company_id)->where('datetime', $yesterday)->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
        } elseif ($type == 'Last 7 Days') {
            if ($request->company_id == 'all_companies') {
                $records = Record::where('category_id',$request->category_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else if($request->sub_company_id == 'all_subcompanies'){
                $records = Record::where('category_id',$request->category_id)->where('company_id',$request->company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else {
                $records = Record::where('category_id',$request->category_id)->where('sub_company_id',$request->sub_company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
        } elseif ($type == 'Last 30 Days') {
            if ($request->company_id == 'all_companies') {
                $records = Record::where('category_id',$request->category_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else if($request->sub_company_id == 'all_subcompanies'){
                $records = Record::where('category_id',$request->category_id)->where('company_id',$request->company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else {
                $records = Record::where('category_id',$request->category_id)->where('sub_company_id',$request->sub_company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
        } elseif ($type == 'This Month') {
            if ($request->company_id == 'all_companies') {
                $records = Record::where('category_id',$request->category_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else if($request->sub_company_id == 'all_subcompanies'){
                $records = Record::where('category_id',$request->category_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else {
                $records = Record::where('category_id',$request->category_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
        } elseif ($type == 'Last Month') {
            if ($request->company_id == 'all_companies') {
                $records = Record::where('category_id',$request->category_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else if($request->sub_company_id == 'all_subcompanies'){
                $records = Record::where('category_id',$request->category_id)->where('company_id',$request->company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else {
                $records = Record::where('category_id',$request->category_id)->where('sub_company_id',$request->sub_company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
        }

        $file = 'Accountant Report - '.date('d-m-Y').'.pdf';
        $pdf = PDF::loadView('admin.reports.accountant_summary_pdf',compact('records','type','from','to'));
        //return $pdf->download($file);
        return $pdf->stream($file, array("Attachment" => 0));
    }
  
  

    //company details report
    public function clientDetailReport()
    {
        $categories = Category::select('id','name')->where('status',1)->orderBy('name','asc')->get();
        $companies = Company::select('id','name')->where('status',1)->orderBy('name','asc')->get();
        return view('admin.reports.client_detail_view', compact('categories','companies'));
    }

    public function clientDetailReportGet(Request $request)
    {
        $type = $request->type;
        $from = Carbon::parse($request->from)->format('Y-m-d');
        $to = Carbon::parse($request->to)->format('Y-m-d');
        if ($type == 'Custom Range') {
            if ($request->company_id == 'all_companies') {
                $records = Record::where('category_id',$request->category_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else if($request->sub_company_id == 'all_subcompanies'){
                $records = Record::where('category_id',$request->category_id)->where('company_id',$request->company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else {
                $records = Record::where('category_id',$request->category_id)->where('sub_company_id',$request->sub_company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }

        } elseif ($type == 'Today') {
            if ($request->company_id == 'all_companies') {
                $records = Record::where('category_id',$request->category_id)->whereRaw('Date(datetime) = CURDATE()')->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else if($request->sub_company_id == 'all_subcompanies'){
                $records = Record::where('category_id',$request->category_id)->where('company_id',$request->company_id)->whereRaw('Date(datetime) = CURDATE()')->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else {
                $records = Record::where('category_id',$request->category_id)->where('sub_company_id',$request->sub_company_id)->whereRaw('Date(datetime) = CURDATE()')->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
        } elseif ($type == 'Yesterday') {
            $yesterday = date("Y-m-d", strtotime( '-1 days' ));
            if ($request->company_id == 'all_companies') {
                $records = Record::where('category_id',$request->category_id)->where('datetime', $yesterday)->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else if($request->sub_company_id == 'all_subcompanies'){
                $records = Record::where('category_id',$request->category_id)->where('company_id',$request->company_id)->where('datetime', $yesterday)->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else {
                $records = Record::where('category_id',$request->category_id)->where('sub_company_id',$request->sub_company_id)->where('datetime', $yesterday)->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
        } elseif ($type == 'Last 7 Days') {
            if ($request->company_id == 'all_companies') {
                $records = Record::where('category_id',$request->category_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else if($request->sub_company_id == 'all_subcompanies'){
                $records = Record::where('category_id',$request->category_id)->where('company_id',$request->company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else {
                $records = Record::where('category_id',$request->category_id)->where('sub_company_id',$request->sub_company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
        } elseif ($type == 'Last 30 Days') {
            if ($request->company_id == 'all_companies') {
                $records = Record::where('category_id',$request->category_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else if($request->sub_company_id == 'all_subcompanies'){
                $records = Record::where('category_id',$request->category_id)->where('company_id',$request->company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else {
                $records = Record::where('category_id',$request->category_id)->where('sub_company_id',$request->sub_company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
        } elseif ($type == 'This Month') {
            if ($request->company_id == 'all_companies') {
                $records = Record::where('category_id',$request->category_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else if($request->sub_company_id == 'all_subcompanies'){
                $records = Record::where('category_id',$request->category_id)->where('company_id',$request->company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else {
                $records = Record::where('category_id',$request->category_id)->where('sub_company_id',$request->sub_company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
        } elseif ($type == 'Last Month') {
            if ($request->company_id == 'all_companies') {
                $records = Record::where('category_id',$request->category_id)->whereBetween('datetime', [$from, $to])->where('status',1)->get()->groupBy('sub_company_id');
            }
            else if($request->sub_company_id == 'all_subcompanies'){
                $records = Record::where('category_id',$request->category_id)->where('company_id',$request->company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else {
                $records = Record::where('category_id',$request->category_id)->where('sub_company_id',$request->sub_company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
        }
        $customPaper = array(0,0,800,900);
        $file = 'Client Details Report - '.date('d-m-Y').'.pdf';
        $pdf = PDF::loadView('admin.reports.client_detail_pdf',compact('records','type','from','to'))->setPaper($customPaper);
        //return $pdf->download($file);
        return $pdf->stream($file, array("Attachment" => 0));
    }

    /* Company Details */
    public function companyDetailReport()
    {
        $categories = Category::select('id','name')->where('status',1)->orderBy('name','asc')->get();
        $companies = Company::select('id','name')->where('status',1)->orderBy('name','asc')->get();
        return view('admin.reports.company_detail_report', compact('categories','companies'));
    }

    public function companyDetailReportGet(Request $request)
    {
        $type = $request->type;
        $from = Carbon::parse($request->from)->format('Y-m-d');
        $to = Carbon::parse($request->to)->format('Y-m-d');

        $records = Record::select('category_id','company_id','sub_company_id','datetime','total_amount')->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->orderBy('datetime','asc')->get();
        $purchases = PurchaseRecord::select('supplier_company_id','datetime','total_amount')->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->orderBy('datetime','asc')->get();
        $expenses = \App\Expense::select('amount','datetime','description')->whereBetween('datetime', [$from, $to])->orderBy('datetime','asc')->get();

        $records = json_decode($records);
        $purchases = json_decode($purchases);
        $expenses = json_decode($expenses);

        $customPaper = array(0,0,800,900);
        $file = 'Company Details Report - '.date('d-m-Y').'.pdf';
        $pdf = PDF::loadView('admin.reports.company_detail_report_pdf',compact('records','type','from','to','purchases','expenses'));
        return $pdf->stream($file, array("Attachment" => 0));
    }

    // Delivery Details
    public function DeliveryDetailView(){
        $categories = Category::select('id','name')->where('status',1)->orderBy('name','asc')->get();
        $companies = Company::select('id','name')->where('status',1)->orderBy('name','asc')->get();
        return view('admin.reports.delivery_detail_view', compact('categories','companies'));
    }

    public function DeliveryDetailReportGet(Request $request)
    {
        $type = $request->type;
        $from = Carbon::parse($request->from)->format('Y-m-d');
        $to = Carbon::parse($request->to)->format('Y-m-d');
        if ($type == 'Custom Range') {
            if ($request->company_id == 'all_companies') {
                $records = Record::where('category_id',$request->category_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else if($request->sub_company_id == 'all_subcompanies'){
                $records = Record::where('category_id',$request->category_id)->where('company_id',$request->company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else {
                $records = Record::where('category_id',$request->category_id)->where('sub_company_id',$request->sub_company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }

        } elseif ($type == 'Today') {
            if ($request->company_id == 'all_companies') {
                $records = Record::where('category_id',$request->category_id)->whereRaw('Date(datetime) = CURDATE()')->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else if($request->sub_company_id == 'all_subcompanies'){
                $records = Record::where('category_id',$request->category_id)->where('company_id',$request->company_id)->whereRaw('Date(datetime) = CURDATE()')->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else {
                $records = Record::where('category_id',$request->category_id)->where('sub_company_id',$request->sub_company_id)->whereRaw('Date(datetime) = CURDATE()')->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
        } elseif ($type == 'Yesterday') {
            $yesterday = date("Y-m-d", strtotime( '-1 days' ));
            if ($request->company_id == 'all_companies') {
                $records = Record::where('category_id',$request->category_id)->where('datetime', $yesterday)->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else if($request->sub_company_id == 'all_subcompanies'){
                $records = Record::where('category_id',$request->category_id)->where('company_id',$request->company_id)->where('datetime', $yesterday)->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else {
                $records = Record::where('category_id',$request->category_id)->where('sub_company_id',$request->sub_company_id)->where('datetime', $yesterday)->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
        } elseif ($type == 'Last 7 Days') {
            if ($request->company_id == 'all_companies') {
                $records = Record::where('category_id',$request->category_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else if($request->sub_company_id == 'all_subcompanies'){
                $records = Record::where('category_id',$request->category_id)->where('company_id',$request->company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else {
                $records = Record::where('category_id',$request->category_id)->where('sub_company_id',$request->sub_company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
        } elseif ($type == 'Last 30 Days') {
            if ($request->company_id == 'all_companies') {
                $records = Record::where('category_id',$request->category_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else if($request->sub_company_id == 'all_subcompanies'){
                $records = Record::where('category_id',$request->category_id)->where('company_id',$request->company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else {
                $records = Record::where('category_id',$request->category_id)->where('sub_company_id',$request->sub_company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
        } elseif ($type == 'This Month') {
            if ($request->company_id == 'all_companies') {
                $records = Record::where('category_id',$request->category_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else if($request->sub_company_id == 'all_subcompanies'){
                $records = Record::where('category_id',$request->category_id)->where('company_id',$request->company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else {
                $records = Record::where('category_id',$request->category_id)->where('sub_company_id',$request->sub_company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
        } elseif ($type == 'Last Month') {
            if ($request->company_id == 'all_companies') {
                $records = Record::where('category_id',$request->category_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else if($request->sub_company_id == 'all_subcompanies'){
                $records = Record::where('category_id',$request->category_id)->where('company_id',$request->company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
            else {
                $records = Record::where('category_id',$request->category_id)->where('sub_company_id',$request->sub_company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status',0)->where('status',1)->get()->groupBy('sub_company_id');
            }
        }
        $customPaper = array(0,0,890,900);
        $file = 'Delivery Detail Report - '.date('d-m-Y').'.pdf';
        $pdf = PDF::loadView('admin.reports.delivery_detail_pdf',compact('records','type','from','to'))->setPaper($customPaper);
        //return $pdf->download($file);
        return $pdf->stream($file, array("Attachment" => 0));
    }
}