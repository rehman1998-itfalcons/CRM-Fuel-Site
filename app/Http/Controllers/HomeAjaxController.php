<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Record;
use App\PurchaseRecord;
use App\RecordProduct;
use App\Log;
use DateTime;
use FontLib\TrueType\Collection;
use Illuminate\Support\Facades\DB;

class HomeAjaxController extends Controller
{

    public function index(Request $request)
    {

        if (\Cache::has('dashboardindex')) {

            $output = \Cache::get('dashboardindex');
        } else {


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



        if ($request->ajax()) {


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


            return response()->json([
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
            ]);
        }
        }
    }

    public function Activitylogs(Request $req)
    {
        if ($req->ajax()) {

            if (\Cache::has('activity_log')) {

                $output = \Cache::get('activity_log');
            }else{

             $activity_logs = Log::latest()->take(3)->get();
            $output = "";
            $i = 1;

            if ($activity_logs) {
                foreach ($activity_logs as $key => $log) {
                    $user = $log->user;
                    $output .= '<tr  role="row" >' .

                        '<td>' .  $i++  . '</td>' .
                        '<td  id="user" >' .  $user->name . '</td>' .
                        '<td id="ip_address" >' .   $log->ip_address  . '</td>' .
                        '<td  id="location">' .  $log->city_name . ', ' . $log->region_name . '<br>' . $log->country_name . ',' . $log->zip_code . '</td>' .
                        '<td  id="date" >' . $log->created_at->diffForHumans()   . '</td>' .

                        '</tr>';
                }

            }

                \Cache::flush();
            return Response($output);
        }

    }
    return view('admin.dashboard');
}


