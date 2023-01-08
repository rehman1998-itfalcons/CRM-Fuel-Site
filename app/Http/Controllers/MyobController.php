<?php

namespace App\Http\Controllers;

use App\myobtokens;
use Illuminate\Http\Request;
use  App\Libraries\MyobCurl;


class MyobController extends Controller
{
    public function myobredirect(){
        return redirect('https://secure.myob.com/oauth2/account/authorize?client_id='.env('MYOB_CLIENT_ID').'&redirect_uri='.urlencode(env('MYOB_REDIRECT_URI')).'&response_type=code&scope=CompanyFile');
    }

    public function refresh_token(){

        if (coupon_status() == false){
            return redirect('/myob');
        }

        return 'Updated';

    }
    public function setting(){
        $myobsettings= \DB::table('myob')->get();
        // dd($myobsettings);
        // $myob_status = $myobsettings->api_key;

        // dd($myobsettings);

        return view('admin.myob.myob_settings',compact('myobsettings'));
    }

    public function updatesetting(Request $request){

            // dd(guid());
         $api_key = env('MYOB_CLIENT_ID');
        // $api_key =  env('MYOB_CLIENT_ID','0dfbcf92-2530-4e41-8fd8-984bcd27499c');
        $secret_key = env('MYOB_CLIENT_SECRET');
        $callback_uri = env('MYOB_REDIRECT_URI');

        dump($api_key);
        dump($secret_key);
        dump($callback_uri);
        dd('DONE');

        if( $api_key != $request->api_key){
            env('MYOB_CLIENT_ID',$request->api_key) ;
            env('MYOB_CLIENT_SECRET',$request->secret_key) ;
            env('MYOB_REDIRECT_URI',$request->callback) ;
            dd('are you sure you want to link a new myob?');
        }

        \DB::table('myob')
                ->where('status', $request->id)
                ->update(['api_key' => $request->api_key,
                'secret_key' =>$request->secret_key,
            'callback'=>$request->callback
            ]);
     return redirect()->back()->with('success', 'updated Myob settings');

        }


     public function updateMyobStatus(Request $request)
{

   if($request->myob_id == "on"){
    $request->myob_id = 1;
   }
   //dd($request->myob_id);

  if($request->mode == "true")
  {
    $affected_myob = \DB::table('myob')->update(array('status' => 1));
    return response()->json(['success'=>'Status changed successfully.']);
  }
  else
  {
     $affected_myob = \DB::table('myob')->update(array('status' => 0));
  }
}
    public function myob_callback(){

        $code = urldecode($_GET['code']);
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://secure.myob.com/oauth2/v1/authorize/',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => 'client_id='.env('MYOB_CLIENT_ID').'&client_secret='.env('MYOB_CLIENT_SECRET').'&response_type=code&scope=CompanyFile&code='.$code.'&redirect_uri='.urlencode(env('MYOB_REDIRECT_URI')).'&grant_type=authorization_code',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $response = json_decode($response);

        $tokenData = myobtokens::where('id',1)->first();
        if (isset($tokenData->access_token)){
            $myobtoken = myobtokens::find(1);
        } else {
            $myobtoken = new myobtokens();
        }
        //  dd($myobtoken);
        $myobtoken->access_token = $response->access_token;
        $myobtoken->token_type = $response->token_type;
        $myobtoken->expires_in = $response->expires_in;
        $myobtoken->refresh_token = $response->refresh_token;
        $myobtoken->scope = $response->scope;
        $myobtoken->uuid = $response->user->uid;
        $myobtoken->username = $response->user->username;
        $myobtoken->save();

        return 'Token Generated';

    }
    public function syncmyob(){
        return view('myob.syncall');
    }

    public function updateitem(){
        $myob = new MyobCurl;
         if (coupon_status() == false){
            return redirect('/myob');
        }
        $token = myobtokens::find(1)->access_token;


        $post_data =     $myob->FileGetContents(
                company_uri().'Inventory/Item/83d28512-e0e3-4d11-b2da-ed541fe05d04',
                'post',
                '{
                "UID": "83d28512-e0e3-4d11-b2da-ed541fe05d04",
                "Number": "00102",
                "Name": "Manual Data- PHP Call",
                "IsActive": true,
                "Description": "A wonderful collection of API endpoints PHP",
                "UseDescription": false,
                "CustomList1": null,
                "CustomList2": null,
                "CustomList3": null,
                "CustomField1": null,
                "CustomField2": null,
                "CustomField3": null,
                "QuantityOnHand": 0,
                "QuantityCommitted": 0,
                "QuantityOnOrder": 0,
                "QuantityAvailable": 0,
                "AverageCost": null,
                "CurrentValue": 0,
                "BaseSellingPrice": 150.00,
                "IsBought": false,
                "IsSold": false,
                "IsInventoried": false,
                "ExpenseAccount": null,
                "CostOfSalesAccount": null,
                "IncomeAccount": null,
                "AssetAccount": null,
                "BuyingDetails": null,
                "SellingDetails": null,
                "PhotoURI": null,
                "RowVersion": "6278299355531182080"
            }'
            ,
            $token

            );
        dd($post_data);

    }

      public function additem(){
        $myob = new MyobCurl;

        dd($post_data);

    }
}
