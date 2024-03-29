@extends('layouts.template')
@section('title','Assign Invoices Mass Matches')
@section('css')

    <style>
        .table > thead > tr > th {
            color: #fff !important;
        }

        .table > tbody > tr > td {
            color: #000 !important;
            font-weight: 600;
        }

        .hr_style {
            color: black;
            border: 0.5px solid black;
            margin: 2px;
        }
    </style>

@endsection
@section('contents')
    @if(session('warning'))
        <p class="alert alert-warning">Please make sure that Purchase order quantity is equal to total invoices.</p>
    @endif
   @if(session('danger'))
        <p class="alert alert-warning"><strong>{{ Session::get('danger') }}</strong></p>
    @endif
    <form action="{{ url('/mass-match-manual-insertion') }}" method="POST" onsubmit="loginLoadingBtn()">
        @csrf
    <div class="row layout-top-spacing" id="cancel-row">
        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
            <div class="widget-content widget-content-area br-6" >
                    <div class="row">
                        <div class="col-md-12">
                            <div class="widget-header">
                                <div class="row">
                                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                        <h3>Assign Purchase</h3>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="table-responsive">
                                <table id="zero_config" class="table table-bordered">
                                    <thead>
                                    <tr class="bg-primary">
                                        <th class="invoice-th">Invoice No</th>
                                        <th class="invoice-th">Company</th>
                                        <th class="invoice-th">Total Quantities</th>
                                        <th class="invoice-th">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody id="purchase_records_list"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-4">
                            <select id="purchase_record_id" class="form-control">
                                <option value="">-- Select Purchase --</option>
                                @foreach ($purchase_records as $record)
                                    <option value="{{ $record->id }}">
                                        {{ $record->invoice_number }} - (Quantity: {{ $record->products->sum('qty') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <a class="btn btn-md btn-outline-primary" style="height: 45.5px;" id="add-purchase-invoice">Add Invoice</a>
                        </div>
                        <div class="col-md-6">
                            <hr class="hr_style">
                            <h3 style="margin: 0px 20px;">
                                TOTAL (<span id="purchase_total_count">0</span>)
                                <span style="float: right;" id="purchase_total_amount"></span>
                            </h3>
                            <hr class="hr_style">
                        </div>
                    </div>
                    <br>
            </div>
        </div>
    </div>

    <hr>
    <div class="row layout-top-spacing" id="cancel-row">
        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
            <div class="widget-content widget-content-area br-6" >

                <div class="row">
                    <div class="col-md-12">
                        <div class="widget-header">
                            <div class="row">
                                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                    <h3>Assign Sales</h3>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="table-responsive">
                            <table id="zero_config" class="table table-bordered">
                                <thead>
                                <tr class="bg-primary">
                                    <th class="invoice-th">Invoice No</th>
                                    <th class="invoice-th">Company</th>
                                    <th class="invoice-th">Sub Company</th>
                                    <th class="invoice-th">Fuel Company</th>
                                    <th class="invoice-th">Trip No</th>
                                    <th class="invoice-th">Order No</th>
                                    <th class="invoice-th">Total Quantities</th>
                                    <th class="invoice-th">Action</th>
                                </tr>
                                </thead>
                                <tbody id="records_list"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-4">
                        <select id="record_id" class="form-control">
                            <option value="">-- Select Sale --</option>
                            @foreach ($records as $record)
                                <option value="{{ $record->id }}">
                                    {{ $record->invoice_number }} - (Quantity: {{ $record->products->sum('qty') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <a class="btn btn-md btn-outline-primary" style="height: 45.5px;" id="add-invoice">Add Invoice</a>
                    </div>
                    <div class="col-md-6">
                        <hr class="hr_style">
                        <h3 style="margin: 0px 20px;">
                            TOTAL (<span id="total_count">0</span>)
                            <span style="float: right;" id="total_amount"></span>
                        </h3>
                        <hr class="hr_style">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 text-right" id="loading-btn">
                        <button type="submit" class="btn btn-lg mr-3 btn-primary" id="submit-btn" disabled>Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>
@endsection
@section('scripts')

    <script>

        function loginLoadingBtn()
        {
            document.getElementById('loading-btn').innerHTML = '<button class="btn btn-lg mr-3 btn-primary disabled" style="width: auto !important;">Submiting <svg xmlns="http://www.w3.org/2000/svg" width="24" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-loader spin ml-2"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg></button>';
            return true;
        }

        $('#add-purchase-invoice').click(function () {
            var sale = $('#purchase_record_id').val();
            if (sale == '') {
                $('#purchase_record_id').css('border-color','#e91e63');
            } else {
                $('#purchase_record_id').css('border-color','#bfc9d4');
                $.post('{{ url('/add-purchase-invoice-mass-match') }}',{_token:'{{ csrf_token() }}', id:sale}, function(data) {
                    $('#purchase_records_list').append(data);
                    calculatePurchaseQty();
                    compareQuantities();
                });
            }
        });

        $('#add-invoice').click(function () {
            var sale = $('#record_id').val();
            if (sale == '') {
                $('#record_id').css('border-color','#e91e63');
            } else {
                $('#record_id').css('border-color','#bfc9d4');
                $.post('{{ url('/add-invoice-mass-match') }}',{_token:'{{ csrf_token() }}', id:sale}, function(data) {
                    $('#records_list').append(data);
                    calculateQty();
                    compareQuantities();
                });
            }
        });

        function calculatePurchaseQty()
        {
            var i = parseInt(0);
            var total_invoices = parseInt(0);
            var total;
            $('input[type="hidden"].purchases').map(function () {
                total_invoices = total_invoices + parseInt($(this).val());
                if (parseInt($(this).val()) > 0)
                    i = i + 1;
            });

            $('#purchase_total_amount').html(total_invoices);
            $('#purchase_total_count').html(i);
        }

        function calculateQty()
        {
            var i = parseInt(0);
            var total_invoices = parseInt(0);
            var total;
            $('input[type="hidden"].invoices').map(function () {
                total_invoices = total_invoices + parseInt($(this).val());
                if (parseInt($(this).val()) > 0)
                    i = i + 1;
            });

            $('#total_amount').html(total_invoices);
            $('#total_count').html(i);
        }

        function removeInvoice(id)
        {
            $('#record_data_'+id).val(0);
            $('#invoices_'+id).val('');
            $('#record_body_'+id).css('display','none');
            calculateQty();
            compareQuantities();
        }

        function purchaseRemoveInvoice(id)
        {
            $('#purchase_record_data_'+id).val(0);
            $('#purchases_'+id).val('');
            $('#purchase_record_body_'+id).css('display','none');
            calculatePurchaseQty();
            compareQuantities();
        }

        function compareQuantities()
        {
            var total_purchases = 0;
            var total_sales = 0;
            $('input[type="hidden"].invoices').map(function () {
                total_sales = total_sales + parseInt($(this).val());
            });

            $('input[type="hidden"].purchases').map(function () {
                total_purchases = total_purchases + parseInt($(this).val());
            });
            
            var total = total_sales - total_purchases;
            if (total == 0)
                $('#loading-btn').html('<button type="submit" class="btn btn-lg mr-3 btn-primary" id="submit-btn" style="width: auto !important;">Submit</button>');
            else
                $('#loading-btn').html('<button class="btn btn-lg mr-3 btn-primary" disabled style="width: auto !important;">Submit</button>');
        }

        $('#submit-btn').click(function () {
            alert('You clicked submit button');
        });

    </script>

@endsection