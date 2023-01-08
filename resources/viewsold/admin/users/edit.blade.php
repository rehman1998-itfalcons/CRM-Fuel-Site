@extends('layouts.layout.main')
@section('title','Update User')
@section('css')

	<style>
      .widget-content-area {
          -webkit-box-shadow: 0 4px 6px 0 rgba(85, 85, 85, 0.0901961), 0 1px 20px 0 rgba(0, 0, 0, 0.08), 0px 1px 11px 0px rgba(0, 0, 0, 0.06);
          -moz-box-shadow: 0 4px 6px 0 rgba(85, 85, 85, 0.0901961), 0 1px 20px 0 rgba(0, 0, 0, 0.08), 0px 1px 11px 0px rgba(0, 0, 0, 0.06);
          box-shadow: 0 4px 6px 0 rgba(85, 85, 85, 0.0901961), 0 1px 20px 0 rgba(0, 0, 0, 0.08), 0px 1px 11px 0px rgba(0, 0, 0, 0.06);
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
                    <h4>Update User</h4>
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
                            <a href="{{ route('users.show',$user->username) }}" class="btn btn-md btn-primary" style="float: right;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left close-message"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                                Back
                              </a>
                        </div>
                    </div>
                </div>
                <div class="QA_table mb_30">

                    <hr>
        			<div class="">
						<form action="{{ route('users.update',$user->id) }}" method="POST" onsubmit="return loginLoadingBtn(this)" enctype="multipart/form-data">
							@csrf
                          	@method('PUT')
						    <div class="form-row mb-4">
						        <div class="form-group col-md-6">
						            <label>Role</label>
						            <span style="color: red;"> *</span>
						            <select name="role_id" class="form-control @error('role_id') is-invalid @enderror" required>
						            	<option value="">--Select--</option>
						            	@foreach ($roles as $role)
                                      		<option value='{{ $role->id }}' {{ ($user->role_id == $role->id) ? 'selected' : '' }}>
                                              {{ $role->name }}
                                      		</option>
                                      	@endforeach
						            </select>
	                                @error('role_id')
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $message }}</strong>
	                                    </span>
	                                @enderror
						        </div>
						        <div class="form-group col-md-6">
						            <label>Full Name</label>
						            <span style="color: red;"> *</span>
						            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Full Name" required autocomplete="off" value="{{ $user->name }}">
	                                @error('name')
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $message }}</strong>
	                                    </span>
	                                @enderror
						        </div>
                          	</div>
						    <div class="form-row mb-4">
						        <div class="form-group col-md-6">
						            <label>Username</label>
						            <span style="color: red;" id="username-error"> *</span>
						            <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" id="username" placeholder="Username" required autocomplete="off" value="{{ $user->username }}">
	                                @error('username')
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $message }}</strong>
	                                    </span>
	                                @enderror
						        </div>
						        <div class="form-group col-md-6">
						            <label>E-Mail</label>
						            <span style="color: red;"> *</span>
						            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" placeholder="E-Mail" required autocomplete="off" value="{{ $user->email }}">
	                                @error('email')
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $message }}</strong>
	                                    </span>
	                                @enderror
						        </div>
                          	</div>
						    <div class="form-row mb-4">
						        <div class="form-group col-md-6">
						            <label>Password</label>
						            <span style="color: red;"> *</span>
						            <input type="text" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" required autocomplete="off" value="{{ $user->hint }}">
	                                @error('password')
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $message }}</strong>
	                                    </span>
	                                @enderror
						        </div>
						        <div class="form-group col-md-6">
						            <label>Photo</label>
						            <input type="file" class="form-control" name="photo">
						        </div>
						    </div>
						    <div class="form-row mb-4">
						        <div class="form-group col-md-6">
						            <label>Attachment(s)</label>
                                  	<span style="color: red;"> * Attachment(s) for Drivers</span>
						            <input type="file" class="form-control" name="attachments[]" multiple>
						        </div>
						    </div>
                          	@if($user->attachments)
                                @php
                                    $attachments = explode("::",$user->attachments);
                                @endphp
                                <div class="form-row mb-4">
										@foreach ($attachments as $key => $value)
                                          <div class="col-md-4">
                                                  {{ $value }} <span style="cursor: pointer; color: red; margin-left: 20px;" data-bs-toggle="modal" data-bs-target="#delete" onclick="deleteAttachment('{{ $value }}')">X</span>
                                          </div>
                                      	@endforeach
                                </div>
                          	@endif
						    <div class="form-row mb-4">
						        <div class="form-group col-md-12">
                                  <input type="checkbox" id="confirmation" class="report" value="1" required>
                                  <span id="confirmation-text"> Have you double checked the information?</span>
						        </div>
						    </div>						    <div class="form-row mb-4">
						    	<div class="form-group col-md-12" id="loading-btn">
						    		<button type="submit" class="btn btn-primary btn-lg mr-3">Update User</button>
						    	</div>
						    </div>
						</form>
					</div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>


  <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Change Status</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
              </div>
              <form action="{{ url('/user-attachment-delete') }}" method="POST">
                @csrf
                <input type="hidden" name="attachment" id="attachment" value="">
                <input type="hidden" name="id" value="{{ $user->id }}">
                <div class="modal-body">
                  <div class="form-group">
                    <p>Are you sure to delete attachment?</p>
                  </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Confirm Delete</button>
                </div>
              </form>
          </div>
      </div>
  </div>

@endsection
@section('scripts')

    <script>
        function loginLoadingBtn() {
            document.getElementById('loading-btn').innerHTML = '<button class="btn btn-primary btn-lg mr-3 disabled" style="width: auto !important;">Updating <svg xmlns="http://www.w3.org/2000/svg" width="24" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-loader spin ml-2"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg></button>';
            return true;
        }

      	function deleteAttachment(id)
      	{
        	$('#attachment').val(id);
        }

      	$('#username').blur(function () {
          	var username = $('#username').val();
          	var id = '{{ $user->id }}';
          	if (username != '') {
              $.post('{{ url('/validate-edit-username') }}',{_token:'{{ csrf_token() }}', username:username,id:id}, function(data) {
                  if (data == '1') {
                    $('#username').css('border-color','#E91E63');
                    $('#username-error').html(' * Username taken');
                    $('#loading-btn').html('<button type="button" disabled class="btn btn-primary">Update User</button>');
                  } else {
                    $('#username').css('border-color','#009688');
                    $('#username-error').html(' *');
                    $('#loading-btn').html('<button type="submit" class="btn btn-primary">Update User</button>');
                  }
              });
            }
        });
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
            "lengthMenu": [7, 10, 20, 50],
            "pageLength": 7
        });
    </script>

@endsection
