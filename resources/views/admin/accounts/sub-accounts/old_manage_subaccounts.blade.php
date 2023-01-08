@extends('layouts.template')
@section('title','Sub Accounts')
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
</style>

@endsection
@section('contents')

<style>
    .btn-cust {
        padding: 2px 6px !important;
        font-size: 12px !important;
        box-shadow: 0px 5px 20px 0 rgba(0, 0, 0, 0.2) !important;
    }

    .table>tbody:before {
        content: "" !important;
    }
</style>
<div class="row layout-top-spacing" id="cancel-row">
    <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">

        @if (session()->has('danger'))
        <div class="alert alert-dismissable alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>
                {!! session()->get('danger') !!}
            </strong>
        </div>
        @endif
        <div class="widget-content widget-content-area br-6">
            <h4>Sub Accounts
                <a href="{{route('create.subaccount')}}" class="btn btn-primary" style="float:right;"><svg
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="feather feather-plus">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>Add New Sub Account</a>
            </h4>
            <div class="table-responsive mb-4 mt-4">
                <table id="html5-extension" class="table table-hover non-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Code</th>
                            <th>Chart Of Account</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $sno = 1; ?>
                        @foreach ($subaccounts as $account)
                        <tr>
                            <td>{{ $sno++ }}</td>
                            <td>{{ $account->title}}</td>
                            <td>{{ $account->description }}</td>
                            <td>{{ $account->code }}</td>
                            <td>{{ $account->chartAccount->title}}</td>
                            <td>
                                <a href="{{ route('edit.subaccount',$account->id) }}"
                                    class="btn btn-sm btn-primary btn-cust">Edit</a>
                                <a class="btn btn-sm btn-danger btn-cust" data-toggle="modal" data-target="#deleteModal"
                                    onclick="deleteSubAccount('{{ $account->id }}')" style="display: none;">Delete</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete Sub Account</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">X</button>
            </div>
            <div class="modal-body">
                <p>Are you sure to delete Sub Account?</p>
            </div>
            <div class="modal-footer">
                <form action="{{ route('delete.subaccount') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="sub_account_id">
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
            "lengthMenu": [10, 20, 30, 50],
            "pageLength": 10
        });

  		function deleteSubAccount(id)
          {
              $('#sub_account_id').val(id);
          }
</script>

@endsection
