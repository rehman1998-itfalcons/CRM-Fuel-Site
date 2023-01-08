<?php

namespace App\DataTables;

use App\Record;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Builder;

class RecordDataTable extends DataTable
{

   // protected $actions = ['export', 'print', 'reset', 'reload', 'myCustomAction'];
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {

        // $data = Record::where('status',1)->where('deleted_status',0);


        // return datatables()
        //     ->eloquent($data)
        //         ->addColumn('S.No', '{{$id}}')
        //         ->addColumn('Invoice no', '{{$invoice_number}}')
        //         ->addColumn('Date/Time', '{{$datetime}}')
        //         ->addColumn('Trip Number', '{{$load_number}}')
        //         ->addColumn('Order Number', '{{$order_number}}')

        // ->addColumn('category_name', function (Record $record) {
        //     return $record->category->name;
        // })
        // ->addColumn('subCompany_name', function (Record $record) {
        //     return $record->subCompany->name;
        // })
        // ->addColumn('GST Status', '{{$gst_status}}')
        // ->addColumn('Total Amount','${{$total_amount}}',2)
        // ->addIndexColumn()
        //         ->addColumn('action', function($data){
        //             $button = '<button type="button" name="edit" id="'.$data->id.'" class="status_btn">Edit</button>';
        //             $button .= '&nbsp;&nbsp;&nbsp;<button type="button" name="edit" id="'.$data->id.'" class="status_btn">View</button>';
        //             return $button;
        //         })
        //         ->rawColumns(['action'])
        //         ->make(true)
        //         ->toJson();
         $model = Record::query();

        return datatables()
            ->eloquent($query)
            ->addColumn('S.No', '{{$id}}')
            ->addColumn('Invoice no', '{{$invoice_number}}')
            ->addColumn('Date/Time', '{{$datetime}}')
            ->addColumn('Trip Number', '{{$load_number}}')
            ->addColumn('Order Number', '{{$order_number}}')
            // ->addColumn('Category', '{{$id}}')
            // ->addColumn('Sub Company', '{{$id}}')
            ->addColumn('GST Status', '{{$gst_status}}')
            ->addColumn('Total Amount','${{$total_amount}}',2)
        //      ->addColumn('Details');

            ->addColumn('action', 'record.action') ->make(true);
                    //  ->toJson();
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\App\Record $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Record $model)
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $user = 10;
        return $this->builder()
        ->setTableId('Record-table')
        ->columns($this->getColumns())
        ->minifiedAjax()
        ->columns($this->getColumns())
        ->parameters([
            'dom'          => 'Bfrtip',
            'buttons'      => ['export', 'print', 'reset', 'reload'],
        ])->orderBy(1);

                    // ->setTableId('Record-table')
                    // ->columns($this->getColumns())
                    // ->minifiedAjax()
                    // ->dom('Bfrtip')
                    // ->orderBy(1)
                    // ->buttons(
                    //     Button::make('create'),
                    //     Button::make('export'),
                    //     Button::make('print'),
                    //     Button::make('reset'),
                    //     Button::make('reload')
                    // )->orderBy(1);
    }


    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {

        return
         [
            // Column::computed('action')
            //       ->exportable(false)
            //       ->printable(false)
            //       ->width(60)
            //       ->addClass('text-center'),
            Column::make('S.No' ),
            Column::make('Invoice no'),
            Column::make('Date/Time'),
            Column::make('Trip Number'),
            Column::make('Order Number'),
            // Column::make('category_name'),
            // Column::make('subCompany_name'),
            Column::make('GST Status'),
            Column::make('Total Amount'),
            Column::make('action')
        ];

        // columns: [
        //     { data: 'id', name: 'S.No' },
        //     { data: 'invoice_number', name: 'Invoice no' },
        //     { data: 'datetime', name: 'Date/Time' },
        //     { data: 'load_number', name: 'Trip Number' },
        //     { data: 'order_number', name: 'Order Number' },
        //     // { data: 'email', name: 'Category' },
        //     // { data: 'email', name: 'Sub Company' },
        //     { data: 'gst_status', name: 'GST Status' },
        //     { data: 'total_amount', name: 'Total Amount' },

        // ];

    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Record_' . date('YmdHis');
    }
}
