@extends('layouts.layout.main')
@section('title','Expenses')
@section('css')

    <link rel="stylesheet" type= "text/css" href="{{ URL::asset('plugins/table/table/datatable/datatables.css') }}">
    <link rel="stylesheet" type= "text/css" href="{{ URL::asset('plugins/table/table/datatable/custom_dt_html5.css') }}">
    <link rel="stylesheet" type= "text/css" href="{{ URL::asset('plugins/table/table/datatable/dt-global_style.css') }}">
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
        .btn:hover{
            background-color: #2bbc4a;
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
                    <h4>
                            Expenses

                        </h4>
                    <div class="box_right d-flex lms_block">
                        {{--  <div class="serach_field_2">
                            <div class="search_inner">
                                <form Active="#">
                                    <div class="search_field">
                                        <input type="text" placeholder="Search content here...">
                                    </div>
                                    <button type="submit"> <i class="ti-search"></i> </button>
                                </form>
                            </div>
                        </div>  --}}
                        <div class="add_button ms-2">
                            <a href="{{ route('expenses.create') }}" class="btn btn-md btn-primary"
                            style="float: right;">Add Expense</a>
                        </div>
                    </div>
                </div>
                <div class="QA_table mb_30">

                    <table id="html5-extension" class="table table-hover non-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Main Account</th>
                                <th>Sub Account</th>
                                <th>Date/Time</th>
                                <th>Amount</th>
                                <th>Payee</th>
                                <th>Ref#</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <?php $sno = 1; ?>
                        <tbody>
                            @forelse($expenses as $expense)
                            <tr>
                                <td>{{ $sno++ }}</td>
                                <td>{{ $expense->chartAccount->title }}</td>
                                <td>{{ $expense->subaccount->title }}</td>
                                <td>{{ \Carbon\Carbon::parse($expense->datetime)->format('d-m-Y H:i') }}</td>
                                <td>${{ number_format($expense->amount,2) }}</td>
                                <td>{{ $expense->payee }}</td>
                                <td>{{ $expense->ref_no }}</td>
                                <td style="white-space: nowrap !important;">
                                    <a href="{{ route('expenses.edit',$expense->id) }}"
                                        class="btn btn-sm btn-primary btn-custom" style="color: white">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-edit-3">
                                            <path d="M12 20h9"></path>
                                            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z">
                                            </path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('expenses.show',$expense->id) }}"
                                        class="btn btn-sm btn-primary btn-custom" style="color: white">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-eye">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8">No record found.</td>
                            </tr>
                            @endforelse
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

    <script src="{{ URL::asset('plugins/table/datatable/datatables.js') }}"></script>
    <script src="{{ URL::asset('plugins/table/datatable/button-ext/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('plugins/table/datatable/button-ext/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('plugins/table/datatable/button-ext/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('plugins/table/datatable/button-ext/buttons.print.min.js') }}"></script>
    <script>

        $('#html5-extension').DataTable({
          "order": [[ 0, "desc" ]],
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
                
                "sSearchPlaceholder": "",
                "sLengthMenu": "Results :  _MENU_",
            },
            "stripeClasses": [],
            "order":[[1, 'desc']],//asc or desc
            "lengthMenu": [10, 20, 30, 50],
            "pageLength": 10
        });
    </script>

@endsection
