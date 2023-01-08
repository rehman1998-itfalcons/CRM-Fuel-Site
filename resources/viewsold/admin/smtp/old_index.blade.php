@extends('layouts.template')
@section('title','SMTP Settings')
@section('contents')

	<link rel="stylesheet" href="{{ asset('multiple-emails.css') }}">
	<link rel="stylesheet" href="{{ asset('glyphicons.css') }}">

	<!-- Contents -->
	<div class="row layout-top-spacing">
		<div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
        		<div class="widget-content widget-content-area" >
                      <div class="widget-header">
                          <div class="row">
                              <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                  <h4>Email Settings</h4>
                              </div>
                          </div>
                      </div>
                  <hr>
        	<div class="">
                        <form action="{{ route('smtp.store') }}" method="POST">
                                @csrf
                                <div class="form-row mb-4">
                                    <div class="form-group col-md-6">
                                        <label>MAIL_MAILER</label>
                                      	<span style="color: red;"> *</span>
                                        <input type="text" name="mailer" placeholder="smtp" class="form-control @error('mailer') is-invalid @enderror" value="{{ ($smtp) ? $smtp->mailer : '' }}" required>
                                        @error('mailer')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>MAIL_HOST</label>
                                      	<span style="color: red;"> *</span>
                                        <input type="text" name="host" placeholder="smtp.gmail.com" class="form-control @error('host') is-invalid @enderror" value="{{ ($smtp) ? $smtp->host : '' }}" required>
                                        @error('host')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>MAIL_PORT</label>
                                      	<span style="color: red;"> *</span>
                                        <input type="text" name="port" placeholder="587" class="form-control @error('port') is-invalid @enderror" value="{{ ($smtp) ? $smtp->port : '' }}" required>
                                        @error('port')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>MAIL_USERNAME</label>
                                      	<span style="color: red;"> *</span>
                                        <input type="email" name="username" placeholder="E-Mail address" class="form-control @error('username') is-invalid @enderror" value="{{ ($smtp) ? $smtp->username : '' }}" required autocomplete="off">
                                        @error('username')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>MAIL_PASSWORD</label>
                                      	<span style="color: red;"> *</span>
                                        <input type="password" name="password" placeholder="E-mail password" value="{{ ($smtp) ? $smtp->password : '' }}" class="form-control @error('password') is-invalid @enderror" required autocomplete="off">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>MAIL_ENCRYPTION</label>
                                      	<span style="color: red;"> *</span>
                                        <input type="text" name="encryption" placeholder="tls" value="{{ ($smtp) ? $smtp->encryption : '' }}" class="form-control @error('encryption') is-invalid @enderror" required>
                                        @error('encryption')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Reply To</label>
                                      	<span style="color: red;"> *</span>
                                        <input type="text" name="reply_to" placeholder="replyto@examply.com" value="{{ ($smtp) ? $smtp->reply_to : '' }}" class="form-control @error('reply_to') is-invalid @enderror">
                                        @error('reply_to')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Primary Email</label>
                                      	<span style="color: red;"> *</span>
                                        <input type="email" name="primary_mail" placeholder="E-Mail address" value="{{ ($smtp) ? $smtp->primary_mail : '' }}" class="form-control @error('primary_mail') is-invalid @enderror" required autocomplete="off">
                                        @error('primary_mail')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Primary Name</label>
                                      	<span style="color: red;"> *</span>
                                        <input type="text" name="primary_name" placeholder="E-Mail address" value="{{ ($smtp) ? $smtp->primary_name : '' }}" class="form-control @error('primary_name') is-invalid @enderror" required autocomplete="off">
                                        @error('primary_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                         @enderror
                                    </div>
                                  	@php
                                  		$array = [];
                                  		if ($smtp) {
                                  			if ($smtp->bcc) {
                                  				$array = explode("::",$smtp->bcc);
                                  			}
                                  		}
                                  	@endphp
                                    <div class="form-group col-md-6">
                                        <label>BCC</label>
                                      	<span style="color: red;"> * Put comma for multiple emails.</span>
                                        <input type="text" name="bcc" placeholder="BCC" id="bcc" data-role="tagsinput" value="{{ json_encode($array) }}" class="form-control @error('bcc') is-invalid @enderror" required>
                                        @error('bcc')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                         @enderror
                                    </div>
                                </div>
                          		<div class="row">
                                	<div class="col-md-12">
                                      	<div class="form-group">
                            				<label>Mail Body</label>
                                          	<span style="color: red;"> *</span>
                            				<textarea name="body" id="mytextarea" class="form-control">{{ ($smtp) ? $smtp->body : '' }}</textarea>
                        				</div>
                                  	</div>  
                          		</div>
                          		<div class="row">
                                  <div class="col-md-12 form-group mt-3">
                                    <button type="submit" class="btn btn-md btn-primary">
                                      Update Settings
                                    </button>
                                  </div>
                          </div>
                        </form>
        		</div>
        	</div>
        </div>
    </div>

@endsection
@section('scripts')

	<script src="{{ asset('multiple-emails.js') }}"></script>
	<script>

      	$(document).ready(function () {
			$('#bcc').multiple_emails();
			$('#bcc').change(function(){
				$('#current_emails').text($('#bcc').val());
            });

        });
      
	</script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.9.2/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#mytextarea',
            height: 500,
        });
    </script>

@endsection