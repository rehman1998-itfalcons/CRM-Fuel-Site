<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Record;
use Session;
use DataTables;
use Carbon\Carbon;

class TrashController extends Controller
{
    // public function index()
    // {
    //   	$sales = Record::where('deleted_status',1)->get();
    // 	return view('admin.trash.index', compact('sales'));
    // }



    public function index(Request $request)
    {

           if ($request->ajax()) {

            //    $start_date = request()->input('start_date');
            //    $end_date = request()->input('end_date');


            //    if ($start_date != null  && $end_date != null) {
            //        $start_date =   Carbon::parse($start_date)->format('Y-m-d');
            //        $end_date =   Carbon::parse($end_date)->format('Y-m-d');

            //        $data = Record::where('status', 1 && 'deleted_status', 0)->whereBetween('datetime', array($start_date, $end_date));
            //    } else {
            //        $data = Record::where('status', 1)->where('deleted_status', 0);
            //    }
            $sales = Record::where('deleted_status',1);
            // dd($sales);

               return Datatables::eloquent($sales)
                   ->addColumn('category_name', function (Record $record) {
                       return $record->category->name ?? '';
                   })
                   ->addColumn('subCompany_name', function (Record $record) {
                       return $record->subCompany->name ?? '';
                   })
                   ->addIndexColumn()
                   ->addColumn('action', function ($sales) {
                    // $button = '<button type="button" name="edit" id="' . $sales->id . '" class="status_btn" >Edit</button>';
                    //    $button .= '&nbsp;&nbsp;&nbsp;<button type="button" name="View" id="' . $sales->id . '" class="status_btn">View</button>';
                    //    return $button;
                    //$idx = $sales->id;
                    $btn = '<div class="text-center"> <a href="'.route('trash.details',$sales->id).'"><button style="min-width: 44px; padding:0px;" type="button" name="view" id="' . $sales->id . '" class="status_btn  ">View</button></a></div>';
                    return $btn;
                   })
                   ->rawColumns(['action'])
                   ->make(true);
               // ->toJson();

           }

        //    ->addColumn('action', function ($data) {
        //     $button = '<div class="text-center"><a href="'.route('records.edit',$data->id).'"><button style="min-width: 44px; padding:0px;" type="button" name="edit" id="' . $data->id . '" class="status_btn" >Edit</button></a> <a href="'.route('records.show',$data->id).'"><button style="min-width: 44px; padding:0px;" type="button" name="edit" id="' . $data->id . '" class="status_btn btn-primary ">View</button></a></div>';
        //     return $button;
        // })


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
      	/* Delete Products */
      	foreach ($record->products as $product)
        	$product->delete();

      	/* Delete Notification */
      	foreach ($record->notifications as $notification)
        	$notification->delete();

      	/* Transaction History */
      	foreach ($record->transactionHistory as $history)
        	$history->delete();

      	/* Delete Mass Match */
      	$matches = \App\MassMatch::where('record_id',$id)->get();
      	foreach ($matches as $match)
        	$match->delete();

      	/* Delete Lading & Dockets */
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