    public function SlineChartMonthly(Request $request)
    {


        if ($request->ajax())
        {

            if (\Cache::has('linechart-monthly'))
            {

                $output = \Cache::get('linechart-monthly');
                return Response($output);
            }
            else
            {



            $set_category = isset($_GET['category']) ? $_GET['category'] : 0;
            $set_category = isset($_GET['category']) ? $_GET['category'] : 0;




            $current_month_name = Carbon::now()->month()->format('M');
            $current_month = Carbon::now()->month;
            $current_year = Carbon::now()->year;
            $date = new DateTime('now');
            $date->modify('last day of this month');


            $first_day = Carbon::parse('01-' . $current_month . '-' . $current_year)->format('Y-m-d');
            $seven_day = Carbon::parse('07-' . $current_month . '-' . $current_year)->format('Y-m-d');
            $fourteen_day = Carbon::parse('14-' . $current_month . '-' . $current_year)->format('Y-m-d');
            $twentyone_day = Carbon::parse('21-' . $current_month . '-' . $current_year)->format('Y-m-d');
            $thirty_day = $date->format('Y-m-d');


            $last1 = Carbon::now()->subdays(1)->format('Y-m-d');
            $last2 = Carbon::now()->subdays(2)->format('Y-m-d');
            $last3 = Carbon::now()->subdays(3)->format('Y-m-d');
            $last4 = Carbon::now()->subdays(4)->format('Y-m-d');
            $last5 = Carbon::now()->subdays(5)->format('Y-m-d');
            $last6 = Carbon::now()->subdays(6)->format('Y-m-d');
            $last7 = Carbon::now()->subdays(7)->format('Y-m-d');


            $last_day_1 = Carbon::now()->subdays(1)->format('d-m-Y');
            $last_day_2 = Carbon::now()->subdays(2)->format('d-m-Y');
            $last_day_3 = Carbon::now()->subdays(3)->format('d-m-Y');
            $last_day_4 = Carbon::now()->subdays(4)->format('d-m-Y');
            $last_day_5 = Carbon::now()->subdays(5)->format('d-m-Y');
            $last_day_6 = Carbon::now()->subdays(6)->format('d-m-Y');
            $last_day_7 = Carbon::now()->subdays(7)->format('d-m-Y');



            $Record_data = '';

            if ($set_category != 0 && $set_category != '') {
                $Record_data = [

                    Record::where('category_id', $_GET['category'])->whereBetween('datetime', [$first_day, $seven_day])->where('status', 1)->where('deleted_status', 0)->count(),
                    Record::where('category_id', $_GET['category'])->whereBetween('datetime', [$seven_day, $fourteen_day])->where('status', 1)->where('deleted_status', 0)->count(),
                    Record::where('category_id', $_GET['category'])->whereBetween('datetime', [$fourteen_day, $twentyone_day])->where('status', 1)->where('deleted_status', 0)->count(),
                    Record::where('category_id', $_GET['category'])->whereBetween('datetime', [$twentyone_day, $thirty_day])->where('status', 1)->where('deleted_status', 0)->count()
                ];
            } else {

                $Record_data = [
                    Record::whereBetween('datetime', [$first_day, $seven_day])->where('status', 1)->where('deleted_status', 0)->count(),
                    Record::whereBetween('datetime', [$seven_day, $fourteen_day])->where('status', 1)->where('deleted_status', 0)->count(),
                    Record::whereBetween('datetime', [$fourteen_day, $twentyone_day])->where('status', 1)->where('deleted_status', 0)->count(),
                    Record::whereBetween('datetime', [$twentyone_day, $thirty_day])->where('status', 1)->where('deleted_status', 0)->count()
                ];
            }


            $purchases_data = '';

            if ($set_category != 0 && $set_category != '') {

                $purchases_data = [

                    PurchaseRecord::where('category_id', $_GET['category'])->whereBetween('datetime', [$first_day, $seven_day])->where('status', 1)->count(),
                    PurchaseRecord::where('category_id', $_GET['category'])->whereBetween('datetime', [$seven_day, $fourteen_day])->where('status', 1)->count(),
                    PurchaseRecord::where('category_id', $_GET['category'])->whereBetween('datetime', [$fourteen_day, $twentyone_day])->where('status', 1)->count(),
                    PurchaseRecord::where('category_id', $_GET['category'])->whereBetween('datetime', [$twentyone_day, $thirty_day])->where('status', 1)->count()
                ];
            } else
            {

                $purchases_data = [
                    PurchaseRecord::whereBetween('datetime', [$first_day, $seven_day])->where('status', 1)->count(),
                    PurchaseRecord::whereBetween('datetime', [$seven_day, $fourteen_day])->where('status', 1)->count(),
                    PurchaseRecord::whereBetween('datetime', [$fourteen_day, $twentyone_day])->where('status', 1)->count(),
                    PurchaseRecord::whereBetween('datetime', [$twentyone_day, $thirty_day])->where('status', 1)->count()
                ];
            }

            return response()->json([
                'Categories_data' => $Record_data,
                'purchases_data' => $purchases_data


            ]);
        }


    }


}


