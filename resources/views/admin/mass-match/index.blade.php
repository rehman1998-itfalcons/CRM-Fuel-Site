@extends('layouts.layout.main')
@section('title','Mass Match')
@section('css')

<style>
    .purchase_order {
        padding: 15px;
        background-color: #8ecfd7;
        border-radius: 5px;
        cursor: pointer;
    }

    .purchase_order:hover {
        background-color: #66c6d3;
    }

    .sale-order {
        padding: 15px;
        background-color: #a8e5b5;
        border-radius: 5px;
        cursor: pointer;
    }

    .sale-order:hover {
        background-color: #85dd98;
    }

    .heading_design {
        text-align: center;
        background-color: #32C36C;
        color: white;
        padding: 6px;
        border-radius: 5px;
        padding-top: 12px;
    }

    .page-item.active .page-link {
        z-index: 3;
        color: #fff;
        background-color: #32C36C;
        border-color: #32C36C;
    }

    .table>tbody>tr>td {
        color: #000 !important;
        font-weight: 900 !important;
    }

    #content:before {
        background: none !important;
    }

    .btn-info {
        color: #fff !important;
        background-color: #32C36C;
        border-color: #32C36C;
    }
</style>
<link href="{{ URL::asset('plugins/jquery-ui/jquery-ui.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/css/apps/contacts.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('plugins/flatpickr/flatpickr.css') }}" rel="stylesheet" type="text/css">
<link href="{{ URL::asset('plugins/flatpickr/custom-flatpickr.css') }}" rel="stylesheet" type="text/css">

@endsection
@section('contents')

