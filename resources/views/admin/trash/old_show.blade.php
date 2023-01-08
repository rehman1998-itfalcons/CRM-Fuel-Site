@extends('layouts.template')
@section('title','Sale Details')
@section('css')

	<link rel="stylesheet" href="{{url('resources/views/admin/records/assets/rotate/jquery.magnify.css')}}" />
    <style>

        .dataTables_length {
            display: inline-block;
        }

        .dt-buttons {
            display: inline-block;
            float: right;
        }

        .table-responsive {
            cursor: grab !important
            overflow: scroll !important;
            overflow-y: hidden !important;
            display: block !important;
            width: 100% !important;
            overflow-x: auto !important;
        }

        .paging_simple_numbers {
            float: right;
        }

        .list-group-item {
            padding: 6px;
        }

        .btn-cust {
            padding: 5px 10px !important;
            font-size: 12px !important;
            box-shadow: 0px 5px 20px 0 rgba(0, 0, 0, 0.2) !important;
        }

    </style>

@endsection
@section('contents')

    <div class="row layout-top-spacing" id="cancel-row">
        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
            <div class="widget-content widget-content-area br-6" >

                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Sale Details
                              <button type="button" data-toggle="modal" data-target="#delete" style="float: right;" type="button" class="btn btn-md btn-danger">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                   Permanent Delete
                              </button>
                              <button type="button" data-toggle="modal" data-target="#restore" style="float: right;" type="button" class="btn btn-md btn-warning">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-rotate-ccw"><polyline points="1 4 1 10 7 10"></polyline><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path></svg>
                                   Restore
                              </button>                              
                                <a href="{{ route('trash') }}" class="btn btn-md btn-primary" style="float: right;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left close-message"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                                    Back
                                </a>
                            </h4>
                        </div>
                    </div>
                    <hr>
                </div>
                <table class="table table-bordered">
                    <tbody><tr>
                        <td width="15%"> <b>Categoy</b></td>
                        <td>
                            <p class="card-text">{{ ($record->category_id) ? $record->category->name : '-' }}</p>
                        </td>
                        <td width="15%"> <b>Sub Company</b></td>
                        <td>
                            <p class="card-text">{{ ($record->sub_company_id) ? $record->subCompany->name : '-' }}</p>
                        </td>
                        <td width="15%"> <b>Fuel Company</b></td>
                        <td>
                            <p class="card-text">{{ ($record->supplier_company_id) ? $record->supplierCompany->name : '-' }}</p>
                        </td>

                    </tr>
                      <tr>

                        <td width="15%"> <b> Tax Type </b></td>
                        <td>
                            <p class="card-text">
                                <span class="badge badge-info">{{ $record->gst_status }}</span>
                            </p>
                        </td>
                        <td width="15%"> <b> Trip Number </b></td>
                        <td>
                            <p class="card-text">{{ $record->load_number}}
                            </p>
                        </td>
                        <td width="15%"> <b> Order Number </b></td>
                        <td>
                            <p class="card-text">{{ $record->order_number }}
                            </p>
                        </td>


                    </tr>
                    <tr>
                        <td width="15%"> <b> Split Load</b></td>
                        <td>
                            <p class="card-text">{{ $record->splitfullload }}</p>
                        </td>

                        <td width="15%"> <b> Split Load Status </b></td>
                        <td>
                            <p class="card-text">{{ $record->load_number}}
                            </p>
                        </td>
                        <td width="15%"> <b> Split Load Charges </b></td>
                        <td>
                            <p class="card-text">${{ number_format($record->split_load_charges,2) }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td width="15%"> <b> Date-Time </b></td>
                        <td>
                            <p class="card-text">{{ \Carbon\Carbon::parse($record->datetime)->format('d-m-Y H:i') }}</p>
                        </td>
                        <td width="15%"> <b> Email Status</b></td>
                        <td>
                            @if ($record->email_status == 1)
                                <span class="badge badge-success">Sent</span>
                            @else
                                <span class="badge badge-danger">Not Sent</span>
                            @endif
                        </td>
                        <td width="15%"> <b> Paid Status</b></td>
                        <td>
                            @if ($record->paid_status == 1)
                                <span class="badge badge-success">Paid</span>
                            @else
                                <span class="badge badge-danger">Unpaid</span>
                            @endif
                        </td>

                    </tr>
                    <tr>
                        <td width="15%"><b>Invoice no</b></td>
                        <td width="15%"><p class="card-text">{{ $record->invoice_number }}</p></td>
                        <td width="15%"> <b> Total amount : </b></td>
                        <td>
                            <p class="card-text">${{ number_format($record->total_amount,2) }}</p>
                        </td>
                        <td width="15%"> <b> Paid amount : </b></td>
                        <td>
                            <p class="card-text">${{ number_format($record->paid_amount,2) }}</p>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <br>
                <div class="row">
                    <div class="col-md-6">
                        <label>BILL OF LADING (BOL) :</label><br>
                        @php
                            $i = 1;
                        @endphp
                        @if($record->bill_of_lading)
                            @php
                                $files = explode("::",$record->bill_of_lading)
                            @endphp
                            @foreach ($files as $file)
                                @if($i != 1)
                                    <strong>::</strong>
                                @endif
                                <a data-magnify="gallery" data-caption="Image" href="{{ asset('uploads/records/'.$file) }}" class="btn btn-outline-dark mb-2 mr-2" style="padding: 5px 8px;"><svg xmlns="http://www.w3.org/2000/svg" width="24"  height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></a>
                                <a href="{{ asset('/uploads/records/'.$file) }}"
                                   download="{{$file}}" class="btn btn-outline-dark mb-2 mr-2"
                                   style="padding: 5px 8px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                         viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                         stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round" class="feather feather-download">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="7 10 12 15 17 10"></polyline>
                                        <line x1="12" y1="15" x2="12" y2="3"></line>
                                    </svg>
                                </a>
                                @php
                                    $i++;
                                @endphp
                            @endforeach
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label>DELIVERY DOCKET :</label><br>
                        @php
                            $i = 1;
                        @endphp
                        @if($record->delivery_docket)
                            @php
                                $files = explode("::",$record->delivery_docket)
                            @endphp
                            @foreach ($files as $file)
                                @if($i != 1)
                                    <strong>::</strong>
                                @endif
                                <a data-magnify="gallery" data-caption="Image" href="{{ asset('uploads/records/'.$file) }}" class="btn btn-outline-dark mb-2 mr-2" style="padding: 5px 8px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"  height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></a>
                                <a href="{{ asset('/uploads/records/'.$file) }}"
                                   download="{{$file}}" class="btn btn-outline-dark mb-2 mr-2"
                                   style="padding: 5px 8px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                         viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                         stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round" class="feather feather-download">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="7 10 12 15 17 10"></polyline>
                                        <line x1="12" y1="15" x2="12" y2="3"></line>
                                    </svg>

                                </a>
                                @php
                                    $i++;
                                @endphp
                            @endforeach
                        @endif
                    </div>
                </div>
                <br>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Whole Sale</th>
                        <th>Discount</th>
                        <th>Delivery Rate</th>
                        <th>Brand Charges</th>
                        <th>Cost</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($record->products as $product)
                        <tr>
                            <td>{{ $product->product->name }}</td>
                            <td>{{ $product->qty }}</td>
                            <td>${{ $product->whole_sale }}</td>
                            <td>${{ $product->discount }}</td>
                            <td>${{ $product->delivery_rate }}</td>
                            <td>${{ $product->brand_charges  }}</td>
                            <td>${{ $product->cost_of_credit }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div class="modal fade" id="restore" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Restore Application</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      x
                    </button>
                </div>
                <div class="modal-body">
                    <p class="modal-text">Are you sure to restore application?</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Discard</button>
                    <a href="{{ route('trash.restore.delete',$record->id) }}" class="btn btn-success">Confirm Restore</a>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Application</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      x
                    </button>
                </div>
                <div class="modal-body">
                    <p class="modal-text">Deleting application will delete Sale Products, Transaction History, Mass Match and Sale?</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Discard</button>
                    <a href="{{ route('trash.permanent.delete',$record->id) }}" class="btn btn-success">Confirm Delete</a>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script src="{{url('public/jquery-3.4.1.min.js')}}"></script>
   	<script src="{{url('resources/views/admin/records/assets/rotate/jquery.magnify.js')}}"></script>
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