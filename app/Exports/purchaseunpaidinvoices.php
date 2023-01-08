<?php

namespace App\Exports;

use App\PurchaseRecord;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class purchaseunpaidinvoices implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $search;
    protected $start_date;
    protected $end_date;


    public function __construct(string $search = null,string $start_date = null, string $end_date = null)
    {
        $this->search = $search;
        $this->start_date = $start_date;
        $this->end_date = $end_date;

        return $this;
    }


    public function collection()
    {


        if ( $this->start_date == null || $this->end_date == null ) {
            if ($this->search != null) {
                //  dd($this->search);
            $data = PurchaseRecord::join('categories', 'purchase_records.category_id', '=', 'categories.id')
                ->join('supplier_companies', 'purchase_records.supplier_company_id', '=', 'supplier_companies.id')
                ->select([
                    'purchase_records.*',
                    DB::raw('categories.name as category_name'),
                    DB::raw('supplier_companies.name as fule_Company_name')
                ]) ->where('purchase_records.status', 1)->where('purchase_records.deleted_status', 0)->where('purchase_records.paid_status',0)->where(function($q){
                    $q->where('purchase_records.id', 'LIKE',  '%'.$this->search.'%')
                    ->orWhere('purchase_records.invoice_number', 'LIKE',  '%'.$this->search.'%')
                    ->orWhere('purchase_records.load_number', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('purchase_records.datetime', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('purchase_records.order_number', 'LIKE', '%'.$this->search.'%')
                    ->orwhereRaw("categories.name like ?", ["%{$this->search}%"])
                    ->orwhereRaw("fuelCompany.name like ?", ["%{$this->search}%"])
                    ->orWhere('purchase_records.paid_status', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('purchase_records.total_quantity', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('purchase_records.total_amount', 'LIKE', '%'.$this->search.'%');
                })->get();

            }else{
                $data = PurchaseRecord::join('categories', 'purchase_records.category_id', '=', 'categories.id')
                ->join('supplier_companies', 'purchase_records.supplier_company_id', '=', 'supplier_companies.id')
                ->select([
                    'purchase_records.*',
                    DB::raw('categories.name as category_name'),
                    DB::raw('supplier_companies.name as fule_Company_name')
                ]) ->where('purchase_records.status', 1)->where('purchase_records.deleted_status', 0)->where('purchase_records.paid_status',0)
            ->get();
            }
        }

         else {
            //  dd($this->search);
            // \DB::enableQueryLog(); // Enable query log

            $data = PurchaseRecord::join('categories', 'purchase_records.category_id', '=', 'categories.id')
                ->join('supplier_companies', 'purchase_records.supplier_company_id', '=', 'supplier_companies.id')
                ->select([
                    'purchase_records.*',
                    DB::raw('categories.name as category_name'),
                    DB::raw('supplier_companies.name as fule_Company_name')
                ]) ->where('purchase_records.status', 1)->where('purchase_records.deleted_status', 0)->where('purchase_records.paid_status',0)
                ->whereBetween('datetime', [$this->start_date, $this->end_date])
                ->where(function($q){
                    $q->where('purchase_records.id', 'LIKE',  '%'.$this->search.'%')
                    ->orWhere('purchase_records.invoice_number', 'LIKE','%'.$this->search.'%')
                    ->orWhere('purchase_records.load_number', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('purchase_records.datetime', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('purchase_records.order_number', 'LIKE', '%'.$this->search.'%')
                    ->orwhereRaw("categories.name like ?", ["%{$this->search}%"])
                    ->orwhereRaw("fuelCompany.name like ?", ["%{$this->search}%"])
                    ->orWhere('purchase_records.paid_status', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('purchase_records.total_quantity', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('purchase_records.total_amount', 'LIKE', '%'.$this->search.'%');
                })->get();
                // dd(\DB::getQueryLog()); // Show results of log

        }

        $output = collect([]);
        foreach ($data as  $ddata) {
            $output[] = [
                'S_No' => $ddata->id,
                'Invoice_no' => $ddata->invoice_number,
                'Date_Time' => $ddata->datetime,
                'Category' => $ddata->Category->name,
                'fuel_Company' => $ddata->fuelCompany->name,
                'status' => $ddata->paid_status==1?'Paid':'Unpaid',
                'total_quantity' => $ddata->total_quantity,
                'Total_Amount' => $ddata->total_amount
            ];
        }
        //dd($output);
        return $output;
    }

    public function headings(): array
    {
        return [
            'S.No',
            'Invoice no',
            'Date/Time',
            'Category',

            'fuel Company',
            'Status',
           'total_quantity',
            'Total Amount'
        ];
    }
}
