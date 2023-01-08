<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use  App\Libraries\MyobCurl;
use App\myobtokens;
use Illuminate\Support\Facades\DB;

class PurchaseCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sync-purchase-invoices-cache-to-myob';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'purchase cache for pagination';

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

        $myob = new MyobCurl;
     $token = myobtokens::find(1)->access_token;

     $cron_job =  DB::table('cron_jobs')->where('type', 'sync_p_cache')->first();
        if ($cron_job->type == 'sync_p_cache' && $cron_job->status == 1)
        {

        $get_data =     $myob->FileGetContents(company_uri() . 'Purchase/Bill/Item', 'get', '', $token);
                $purchase_record_list_final = json_decode($get_data['response'], true);
                $purchase_record_list[] = $purchase_record_list_final['Items'];

                $total_pages = $purchase_record_list_final['Count'] / 400;
                for ($x = 1; $x <= $total_pages; $x++) {
                    $page_count = $x * 400;
                    $get_data =     $myob->FileGetContents(company_uri() . 'Purchase/Bill/Item?$top=400&$skip=' . $page_count, 'get', '', $token);
                    $purchase_record_list_final_2 = json_decode($get_data['response'], true);
                    $purchase_record_list[] = $purchase_record_list_final_2['Items'];
                }

                $minutes = 30;
                $purchase_list = \Cache::remember('purchases_list', $minutes, function () use( $purchase_record_list){
                return $purchase_record_list;
            });

                $cron_job->total_run = $cron_job->total_run + 1;
            DB::table('cron_jobs')->where('type' , $cron_job->type)->update([
                'status' => 0,
                'total_run' => $cron_job->total_run
            ]);
            DB::table('cron_jobs')->where('type' , 'sync_purchases')->update([
                'status' => 1
            ]);
        }
    }
}
