<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Category;
use Session;
use DB;

class CategoriesController extends Controller
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
     	$categories = Category::get();
 		return view('admin.categories.index', compact('categories'));
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
          
         $category = Category::create([
              	'name' => $request->name,
            	'status' => 1
          ]);

      Session::flash('success','Category created');
      return redirect('/categories/'.$category->id.'/edit');
      
    }

  	public function edit($id)
  	{
      $category = Category::find($id);
      return view('admin.categories.edit', compact('category'));
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
          
      	$category = Category::find($id);
        $category->update([
        	'name' => $request->name,
            'rate_whole_sale' => $request->rate_whole_sale,
            'whole_sale_display' => $request->whole_sale_display,
            'rate_discount' => $request->rate_discount,
            'rate_delivery_rate' => $request->rate_delivery_rate,
            'rate_brand_charges' => $request->rate_brand_charges,
            'rate_cost_of_credit' => $request->rate_cost_of_credit,
            'report_whole_sale' => $request->report_whole_sale,
            'report_discount' => $request->report_discount,
            'report_delivery_rate' => $request->report_delivery_rate,
            'report_brand_charges' => $request->report_brand_charges,
            'report_cost_of_credit' => $request->report_cost_of_credit,
            'invoice_whole_sale' => $request->invoice_whole_sale,
            'invoice_discount' => $request->invoice_discount,
            'invoice_delivery_rate' => $request->invoice_delivery_rate,
            'invoice_brand_charges' => $request->invoice_brand_charges,
            'invoice_cost_of_credit' => $request->invoice_cost_of_credit
    	]);

		Session::flash('success','Category updated');
      	return redirect('/categories');
    }
  
  	public function changeStatus(Request $request)
    {
          DB::table('categories')->where('id',$request->id)->update([
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
}
