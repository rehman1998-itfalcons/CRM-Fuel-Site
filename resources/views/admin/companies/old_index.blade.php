@extends('layouts.template')
@section('title', 'Companies')
@section('css')

    <link rel="stylesheet" type= "text/css" href="{{ URL::asset('plugins/table/table/datatable/dt-global_style.css') }}">
    <style>
        .badge {
            cursor: pointer;
        }

        .table>thead>tr>th {
            font-weight: 700 !important;
        }
    </style>

@endsection
@section('contents')

    <div class="row layout-top-spacing" id="cancel-row">
        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
            @if ($errors->any())
                <ul class="alert alert-warning"
                    style="background: #eb5a46; color: #fff; font-weight: 300; line-height: 1.7; font-size: 16px; list-style-type: circle;">
                    {!! implode('', $errors->all('<li>:message</li>')) !!}
                </ul>
            @endif
            <div class="widget-content widget-content-area br-6">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>
                                Companies
                                <button type="button" class="btn btn-md btn-primary" data-toggle="modal"
                                    data-target="#addMainCompany" style="float: right;">Add Main Company</button>
                                <button type="button" class="btn btn-md btn-primary" data-toggle="modal"
                                    data-target="#addSubCompany" style="float: right;">Add Sub Company</button>
                            </h4>
                        </div>
                    </div>
                </div>
                <br>
                <div class="table-responsive mb-4 mt-4">
                    <div id="zero-config_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                        <table class="table dataTable" style="width:100%" role="grid"
                            aria-describedby="zero-config_info">
                            <thead>
                                <tr role="row">
                                    <th>#</th>
                                    <th>Company</th>
                                    <th>Change Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                @foreach ($companies as $company)
                                    <tr role="row">
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $company->name }}</td>
                                        <td>
                                            @if ($company->status == 0)
                                                <span class="badge badge-success" data-toggle="modal"
                                                    data-target="#changeMainStatus"
                                                    onclick="changeStatus('{{ $company->id }}','1')">Active</span>
                                            @else
                                                <span class="badge badge-danger" data-toggle="modal"
                                                    data-target="#changeMainStatus"
                                                    onclick="changeStatus('{{ $company->id }}','0')">Deactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('companies.edit', $company->id) }}" style="cursor: pointer;"
                                                class="btn btn-sm btn-primary btn-custom">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="25"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-settings">
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                    <path
                                                        d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z">
                                                    </path>
                                                </svg>
                                            </a>
                                            <a data-toggle="collapse" href="#collapseExample{{ $company->id }}"
                                                aria-expanded="false" aria-controls="collapseExample{{ $company->id }}"
                                                class="btn btn-sm btn-primary btn-custom">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="25"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-chevron-down">
                                                    <polyline points="6 9 12 15 18 9"></polyline>
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="3" style="padding: 0px;">
                                            <div class="collapse" id="collapseExample{{ $company->id }}">
                                                <div class="card card-body">
                                                    <table class="table table-striped table-hover">
                                                        <thead>
                                                            <tr style="background: azure;">
                                                                <th scope="col"># </th>
                                                                <th scope="col">SubCompany </th>
                                                                <th scope="col"> Change Status </th>
                                                                <th> Action </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php $j = 1; ?>
                                                            @foreach ($company->sub_companies as $subcompany)
                                                                <tr>
                                                                    <td>{{ $j++ }}</td>
                                                                    <td>{{ $subcompany->name }}</td>
                                                                    <td>
                                                                        @if ($subcompany->status == 0)
                                                                            <span class="badge badge-success"
                                                                                data-toggle="modal"
                                                                                data-target="#changeSubStatus"
                                                                                onclick="changeSubStatus('{{ $subcompany->id }}','1')">Active</span>
                                                                        @else
                                                                            <span class="badge badge-danger"
                                                                                data-toggle="modal"
                                                                                data-target="#changeSubStatus"
                                                                                onclick="changeSubStatus('{{ $subcompany->id }}','0')">Deactive</span>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        <a href="{{ route('subcompanies.edit', $subcompany->id) }}"
                                                                            style="cursor: pointer;"
                                                                            class="btn btn-sm btn-primary btn-custom">
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                width="30" height="25"
                                                                                viewBox="0 0 24 24" fill="none"
                                                                                stroke="currentColor" stroke-width="2"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                class="feather feather-settings">
                                                                                <circle cx="12" cy="12"
                                                                                    r="3"></circle>
                                                                                <path
                                                                                    d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z">
                                                                                </path>
                                                                            </svg>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="sorting">#</th>
                                    <th class="sorting">Category</th>
                                    <th class="sorting">Change Status</th>
                                    <th class="sorting">Action</th>
                                </tr>
                            </tfoot>
                        </table>
                        {{ $companies->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="addMainCompany" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Main Company</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
                </div>
                <form action="{{ route('companies.store') }}" method="POST" onsubmit="return loginLoadingBtn(this)">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Company</label>
                            <small style="color: red;"> *</small>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer" id="loading-btn">
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="changeMainStatus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Change Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
                </div>
                <form action="{{ route('companies.change.status') }}" method="POST"
                    onsubmit="return loginLoadingBtn3(this)">
                    @csrf
                    <input type="hidden" name="id" id="category_id" value="">
                    <input type="hidden" name="status" id="status" value="">
                    <div class="modal-body">
                        <div class="form-group">
                            <p>Are you sure to change status to <strong id="status-current"></strong></p>
                        </div>
                    </div>
                    <div class="modal-footer" id="loading-btn3">
                        <button type="submit" class="btn btn-primary">Change Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="changeSubStatus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Change Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
                </div>
                <form action="{{ route('subcompanies.change.status') }}" method="POST"
                    onsubmit="return loginLoadingBtn4(this)">
                    @csrf
                    <input type="hidden" name="id" id="company_id" value="">
                    <input type="hidden" name="status" id="sub_status" value="">
                    <div class="modal-body">
                        <div class="form-group">
                            <p>Are you sure to change status to <strong id="sub-status-current"></strong></p>
                        </div>
                    </div>
                    <div class="modal-footer" id="loading-btn4">
                        <button type="submit" class="btn btn-primary">Change Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <div class="modal fade" id="addSubCompany" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Sub Company</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
                </div>
                <form action="{{ route('subcompanies.store') }}" method="POST"
                    onsubmit="return loginLoadingBtn1(this)">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Select Category</label>
                            <small style="color: red;"> *</small>
                            <select name="category_id" class="form-control" required>
                                <option value="">--Select--</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Select Company</label>
                            <small style="color: red;"> *</small>
                            <select name="company_id" class="form-control" required>
                                <option value="">--Select--</option>
                                @foreach ($companies_list as $list)
                                    <option value="{{ $list->id }}">{{ $list->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Sub Company</label>
                            <small style="color: red;"> *</small>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer" id="loading-btn1">
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@section('scripts')

    <script>
        function loginLoadingBtn() {
            document.getElementById('loading-btn').innerHTML =
                '<button class="btn btn-primary disabled" style="width: auto !important;">Creating <svg xmlns="http://www.w3.org/2000/svg" width="24" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-loader spin ml-2"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg></button>';
            return true;
        }

        function loginLoadingBtn3() {
            document.getElementById('loading-btn3').innerHTML =
                '<button class="btn btn-primary disabled" style="width: auto !important;">Changing Status <svg xmlns="http://www.w3.org/2000/svg" width="24" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-loader spin ml-2"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg></button>';
            return true;
        }

        function loginLoadingBtn4() {
            document.getElementById('loading-btn4').innerHTML =
                '<button class="btn btn-primary disabled" style="width: auto !important;">Changing Status <svg xmlns="http://www.w3.org/2000/svg" width="24" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-loader spin ml-2"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg></button>';
            return true;
        }

        function loginLoadingBtn1() {
            document.getElementById('loading-btn1').innerHTML =
                '<button class="btn btn-primary disabled" style="width: auto !important;">Creating <svg xmlns="http://www.w3.org/2000/svg" width="24" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-loader spin ml-2"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg></button>';
            return true;
        }

        function changeStatus(id, status) {
            $('#category_id').val(id);
            $('#status').val(status);
            if (status == 1)
                $('#status-current').html('Activate?');
            else
                $('#status-current').html('Deactivate?');
        }

        function changeSubStatus(id, status) {
            $('#company_id').val(id);
            $('#sub_status').val(status);
            if (status == 1)
                $('#sub-status-current').html('Activate?');
            else
                $('#sub-status-current').html('Deactivate?');
        }

        function editCategory(id, name) {
            $('#categoryID').val(id);
            $('#categoryName').val(name);
        }

        $('[data-toggle="tooltip"]').tooltip();
    </script>

@endsection
