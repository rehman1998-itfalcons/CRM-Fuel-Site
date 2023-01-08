@extends('layouts.layout.main')
@section('title', 'Purchase Orders')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('plugins/table/datatable/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('plugins/table/datatable/custom_dt_html5.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('plugins/table/datatable/dt-global_style.css') }}">
    <style>
        table.dataTable thead .sorting_asc:before {
            display: none;
        }
    </style>
    <link href="{{ URL::asset('plugins/flatpickr/flatpickr.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('plugins/flatpickr/custom-flatpickr.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('plugins/loaders/custom-loader.css') }}" rel="stylesheet" type="text/css" />
    <style>
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
        .btn-group button {
            margin-right: 5px;
        }
        .btn-group {
            float: center;
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
                        <h4>Purchase Invoices</h4>
                        <div class="box_right d-flex lms_block">
                            <div class="serach_field_2">
                                
                            </div>
                            <div class="add_button ms-2">
                               
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">

                            <div class="row">
                                <div class="col-md-3">
                                    <select id="select-range" class="form-control select-change">
                                        <option value="">--Select Date Range --</option>
                                        <option value="Today">Today</option>
                                        <option value="Yesterday">Yesterday</option>
                                        <option value="Last 7 Days">Last 7 Days</option>
                                        <option value="Last 30 Days">Last 30 Days</option>
                                        <option value="This Month">This Month</option>
                                        <option value="Last Month">Last Month</option>
                                        <option value="Custom Range">Custom Range</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select id="select-company" class="form-control select-change">
                                        <option value="">--Select Company --</option>
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- <div class="col-md-3"><a href="{{ url('upload-purchase-invoices') }}"><button
                                            style="float: right;" type="button" class="btn btn-md btn-primary">Click to upload on
                                            Myob</button></a> </div> --}}
                              
                                 <div class="col-md-1 btn-group">

                                    <button type="button"  name="Excel" id="Excel" class="btn btn-primary">Excel</button>
                                </div>
                            </div>
                            <br>
                            <div class="row" id="custom-range" style="display: none;">
                                <div class="col-md-3">
                                    <input id="from_date" class="form-control" placeholder="Start Date" />

                                </div>
                                <div class="col-md-3">
                                    <input id="to_date" class="form-control" placeholder="To Date" />
                                </div>
                                <div class="col-md-2">
                                    <button type="button" id="submit-btn" class="btn btn-md btn-block btn-primary"
                                        style="height: 45.4px;">Submit</button>
                                </div>
                            </div>
                            <div class="QA_table mb_30">
                                <table class="table lms_table_active table-bordered data-table">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Invoice no</th>
                                            <th>Date/Time</th>
                                            <th>Category</th>
                                            <th>Fuel Company</th>
                                            <th>Status</th>
                                            <th>Total Quantity</th>
                                            <th>Total Amount</th>
                                            <th>Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
@endsection
@section('scripts')
<script type="text/javascript">
    $('#Excel').on('click', function() {
        $start_date = $('#from_date').val();
        $end_date = $('#to_date').val();
        $search = $('input[type=search]').val();
        window.location.href = "{{ route('purchaseallInvoicesexport.export') }}/?start_date=" + $start_date + "&end_date=" + $end_date + "&search=" + $search;
    })
</script>
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script> --}}
    {{-- <script src="{{ URL::asset('plugins/table/datatable/datatables.js') }}"></script>
    <script src="{{ URL::asset('plugins/table/datatable/button-ext/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('plugins/table/datatable/button-ext/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('plugins/table/datatable/button-ext/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('plugins/table/datatable/button-ext/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('plugins/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ URL::asset('plugins/flatpickr/custom-flatpickr.js') }}"></script>
    <script src="{{URL::asset('assets/js/jquery.multifile.js')}}"></script> --}}

    <script>

        $("#select-company").change(function() {
            tableRecords.ajax.reload();
        });
        $("#select-range").change(function() {
            if ($(this).val() == 'Custom Range'){
                
                $('#custom-range').css('display','flex');

            } else {
                $('#custom-range').css('display','none');
                tableRecords.ajax.reload();
            }
        });
        $('#submit-btn').click(function() {
            var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();
                if (from_date != '' && to_date != '') {

                    tableRecords.ajax.reload();
                } else {
                    alert('Both Date is required');
                }
        });


</script>
    <script type="text/javascript">
        $(document).ready(function() {
            tableRecords = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('purchase.all.invoices') }}',
                    data: function(d) {
                        var from_date = $('#from_date').val();
                        var to_date = $('#to_date').val();
                        var company = $("#select-company").val();
                        var type = $("#select-range").val();
                        d.company = company;
                        d.type = type;
                        if (from_date != '' && to_date != '') {
                            d.start_date = from_date;
                            d.end_date = to_date;
                        }
                    }
                },

                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'invoice_number',
                        name: 'invoice_number'
                    },
                    {
                        data: 'datetime',
                        name: 'datetime'
                    },
                    {
                        data: 'category_name',
                        name: 'category_name'
                    },
                    {
                        data: 'fuelCompany_name',
                        name: 'fuelCompany_name'
                    },
                    {
                        data: 'paid_status',
                        name: 'paid_status'
                    },
                    {
                        data: 'total_quantity',
                        name: 'total_quantity'
                    },
                    {
                        data: 'total_amount',
                        name: 'total_amount'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: true,
                        searchable: true
                    },
                ]
            });
        $('#filter').click(function() {
                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();
                if (from_date != '' && to_date != '') {
                    tableRecords.ajax.reload();
                } else {
                    alert('Both Date is required');
                }
            });
            $('#reset').click(function() {
                $('#from_date').val('');
                $('#to_date').val('');
                tableRecords.ajax.reload();
            });

            var today = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
        $('#from_date').datepicker({
            uiLibrary: 'bootstrap4',
            iconsLibrary: 'fontawesome',
            
            maxDate: function() {
                return $('#to_date').val();
            }
        });
        $('#to_date').datepicker({
            uiLibrary: 'bootstrap4',
            iconsLibrary: 'fontawesome',
            minDate: function() {
                return $('#startDate').val();
            }
        });
        });
    </script>
@endsection
