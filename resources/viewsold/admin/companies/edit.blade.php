@extends('layouts.layout.main')
@section('title','Update Company')
@section('css')
	<style>
      .btn-danger {
	  	border-radius: 25px !important;
        box-shadow: 0px 5px 20px 0 rgba(0, 0, 0, 0.1);
      }
	</style>
@endsection
@section('contents')


<div class="row justify-content-center">
    <div class="white_box mb_20">
        	<div id="flFormsGrid" class="col-lg-12 layout-spacing">
        		<div class="statbox widget box box-shadow">
        			<div class="widget-header">
        				<div class="row">
        					<div class="col-xl-12 col-md-12 col-sm-12 col-12">
        						<h4>
                                  Update Company
                                	<a href="{{ route('companies.index') }}" class="btn btn-md btn-primary" style="float: right;">
                                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left close-message"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                                      Back
                                    </a>
                              	</h4>
                          </div>
        				</div>

        			</div>
        			<div class="widget-content widget-content-area">
						<form action="{{ route('companies.update',$company->id) }}" method="POST" onsubmit="return loginLoadingBtn(this)">
							@csrf
                          	@method('PUT')
						    <div class="form-row mb-4">
						        <div class="col-md-6">
						            <label>Company</label>
						            <small style="color: red;"> *</small>
						            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Company" required autocomplete="off" value="{{ $company->name }}">
	                                @error('name')
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $message }}</strong>
	                                    </span>
	                                @enderror
						        </div>
                              <div class="col-md-6"></div>
                          	</div>
                          @php
                          	$emails = $company->emails;
                          	$i = 1;
                          @endphp
                          <input type="hidden" id="counter" name="counter" value="{{ $emails->count() }}">
                          <div class="form-row mb-4">
                            <div class="col-md-6">
                              <strong style="color: red;">* Note: Delete an e-mail field by clicking "Delete" button.</strong>
                              <a class="btn btn-sm btn-dark" style="border: 1px solid #999; float: right;color: #fff;" onclick="addEmail()">
                              	Add E-Mail
                              </a>
                            </div>
                            <div class="col-md-6"></div>
                          </div>
                          @foreach ($emails as $email)
                            <div class="form-row mb-4" id="add_email_{{ $i }}">
                              <div class="form-group col-md-6">
                                <input type="email" placeholder="E-Mail address" value="{{ $email->email_address }}" name="email_{{ $i }}" id="email_{{ $i }}" class="form-control" autocomplete="off">
                              </div>
                              <div class="form-group col-md-6">
                                <a style="padding: 13px;" data-bs-toggle="tooltip" data-placement="top" data-original-title="Delete" class="btn btn-sm btn-danger" onclick="deleteEmail('{{ $email->id }}','{{ $i }}')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>
                              </div>
                            </div>
                          	<?php $i++; ?>
                          @endforeach
                          <div id="new-recipient">
                          </div>
                          	<div class="form-row mb-4">
						        <div class="form-group col-md-6">
						            <label>Tax Number</label>
						            <small style="color: red;"> *</small>
						            <input type="text" class="form-control @error('tax_no') is-invalid @enderror" name="tax_no" placeholder="Tax Number" autocomplete="off" value="{{ $company->tax_no }}">
	                                @error('tax_no')
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $message }}</strong>
	                                    </span>
	                                @enderror
						        </div>
						        <div class="form-group col-md-6">
						            <label>Phone No</label>
						            <small style="color: red;"> *</small>
						            <input type="text" class="form-control @error('phone_no') is-invalid @enderror" name="phone_no" placeholder="Phone No" autocomplete="off" value="{{ $company->phone_no }}">
	                                @error('phone_no')
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $message }}</strong>
	                                    </span>
	                                @enderror
						        </div>
						    </div>
						    <div class="form-row mb-4">
                              <div class="form-group col-md-12">
                                <label>Address</label>
                                <small style="color: red;"> *</small>
                                <textarea name="address" rows="4" class="form-control">{{ $company->address }}</textarea>
                              </div>
						    </div>
						    <div class="form-row mb-4">
						    	<div class="form-group col-md-12" id="loading-btn">
						    		<button type="submit" class="btn btn-primary">
						    			Update Company
						    		</button>
						    	</div>
						    </div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

@endsection
@section('scripts')

	<script>

        function loginLoadingBtn()
      	{
          document.getElementById('loading-btn').innerHTML = '<button class="btn btn-primary disabled" style="width: auto !important;">Updating <svg xmlns="http://www.w3.org/2000/svg" width="24" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-loader spin ml-2"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg></button>';
          return true;
       }

        function addEmail()
        {
            var total = $('#counter').val();
            var count = parseInt(total);
            if (count > 0) {
                var new_count = count + 1;
                document.getElementById('new-recipient').insertAdjacentHTML('beforeend', '<div class="form-row mb-4" id="add_email_'+new_count+'"><div class="form-group col-md-6"><input type="email" placeholder="E-Mail address" name="email_' + new_count + '" id="email_' + new_count + '" class="form-control" autocomplete="off"></div><div class="form-group col-md-6"><a data-bs-toggle="tooltip" data-placement="top" data-original-title="Delete" style="padding: 13px;" class="btn btn-sm btn-danger" onclick="removeEmail('+new_count+')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a></div></div>');
                $('#counter').val(new_count);
          } else {
            var new_count = 1;
            document.getElementById('new-recipient').insertAdjacentHTML('beforeend', '<div class="form-row mb-4" id="add_email_'+new_count+'"><div class="form-group col-md-6"><input type="email" placeholder="E-Mail address" name="email_' + new_count + '" id="email_' + new_count + '" class="form-control" autocomplete="off"></div><div class="form-group col-md-6"><a data-bs-toggle="tooltip" data-placement="top" data-original-title="Delete" style="padding: 13px;" class="btn btn-sm btn-danger" onclick="removeEmail('+new_count+')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a></div></div>');
            $('#counter').val(new_count);
         }
      }

      	function removeEmail(id)
      	{
          $('#add_email_'+id).css('display','none');
        }

      	function deleteEmail(id,sno)
      	{
          $.post('{{ url('/delete-company-email') }}',{_token:'{{ csrf_token() }}', id:id}, function(data){
            $('#email_'+sno).val('');
            $('#add_email_'+sno).css('display','none');
          });
        }

	</script>
    <script>
        $('[data-toggle="tooltip"]').tooltip()
    </script>
@endsection
