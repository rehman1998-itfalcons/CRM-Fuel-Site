<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Record;
use App\PurchaseRecord;
use App\RecordProduct;
use App\Log;
use DateTime;
use FontLib\TrueType\Collection;
use Illuminate\Support\Facades\DB;

class Slinechart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:Slinechart';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $cron_job =  DB::table('dashboard_cache')->where('value', 'slinechart')->first();
        if ($cron_job->value == 'slinechart' && $cron_job->status == 1) {
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


            $minutes = 9;
            $output = \Cache::remember('Slinechart', $minutes, function () use ($Categories_data, $purchases_data,$sale_data) {

                return [
            'Categories_data' => $Categories_data,
            'purchases_data' => $purchases_data,
            'sale_data' => $sale_data


                ];
            });

            $cron_job->total_run = $cron_job->total_run + 1;
            DB::table('dashboard_cache')->where('value', $cron_job->value)->update([
                'status' => 0,
                'total_run' => $cron_job->total_run
            ]);
            DB::table('dashboard_cache')->where('value', 'linechart')->update([
                'status' => 1
            ]);

    }
}

}
