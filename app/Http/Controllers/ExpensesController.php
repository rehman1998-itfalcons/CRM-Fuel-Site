<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Expense;
use App\TransactionAllocation;

class ExpensesController extends Controller
{
    public function index()
    {
        $expenses = Expense::get();
    	return view('admin.expenses.index', compact('expenses'));
    }

  	public function create()
    {
    	return view('admin.expenses.create');
    }

   public function show($id)
    {
        $expense = Expense::find($id);
    	return view('admin.expenses.show', compact('expense'));
    }

   public function edit($id)
    {
        $expense = Expense::find($id);
    	return view('admin.expenses.edit', compact('expense'));
    }

  	public function store(Request $request)
    {
      $this->validate($request, [
          	'chart_account' => 'required',
          	'amount' => 'required',
          	'datetime' => 'required',
          	'description' => 'required',
          	'sub_account_id' => 'required',
            'payee' => 'required',
            'ref_no' => 'required',
        	'attach.*' => 'mimes:jpeg,png,jpg,gif,svg,pdf',
        ]);


      	if ($request->has('attach')) {
          	$name = [];
          	foreach ($request->file('attach') as $file) {
              $file_name = str_replace(" ","",rand(1000,9999).$file->getClientOriginalName());
              $file->move('public/uploads/expenses',$file_name);
              array_push($name,$file_name);
            }
          	$name = implode("::",$name);
          } else {
        	$name = '';
      	}

      	$record = Expense::create([
        	'chart_account_id' => $request->chart_account,
          	'sub_account_id' => $request->sub_account_id,
          	'amount' => $request->amount,
          	'datetime' => \Carbon\Carbon::parse($request->datetime)->format('Y-m-d H:i'),
          	'payee' => $request->payee,
          	'ref_no' => $request->ref_no,
          	'description' => $request->description,
          	'attachment' => $name
        ]);

      

       \Session::flash('success','Expense has been submitted.');
       return redirect('/expenses');
    }

   	public function update(Request $request, $id)
    {
      $this->validate($request, [
          	'chart_account' => 'required',
          	'amount' => 'required',
          	'datetime' => 'required',
          	'description' => 'required',
          	'sub_account_id' => 'required',
            'payee' => 'required',
            'ref_no' => 'required',
        	'attach.*' => 'mimes:jpeg,png,jpg,gif,svg,pdf',
        ]);

        $expense = Expense::find($id);
        $name = ($expense->attachment) ? explode("::",$expense->attachment) : [];
      	if ($request->has('attach')) {
          foreach ($request->file('attach') as $file) {
            $file_name = str_replace(" ","",rand(1000,9999).$file->getClientOriginalName());
        	$file->move('public/uploads/expenses',$file_name);
            array_push($name,$file_name);
          }
        }

      $name = implode("::",$name);

      	$expense->update([
        	'chart_account_id' => $request->chart_account,
          	'sub_account_id' => $request->sub_account_id,
          	'payee' => $request->payee,
          	'amount' => $request->amount,
          	'datetime' => \Carbon\Carbon::parse($request->datetime)->format('Y-m-d H:i'),
          	'ref_no' => $request->ref_no,
          	'description' => $request->description,
          	'attachment' => $name
        ]);

       \Session::flash('success','Expense has been Updated.');
       return redirect('/expenses');
    }

    public function deleteExpenseAttachment(Request $request)
  	{

        $expense= Expense::find($request->id);
      	$attachments = explode("::",$expense->attachment);

      	foreach ($attachments as $key => $value) {
          	if ($value == $request->attachment) {
            	unset($attachments[$key]);
               if (file_exists('public/uploads/expenses/'.$value))
              	unlink(public_path().'/uploads/expenses/'.$value);
          	}
        }

      	$expense->update([
        	'attachment' => implode("::",$attachments)
        ]);

      	\Session::flash('success','Attachment Deleted');
      	return back();

     }
}
