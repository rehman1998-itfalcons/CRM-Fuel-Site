@extends('layouts.layout.main')
@section('title','Record')
@section('css')
   
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
        .table td {
            text-align:center;



        }
        .QA_table td {
            padding:16px 2px !important;
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
                            <div class="col-md-5">
                                <button type="button" name="filter" id="filter" class="btn btn-primary">Filter</button>
                                <button type="button" name="reset" id="reset" class="btn btn-primary btn-default">Reset</button>
                                <button type="button"  name="Excel" id="Excel" class="btn btn-primary">Excel</button>
                            </div>
                        </div>
                        <br />


                    </div>
                    <div class="QA_table mb_30">

                        <table class="table lms_table_active table-bordered data-table" >
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
    $('#Excel').on('click', function() {
        $start_date = $('#from_date').val();
        $end_date = $('#to_date').val();
        $search = $('input[type=search]').val();
        window.location.href = "{{ route('export') }}/?start_date=" + $start_date + "&end_date=" + $end_date + "&search=" + $search;
    })
</script>
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
                

                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'invoice_number',
                        name: 'invoice_number',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'datetime',
                        name: 'datetime',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'load_number',
                        name: 'load_number',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'order_number',
                        name: 'order_number',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'category_name',
                        name: 'category_name',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'subCompany_name',
                        name: 'subCompany_name',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'gst_status',
                        name: 'gst_status',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'total_amount',
                        name: 'total_amount',
                        orderable: true,
                        searchable: true

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
        });
    </script>
    <script>
        var today = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
        $('#from_date').datepicker({
            uiLibrary: 'bootstrap4',
            iconsLibrary: 'fontawesome',
            // minDate: today,
            minDate: function() {
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
   
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
  
@endsection
