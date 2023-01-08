<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Record;
use Session;
use App\Company;
use App\SubCompany;
use App\Category;
use App\SubCompanyRateArea;
use Image;
use  App\Libraries\MyobCurl;
use App\myobtokens;
use DB;
use App\PurchaseRecord;
use App\Smtp;
use App\SubAccount;
use App\InvoiceSetting;
use Carbon\Carbon;
use App\TransactionAllocation;
use PDF;

class AccountantController extends Controller
{

    public function __construct()
    {
        $this->middleware('accountant');
    }


  	public function deleteBillOfLading(Request $request)
  	{

       $record = Record::find($request->id);
      	$attachments = explode("::",$record->bill_of_lading);

      	foreach ($attachments as $key => $value) {
          	if ($value == $request->attachment) {
            	unset($attachments[$key]);

          	}
        }
      	$record->update([
        	'bill_of_lading' => implode("::",$attachments)
        ]);

      	Session::flash('success','Attachment Deleted');
      	return back();

     }

  	public function deleteDeliveryDocket(Request $request)
  	{

        $record = Record::find($request->id);
      	$attachments = explode("::",$record->delivery_docket);

      	foreach ($attachments as $key => $value) {
          	if ($value == $request->attachment) {
            	unset($attachments[$key]);

          	}
        }
      	$record->update([
        	'delivery_docket' => implode("::",$attachments)
        ]);

      	Session::flash('success','Attachment Deleted');
      	return back();

    }


  	public function dashboard()
  	{
     	$records = Record::where('supervisor_status',1)->where('status',0)->where('deleted_status',0)->get();
 		return view('accountant.dashboard', compact('records'));
    }

  	public function record($id)
    {
      	$record = Record::find($id);
      	return view('accountant.record_details', compact('record'));
    }

  	public function approveApplication($id)
    {
        $myob = new MyobCurl;


        $invoice_record = Record::select('*')->where('id', $id)->get();

        $record = Record::find($invoice_record[0]->id);
        $products = $record->products;
        $product_items = [];
        $product_name['account_uid'] = $record->subCompany->ac_uid;

        foreach ($products as $product) {
           if ($product->qty > 0) {
              $unit = ($product->whole_sale + $product->delivery_rate + $product->brand_charges + $product->cost_of_credit) - $product->discount;
              $amount = $unit * $product->qty;

              $product_lines[] =
                [
                   "Type" => "Transaction",
                   "Description"=>$product->product->name,
                   "ShipQuantity" => $product->qty,
                   "UnitOfMeasure"=>"ltr",
                   "UnitPrice" => number_format(round($unit, 4), 4),
                   "DiscountPercent" => 0,
                   "Account" => [
                    "UID"=>$product_name['account_uid'],
                  ],
                   "TaxCode" => [
                      "UID" => "be778956-e739-4e23-8786-c2f63aa4fa28"
                   ],
                   "Item" => [
                         "UID" => $product->product->item_uid

                      ]
                ]
                   ;
           }
        }
        $date = '-';
        $due = '-';
        $origional_date = '';
        if ($record->subCompany->inv_due_days > 0) {
           $date = \Carbon\Carbon::parse($record->datetime)->format('d-m-Y');
           $origional_date =$record->subCompany->inv_due_days;
           $due = date('Y-m-d h:m:s', strtotime($date . ' + ' . $record->subCompany->inv_due_days . ' days'));
        } elseif ($record->subCompany->inv_due_days < 0) {
           $timestamp = strtotime(\Carbon\Carbon::parse($record->datetime)->format('d-m-Y H:i'));
           $daysRemaining = (int)date('t', $timestamp) - (int)date('j', $timestamp);
           $positive_value =  abs($record->subCompany->inv_due_days);
           $origional_date = $positive_value + $daysRemaining;
           $date = \Carbon\Carbon::parse($record->datetime)->format('d-m-Y');
           $due = date('Y-m-d h:m:s', strtotime($date . ' + ' . $origional_date . ' days'));
        }
        $product_name['items'] = $product_items;
        $product_name['duedays'] = $origional_date;
        $product_name['record'] = $invoice_record;
        $product_name['duedate'] = ($due == '-') ? \Carbon\Carbon::parse($record->datetime)->format('Y-m-d h:m:s') : $due;
        $product_name['billto'] = $record->subCompany->company->name;

        $product_name['co_uid'] = $record->subCompany->co_uid;
        $product_name['shipto'] = $record->subCompany->name;
        $product_name['supplier'] = $record->supplierCompany->name;

      $post_json_data = [
          "Number" => $record->invoice_number,
          "Date" => $record->datetime,
          "SupplierInvoiceNumber" => null,
          "Customer" => [
                "UID" => $product_name['co_uid'],
             ],
          "ShipToAddress" => $product_name['shipto'],


          "Terms" => [
                  "PaymentIsDue"=> "InAGivenNumberOfDays",
                   "BalanceDueDate" => $product_name['duedays'],

                ],
          "IsTaxInclusive" => ($record->gst_status=='Excluded') ? false : true,
          "IsReportable" => false,

          ];

        if (coupon_status() == false){

      }
      $token = myobtokens::find(1)->access_token;
       $post_data =     $myob->FileGetContents(
        company_uri().'Sale/Invoice/Item',
        'post',
        json_encode($post_json_data)
    ,
    $token
    );
    $post_data ['post'] = $post_json_data;

      	$record->update([
          'status' => 1
        ]);


      	$this->deleteNotification($id);

      	Session::flash('success','Application approved successfully.');
      	return redirect('/accountant/dashboard');
    }
    public function syncSalesinvoiceduid(){

      $sale_record =Record::get();

         if (coupon_status() == false){
        }
        $myob = new MyobCurl;
        $token = myobtokens::find(1)->access_token;


              $record_uid='';
              $get_data =     $myob->FileGetContents(company_uri().'Sale/Invoice/Item', 'get','', $token);
              $sale_record_list = json_decode($get_data['response'],true);

              foreach($sale_record_list as $sale_list){
                if (is_array($sale_list) || is_object($sale_list))
                {
                foreach($sale_list as $list) {
                  foreach ($sale_record as $salerecord){
                  if ($salerecord->invoice_number === $list['Number']){
                    $record_uid = $list['UID'];

                    DB::table('records')->where('invoice_number',$salerecord->invoice_number)->update([
                    'invoice_uid' => $record_uid


                    ]);


                  }
                  }
                }
                }
              }
      }


