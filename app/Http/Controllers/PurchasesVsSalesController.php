<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PurchaseRecord;
use App\MassMatch;
use App\Product;
use App\MassMatchManual;

class PurchasesVsSalesController extends Controller
{
    public function index()
    {
      	$matches = MassMatch::get();
      	$products = Product::select('id','name')->get();
    	return view('admin.reports.purchases-vs-sales', compact('matches','products'));
    }

    public function show($id)
    {
      	$purchase = PurchaseRecord::find($id);
    	return view('admin.reports.purchases-vs-sales-details', compact('purchase'));
    }

  	public function purchasesVsSalesCompany()
    {
      $products = Product::select('id','name')->get();
      $matches = \DB::table('mass_matches')
  		->join('records', 'records.id', '=', 'mass_matches.record_id')
  		->join('purchase_records', 'purchase_records.id', '=', 'mass_matches.purchase_record_id')
  		->join('supplier_companies', 'supplier_companies.id', '=', 'purchase_records.supplier_company_id')
  		->join('companies', 'companies.id', '=', 'records.company_id')
  		->join('sub_companies', 'sub_companies.id', '=', 'records.sub_company_id')
  		->select('mass_matches.purchase_record_id', 'purchase_records.invoice_number as purchase_invoice', 'purchase_records.datetime as purchase_date', 'purchase_records.total_amount as purchase_total', 'supplier_companies.name as purchase_company', 'records.invoice_number as sale_invoice', 'records.datetime as sale_date', 'records.total_amount as sale_total', 'companies.name as main_company', 'sub_companies.name as sub_company')
        ->paginate(8);

    	return view('admin.reports.purchases-vs-sales-company', compact('matches','products'));
    }
    public function manualindex()



    {



        $manualmatches1 = MassMatchManual::paginate(5);

         $manualmatches=[];

        foreach ($manualmatches1 as $key => $value) {

                $purchases=explode(",", $value->purchases);

                $sales=explode(",", $value->sales);



                foreach ($purchases as $key1 => $purchase) {

                    foreach ($sales as $key2 => $sale) {



                        $tem_array=["id"=>($key+1)*($key1+1)*($key2+1),"purchases"=>$purchase, "sales"=>$sale];

                        array_push($manualmatches, $tem_array);

                    }

                }



        }

        $products = Product::select('id','name')->get();





        return view('admin.reports.manual-purchase-record.purchases-vs-sales', compact('products','manualmatches'));



    }







    public function manualshow($id)



    {



        $purchase = PurchaseRecord::find($id);



        return view('admin.reports.manual-purchase-record.purchases-vs-sales-details', compact('purchase'));



    }







    public function manualpurchasesVsSalesCompany()



    {


        $products = Product::select('id','name')->get();
        $matches = \DB::table('mass_matches')



                            ->join('records', 'records.id', '=', 'mass_matches.record_id')



                             ->join('purchase_records', 'purchase_records.id', '=', 'mass_matches.purchase_record_id')



                      


                            ->join('supplier_companies', 'supplier_companies.id', '=', 'purchase_records.supplier_company_id')



                            ->join('companies', 'companies.id', '=', 'records.company_id')



                            ->join('sub_companies', 'sub_companies.id', '=', 'records.sub_company_id')



                            ->select('mass_matches.purchase_record_id', 'purchase_records.invoice_number as purchase_invoice', 'purchase_records.datetime as purchase_date', 'purchase_records.total_amount as purchase_total', 'supplier_companies.name as purchase_company', 'records.invoice_number as sale_invoice', 'records.datetime as sale_date', 'records.total_amount as sale_total', 'companies.name as main_company', 'sub_companies.name as sub_company')->get();











        return view('admin.reports.manual-purchase-record.purchases-vs-sales-company', compact('matches','products'));



    }
}
