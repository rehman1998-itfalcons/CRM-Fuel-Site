@extends('layouts.layout.main')
@section('title','Update Fuel Delivery')
@section('css')

    <link href="{{ url('assets/css/tables/table-basic.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ url('plugins/flatpickr/flatpickr.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ url('plugins/flatpickr/custom-flatpickr.css') }}" rel="stylesheet" type="text/css">
   <link rel="stylesheet" href="{{ url('assets/js/jquery.magnify.css') }}" />
    <!--<link rel="stylesheet" href="{{url('resources/views/supervisor/assets/rotate/jquery.magnify.css')}}" />-->
    <style>
        .widget-content-area {
            -webkit-box-shadow: 0 4px 6px 0 rgba(85, 85, 85, 0.0901961), 0 1px 20px 0 rgba(0, 0, 0, 0.08), 0px 1px 11px 0px rgba(0, 0, 0, 0.06);
            -moz-box-shadow: 0 4px 6px 0 rgba(85, 85, 85, 0.0901961), 0 1px 20px 0 rgba(0, 0, 0, 0.08), 0px 1px 11px 0px rgba(0, 0, 0, 0.06);
            box-shadow: 0 4px 6px 0 rgba(85, 85, 85, 0.0901961), 0 1px 20px 0 rgba(0, 0, 0, 0.08), 0px 1px 11px 0px rgba(0, 0, 0, 0.06);
        }

        .table > thead > tr > th {
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
            float:right;
            color:red;
        }

        .file_input{
            padding: 8px;
        }
        .btn-danger {
            border-radius: 25px !important;
            box-shadow: 0px 5px 20px 0 rgba(0, 0, 0, 0.1);
        }
    </style>

