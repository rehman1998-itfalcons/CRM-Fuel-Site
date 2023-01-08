<?php

namespace App\Exports;

use App\Record;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class paidexport implements FromCollection, WithHeadings
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

                $data = Record::join('users', 'records.user_id', '=', 'users.id')
                ->join('sub_companies', 'records.sub_company_id', '=', 'sub_companies.id')
                ->join('companies', 'records.company_id', '=', 'companies.id')
                ->select([
                    'records.*',
                    DB::raw('users.username as Operator'),
                    DB::raw('sub_companies.name as subCompany_name'),
                    DB::raw('companies.name as company_name')
                ])
                ->where('records.status', 1)->where('records.deleted_status', 0)->where('records.paid_status',1)
                ->where(function($q){
                    $q->where('records.invoice_number', 'LIKE',  '%'.$this->search.'%')
                    ->orwhereRaw("users.username like ?", ["%{$this->search}%"])
                    ->orwhereRaw("companies.name like ?", ["%{$this->search}%"])
                    ->orwhereRaw("sub_companies.name like ?", ["%{$this->search}%"])
                    ->orWhere('records.load_number', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('records.order_number', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('records.total_amount', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('records.datetime', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('records.email_status', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('records.paid_status', 'LIKE', '%'.$this->search.'%');
                })->get();

            }else{


                $data = Record::join('users', 'records.user_id', '=', 'users.id')
                ->join('sub_companies', 'records.sub_company_id', '=', 'sub_companies.id')
                ->join('companies', 'records.company_id', '=', 'companies.id')
                ->select([
                    'records.*',
                    DB::raw('users.username as Operator'),
                    DB::raw('sub_companies.name as subCompany_name'),
                    DB::raw('companies.name as company_name')
                ])
                ->where('records.status', 1)->where('records.deleted_status', 0)->where('records.paid_status',1)->get();
            }
        }

         else {
            //  dd($this->search);
            // \DB::enableQueryLog(); // Enable query log


            $data = Record::join('users', 'records.user_id', '=', 'users.id')
                ->join('sub_companies', 'records.sub_company_id', '=', 'sub_companies.id')
                ->join('companies', 'records.company_id', '=', 'companies.id')
                ->select([
                    'records.*',
                    DB::raw('users.username as users'),
                    DB::raw('sub_companies.name as sub_companies'),
                    DB::raw('companies.name as companies')
                ])
               ->where('records.status', 1 && 'records.deleted_status', 0)->where('records.paid_status',1)
                ->whereBetween('datetime', [$this->start_date, $this->end_date])
                ->where(function($q){
                    $q->where('records.invoice_number', 'LIKE',  '%'.$this->search.'%')
                    ->orwhereRaw("users.username like ?", ["%{$this->search}%"])
                    ->orWhere('records.datetime', 'LIKE', '%'.$this->search.'%')
                    ->orwhereRaw("companies.name like ?", ["%{$this->search}%"])
                    ->orwhereRaw("sub_companies.name like ?", ["%{$this->search}%"])
                    ->orWhere('records.load_number', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('records.order_number', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('records.total_amount', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('records.email_status', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('records.paid_status', 'LIKE', '%'.$this->search.'%');
                })->get();
                //  dd(\DB::getQueryLog()); // Show results of log

        }

        //  dd($data);
        $output = collect([]);
        foreach ($data as  $ddata) {
            $output[] = [
                'Invoice' => $ddata->invoice_number,
                'Operator' => $ddata->user->name,
                'DateTime' => $ddata->datetime,
                'Sub-Company' => $ddata->subCompany->name,
                'Trip#' => $ddata->load_number,
                'Order#' => $ddata->order_number,
                'Company' => $ddata->company->name,
                'Outstanding Balance' => $ddata->total_amount,
                'Email' => $ddata->email_status==1?'Sent':'Not Sent',
                'Status' => $ddata->paid_status==1?'Paid':'Unpaid'
            ];
        }
        //  dd($output);
        return $output;
    }

    public function headings(): array
    {
        return [
            'Invoice',
            'Operator',
            'DateTime',
            'Company',
            'Sub-Company',
            'Trip#',
            'Order#',
            'Outstanding Balance',
            'Email',
            'Status'

        ];




    }
}
