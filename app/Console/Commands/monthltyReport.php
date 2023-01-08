<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Log as Logger;
use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Record;
use App\PurchaseRecord;
use App\RecordProduct;
use App\Log;
use DateTime;
use FontLib\TrueType\Collection;
use Illuminate\Support\Facades\DB;

class monthltyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:monthltyReport';

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
        $cron_job =  DB::table('dashboard_cache')->where('value', 'monthltyReport')->first();
        if ($cron_job->value == 'monthltyReport' && $cron_job->status == 1) {

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
        }

        $minutes = 13;
        $output = \Cache::remember('monthltyReport', $minutes, function () use ( $Purchase_data, $sales_data) {

            return [
            'sales_data' => $sales_data,
            'purchases_data' => $Purchase_data
                ];
        });

        $cron_job->total_run = $cron_job->total_run + 1;
        DB::table('dashboard_cache')->where('value', 'activity_log')->update([
            'status' => 1
        ]);
        DB::table('dashboard_cache')->where('value', $cron_job->value)->update([
            'status' => 0,
            'total_run' => $cron_job->total_run
        ]);
        Logger::info('updated.................');





    }
}

