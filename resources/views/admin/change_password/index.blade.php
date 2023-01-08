@extends('layouts.template')
@section('title','Change Password')
@section('css')

    <link rel="stylesheet" type= "text/css" href="{{ URL::asset('pluginsdropify/dropify.min.css') }}">
    <link href="{{ asset('assets/css/users/account-setting.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">
    <link href="{{ asset('bootstrap/css/bootstrap.min.css" rel="stylesheet') }}" type="text/css" />
    <link href="{{ asset('assets/css/plugins.css" rel="stylesheet') }}" type="text/css" />
	<style>
		.general-info .info label {
    		color: #000;
        }
	</style>

@endsection
@section('contents')

    <div id="content" class="main-content">
        <div class="mt-4">
            <div class="account-settings-container">
                <div class="account-content">
                    <div class="scrollspy-example" data-spy="scroll" data-bs-target="#account-settings-scroll" data-offset="-100">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                              	@if (Session::has('error'))
                              		<div class="alert alert-info">Password is not matched!</div>
                              	@endif
                              	@if (Session::has('incorrect_old_password'))
                              		<div class="alert alert-info">your old password is incorrect!</div>
                                @endif
                              	@if (Session::has('correct'))
                                	<div class="alert alert-info">your password is renewed successfully!</div>
                              	@endif
                              	<form action="{{route('change-password.update',Auth::id())}}" id="general-info" class="section general-info" method="post"  enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="info">
                                        <h4>Change Password</h4>
                                      	<hr>
                                        <div class="row">
                                            <div class="col-lg-12">
                                              <div class="">
                                                <div class="row">
                                                  <div class="col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                      <label for="fullName">Old Password</label>
                                                      <span style="color: red;"> *</span>
                                                      <input type="password" class="form-control mb-4" name="old_password" id="name" placeholder="Enter Your Old Password" value="" required>
                                                    </div>
                                                  </div>
                                                  <div class="col-md-6 col-sm-12"></div>
                                                  <div class="col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                      <label for="fullName">New Password</label>
                                                      <span style="color: red;"> *</span>
                                                      <input type="password" class="form-control mb-4" name="new_password" id="name" placeholder="Password" value="" required>
                                                    </div>
                                                  </div>
                                                  <div class="col-md-6 col-sm-6">
                                                    <label class="dob-input">Re-enter New Password</label>
                                                    <span style="color: red;"> *</span>
                                                    <input type="password" class="form-control mb-4" name="new_confirm_password" id="email" placeholder="Re-enter password" value="" required>
                                                  </div>
                                                </div>
                                                <div class="form-group">
                                                  <button type="submit" class="btn btn-md btn-primary">
                                                    Update Password
                                                  </button>
                                                </div>
                                              </div>
                                            </div>
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

@endsection
