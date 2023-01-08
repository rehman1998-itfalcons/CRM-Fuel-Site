@extends('layouts.layout.main')
@section('title','Trash')
@section('css')

    <link rel="stylesheet" type="text/css" href="{{ URL::asset('plugins/table/datatable/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('plugins/table/datatable/custom_dt_html5.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('plugins/table/datatable/dt-global_style.css') }}">
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

      	thead > tr > th.sorting {
    		white-space: nowrap !important;
        }

		.table > thead > tr > th {
    		font-weight: 700 !important;
        }

        .btn-cust {
            padding: 5px 10px !important;
            font-size: 12px !important;
            box-shadow: 0px 5px 20px 0 rgba(0, 0, 0, 0.2) !important;
        }
        .btn-group button{
             margin-right:5px;
        }
        .btn-group{
            float:center;
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
                    <h4>Trash</h4>
                    <br />

                    {{-- <div class="row input-daterange"> --}}
                        {{-- <div class="col-md-3">
                            <input id="from_date" class="form-control" placeholder="Start Date" />

                        </div>
                        <div class="col-md-3">
                            <input id="to_date" class="form-control" placeholder="To Date" />
                        </div> --}}
                        <div class="col-md-3 btn-group">
                            <button type="button" name="csv" id="csv" class="btn btn-primary">CSV</button>
                            <button type="button" name="excel" id="excel" class="btn btn-primary btn-default">Excel</button>
                        </div>
                    {{-- </div> --}}
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
{{--
    <script src="{{ URL::asset('plugins/table/datatable/datatables.js') }}"></script>
    <script src="{{ URL::asset('plugins/table/datatable/button-ext/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('plugins/table/datatable/button-ext/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('plugins/table/datatable/button-ext/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('plugins/table/datatable/button-ext/buttons.print.min.js') }}"></script> --}}
    {{-- <script>
        $('#html5-extension').DataTable({
          	"order": [[ 0, "desc" ]],
           dom: '<"row"<"col-md-12"<"row"<"col-md-6"B><"col-md-6"f> > ><"col-md-12"rt> <"col-md-12"<"row"<"col-md-5"i><"col-md-7"p>>> >',
            buttons: {
                buttons: [
                    { extend: 'csv', className: 'btn' },
                    { extend: 'excel', className: 'btn' },
                    { extend: 'print', className: 'btn' }
                ]

            },

            "oLanguage": {
                "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'

              },
                "sInfo": "Showing page _PAGE_ of _PAGES_",
                "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                "sSearchPlaceholder": "Search...",
               "sLengthMenu": "Results :  _MENU_",

             },

            "stripeClasses": [],
            "lengthMenu": [10, 20, 25, 50],
            "pageLength": 10

        });
    </script> --}}
    <script type="text/javascript">
        $(document).ready(function() {

            tableRecords = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('trash') }}',
                    data: function(d) {

                      
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



           
        });
    </script>
    <script>
       
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

@endsection
