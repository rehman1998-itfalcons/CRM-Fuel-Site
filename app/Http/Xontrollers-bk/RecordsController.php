<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Notification;
use App\SupplierCompany;
use App\Product;
use App\Record;
use App\RecordProduct;
use App\Category;
use App\Company;
use App\User;
use Session;
use DB;
use Image;
use App\SubCompany;
use App\SubCompanyRateArea;
use App\DataTables\RecordDataTable;
use DataTables;
use Carbon\Carbon;





class RecordsController extends Controller
{
    public function __construct()
    {
        $this->middleware('superadmin');
    }


    public function index(Request $request)
    {
           // dd( $request);
           // RecordDataTable $dataTable
           if ($request->ajax()) {

               $start_date = request()->input('start_date');
               $end_date = request()->input('end_date');
               $search = request()->input('search');
                


               if ($start_date != null  && $end_date != null) {
                   $start_date =   Carbon::parse($start_date)->format('Y-m-d');
                   $end_date =   Carbon::parse($end_date)->format('Y-m-d');

                   $data = Record::join('categories','records.category_id','=','categories.id')
                   ->join('sub_companies','records.sub_company_id','=','sub_companies.id')
                   ->select(['records.*',
                   DB::raw('categories.name as category_name'),
                   DB::raw('sub_companies.name as subCompany_name')])
                   ->where('records.status', 1 && 'records.deleted_status', 0)->whereBetween('datetime', array($start_date, $end_date));
               } else {
                   $data = Record::
                   
                   join('categories','records.category_id','=','categories.id')
                   ->join('sub_companies','records.sub_company_id','=','sub_companies.id')
                   ->select(['records.*',
                   DB::raw('categories.name as category_name'),
                   DB::raw('sub_companies.name as subCompany_name')])
                   ->where('records.status', 1 && 'records.deleted_status', 0);
               }

               return Datatables::eloquent($data)

                  ->addColumn('category_name', function ($data) { 
                      return $data->category_name;
                  })
                  ->addColumn('subCompany_name', function ($data) { 
                      return $data->subCompany_name;
                  })
                   ->filterColumn('category_name', function($query, $keyword) {
                        $query->whereRaw("categories.name like ?", ["%{$keyword}%"]);
                    })
                    ->filterColumn('subCompany_name', function($query, $keyword) {
                        $query->whereRaw("sub_companies.name like ?", ["%{$keyword}%"]);
                    })
                   ->addIndexColumn()
                   ->addColumn('action', function ($data) {
                    $button = '<div class="text-center"><a href="'.route('records.edit',$data->id).'"><button type="button" name="edit" id="' . $data->id . '" class="btn btn_1" >Edit</button></a> <a href="'.route('records.show',$data->id).'"><button type="button" name="edit" id="' . $data->id . '" class="btn btn_1">View</button></a></div>';
                    return $button;
                })
                   ->rawColumns(['action'])
                   ->smart(true)
                   ->make(true);
               // ->toJson();

           }


           return view('admin.records.index');
       }
    //     $sales = Record::where('status',1)->where('deleted_status',0)->paginate(10);
    //     // $sales = Record::where('status',1)->where('deleted_status',0)->get();
    // 	return view('admin.records.index', compact('sales'));
    // }
    // return $dataTable->render('admin.records.index');



    public function create()
    {
      	$products = Product::select('id','name')->where('status',1)->get();
      	$driver_role_id = DB::table('roles')->where('slug','driver')->orWhere('slug','drivers')->first()->id;
      	$drivers = User::select('id','name')->where('role_id',$driver_role_id)->where('account_status',1)->where('deleted_status',0)->get();
      	$supplycompanies = SupplierCompany::select('id','name')->get();
      	return view('admin.records.create', compact('products','drivers','supplycompanies'));
    }

    public function load(Request $request)
    {

        $posts = Record::paginate(10);

        return response()->json($posts);
        // return view('admin.records.index');
    }

