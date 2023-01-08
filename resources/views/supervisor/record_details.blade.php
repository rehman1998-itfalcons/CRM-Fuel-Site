@extends('layouts.layout.main')
@section('title', 'Record Details')
@section('css')


    <!--<link href="{{ asset('assets/css/tables/table-basic.css') }}" rel="stylesheet" type="text/css" />-->
    <link rel="stylesheet" href="{{ url('assets/js/jquery.magnify.css') }}" />

    <style>
        .widget-content-area {
            -webkit-box-shadow: 0 4px 6px 0 rgba(85, 85, 85, 0.0901961), 0 1px 20px 0 rgba(0, 0, 0, 0.08), 0px 1px 11px 0px rgba(0, 0, 0, 0.06);
            -moz-box-shadow: 0 4px 6px 0 rgba(85, 85, 85, 0.0901961), 0 1px 20px 0 rgba(0, 0, 0, 0.08), 0px 1px 11px 0px rgba(0, 0, 0, 0.06);
            box-shadow: 0 4px 6px 0 rgba(85, 85, 85, 0.0901961), 0 1px 20px 0 rgba(0, 0, 0, 0.08), 0px 1px 11px 0px rgba(0, 0, 0, 0.06);
        }

        .table>thead>tr>th {
            color: #000 !important;
            width: 25% !important;
        }

        .btn-group .btn {
            margin-right: 3px;
        }
    </style>