<div class="container-fluid p-10">
    <div class="row justify-content-center">
        <div class="white_box mb_20">
            <div class="row">
                <div class="col-xl-6 col-md-6 col-sm-12 col-12">
                    <h3>
                        List of Purchase orders to Match
                    </h3>
                </div>
                <div class="col-xl-6 col-md-6 col-sm-12 col-12">
                    <div class="text-sm-right filtered-list-search layout-spacing"
                        style="display: inline-block; width: 70%;">
                        <form class="">
                            <div class="" style="float: right; border: 1px solid #bfc9d4; border-radius: 6px;">
                                {{-- <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-search">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                </svg> --}}
                                <input type="text" class="form-control product-search" id="input-search"
                                    placeholder="Type any word">
                            </div>
                        </form>
                    </div>
                    <a href="{{ route('add.manual.mass.match') }}" style="height: 44.57px; float: right;"
                        class="btn btn-primary">Add Mass Match</a>
                </div>
            </div>
            <hr>
            <?php $companies = \DB::table('supplier_companies')->select('id','name')->get(); ?>
            <form action="{{ url('/mass-match') }}" method="GET">
                <div class="row">
                    <div class="col-md-4">
                        <label>Select Company</label>
                        <select name="company" class="form-control">
                            <option value="">-- Select --</option>
                            @foreach ($companies as $company)
                            <option value="{{ $company->id }}" @if(isset($_GET['company'])) {{
                                ($_GET['company']==$company->id) ? 'selected' : '' }} @endif>
                                {{ $company->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Date Range Type</label>
                        <select id="select-range" name="date_range" class="form-control">
                            <option value="">-- Select Date Range --</option>
                            <option value="Today" @if(isset($_GET['date_range'])) {{ ($_GET['date_range']=='Today' )
                                ? 'selected' : '' }} @endif>Today</option>
                            <option value="Yesterday" @if(isset($_GET['date_range'])) {{
                                ($_GET['date_range']=='Yesterday' ) ? 'selected' : '' }} @endif>Yesterday</option>
                            <option value="Last 7 Days" @if(isset($_GET['date_range'])) {{
                                ($_GET['date_range']=='Last 7 Days' ) ? 'selected' : '' }} @endif>Last 7 Days</option>
                            <option value="Last 30 Days" @if(isset($_GET['date_range'])) {{
                                ($_GET['date_range']=='Last 30 Days' ) ? 'selected' : '' }} @endif>Last 30 Days</option>
                            <option value="This Month" @if(isset($_GET['date_range'])) {{
                                ($_GET['date_range']=='This Month' ) ? 'selected' : '' }} @endif>This Month</option>
                            <option value="Last Month" @if(isset($_GET['date_range'])) {{
                                ($_GET['date_range']=='Last Month' ) ? 'selected' : '' }} @endif>Last Month</option>
                            <option value="Custom Range" @if(isset($_GET['date_range'])) {{
                                ($_GET['date_range']=='Custom Range' ) ? 'selected' : '' }} @endif>Custom Range</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Enter Text</label>
                        <input type="text" name="field_text" @if(isset($_GET['field_text']))
                            value="{{ $_GET['field_text'] }}" @endif class="form-control">
                    </div>
                </div>
                <div class="row mt-4" id="custom-range" @if(isset($_GET['date_range'])) style="display: flex;" @else
                    style="display: none;" @endif>
                    <div class="col-md-4">
                        <label>From Date</label>
                        <input type="text" id="from_date" name="from_date"
                            class="form-control flatpickr flatpickr-input" @if(isset($_GET['from_date']))
                            value="{{ $_GET['from_date'] }}" @endif placeholder="Select From Date">
                    </div>
                    <div class="col-md-4">
                        <label>To Date</label>
                        <input type="text" id="to_date" name="to_date" class="form-control flatpickr flatpickr-input"
                            @if(isset($_GET['to_date'])) value="{{ $_GET['to_date'] }}" @endif
                            placeholder="Select To Date">
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-12">
                        <button id="submit-btn" class="btn btn-md btn-rounded btn-primary"
                            style="height: 44.57px; width: 10%; ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-search">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg> Search
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- 2nd --}}

<div class="container-fluid p-10">
    <div class="row justify-content-center">
        <div class="white_box mb_20">
            <div class="row text-center"
                style="background-color: #008e20; padding: 6px; border-radius: 5px; margin: 0px;">
                <div class="col-md-6">
                    <h4 style="color:white;">Purchase Orders</h4>
                </div>
                <div class="col-md-6">
                    <h4 style="color:white;">
                        Invoices
                    </h4>
                </div>
            </div>
            <div class="searchable-items grid" id="live_stream" style="display: unset;">
                @forelse ($purchases as $purchase)
                <div class="row justify-content-center mt-3 mb-3 items" id="purchase_order_{{ $purchase->id }}">
                    <div class="col-md-5">
                        <br>
                        <div class="purchase_order">
                            <div class="row mb-1">
                                <div class="col-md-6">
                                    <b>Date</b>
                                </div>
                                <div class="col-md-6">{{ \Carbon\Carbon::parse($purchase->datetime)->format('d-m-Y') }}
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-6">
                                    <b>Fuel Company</b>
                                </div>
                                <div class="col-md-6">{{ $purchase->fuelCompany->name }}</div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-6">
                                    <b>Invoice No</b>
                                </div>
                                <div class="col-md-6">{{ $purchase->invoice_number }}</div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-6">
                                    <b> Purchase Order</b>
                                </div>
                                <div class="col-md-6">{{ $purchase->purchase_no }}</div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-6">
                                    <b> Total Quantities</b>
                                </div>
                                <div class="col-md-6">{{ $purchase->total_quantity }}</div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-6">
                                    <b> Total Amount</b>
                                </div>
                                <div class="col-md-6">${{ number_format($purchase->total_amount,2) }}</div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-6">
                                    <b> Assign Multiple Invoices</b>
                                </div>
                                <div class="col-md-6">
                                    @php
                                    $i = 1;
                                    $j = 1;
                                    @endphp
                                    <a id="purchase-details_{{ $purchase->id }}" class="btn btn-primary mb-2 mr-2"
                                        data-bs-toggle="modal" data-bs-target="#viewDetails1"
                                        onclick="viewDetails1('{{ $purchase->id }}','{{ $purchase->products->count() }}')"
                                        data-fuel="{{ $purchase->fuelCompany->name }}"
                                        data-invoice="{{ $purchase->invoice_number }}"
                                        data-purchase="{{ $purchase->purchase_no }}" @foreach ($purchase->products as
                                        $product) data-product_{{ $purchase->id }}_{{ $i++ }}_name="{{
                                        $product->product->name }}"
                                        data-product_{{ $purchase->id }}_{{ $j++ }}_qty="{{ $product->qty }}"
                                        @endforeach data-quantities="{{ $purchase->total_quantity }}"
                                        data-amount="{{ number_format($purchase->total_amount,2) }}"
                                        style="padding: 5px 8px; color:white">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="button" data-bs-toggle="modal" data-bs-target="#confirmMatch"
                            onclick="confirmMatch('{{ $purchase->id }}')" class="text-center btn btn-md btn-primary"
                            style=" background-color: position: absolute; top: 40%;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14px" height="20px" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-thumbs-up">
                                <path
                                    d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3">
                                </path>
                            </svg>
                            Done
                        </button>
                    </div>
                    <div class="col-md-5">
                        @php
                        $sales =
                        \App\Record::where('category_id',$purchase->category_id)->where('status',1)->where('deleted_status',0)->where('mass_match_status',0)->get();
                        // dd($sales);
                        $flag = 0;
                        @endphp
                        @foreach ($sales as $sale)
                        @php
                        $quantities = $sale->products->sum('qty');
                        @endphp
                        @if($quantities == $purchase->total_quantity)
                        <?php $flag = 1;?>
                        <div class="row">
                            <div class="col-md-12">
                                <label style="width: 100%;">
                                    <input type="radio" class="sale_order_{{ $purchase->id }}"
                                        name="sale_order_{{ $purchase->id }}" value="{{ $sale->id }}">
                                    <div class="sale-order">
                                        <div class="row mb-1">
                                            <div class="col-md-6">
                                                <b>Date</b>
                                            </div>
                                            <div class="col-md-6">{{
                                                \Carbon\Carbon::parse($sale->datetime)->format('d-m-Y') }}</div>
                                        </div>
                                        <div class="row mb-1">
                                            <div class="col-md-6">
                                                <b>Company</b>
                                            </div>
                                            <div class="col-md-6">{{ $sale->subCompany->company->name }}</div>
                                        </div>
                                        <div class="row mb-1">
                                            <div class="col-md-6">
                                                <b>Sub Company</b>
                                            </div>
                                            <div class="col-md-6">{{ $sale->subCompany->name }}</div>
                                        </div>
                                        <div class="row mb-1">
                                            <div class="col-md-6">
                                                <b>Fuel Company</b>
                                            </div>
                                            <div class="col-md-6">{{ $sale->supplierCompany->name }}</div>
                                        </div>
                                        <div class="row mb-1">
                                            <div class="col-md-6">
                                                <b>Invoice No</b>
                                            </div>
                                            <div class="col-md-6">{{ $sale->invoice_number }}</div>
                                        </div>
                                        <div class="row mb-1">
                                            <div class="col-md-6">
                                                <b>Total Quantities</b>
                                            </div>
                                            <div class="col-md-6">{{ $sale->products()->sum('qty') }}</div>
                                        </div>
                                        <div class="row mb-1">
                                            <div class="col-md-6">
                                                <b> Total Amount</b>
                                            </div>
                                            <div class="col-md-6">
                                                ${{ number_format($sale->total_amount,2) }}</div>
                                        </div>
                                        <div class="row mb-1">
                                            <div class="col-md-6">
                                                <b>View Detail</b>
                                            </div>
                                            <div class="col-md-6">
                                                @php
                                                $i = 1;
                                                $j = 1;
                                                @endphp
                                                <a data-bs-toggle="modal" data-bs-target="#viewDetails" class="btn btn-primary mb-2 mr-2"
                                                    id="invoice-details_{{ $sale->id }}"
                                                    onclick="viewDetails('{{ $sale->id }}','{{ $sale->products->count() }}')"
                                                    data-invoice="{{ $sale->invoice_number }}"
                                                    data-company="{{ $sale->subCompany->company->name }}"
                                                    data-subcompany="{{ $sale->subCompany->name }}"
                                                    data-driver="{{ $sale->user->name }}"
                                                    data-time="{{ \Carbon\Carbon::parse($sale->datetime)->format('d-m-Y H:i') }}"
                                                    data-trip="{{ $sale->load_number }}"
                                                    data-order="{{ $sale->order_number }}"
                                                    data-fuelcompany="{{ $sale->supplierCompany->name }}"
                                                    data-amount="{{ $sale->total_amount }}"
                                                    data-paidamount="{{ $sale->paid_amount }}"
                                                    data-load="{{ $sale->splitfullload }}" @foreach ($sale->products as
                                                    $product) data-product_{{ $sale->id }}_{{ $i++ }}_name="{{
                                                    $product->product->name }}"
                                                    data-product_{{ $sale->id }}_{{ $j++ }}_qty="{{ $product->qty }}"
                                                    @endforeach class="class="btn btn-outline-dark mb-2 mr-2"
                                                    style="padding: 5px 8px; color:white">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-eye">
                                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                        <circle cx="12" cy="12" r="3"></circle>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div><br>
                        @endif
                        @endforeach
                        @if($flag == 0)
                        <a href="{{ route('manual.insertion',$purchase->id) }}"
                            class="btn btn-md btn-primary text-center"
                            style="position: absolute; top: 40%; margin-left: 25%;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-external-link">
                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                <polyline points="15 3 21 3 21 9"></polyline>
                                <line x1="10" y1="14" x2="21" y2="3"></line>
                            </svg>
                            Click for manual insertion
                        </a>
                        @endif
                    </div>
                </div>
                <hr>
                @empty
                <p style="padding: 20px; background: #9999992e;">No purchase order record found.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
{{-- 3rd --}}






























