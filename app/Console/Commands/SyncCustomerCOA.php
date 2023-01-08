<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use App\SubCompany;
use App\SubCompanyEmail;
use Session;
use Illuminate\Support\Facades\DB;
use App\Company;
use Illuminate\Support\Facades\Log;
use App\Category;
use App\Product;
use App\SubcompanyField;
use App\SubCompanyRateArea;
use  App\Libraries\MyobCurl;
use App\myobtokens;

class SyncCustomerCOA extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:Sync-customer-chartOfAccount
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'sync customer chat of account';

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
        $cron_job =  DB::table('cron_jobs')->where('type','customer_coa')->first();
        if ($cron_job->type == 'customer_coa' && $cron_job->status == 1)
        {


            $db_subcompany = SubCompany::where('coa_status', 0)->limit(10)->get();

            if (coupon_status() == false) {
                return redirect('/myob');
            }
            $myob1 = DB::table('myob')->first();
            if ($myob1->status == 1) {
                $myob = new MyobCurl;
                $token = myobtokens::find(1)->access_token;

                $get_data =     $myob->FileGetContents(company_uri() . 'GeneralLedger/Account', 'get', '', $token);
                $customer_list = json_decode($get_data['response'], true);
                $not_sync = [];
                $sync = [];
                foreach ($db_subcompany as $customer_comp) {
                    foreach ($customer_list['Items'] as $slist) {

                        $not_sync[$customer_comp->id] = $customer_comp;
                        // dd($slist['DisplayID']);
                        // dd($customer_comp->display_id);
                        if (isset($slist['DisplayID']) && isset($customer_comp->display_id) && $customer_comp->display_id == $slist['DisplayID']) {
                            $sync[$customer_comp->id] = $customer_comp;
                        }
                    }
                }

                foreach ($sync as $key => $all_key) {
                    unset($not_sync[$key]);
                }

                foreach ($not_sync as $subcompany_notsync) {
                    $display = $subcompany_notsync->display_id;
                    $post_json_data = [
                        "Name" => $subcompany_notsync->name,
                        "DisplayID" => $display,
                        "Classification" => "Income",
                        "Type" => "Income",
                        "Number" => substr($display, 2, 4),
                        "Description" => $subcompany_notsync->name,
                        "IsActive" => true,
                        "TaxCode" => [

                            // INCOME tax code DEMO developer UID gst code
                            //  "UID" => "be778956-e739-4e23-8786-c2f63aa4fa28",
                            // INCOME taxcode live UID
                              "UID" => "f9338d31-b33f-425a-bbf7-da4bd8cd53e1",
                        ],
                        "ParentAccount" => [
                            // live parent account UID
                              "UID" => "13f54671-7a4b-4e1e-9de6-0440da9ec25d",
                            // DEMO parent uid TYPE INCODE
                            //  "UID" => "393e5d7d-b0ca-4b48-8105-c8fb90488eee",
                        ]
                    ];



                    $post_data =     $myob->FileGetContents(
                        company_uri() . 'GeneralLedger/Account',
                        'post',
                        json_encode($post_json_data),
                        $token
                    );
                    //  dump($post_data);
                }

                $get_data =     $myob->FileGetContents(company_uri() . 'GeneralLedger/Account', 'get', '', $token);
                $customer_list1 = json_decode($get_data['response'], true);
                //  dd($customer_list1);

                foreach ($db_subcompany as $customer_comp) {
                    foreach ($customer_list1['Items'] as $slist) {


                        if (isset($slist['DisplayID']) && isset($customer_comp->display_id) && $customer_comp->display_id == $slist['DisplayID']) {
                            //   dump('updation code');
                            DB::table('sub_companies')->where('id', $customer_comp->id)->update([
                                'ac_uid' => $slist['UID'],
                                'account_id' => $slist['DisplayID'],
                                'coa_status' => 1

                            ]);
                            $cron_job->status = 2;
                            //   dump('update');
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
            DB::table('cron_jobs')->where('type' , 'suppliers')->update([
                'status' => 1
            ]);

        }
    }
}