  	public function store(Request $request)
    {
		$this->validate($request, [
          	'driver_id' => 'required',
          	'load_number' => 'required',
          	'order_number' => 'required',
          	'fuel_company' => 'required',
          	'splitfullload' => 'required',
        	'billoflading.*' => 'mimes:jpeg,png,jpg,gif,svg,pdf',
        	'deleverydocket.*' => 'mimes:jpeg,png,jpg,gif,svg,pdf',
        ]);

      	$dockets = [];
      	$lading = [];

      	if ($request->has('billoflading')) {
          foreach ($request->file('billoflading') as $file) {
            $ext = $file->getClientOriginalExtension();
            if ($ext != 'pdf') {
              $obj = Image::make($file);
              $obj->resize(1024, null, function ($constraint) {
                  $constraint->aspectRatio();
              });
              $name = str_replace(" ","",rand(1000,9999).$file->getClientOriginalName());
              $obj->save('public/uploads/records/'.$name);
              array_push($lading,$name);
           	} else {
              $name = str_replace(" ","",rand(1000,9999).$file->getClientOriginalName());
              $file->move('public/uploads/records',$name);
              array_push($lading,$name);
            }
          }
        }

      	if ($request->has('deleverydocket')) {
          foreach ($request->file('deleverydocket') as $file) {
                $ext = $file->getClientOriginalExtension();
                if ($ext != 'pdf') {
                  $obj = Image::make($file);
                  $obj->resize(1024, null, function ($constraint) {
                      $constraint->aspectRatio();
                  });
                  $name = str_replace(" ","",rand(1000,9999).$file->getClientOriginalName());
                  $obj->save('public/uploads/records/'.$name);
                  array_push($dockets,$name);
                }  else {
                    $name = str_replace(" ","",rand(1000,9999).$file->getClientOriginalName());
                    $file->move('public/uploads/records',$name);
                    array_push($dockets,$name);
                }
             }
          }

      	$dockets = implode("::",$dockets);
      	$lading = implode("::",$lading);

      $max = Record::max('invoice_number');
      if($max > 7000)
      $invoice_no =  $max + 1;
      else
      $invoice_no =  90001;

      	$record = Record::create([
        	'user_id' => $request->driver_id,
          	'supplier_company_id' => $request->fuel_company,
          	'datetime' => \Carbon\Carbon::parse($request->date_time)->format('Y-m-d H:i'),
          	'load_number' => $request->load_number,
          	'order_number' => $request->order_number,
          	'splitfullload' => $request->splitfullload,
          	'delivery_docket' => $dockets,
          	'bill_of_lading' => $lading,
            'invoice_number' => $invoice_no
        ]);

      	$products = Product::select('id','name')->where('status',1)->get();
      	foreach ($products as $product) {
          $qty = $request->input('product_' . $product->id);
          if ($qty != '') {
            RecordProduct::create([
                  'record_id' => $record->id,
                  'product_id' => $product->id,
                  'qty' => $qty
            ]);
          } else {
            RecordProduct::create([
                  'record_id' => $record->id,
                  'product_id' => $product->id,
                  'qty' => 0
            ]);
          }
        }

      	/* Notification */
      	$name = DB::table('users')->where('id',$request->driver_id)->first()->name;
      	$comment = 'Submitted new application';
      	$url = route('supervisor.record.details',$record->id);
      	Notification::create([
          'sender_id' => $request->driver_id,
          'record_id' => $record->id,
          'comment' => $comment,
          'type' => 'Supervisor',
          'url' => $url
        ]);

      Session::flash('success','Application has been submitted.');
      return back();
    }

  	public function show($id)
    {
        $record = Record::find($id);
      	return view('admin.records.show', compact('record'));
    }

  	public function edit($id)
    {
      	$driver_role_id = DB::table('roles')->where('slug','driver')->orWhere('slug','drivers')->first()->id;
      	$drivers = User::select('id','name')->where('role_id',$driver_role_id)->where('account_status',1)->where('deleted_status',0)->get();
      	$supplycompanies = SupplierCompany::select('id','name')->get();
    	$record = Record::find($id);
        $categories = Category::where('status',1)->orderBy('name','asc')->get();
        $companies = Company::where('status',1)->orderBy('name','asc')->get();
      	return view('admin.records.edit', compact('record','drivers','supplycompanies','categories','companies'));
    }

