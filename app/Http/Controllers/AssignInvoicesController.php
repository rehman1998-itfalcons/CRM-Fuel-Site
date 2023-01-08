<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\PurchaseRecord;
use App\Record;
use App\MassMatch;
use App\MassMatchManual;
use Session;
use DB;

class AssignInvoicesController extends Controller
{
   
    public function __construct()
    {
        $this->middleware('superadmin');
    }


	public function manualInsertion($id)
    {
      	$purchase = PurchaseRecord::find($id);
      	$records = Record::select('id','invoice_number')->where('status',1)->where('mass_match_status', 0)->get();
        return view('admin.mass-match.manual_insertion', compact('purchase','records'));
    }

  	public function submitManulInsertion(Request $request)
    {
      	$invoices = $request->invoices;
      	foreach ($invoices as $key => $value) {
          	$qty = $request->input('record_qty_'.$value);
          	if ($qty > 0) {
              DB::table('records')->where('id',$value)->update(['mass_match_status' => 1]);
              DB::table('purchase_records')->where('id',$request->purchase_id)->update(['match_status' => 1]);

              MassMatch::create([
                  'record_id' => $value,
                  'purchase_record_id' => $request->purchase_id,
              ]);
            }
        }

      	Session::flash('success','Mass matched successfull completed.');
      	return redirect('/mass-match');
    }

  public function submitMassMatchManulInsertion(Request $request)
  {

        $sales = $request->invoices;
        $purchases = $request->purchases;
        if($sales == [null]){
            Session::flash('danger','Please select Invoices.');
      	    return back();
        }
     else{
        foreach ($sales as $key => $value) {
          	$qty = $request->input('sale_qty_'.$value);
          	if ($qty > 0) {
              DB::table('records')->where('id',$value)->update(['mass_match_status' => 1]);
            }
        }
     foreach ($purchases as $key => $value) {
          	$qty = $request->input('purchase_qty_'.$value);
          	if ($qty > 0) {
              DB::table('purchase_records')->where('id',$value)->update(['match_status' => 1]);
            }
        }
        $record_mass_match=[];
        foreach ($request->invoices as $invoice){
            foreach($request->purchases as $purchase){
                if($invoice!='' && $purchase != ''){

                    MassMatch::create(['record_id'=>$invoice,'purchase_record_id'=>$purchase]);
                }
            }
        }

      
        dd('Inserted');

  

      	Session::flash('success','Manual Mass matched completed successfully.');
      	return redirect('/mass-match');
    }
  }
}
