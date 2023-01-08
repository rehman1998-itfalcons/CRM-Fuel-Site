@extends('layouts.layout.main')
@section('title', 'Update Fuel Delivery')
@section('css')

    <link href="{{ url('assets/css/tables/table-basic.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('plugins/flatpickr/flatpickr.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('plugins/flatpickr/custom-flatpickr.css') }}" rel="stylesheet" type="text/css">
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

        .btn-success {
            color: #fff !important;
            background-color: #4CAF50 !important;
            border-color: #4CAF50 !important;
        }
    </style>
    <style>
        .close {
            cursor: pointer;
            position: relative;
            top: 50%;
            transform: translate(0%, -50%);
            opacity: 1;
            float: right;
            color: red;
        }

        .file_input {
            padding: 8px;
        }

        .btn-danger {
            border-radius: 25px !important;
            box-shadow: 0px 5px 20px 0 rgba(0, 0, 0, 0.1);
        }
        .color:hover{
            background-color:#32C36C;
        }
    </style>

@endsection
@section('contents')
<div class="container-fluid p-10">
    <div class="row justify-content-center">
        <div class="white_box mb_20">
        <div class="col-sm-12 ">
            <div class="QA_section">
                <div class="white_box_tittle list_header">
                    <h4>Fuel Delivery Approval</h4>
                    <div class="box_right d-flex lms_block">
                        <div class="serach_field_2">
                            <div class="search_inner">
                                {{-- <form Active="#">
                                <div class="search_field">
                                    <input type="text" placeholder="Search content here...">
                                </div>
                                <button type="submit"> <i class="ti-search"></i> </button>
                            </form> --}}
                            </div>
                        </div>
                        <div class="add_button ms-2">
                            <a href="{{ route('supervisor.record.details', $record->id) }}" class="btn btn-md btn-primary"
                                style="float: right;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-arrow-left close-message">
                                    <line x1="19" y1="12" x2="5" y2="12"></line>
                                    <polyline points="12 19 5 12 12 5"></polyline>
                                </svg> Back</a>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        @if ($record->supervisor_status == 1)
                            <form>
                            @else
                                <form action="{{ route('submit.fuel.delivery') }}" method="POST"
                                    onsubmit="return loginLoadingBtn(this)">
                                    @csrf
                        @endif
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Approval Type</label>&nbsp;&nbsp;
                                    <span class="badge badge-block badge-info">Fuel Delivery</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Approval Status</label>&nbsp;&nbsp;
                                    <span class="badge badge-block badge-danger">Not Approved</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Operator Name *</label>
                                    <input type="text" value="{{ $record->user->name }}" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Date Time *</label>
                                    <input type="text"
                                        value="{{ \Carbon\Carbon::parse($record->datetime)->format('d-m-Y H:i') }}"
                                        class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label>BILL OF LADING (BOL)</label><br>
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
                                                class="btn color btn-outline-dark mb-2 mr-2" style="padding: 5px 8px;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-eye">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z">
                                                    </path>
                                                    <circle cx="12" cy="12" r="3">
                                                    </circle>
                                                </svg></a>
                                            <a href="{{ asset('/uploads/records/' . $file) }}"
                                                class="btn color btn-outline-dark mb-2 mr-2" download="{{ $file }}"
                                                style="padding: 5px 8px;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-download">
                                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                    <polyline points="7 10 12 15 17 10"></polyline>
                                                    <line x1="12" y1="15" x2="12" y2="3">
                                                    </line>
                                                </svg>
                                            </a>
                                            @php
                                                $i++;
                                            @endphp
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>DELIVERY DOCKET</label><br>
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
                                                class="btn color btn-outline-dark mb-2 mr-2" style="padding: 5px 8px;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-eye">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z">
                                                    </path>
                                                    <circle cx="12" cy="12" r="3">
                                                    </circle>
                                                </svg></a>
                                            <a href="{{ asset('/uploads/records/' . $file) }}"
                                                class="btn color btn-outline-dark mb-2 mr-2" download="{{ $file }}"
                                                style="padding: 5px 8px;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-download">
                                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                    <polyline points="7 10 12 15 17 10"></polyline>
                                                    <line x1="12" y1="15" x2="12" y2="3">
                                                    </line>
                                                </svg>
                                            </a>
                                            @php
                                                $i++;
                                            @endphp
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Trip Number *</label>
                                    <input type="text" value="{{ $record->load_number }}" class="form-control"
                                        readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Order Number *</label>
                                    <input type="text" value="{{ $record->order_number }}" class="form-control"
                                        readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Fuel Company *</label>
                            <select class="form-control" readonly>
                                <option>{{ $record->supplierCompany->name }}</option>
                            </select>
                        </div>
                        <br>
                        <h5 style="color:black;">Selection of Companies</h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Selected Category *</label>
                                    <select name="category_id" id="category_id" class="form-control" readonly>
                                        @if ((isset($record->category->rate_whole_sale) && $record->category->rate_whole_sale == 1) ||
                                        (isset($record->category->rate_discount) && $record->category->rate_discount == 1) ||
                                        (isset($record->category->rate_delivery_rate) && $record->category->rate_delivery_rate == 1) ||
                                        (isset($record->category->rate_brand_charges) && $record->category->rate_brand_charges == 1) ||
                                         (isset($record->category->rate_cost_of_credit) && $record->category->rate_cost_of_credit == 1))
                                            <option value="{{ $record->category_id }}">
                                                {{ $record->category->name }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Select Company *</label>
                                    @if ($record->supervisor_status == 1)
                                        <select class="form-control" readonly>
                                            <option value="">{{ $record->subCompany->company->name }}
                                            </option>
                                        </select>
                                    @else
                                        @php
                                            $companies = \App\Company::where('status', 1)->get();
                                        @endphp
                                        <select name="company_id" id="company_id" class="form-control" required>
                                            <option value="">--Select--</option>
                                            @foreach ($companies as $company)
                                                <option value="{{ $company->id }}">{{ $company->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <input type="hidden" name="record_id" value="{{ $record->id }}">
                                <div class="form-group">
                                    <label>Select Sub Company *</label>
                                    @if ($record->supervisor_status == 1)
                                        <select class="form-control" readonly>
                                            <option value="{{ $record->subCompany->id }}">
                                                {{ $record->subCompany->name }}</option>
                                        </select>
                                    @else
                                        <select name="sub_company_id" id="sub_company_id" class="form-control" required>
                                            <option value="">--Select--</option>
                                        </select>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <input id="record_id" name="" type="hidden" value="{{ $record->id }}">
                        <div class="table-responsive" id="table_previous">
                            <table class="table table-bordered mb-4">
                                <thead>
                                    <tr>
                                        @php
                                            $total = 0;
                                            $category = $record->category;
                                        @endphp
                                        <th>Products</th>
                                        <th>Quantity</th>
                                        @if (isset($category->rate_whole_sale) && $category->rate_whole_sale != '')
                                            <th>Whole Sale</th>
                                        @endif
                                        @if (isset($category->rate_discount) && $category->rate_discount != '')
                                            <th>Discount</th>
                                        @endif
                                        @if (isset($category->rate_delivery_rate) && $category->rate_delivery_rate != '')
                                            <th>Delivery Rate</th>
                                        @endif
                                        @if (isset($category->rate_brand_charges) && $category->rate_brand_charges != '')
                                            <th>Brand Charges</th>
                                        @endif
                                        @if (isset($category->rate_cost_of_credit) && $category->rate_cost_of_credit != '')
                                            <th>COC/limit</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($record->products as $product)
                                        @if ($product->qty != 0)
                                            <tr>
                                                <td>{{ $product->product->name }}</td>
                                                <td>
                                                    {{ $product->qty }}
                                                </td>
                                                @if (isset($category->rate_whole_sale) && $category->rate_whole_sale != '')
                                                    <td>
                                                        <input type="text" name="whole_sale_price"
                                                            class="form-control"
                                                            {{ $category->whole_sale_display == 2 ? 'disabled' : '' }}>
                                                    </td>
                                                @endif
                                                @if ($category->rate_discount != '')
                                                    <td>
                                                        <input type="text" class="form-control" disabled>
                                                    </td>
                                                @endif
                                                @if ( isset($category->rate_delivery_rate) && $category->rate_delivery_rate != '')
                                                    <td>
                                                        <input type="text" class="form-control" {{ $category->rate_delivery_rate == 1 ? 'disabled' : '' }}>
                                                    </td>
                                                @endif
                                                @if ($category->rate_brand_charges != '')
                                                    <td>
                                                        <input type="text" class="form-control" disabled>
                                                    </td>
                                                @endif
                                                @if ($category->rate_cost_of_credit != '')
                                                    <td>
                                                        <input type="text" class="form-control" disabled>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endif
                                        @php
                                            $total = $total + $product->qty;
                                        @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="table-responsive" id="table_prices" style="display:none;">
                            <table class="table table-bordered mb-4">
                                <thead>
                                    <tr>
                                        @php
                                            $total = 0;
                                            $category = $record->category;
                                        @endphp
                                        <th>Products</th>
                                        <th>Quantity</th>
                                        @if ( isset($category->rate_whole_sale) && $category->rate_whole_sale != '')
                                            <th>Whole Sale</th>
                                        @endif
                                        @if (isset($category->rate_discount) && $category->rate_discount != '')
                                            <th>Discount</th>
                                        @endif
                                        @if (isset($category->rate_delivery_rate) && $category->rate_delivery_rate != '')
                                            <th>Delivery Rate</th>
                                        @endif
                                        @if (isset($category->rate_brand_charges) && $category->rate_brand_charges != '')
                                            <th>Brand Charges</th>
                                        @endif
                                        @if (isset($category->rate_cost_of_credit) && $category->rate_cost_of_credit != '')
                                            <th>COC/limit</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($record->products as $product)
                                        @if ($product->qty != 0)
                                            <tr>
                                                <td>{{ $product->product->name }}</td>
                                                <td>
                                                    {{ $product->qty }}
                                                </td>
                                                @if (isset($category->rate_whole_sale) && $category->rate_whole_sale != '')
                                                    <td>
                                                        <input type="text" name="whole_sale_price"
                                                            class="form-control"
                                                            {{ $category->whole_sale_display == 2 ? 'disabled' : '' }}>
                                                    </td>
                                                @endif
                                                @if (isset($category->rate_discount) && $category->rate_discount != '')
                                                    <td>
                                                        <input type="text" class="form-control" disabled>
                                                    </td>
                                                @endif
                                                @if (isset($category->rate_delivery_rate) && $category->rate_delivery_rate != '')
                                                    <td>
                                                        <input type="text" class="form-control" {{ $category->rate_delivery_rate == 1 ? 'disabled' : '' }}>
                                                    </td>
                                                @endif
                                                @if (isset($category->rate_brand_charges) && $category->rate_brand_charges != '')
                                                    <td>
                                                        <input type="text" class="form-control" disabled>
                                                    </td>
                                                @endif
                                                @if (isset($category->rate_cost_of_credit) && $category->rate_cost_of_credit != '')
                                                    <td>
                                                        <input type="text" class="form-control" disabled>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endif
                                        @php
                                            $total = $total + $product->qty;
                                        @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>



                        <div class="form-group">
                            <label>Total Calculation *</label>
                            <input type="text" value="{{ $total }}" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label>Include Split Load Charges?</label><br>
                            @if ($record->supervisor_status == 1)
                                @if ($record->split_load_status == 1)
                                    <span class="badge badge-block badge-success">Included</span>
                                @else
                                    <span class="badge badge-block badge-danger">Not Included</span>
                                @endif
                            @else
                                <input type="radio" name="split_load_tax" value="1"> Yes <br>
                                <input type="radio" name="split_load_tax" value="0" checked> No
                            @endif
                        </div>
                        <div class="form-group" id="split_desc" style="display: none;">
                            <label>Write Some Description</label>
                            <textarea class="form-control" name="split_load_des" rows="3"></textarea>
                        </div>


                        @if ($record->supervisor_status == 0)
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="confirm" value="1" required> Have you
                                    double checked the information?
                                </label>
                            </div>
                        @endif
                        @if ($record->supervisor_status == 1)
                            <div class="form-group">
                                <a href="" class="btn color btn-outline-dark mb-2 mr-2" target="_blank"
                                    style="padding: 5px 8px;"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="feather feather-download">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="7 10 12 15 17 10"></polyline>
                                        <line x1="12" y1="15" x2="12" y2="3">
                                        </line>
                                    </svg> Invoice</a>
                            </div>
                        @else
                            <div class="form-group" id="loading-btn">
                                <button type="submit" class="btn btn-md btn-primary">Approve
                                    Application</button>
                            </div>
                        @endif

                        </form>
                    </div>
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
                    <h5 class="modal-title" id="exampleModalLabel">Change Status</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"
                        style="transform: translate(0%, 0%);color: blue">x</button>
                </div>
                <form action="{{ url('/delete-bill-of-lading') }}" method="POST">
                    @csrf
                    <input type="hidden" name="attachment" id="attachment" value="">
                    <input type="hidden" name="id" value="{{ $record->id }}">
                    <div class="modal-body">
                        <div class="form-group">
                            <p>Are you sure to delete attachment?</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Confirm Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="deleteDocket" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Change Status</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"
                        style="transform: translate(0%, 0%);color: blue">x</button>
                </div>
                <form action="{{ url('/delete-delivery-docket') }}" method="POST">
                    @csrf
                    <input type="hidden" name="attachment" id="attachment1" value="">
                    <input type="hidden" name="id" value="{{ $record->id }}">
                    <div class="modal-body">
                        <div class="form-group">
                            <p>Are you sure to delete attachment?</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Confirm Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script src="{{ URL::asset('plugins/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ URL::asset('plugins/flatpickr/custom-flatpickr.js') }}"></script>
    <script src="{{ url('assets/js/jquery.multifile.js') }}"></script>
    <script src="{{ url('assets/js/jquery.magnify.js') }}"></script>
   

    <script>
        function deleteAttachment1(id) {
            $('#attachment1').val(id);
        }

        function deleteAttachment(id) {
            $('#attachment').val(id);
        }

        function loginLoadingBtn() {
            document.getElementById('loading-btn').innerHTML =
                '<button class="btn btn-primary disabled" style="width: auto !important;">Please wait <svg xmlns="http://www.w3.org/2000/svg" width="24" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-loader spin ml-2"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg></button>';
            return true;
        }

        $('#category_id').change(function() {
            $.get('{{ route('companies.get') }}', {
                _token: '{{ csrf_token() }}',
                type: 'companies'
            }, function(data) {
                var companies = JSON.parse(data);
                var i = 0;
                var len = companies.length;
                for (i = 0; i < len; i++) {
                    var o = new Option(companies[i].name, companies[i].id);
                    $("#company_id").append(o);
                }
            });
        });

        $('#company_id').change(function() {
            var cat = $("#category_id").val();
            var id = $("#company_id").val();
            if (id != '' && cat != '') {
                $.get('{{ route('sub.companies.get') }}', {
                    _token: '{{ csrf_token() }}',
                    id: id
                }, function(data) {
                    var companies = JSON.parse(data);
                    var i = 0;
                    var len = companies.length;
                    for (i = 0; i < len; i++) {
                        var o = new Option(companies[i].name, companies[i].id);
                        $("#sub_company_id").append(o);
                    }
                });
            }
        });
        
        $('#sub_company_id').change(function() {
        var id = $("#sub_company_id").val();
        var record_id = $("#record_id").val();
        if (id != '') {
          $.post('{{ route('sub.companies.prices') }}',{_token:'{{ csrf_token() }}',id:id,record_id:record_id}, function(data) {
            $("#table_previous").css('display','none');
            $("#table_prices").css('display','block');
            $("#table_prices").html(data);
          });
        }
      });

        $('input[type=radio][name=split_load_tax]').change(function() {
            if (this.value == '1') {
                $('#split_desc').css('display', 'block');
            } else if (this.value == '0') {
                $('#split_desc').css('display', 'none');
            }
        });
    </script>

    <script>
        var f1 = flatpickr(document.getElementById('datetimepicker1'), {
            enableTime: true,
            dateFormat: "d-m-Y H:i"
        });

        function calculateTotal(id) {
            var val = $("#product_" + id).val();
            if (val != '') {
                if (val.match(/^[0-9]+$/)) {
                    $("#product_" + id).css('border-color', '#bfc9d4');
                    $("#error_" + id).html('');
                    $("#submit-btn").removeAttr('disabled');
                    var i = 0;
                    var total = 0;
                    var arr = '<?php echo json_encode($array??""); ?>';
                    var products = JSON.parse(arr);
                    for (i; i < products.length; i++) {
                        var qty = $("#product_" + products[i]).val();
                        if (qty != '')
                            total = total + parseInt(qty);
                    }
                    $("#total_calculation").val(total);
                } else {
                    $("#product_" + id).css('border-color', '#fd0909');
                    $("#error_" + id).html(' * Quantity can only be a number');
                    $("#submit-btn").attr('disabled', 'disabled');
                }
            } else {
                $("#product_" + id).css('border-color', '#bfc9d4');
                $("#error_" + id).html('');
                $("#submit-btn").removeAttr('disabled');
            }
        }
    </script>

    <script type="text/javascript">
        jQuery(function($) {
            $('#file_input1').multifile();
            $('#deliverydocket1').multifile();
        });
    </script>

    <script>
        $('[data-toggle="tooltip"]').tooltip()
    </script>

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
