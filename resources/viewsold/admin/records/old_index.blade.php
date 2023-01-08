@extends('layouts.layout.main')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('plugins/table/datatable/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('plugins/table/datatable/custom_dt_html5.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('plugins/table/datatable/dt-global_style.css') }}">
    <link href="{{ URL::asset('plugins/loaders/custom-loader.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('plugins/flatpickr/flatpickr.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('plugins/flatpickr/custom-flatpickr.css') }}" rel="stylesheet" type="text/css">
    {{-- here im --}}
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
            <div class="col-12">
                <div class="QA_section">
                    <div class="white_box_tittle list_header">
                        <h4>Records</h4>
                        <br />

                        <div class="row input-daterange">
                            <div class="col-md-3">
                                <input id="from_date" class="form-control" placeholder="Start Date" />

                            </div>
                            <div class="col-md-3">
                                <input id="to_date" class="form-control" placeholder="To Date" />
                            </div>
                            <div class="col-md-3">
                                <button type="button" name="filter" id="filter" class="btn btn-primary">Filter</button>
                                <button type="button" name="reset" id="reset" class="btn btn-primary btn-default">Reset</button>
                            </div>
                        </div>
                        <br />


                    </div>
                    <div class="QA_table mb_30">

                        <table class="table lms_table_active table-bordered data-table" id="order_table">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Invoice no</th>
                                    <th>Date/Time</th>
                                    <th>Trip Number</th>
                                    <th>Order Number</th>
                                    <th>Category</th>
                                    <th>Sub Company</th>
                                    <th>GST Status</th>
                                    <th>Total Amount</th>
                                    <th>Action</th>


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
@endsection
@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            tableRecords = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('records.index') }}',
                    data: function(d) {

                        var from_date = $('#from_date').val();
                        var to_date = $('#to_date').val();
                        if (from_date != '' && to_date != '') {
                            d.start_date = from_date;
                            d.end_date = to_date;
                        }
                    }

                },
                // ajax: "{{ route('records.index') }}",

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
                        data: 'load_number',
                        name: 'load_number'
                    },
                    {
                        data: 'order_number',
                        name: 'order_number'
                    },
                    {
                        data: 'category_name',
                        name: 'category_name'
                    },
                    {
                        data: 'subCompany_name',
                        name: 'subCompany_name'
                    },
                    {
                        data: 'gst_status',
                        name: 'gst_status'
                    },
                    {
                        data: 'total_amount',
                        name: 'total_amount'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
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
        });
    </script>
    <script>
        var today = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
        $('#from_date').datepicker({
            uiLibrary: 'bootstrap4',
            iconsLibrary: 'fontawesome',
            // minDate: today,
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
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
@endsection
