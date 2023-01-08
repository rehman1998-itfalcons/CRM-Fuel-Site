<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Record;
use Session;
use DataTables;
use Carbon\Carbon;

class TrashController extends Controller
{
    



    public function index(Request $request)
    {

           if ($request->ajax()) {

         
            $sales = Record::where('deleted_status',1);
            

               return Datatables::eloquent($sales)
                   ->addColumn('category_name', function (Record $record) {
                       return $record->category->name ?? '';
                   })
                   ->addColumn('subCompany_name', function (Record $record) {
                       return $record->subCompany->name ?? '';
                   })
                   ->addIndexColumn()
                   ->addColumn('action', function ($sales) {
                  

                    $btn = '<div class="text-center"> <a href="'.route('trash.details',$sales->id).'"><button  type="button" name="view" id="' . $sales->id . '" class=" btn  btn-primary ">View</button></a></div>';
                     return $btn;
                   })
                   ->rawColumns(['action'])
                   ->make(true);
               

           }

    
       


           return view('admin.trash.index');
       }

  	public function show($id)
    {
      	$record = Record::where('id',$id)->where('deleted_status',1)->first();
      	if ($record)
	    	return view('admin.trash.show', compact('record'));
      	else
          	return abort(403, "Unauthorized action");
    }

  	public function restoreDelete($id)
    {
      	$record = Record::find($id);
        $record->update(['deleted_status' => 0]);

      	Session::flash('success','Sale record restored successfully.');
		return redirect('/trash');
    }

  	public function permanentDelete($id)
    {
      	$record = Record::find($id);
     
      	foreach ($record->products as $product)
        	$product->delete();

      
      	foreach ($record->notifications as $notification)
        	$notification->delete();


      	foreach ($record->transactionHistory as $history)
        	$history->delete();

   
      	$matches = \App\MassMatch::where('record_id',$id)->get();
      	foreach ($matches as $match)
        	$match->delete();

      	
      	$dockets = explode("::",$record->delivery_docket);
      	$ladings = explode("::",$record->bill_of_lading);

      	if (count($dockets) > 0) {
        	foreach ($dockets as $key => $value) {
            	if (file_exists('public/uploads/records/'.$value)) {
                	unlink('public/uploads/records/'.$value);
                }
            }
        }

      	if (count($ladings) > 0) {
        	foreach ($ladings as $key => $value) {
            	if (file_exists('public/uploads/records/'.$value)) {
                	unlink('public/uploads/records/'.$value);
                }
            }
        }

      	$record->delete();

      	Session::flash('success','Sale record deleted successfully.');
		return redirect('/trash');
    }
}