  	public function update(Request $request, $id)
    {
		$this->validate($request, [
          	'driver_id' => 'required',
          	'load_number' => 'required',
          	'order_number' => 'required',
          	'fuel_company' => 'required',
          	'splitfullload' => 'required',
        	'billoflading.*' => 'mimes:jpeg,png,jpg,gif,svg,pdf',
        	'deleverydocket.*' => 'mimes:jpeg,png,jpg,gif,svg,pdf',
        ]);

      	$record = Record::find($id);
      	$sub = SubCompany::find($request->sub_company_id);
      	$cats = Category::find($request->category_id);
      	$total = 0;

      	$dockets = ($record->delivery_docket) ? explode("::",$record->delivery_docket) : [];
      	$lading = ($record->bill_of_lading) ? explode("::",$record->bill_of_lading) : [];

      	if ($request->has('billoflading')) {
          foreach ($request->file('billoflading') as $file) {
            $ext = $file->getClientOriginalExtension();
            if ($ext != 'pdf') {
              $obj = Image::make($file);
              $obj->resize(1024, null, function ($constraint) {
                  $constraint->aspectRatio();
              });
              $name = str_replace(" ","",rand(1000,9999).$file->getClientOriginalName());
              $obj->save('public/uploads/records/'.$name);
              array_push($lading,$name);
           	} else {
              $name = str_replace(" ","",rand(1000,9999).$file->getClientOriginalName());
              $file->move('public/uploads/records',$name);
              array_push($lading,$name);
            }
          }
        }

      	if ($request->has('deleverydocket')) {
          foreach ($request->file('deleverydocket') as $file) {
                $ext = $file->getClientOriginalExtension();
                if ($ext != 'pdf') {
                  $obj = Image::make($file);
                  $obj->resize(1024, null, function ($constraint) {
                      $constraint->aspectRatio();
                  });
                  $name = str_replace(" ","",rand(1000,9999).$file->getClientOriginalName());
                  $obj->save('public/uploads/records/'.$name);
                  array_push($dockets,$name);
                }  else {
                    $name = str_replace(" ","",rand(1000,9999).$file->getClientOriginalName());
                    $file->move('public/uploads/records',$name);
                    array_push($dockets,$name);
                }
             }
          }

      		$dockets = implode("::",$dockets);
      		$lading = implode("::",$lading);

        $whole_sale = 0;
      	$products = $record->products;
      	foreach ($products as $product) {
           $qty = $request->input('product_' . $product->id);
           $whole_sale = ($request->input('whole_sale_price_' . $product->id)) ? $request->input('whole_sale_price_' . $product->id) : 0;
          if ($qty != '') {

            $rate = SubCompanyRateArea::where('sub_company_id',$request->sub_company_id)->where('product_id',$product->product_id)->first();

            $whole_sale = $whole_sale;
            $discount = ($rate->discount != 0) ? $rate->discount : 0;
            $delivery_rate = ($rate->delivery_rate != 0) ? $rate->delivery_rate : 0;
            $brand_charges = ($rate->brand_charges != 0) ? $rate->brand_charges : 0;
            $cost_of_credit = ($rate->cost_of_credit != 0) ? $rate->cost_of_credit : 0;

            $total = $total + ((($whole_sale + $delivery_rate + $brand_charges + $cost_of_credit) - $discount) * $qty);

            $product->update([
                  'qty' => $qty,
                  'whole_sale' => $whole_sale,
                  'discount' => $discount,
                  'delivery_rate' => $delivery_rate,
                  'brand_charges' => $brand_charges,
                  'cost_of_credit' => $cost_of_credit
            ]);

          } else {
             $product->update([
                  'qty' => 0,
                  'whole_sale' => $whole_sale,
                  'discount' => $discount,
                  'delivery_rate' => $delivery_rate,
                  'brand_charges' => $brand_charges,
                  'cost_of_credit' => $cost_of_credit
            ]);
          }
        }

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
        	'user_id' => $request->driver_id,
          	'category_id' => $request->category_id,
          	'company_id' => $request->company_id,
          	'sub_company_id' => $request->sub_company_id,
          	'supplier_company_id' => $request->fuel_company,
          	'datetime' => \Carbon\Carbon::parse($request->datetime)->format('Y-m-d H:i'),
          	'load_number' => $request->load_number,
          	'order_number' => $request->order_number,
          	'splitfullload' => $request->splitfullload,
			'total_without_gst' => $tol,
            'gst' => $gst_tol,
            'gst_status' => $sub->gst_status,
            'split_load_status' => $request->split_load_tax,
            'split_load_charges' => $split_charges,
            'split_load_des' => $desc,
            'total_amount' => $grand_total,
          	'delivery_docket' => $dockets,
          	'bill_of_lading' => $lading,
        ]);

      	Session::flash('success','Record updated.');
      	return redirect('/records/'.$id);

    }
   public function fetchCompanyRates(Request $request)
    {
        $record = Record::find($request->record_id);
        $subcompany_id = $request->id;
      	return view('admin.records.record_prices', compact('record','subcompany_id'));
    }


    public function deleteSale($id)
    {
        $record = Record::find($id);
        $record->update(['deleted_status' => 1]);

      	Session::flash('success','Record moved to trash.');
      	return redirect('/records');
    }
}
