<?php


namespace App\Libraries;
// $browser = new Browser;
// $post = json_encode('My Data array');
// $browser->FileGetContents('http://api.myob/','post',$post, ['Authorize: berare token','x-myobapi-version: 1.0'],);
class MyobCurl
{
    public function FileGetContents($url, $method = "", $postData = "" , $token = '', $headers = [])
    {

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        if ($headers) {
			curl_setopt($ch,CURLOPT_HTTPHEADER, $headers);
        } else {
        	curl_setopt($ch,CURLOPT_HTTPHEADER,  
        	[
        		'Authorization: Bearer '.$token,
        		'x-myobapi-key: '. env('MYOB_CLIENT_ID'),
    			'x-myobapi-version: v2',
    			// 'Accept-Encoding: gzip,deflate',
    			'Content-Type: application/json'
    		]);
        }

        if(strtolower($method) == 'post')
		{
			curl_setopt($ch,CURLOPT_POST, true);
			//set curl option to post method
			curl_setopt($ch,CURLOPT_POSTFIELDS, $postData);
			//if post data present send them.
		}
		else if(strtolower($method) == 'delete')
		{
			curl_setopt($ch,CURLOPT_CUSTOMREQUEST, 'DELETE');
			//file transfer time delete
		}
		else if(strtolower($method) == 'put')
		{
			curl_setopt($ch,CURLOPT_CUSTOMREQUEST, 'PUT');
			curl_setopt($ch,CURLOPT_POSTFIELDS, $postData); // No tsure Json or array? API JSON
			//file transfer to post ,put method and set data
		}
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $result = curl_exec($ch);
        $statusCode = curl_getinfo($ch);
        curl_close($ch);
        return ['response'=>$result,'status'=>$statusCode];
    }

}