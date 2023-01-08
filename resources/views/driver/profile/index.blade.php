@extends('layouts.driver')
@section('title','driver-profile')
@section('css')
    <link href="{{asset('assets/css/users/user-profile.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('contents')
    <style>
        .span{
            color:black;
            font-size: 18px;
        }
        .span_font{
            font-size: 18px;
        }
    </style>





    <div id="content" class="main-content">
        <div class="layout-spacing">

            <div class="row layout-spacing">

                <!-- Content -->
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 layout-top-spacing">
                    <div class="user-profile layout-spacing">
                        <div class="widget-content widget-content-area">
                            <div class="d-flex justify-content-between">
                                <h4>Profile Info</h4>
                                <a href="{{ url('/driver/edit-profile') }}" class="btn btn-md btn-primary">
                                    Edit Profile
                                </a>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mt-4">
                                        @if(Auth::user()->photo)
                                            <img src="{{ asset('uploads/profile/'.Auth::user()->photo) }}" alt="avatar" style="height: 190px;width: 190px;border-radius: 130px;">
                                        @else
                                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcTk-fGccntmLVGHzIR-QdV-9DdlgUQx2fGyIA&usqp=CAU" style="height: 190px;border-radius: 130px;">
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mt-4">
                                        <strong class="span">Name:</strong>
                                        <p class="span_font mb-3">{{ Auth::user()->name }}</p>
                                        <strong class="span">Email:</strong>
                                        <p class="span_font mb-3">{{ Auth::user()->email }}</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mt-4">
                                        <strong class="span">Username:</strong>
                                        <p class="span_font mb-3">{{ Auth::user()->username }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection