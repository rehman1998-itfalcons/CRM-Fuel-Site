<?php

use Illuminate\Support\Facades\DB;

use App\myobtokens;



if (!function_exists('coupon_status')) {



    function coupon_status(){

        $tokenData = DB::table('myobtokens')->where('id',1)->first();
        if (empty($tokenData)) {
            return false;

        }

        $time_in_seconds = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $tokenData->updated_at)->diffInSeconds(\Carbon\Carbon::now());

        // dd($time_in_seconds);

        if ($time_in_seconds > 1190){

            if (update_token() == true){

                return true;

            }

            return false;

        }

        return true;



    }

    function company_uri(){
        // env('MYOB_CLIENT_ID')
    return env('MYOB_COMPANY_URI').env('MYOB_GUID').'/';
    }

    function guid(){

         return env('MYOB_CLIENT_ID');

        }
    function secret_key(){
        return env('MYOB_CLIENT_SECRET');
        }
    function callback(){
        return env('MYOB_REDIRECT_URI');
        }




    function update_token(){



        $tokenData = myobtokens::where('id',1)->first();

        $refresh_token_code = $tokenData->refresh_token;

        $access_token_code = $tokenData->access_token;

        $curl = curl_init();

        curl_setopt_array($curl, array(

          CURLOPT_URL => 'https://secure.myob.com/oauth2/v1/authorize',

          CURLOPT_RETURNTRANSFER => true,

          CURLOPT_MAXREDIRS => 10,

          CURLOPT_TIMEOUT => 0,

          CURLOPT_FOLLOWLOCATION => true,

          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

          CURLOPT_CUSTOMREQUEST => 'POST',

          CURLOPT_POSTFIELDS => 'client_id='.env('MYOB_CLIENT_ID').'&client_secret='.env('MYOB_CLIENT_SECRET').'&refresh_token='.$refresh_token_code.'&grant_type=refresh_token',

          CURLOPT_HTTPHEADER => array(

            'Content-Type: application/x-www-form-urlencoded',

            'Authorization: Bearer  '.$access_token_code,

            'x-myobapi-key: '.env('MYOB_API_KEY'),

            'x-myobapi-version: v0',

            'Accept: application/json',

          ),

        ));



        $response = curl_exec($curl);



        curl_close($curl);



        $response = json_decode($response);

        if (isset($response->error)){

            return false;

        }

        $myobtoken = myobtokens::find(1);

        $myobtoken->access_token = $response->access_token;

        $myobtoken->token_type = $response->token_type;

        $myobtoken->expires_in = $response->expires_in;

        $myobtoken->refresh_token = $response->refresh_token;

        $myobtoken->scope = $response->scope;

        $myobtoken->save();

        return true;

    }



}

