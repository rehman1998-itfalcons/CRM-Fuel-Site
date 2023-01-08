@extends('layouts.driver')
@section('title','Change Password')
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
                                <form action="{{route('change-password.update',Auth::id())}}" id="general-info" class="section general-info" method="post"  enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    {{ method_field('PUT') }}
                                    <div class="info">
                                        @if (Session::has('error'))
                                            <div class="alert alert-info">Password is not matched!</div>
                                        @endif
                                        @if (Session::has('incorrect_old_password'))
                                            <div class="alert alert-info">your old password is incorrect!</div>
                                        @endif
                                        @if (Session::has('correct'))
                                            <div class="alert alert-info">your password is renewed successfully!</div>
                                        @endif
                                        <h6 class="">Change Password</h6>
                                        <div class="row">
                                            <div class="col-lg-11 mx-auto">
                                                <div class="row">
                                                    <div class="col-xl-10 col-lg-12 col-md-8 mt-md-0 mt-4">
                                                        <div class="form">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <div class="form-group">
                                                                        <label for="fullName">Old Password</label>
                                                                        <input type="password" class="form-control mb-4" name="old_password" id="name" placeholder="Enter Your Old Password" value="" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <div class="form-group">
                                                                        <label for="fullName">New Password</label>
                                                                        <input type="password" class="form-control mb-4" name="new_password" id="name" placeholder="Password" value="" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <label class="dob-input">Re-enter New Password</label>
                                                                    <input type="password" class="form-control mb-4" name="new_confirm_password" id="email" placeholder="Re-enter password" value="" required>
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
@section('scripts')
    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    {{--<script src="{{ asset('assets/js/libs/jquery-3.1.1.min.js') }}"></script>--}}
    {{--<script src="{{ asset('bootstrap/js/popper.min.js') }}"></script>--}}
    {{--<script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>--}}
    {{--<script src="{{ URL::asset('plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>--}}
    {{--<script src="{{ asset('assets/js/app.js') }}"></script>--}}

    {{--<script>--}}
        {{--$(document).ready(function() {--}}
            {{--App.init();--}}
        {{--});--}}
    {{--</script>--}}
    {{--<script src="{{ asset('assets/js/custom.js') }}"></script>--}}
    <!-- END GLOBAL MANDATORY SCRIPTS -->

    <!--  BEGIN CUSTOM SCRIPTS FILE  -->

    {{--    <script src="{{ URL::asset('plugins/dropify/dropify.min.js') }}"></script>--}}
{{--    <script src="{{ URL::asset('plugins/blockui/jquery.blockUI.min.js') }}"></script>--}}
    <!-- <script src="plugins/tagInput/tags-input.js"></script> -->
{{--    <script src="{{ asset('assets/js/users/account-settings.js') }}"></script>--}}
    <!--  END CUSTOM SCRIPTS FILE  -->
@endsection
