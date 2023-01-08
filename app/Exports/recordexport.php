<?php

namespace App\Exports;

use App\Record;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class recordexport implements FromCollection, WithHeadings
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
            $data = Record::join('categories', 'records.category_id', '=', 'categories.id')
                ->join('sub_companies', 'records.sub_company_id', '=', 'sub_companies.id')
                ->select([
                    'records.*',
                    DB::raw('categories.name as category_name'),
                    DB::raw('sub_companies.name as subCompany_name')
                ])
                ->where('records.status', 1 && 'records.deleted_status', 0)->where(function($q){
                    $q->where('records.id', 'LIKE',  '%'.$this->search.'%')
                    ->orWhere('records.invoice_number', 'LIKE',  '%'.$this->search.'%')
                    ->orWhere('records.load_number', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('records.datetime', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('records.order_number', 'LIKE', '%'.$this->search.'%')
                    ->orwhereRaw("categories.name like ?", ["%{$this->search}%"])
                    ->orwhereRaw("sub_companies.name like ?", ["%{$this->search}%"])
                    ->orWhere('records.gst_status', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('records.total_amount', 'LIKE', '%'.$this->search.'%');
                })->get();

            }else{
            $data = Record::join('categories', 'records.category_id', '=', 'categories.id')
            ->join('sub_companies', 'records.sub_company_id', '=', 'sub_companies.id')
            ->select([
                'records.*',
                DB::raw('categories.name as category_name'),
                DB::raw('sub_companies.name as subCompany_name')
            ])
            ->where('records.status', 1 && 'records.deleted_status', 0)->get();
            }
        }

         else {
            //  dd($this->search);
            // \DB::enableQueryLog(); // Enable query log

            $data = Record::join('categories', 'records.category_id', '=', 'categories.id')
                ->join('sub_companies', 'records.sub_company_id', '=', 'sub_companies.id')
                ->select([
                    'records.*',
                    DB::raw('categories.name as category_name'),
                    DB::raw('sub_companies.name as subCompany_name')
                ])
                ->where('records.status', 1 && 'records.deleted_status', 0)
                ->whereBetween('datetime', [$this->start_date, $this->end_date])
                ->where(function($q){
                    $q->where('records.id', 'LIKE',  '%'.$this->search.'%')
                    ->orWhere('records.invoice_number', 'LIKE','%'.$this->search.'%')
                    ->orWhere('records.load_number', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('records.datetime', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('records.order_number', 'LIKE', '%'.$this->search.'%')
                    ->orwhereRaw("categories.name like ?", ["%{$this->search}%"])
                    ->orwhereRaw("sub_companies.name like ?", ["%{$this->search}%"])
                    ->orWhere('records.gst_status', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('records.total_amount', 'LIKE', '%'.$this->search.'%');
                })->get();
                // dd(\DB::getQueryLog()); // Show results of log

        }

        $output = collect([]);
        foreach ($data as  $ddata) {
            $output[] = [
                'S_No' => $ddata->id,
                'Invoice_no' => $ddata->invoice_number,
                'Date_Time' => $ddata->datetime,
                'Trip_Number' => $ddata->load_number,
                'Order_Number' => $ddata->order_number,
                'Category' => $ddata->Category->name,
                'Sub_Company' => $ddata->subCompany->name,
                'GST_Status' => $ddata->gst_status,
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
            'Trip Number',
            'Order Number',
            'Category',
            'Sub Company',
            'GST Status',
            'Total Amount'
        ];
    }
}
