<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Smtp;
use App\InvoiceSetting;
use Session;

class SmtpController extends Controller
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
      $smtp = Smtp::first();
      return view('admin.smtp.index', compact('smtp'));
    }
  
  	public function store(Request $request)
    {
    	$this->validate($request, [
          'mailer' => 'required',
          'host' => 'required',
          'port' => 'required',
          'username' => 'required',
          'password' => 'required',
          'encryption' => 'required',
          'reply_to' => 'required',
          'primary_mail' => 'required',
          'primary_name' => 'required'
        ]);
      	
      	$array = json_decode($request->bcc);
      	$bcc = implode("::",$array);

        $smtp = Smtp::first();
        if ($smtp) {
          	$smtp->update([
              'mailer' => $request->mailer,
              'host' => $request->host,
              'port' => $request->port,
              'username' => $request->username,
              'password' => $request->password,
              'encryption' => $request->encryption,
              'reply_to' => $request->reply_to,
              'primary_mail' => $request->primary_mail,
              'primary_name' => $request->primary_name,
              'bcc' => $bcc,
              'body' => $request->body
        	]);
        } else {
			Smtp::create([
              'mailer' => $request->mailer,
              'host' => $request->host,
              'port' => $request->port,
              'username' => $request->username,
              'password' => $request->password,
              'encryption' => $request->encryption,
              'reply_to' => $request->reply_to,
              'primary_mail' => $request->primary_mail,
              'primary_name' => $request->primary_name,
              'bcc' => $bcc,
              'body' => $request->body
            ]);          
        }
      
      	Session::flash('success','Email settings updated.');
      	return back();
    }
  
  
  public function invoiceshow()
    {
      $invoice = InvoiceSetting::first();
      return view('admin.invoice-setting.index', compact('invoice'));
    }
  
  	public function invoiceUpdate(Request $request)
    {
    	$this->validate($request, [
          'invoice_logo' => 'mimes:jpeg,png,jpg,gif,svg',
          'invoice_email' => 'email',
          'pay_online_images.*' => 'mimes:jpeg,png,jpg,gif,svg',
          'telephone_header_img1' => 'mimes:jpeg,png,jpg,gif,svg',
          'telephone_header_img2' => 'mimes:jpeg,png,jpg,gif,svg',
        ]);
      
       
      	$invoice = InvoiceSetting::first();
      	
        if ($invoice) {
          
            if ($request->has('invoice_logo')) {
            $file = $request->invoice_logo;
            $name1 = time().$file->getClientOriginalName();
            $file->move('public/uploads/siteinvoice',$name1);
        }
          else{
            $name1 = $invoice->invoice_logo;
          }
          if ($request->has('telephone_header_img1')) {
            $file = $request->telephone_header_img1;
            $image1 = time().$file->getClientOriginalName();
            $file->move('public/uploads/siteinvoice',$image1);
        }
          else{
            $image1 = $invoice->telephone_header_img1;
          }
         if ($request->has('telephone_header_img2')) {
            $file = $request->telephone_header_img2;
            $image2 = time().$file->getClientOriginalName();
            $file->move('public/uploads/siteinvoice',$image2);
        }
          
          else{
            $image2 = $invoice->telephone_header_img2;
          }
          $images = ($invoice->pay_online_imges) ? explode("::",$invoice->pay_online_imges) : [];
          if ($request->has('pay_online_images')) {
          foreach ($request->file('pay_online_images') as $file) {
            $name = str_replace(" ","",time().$file->getClientOriginalName());
        	$file->move('public/uploads/siteinvoice',$name);
            array_push($images,$name);
          }
        }
        $images = implode("::",$images);
          
          	$invoice->update([
              'invoice_logo' => $name1,
              'invoice_abn' => $request->invoice_abn,
              'invoice_bank' => $request->invoice_bank,
              'name' => $request->name,
              'invoice_bsb' => $request->invoice_bsb,
              'invoice_account_no' => $request->invoice_account_no,
              'invoice_web_url' => $request->invoice_web_url,
              'invoice_phone_no' => $request->invoice_phone_no,
              'invoice_email' => $request->invoice_email,
              'invoice_address' => $request->invoice_address,
              'powerd_text' => $request->powerd_text,
              'pay_online_imges' => $images,
              'pay_online_text' => $request->pay_online_text,
              'telephone_header_img1' => $image1,
              'telephone_header_img2' => $image2,
              'telephone_header' => $request->telephone_header,
              'telephone_text' => $request->telephone_text,
        	]);
        } 
       else {
           $name= '';
           $image1 = '';
           $image2 = '';
           $images = [];
         
         if ($request->has('pay_online_images')) {
          foreach ($request->file('pay_online_images') as $file) {
            $name = str_replace(" ","",time().$file->getClientOriginalName());
        	$file->move('public/uploads/siteinvoice',$name);
            array_push($images,$name);
          }
        }
         $images = implode("::",$images);
         
       if ($request->has('invoice_logo')) {
            $file = $request->invoice_logo;
            $name = time().$file->getClientOriginalName();
            $file->move('public/uploads/siteinvoice',$name);
        }
         if ($request->has('telephone_header_img1')) {
            $file = $request->telephone_header_img1;
            $image1 = time().$file->getClientOriginalName();
            $file->move('public/uploads/siteinvoice',$image1);
        }
         if ($request->has('telephone_header_img2')) {
            $file = $request->telephone_header_img2;
            $image2 = time().$file->getClientOriginalName();
            $file->move('public/uploads/siteinvoice',$image2);
        }
			InvoiceSetting::create([
              'invoice_logo' => $name,
              'invoice_abn' => $request->invoice_abn,
              'invoice_bank' => $request->invoice_bank,
              'name' => $request->name,
              'invoice_bsb' => $request->invoice_bsb,
              'invoice_account_no' => $request->invoice_account_no,
              'invoice_web_url' => $request->invoice_web_url,
              'invoice_phone_no' => $request->invoice_phone_no,
              'invoice_email' => $request->invoice_email,
              'invoice_address' => $request->invoice_address,
              'powerd_text' => $request->powerd_text,
              'pay_online_imges' => $images,
              'pay_online_text' => $request->pay_online_text,
              'telephone_header_img1' => $image1,
              'telephone_header_img2' => $image2,
              'telephone_header' => $request->telephone_header,
              'telephone_text' => $request->telephone_text,
            ]);          
        }
      
      	Session::flash('success','Invoice settings updated.');
      	return back();
    }
  
  public function deletePayOnlineImage(Request $request)
  	{
      
        $record= InvoiceSetting::first();
      	$attachments = explode("::",$record->pay_online_imges);
      
      	foreach ($attachments as $key => $value) {
          	if ($value == $request->attachment) {
            	unset($attachments[$key]);
              	unlink(public_path().'/uploads/siteinvoice/'.$value);
          	}
        }

      	$record->update([
        	'pay_online_imges' => implode("::",$attachments)
        ]);
      
      	Session::flash('success','Attachment Deleted');
      	return back();
      
     }
  
   public function deleteheaderImg(Request $request)
  	{
        $record = InvoiceSetting::first(); 
         $value = $record->telephone_header_img1;
          	if ($value == $request->header_image)
            {
                unlink(public_path().'/uploads/siteinvoice/'.$value);
            	$record->update([
                'telephone_header_img1' => 'NULL'
            ]);
              
          	}
     else{
        $secondimage = $record->telephone_header_img2;
        unlink(public_path().'/uploads/siteinvoice/'.$secondimage);
        $record->update([
                'telephone_header_img2' => 'NULL'
            ]);
     }
     
      	Session::flash('success','Attachment Deleted');
      	return back();
      
     }
}
