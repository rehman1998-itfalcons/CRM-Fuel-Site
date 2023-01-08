<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Record;
use App\SubCompanyRateArea;
use App\Category;
use App\SubCompany;
use App\Notification;
use App\Company;
use App\User;
use App\Role;
use Session;
use Image;

class SupervisorController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('supervisor');
    }  
   
  	public function dashboard()
  	{
     	$records = Record::where('supervisor_status',0)->where('deleted_status',0)->get();
 		return view('supervisor.dashboard', compact('records'));
    }
  
  	public function record($id)
    {
      	$record = Record::find($id);
      	return view('supervisor.record_details', compact('record'));
    }
  
  	public function fuelDelivery(Request $request, $id)
    {
      
      	$record = Record::find($request->record_id);
        $record->category_id = $id;
        $record->update();
        return view('supervisor.fuel_delivery_approval', compact('record'));
    }
  
  	public function submitFuelDelivery(Request $request)
    {
       
      $record = Record::find($request->record_id);
      $sub = SubCompany::find($request->sub_company_id);
      
      /* Record Prices */
      $cats = Category::where('id',$request->category_id)->first();
      $total = 0;
      foreach ($record->products as $product) {
        $rate = SubCompanyRateArea::where('sub_company_id',$request->sub_company_id)->where('product_id',$product->product_id)->first();
        if($cats->whole_sale_display == 2){
         $whole_sale = ($rate->whole_sale != 0) ? $rate->whole_sale : 0; 
        }
        else{
          $whole_sale = ($request->input('whole_sale_price_'. $product->id) ?? 0); 
        }
       
        $discount = ($rate) ? $rate->discount : 0;
        $delivery_rate = ($rate) ? $rate->delivery_rate : 0;
        $brand_charges = ($rate) ? $rate->brand_charges : 0;
        $cost_of_credit = ($rate) ? $rate->cost_of_credit : 0;
        
        $total = $total + ((($whole_sale + $delivery_rate + $brand_charges + $cost_of_credit) - $discount) * $product->qty);
        
        $product->update([
          'whole_sale' => $whole_sale,
          'discount' => $discount,
          'delivery_rate' => $delivery_rate,
          'brand_charges' => $brand_charges,
          'cost_of_credit' => $cost_of_credit
        ]);
      }
      
      /* Record */
      $desc = ($request->split_load_tax == 1) ? $request->split_load_des : null;
      $split_charges = ($request->split_load_tax == 1) ? $sub->split_load : 0;
      $gst = ($sub->gst != '' || $sub->gst != null) ? $sub->gst : 0;
      $gst_tol = ($sub->gst_status == 'Excluded') ? $gst : 0;
      $tol = round($total,2);
      
      if ($sub->gst_status == 'Excluded') {
        $tol_amount = ($gst * $tol) / 100;
        $gst_tol = round($tol_amount,2);        
        $gst_amount = $gst_tol;
      } else {
      	$tol_amount = $gst * $tol / (100 + $gst);
        $gst_tol = round($tol_amount,2);        
        $gst_amount = 0;
      }

      $grand = $total + $gst_amount + $split_charges;
      $grand_total = round($grand,2);
      
      $record->update([
    	'company_id' => $request->company_id,
    	'sub_company_id' => $request->sub_company_id,
        'total_without_gst' => $tol,
        'gst' => $gst_tol,
        'gst_status' => $sub->gst_status,
        'split_load_status' => $request->split_load_tax,
        'split_load_charges' => $split_charges,
        'split_load_des' => $desc,
        'total_amount' => $grand_total,
        'supervisor_status' => 1,
        'invoice_no' => 1
      ]);
      
      /* Delete Old Notification */
      
      	/* Notification */
      	$name = \DB::table('users')->where('id',$record->user_id)->first()->name;
      	$comment = 'application approved by supervisor';
      	$url = route('accountant.record.details',$record->id);
      	Notification::create([
          'sender_id' => $record->user_id,
          'record_id' => $record->id,
          'comment' => $comment,
          'type' => 'Accountant',
          'url' => $url
        ]);
      
      Session::flash('success','Application approved successfully.');
      return redirect('/supervisor/dashboard');
    }
  
  	public function fuelDeliveryUpdate($id)
    {
      	$driver_role_id = \DB::table('roles')->where('slug','driver')->orWhere('slug','drivers')->first()->id;
      	$drivers = User::select('id','name')->where('role_id',$driver_role_id)->where('account_status',1)->where('deleted_status',0)->get();
      	$record = Record::find($id);
      	$categories = Category::where('status',1)->orderBy('name','asc')->get();
      	$supplycompanies = \App\SupplierCompany::select('id','name')->get();
      	return view('supervisor.fuel_delivery_update', compact('record','categories','drivers','supplycompanies'));
    }
  
  	public function fuelDeliveryFormUpdate(Request $request)
    {
     $this->validate($request, [
          	'driver_id' => 'required',
          	'fuel_company' => 'required',
        	'billoflading.*' => 'mimes:jpeg,png,jpg,gif,svg,pdf',
        	'deleverydocket.*' => 'mimes:jpeg,png,jpg,gif,svg,pdf',
        ]);
      	$record = Record::find($request->record_id);
      if($record->bill_of_lading){
        $arr1 = explode("::",$record->bill_of_lading);
        }
      else{
        $arr1 = [];
      }
      	if ($request->has('billoflading')) {
          foreach ($request->file('billoflading') as $file) {
            $ext = $file->getClientOriginalExtension();
            if ($ext != 'pdf') {
              $obj = Image::make($file);
              $obj->resize(1024, null, function ($constraint) {
                  $constraint->aspectRatio();
              });
              $name = str_replace(" ","",time().$file->getClientOriginalName());
              $obj->save('public/uploads/records/'.$name);
            } else {
              $name = str_replace(" ","",rand(1000,9999).$file->getClientOriginalName());
              $file->move('public/uploads/records',$name);
            }
            array_push($arr1,$name);
          }          
        }
      	$lading = implode("::",$arr1);

    if($record->delivery_docket){
        $arr2 = explode("::",$record->delivery_docket);
      }
      else{
        $arr2 = [];
      }  
      
      if ($request->has('deleverydocket')) {
            foreach ($request->file('deleverydocket') as $file) {
              $ext = $file->getClientOriginalExtension();
              if ($ext != 'pdf') {
                $obj = Image::make($file);
                $obj->resize(1024, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $name = str_replace(" ","",time().$file->getClientOriginalName());
                $obj->save('public/uploads/records/'.$name);
              }  else {
                  $name = str_replace(" ","",rand(1000,9999).$file->getClientOriginalName());
                  $file->move('public/uploads/records',$name);
              }
              array_push($arr2,$name);
        	}
      	}
        
      	$dockets = implode("::",$arr2);      
      	$record->update([
        	'user_id' => $request->driver_id,
          	'supplier_company_id' => $request->fuel_company,
          	'datetime' => \Carbon\Carbon::parse($request->datetime)->format('Y-m-d H:i'),
          	'load_number' => $request->load_number,
          	'order_number' => $request->order_number,
          	'splitfullload' => $request->splitfullload,
          	'delivery_docket' => $dockets,
          	'bill_of_lading' => $lading
        ]);
      
      	//$products = Product::select('id','name')->where('status',1)->get();
      	foreach ($record->products as $product) {
          $qty = $request->input('product_' . $product->id);
          if ($qty != '') {
            $product->update([
                  'qty' => $qty
            ]);
          }
        }
      
      Session::flash('success','Application has been updated.');
      return back(); 
      
      
      
    }
  
  	public function getCompanies()
    {
    	$companies = Company::select('id','name')->get();
      	return json_encode($companies);
    }
  
  	public function getSubCompanies(Request $request)
    {
    	 $companies = SubCompany::select('id','name')->where('company_id',$request->id)->orwhere('category_id',$request->cat)->where('status',1)->orderBy('name','asc')->get();
      	 return json_encode($companies);
    }
  
  	public function deleteApplication($id)
    {
      	$record = Record::find($id);
      	$record->update([
        	'deleted_status' => 1  
        ]);
      
      	Session::flash('success','Application deleted successfully.');
      	return redirect('/supervisor/dashboard');
    }
  
  	public function fetchCompanyRates(Request $request)
    {
        $record = Record::find($request->record_id);
        $subcompany_id = $request->id;
      	return view('supervisor.sub_companies_prices', compact('record','subcompany_id'));
    }
}
