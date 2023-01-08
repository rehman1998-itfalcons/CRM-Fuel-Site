@extends('layouts.layout.main')
@section('title','Purchase Vs Sales')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('plugins/table/table/datatable/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('plugins/table/table/datatable/custom_dt_html5.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('plugins/table/table/datatable/dt-global_style.css') }}">
<style>
    table.dataTable thead .sorting_asc:before {
        display: none;
    }

    .loader {
        position: sticky !important;
        text-align: center !important;
        left: 50% !important;
        top: 50% !important;
    }

    thead>tr>th.sorting {
        white-space: nowrap !important;
    }

    .table>thead>tr>th {
        font-weight: 700 !important;
    }
</style>
@endsection
@section('contents')
<div class="container-fluid p-10">
    <div class="row justify-content-center">
        <div class="white_box mb_20">
            <div class="col-sm-12 ">
                <div class="QA_section">
                    <div class="white_box_tittle list_header">
                        <h4>Purchase Vs Sales Report</h4>
                        {{--  <div class="box_right d-flex lms_block">
                            <div class="serach_field_2">
                                <div class="search_inner">
                                    <form Active="#">
                                        <div class="search_field">
                                            <input type="text" placeholder="Search content here...">
                                        </div>
                                        <button type="submit"> <i class="ti-search"></i> </button>
                                    </form>
                                </div>
                            </div>
                            <div class="add_button ms-2">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#addcategory" class="btn_1">Add
                                    New</a>
                            </div>
                        </div>  --}}
                    </div>
                    <div class="QA_table mb_30">
                        <div class="table-responsive mb-4 mt-4">
                            <table id="html5-extension" class="table table-hover non-hover" >
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Purchase Invoice #</th>
                                        <th>Sale Invoice #</th>
                                        <th>Purchase Date</th>
                                        <th>Sale Date</th>
                                        @foreach ($products as $product)
                                        <th>{{ $product->name }}</th>
                                        @endforeach
                                        <th>Purchase Amount</th>
                                        <th>Sale Amount</th>
                                        <th>Purchase Tax</th>
                                        <th>Sale Tax</th>
                                        <th>Details</th>
                                    </tr>
                                </thead>
                                <?php $sno = 1; ?>
                                <tbody>
                                    @foreach ($matches as $match)
                                    <tr>
                                        <td>{{ $sno++ }}</td>
                                        <td>{{ $match->purchaseRecord->invoice_number }}</td>
                                        <td>{{ $match->record->invoice_number }}</td>
                                        <td>{{ \Carbon\Carbon::parse($match->purchaseRecord->datetime)->format('d-m-Y')
                                            }}</td>
                                        <td>{{ \Carbon\Carbon::parse($match->record->datetime)->format('d-m-Y') }}</td>
                                        <?php $sum_tot_Price = 0 ?>
                                        <?php $sum_unit_Price = 0 ?>
                                        <?php $total = 0 ?>
                                        @foreach ($products as $product)
                                        @php
                                        $obj =
                                        DB::table('records')->join('record_products','record_products.record_id','records.id')->where('invoice_number',$match->record->invoice_number)->where('product_id',$product->id)->first();
                                        $purchase_obj =
                                        DB::table('purchase_records')->join('purchase_record_products','purchase_record_products.purchase_record_id','purchase_records.id')->where('invoice_number',$match->purchaseRecord->invoice_number)->where('product_id',$product->id)->first();

                                        @endphp
                                        <td>
                                            {{ ($obj) ? $obj->qty : 0 }}L
                                            @php
                                            $sum_tot_Price = ($obj) ? $obj->qty : 0;
                                            $sum_unit_Price = ($purchase_obj) ? $purchase_obj->rate : 0;
                                            $total += $sum_tot_Price * $sum_unit_Price;

                                            @endphp

                                        </td>


                                        @endforeach
                                        @php
                                        $purchase_obj =
                                        DB::table('purchase_records')->where('invoice_number',$match->purchaseRecord->invoice_number)->first();
                                        if($purchase_obj->gst_status == 'Tax Exclusive'){
                                        $gst = $total *10 /100;
                                        $total = $total+ $gst;
                                        }else{
                                        // $gst = gst * $total / (100 + 10)
                                        $gst = $total *10 /(100 + 10);
                                        $total = $total;

                                        }
                                        @endphp
                                        <td>${{ number_format($total,2) }}</td>
                                        <td>${{ number_format($match->record->total_amount,2) }}</td>
                                        <td>${{ number_format($gst,2) }}</td>
                                        <td>${{ number_format($match->record->gst,) }}</td>
                                        <td>
                                            <a href="{{ route('purchases.vs.sales.details',$match->purchase_record_id) }}"
                                                style="padding: 6px 15px;" class="btn btn-sm btn-primary">View</a>
                                        </td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="{{ URL::asset('plugins/table/datatable/datatables.js') }}"></script>
<script src="{{ URL::asset('plugins/table/datatable/button-ext/dataTables.buttons.min.js') }}"></script>
<script src="{{ URL::asset('plugins/table/datatable/button-ext/jszip.min.js') }}"></script>
<script src="{{ URL::asset('plugins/table/datatable/button-ext/buttons.html5.min.js') }}"></script>
<script src="{{ URL::asset('plugins/table/datatable/button-ext/buttons.print.min.js') }}"></script>
<script>
    $('#html5-extension').DataTable({
          "order": [0, "desc"],
            responsive: true,
            dom:
            '<"row"<"col-md-12"<"row"  <"col-md-4"l>  <"col-md-4 text-center"B>  <"col-md-4"f>>>' +
            '<"col-md-12"tr>' +
            '<"col-md-12 mt-2"<"row mt-3"  <"col-md-5"i>  <"col-md-7 text-right"p>>> >',
            buttons: {
                buttons: [{
                    extend: 'csv',
                    className: 'btn'
                },{
                    extend: 'excel',
                    className: 'btn'
                }]
        },
            "oLanguage": {
                "oPaginate": {
                    "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                    "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'
                },
                "sInfo": "Showing page _PAGE_ of _PAGES_",
                "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                "sSearchPlaceholder": "Search...",
                "sLengthMenu": "Results :  _MENU_",
            },
            "stripeClasses": [],
            "order":[[1, 'desc']],
            "lengthMenu": [10, 20, 30, 50],
            "pageLength": 10
        });

</script>
@endsection