<div class="modal fade" id="confirmMatch" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Mass Match</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
            </div>
            <form action="{{ route('mass-match.store') }}" method="POST">
                @csrf
                <input type="hidden" name="record_id" id="record_id" value="">
                <input type="hidden" name="purchase_record_id" id="purchase_record_id" value="">
                <div class="modal-body">
                    <div class="form-group">
                        <p id="text-info">Please confirm the match again!</p>
                    </div>
                </div>
                <div class="modal-footer" id="loading-btn">
                    <button type="submit" class="btn btn-primary">Confirm Match</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="viewDetails" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Invoice Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <table id="zero-config" class="table table-hover text-center table-bordered" style="width:100%"
                        role="grid">
                        <tbody id="tb-body"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="viewDetails1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Purchases Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <table id="zero-config-purchase" class="table table-hover text-center table-bordered"
                        style="width:100%" role="grid">
                        <tbody id="tb-body-purchase"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')

<script src="{{ URL::asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="{{ asset('assets/js/apps/contact.js') }}"></script>
<script src="{{ URL::asset('plugins/flatpickr/flatpickr.js') }}"></script>
<script src="{{ URL::asset('plugins/flatpickr/custom-flatpickr.js') }}"></script>
<script>
    var f1 = flatpickr(document.getElementById('from_date'), {
             enableTime: true,
             dateFormat: "d-m-Y H:i"
        });

      	var f2 = flatpickr(document.getElementById('to_date'), {
             enableTime: true,
             dateFormat: "d-m-Y H:i"
        });

        function confirmMatch(id) {
            $('#purchase_record_id').val(id);
            if ($(".sale_order_" + id + ":checked").val() != undefined) {
                var sale = $(".sale_order_" + id + ":checked").val()
                $('#loading-btn').css('display', 'block');
                $('#text-info').html('Please confirm the match again!');
                $('#record_id').val(sale);
            } else {
                $('#loading-btn').css('display', 'none');
                $('#text-info').html('Please select Invoice first...');
            }
        }

    	$("#select-range").change(function () {
       		var value = $("#select-range").val();
          	if (value != '') {
            	if (value == 'Custom Range') {
                	  $('#custom-range').css('display','flex');
                } else {

                  	if (value == 'Today') {
                    	var d = new Date();
                        var curr_date = d.getDate();
                        var curr_month = d.getMonth();
                        var curr_year = d.getFullYear();
                      	var from = curr_date + "-" + (parseInt(curr_month) + parseInt(1)) + "-" + curr_year + ' ' + '00:00';

                    	var d = new Date();
                        var curr_date = d.getDate();
                        var curr_month = d.getMonth();
                        var curr_year = d.getFullYear();
                      	var to = curr_date + "-" + (parseInt(curr_month) + parseInt(1)) + "-" + curr_year + ' ' + '24:00';
                    }

                  	if (value == 'Yesterday') {
                    	var today = new Date();
                      	var yesterday = new Date(today);
                      	yesterday.setDate(today.getDate() - 1);
                      	const monthNames = [
                            "01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"
                          ];
                      	var month = today.getMonth();
                        yesterday = yesterday.getDate() + '-' + monthNames[month] + '-' + yesterday.getFullYear()
                      	var from = yesterday + ' ' + '00:00';
                      	var to = yesterday + ' ' + '24:00';
                    }

                  	if (value == 'Last 7 Days') {
                    	var days = '7';
                      	var date = new Date();
                      	var last = new Date(date.getTime() - (days * 24 * 60 * 60 * 1000));
                      	var day = last.getDate();
                      	var month = last.getMonth() + 1;
                      	var year = last.getFullYear();
                      	var from = day + '-' + month+'-' + year +' '+ '00:00';

						var d = new Date();
                        var curr_date = d.getDate();
                        var curr_month = d.getMonth();
                        var curr_year = d.getFullYear();
                      	var to = curr_date + "-" + (parseInt(curr_month) + parseInt(1)) + "-" + curr_year + ' ' + '24:00';
                    }

                  	if (value == 'Last 30 Days') {
                    	var days = '30';
                      	var date = new Date();
                      	var last = new Date(date.getTime() - (days * 24 * 60 * 60 * 1000));
                      	var day = last.getDate();
                      	var month = last.getMonth() + 1;
                      	var year = last.getFullYear();
                      	var from = day + '-' + month+'-' + year +' '+ '00:00';

						var d = new Date();
                        var curr_date = d.getDate();
                        var curr_month = d.getMonth();
                        var curr_year = d.getFullYear();
                      	var to = curr_date + "-" + (parseInt(curr_month) + parseInt(1)) + "-" + curr_year + ' ' + '24:00';
                    }

                  	if (value == 'This Month') {
                    	var date = new Date();
                      	var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
                      	var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
                      	var firstDayWithSlashes = (firstDay.getDate()) + '-' + (firstDay.getMonth() + 1) + '-' + firstDay.getFullYear();
                      	var lastDayWithSlashes = (lastDay.getDate()) + '-' + (lastDay.getMonth() + 1) + '-' + lastDay.getFullYear();

                      	var from = firstDayWithSlashes + ' ' + '00:00';
                        var to = lastDayWithSlashes + ' ' + '24:00';
                    }

                  	if (value == 'Last Month') {
                    	var now = new Date();
                        var prevMonthLastDate = new Date(now.getFullYear(), now.getMonth(), 0);
                        var prevMonthFirstDate = new Date(now.getFullYear() - (now.getMonth() > 0 ? 0 : 1), (now.getMonth() - 1 + 12) % 12, 1);
                        var formatDateComponent = function(dateComponent) {
  							return (dateComponent < 10 ? '0' : '') + dateComponent;
						};

                        var formatDate = function(date) {
                          	return formatDateComponent(date.getDate()) + '-' + formatDateComponent(parseInt(date.getMonth()) + 1) + '-' + formatDateComponent(date.getFullYear());
                        };

                        var from = formatDate(prevMonthFirstDate) + ' ' + '00:00';
                        var to = formatDate(prevMonthLastDate) + ' ' + '24:00';
                    }

                  	$('#from_date').val(from);
                  	$('#to_date').val(to);
                	$('#custom-range').css('display','none');
                }
            }
        });

        function viewDetails1(id, count) {
            $('#zero-config-purchase').html('<tbody id="tb-body-purchase"></tbody>');
            $('#tb-body-purchase').html('');
            $('#tb-body-purchase').after('<tr><th>Total Amount</th><td>' + $('#purchase-details_'+id).attr('data-amount') + '</td></tr>');
            $('#tb-body-purchase').after('<tr><th>Total Quantities</th><td>' + $('#purchase-details_'+id).attr('data-quantities') + '</td></tr>');
            var i;
            var j = count;
            var k = count;
            for (i = count; i >= 1; i--) {
                var name = $('#purchase-details_'+id).attr('data-product_' + id + '_' + j + '_name');
                var qty = $('#purchase-details_'+id).attr('data-product_' + id + '_' + k + '_qty');
                $("#tb-body-purchase").after('<tr><th>' + name + '</th><td>' + qty + '</td></tr>');
                j--;
                k--;
            }

            $('#tb-body-purchase').after('<tr><th>Purchase No</th><td>' + $('#purchase-details_'+id).attr('data-purchase') + '</td></tr>');
            $('#tb-body-purchase').after('<tr><th>Invoice No</th><td>' + $('#purchase-details_'+id).attr('data-invoice') + '</td></tr>');
            $('#tb-body-purchase').after('<tr><th>Fuel Company</th><td>' + $('#purchase-details_'+id).attr('data-fuel') + '</td></tr>');
        }

        function viewDetails(id, count) {
            $('#zero-config').html('<tbody id="tb-body"></tbody>');
            $('#tb-body').html('');
            $('#tb-body').after('<tr><th>Split Load /Full Load</th><td>' + $('#invoice-details_'+id).attr('data-load') + '</td></tr>');
            $('#tb-body').after('<tr><th>Paid Amount</th><td>$' + $('#invoice-details_'+id).attr('data-paidamount') + '</td></tr>');
            $('#tb-body').after('<tr><th>Total Amount</th><td>$' + $('#invoice-details_'+id).attr('data-amount') + '</td></tr>');
            var i;
            var j = count;
            var k = count;
            for (i = count; i >= 1; i--) {
                var name = $('#invoice-details_'+id).attr('data-product_' + id + '_' + j + '_name');
                var qty = $('#invoice-details_'+id).attr('data-product_' + id + '_' + k + '_qty');
                $("#tb-body").after('<tr><th>' + name + '</th><td>' + qty + '</td></tr>');
                j--;
                k--;
            }
            $('#tb-body').after('<tr><th>Fuel Company</th><td>' + $('#invoice-details_'+id).attr('data-fuelcompany') + '</td></tr>');
            $('#tb-body').after('<tr><th>Order #</th><td>' + $('#invoice-details_'+id).attr('data-order') + '</td></tr>');
            $('#tb-body').after('<tr><th>Trip #</th><td>' + $('#invoice-details_'+id).attr('data-trip') + '</td></tr>');
            $('#tb-body').after('<tr><th>Date Time</th><td>' + $('#invoice-details_'+id).attr('data-time') + '</td></tr>');
            $('#tb-body').after('<tr><th>Operator Name</th><td>' + $('#invoice-details_'+id).attr('data-driver') + '</td></tr>');
            $('#tb-body').after('<tr><th>Sub Company</th><td>' + $('#invoice-details_'+id).attr('data-subcompany') + '</td></tr>');
            $('#tb-body').after('<tr><th>Company</th><td>' + $('#invoice-details_'+id).attr('data-company') + '</td></tr>');
            $('#tb-body').after('<tr><th>Invoice #</th><td>' + $('#invoice-details_'+id).attr('data-invoice') + '</td></tr>');

        }

</script>

@endsection
