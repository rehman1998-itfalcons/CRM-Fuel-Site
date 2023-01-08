<?php

namespace App\Exports;

use App\PurchaseRecord;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class purchaseexport implements FromCollection, WithHeadings
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
                    DB::raw('supplier_companies.name as fuelCompany_name')
                ])
                ->where(function($q){
                    $q->where('purchase_records.id', 'LIKE',  '%'.$this->search.'%')
                    ->orWhere('purchase_records.invoice_number', 'LIKE',  '%'.$this->search.'%')
                    // ->orWhere('records.load_number', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('purchase_records.datetime', 'LIKE', '%'.$this->search.'%')
                     ->orWhere('purchase_records.paid_status', 'LIKE', '%'.$this->search.'%')
                    ->orwhereRaw("categories.name like ?", ["%{$this->search}%"])
                    ->orwhereRaw("supplier_companies.name like ?", ["%{$this->search}%"])
                    ->orWhere('purchase_records.total_quantity', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('purchase_records.total_amount', 'LIKE', '%'.$this->search.'%');
                })->get();

            }else{
                $data = PurchaseRecord::join('categories', 'purchase_records.category_id', '=', 'categories.id')
                ->join('supplier_companies', 'purchase_records.supplier_company_id', '=', 'supplier_companies.id')
                ->select([
                    'purchase_records.*',
                    DB::raw('categories.name as category_name'),
                    DB::raw('supplier_companies.name as fuelCompany_name')
                ])->get();
            }
        }

         else {
            $data = PurchaseRecord::join('categories', 'purchase_records.category_id', '=', 'categories.id')
            ->join('supplier_companies', 'purchase_records.supplier_company_id', '=', 'supplier_companies.id')
            ->select([
                'purchase_records.*',
                DB::raw('categories.name as category_name'),
                DB::raw('supplier_companies.name as fuelCompany_name')
            ])
            ->whereBetween('datetime', [$this->start_date, $this->end_date])
            ->where(function($q){
                $q->where('purchase_records.id', 'LIKE',  '%'.$this->search.'%')
                ->orWhere('purchase_records.invoice_number', 'LIKE',  '%'.$this->search.'%')
                ->orWhere('purchase_records.datetime', 'LIKE', '%'.$this->search.'%')
                 ->orWhere('purchase_records.paid_status', 'LIKE', '%'.$this->search.'%')
                ->orwhereRaw("categories.name like ?", ["%{$this->search}%"])
                ->orwhereRaw("supplier_companies.name like ?", ["%{$this->search}%"])
                ->orWhere('purchase_records.total_quantity', 'LIKE', '%'.$this->search.'%')
                ->orWhere('purchase_records.total_amount', 'LIKE', '%'.$this->search.'%');
            })->get();

        }

        //dd($data);

        $output = collect([]);
        foreach ($data as  $ddata) {
            $output[] = [
                'S_No' => $ddata->id,
                'Invoice_no' => $ddata->invoice_number,
                'Date/Time' => $ddata->datetime,
                'Category' => $ddata->Category->name,
                'Fuel_Company' => $ddata->fuelCompany->name,
                'Status' => $ddata->paid_status==1?'Paid':'Unpaid',
                'total_quantity' => $ddata->total_quantity,
                'Total_Amount' => $ddata->total_amount
            ];
        }
        // dd($output);
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
