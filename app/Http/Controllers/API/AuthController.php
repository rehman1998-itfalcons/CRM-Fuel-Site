<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\ResponseController as ResponseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Driver;
use App\Record;
use App\Notification;
use App\SupplierCompany;
use App\Product;
use Intervention\Image\Facades\Image as Image;
use App\RecordProduct;
use Illuminate\Support\Facades\Validator;

class AuthController extends ResponseController
{


    //create user
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|',
            'email' => 'required|string|email|unique:users',
            'password' => 'required',
            'confirm_password' => 'required|same:password'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        if ($user) {
            $success['token'] =  $user->createToken('token')->accessToken;
            $success['message'] = "Registration successfull..";
            return $this->sendResponse($success);
        } else {
            $error = "Sorry! Registration is not successfull.";
            return $this->sendError($error, 401);
        }
    }

    //login
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }

        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            $error = "Unauthorized";
            return $this->sendError($error, 401);
        }
        $user = $request->user();
        $success['token'] =  $user->createToken('token')->accessToken;
        $success['message'] = "Login successfully..";
        return $this->sendResponse($success);
    }

    //logout
    public function logout(Request $request)
    {

        $isUser = $request->user()->token()->revoke();
        if ($isUser) {
            $success['message'] = "Successfully logged out.";
            return $this->sendResponse($success);
        } else {
            $error = "Already Log out";
            return $this->sendError($error);
        }
    }

    //post driver data

    public function postDriver(Request $request)
    {

        // dd($request);
        $driverId = $request->user()->token()->user_id;
        // dd($isUser);
       $rule  = Validator::make($request->all(), [
            // 'driver_id' => 'required',
            'load_number' => 'required',
            'order_number' => 'required',
            'fuel_company' => 'required',
            'splitfullload' => 'required',
            'billoflading.*' => 'mimes:jpeg,png,jpg,gif,svg,pdf',
            'deleverydocket.*' => 'mimes:jpeg,png,jpg,gif,svg,pdf',
        ]);

        if ($rule->fails()) {

            return $this->sendError($rule->errors());
        }

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
                    $name = str_replace(" ", "", rand(1000, 9999) . $file->getClientOriginalName());
                    $obj->save('public/uploads/records/' . $name);
                    array_push($lading, $name);
                } else {
                    $name = str_replace(" ", "", rand(1000, 9999) . $file->getClientOriginalName());
                    $file->move('public/uploads/records', $name);
                    array_push($lading, $name);
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
                    $name = str_replace(" ", "", rand(1000, 9999) . $file->getClientOriginalName());
                    $obj->save('public/uploads/records/' . $name);
                    array_push($dockets, $name);
                } else {
                    $name = str_replace(" ", "", rand(1000, 9999) . $file->getClientOriginalName());
                    $file->move('public/uploads/records', $name);
                    array_push($dockets, $name);
                }
            }
        }

        $dockets = implode("::", $dockets);
        $lading = implode("::", $lading);

        $max = Record::max('invoice_number');
        if ($max > 7000){
            $invoice_no =  $max + 1;}
        else{
            $invoice_no =  90001;}
            
        $select_fuel_compnay=  SupplierCompany::select('id', 'name')->where('name',$request->fuel_company)->first();
        $record = Record::create([
            'user_id' => $driverId,
            'supplier_company_id' => $select_fuel_compnay->id,
            'datetime' => \Carbon\Carbon::parse($request->date_time)->format('Y-m-d H:i'),
            'load_number' => $request->load_number,
            'order_number' => $request->order_number,
            'splitfullload' => $request->splitfullload,
            'delivery_docket' => $dockets,
            'bill_of_lading' => $lading,
            'invoice_number' => $invoice_no
        ]);
        
       
       if (isset($request->totalDieselCount) && $request->totalDieselCount > 0){
           RecordProduct::create([
                    'record_id' => $record->id,
                    'product_id' => 1,
                    'qty' => $request->totalDieselCount
            ]);
       } else {
           RecordProduct::create([
                    'record_id' => $record->id,
                    'product_id' => 1,
                    'qty' => 0
            ]);
       }
       if (isset($request->totalBlueCount) && $request->totalBlueCount > 0){
           RecordProduct::create([
                    'record_id' => $record->id,
                    'product_id' => 8,
                    'qty' => $request->totalBlueCount
            ]);
       } else {
           RecordProduct::create([
                    'record_id' => $record->id,
                    'product_id' => 8,
                    'qty' => 0
            ]);
       }
       if (isset($request->totalPREMIUM2Count) && $request->totalPREMIUM2Count > 0){
           RecordProduct::create([
                    'record_id' => $record->id,
                    'product_id' => 7,
                    'qty' => $request->totalPREMIUM2Count
            ]);
       } else {
           RecordProduct::create([
                    'record_id' => $record->id,
                    'product_id' => 7,
                    'qty' => 0
            ]);
       }
       if (isset($request->totalULTIMATECount) && $request->totalULTIMATECount > 0){
           RecordProduct::create([
                    'record_id' => $record->id,
                    'product_id' => 6,
                    'qty' => $request->totalULTIMATECount
            ]);
       } else {
           RecordProduct::create([
                    'record_id' => $record->id,
                    'product_id' => 6,
                    'qty' => 0
            ]);
       }
       if (isset($request->totalEXTRACount) && $request->totalEXTRACount > 0){
           RecordProduct::create([
                    'record_id' => $record->id,
                    'product_id' => 5,
                    'qty' => $request->totalEXTRACount
            ]);
       } else {
           RecordProduct::create([
                    'record_id' => $record->id,
                    'product_id' => 5,
                    'qty' => 0
            ]);
       }
       if (isset($request->totalPREMIUMCount) && $request->totalPREMIUMCount > 0){
           RecordProduct::create([
                    'record_id' => $record->id,
                    'product_id' => 4,
                    'qty' => $request->totalPREMIUMCount
            ]);
       } else {
           RecordProduct::create([
                    'record_id' => $record->id,
                    'product_id' => 4,
                    'qty' => 0
            ]);
       }
       if (isset($request->totalPULPCount) && $request->totalPULPCount > 0){
           RecordProduct::create([
                    'record_id' => $record->id,
                    'product_id' => 3,
                    'qty' => $request->totalPULPCount
            ]);
       } else {
           RecordProduct::create([
                    'record_id' => $record->id,
                    'product_id' => 3,
                    'qty' => 0
            ]);
       }
       if (isset($request->totalUnleadedCount) && $request->totalUnleadedCount > 0){
           RecordProduct::create([
                    'record_id' => $record->id,
                    'product_id' => 2,
                    'qty' => $request->totalUnleadedCount
            ]);
       } else {
           RecordProduct::create([
                    'record_id' => $record->id,
                    'product_id' =>2,
                    'qty' => 0
            ]);
           
       }
        

        // $products = Product::select('id', 'name')->where('status', 1)->get();
        // foreach ($products as $product) {
        //     $qty = $request->input('product_' . $product->id);
        //     if ($qty != '') {
        //         RecordProduct::create([
        //             'record_id' => $record->id,
        //             'product_id' => $product->id,
        //             'qty' => $qty
        //         ]);
        //     } else {
        //         RecordProduct::create([
        //             'record_id' => $record->id,
        //             'product_id' => $product->id,
        //             'qty' => 0
        //         ]);
        //     }
        // }


        $name = DB::table('users')->where('id', $driverId)->first()->name;
        $comment = 'Submitted new application';
        $url = route('supervisor.record.details', $record->id);
        Notification::create([
            'sender_id' => $driverId,
            'record_id' => $record->id,
            'comment' => $comment,
            'type' => 'Supervisor',
            'url' => $url
        ]);

        $res = ['success'=>["New Driver Entry Added Successfully"]];
        if($res){
        return $this->sendResponse($res);
        }
        $err =['error'=>"Something Went Wrong, Driver's Entry not completed"];
        return $this->sendError($err);
    }

    //get fuel company api

    public function getFuelCompany(Request $request)
    {
        $supplycompanies = SupplierCompany::select('id', 'name')->get();
        if ($supplycompanies) {
            return $this->sendResponse($supplycompanies);
        }
        $error = "Fuel Company not Found";
            return $this->sendError($error, 401);
    }

    public function recordProduct(Request $request)
    {
        $products = Product::select('id', 'name')->where('status', 1)->get();
        if ($products) {
            return $this->sendResponse($products);
        }
        $error = "Product not Found";
            return $this->sendError($error, 401);
    }
}
