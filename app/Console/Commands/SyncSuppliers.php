<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use App\SupplierCompany;
use Session;
use Str;
use Illuminate\Support\Facades\DB;
use App\myobtokens;

use  App\Libraries\MyobCurl;

class SyncSuppliers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:Sync-supplier-companies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync supplier companies';

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


        $cron_job =  DB::table('cron_jobs')->where('type','suppliers')->first();
        if ($cron_job->type == 'suppliers' && $cron_job->status == 1)
        {

            $db_suppliercompany = SupplierCompany::get();

            if (coupon_status() == false) {
                return redirect('/myob');
            }
            $myob1 = DB::table('myob')->first();
            if ($myob1->status == 1) {
                $myob = new MyobCurl;
                $token = myobtokens::find(1)->access_token;
                $get_data =     $myob->FileGetContents(company_uri() . 'Contact/Supplier', 'get', '', $token);
                $supplier_list = json_decode($get_data['response'], true);
                $not_sync = [];
                $sync = [];
                foreach ($db_suppliercompany as $supplier_comp) {
                    $not_sync[$supplier_comp->id] = $supplier_comp;
                    foreach ($supplier_list['Items'] as $slist) {


                        if (isset($slist['DisplayID']) && isset($supplier_comp->display_id) && $supplier_comp->display_id == $slist['DisplayID']) {
                            $sync[$supplier_comp->id] = $supplier_comp;
                        }
                    }
                }

                foreach ($sync as $key => $all_key) {
                    unset($not_sync[$key]);
                }
                foreach ($not_sync as $scompany_notsync) {
                    $post_json_data = [

                        'CompanyName' => $scompany_notsync->name,
                        'IsIndividual' => false,
                        'DisplayID' => $scompany_notsync->display_id,
                        "BuyingDetails" => [
                            "TaxCode" => [
                                //live uid
                                  "UID" => "4afae618-116c-4b16-ba56-6486cfaafef0",
                                //sanaullah developer api UID
                                //  "UID" => "be778956-e739-4e23-8786-c2f63aa4fa28",
                                "Code" => "GST"
                            ],
                            "FreightTaxCode" => [
                                //live uid
                                  "UID" => "4afae618-116c-4b16-ba56-6486cfaafef0",
                                //sanaullah developer api UID
                                //  "UID" => "be778956-e739-4e23-8786-c2f63aa4fa28",
                                "Code" => "GST"
                            ]
                        ]
                    ];

                    $post_data =     $myob->FileGetContents(company_uri() . 'Contact/Supplier', 'post', json_encode($post_json_data), $token);
                    //  dump($post_data);
                }

                $get_data =     $myob->FileGetContents(company_uri() . 'Contact/Supplier', 'get', '', $token);
                $supplier_list1 = json_decode($get_data['response'], true);
                // dd($supplier_list1);

                foreach ($db_suppliercompany as $supplier_comp) {
                    foreach ($supplier_list1['Items'] as $slist) {


                        if (isset($slist['DisplayID']) && isset($supplier_comp->display_id) && $supplier_comp->display_id == $slist['DisplayID']) {
                            // dump('updation code');
                            DB::table('supplier_companies')->where('id', $supplier_comp->id)->update([
                                'supplier_uid' => $slist['UID'],
                                'display_id' => $slist['DisplayID'],
                                'myob_status' => 1
                            ]);
                            $cron_job->status = 2;
                            // dump('update');
                        }
                    }
                }

                // return redirect()->back();
            }
            $cron_job->total_run = $cron_job->total_run + 1;
            DB::table('cron_jobs')->where('type' , $cron_job->type)->update([
                'status' => 0,
                'total_run' => $cron_job->total_run
            ]);
            DB::table('cron_jobs')->where('type' , 'suppliers-coa')->update([
                'status' => 1
            ]);

        }
    }
}
