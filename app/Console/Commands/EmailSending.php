<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PDF;

use App\Company;
use App\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Record;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class EmailSending extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sending-report-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command sending Emails Report';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $json = DB::table('email_queues')->where('status',0)->first();
        $request ="";
        if(!empty($json)){
        $request = json_decode($json->data);
        }

        $type = $request->type;
        $from = Carbon::parse($request->from)->format('Y-m-d');
        $to = Carbon::parse($request->to)->format('Y-m-d');
        if ($type == 'Custom Range') {
            if ($request->company_id == 'all_companies') {
                $records = Record::where('category_id', $request->category_id)->whereBetween('datetime', [$from, $to])->where('deleted_status', 0)->where('status', 1)->get()->groupBy('sub_company_id');
            } else if ($request->sub_company_id == 'all_subcompanies') {
                $records = Record::where('category_id', $request->category_id)->where('company_id', $request->company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status', 0)->where('status', 1)->get()->groupBy('sub_company_id');
            } else {
                $records = Record::where('category_id', $request->category_id)->where('company_id', $request->company_id)->where('sub_company_id', $request->sub_company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status', 0)->where('status', 1)->get()->groupBy('sub_company_id');
            }
        } elseif ($type == 'Today') {
            if ($request->company_id == 'all_companies') {
                $records = Record::where('category_id', $request->category_id)->whereRaw('Date(datetime) = CURDATE()')->where('deleted_status', 0)->where('status', 1)->get()->groupBy('sub_company_id');
            } else if ($request->sub_company_id == 'all_subcompanies') {
                $records = Record::where('category_id', $request->category_id)->where('company_id', $request->company_id)->whereRaw('Date(datetime) = CURDATE()')->where('deleted_status', 0)->where('status', 1)->get()->groupBy('sub_company_id');
            } else {
                $records = Record::where('category_id', $request->category_id)->where('sub_company_id', $request->sub_company_id)->whereRaw('Date(datetime) = CURDATE()')->where('deleted_status', 0)->where('status', 1)->get()->groupBy('sub_company_id');
            }
        } elseif ($type == 'Yesterday') {
            if ($request->company_id == 'all_companies') {
                $records = Record::where('category_id', $request->category_id)->whereBetween('datetime', [$from, $to])->where('deleted_status', 0)->where('status', 1)->get()->groupBy('sub_company_id');
            } else if ($request->sub_company_id == 'all_subcompanies') {
                $records = Record::where('category_id', $request->category_id)->where('company_id', $request->company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status', 0)->where('status', 1)->get()->groupBy('sub_company_id');
            } else {
                $records = Record::where('category_id', $request->category_id)->where('sub_company_id', $request->sub_company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status', 0)->where('status', 1)->get()->groupBy('sub_company_id');
            }
        }
        if ($type == 'Last 7 Days') {
            if ($request->company_id == 'all_companies') {
                $records = Record::where('category_id', $request->category_id)->whereBetween('datetime', [$from, $to])->where('deleted_status', 0)->where('status', 1)->get()->groupBy('sub_company_id');
            } else if ($request->sub_company_id == 'all_subcompanies') {
                $records = Record::where('category_id', $request->category_id)->where('company_id', $request->company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status', 0)->where('status', 1)->get()->groupBy('sub_company_id');
            } else {
                $records = Record::where('category_id', $request->category_id)->where('sub_company_id', $request->sub_company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status', 0)->where('status', 1)->get()->groupBy('sub_company_id');
            }
        } elseif ($type == 'Last 30 Days') {
            if ($request->company_id == 'all_companies') {
                $records = Record::where('category_id', $request->category_id)->whereBetween('datetime', [$from, $to])->where('deleted_status', 0)->where('status', 1)->get()->groupBy('sub_company_id');
            } else if ($request->sub_company_id == 'all_subcompanies') {
                $records = Record::where('category_id', $request->category_id)->where('company_id', $request->company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status', 0)->where('status', 1)->get()->groupBy('sub_company_id');
            } else {
                $records = Record::where('category_id', $request->category_id)->where('sub_company_id', $request->sub_company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status', 0)->where('status', 1)->get()->groupBy('sub_company_id');
            }
        } elseif ($type == 'This Month') {
            if ($request->company_id == 'all_companies') {
                $records = Record::where('category_id', $request->category_id)->whereBetween('datetime', [$from, $to])->where('deleted_status', 0)->where('status', 1)->get()->groupBy('sub_company_id');
            } else if ($request->sub_company_id == 'all_subcompanies') {
                $records = Record::where('category_id', $request->category_id)->where('company_id', $request->company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status', 0)->where('status', 1)->get()->groupBy('sub_company_id');
            } else {
                $records = Record::where('category_id', $request->category_id)->where('sub_company_id', $request->sub_company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status', 0)->where('status', 1)->get()->groupBy('sub_company_id');
            }
        } elseif ($type == 'Last Month') {
            if ($request->company_id == 'all_companies') {
                $records = Record::where('category_id', $request->category_id)->whereBetween('datetime', [$from, $to])->where('deleted_status', 0)->where('status', 1)->get()->groupBy('sub_company_id');
            } else if ($request->sub_company_id == 'all_subcompanies') {
                $records = Record::where('category_id', $request->category_id)->where('company_id', $request->company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status', 0)->where('status', 1)->get()->groupBy('sub_company_id');
            } else {
                $records = Record::where('category_id', $request->category_id)->where('sub_company_id', $request->sub_company_id)->whereBetween('datetime', [$from, $to])->where('deleted_status', 0)->where('status', 1)->get()->groupBy('sub_company_id');
            }
        }
        $category = Category::where('id', $request->category_id)->first();
        $company = Company::where('id', $request->category_id)->first();

        $customPaper = array(0, 0, 680, 920);

        $file = 'Client Ledger Report - ' . date('d-m-Y') . '.pdf';

        $pdf = \PDF::loadView('admin.reports.client_statement_for_Account_report', compact('records', 'company', 'category', 'type', 'from', 'to'));
        $smtp = DB::table('smtps')->first();

        if ($smtp) {
            $smtp = \App\Smtp::first();
            $smtp_bcc = explode("::", $smtp->bcc);
            $emails = $smtp_bcc;
            if (!empty($emails)) {
                try {

                    Mail::to($smtp->primary_mail)->bcc($emails)->send(new \App\Mail\ClientStatmentForAccountEmail($pdf));
                } catch (\Exception $e) {
                    // change status to 3
                    $json->status = 3;
                }
            } else {

                $json->status = 2;
            }

            // $json->status = 1;
            DB::table('email_queues')->where('id', $json->id)->update([
                'status' => 1
            ]);


        } else {

            $json->status = 2;

        }

        // return back();

    }
}
