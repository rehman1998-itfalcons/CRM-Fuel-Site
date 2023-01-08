@extends('layouts.layout.main')
@section('title','Dashboard')
@section('css')

    <link rel="stylesheet" type="text/css" href="{{URL::asset('plugins/table/datatable/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{URL::asset('plugins/table/datatable/custom_dt_html5.css') }}">
    <link rel="stylesheet" type="text/css" href="{{URL::asset('plugins/table/datatable/dt-global_style.css') }}">
	<style>
		table.dataTable thead .sorting_asc:before {
          display: none;
        }
      	thead > tr > th.sorting {
    		white-space: nowrap !important;
        }
		.table > thead > tr > th {
    		font-weight: 700 !important;
        }
        .butn:hover{
            background-color:#32C36C;
        }
	</style>
@endsection
@section('contents')

	<style>
		.btn-cust {
          padding: 2px 6px !important;
          font-size: 12px !important;
          box-shadow: 0px 5px 20px 0 rgba(0, 0, 0, 0.2) !important;
        }
        .table > tbody:before {
            content: "" !important;
        }
        .btn-group button{
             margin-right:5px;
        }
        .btn-group{
            float:center;
        }
	</style>
	<div class="container-fluid p-10">
        <div class="row justify-content-center">
            <div class="col-sm-12 ">
                <div class="QA_section">
                    <div class="white_box_tittle list_header">
                        <h4>Accountant Pending Application</h4>
                        <div class="box_right d-flex lms_block">
                            <div class="serach_field_2">
                                {{-- <div class="search_inner">
                                    <form Active="#">
                                        <div class="search_field">
                                            <input type="text" placeholder="Search content here...">
                                        </div>
                                        <button type="submit"> <i class="ti-search"></i> </button>
                                    </form>
                                </div> --}}
                            </div>
                            <div class="add_button ms-2">
                                <div class="col-md-3 btn-group">
                                    <button type="button" name="csv" id="csv" class="btn btn-primary">CSV</button>
                                    <button type="button" name="excel" id="excel" class="btn btn-primary btn-default">Excel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="QA_table mb_30">
                        {{-- <table id="html5-extension" class="table lms_table_active table-hover non-hover" style="width:100%"> --}}
                            <table class="table dataTable lms_table_active" style="width:100%" role="grid"
                            aria-describedby="zero-config_info">
                            <thead>
                            <tr>
                              <th>#</th>
                              <th>Invoice</th>
                              <th>Operator</th>
                              <th>Date/Time</th>
                              <th>Company</th>
                              <th>Sub-Company</th>
                              <th>Load #</th>
                              <th>Order #</th>
                              <th>Status</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php $sno = 1; ?>
                            @foreach ($records as $record)
                              <tr>
                                <th>{{ $sno++ }}</th>
                                <th>@if($record->invoice_number)
                                    {{$record->invoice_number}}
                                  {{--<a href="{{ route('invoice',$record->id) }}" target="_blank" class="btn btn-outline-dark mb-2 mr-2" target="_blank" style="padding: 4px 5px; width: 70px;"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg></a>--}}
                                  @else - @endif</th>
                                <th>{{ $record->user->name }}</th>
                                <th>{{ \Carbon\Carbon::parse($record->datetime)->format('d-m-Y') }}</th>
                                <th>{{ ($record->sub_company_id) ? $record->subCompany->company->name : 'Not Selected' }}</th>
                                <th>{{ ($record->sub_company_id) ? $record->subCompany->name : 'Not Selected' }}</th>
                                <th>{{ $record->load_number }}</th>
                                <th>{{ $record->order_number }}</th>
                                <th>
                                  @if($record->status == 1)
                                  <span class="badge badge-success">Approved</span>
                                  @else
                                   <span class="badge badge-danger">Pending</span>
                                  @endif
                                </th>
                                <th>
                                  <a href="{{ route('accountant.record.details',$record->id) }}" style="color:white;" class="btn btn-sm btn-primary">View</a>
                                </th>
                              </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')

    <script src="{{URL::asset('plugins/table/datatable/datatables.js') }}"></script>
    <script src="{{URL::asset('plugins/table/datatable/button-ext/dataTables.buttons.min.js') }}"></script>
    <script src="{{URL::asset('plugins/table/datatable/button-ext/jszip.min.js') }}"></script>
    <script src="{{URL::asset('plugins/table/datatable/button-ext/buttons.html5.min.js') }}"></script>
    <script src="{{URL::asset('plugins/table/datatable/button-ext/buttons.print.min.js') }}"></script>
    <script>
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
    </script>

@endsection
