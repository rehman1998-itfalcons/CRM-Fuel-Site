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

class SyncSupplierCOA extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:Sync-supplier-COA';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Supplier chart of accounts';

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
        $cron_job =  DB::table('cron_jobs')->where('type','suppliers-coa')->first();
        if ($cron_job->type == 'suppliers-coa' && $cron_job->status == 1)
        {
            $db_suppliercompany = SupplierCompany::where('coa_status', 0)->get();
            if (coupon_status() == false) {
                return redirect('/myob');
            }
            $myob1 = DB::table('myob')->first();
            if ($myob1->status == 1) {
                $myob = new MyobCurl;
                $token = myobtokens::find(1)->access_token;
                $get_data =     $myob->FileGetContents(company_uri() . 'GeneralLedger/Account', 'get', '', $token);
                $contact_list = json_decode($get_data['response'], true);
                $not_sync = [];
                $sync = [];
                foreach ($db_suppliercompany as $supplier_comp) {
                    $not_sync[$supplier_comp->id] = $supplier_comp;
                    foreach ($contact_list['Items'] as $slist) {


                        if (isset($slist['DisplayID']) && isset($supplier_comp->display_id) && $supplier_comp->display_id == $slist['DisplayID']) {
                            $sync[$supplier_comp->id] = $supplier_comp;
                        }
                    }
                }

                foreach ($sync as $key => $all_key) {
                    unset($not_sync[$key]);
                }
                foreach ($not_sync as $scompany_notsync) {
                    $display = $scompany_notsync->display_id;
                    $post_json_data = [
                        "Name" => $scompany_notsync->name,
                        "DisplayID" => $scompany_notsync->display_id,
                        "Classification" => "Liability",
                        "Type" => "OtherLiability",
                        "Number" => substr($display, 2, 4),
                        "Description" => $scompany_notsync->name,
                        "IsActive" => true,
                        "TaxCode" => [


                            // LIVE N-T code
                             "UID" => "f9338d31-b33f-425a-bbf7-da4bd8cd53e1",
                            //demo N-T code
                            //  "UID" => "488e7b93-6b28-4eb2-80f0-72a6f3ab3428",
                        ],
                        "ParentAccount" => [
                            //live liabilities account
                              "UID" => "e6d0fbfc-c7e9-4019-9684-7239019df145",

                            // demo libilities account
                            //  "UID" =>  " ae5ed194-e01c-473f-ae84-5c85fcae4236",
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
                $contact_list1 = json_decode($get_data['response'], true);
                // dd($supplier_list1);
                foreach ($db_suppliercompany as $supplier_comp) {
                    foreach ($contact_list1['Items'] as $slist) {

                        if (isset($slist['DisplayID']) && isset($supplier_comp->display_id) && $supplier_comp->display_id == $slist['DisplayID']) {
                            // dump('updation code');
                            DB::table('supplier_companies')->where('id', $supplier_comp->id)->update([
                                'ac_uid' => $slist['UID'],
                                'account_id' => $slist['DisplayID'],
                                'coa_status' => 1
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
            DB::table('cron_jobs')->where('type' , 'sync_sale_cache')->update([
                'status' => 1
            ]);

        }
    }
}
