<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ChartAccountType;
use App\ChartAccount;

class ChartAccountTypeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('superadmin');
    }  
 
  
  public function manageChartAccountType()
    {
        $chartaccounttypes = ChartAccountType::get();
        return view('admin.accounts.chartaccounts.chart-account-types.manage_charts_account_type',compact('chartaccounttypes'));
    }
  
  public function createChartAccountType()
    {
        return view('admin.accounts.chartaccounts.chart-account-types.create_chart_account_type');
    }
  
  public function StoreChartAccountType(Request $request)
    {
        $this->validate($request, [
          'title' => 'required',
        ]);
    
   		ChartAccountType::create([
     		'title' => $request->title
   		]);
    \Session::flash('success','Record Added successfully.');
    return redirect('/manage-account-type');
  }
  
  public function editChartAccountType($id)
    {
        $chartaccounttype = ChartAccountType::find($id);
        return view('admin.accounts.chartaccounts.chart-account-types.edit_chart_account_type',compact('chartaccounttype'));
    }
  
   public function updateChartAccountType(Request $request, $id)
    {
     $this->validate($request, [
          'title' => 'required',
        ]);
     
        $chartaccounttype = ChartAccountType::find($id);
        $chartaccounttype->title = $request->title;
        $chartaccounttype->update();
        \Session::flash('success','Record Updated successfully.');
        return redirect('/manage-account-type');
    }
  
    public function deleteChartAccountType(Request $request)
    {
        $chartaccounttype = ChartAccountType::find($request->id);
        $chart_account = ChartAccount::where('account_type',$chartaccounttype->id)->get();
        if($chart_account->count() > 0){
         return back()->with('danger','You can not delete this record because it is assigned to an entry.'); 
        }
      else{
        $chartaccounttype->delete();
        \Session::flash('success','Record Deleted successfully.');
        return back();
      }    
    }
}