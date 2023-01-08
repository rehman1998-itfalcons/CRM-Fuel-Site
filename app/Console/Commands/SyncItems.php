<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use  App\Libraries\MyobCurl;
use App\myobtokens;
use App\Product;
use Illuminate\Support\Facades\Log;
use Session;
use Illuminate\Support\Facades\DB;

class SyncItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sync-items';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'items sync job';

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
        // Cronjob #1

        // if status == 0 return null or false.
        // if status == 1 change status to 2//
        $cron_job =  DB::table('cron_jobs')->where('type', 'items')->first();
        if ($cron_job->type == 'items' && $cron_job->status == 1) {

            $db_product = Product::get();

            if (coupon_status() == false) {
                return redirect('/myob');
            }
            $myob1 = DB::table('myob')->first();
            // dump($myob1->status);
            if ($myob1->status == 1) {
                // dump('im in');
                $myob = new MyobCurl;
                $token = myobtokens::find(1)->access_token;
                $get_data =     $myob->FileGetContents(company_uri() . 'Inventory/Item', 'get', '', $token);
                $item_list = json_decode($get_data['response'], true);
                //dump($item_list);
                $not_sync = [];
                $sync = [];
                // dump($db_product);
                foreach ($db_product as $product_local) {
                    $not_sync[$product_local->id] = $product_local;
                    foreach ($item_list['Items'] as $item) {
                        if (isset($item['Number']) && isset($product_local->number) && $product_local->number == $item['Number']) {
                            $sync[$product_local->id] = $product_local;
                        }
                    }
                }
                //  dump($sync);
                foreach ($sync as $key => $all_key) {
                    unset($not_sync[$key]);
                }
                //dump($not_sync);
                foreach ($not_sync as $product_notsync) {
                    $post_json_data = [

                        'Number' => $product_notsync->number,
                        'Name' => $product_notsync->name,
                        'IsActive' => true,
                        'Description' => $product_notsync->name
                    ];
                    $post_data = $myob->FileGetContents(company_uri() . 'Inventory/Item', 'post', json_encode($post_json_data), $token);
                    dump($post_data);
                }
                //dd('out');
                $get_data =     $myob->FileGetContents(company_uri() . 'Inventory/Item', 'get', '', $token);
                $item_list = json_decode($get_data['response'], true);
                foreach ($db_product as $product_local) {
                    foreach ($item_list['Items'] as $item) {
                        if (isset($item['Number']) && isset($product_local->number) && $product_local->number == $item['Number']) {
                            DB::table('products')->where('id', $product_local->id)->update([
                                'item_uid' => $item['UID'],
                                'number' => $item['Number'],
                                'status' => 1
                            ]);
                            $cron_job->status = 2;
                        }
                    }
                }




                $cron_job->total_run = $cron_job->total_run + 1;
                DB::table('cron_jobs')->where('type', $cron_job->type)->update([
                    'status' => 0,
                    'total_run' => $cron_job->total_run
                ]);
                DB::table('cron_jobs')->where('type', 'customers')->update([
                    'status' => 1
                ]);
            }
        }
    }
}
