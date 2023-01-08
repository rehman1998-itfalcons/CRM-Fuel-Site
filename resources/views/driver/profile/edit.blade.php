@extends('layouts.driver')
@section('title','update-driver-profile')
@section('css')
    <link rel="stylesheet" type= "text/css" href="{{ URL::asset('pluginsdropify/dropify.min.css') }}">
    <link href="{{ asset('assets/css/users/account-setting.css') }}" rel="stylesheet" type="text/css" />

    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">
    <link href="{{ asset('bootstrap/css/bootstrap.min.css" rel="stylesheet') }}" type="text/css" />
    <link href="{{ asset('assets/css/plugins.css" rel="stylesheet') }}" type="text/css" />
@endsection
@section('contents')
    <div id="content" class="main-content">
        <div class="layout-px-spacing">

            <div class="account-settings-container layout-top-spacing">

                <div class="account-content">
                    <div class="scrollspy-example" data-spy="scroll" data-bs-target="#account-settings-scroll" data-offset="-100">
                        <div class="row">

                            <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                                    <form action="{{url('/driver-profile-update',Auth::id())}}" id="general-info" class="section general-info" method="post"  enctype="multipart/form-data">
                                    {{ csrf_field() }}

                                    <div class="info">
                                        <h6 class="">Profile Information</h6>

                                        @if($errors->any())
                                          <ul class="alert alert-warning"
                                              style="background: #eb5a46; color: #fff; font-weight: 300; line-height: 1.7; font-size: 16px; list-style-type: circle;">
                                              {!! implode('', $errors->all('<li>:message</li>')) !!}
                                          </ul>
                                        @endif
                                        <div class="row">
                                            <div class="col-lg-11 mx-auto">
                                                <div class="row">
                                                    <div class="col-xl-2 col-lg-12 col-md-4">
                                                        <div class="upload mt-4 pr-md-4">
                                                            <div class="dropify-wrapper has-preview">
                                                           @if(Auth::user()->photo)
                                                              <img src="{{ asset('uploads/profile/'.Auth::user()->photo) }}" alt="avatar" style="height: 106px;">
                                                           @else
                                                         <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcTk-fGccntmLVGHzIR-QdV-9DdlgUQx2fGyIA&usqp=CAU" style="height: 106px;">
                                                           @endif
                                                          </div>

                                                            <p class="mt-2"><i class="flaticon-cloud-upload mr-1"></i>Uploaded Picture</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-10 col-lg-12 col-md-8 mt-md-0 mt-4">
                                                        <div class="form">
                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    <div class="form-group">
                                                                        <label for="fullName">Name</label>
                                                                        <input type="text" class="form-control mb-4" name="name" id="name" placeholder="Full Name" value="{{ Auth::user()->name }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <label class="dob-input">email</label>
                                                                    <input type="email" class="form-control mb-4" name="email" id="email" placeholder="email" value="{{ Auth::user()->email }}">
                                                                </div>
                                                            </div>
                                                          <div class="row">
                                                             <div class="col-sm-6">
                                                                    <label class="dob-input">Username</label>
                                                                    <input type="text" class="form-control mb-4" name="username" id="username" placeholder="Username" value="{{ Auth::user()->username }}">
                                                                </div>
                                                             <div class="col-sm-6">
                                                                    <label class="dob-input">Profile Picture</label>
                                                                    <input type="file" class="form-control mb-4" name="profile_picture" id="profile_picture">
                                                                </div>
                                                          </div>
                                                            <div class="form-group">
                                                                <input type="submit" value="Update" class="btn btn-primary">                                                 </div>
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