  	public function recordUpdate($id)
    {
      	$record = Record::find($id);
      	$driver_role_id = \DB::table('roles')->where('slug','driver')->orWhere('slug','drivers')->first()->id;
      	$drivers = \App\User::select('id','name')->where('role_id',$driver_role_id)->where('account_status',1)->where('deleted_status',0)->get();
      	$record = Record::find($id);
      	$categories = \App\Category::where('status',1)->orderBy('name','asc')->get();
        $companies = \App\Company::select('id','name')->where('status',1)->orderBy('name','asc')->get();
      	$supplycompanies = \App\SupplierCompany::select('id','name')->get();
      	return view('accountant.record_update', compact('record','categories','drivers','supplycompanies','companies'));
    }

  	public function disApprove(Request $request)
    {
      	$record = Record::find($request->record_id);
      	$record->update([
          'supervisor_status' => 0,
          'cancel_reason' => $request->cancel_reason
        ]);


      	$this->deleteNotification($request->record_id);

        Session::flash('success','Application disapproved successfully.');
      	return redirect('/accountant/dashboard');
    }

  	public function recordUpdation(Request $request)
    {
       $this->validate($request, [
          	'driver_id' => 'required',
          	'fuel_company' => 'required',
        	'billoflading.*' => 'mimes:jpeg,png,jpg,gif,svg,pdf',
        	'deleverydocket.*' => 'mimes:jpeg,png,jpg,gif,svg,pdf',
        ]);
      	$record = Record::find($request->record_id);
        $sub = SubCompany::find($request->sub_company_id);
      	$cats = Category::find($request->category_id);
        $total = 0;
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

        $whole_sale = 0;
      	$products = $record->products;
      	foreach ($products as $product) {
           $qty = $request->input('product_' . $product->id);
           $whole_sale = ($request->input('whole_sale_price_' . $product->id)) ? $request->input('whole_sale_price_' . $product->id) : 0;
           $rate = SubCompanyRateArea::where('sub_company_id',$request->sub_company_id)->where('product_id',$product->product_id)->first();

            $whole_sale = $whole_sale;
            $discount = ($rate) ? $rate->discount : 0;
            $delivery_rate = ($rate) ? $rate->delivery_rate : 0;
            $brand_charges = ($rate) ? $rate->brand_charges : 0;
            $cost_of_credit = ($rate) ? $rate->cost_of_credit : 0;

            $total = $total + ((($whole_sale + $delivery_rate + $brand_charges + $cost_of_credit) - $discount) * $qty);

            $product->update([
                  'qty' => $qty,
                  'whole_sale' => $whole_sale,
              		'discount' => $discount,
              		'delivery_rate' => $delivery_rate,
              		'brand_charges' => $brand_charges,
              		'cost_of_credit' => $cost_of_credit,
            ]);
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

      Session::flash('success','Application has been updated.');
      return redirect('accountant/record-details/'.$request->record_id);
    }

            public function syncSalesuid(){
		$sale_record_list=[];
		$invoice_data=[];
		$sale_record =Record::get();
		   if (coupon_status() == false){}
			$myob = new MyobCurl;
		  $token = myobtokens::find(1)->access_token;
						$record_uid='';
						$get_data =     $myob->FileGetContents(company_uri().'Sale/Invoice/Item', 'get','', $token);
						$sale_record_list_final = json_decode($get_data['response'],true);


						$sale_record_list[]['Items'] = $sale_record_list_final['Items'];

						$total_pages = $sale_record_list_final['Count']/400;
						for ($x = 1; $x <= $total_pages; $x++ ){
						    $page_count = $x * 400;
						    $get_data =     $myob->FileGetContents(company_uri().'Sale/Invoice/Item?$top=400&$skip='.$page_count, 'get','', $token);
						    $sale_record_list_final_2 = json_decode($get_data['response'],true);
						    $sale_record_list[]['Items'] = $sale_record_list_final_2['Items'];
              }



						foreach ($sale_record_list as $record){
						    foreach ($record['Items'] as $invoice){
						    $invoice_data[]=['UID'=>$invoice['UID'],'Number'=>$invoice['Number']];

						     DB::table('records')->where('invoice_number',$invoice['Number'])->update([
                    'invoice_uid' => $invoice['UID']


                    ]);
                  						    }
						}



	  }
  public function approveApplicationWithemail(Request $request)
  {
    	$this->validate($request, [
			'subject' => 'required',
			'body' => 'required',
        ]);
      	if ($request->companies == '' && $request->more_emails == '') {
        	Session::flash('warning','Failed');
          	return back();
        }

    	$this->deleteNotification($request->record_id);

      	$smtp = \DB::table('smtps')->first();
      	if ($smtp)
        {
          $record = Record::find($request->record_id);
          $smtp = \App\Smtp::first();
          $data = array('record_id' => $request->record_id,'subject' => $request->subject, 'body' => $request->body,'sdd_status' => $request->sdd_status, 'bol_status' => $request->bol_status, 'invoice_status' => $request->invoice_status);

          $companies_emails = $request->companies;
          $smtp_bcc = explode("::",$smtp->bcc);
          if (!empty($companies_emails)) {
	          $array_merged = array_merge($companies_emails,$smtp_bcc);
          } else
          		$array_merged = $smtp_bcc;

          if ($request->more_emails) {
            $array_of_more = json_decode($request->more_emails);
            $emails = array_merge($array_merged,$array_of_more);
          } else {
          	$emails = $array_merged;
          }

          if(!empty($emails)){
              try {
                  \Mail::to($smtp->primary_mail)->bcc($emails)->send(new \App\Mail\AccountantApproveRecordMail($data));
              } catch (\Exception $e) {
              	Session::flash('danger', 'E-Mail is not send please review e-mail settings');
              	return back();
              }
          }
          else{
            Session::flash('warning','Failed');
            return back();
          }

          $record->update([
            'email_status' => 1,
            'status' => 1
          ]);

          Session::flash('success','Record Approved successfully with E-mail.');
        }
      else {
        	Session::flash('danger','Failed');
        }

      	return redirect('/accountant/dashboard');

  	}
	public function deleteNotification($id)
    {
    	$notifications = \App\Notification::where('record_id',$id)->get();
      	foreach ($notifications as $notification)
        	$notification->delete();
            return back();
    }

  public function fetchCompanyRates(Request $request)
    {
        $record = Record::find($request->record_id);
        $subcompany_id = $request->id;
      	return view('accountant.record_prices', compact('record','subcompany_id'));
    }
}
