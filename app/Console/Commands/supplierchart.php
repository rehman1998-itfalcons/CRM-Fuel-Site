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

class supplierchart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:supplierchart';

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
        $cron_job =  DB::table('dashboard_cache')->where('value', 'supplierchart')->first();
        if ($cron_job->value == 'supplierchart' && $cron_job->status == 1) {
            $companies = [];
            $purchases_companies = [];

            $set_category = isset($_GET['category']) ? $_GET['category'] : 0;
            $set_category = isset($_GET['category']) ? $_GET['category'] : 0;
            $products = \App\Product::select('id', 'name')->where('status', 1)->get();
            if ($set_category != 0 && $set_category != '') {
                $records = Record::select('id', 'supplier_company_id')->where('category_id', $_GET['category'])->where('status', 1)->where('deleted_status', 0)->get()->groupBy('supplier_company_id');
                $purchases = PurchaseRecord::select('id', 'supplier_company_id')->where('category_id', $_GET['category'])->where('status', 1)->where('deleted_status', 0)->get()->groupBy('supplier_company_id');
            } else {
                $records = Record::select('id', 'supplier_company_id')->where('status', 1)->where('deleted_status', 0)->get()->groupBy('supplier_company_id');
                $purchases = PurchaseRecord::select('id', 'supplier_company_id')->where('status', 1)->where('deleted_status', 0)->get()->groupBy('supplier_company_id');
            }

            $array = [];
            $per_total = 0;

            foreach ($records as $supplier_record) {

                $company = $supplier_record->first();
                $total_liters = 0;

                foreach ($products as $product) {
                    ${"prod_$product->id"} = 0;
                }
                foreach ($supplier_record as $rec) {
                    foreach ($products as $product) {

                        $data = $rec->products->where('product_id', $product->id)->first();
                        $qty = ($data) ? $data->qty : 0;
                        ${"prod_$product->id"} = ${"prod_$product->id"} + $qty;
                        $total_liters = $total_liters + ${"prod_$product->id"};
                    }
                }

                $companies[$company->supplierCompany->name] = $total_liters;
            }
            foreach ($purchases as $supplier_record) {

                $company = $supplier_record->first();
                $total_liters = 0;

                foreach ($products as $product) {
                    ${"prod_$product->id"} = 0;
                }
                foreach ($supplier_record as $rec) {
                    foreach ($products as $product) {

                        $data = $rec->products->where('product_id', $product->id)->first();
                        $qty = ($data) ? $data->qty : 0;
                        ${"prod_$product->id"} = ${"prod_$product->id"} + $qty;
                        $total_liters = $total_liters + ${"prod_$product->id"};
                    }
                }

                $purchases_companies[$company->fuelCompany->name] = $total_liters;
            }



            foreach ($companies as $company => $liter) {
                $supplier_sale[] = $liter;
            }



            foreach ($purchases_companies as $company => $liter) {
                $supplier_purchase[] = $liter;
            }





            foreach ($companies as $company => $liter) {
                $supplier_category[] = $company;
            }
            $minutes = 11;
            $output = \Cache::remember('supplierchart', $minutes, function () use ($supplier_sale, $supplier_purchase, $supplier_category) {

                return [
                    'supplier_sale' => $supplier_sale,
                'supplier_purchase' => $supplier_purchase,
                'supplier_category' => $supplier_category


                ];
            });

            $cron_job->total_run = $cron_job->total_run + 1;
            DB::table('dashboard_cache')->where('value', $cron_job->value)->update([
                'status' => 0,
                'total_run' => $cron_job->total_run
            ]);
            DB::table('dashboard_cache')->where('value', 'sidechart')->update([
                'status' => 1
            ]);
           


        }

    }
}
