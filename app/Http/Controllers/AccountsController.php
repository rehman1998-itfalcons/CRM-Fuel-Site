<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ChartAccount;
use App\Account;
use App\ChartAccountType;
use Session;
use App\Transaction;
use App\SubAccount;
use App\TransactionAllocation;
use  App\Libraries\MyobCurl;
use App\myobtokens;
use DB;
class AccountsController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('superadmin');
    }


   	public function addAccount()
    {
      	$parents = ChartAccount::select('id','title')->get();
        return view('admin.accounts.add_new_bank', compact('parents'));
    }

  	public function storeAccount(Request $request)
  	{
      	$this->validate($request, [
          'account_name' => 'required',
          'account_number' => 'required',
          'chart_account_id' => 'required',
          'opening_date' => 'required',
          'bank_name' => 'required',
          'opening_balance' => 'required',
        
        ]);

   		Account::create([
     		'chart_account_id' => $request->chart_account_id,
     		'account_name' => $request->account_name,
     		'account_number' => $request->account_number,
     		'opening_date' => \Carbon\Carbon::parse($request->opening_date)->format('Y-m-d H:i'),
     		'bank_name' => $request->bank_name,
     		'opening_balance' => $request->opening_balance,
     		'description' => $request->description,
   		]);

      	Session::flash('success','Bank Account added successfully.');
    	return redirect()->back();
  	}

    public function manageAccount()
    {
      	$accounts = Account::get();
        return view('admin.accounts.manage_bank_accounts', compact('accounts'));
    }

    public function editAccount($id)
    {
      	$account = Account::find($id);
      	$parents = ChartAccount::select('id','title')->get();
        return view('admin.accounts.edit_accounts', compact('account','parents'));
    }
    public function syncAccounts(){
      
      $db_account =Account::get();

         if (coupon_status() == false){
          }
          $myob = new MyobCurl;
        $token = myobtokens::find(1)->access_token;


                      $account_uid='';
                      $get_data =     $myob->FileGetContents(company_uri().'GeneralLedger/Account', 'get','', $token);
                      $accounts_list = json_decode($get_data['response'],true);
                     
                      foreach($accounts_list as $ac_list){
                        if (is_array($ac_list) || is_object($ac_list))
                          {
                          foreach($ac_list as $list) {
                            foreach ($db_account as $accounts){
                              if ($accounts->account_number === $list['DisplayID']){
                                $account_uid = $list['UID'];
                             
                                DB::table('accounts')->where('account_number',$accounts->account_number)->update([
                                  'ac_uid' => $account_uid


                                ]);


                              }
                            }
                          }
                        }
                      }
    }


  	public function updateAccount(Request $request)
    {
      	$this->validate($request, [
          'account_name' => 'required',
          'account_number' => 'required',
          'chart_account_id' => 'required',
          'opening_date' => 'required',
          'bank_name' => 'required',
          'opening_balance' => 'required',
          
        ]);

      	$account = Account::find($request->id);
      	$account->update([
     		'chart_account_id' => $request->chart_account_id,
     		'account_name' => $request->account_name,
     		'account_number' => $request->account_number,
     		'opening_date' => \Carbon\Carbon::parse($request->opening_date)->format('Y-m-d H:i'),
     		'bank_name' => $request->bank_name,
     		'opening_balance' => $request->opening_balance,
     		'description' => $request->description,
   		]);

      	Session::flash('success','Bank Account updated successfully.');
    	return redirect('/manage-account');
    }

  	public function deleteAccount(Request $request)
    {
    	$account = Account::find($request->id);
      	$account->delete();

      	Session::flash('success','Bank Account deleted.');
    	return redirect('/manage-account');
    }



 	public function manageChartsAccounts()
    {
        $chartaccounts = ChartAccount::get();
        return view('admin.accounts.chartaccounts.manage_charts_account',compact('chartaccounts'));
    }

 	public function createChartAccount()
    {
        $chartof_account_types = ChartAccountType::get();
        return view('admin.accounts.chartaccounts.create_chart_account',compact('chartof_account_types'));
    }

  	public function StoreChartAccount(Request $request)
    {
       $this->validate($request, [
          'title' => 'required',
          'account_type' => 'required',
          'code' => 'required',
          'description' => 'string',
        ]);

   		ChartAccount::create([
     		'title' => $request->title,
     		'description' => $request->description,
     		'code' => $request->code,
     		'account_type' => $request->account_type,
   		]);

      	Session::flash('success','Chart of Account added successfully.');
    	return redirect('/manage-chart-accounts');
    }

  public function editChartAccount($id)
    {

   		$chart_account = ChartAccount::find($id);
        $chartof_account_types = ChartAccountType::get();
    	return view('admin.accounts.chartaccounts.edit_chart_account',compact('chart_account','chartof_account_types'));
    }

  public function updateChartAccount(Request $request, $id)
    {

     $this->validate($request, [
          'title' => 'required',
          'account_type' => 'required',
          'code' => 'required',
          'description' => 'string',
        ]);

   		$chart_account = ChartAccount::find($id);
     		$chart_account->title = $request->title;
     		$chart_account->description = $request->description;
     		$chart_account->code = $request->code;
     		$chart_account->account_type = $request->account_type;
            $chart_account->update();
        Session::flash('success','Chart of Account updated successfully.');
    	return redirect('/manage-chart-accounts');
    }

   public function deleteChartAccount(Request $request)
  {
    $chart_account = ChartAccount::find($request->id);
    $accounts = Account::where('chart_account_id',$chart_account->id)->get();
    $subaccounts = SubAccount::where('chart_account_id',$chart_account->id)->get();
        if($accounts->count() > 0 || $subaccounts->count() > 0){
         return back()->with('danger','You can not delete this record because it is assigned to an entry.');
        }
      else{
         $chart_account->delete();
        \Session::flash('success','Record Deleted successfully.');
        return back();
      }

    }

  	public function transaction()
    {
      if (isset($_GET['date_search'])) {
 	     	$date_search = $_GET['date_search'];
        	$date = \Carbon\Carbon::parse($date_search)->format('Y-m-d');
      		$transactions = Transaction::where('date',$date)->paginate(20);
      } elseif (isset($_GET['account_search'])) {
        $account = $_GET['account_search'];
      	$transactions = Transaction::where('account_id',$account)->paginate(20);
      } elseif (isset($_GET['desc_search'])) {
        $desc = $_GET['desc_search'];
      	$transactions = Transaction::where('description','LIKE',$desc.'%')->paginate(20);
      } else {
      	$transactions = Transaction::paginate(20);
      }

      	$accounts = Account::select('id','account_name')->get();
      	$charts = ChartAccount::select('id','title')->get();

      	return view('admin.accounts.transaction', compact('accounts','transactions','charts'));
    }

  	public function transactionUpload(Request $request)
    {
      	if ($request->file->getClientOriginalExtension() != 'qif') {
        	Session::flash('warning','File format mis-matched, please select Qif file.');
          	return back();
        }

   		$lines = file($request->file);
    	$records = array();
    	$record = array();
   		$end = 0;

      	foreach($lines as $line) {
        	$line = trim($line);
          	if($line === "^") {
            	$end=1;
            } elseif(preg_match("#^!Type:(.*)$#", $line, $match)) {

            } else {
            	switch(substr($line, 0, 1)) {
                	case 'D':
                  		$record['date']   = trim(substr($line, 1));
                  		break;

                  	case 'T':
		      			$record['amount'] = trim(substr($line, 1));
              			break;

        			case 'P':
                  		$line = htmlentities($line);
                  		$line = str_replace("  ", "", $line);
                  		$record['description']  = trim(substr($line, 1));
                  		break;

               
                }
            }

            if($end == 1)
            {
                $records[] = $record;
                $record = array();
                $end = 0;
            }
        }

      	$account_id = $request->account_id;
      	foreach ($records as $rec) {
          	$withdraw = 0;
          	$deposit = 0;

          	$amount = $rec['amount'];
          	$desc = $rec['description'];

          	$date = explode('/', $rec['date']);
        	$new_date = date("Y-m-d", mktime('0', '0', '0', $date[2], $date[0], $date[1]));
          	if ($amount > 0)
            	$deposit = $amount;
          	else
            	$withdraw = str_replace("-","",$amount);

          
          	$check = \DB::table('transactions')
              			->where('account_id',$account_id)
              			->where('date',$new_date)
              			->where('withdraw',$withdraw)
              			->where('deposit',$deposit)
              			->first();

            if (!$check) {
              Transaction::create([
                'account_id' => $account_id,
                'date' => $new_date,
                'withdraw' => $withdraw,
                'deposit' => $deposit,
                'description' => $desc,
                'status' => 0
              ]);
            }
        }

      	Session::flash('success','Transactions added successfully.');
      	return back();
    }

  	public function fetchSubAccounts(Request $request)
    {
    	$accounts = SubAccount::select('id','title')->where('chart_account_id',$request->account_id)->get();
      	return json_decode($accounts);
    }

  	public function fetchInvoices(Request $request)
    {
      	$account = ChartAccount::find($request->account_id);
      	if ($account->title == 'Sale') {
          	$invoices = \App\Record::select('id','invoice_number','total_amount','paid_amount')->where('paid_status',0)->get();
        } elseif ($account->title == 'Purchase') {
    		$invoices = \App\PurchaseRecord::select('id','invoice_number','total_amount','paid_amount')->where('paid_status',0)->get();
        }

      return view('admin.accounts.invoices', compact('invoices'));
    }

  	public function allocateToMe(Request $request)
    {
      	$chart = ChartAccount::find($request->chart_account_id);
      	foreach ($request->invoices as $key => $value)
        {
			if ($chart->title == 'Sales') {
              	$sale = \App\Record::find($value);
              	$amount = $sale->total_amount - $sale->paid_amount;
              	$sale->update([
                  	'paid_amount' => $sale->total_amount,
                	'paid_status' => 1
                ]);

              $transaction_alloc = new TransactionAllocation();
              $transaction_alloc->sub_account_id = $request->sub_account_id;
              $transaction_alloc->record_id = $sale->id;
              $transaction_alloc->transaction_id = $request->transactoin_id;
              $transaction_alloc->amount = $amount;
              $transaction_alloc->save();

            } elseif ($chart->title == 'Purchases') {
              	$purchase = \App\PurchaseRecord::find($value);
              	$amount = $purchase->total_amount - $purchase->paid_amount;
              	$purchase->update([
                	'paid_status' => 1,
                  	'paid_amount' => $purchase->total_amount
                ]);

              $transaction_alloc = new TransactionAllocation();
              $transaction_alloc->sub_account_id = $request->sub_account_id;
              $transaction_alloc->purchase_record_id = $purchase->id;
              $transaction_alloc->transaction_id = $request->transactoin_id;
              $transaction_alloc->amount = $amount;
              $transaction_alloc->payment_date = \Carbon\Carbon::parse($request->payment_datetime)->format('Y-m-d H:i');
              $transaction_alloc->save();

            }
        }

      	\DB::table('transactions')->where('id',$request->transactoin_id)->update(['status' => 1]);
    	Session::flash('success','Transactions allocated successfully.');
      	return back();
    }
}
