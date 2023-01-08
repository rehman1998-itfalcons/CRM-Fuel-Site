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

class sidechart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sidechart';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command sidechart';

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
        $cron_job =  DB::table('dashboard_cache')->where('value', 'sidechart')->first();
        if ($cron_job->value == 'sidechart' && $cron_job->status == 1) {

        $product_record = DB::table('record_products')
            ->select(DB::raw('sum(qty) as qty, name'))
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


        foreach ($array as $key => $value) {

            $k[] =    $key;

            $v[] = $value;

            $p[] = round(($value / $per_total) * 100, 0);
        }


        $minutes = 10;
        $output = \Cache::remember('sidechart', $minutes, function () use ($array, $v, $k,$p) {

            return [
                    'array' => $array,
                    'v' => $v,
                    'k' => $k,
                    'p' => $p


            ];
        });

        $cron_job->total_run = $cron_job->total_run + 1;
        DB::table('dashboard_cache')->where('value', $cron_job->value)->update([
            'status' => 0,
            'total_run' => $cron_job->total_run
        ]);
        DB::table('dashboard_cache')->where('value', 'monthltyReport')->update([
            'status' => 1
        ]);
    }


    }
}
