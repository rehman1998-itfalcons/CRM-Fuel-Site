@extends('layouts.template')
@section('title','Update Profile')
@section('css')

    <link rel="stylesheet" type= "text/css" href="{{ URL::asset('pluginsdropify/dropify.min.css') }}">
    <link href="{{ asset('assets/css/users/account-setting.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">
    <link href="{{ asset('bootstrap/css/bootstrap.min.css" rel="stylesheet') }}" type="text/css" />
    <link href="{{ asset('assets/css/plugins.css" rel="stylesheet') }}" type="text/css" />
	<style>
		.general-info .info label {
    		color: #000 !important;
        }
	</style>

@endsection
@section('contents')

    <div id="content" class="main-content">
        <div class="layout-spacing">
            <div class="account-settings-container layout-top-spacing">
                <div class="account-content">
                    <div class="scrollspy-example" data-spy="scroll" data-bs-target="#account-settings-scroll" data-offset="-100">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                              @if($errors->any())
                                <ul class="alert alert-warning" style="background: #eb5a46; color: #fff; font-weight: 300; line-height: 1.7; font-size: 16px; list-style-type: circle;">
                                  {!! implode('', $errors->all('<li>:message</li>')) !!}
                                </ul>
                              @endif
                              	<form action="{{route('profile.update',$user->id)}}" id="general-info" class="section general-info" method="post"  enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    {{ method_field('PUT') }}
                                    <div class="info">
                                        <h4 class="">
                                        	Profile Information
                                          	<a href="{{ url('/profile') }}" class="btn btn-md btn-primary" style="float: right;">Back</a>
                                      	</h4>
                                      	<hr>
                                        <div class="row">
                                            <div class="col-lg-12 mx-auto">
                                                <div class="row">
                                                    <div class="col-xl-2 col-lg-12 col-md-4">
                                                        <div class="upload mt-4 pr-md-4">
                                                            <div class="dropify-wrapper has-preview"> <img src="{{ asset('uploads/profile/'.$user->photo) }}" alt="avatar" style="height: 106px;"></div>
                                                            <p class="mt-2"><i class="flaticon-cloud-upload mr-1"></i>Uploaded Picture</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-10 col-lg-12 col-md-8 mt-md-0 mt-4">
                                                        <div class="form">
                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    <div class="form-group">
                                                                        <label for="fullName">Name</label>
                                                                      		<span style="color: red;"> *</span>
                                                                        <input type="text" class="form-control mb-4" name="name" id="name" placeholder="Full Name" value="{{ $user->name }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <label class="dob-input">E-mail address</label>
                                                                  		<span style="color: red;"> *</span>
                                                                    <input type="email" class="form-control mb-4" name="email" id="email" placeholder="email" value="{{ $user->email }}">
                                                                </div>
                                                            </div>
                                                          <div class="row">
                                                             <div class="col-sm-6">
                                                                    <label class="dob-input">Username</label>
                                                               		<span style="color: red;"> *</span>
                                                                    <input type="text" class="form-control mb-4" name="username" id="username" placeholder="Username" value="{{ $user->username }}">
                                                                </div>
                                                             <div class="col-sm-6">
                                                                    <label class="dob-input">Profile Picture</label>
                                                               		<span style="color: red;"> *</span>
                                                                    <input type="file" class="form-control mb-4" name="profile_picture" id="profile_picture">
                                                                </div>
                                                          </div>
                                                            <div class="form-group">
                                                                <button type="submit" class="btn btn-md btn-primary">
                                                                  Update Profile
                                                              	</button>
                                                            </div>
                                                        </div>
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
