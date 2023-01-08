@extends('layouts.layout.main')
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

    #color {
        color: white;
    }

    #color:hover {
        color: #2bbc4a;
    }
</style>

@endsection
@section('contents')

    <div class="container-fluid p-10 mb-50">
        <div class="row justify-content-center mb-50">
            <div class="col-sm-12 mb-50 ">
                <div class="QA_section mb-50">
                    <div class="white_box_tittle list_header mb-50">
                        <h4>Sub Accounts</h4>
                        <div class="box_right d-flex lms_block">
                            {{-- <div class="serach_field_2">
                                <div class="search_inner">
                                    <form Active="#">
                                        <div class="search_field">
                                            <input type="text" placeholder="Search content here...">
                                        </div>
                                        <button type="submit"> <i class="ti-search"></i> </button>
                                    </form>
                                </div>
                            </div> --}}
                            <div class="add_button ms-2">
                                <a href="{{route('create.subaccount')}}" class="btn btn-primary" style="float:right;"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-plus">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>Add New Sub Account</a>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="QA_table mb_30">

                        <table class="table lms_table_active table-hover non-hover" id="html5-extension" style="width:100%">
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
                                            class="btn btn-sm btn-primary btn-cust" id="color">Edit</a>
                                        <a class="btn btn-sm btn-danger btn-cust" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal" onclick="deleteSubAccount('{{ $account->id }}')"
                                            style="display: none;">Delete</a>
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
                "sSearchPlaceholder": "",
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
