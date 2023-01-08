<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Record;
use App\PurchaseRecord;
use Illuminate\Http\Request;
use App\RecordProduct;
use DateTime;
use FontLib\TrueType\Collection;
use Illuminate\Support\Facades\DB;

class DashboardCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:dashboardindex';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Activity logs';

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
        Log::info('activity log is Working in DashboardCache');
        $cron_job =  DB::table('dashboard_cache')->where('value', 'dashboardindex')->first();
        if ($cron_job->value == 'dashboardindex' && $cron_job->status == 1) {

            $set_category = isset($_GET['category']) ? $_GET['category'] : 0;
            $set_category = isset($_GET['category']) ? $_GET['category'] : 0;
            $categories = DB::table('categories')
            ->select('id', 'name')
            ->orderBy('name', 'asc')
            ->get();
            $from =
                Carbon::now()
                ->subdays(7)
                ->format('Y-m-d') . ' 00:00:00';
            $to =
                Carbon::now()
                ->subdays(1)
                ->format('Y-m-d') . ' 24:00:00';
            $expenses = DB::table('expenses')
            ->select('datetime', 'amount')
            ->whereBetween('datetime', [new Carbon($from), new Carbon($to)])
            ->get();

            if ($set_category != '' && $set_category != 0) {
                $sales = Record::select('id', 'company_id', 'sub_company_id', 'datetime', 'status', 'paid_status', 'gst', 'total_amount', 'paid_amount', 'total_without_gst', 'deleted_status')
                ->where('category_id', $_GET['category'])
                    ->get();

                $purchases = PurchaseRecord::select('id', 'category_id', 'supplier_company_id', 'invoice_number', 'total_amount', 'datetime', 'status', 'deleted_status')
                ->where('category_id', $_GET['category'])
                    ->get();
            } else {
                $sales = Record::select('id', 'company_id', 'sub_company_id', 'datetime', 'status', 'paid_status', 'gst', 'total_amount', 'paid_amount', 'total_without_gst', 'deleted_status')->get();

                $purchases = PurchaseRecord::select('id', 'category_id', 'supplier_company_id', 'invoice_number', 'total_amount', 'datetime', 'status', 'deleted_status')->get();
            }

            $purchases1 = $purchases;
            $tax = $sales
            ->where('status', 1)
            ->where('deleted_status', 0)
            ->sum('gst');
            $gross = $sales
            ->where('status', 1)
            ->where('deleted_status', 0)
            ->sum('total_amount');

            $net = $gross - $tax;


            $date1 = Carbon::now()
            ->addday(1)
            ->format('d-m-Y');
            $date2 = Carbon::now()
            ->addday(2)
            ->format('d-m-Y');
            $date3 = Carbon::now()
            ->addday(3)
            ->format('d-m-Y');
            $date4 = Carbon::now()
            ->addday(4)
            ->format('d-m-Y');
            $date5 = Carbon::now()
            ->addday(5)
            ->format('d-m-Y');
            $date6 = Carbon::now()
            ->addday(6)
            ->format('d-m-Y');
            $date7 = Carbon::now()
            ->addday(7)
            ->format('d-m-Y');
            $yesterday = Carbon::now()
            ->subdays(1)
            ->format('d-m-Y');
            $d1_total = 0;
            $d2_total = 0;
            $d3_total = 0;
            $d4_total = 0;
            $d5_total = 0;
            $d6_total = 0;
            $d7_total = 0;
            $array_date_1 = [];
            $array_date_2 = [];
            $array_date_3 = [];
            $array_date_4 = [];
            $array_date_5 = [];
            $array_date_6 = [];
            $array_date_7 = [];
            $overdue_invoices = 0;
            $overdue_balance = 0;
            $unpaid_balance = 0;


            foreach ($sales->where('status', 1)->where('paid_status', 0)->where('deleted_status', 0)
            as $record) {
                $record_date = Carbon::parse($record->datetime)->format('d-m-Y');
                $sub_company_days = $record->subCompany->inv_due_days;
                if ($sub_company_days > 0) {
                    $due = date('d-m-Y', strtotime($record_date . ' + ' . $sub_company_days . ' days'));
                } elseif ($sub_company_days < 0) {
                    $timestamp = strtotime($record_date);
                    $daysRemaining = (int) date('t', $timestamp) - (int) date('j', $timestamp);
                    $positive_value = abs($sub_company_days);
                    $original_date = $positive_value + $daysRemaining;
                    $due = date('d-m-Y', strtotime($record_date . ' + ' . $original_date . ' days'));
                } else {
                    $due = $record_date;
                }

                $over_date = strtotime($yesterday) - strtotime($due);
                if ($over_date < 0) {
                    $overdue_invoices++;
                    $amount = $record->total_amount - $record->paid_amount;
                    $overdue_balance = $overdue_balance + $amount;
                } else {
                    $amount = $record->total_amount - $record->paid_amount;
                    $unpaid_balance = $unpaid_balance + $amount;
                }

                switch ($due) {
                    case $date1:
                        array_push($array_date_1, $record->id);
                        $d1_total++;
                        break;
                    case $date2:
                        array_push($array_date_2, $record->id);
                        $d2_total++;
                        break;
                    case $date3:
                        array_push($array_date_3, $record->id);
                        $d3_total++;
                        break;
                    case $date4:
                        array_push($array_date_4, $record->id);
                        $d4_total++;
                        break;
                    case $date5:
                        array_push($array_date_5, $record->id);
                        $d5_total++;
                        break;
                    case $date6:
                        array_push($array_date_6, $record->id);
                        $d6_total++;
                        break;
                    case $date7:
                        array_push($array_date_7, $record->id);
                        $d7_total++;
                        break;
                }
            }

            $getoverdues = 0;
            if ($set_category != '' && $set_category != 0) {
                $num_records = Record::where('category_id', $_GET['category'])->where('status', 1)->where('paid_status', 0)->where('deleted_status', 0)->get();
            } else {
                $num_records = Record::where('status', 1)->where('paid_status', 0)->where('deleted_status', 0)->get();
            }


            foreach ($num_records as $record) {
                $date = \Carbon\Carbon::parse($record->datetime)->format('d-m-Y');
                $days = $record->subCompany->inv_due_days;
                if ($days > 0) {
                    $date = \Carbon\Carbon::parse($record->datetime)->format('d-m-Y');
                    $due = date('d-m-Y', strtotime($date . ' + ' . $days . ' days'));
                } elseif ($days < 0) {

                    $timestamp = strtotime($date);
                    $daysRemaining = (int)date('t', $timestamp) - (int)date('j', $timestamp);
                    $positive_value =  abs($days);
                    $original_date = $positive_value + $daysRemaining;

                    $date = substr($date, 0, 10);
                    $due = date('d-m-Y', strtotime($date . ' + ' . $original_date . ' days'));
                } else {
                    $due = date('d-m-Y', strtotime($date . ' + ' . $days . ' days'));
                }

                $remaining_date = strtotime($due) - strtotime(date('d-m-Y'));
                if ($remaining_date >= 0)
                    continue;
                $getoverdues =   $getoverdues + 1;
            }




                $TOTAL_DELIVERIES = $sales->where('deleted_status', 0)->where('status', 1)->count();
                $PENDING_RECORDS = $sales->where('status', 0)->where('deleted_status', 0)->count();
                $PAID_INVOICES = $sales->where('status', 1)->where('paid_status', 1)->where('deleted_status', 0)->count();
                $BALANCE_AMOUNT = number_format($gross, 2);


                $unpaid_invoice =  $sales->where('status', 1)->where('paid_status', 0)->where('deleted_status', 0)->count();
                $unpaid_balance = number_format($unpaid_balance, 2);

                $overdue_invoice = $getoverdues;
                $overdue_balance = number_format($overdue_balance, 2);
                $net_earnings = number_format($net, 2);
                $gross_earnings = number_format($gross, 2);
                $tax_withheld = number_format($tax, 2);




                 $minutes = 4;
                $output = \Cache::remember('dashboardindex', $minutes, function () use ($TOTAL_DELIVERIES, $PENDING_RECORDS,$PAID_INVOICES, $BALANCE_AMOUNT, $unpaid_invoice, $overdue_invoice, $unpaid_balance, $tax_withheld, $gross_earnings, $net_earnings, $date7, $d7_total, $date6, $d6_total, $date5, $d5_total, $date4, $d4_total, $date3, $d3_total, $date2, $d2_total, $date1, $d1_total) {
                return [
                        'TOTAL_DELIVERIES' => $TOTAL_DELIVERIES,
                        'PENDING_RECORDS' => $PENDING_RECORDS,
                        'PAID_INVOICES' => $PAID_INVOICES,
                        'BALANCE_AMOUNT' => $BALANCE_AMOUNT,
                        'unpaid_invoice' => $unpaid_invoice,
                        'overdue_invoice' => $overdue_invoice,
                        'unpaid_balance' => $unpaid_balance,
                        'tax_withheld' => $tax_withheld,
                        'gross_earnings' => $gross_earnings,
                        'net_earnings' => $net_earnings,

                        'date7' => $date7,
                        'd7_total' => $d7_total,

                        'date6' => $date6,
                        'd6_total' => $d6_total,

                        'date5' => $date5,
                        'd5_total' => $d5_total,

                        'date4' => $date4,
                        'd4_total' => $d4_total,

                        'date3' => $date3,
                        'd3_total' => $d3_total,

                        'date2' => $date2,
                        'd2_total' => $d2_total,

                        'date1' => $date1,
                        'd1_total' => $d1_total
                ];
             });



                $cron_job->total_run = $cron_job->total_run + 1;
                DB::table('dashboard_cache')->where('value', $cron_job->value)->update([
                    'status' => 0,
                    'total_run' => $cron_job->total_run
                ]);
                DB::table('dashboard_cache')->where('value', 'activity-log')->update([
                    'status' => 1
                ]);

                // Log::info('activity log done');
            }

    }
}


