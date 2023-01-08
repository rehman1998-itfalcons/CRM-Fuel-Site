@extends('layouts.layout.main')
@section('title','Chart Of Accounts')
@section('css')

<link rel="stylesheet" type= "text/css" href="{{ URL::asset('plugins/table/table/datatable/datatables.css') }}">
<link rel="stylesheet" type= "text/css" href="{{ URL::asset('plugins/table/table/datatable/custom_dt_html5.css') }}">
<link rel="stylesheet" type= "text/css" href="{{ URL::asset('plugins/table/table/datatable/dt-global_style.css') }}">
<style>
    table.dataTable thead .sorting_asc:before {
        display: none;
    }

    .table>thead>tr>th {
        font-weight: 700 !important;
    }
    #color{
        color: white;
    }
    #color:hover{
        color: #2bbc4a;
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
                    <h4>Chart Of Accounts</h4>
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
                            <a href="{{route('create.chart.account')}}" class="btn btn-primary"
                                style="float:right;"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg> Add Account</a>
                            <a href="{{route('manage.chart.account_type')}}" class="btn btn-primary"
                                style="float:right; display: none;">Manage Chart Of Account Types</a>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="QA_table mb_50">

                    <table  class="table table-hover non-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Account Type</th>
                                <th>Description</th>
                                <th>Code</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $sno = 1; ?>
                            @foreach ($chartaccounts as $account)
                            <tr>
                                <td>{{ $sno++ }}</td>
                                <td>{{ $account->title }}</td>
                                <td>{{ $account->chartAccountType->title }}</td>
                                <td>{{ $account->description }}</td>
                                <td>{{ $account->code }}</td>
                                <td>
                                    @if($account->title == 'Sale' || $account->title == 'Purchase' || $account->title ==
                                    'Expense')
                                    <button class="btn btn-sm btn-primary btn-cust" disabled>Edit</button>
                                    <button class="btn btn-sm btn-danger btn-cust" disabled
                                        style="display: none;">Delete</button>
                                    @else
                                    <a href="{{route('edit.chart.account',$account->id)}}"
                                        class="btn btn-sm btn-primary btn-cust" id="color">Edit</a>
                                    <a class="btn btn-sm btn-danger btn-cust" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal" onclick="deleteChartAccount('{{ $account->id }}')"
                                        style="display: none;">Delete</a>
                                    @endif
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


<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete Chart Of Account</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">X</button>
            </div>
            <div class="modal-body">
                <p>Are you sure to delete Chart Of Account?</p>
            </div>
            <div class="modal-footer">
                <form action="{{ route('delete.chart.account') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="chart_account_id">
                    <button type="submit" class="btn btn-primary">Confirm Delete</button>
                </form>
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
           "oLanguage": {
                "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
                "sInfo": "Showing page _PAGE_ of _PAGES_",
                "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                "sSearchPlaceholder": "Search...",
               "sLengthMenu": "Results :  _MENU_",
            },
            "stripeClasses": [],
            "lengthMenu": [10, 20, 30, 50],
            "pageLength": 10
        });
</script>

<script>
    function deleteChartAccount(id)
      	{
        	$('#chart_account_id').val(id);
        }
</script>
@endsection
