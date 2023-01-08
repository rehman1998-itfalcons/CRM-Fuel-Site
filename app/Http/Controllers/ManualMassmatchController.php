<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PurchaseRecord;
use App\Record;
use App\MassMatchManual;

class ManualMassmatchController extends Controller
{
    public function addManualMassMatch(){
      $records = Record::select('id','invoice_number')->where('status',1)->where('deleted_status',0)->where('mass_match_status', 0)->limit(500)->get();
      $purchase_records = PurchaseRecord::select('id','invoice_number')->where('match_status',0)->where('status',1)->limit(500)->get();
      return view('admin.mass-match.add_manual_mass_match', compact('purchase_records', 'records'));
    }

   public function show()
    {
      	$matches = MassMatchManual::paginate(10);
        return view('admin.mass-match.manual-massmatch-show', compact('matches'));
    }
  public function reConsile(Request $request)
  {
           $match = MassMatchManual::find($request->mass_match_id);
           $sales = explode(',',$match->sales);
           $purchases = explode(',',$match->purchases);

           \App\Record::whereIn('id',$sales)->where('mass_match_status',1)->update([
             'mass_match_status' => 0,
           ]);

           \App\PurchaseRecord::whereIn('id',$purchases)->where('match_status',1)->update([
             'match_status' => 0,
           ]);

           $match->delete();

      	\Session::flash('success','Purchase and sale orders re-consiled.');
      	return back();
  }
}