@endsection
@section('contents')
<div class="container-fluid p-10">
    <div class="row justify-content-center">
        <div class="white_box mb_20">
        <div class="col-sm-12 ">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>
                                Update Fuel Delivery
                                <a href="{{ route('supervisor.record.details',$record->id) }}"
                                   class="btn btn-md btn-primary" style="float: right;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round" class="feather feather-arrow-left close-message">
                                        <line x1="19" y1="12" x2="5" y2="12"></line>
                                        <polyline points="12 19 5 12 12 5"></polyline>
                                    </svg>
                                    Back
                                </a>
                            </h4>

                            @if($errors->any())
                                <ul class="alert alert-warning"
                                    style="background: #eb5a46; color: #fff; font-weight: 300; line-height: 1.7; font-size: 16px; list-style-type: circle;">
                                    {!! implode('', $errors->all('<li>:message</li>')) !!}
                                </ul>
                            @endif


                            <div class="row">
                                <div class="col-md-6 offset-3">
                                    <form action="{{ route('supervisor.record.fuel.delivery.form.update') }}" enctype="multipart/form-data" method="POST" onsubmit="return loginLoadingBtn(this)">
                                        @csrf
                                        <input type="hidden" name="record_id" value="{{$record->id}}">
                                        <div class="form-group">
                                            <label>Driver Name *</label>
                                            <span style="color: red;"> (Previous: {{ $record->user->name }})</span>
                                            <select name="driver_id" class="form-control" required>
                                                @foreach($drivers as $driver)
                                                    <option value="{{ $driver->id }}" {{ ($record->user_id == $driver->id) ? 'selected' : '' }}>
                                                        {{$driver->name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Date Time *</label>
                                            <input type="text" value="{{ \Carbon\Carbon::parse($record->datetime)->format('d-m-Y H:i') }}" name="datetime" id="datetimepicker1" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label>BILL OF LADING (BOL)</label><br>
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
                                                    <a data-magnify="gallery" data-caption="Image" href="{{ asset('/uploads/records/'.$file) }}" class="btn btn-outline-dark mb-2 mr-2" style="padding: 5px 8px;">
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
                                                    <button type="button" class="btn btn-outline-danger mb-2" data-bs-toggle="modal" data-bs-target="#delete" onclick="deleteAttachment('{{ $file }}')">Delete</button>
                                                    @php
                                                        $i++;
                                                    @endphp
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <input type="file" name="billoflading[]" id="file_input1" class="form-control file_input @error('billoflading.*') is-invalid @enderror">
                                        </div>
                                        <div class="form-group">
                                            <label>DELIVERY DOCKET</label><br>
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
                                                    <a data-magnify="gallery" data-caption="Image" href="{{ asset('/uploads/records/'.$file) }}" class="btn btn-outline-dark mb-2 mr-2" style="padding: 5px 8px;">
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

                                                    <button type="button" class="btn btn-outline-danger mb-2" data-bs-toggle="modal" data-bs-target="#deleteDocket" onclick="deleteAttachment1('{{ $file }}')">Delete</button>

                                                    @php
                                                        $i++;
                                                    @endphp
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <input type="file" name="deleverydocket[]" id="deliverydocket1" class="form-control file_input @error('deleverydocket.*') is-invalid @enderror">
                                        </div>

                                        <div class="form-group">
                                            <label>Trip Number *</label>
                                            <input type="text" name="load_number" value="{{ $record->load_number }}" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Order Number *</label>
                                            <span style="color: red;"> (Previous: )</span>
                                            <input type="text" name="order_number" value="{{ $record->order_number }}" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Fuel Company *</label>
                                            <span style="color: red;"> (Previous: {{ $record->supplierCompany->name }})</span>
                                            <select class="form-control" name="fuel_company" value="{{ old('fuel_company') }}">
                                                <option value="">--Select--</option>
                                                @foreach($supplycompanies as $company)
                                                    <option value="{{$company->id}}" {{ ($record->supplier_company_id == $company->id) ? 'selected' : '' }}>
                                                        {{$company->name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @php
                                            $total = 0;
			                                $array = [];
                                        @endphp
                                        @foreach ($record->products as $product)
                                            @php
                                                array_push ($array,$product->id);
                                            @endphp
                                            <div class="form-group">
                                                <label>{{ $product->product->name }} *</label>
                                                <input type="text" name="product_{{ $product->id }}" id="product_{{ $product->id }}" onchange="calculateTotal('{{ $product->id }}')" value="{{ $product->qty }}" class="form-control" required>
                                            </div>
                                            @php
                                                $total = $total + $product->qty;
                                            @endphp
                                        @endforeach
                                        <div class="form-group">
                                            <label>Total Calculation *</label>
                                            <input type="text" value="{{ $total }}" id="total_calculation" class="form-control" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label>Split Load /Full Load</label>
                                            <small style="color: red;"> *</small>
                                            <select name="splitfullload" class="form-control" required>
                                                <option value="">--Select--</option>
                                                <option value="Split Load" {{ ($record->splitfullload == 'Split Load') ? 'selected' : '' }}> Split Load</option>
                                                <option value="Full Load" {{ ($record->splitfullload == 'Full Load') ? 'selected' : '' }}> Full Load </option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>
                                                <input type="checkbox" name="confirm" value="1" required> Have you
                                                double checked the information again?
                                            </label>
                                        </div>

                                        <div class="form-group" id="loading-btn">
                                            <button type="submit" class="btn btn-md btn-primary">
                                                Update Application
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

</div>
</div>

    <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Change Status</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" style="transform: translate(0%, 0%);color: blue">x</button>
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


    <div class="modal fade" id="deleteDocket" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Change Status</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" style="transform: translate(0%, 0%);color: blue">x</button>
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
    <script src="{{ url('plugins/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ url('plugins/flatpickr/custom-flatpickr.js') }}"></script>
    <script src="{{url('jquery-3.4.1.min.js')}}"></script>
    <script src="{{url('assets/js/jquery.multifile.js')}}"></script>
    <!--<script src="{{url('resources/views/supervisor/assets/rotate/jquery.magnify.js')}}"></script>-->
 <!--<script src="{{ url('assets/js/jquery.magnify.js') }}"></script>-->
    <script>
        function deleteAttachment1(id)
        {
            $('#attachment1').val(id);
        }
        function deleteAttachment(id)
        {
            $('#attachment').val(id);
        }
        function loginLoadingBtn() {
            document.getElementById('loading-btn').innerHTML = '<button class="btn btn-primary disabled" style="width: auto !important;">Please wait <svg xmlns="http://www.w3.org/2000/svg" width="24" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-loader spin ml-2"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg></button>';
            return true;
        }

        $('#category_id').change(function () {
            $.get('{{ route('companies.get') }}', {_token: '{{ csrf_token() }}', type: 'companies'}, function (data) {
                var companies = JSON.parse(data);
                var i = 0;
                var len = companies.length;
                for (i = 0; i < len; i++) {
                    var o = new Option(companies[i].name, companies[i].id);
                    $("#company_id").append(o);
                }
            });
        });

        $('#company_id').change(function () {
            var cat = $("#category_id").val();
            var id = $("#company_id").val();
            if (id != '' && cat != '') {
                $.get('{{ route('sub.companies.get') }}', {_token: '{{ csrf_token() }}', id: id}, function (data) {
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

        $('input[type=radio][name=split_load_tax]').change(function () {
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

        function calculateTotal(id)
        {
            var val = $("#product_"+id).val();
            if (val != '') {
                if (val.match(/^[0-9]+$/)) {
                    $("#product_"+id).css('border-color','#bfc9d4');
                    $("#error_"+id).html('');
                    $("#submit-btn").removeAttr('disabled');
                    var i = 0;
                    var total = 0;
                    var arr = '<?php echo json_encode($array) ?>';
                    var products = JSON.parse(arr);
                    for (i; i < products.length; i++) {
                        var qty = $("#product_"+products[i]).val();
                        if (qty != '')
                            total = total + parseInt(qty);
                    }
                    $("#total_calculation").val(total);
                } else {
                    $("#product_"+id).css('border-color','#fd0909');
                    $("#error_"+id).html(' * Quantity can only be a number');
                    $("#submit-btn").attr('disabled','disabled');
                }
            } else {
                $("#product_"+id).css('border-color','#bfc9d4');
                $("#error_"+id).html('');
                $("#submit-btn").removeAttr('disabled');
            }
        }
    </script>

    <script type="text/javascript">
        jQuery(function($)
        {
            $('#file_input1').multifile();
            $('#deliverydocket1').multifile();
        });
    </script>

    <script>

        $('[data-bs-toggle="tooltip"]').tooltip()

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
