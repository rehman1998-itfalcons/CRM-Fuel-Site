<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SubAccount;
use App\ChartAccount;

class SubAccountController extends Controller
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
 
  
  public function manageSubAccount()
    {
        $subaccounts = SubAccount::get();
        return view('admin.accounts.sub-accounts.manage_subaccounts',compact('subaccounts'));
    }
  
  public function createSubAccount()
    {
        $chart_accounts = ChartAccount::get();
        return view('admin.accounts.sub-accounts.create_subaccount',compact('chart_accounts'));
    }
  
  public function StoreSubAccount(Request $request)
    {
        $this->validate($request, [
          'title' => 'required',
          'code' => 'required',
          'account_type' => 'required',
          'description' => 'string',
        ]);
    
    	$code = $request->code;
    	$code = $this->generateUniqueCode($code);

    	SubAccount::create([
     		'chart_account_id' => $request->account_type,
            'title' => $request->title,
            'description' => $request->description,
            'code' => $code,
   		]);
    \Session::flash('success','Record Added successfully.');
    return redirect('/manage-subAccounts');
  }
  
  public function generateUniqueCode($code)
  {
    $check = \DB::table('sub_accounts')->where('code',$code)->first();
    if ($check) {
       	$code = mt_rand(1000,9999);  
	    $check = \DB::table('sub_accounts')->where('code',$code)->first();
      	if ($check) {
        	$this->generateUniqueCode($code);	  
        }
 	}
    
    return $code;
  }
  
  public function editSubAccount($id)
    {
         $sub_account = SubAccount::find($id);
         $chart_accounts = ChartAccount::get();
        return view('admin.accounts.sub-accounts.edit_subaccount',compact('sub_account','chart_accounts'));
    }
  
   public function updateSubAccount(Request $request, $id)
    {
     $this->validate($request, [
          'title' => 'required',
          'account_type' => 'required',
          'description' => 'string',
        ]);
     
        \DB::table('sub_accounts')->where('id',$id)->update([
            'chart_account_id' => $request->account_type,
            'title' => $request->title,
            'description' => $request->description,
        ]);

        \Session::flash('success','Record Updated successfully.');
        return redirect('/manage-subAccounts');
    }
  
    public function deleteSubAccount(Request $request)
    {
        $subaccount = SubAccount::find($request->id);
        $subaccount->delete();
        \Session::flash('success','Record Deleted successfully.');
        return back();
    }
}