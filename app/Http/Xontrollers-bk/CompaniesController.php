<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CompanyEmail;
use App\Category;
use App\Company;
use Session;
use DB;
class CompaniesController extends Controller
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
 
  
  	public function index()
  	{
     	$categories = Category::where('status',1)->orderBy('name','asc')->get();
     	$companies = Company::paginate(10);
      	$companies_list = Company::select('id','name')->get();
 		return view('admin.companies.index', compact('companies','categories','companies_list'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
  
    public function store(Request $request)
    {
       	
          $this->validate($request,[
              'name' => 'required'
          ]);
          
          $company = Company::create([
              	'name' => $request->name,
            	'status' => 1
          ]);
          Session::flash('success','Main Company created');
      
      	return redirect('/companies/'.$company->id.'/edit');
    }
  
  	public function edit($id)
    {
      $company = Company::find($id);
      return view('admin.companies.edit', compact('company'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      	$this->validate($request,[
      		'name' => 'required'
      	]);
          
     	DB::table('companies')->where('id',$id)->update([
      		'name' => $request->name,
        	'phone_no' => $request->phone_no,
        	'address' => $request->address,
        	'tax_no' => $request->tax_no
      	]);
      
        $count = $request->counter;
        if ($count >= 1) {
            for ($i = 1; $i <= $count; $i++) {
                $email = $request->input('email_' . $i);
                if ($email != '') {
                  	$check = CompanyEmail::where('company_id',$id)->where('email_address',$email)->first();
                  	if (!$check) {
                      CompanyEmail::create([
                          'company_id' => $id,
                          'email_address' => $email
                      ]);
                    }
                }
            }
        }
      	Session::flash('success','Main Company updated');
    	return redirect('/companies');
    }
  
  	public function changeStatus(Request $request)
    {
    	
          DB::table('companies')->where('id',$request->id)->update([
            'status' => $request->status,
          ]);
          
          Session::flash('success','Status changed.');
     
      	return back();
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
  
  	public function deleteEmail(Request $request)
    {
      $email = CompanyEmail::find($request->id);
      $email->delete();
      
      return '1';
    }
}
