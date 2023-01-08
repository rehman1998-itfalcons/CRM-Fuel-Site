@extends('layouts.template')
@section('title','Users')
@section('css')

    <link rel="stylesheet" type= "text/css" href="{{ URL::asset('plugins/table/table/datatable/datatables.css') }}">
    <link rel="stylesheet" type= "text/css" href="{{ URL::asset('plugins/table/table/datatable/dt-global_style.css') }}">
    <style>
        .badge {
            cursor: pointer;
        }
		.table > thead > tr > th {
    		font-weight: 700 !important;
        }
        .badge-online {background-color: #4caf50 !important; font-size: 10px !important;}
        .badge-offline {background-color: #b1b1b1 !important; font-size: 10px !important;}
    </style>

@endsection
@section('contents')

    <div class="row layout-top-spacing" id="cancel-row">
        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
            @if($errors->any())
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
                                Users
                                <a href="{{ route('trash.box') }}" style="float: right;" type="button" class="btn btn-md btn-warning">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                  Trash <span class="badge badge-dark">{{ \DB::table('users')->where('deleted_status',1)->count() }}</span>
                                </a>
                                <a href="{{ route('users.create') }}" style="float: right;" type="button" class="btn btn-md btn-primary">
                                  Add User
                                </a>
                            </h4>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="table-responsive mb-4 mt-4">
                    <div id="zero-config_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                        <table id="zero-config" class="table table-hover dataTable" style="width:100%" role="grid"
                               aria-describedby="zero-config_info">
                            <thead>
                            <tr role="row">
                                <th class="sorting">#</th>
                                <th class="sorting">Name</th>
                                <th class="sorting">Role</th>
                                <th class="sorting">Username</th>
                                <th class="sorting">Agreement</th>
                                <th class="sorting">E-mail</th>
                                <th class="sorting">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 1; ?>
                            @foreach ($users as $user)
                                <tr role="row">
                                    <td>{{ $i++ }}</td>
                                    <td>
                                        {{ $user->name }}
                                        @if (Cache::has('user-is-online-' . $user->id))
                                            <span class="badge badge-success badge-online">Online</span>
                                        @else
                                            <span class="badge badge-secondary badge-offline">Offline</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->role->name }}</td>
                                    <td>{{ $user->username }}</td>
                                  	<td>
                                  		@if ($user->role->name == 'Driver')
                                      		@if($user->agreement_time)
                                      			<span class="badge badge-success">Agreed</span>
                                      		@else
                                      			<span class="badge badge-danger">Not Confirmed</span>
                                      		@endif
                                      	@else
                                      		-
                                      	@endif
                                  	</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                      <a href="{{ route('users.edit',$user->username) }}" class="btn btn-sm btn-primary btn-custom" data-toggle="tooltip" data-placement="top" data-original-title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="25"
                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                 stroke-linecap="round" stroke-linejoin="round"
                                                 class="feather feather-settings text-white">
                                                <circle cx="12" cy="12" r="3"></circle>
                                                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                                            </svg>
                                        </a>
                                        <a href="{{ route('users.show',$user->username) }}" class="btn btn-sm btn-primary btn-custom" data-toggle="tooltip" data-placement="top" data-original-title="View">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                        </a>
                                        <a href="{{ route('logs.show',$user->username) }}" class="btn btn-sm btn-primary btn-custom" data-toggle="tooltip" data-placement="top" data-original-title="View Logs">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-in"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><polyline points="10 17 15 12 10 7"></polyline><line x1="15" y1="12" x2="3" y2="12"></line></svg> Logs
                                        </a>
                                      @if ($user->role->slug != 'super-admin')
                                        @if ($user->account_status == 1)
                                          <button data-toggle="modal" data-target="#banModal" class="btn btn-sm btn-danger btn-custom" onclick="banUser('{{ $user->id }}','0','ban','{{ $user->name }}')" data-toggle="tooltip" data-placement="top" data-original-title="Ban">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-slash"><circle cx="12" cy="12" r="10"></circle><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line></svg>
                                            Ban
                                          </button>
                                        @else
                                          <button data-toggle="modal" data-target="#banModal" class="btn btn-sm btn-success btn-custom" onclick="banUser('{{ $user->id }}','1','unban','{{ $user->name }}')" data-toggle="tooltip" data-placement="top" data-original-title="Unban"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-rotate-ccw"><polyline points="1 4 1 10 7 10"></polyline><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path></svg>
                                            UnBan
                                          </button>
                                        @endif
                                        @if ($user->deleted_status == 0)
                                          <button data-toggle="modal" data-target="#trashModal" class="btn btn-sm btn-danger btn-custom" onclick="trashUser('{{ $user->id }}','1','trash','{{ $user->name }}')" data-toggle="tooltip" data-placement="top" data-original-title="Trash">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg> Trash
                                          </button>
                                        @else
                                          <button data-toggle="modal" data-target="#trashModal" class="btn btn-sm btn-success btn-custom" onclick="trashUser('{{ $user->id }}','0','untrash','{{ $user->name }}')" data-toggle="tooltip" data-placement="top" data-original-title="Un Trash">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg> Un Trash
                                          </button>
                                        @endif
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

    <div class="modal fade" id="banModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ban User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
                </div>
                <form action="{{ url('/ban-user') }}" method="POST" onsubmit="return loginLoadingBtn(this)">
                  @csrf
                  <input type="hidden" name="id" id="user_id" value="">
                  <input type="hidden" name="type" id="type" value="">
                  <input type="hidden" name="status" id="ban_status" value="">
                  <div class="modal-body">
                    <div class="form-group">
                      <p>Are you sure to <span id="current-status"></span> <strong id="current-user"></strong>?</p>
                    </div>
                  </div>
                  <div class="modal-footer" id="loading-btn">
                      <button type="submit" class="btn btn-primary">Confirm</button>
                  </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="trashModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Trash User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
                </div>
                <form action="{{ url('/trash-user') }}" method="POST" onsubmit="return loginLoadingBtn1(this)">
                  @csrf
                  <input type="hidden" name="id" id="trash_id" value="">
                  <input type="hidden" name="type" id="trash_type" value="">
                  <input type="hidden" name="status" id="trash_status" value="">
                  <div class="modal-body">
                    <div class="form-group">
                      <p>Are you sure to <span id="current-trash-status"></span> <strong id="current-trash-user"></strong>?</p>
                    </div>
                  </div>
                  <div class="modal-footer" id="loading-btn1">
                      <button type="submit" class="btn btn-primary">Confirm</button>
                  </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@section('scripts')

	<script>
      	function banUser(id,status,type,name)
      	{
        	$('#user_id').val(id);
        	$('#type').val(type);
        	$('#ban_status').val(status);
          if (type == 'ban')
            $('#current-status').html('Ban');
          else
            $('#current-status').html('Unban');
          $('#current-user').html(name);
        }

        function loginLoadingBtn() {
            document.getElementById('loading-btn').innerHTML = '<button class="btn btn-primary disabled" style="width: auto !important;">Please wait <svg xmlns="http://www.w3.org/2000/svg" width="24" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-loader spin ml-2"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg></button>';
            return true;
        }

	</script>
	<script>

      	function trashUser(id,status,type,name)
      	{
        	$('#trash_id').val(id);
        	$('#trash_type').val(type);
        	$('#trash_status').val(status);
          if (type == 'trash')
            $('#current-trash-status').html('Trash');
          else
            $('#current-trash-status').html('Un-Trash');
          $('#current-trash-user').html(name);
        }

        function loginLoadingBtn1() {
            document.getElementById('loading-btn1').innerHTML = '<button class="btn btn-primary disabled" style="width: auto !important;">Please wait <svg xmlns="http://www.w3.org/2000/svg" width="24" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-loader spin ml-2"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg></button>';
            return true;
        }

	</script>
    <script src="{{ URL::asset('plugins/table/datatable/datatables.js') }}"></script>
    <script>
        $('#zero-config').DataTable({
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

        $('[data-toggle="tooltip"]').tooltip()
    </script>

@endsection
