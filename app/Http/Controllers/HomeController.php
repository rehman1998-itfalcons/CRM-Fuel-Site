<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SupplierCompany;
use App\Product;
use App\User;
use Carbon\Carbon;
use App\PurchaseRecord;
use App\Record;
use App\Notification;
use App\RecordProduct;
use App\Category;
use App\Company;
use Session;
use DB;
use Image;
use App\SubCompany;
use App\SubCompanyRateArea;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        set_time_limit(8000000);
    }


    public function index()
    {
      	$role = \Auth::user()->role->name;
      	if ($role != 'Driver')

        	return view('admin.dashboard_abd');

      	if (\Auth::user()->agreement == 1)
          	return redirect('/driver');
      	else
          	return view('agreement');
    }


  public function overallStats(Request $request){

            $from = Carbon::parse($request->from)->format('Y-m-d');
          	$to = Carbon::parse($request->to)->format('Y-m-d');
            $companies = \DB::table('companies')->whereBetween('created_at',array($from,$to))->select('id','name')->orderBy('name','asc')->get();
    return view('admin.dashboard');

  }


  	public function filter(Request $request)
    {
    	 $type = $request->type;
      	if ($type == 'Custom Range') {
			$from = Carbon::parse($request->from)->format('Y-m-d');
          	$to = Carbon::parse($request->to)->format('Y-m-d');
          	$records = PurchaseRecord::whereBetween('datetime', [$from, $to])
                      ->when(request('company') != '', function ($q) {
                        return $q->where('supplier_company_id', request('company'));
                      })
                      ->get();
        } elseif ($type == 'Today') {
        	$records = PurchaseRecord::whereRaw('Date(datetime) = CURDATE()')
                      ->when(request('company') != '', function ($q) {
                        return $q->where('supplier_company_id', request('company'));
                      })
                      ->get();
        } elseif ($type == 'Yesterday') {
          	$yesterday = date("Y-m-d", strtotime( '-1 days' ));
        	$records = PurchaseRecord::where('datetime', $yesterday)
                      ->when(request('company') != '', function ($q) {
                        return $q->where('supplier_company_id', request('company'));
                      })
                      ->get();
        } elseif ($type == 'Last 7 Days') {
          	$date = Carbon::today()->subDays(7);
        	$records = PurchaseRecord::where('datetime', '>=', $date)
                      ->when(request('company') != '', function ($q) {
                        return $q->where('supplier_company_id', request('company'));
                      })
                      ->get();
        } elseif ($type == 'Last 30 Days') {
          	$date = Carbon::today()->subDays(30);
        	$records = PurchaseRecord::where('datetime', '>=', $date)
                      ->when(request('company') != '', function ($q) {
                        return $q->where('supplier_company_id', request('company'));
                      })
                      ->get();
        } elseif ($type == 'This Month') {
        	$records = PurchaseRecord::where('datetime', '>', Carbon::now()->startOfMonth()) ->where('datetime', '<', Carbon::now()->endOfMonth())
                      ->when(request('company') != '', function ($q) {
                        return $q->where('supplier_company_id', request('company'));
                      })
                      ->get();
        } elseif ($type == 'Last Month') {
        	$records = PurchaseRecord::where('datetime', '=', Carbon::now()->subMonth()->month)
                      ->when(request('company') != '', function ($q) {
                        return $q->where('supplier_company_id', request('company'));
                      })
                      ->get();
        } else {
        	$records = PurchaseRecord::
                      when(request('company') != '', function ($q) {
                        return $q->where('supplier_company_id', request('company'));
                      })
                      ->get();
        }

		return view('admin.purchases.filter', compact('records'));
    }

  	public function salesFilter(Request $request)
    {
    	 $type = $request->type;
      	if ($type == 'Custom Range') {
            $from = Carbon::parse($request->from)->format('Y-m-d');
          	$to = Carbon::parse($request->to)->format('Y-m-d');

          	$records = Record::whereBetween('datetime', [$from, $to])->where('status',1)->get();
        } elseif ($type == 'Today') {
        	$records = Record::whereRaw('Date(datetime) = CURDATE()')->where('status',1)->get();
        } elseif ($type == 'Yesterday') {
          	$yesterday = date("Y-m-d", strtotime( '-1 days' ));
        	$records = Record::where('datetime', $yesterday)->where('status',1)->get();
        } elseif ($type == 'Last 7 Days') {
          	$date = Carbon::today()->subDays(7);
        	$records = Record::where('datetime', '>=', $date)->where('status',1)->get();
        } elseif ($type == 'Last 30 Days') {
          	$date = Carbon::today()->subDays(30);
        	$records = Record::where('datetime', '>=', $date)->where('status',1)->get();
        } elseif ($type == 'This Month') {
        	$records = Record::where('datetime', '>', Carbon::now()->startOfMonth()) ->where('datetime', '<', Carbon::now()->endOfMonth())->where('status',1)->get();
        } elseif ($type == 'Last Month') {
        	$records = Record::where('datetime', '=', Carbon::now()->subMonth()->month)->where('status',1)->get();
        }
		return view('admin.records.filter', compact('records'));
    }

  	public function addInvoiceMassMatch(Request $request)
    {
    	$record = Record::find($request->id);
      	return view('admin.mass-match.add_invoice', compact('record'));
    }
    public function addPurchaseInvoiceMassMatch(Request $request)
    {
    	$record = PurchaseRecord::find($request->id);
        $company = Company::where('id', $record->supplier_company_id)->first();
      	return view('admin.mass-match.add_purchase_invoice', compact('record', 'company'));
    }


  	public function driver()
    {
      	$role = \Auth::user()->role->name;
      	if ($role != 'Driver')
        	return redirect('/dashboard');

      	$user = \Auth::user();
      	if ($user->agreement != 1)
          	return view('agreement');


    	$products = Product::select('id','name')->where('status',1)->get();
      	$supplycompanies = SupplierCompany::select('id','name')->get();
      	return view('driver', compact('products','supplycompanies'));
    }

  	public function agreement(Request $request)
    {
    	\Auth::user()->update([
        	'agreement' => 1,
          	'agreement_time' => now()
        ]);

      	\Session::flash('success','Agreement completed');
          	return redirect('/driver');
    }

  	public function changePassword()
  	{
    	return view('changepassword');
  	}

  	public function submit(Request $request)
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
public function DriverProfileUpdate(Request $request, $id)
  {
       $user = User::find($id);
       $this->validate($request, [
          	'name' => 'string',
            'username' => 'alpha_dash|unique:users,username,'.$user->id,
          	'email' => 'email',
        	'profile_picture' => 'mimes:jpeg,png,jpg,gif,svg',
        ]);

        $image = $user->photo;

        if ($request->has('profile_picture')) {
            if($image){
            unlink('public/uploads/profile/'. $image);
            }
            $file = $request->profile_picture;
            $image1 =  time() . $file->getClientOriginalName();
            $file->move('public/uploads/profile/', $image1);
        }
      else{
         $image1 = $user->photo;
          }

            $user->update([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'photo' => $image1,
            ]);

        return redirect('/driver/profile')->with('success','Profile Updated Successfully.');
  }

}
