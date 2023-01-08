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

class lineChartMonthly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:linechart-monthly';

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
        //
        $cron_job =  DB::table('dashboard_cache')->where('value', 'linechart-monthly')->first();
        if ($cron_job->value == 'linechart-monthly' && $cron_job->status == 1) {

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
            } else {

                $purchases_data = [
                    PurchaseRecord::whereBetween('datetime', [$first_day, $seven_day])->where('status', 1)->count(),
                    PurchaseRecord::whereBetween('datetime', [$seven_day, $fourteen_day])->where('status', 1)->count(),
                    PurchaseRecord::whereBetween('datetime', [$fourteen_day, $twentyone_day])->where('status', 1)->count(),
                    PurchaseRecord::whereBetween('datetime', [$twentyone_day, $thirty_day])->where('status', 1)->count()
                ];
            }

            $minutes = 7;
            $output = \Cache::remember('linechart-monthly', $minutes, function () use ($Record_data, $purchases_data) {
                // return $output;
                return [
                    'Categories_data' => $Record_data,
                    'purchases_data' => $purchases_data


                ];
            });

            $cron_job->total_run = $cron_job->total_run + 1;
            DB::table('dashboard_cache')->where('value', $cron_job->value)->update([
                'status' => 0,
                'total_run' => $cron_job->total_run
            ]);
            DB::table('dashboard_cache')->where('value', 'slinechart')->update([
                'status' => 1
            ]);
        }




    }
}
