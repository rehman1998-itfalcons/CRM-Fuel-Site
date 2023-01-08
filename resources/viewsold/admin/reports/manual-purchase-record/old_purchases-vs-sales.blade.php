@extends('layouts.template')

@section('title','Manual Purchase Vs Sales')

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
<div class="row layout-top-spacing" id="cancel-row">
    <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
        <div class="widget-content widget-content-area br-6">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4>Manual Purchase Vs Sales Report</h4>
                        <div class="table-responsive mb-4 mt-4">
                            <table id="html5-extension" class="table table-hover non-hover" style="width:100%">
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
                                    @foreach ($manualmatches as $manualmatch)
                                    @php
                                    $obj =
                                    DB::table('records')->join('record_products','record_products.record_id','records.id')->where('records.id',$manualmatch['sales'])->where('record_id',$manualmatch['sales'])->first();
                                    $purchase_obj =
                                    DB::table('purchase_records')->join('purchase_record_products','purchase_record_products.purchase_record_id','purchase_records.id')->where('purchase_records.id',$manualmatch['purchases'])->where('purchase_record_id',$manualmatch['purchases'])->first();
                                    @endphp
                                    <tr>
                                        <td>{{ $sno++ }}</td>
                                        <td>{{ $purchase_obj->invoice_number }}</td>
                                        <td>{{ $obj->invoice_number }}</td>
                                        <td>{{ \Carbon\Carbon::parse($purchase_obj->datetime)->format('d-m-Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($obj->datetime)->format('d-m-Y') }}</td>
                                        <?php $sum_qty = 0 ?>
                                        <?php $sum_unit_Price = 0 ?>
                                        <?php $sale_total = 0;
                                         $sale_qty = 0;
                                         $sale_unit_Price = 0;
                                            ?>
                                        <?php $total = 0 ?>
                                        @php
                                        // $i=0;
                                        @endphp
                                        @foreach ($products as $product)
                                        @php
                                        $sale_objs =
                                        DB::table('records')->join('record_products','record_products.record_id','records.id')->where('invoice_number',$obj->invoice_number)->where('record_products.product_id',$product->id)->first();
                                        $purchase_objs =
                                        DB::table('purchase_records')->join('purchase_record_products','purchase_record_products.purchase_record_id','purchase_records.id')->where('invoice_number',$purchase_obj->invoice_number)->where('product_id',$product->id)->first();
                                        @endphp
                                        <td>
                                            {{ ($purchase_objs) ? $purchase_objs->qty : 0 }}L
                                            @php

                                            $sum_qty = ($purchase_objs) ? $purchase_objs->qty : 0;
                                            $sum_unit_Price = ($purchase_objs) ? $purchase_objs->rate : 0;
                                            $total += $sum_qty * $sum_unit_Price;

                                            $whole_sale = ($sale_objs) ? $sale_objs->whole_sale : 0;
                                            $delivery_rate = ($sale_objs) ? $sale_objs->delivery_rate : 0;
                                            $brand_charges = ($sale_objs) ? $sale_objs->brand_charges : 0;
                                            $cost_of_credit = ($sale_objs) ? $sale_objs->cost_of_credit : 0;
                                            $discount = ($sale_objs) ? $sale_objs->discount : 0;

                                            // $sale_qty = ($sale_objs) ? $sale_objs->qty : 0;
                                            $sale_unit_Price = $whole_sale + $delivery_rate + $brand_charges +
                                            $cost_of_credit - $discount;
                                            $sale_total += $sum_qty * $sale_unit_Price;
                                            @endphp

                                        </td>
                                        @endforeach
                                        @php
                                        $sale_ob =
                                        DB::table('records')->where('invoice_number',$obj->invoice_number)->first();
                                        $purchase_ob =
                                        DB::table('purchase_records')->where('invoice_number',$purchase_obj->invoice_number)->first();
                                        if($purchase_ob->gst_status == 'Tax Exclusive'){
                                        $gst = $total *10 /100;
                                        $total = $total+ $gst;

                                        }else{
                                        // $gst = gst * $total / (100 + 10)
                                        $gst = $total *10 /(100 + 10);
                                        $total = $total;

                                        }
                                        if($sale_ob->gst_status == 'Tax Exclusive'){
                                        $sale_gst = $sale_total *10 /100;
                                        $sale_totals = $sale_total+ $sale_gst;

                                        }else{
                                        // $gst = gst * $total / (100 + 10)
                                        $sale_gst = $sale_total *10 /(100 + 10);
                                        $sale_totals = $sale_total;

                                        }
                                        @endphp
                                        <td>${{ number_format($total,2) }}</td>
                                        <td>${{ number_format($sale_totals,2) }}</td>
                                        <td>${{ number_format($gst,2) }}</td>
                                        <td>${{ number_format($sale_gst,2) }}</td>
                                        <td>
                                            <a href="{{ route('manual.purchases.vs.sales.details',$manualmatch['purchases']) }}"
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

            "order":[[1, 'desc']],//asc or desc

            "lengthMenu": [10, 20, 30, 50],

            "pageLength": 10

        });



</script>



@endsection