    public function SlineChart(Request $request)
    {

        if ($request->ajax()) {
            if (\Cache::has('Slinechart')) {

                $output = \Cache::get('Slinechart');
                return Response($output);
            } else {



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




            $last1 = Carbon::now()->subdays(1)->format('Y-m-d');
            $last2 = Carbon::now()->subdays(2)->format('Y-m-d');
            $last3 = Carbon::now()->subdays(3)->format('Y-m-d');
            $last4 = Carbon::now()->subdays(4)->format('Y-m-d');
            $last5 = Carbon::now()->subdays(5)->format('Y-m-d');
            $last6 = Carbon::now()->subdays(6)->format('Y-m-d');
            $last7 = Carbon::now()->subdays(7)->format('Y-m-d');

            $current_month_name = Carbon::now()->month()->format('M');
            $current_month = Carbon::now()->month;
            $current_year = Carbon::now()->year;
            $date = new DateTime('now');
            $date->modify('last day of this month');

            $sale_data = '';

            if ($set_category != 0 && $set_category != '') {

                $sale_data = [
                    $sales->where('category_id', $_GET['category'])->whereBetween('datetime', [$last7 . ' 00:00:00', $last7 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                    $sales->where('category_id', $_GET['category'])->whereBetween('datetime', [$last6 . ' 00:00:00', $last6 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                    $sales->where('category_id', $_GET['category'])->whereBetween('datetime', [$last5 . ' 00:00:00', $last5 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                    $sales->where('category_id', $_GET['category'])->whereBetween('datetime', [$last4 . ' 00:00:00', $last4 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                    $sales->where('category_id', $_GET['category'])->whereBetween('datetime', [$last3 . ' 00:00:00', $last3 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                    $sales->where('category_id', $_GET['category'])->whereBetween('datetime', [$last2 . ' 00:00:00', $last2 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                    $sales->where('category_id', $_GET['category'])->whereBetween('datetime', [$last1 . ' 00:00:00', $last1 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count()
                ];
            } else {
                $sale_data = [
                    $sales->whereBetween('datetime', [$last7 . ' 00:00:00', $last7 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                    $sales->whereBetween('datetime', [$last6 . ' 00:00:00', $last6 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                    $sales->whereBetween('datetime', [$last5 . ' 00:00:00', $last5 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                    $sales->whereBetween('datetime', [$last4 . ' 00:00:00', $last4 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                    $sales->whereBetween('datetime', [$last3 . ' 00:00:00', $last3 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                    $sales->whereBetween('datetime', [$last2 . ' 00:00:00', $last2 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                    $sales->whereBetween('datetime', [$last1 . ' 00:00:00', $last1 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                ];
            }
            $purchases_data = '';

            if ($set_category != 0 && $set_category != '') {

                $purchases_data = [
                    $purchases->where('category_id', $_GET['category'])->whereBetween('datetime', [$last7 . ' 00:00:00', $last7 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                    $purchases->where('category_id', $_GET['category'])->whereBetween('datetime', [$last6 . ' 00:00:00', $last6 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                    $purchases->where('category_id', $_GET['category'])->whereBetween('datetime', [$last5 . ' 00:00:00', $last5 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                    $purchases->where('category_id', $_GET['category'])->whereBetween('datetime', [$last4 . ' 00:00:00', $last4 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                    $purchases->where('category_id', $_GET['category'])->whereBetween('datetime', [$last3 . ' 00:00:00', $last3 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                    $purchases->where('category_id', $_GET['category'])->whereBetween('datetime', [$last2 . ' 00:00:00', $last2 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                    $purchases->where('category_id', $_GET['category'])->whereBetween('datetime', [$last1 . ' 00:00:00', $last1 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                ];
            } else {
                $purchases_data = [

                    $purchases->whereBetween('datetime', [$last7 . ' 00:00:00', $last7 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                    $purchases->whereBetween('datetime', [$last6 . ' 00:00:00', $last6 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                    $purchases->whereBetween('datetime', [$last5 . ' 00:00:00', $last5 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                    $purchases->whereBetween('datetime', [$last4 . ' 00:00:00', $last4 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                    $purchases->whereBetween('datetime', [$last3 . ' 00:00:00', $last3 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                    $purchases->whereBetween('datetime', [$last2 . ' 00:00:00', $last2 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                    $purchases->whereBetween('datetime', [$last1 . ' 00:00:00', $last1 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                ];
            }



            $Categories_data = [
                date('d', strtotime($last7)) . $current_month_name,

                date('d', strtotime($last6)) . $current_month_name,

                date('d', strtotime($last5)) . $current_month_name,

                date('d', strtotime($last4)) . $current_month_name,

                date('d', strtotime($last3)) . $current_month_name,

                date('d', strtotime($last2)) . $current_month_name,

                date('d', strtotime($last1)) . $current_month_name,

            ];
                return response()->json([
                    'Categories_data' => $Categories_data,
                    'purchases_data' => $purchases_data,
                    'sale_data' => $sale_data


                ]);
        }





    }
}

    public function linechart(Request $request)
    {
        if ($request->ajax()) {

                if (\Cache::has('linechart')) {

                    $output = \Cache::get('linechart');
                    return Response($output);
                } else {


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




            $last1 = Carbon::now()->subdays(1)->format('Y-m-d');
            $last2 = Carbon::now()->subdays(2)->format('Y-m-d');
            $last3 = Carbon::now()->subdays(3)->format('Y-m-d');
            $last4 = Carbon::now()->subdays(4)->format('Y-m-d');
            $last5 = Carbon::now()->subdays(5)->format('Y-m-d');
            $last6 = Carbon::now()->subdays(6)->format('Y-m-d');
            $last7 = Carbon::now()->subdays(7)->format('Y-m-d');

            $current_month_name = Carbon::now()->month()->format('M');
            $current_month = Carbon::now()->month;
            $current_year = Carbon::now()->year;
            $date = new DateTime('now');
            $date->modify('last day of this month');

            $sale_data = '';

            if ($set_category != 0 && $set_category != '') {

                $sale_data = [
                    $sales->where('category_id', $_GET['category'])->whereBetween('datetime', [$last7 . ' 00:00:00', $last7 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                    $sales->where('category_id', $_GET['category'])->whereBetween('datetime', [$last6 . ' 00:00:00', $last6 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                    $sales->where('category_id', $_GET['category'])->whereBetween('datetime', [$last5 . ' 00:00:00', $last5 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                    $sales->where('category_id', $_GET['category'])->whereBetween('datetime', [$last4 . ' 00:00:00', $last4 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                    $sales->where('category_id', $_GET['category'])->whereBetween('datetime', [$last3 . ' 00:00:00', $last3 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                    $sales->where('category_id', $_GET['category'])->whereBetween('datetime', [$last2 . ' 00:00:00', $last2 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                    $sales->where('category_id', $_GET['category'])->whereBetween('datetime', [$last1 . ' 00:00:00', $last1 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count()
                ];
            } else {
                $sale_data = [
                    $sales->whereBetween('datetime', [$last7 . ' 00:00:00', $last7 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                    $sales->whereBetween('datetime', [$last6 . ' 00:00:00', $last6 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                    $sales->whereBetween('datetime', [$last5 . ' 00:00:00', $last5 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                    $sales->whereBetween('datetime', [$last4 . ' 00:00:00', $last4 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                    $sales->whereBetween('datetime', [$last3 . ' 00:00:00', $last3 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                    $sales->whereBetween('datetime', [$last2 . ' 00:00:00', $last2 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                    $sales->whereBetween('datetime', [$last1 . ' 00:00:00', $last1 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                ];
            }
            $purchases_data = '';

            if ($set_category != 0 && $set_category != '') {

                $purchases_data = [
                    $purchases->where('category_id', $_GET['category'])->whereBetween('datetime', [$last7 . ' 00:00:00', $last7 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                    $purchases->where('category_id', $_GET['category'])->whereBetween('datetime', [$last6 . ' 00:00:00', $last6 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                    $purchases->where('category_id', $_GET['category'])->whereBetween('datetime', [$last5 . ' 00:00:00', $last5 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                    $purchases->where('category_id', $_GET['category'])->whereBetween('datetime', [$last4 . ' 00:00:00', $last4 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                    $purchases->where('category_id', $_GET['category'])->whereBetween('datetime', [$last3 . ' 00:00:00', $last3 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                    $purchases->where('category_id', $_GET['category'])->whereBetween('datetime', [$last2 . ' 00:00:00', $last2 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                    $purchases->where('category_id', $_GET['category'])->whereBetween('datetime', [$last1 . ' 00:00:00', $last1 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                ];
            } else {
                $purchases_data = [

                    $purchases->whereBetween('datetime', [$last7 . ' 00:00:00', $last7 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                    $purchases->whereBetween('datetime', [$last6 . ' 00:00:00', $last6 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                    $purchases->whereBetween('datetime', [$last5 . ' 00:00:00', $last5 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                    $purchases->whereBetween('datetime', [$last4 . ' 00:00:00', $last4 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                    $purchases->whereBetween('datetime', [$last3 . ' 00:00:00', $last3 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                    $purchases->whereBetween('datetime', [$last2 . ' 00:00:00', $last2 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                    $purchases->whereBetween('datetime', [$last1 . ' 00:00:00', $last1 . ' 24:00:00'])->where('status', 1)->where('deleted_status', 0)->count(),
                ];
            }



            $Categories_data = [
                date('d', strtotime($last7)) . $current_month_name,

                date('d', strtotime($last6)) . $current_month_name,

                date('d', strtotime($last5)) . $current_month_name,

                date('d', strtotime($last4)) . $current_month_name,

                date('d', strtotime($last3)) . $current_month_name,

                date('d', strtotime($last2)) . $current_month_name,

                date('d', strtotime($last1)) . $current_month_name,

            ];
                    return response()->json([
                        'Categories_data' => $Categories_data,
                        'purchases_data' => $purchases_data,
                        'sale_data' => $sale_data


                    ]);
        }


    }
}




    public function supplierchart(Request $request)
    {


        if ($request->ajax()) {

            if (\Cache::has('supplierchart')) {

                $output = \Cache::get('supplierchart');
                return Response($output);
            } else {


            $companies = [];
            $purchases_companies = [];

            $set_category = isset($_GET['category']) ? $_GET['category'] : 0;
            $set_category = isset($_GET['category']) ? $_GET['category'] : 0;
            $products = \App\Product::select('id', 'name')->where('status', 1)->get();
            if ($set_category != 0 && $set_category != '') {
                $records = Record::select('id', 'supplier_company_id')->where('category_id', $_GET['category'])->where('status', 1)->where('deleted_status', 0)->get()->groupBy('supplier_company_id');
                $purchases = PurchaseRecord::select('id', 'supplier_company_id')->where('category_id', $_GET['category'])->where('status', 1)->where('deleted_status', 0)->get()->groupBy('supplier_company_id');
            } else {
                $records = Record::select('id', 'supplier_company_id')->where('status', 1)->where('deleted_status', 0)->get()->groupBy('supplier_company_id');
                $purchases = PurchaseRecord::select('id', 'supplier_company_id')->where('status', 1)->where('deleted_status', 0)->get()->groupBy('supplier_company_id');
            }

            $array = [];
            $per_total = 0;

            foreach ($records as $supplier_record) {

                $company = $supplier_record->first();
                $total_liters = 0;

                foreach ($products as $product) {
                    ${"prod_$product->id"} = 0;
                }
                foreach ($supplier_record as $rec) {
                    foreach ($products as $product) {

                        $data = $rec->products->where('product_id', $product->id)->first();
                        $qty = ($data) ? $data->qty : 0;
                        ${"prod_$product->id"} = ${"prod_$product->id"} + $qty;
                        $total_liters = $total_liters + ${"prod_$product->id"};
                    }
                }

                $companies[$company->supplierCompany->name] = $total_liters;
            }
            foreach ($purchases as $supplier_record) {

                $company = $supplier_record->first();
                $total_liters = 0;

                foreach ($products as $product) {
                    ${"prod_$product->id"} = 0;
                }
                foreach ($supplier_record as $rec) {
                    foreach ($products as $product) {

                        $data = $rec->products->where('product_id', $product->id)->first();
                        $qty = ($data) ? $data->qty : 0;
                        ${"prod_$product->id"} = ${"prod_$product->id"} + $qty;
                        $total_liters = $total_liters + ${"prod_$product->id"};
                    }
                }

                $purchases_companies[$company->fuelCompany->name] = $total_liters;
            }



            foreach ($companies as $company => $liter) {
                $supplier_sale[] = $liter;
            }



            foreach ($purchases_companies as $company => $liter) {
                $supplier_purchase[] = $liter;
            }





            foreach ($companies as $company => $liter) {
                $supplier_category[] = $company;
            }

            return response()->json([
                'supplier_sale' => $supplier_sale,
                'supplier_purchase' => $supplier_purchase,
                'supplier_category' => $supplier_category


            ]);
        }
    }
    }





    public function sidechart(Request $request)
    {
        if ($request->ajax()) {
            if (\Cache::has('sidechart')) {

                $output = \Cache::get('sidechart');
                return Response($output);
            } else {


            $product_record = \DB::table('record_products')
                                    ->select(\DB::raw('sum(qty) as qty, name'))
                                    ->join('products', 'record_products.product_id', '=', 'products.id')
                                    ->groupBy('product_id')
                                    ->get();
                                $per_total = 0;
                                foreach ($product_record as $record_data) {
                                    $array[$record_data->name] = $record_data->qty;
                                    $per_total += $record_data->qty;
                                }

                                arsort($array);
                                $height = count($array) * 48.87;


                                foreach ($array as $key => $value){

                                             $k[] =    $key ;

                                             $v[] = $value;

                                            $p[] = round(($value / $per_total) * 100, 0);




                                }



        return response()->json([

            'array' => $array,
            'v' => $v,
            'k' => $k,
            'p' =>$p


        ]);
    }
        }
    }

    public function monthltyReport(Request $request)
    {

        if ($request->ajax()) {
            if (\Cache::has('monthltyReport')) {

                $output = \Cache::get('monthltyReport');
                return Response($output);
            } else {



            $set_category = isset($_GET['category']) ? $_GET['category'] : 0;
            $set_category = isset($_GET['category']) ? $_GET['category'] : 0;




    $current_month_name = Carbon::now()->month()->format('M');
    $current_month = Carbon::now()->month;
    $current_year = Carbon::now()->year;
    $date = new DateTime('now');
    $date->modify('last day of this month');

    $sales_data = '';


                if ($set_category != 0 && $set_category != '') {

                    $sales_data = [

                Record::where('category_id',$_GET['category'])->whereMonth('datetime', '01')->whereYear('datetime',$current_year)->where('status',1)->where('deleted_status',0)->count(),
            Record::where('category_id',$_GET['category'])->whereMonth('datetime', '02')->whereYear('datetime',$current_year)->where('status',1)->where('deleted_status',0)->count(),
            Record::where('category_id',$_GET['category'])->whereMonth('datetime', '03')->whereYear('datetime',$current_year)->where('status',1)->where('deleted_status',0)->count(),
            Record::where('category_id',$_GET['category'])->whereMonth('datetime', '04')->whereYear('datetime',$current_year)->where('status',1)->where('deleted_status',0)->count(),
            Record::where('category_id',$_GET['category'])->whereMonth('datetime', '05')->whereYear('datetime',$current_year)->where('status',1)->where('deleted_status',0)->count(),
            Record::where('category_id',$_GET['category'])->whereMonth('datetime', '06')->whereYear('datetime',$current_year)->where('status',1)->where('deleted_status',0)->count(),
            Record::where('category_id',$_GET['category'])->whereMonth('datetime', '07')->whereYear('datetime',$current_year)->where('status',1)->where('deleted_status',0)->count(),
            Record::where('category_id',$_GET['category'])->whereMonth('datetime', '08')->whereYear('datetime',$current_year)->where('status',1)->where('deleted_status',0)->count(),
            Record::where('category_id',$_GET['category'])->whereMonth('datetime', '09')->whereYear('datetime',$current_year)->where('status',1)->where('deleted_status',0)->count(),
            Record::where('category_id',$_GET['category'])->whereMonth('datetime', '10')->whereYear('datetime',$current_year)->where('status',1)->where('deleted_status',0)->count(),
            Record::where('category_id',$_GET['category'])->whereMonth('datetime', '11')->whereYear('datetime',$current_year)->where('status',1)->where('deleted_status',0)->count(),
            Record::where('category_id',$_GET['category'])->whereMonth('datetime', '12')->whereYear('datetime',$current_year)->where('status',1)->where('deleted_status',0)->count()
                    ];
                } else {
                    $sales_data = [


                Record::whereMonth('datetime', '01')->whereYear('datetime',$current_year)->where('status',1)->where('deleted_status',0)->count(),
            Record::whereMonth('datetime', '02')->whereYear('datetime',$current_year)->where('status',1)->where('deleted_status',0)->count(),
            Record::whereMonth('datetime', '03')->whereYear('datetime',$current_year)->where('status',1)->where('deleted_status',0)->count(),
            Record::whereMonth('datetime', '04')->whereYear('datetime',$current_year)->where('status',1)->where('deleted_status',0)->count(),
            Record::whereMonth('datetime', '05')->whereYear('datetime',$current_year)->where('status',1)->where('deleted_status',0)->count(),
            Record::whereMonth('datetime', '06')->whereYear('datetime',$current_year)->where('status',1)->where('deleted_status',0)->count(),
            Record::whereMonth('datetime', '07')->whereYear('datetime',$current_year)->where('status',1)->where('deleted_status',0)->count(),
            Record::whereMonth('datetime', '08')->whereYear('datetime',$current_year)->where('status',1)->where('deleted_status',0)->count(),
            Record::whereMonth('datetime', '09')->whereYear('datetime',$current_year)->where('status',1)->where('deleted_status',0)->count(),
            Record::whereMonth('datetime', '10')->whereYear('datetime',$current_year)->where('status',1)->where('deleted_status',0)->count(),
            Record::whereMonth('datetime', '11')->whereYear('datetime',$current_year)->where('status',1)->where('deleted_status',0)->count(),
            Record::whereMonth('datetime', '12')->whereYear('datetime',$current_year)->where('status',1)->where('deleted_status',0)->count()
                    ];

            }




$Purchase_data = '';

                if ($set_category != 0 && $set_category != '') {

                    $Purchase_data = [

                PurchaseRecord::where('category_id',$_GET['category'])->whereMonth('datetime', '01')->whereYear('datetime',$current_year)->where('status',1)->count(),
            PurchaseRecord::where('category_id',$_GET['category'])->whereMonth('datetime', '02')->whereYear('datetime',$current_year)->where('status',1)->count(),
            PurchaseRecord::where('category_id',$_GET['category'])->whereMonth('datetime', '03')->whereYear('datetime',$current_year)->where('status',1)->count(),
            PurchaseRecord::where('category_id',$_GET['category'])->whereMonth('datetime', '04')->whereYear('datetime',$current_year)->where('status',1)->count(),
            PurchaseRecord::where('category_id',$_GET['category'])->whereMonth('datetime', '05')->whereYear('datetime',$current_year)->where('status',1)->count(),
            PurchaseRecord::where('category_id',$_GET['category'])->whereMonth('datetime', '06')->whereYear('datetime',$current_year)->where('status',1)->count(),
            PurchaseRecord::where('category_id',$_GET['category'])->whereMonth('datetime', '07')->whereYear('datetime',$current_year)->where('status',1)->count(),
            PurchaseRecord::where('category_id',$_GET['category'])->whereMonth('datetime', '08')->whereYear('datetime',$current_year)->where('status',1)->count(),
            PurchaseRecord::where('category_id',$_GET['category'])->whereMonth('datetime', '09')->whereYear('datetime',$current_year)->where('status',1)->count(),
            PurchaseRecord::where('category_id',$_GET['category'])->whereMonth('datetime', '10')->whereYear('datetime',$current_year)->where('status',1)->count(),
            PurchaseRecord::where('category_id',$_GET['category'])->whereMonth('datetime', '11')->whereYear('datetime',$current_year)->where('status',1)->count(),
            PurchaseRecord::where('category_id',$_GET['category'])->whereMonth('datetime', '12')->whereYear('datetime',$current_year)->where('status',1)->count()
                    ];
                } else {
                    $Purchase_data = [
                PurchaseRecord::whereMonth('datetime', '01')->whereYear('datetime',$current_year)->where('status',1)->count(),
            PurchaseRecord::whereMonth('datetime', '02')->whereYear('datetime',$current_year)->where('status',1)->count(),
            PurchaseRecord::whereMonth('datetime', '03')->whereYear('datetime',$current_year)->where('status',1)->count(),
            PurchaseRecord::whereMonth('datetime', '04')->whereYear('datetime',$current_year)->where('status',1)->count(),
            PurchaseRecord::whereMonth('datetime', '05')->whereYear('datetime',$current_year)->where('status',1)->count(),
            PurchaseRecord::whereMonth('datetime', '06')->whereYear('datetime',$current_year)->where('status',1)->count(),
            PurchaseRecord::whereMonth('datetime', '07')->whereYear('datetime',$current_year)->where('status',1)->count(),
            PurchaseRecord::whereMonth('datetime', '08')->whereYear('datetime',$current_year)->where('status',1)->count(),
            PurchaseRecord::whereMonth('datetime', '09')->whereYear('datetime',$current_year)->where('status',1)->count(),
            PurchaseRecord::whereMonth('datetime', '10')->whereYear('datetime',$current_year)->where('status',1)->count(),
            PurchaseRecord::whereMonth('datetime', '11')->whereYear('datetime',$current_year)->where('status',1)->count(),
            PurchaseRecord::whereMonth('datetime', '12')->whereYear('datetime',$current_year)->where('status',1)->count()
                    ];

            }
                return response()->json([
                    'sales_data' => $sales_data,
                    'purchases_data' => $Purchase_data


                ]);
        }






    }





}
}
