@extends('layouts.template')
@section('title', 'User Details')
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

    <div class="row layout-top-spacing" id="cancel-row">
        <div class="col-xl-8 col-lg-8 col-sm-8  layout-spacing">
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
                                User Details
                                <div style="float: right;">
                                    <a href="{{ route('users.index') }}" class="btn btn-md btn-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-arrow-left close-message">
                                            <line x1="19" y1="12" x2="5" y2="12"></line>
                                            <polyline points="12 19 5 12 12 5"></polyline>
                                        </svg>
                                        Back
                                    </a>
                                    <a href="{{ route('users.edit', $user->username) }}" class="btn btn-sm btn-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2">
                                            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                        </svg>
                                        Edit
                                    </a>
                                </div>
                            </h4>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row form-group">
                    <div class="col-md-6">
                        <p>Username</p>
                        <h5>{{ $user->username }}</h5>
                    </div>
                    <div class="col-md-6">
                        <p>Role</p>
                        <h5>{{ $user->role->name }}</h5>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-6">
                        <p>E-mail address</p>
                        <h5>{{ $user->email }}</h5>
                    </div>
                    <div class="col-md-6">
                        <p>Account Status</p>
                        <h5>
                            @if ($user->account_status == 1)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-warning">Banned</span>
                            @endif
                        </h5>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-6">
                        <p>Created</p>
                        <h5>{{ $user->created_at->toFormattedDateString() }}</h5>
                    </div>
                    <div class="col-md-6">
                        <p>Last Updated</p>
                        <h5>{{ $user->updated_at->toFormattedDateString() }}</h5>
                    </div>

                </div>
                @if ($user->role->name == 'Driver')
                    <div class="row">
                        <div class="col-md-12">
                            <p>Agreement Time</p>
                            <h5>{{ $user->agreement_time }}</h5>
                        </div>
                    </div>
                    @php
                        $attachments = explode('::', $user->attachments);
                    @endphp
                    <hr>
                    <h6>Attachments</h6>
                    @foreach ($attachments as $key => $value)
                        <a href="{{ asset('uploads/' . $value) }}" target="_blank"
                            class="text-primary">{{ $value }}</a>
                    @endforeach
                @endif
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-sm-4  layout-spacing">
            <div class="widget-content widget-content-area br-6">
                <div class="widget-header">
                    <h4>{{ $user->name }}</h4>
                    <hr>
                    @if ($user->photo)
                        <img src="{{ asset('uploads/profile/' . $user->photo) }}" class="img-fluid">
                    @else
                        <img src="{{ asset('uploads/default.png') }}" class="img-fluid">
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')

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