@endsection
@section('contents')

    <div class="row justify-content-center">
        <div class="white_box mb_20">

        <div class="col-sm-12 ">
            <div class="QA_section">
                <div class="white_box_tittle list_header">
                    <h4>Application Details</h4>
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
                        <div class="add_button ms-2 btn-group">
                            <button type="button" class="btn btn-md btn-danger" data-bs-toggle="modal" data-bs-target="#delete"
                                style="float: right;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-trash-2">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path
                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                    </path>
                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                </svg>
                                Delete
                            </button>
                            <a href="{{ route('supervisor.record.fuel.delivery.update', $record->id) }}"
                                class="btn btn-md btn-primary" style="float: right;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-edit-2">
                                    <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                </svg>
                                Update
                            </a>
                            <a href="{{ route('supervisor.dashboard') }}" class="btn btn-md btn-primary" style="float: right;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-arrow-left close-message">
                                    <line x1="19" y1="12" x2="5" y2="12"></line>
                                    <polyline points="12 19 5 12 12 5"></polyline>
                                </svg> Back</a>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        @if ($record->cancel_reason != '')
                            <p class="alert alert-warning"><strong>Cancellation Reason:</strong>
                                {{ $record->cancel_reason }}</p>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-bordered mb-4">
                                <thead>
                                    <tr>
                                        <th>Driver Name</th>
                                        <td>{{ $record->user->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>BILL OF LADING (BOL)</th>
                                        <td>
                                            @php
                                                $i = 1;
                                            @endphp
                                            @if ($record->bill_of_lading)
                                                @php
                                                    $files = explode('::', $record->bill_of_lading);
                                                @endphp
                                                @foreach ($files as $file)
                                                    @if ($i != 1)
                                                        <strong>::</strong>
                                                    @endif
                                                    <a data-magnify="gallery" data-caption="Image"
                                                        href="{{ asset('/uploads/records/' . $file) }}"
                                                        class="btn btn-outline-dark mb-2 mr-2" style="padding: 5px 8px;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" class="feather feather-eye">
                                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                            <circle cx="12" cy="12" r="3"></circle>
                                                        </svg></a>
                                                    <a href="{{ asset('/uploads/records/' . $file) }}"
                                                        class="btn btn-outline-dark mb-2 mr-2"
                                                        download="{{ $file }}" style="padding: 5px 8px;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" class="feather feather-download">
                                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                            <polyline points="7 10 12 15 17 10"></polyline>
                                                            <line x1="12" y1="15" x2="12"
                                                                y2="3"></line>
                                                        </svg>
                                                    </a>


                                                    @php
                                                        $i++;
                                                    @endphp
                                                @endforeach
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>DELIVERY DOCKET</th>
                                        <td>
                                            @php
                                                $i = 1;
                                            @endphp
                                            @if ($record->delivery_docket)
                                                @php
                                                    $files = explode('::', $record->delivery_docket);
                                                @endphp
                                                @foreach ($files as $file)
                                                    @if ($i != 1)
                                                        <strong>::</strong>
                                                    @endif
                                                    <a data-magnify="gallery" data-caption="Image"
                                                        href="{{ asset('/uploads/records/' . $file) }}"
                                                        class="btn btn-outline-dark mb-2 mr-2" style="padding: 5px 8px;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" class="feather feather-eye">
                                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                            <circle cx="12" cy="12" r="3">
                                                            </circle>
                                                        </svg></a>
                                                    <a href="{{ asset('/uploads/records/' . $file) }}"
                                                        class="btn btn-outline-dark mb-2 mr-2"
                                                        download="{{ $file }}" style="padding: 5px 8px;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" class="feather feather-download">
                                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                            <polyline points="7 10 12 15 17 10"></polyline>
                                                            <line x1="12" y1="15" x2="12"
                                                                y2="3"></line>
                                                        </svg>
                                                    </a>



                                                    @php
                                                        $i++;
                                                    @endphp
                                                @endforeach
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Date Time</th>
                                        <td>{{ \Carbon\Carbon::parse($record->datetime)->format('d-m-Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Trip NUMBER</th>
                                        <td>{{ $record->load_number }}</td>
                                    </tr>
                                    <tr>
                                        <th>ORDER NUMBER</th>
                                        <td>{{ $record->order_number }}</td>
                                    </tr>
                                    <tr>
                                        <th>Fuel Company</th>
                                        <td>{{ $record->supplierCompany->name }}</td>
                                    </tr>
                                    @php
                                        $total = 0;
                                    @endphp
                                    @foreach ($record->products as $product)
                                        <tr>
                                            <th>{{ $product->product->name }}</th>
                                            <td>{{ $product->qty }}</td>
                                        </tr>
                                        @php
                                            $total = $total + $product->qty;
                                        @endphp
                                    @endforeach
                                    <tr>
                                        <th>Total Calculation</th>
                                        <td>{{ $total }}</td>
                                    </tr>
                                    <tr>
                                        <th style="border: 1px solid #ebedf2;">Split Load /Full Load</th>
                                        <td>{{ $record->splitfullload }}</td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row text-center">
                    <div class="col-md-12">
                        <h5>Place the Record inside of</h5>
                        <hr>
                        @php
                            $categories = \App\Category::where('status', 1)->get();
                        @endphp
                        @foreach ($categories as $category)
                            <form method="post" action="{{ route('supervisor.record.fuel.delivery', $category->id) }}"
                                style="display:inline;">
                                @csrf
                                <input type="hidden" name="record_id" value="{{ $record->id }}">
                                <button type="submit"
                                    class="btn btn-dark btn-rounded mb-2">{{ $category->name }}</button>
                            </form>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Application</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        x
                    </button>
                </div>
                <div class="modal-body">
                    <p class="modal-text">Are you sure to delete application?</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" data-dismiss="modal"><i class="flaticon-cancel-12"></i>
                        Discard</button>
                    <a href="{{ route('supervisor.record.delete', $record->id) }}" class="btn btn-success">Confirm
                        Delete</a>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script src="{{ asset('jquery-3.4.1.min.js') }}"></script>
    <script src="{{ url('assets/js/jquery.magnify.js') }}"></script>
    <script>
        $('[data-magnify]').magnify({
            headToolbar: [
                'close'
            ],
            footToolbar: [
                'zoomIn',
                'zoomOut',
                'prev',
                'fullscreen',
                'next',
                'actualSize',
                'rotateRight'
            ],
            title: false
        });
    </script>
@endsection
