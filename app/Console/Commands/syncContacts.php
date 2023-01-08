<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use  App\Libraries\MyobCurl;
use Illuminate\Http\Request;
use App\SupplierCompany;
use App\SubCompany;
use Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Company;
use App\myobtokens;
use App\Category;

class syncContacts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sync-contacts-to-myob';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync contacts with myob API';

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

        $cron_job =  DB::table('cron_jobs')->where('type','customers')->first();
        if ($cron_job->type == 'customers' && $cron_job->status == 1)
        {


            $db_subcompany = SubCompany::where('myob_status', 0)->limit(5)->get();

            if (coupon_status() == false) {
                return redirect('/myob');
            }
            $myob1 = DB::table('myob')->first();
            if ($myob1->status == 1) {
                $myob = new MyobCurl;
                $token = myobtokens::find(1)->access_token;

                $get_data =     $myob->FileGetContents(company_uri() . 'Contact/Customer', 'get', '', $token);
                $customer_list = json_decode($get_data['response'], true);

                $not_sync = [];
                $sync = [];
                foreach ($db_subcompany as $customer_comp) {
                    $not_sync[$customer_comp->id] = $customer_comp;

                    foreach ($customer_list['Items'] as $slist) {

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
                    $post_json_data_to_customer = [
                        "LastName" => "",
                        "FirstName" => $subcompany_notsync->name,
                        "CompanyName" => $subcompany_notsync->name,
                        "IsIndividual" => false,
                        "DisplayID" => $subcompany_notsync->display_id,
                        "SellingDetails" => [
                            "SaleLayout" => "NoDefault",
                            "PrintedForm" => "Pre-Printed Invoice",
                            "InvoiceDelivery" => "Print",
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



                    $post_data_to_customer =     $myob->FileGetContents(
                        company_uri() . 'Contact/Customer',
                        'post',
                        json_encode($post_json_data_to_customer),
                        $token

                    );
                    // dd($post_data_to_customer);
                    //   dump($post_data_to_customer);
                }

                $get_data =  $myob->FileGetContents(company_uri() . 'Contact/Customer', 'get', '', $token);
                $customer_list1 = json_decode($get_data['response'], true);
                //    dump($customer_list1);

                foreach ($db_subcompany as $customer_comp) {
                    foreach ($customer_list1['Items'] as $slist) {


                        if (isset($slist['DisplayID']) && isset($customer_comp->display_id) && $customer_comp->display_id == $slist['DisplayID']) {
                            //   dump('updation code');
                            DB::table('sub_companies')->where('id', $customer_comp->id)->update([
                                'co_uid' => $slist['UID'],
                                'myob_status' => 1

                            ]);
                            $cron_job->status = 2;
                            //  dump('update');
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
            DB::table('cron_jobs')->where('type' , 'customer_coa')->update([
                'status' => 1
            ]);

        }
    }
}
