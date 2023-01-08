<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use  App\Libraries\MyobCurl;
use App\myobtokens;
use App\Record;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class SyncSalesRecordCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sync-sales-records-cache-to-myob';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'sales cache for pagination';

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
        $cron_job =  DB::table('cron_jobs')->where('type', 'sync_sale_cache')->first();
        if ($cron_job->type == 'sync_sale_cache' && $cron_job->status == 1) {

            $myob = new MyobCurl;
            $token = myobtokens::find(1)->access_token;

            $get_data =     $myob->FileGetContents(company_uri() . 'Sale/Invoice/Item', 'get', '', $token);
            // Log::info($get_data);
            $sale_record_list_final = json_decode($get_data['response'], true);

            if (isset($sale_record_list_final['Items']) && !empty($sale_record_list_final['Items'])){
            $sale_record_list[]= $sale_record_list_final['Items'];

            $total_pages = $sale_record_list_final['Count'] / 400;
                for ($x = 1; $x <= $total_pages; $x++) {
                    $page_count = $x * 400;
                    $get_data =     $myob->FileGetContents(company_uri() . 'Sale/Invoice/Item?$top=400&$skip=' . $page_count, 'get', '', $token);
                    $sale_record_list_final_2 = json_decode($get_data['response'], true);
                    $sale_record_list[]= $sale_record_list_final_2['Items'];
                }
                
                $minutes = 30;
                $slist = \Cache::remember('sale_list', $minutes, function () use($sale_record_list){
                    return $sale_record_list;
                } );
         
            }
            $cron_job->total_run = $cron_job->total_run + 1;
            DB::table('cron_jobs')->where('type' , $cron_job->type)->update([
                'status' => 0,
                'total_run' => $cron_job->total_run
            ]);
            DB::table('cron_jobs')->where('type' , 'sync_sales')->update([
                'status' => 1
            ]);
        }
    }
}
