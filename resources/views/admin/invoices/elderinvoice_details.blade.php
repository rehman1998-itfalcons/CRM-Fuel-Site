@extends('layouts.layout.main')
@section('title','Invoice Details')
@section('css')

    <link rel="stylesheet" href="{{url('assets/js/jquery.magnify.css')}}" />
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #fff !important;
            font-family: 'Quicksand' !important;
        }
        .flatpickr-calendar {
            z-index: 1050 !important;
        }
        .table > thead > tr > th {
            color: #000;
        }


        h1, h2, h3, h4, h5, h6, p, span {
            font-family: 'Quicksand' !important;
        }

        .widget-content-area {
            -webkit-box-shadow: 0 4px 6px 0 rgba(85, 85, 85, 0.0901961), 0 1px 20px 0 rgba(0, 0, 0, 0.08), 0px 1px 11px 0px rgba(0, 0, 0, 0.06);
            -moz-box-shadow: 0 4px 6px 0 rgba(85, 85, 85, 0.0901961), 0 1px 20px 0 rgba(0, 0, 0, 0.08), 0px 1px 11px 0px rgba(0, 0, 0, 0.06);
            box-shadow: 0 4px 6px 0 rgba(85, 85, 85, 0.0901961), 0 1px 20px 0 rgba(0, 0, 0, 0.08), 0px 1px 11px 0px rgba(0, 0, 0, 0.06);
        }
        table{
            table-layout: fixed;
            width: 300px;
        }

        .dataTables_length {
            display: inline-block;
        }

        .dt-buttons {
            display: inline-block;
            float: right;
        }
        .button1:hover{
            background-color: #2bbc4a;
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

        .invoice-th {
            color: #dee2e6 !important;
        }
    </style>
  <link href="{{URL::asset('plugins/flatpickr/flatpickr.css') }}" rel="stylesheet" type="text/css">
  <link href="{{ URL::asset('plugins/flatpickr/custom-flatpickr.css') }}" rel="stylesheet" type="text/css">
@endsection
@section('contents')

    <link rel="stylesheet" href="{{ URL::asset('multiple-emails.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('glyphicons.css') }}">
    <div class="row layout-top-spacing mb-5" id="cancel-row">
        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
            @if (session('danger'))
                <p class="alert alert-danger">
                    <strong>Failed</strong> Please update smtp setting to send e-mail.
                </p>
            @endif
            @if (session('warning'))
                <p class="alert alert-danger">
                    <strong>Failed</strong> Please select email.
                </p>
            @endif
            @if($errors->any())
                <ul class="alert alert-warning" style="background: #eb5a46; color: #fff; font-weight: 300; line-height: 1.7; font-size: 16px; list-style-type: circle;">
                    {!! implode('', $errors->all('<li>:message</li>')) !!}
                </ul>
            @endif
            <div class="widget-content container widget-content-area br-6">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4 style="display: contents">Invoice Details</h4>
                            <div style="float: right;">
                              <button class="btn btn-md btn-primary btn-rounded" data-bs-toggle="modal" data-bs-target="#followsModal">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-book-open"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>&nbsp; Follow-up
                                </button>
                                <a href="{{ route('records.show',$record->id) }}" class="btn btn-md btn-primary btn-rounded">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>&nbsp; View Details
                                </a>
                                <button class="btn btn-md btn-primary btn-rounded" data-bs-toggle="modal" data-bs-target="#addPayment">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>&nbsp; Add Payment
                                </button>
                                <button class="btn btn-md btn-primary btn-rounded" data-bs-toggle="modal" data-bs-target="#sendMail">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>&nbsp; Send Mail
                                </button>
                                <button class="btn btn-md btn-primary btn-rounded" onclick="printInvoice('invoice_print')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer action-print" data-bs-toggle="tooltip" data-placement="top" data-original-title="Reply"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>&nbsp; Print
                                </button>
                                <a href="{{ route('invoice',$record->id) }}" target="_blank" class="btn btn-md btn-primary btn-rounded">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>&nbsp; PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
              @if($record->follows_note)
              <div class="row">
                <div class="col-md-12">
                <p class="alert alert-warning" style="font-weight: 700;">{{$record->follows_note}}</p>
              </div>
              </div>
              @endif
                <div id="invoice_print">
                    <div class="row">
                        <div class="col-md-5">
                            <hr style="border-top: 4px solid #1db239; margin-top: 95px;">
                        </div>
                        <div class="col-md-2" style="padding: 0px;">
                            @if($invoicedata->invoice_logo)
                                <img src="{{($invoicedata) ? URL::asset('/public/uploads/siteinvoice/'.$invoicedata->invoice_logo) : '' }}" class="img-fluid">
                            @else
                                <img src="{{ URL::asset('public/assets/img/atlas_logo.png') }}" class="img-fluid">
                            @endif
                        </div>
                        <div class="col-md-5">
                            <hr style="border-top: 4px solid #1db239; margin-top: 95px;">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                        </div>
                        <div class="col-md-4" style="padding: 0px;">

                            <h4 class="text-center" style="font-weight: 600;">ABN {{($invoicedata->invoice_abn) ? $invoicedata->invoice_abn : '141 628 616 90'}}</h4>
                        </div>
                        <div class="col-md-4">
                        </div>
                    </div>
                    <br><br>

                    <div class="row">
                        <div class="col-md-4">
                            <div style="background: #f3f3f3; padding: 15px; height: 180px;">
                                <p style="font-size: 21px;">Bill To:</p>
                                <h6 style="font-weight: bolder; font-size: 18px;">{{ $record->subCompany->company->name }}</h6>
                                @php
                                    $email = $record->subCompany->company->emails->first();
                                @endphp
                                <p style="font-size: 15px;">{{ ($email) ? $email->email_address : '' }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div style="background: #f3f3f3; padding: 15px; height: 180px;">
                                <p style="font-size: 21px;">Ship To:</p>
                                <h6 style="font-weight: bolder; font-size: 18px;">{{ $record->subCompany->name }}</h6>
                                <p style="font-size: 15px;">{{ $record->subCompany->address }}</p>
                                @php
                                    $email = $record->subCompany->emails->first();
                                    $sno = 1;
                                    $sub_total = 0;
                                @endphp
                                <p style="font-size: 15px;">{{ ($email) ? $email->email_address : '' }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div style="background: #f3f3f3; padding: 15px; height: 180px;">
                                <h6 style="font-weight: bolder; font-size: 18px;">Tax Invoice #{{ $record->invoice_number }}</h6>
                                <br>
                                @php
                              		$due = '-';
                                    if($record->subCompany->inv_due_days > 0)
                                    {
                                        $date = \Carbon\Carbon::parse($record->datetime)->format('d-m-Y');
                                        $due = date('d-m-Y', strtotime($date. ' + '.$record->subCompany->inv_due_days.' days'));
                                         }
                                        elseif($record->subCompany->inv_due_days < 0){

                                          $timestamp = strtotime($record->datetime);
                                          $daysRemaining = (int)date('t', $timestamp) - (int)date('j', $timestamp);
                                          $positive_value =  abs($record->subCompany->inv_due_days);
                                          $origional_date = $positive_value+$daysRemaining;

                                          $date = \Carbon\Carbon::parse($record->datetime)->format('d-m-Y');
                                            $due = date('d-m-Y', strtotime($date. ' + '.$origional_date.' days'));
                                      }
                                @endphp
                                <p style="font-size: 15px;">Created Date: {{ \Carbon\Carbon::parse($record->datetime)->format('d-m-Y') }}</p>
                                <p style="font-size: 15px;"> Due Date: {{ ($due == '-') ? \Carbon\Carbon::parse($record->datetime)->format('d-m-Y') : $due }}</p>
                            </div>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="" style="background: #f3f3f3;">
                                    <tr>
                                        @php
                                            $category = $record->category;
                                        @endphp
                                        <th scope="col" style="width: 60px;">S.No</th>
                                        <th scope="col" style="width: 176px;">Item</th>
                                        <th scope="col">Quantity</th>
                                        @if($category->invoice_whole_sale != '')
                                            <th>Base Price</th>
                                        @endif
                                        @if($category->invoice_discount != '')
                                            <th>Discount</th>
                                        @endif
                                        @if($category->invoice_delivery_rate != '')
                                            <th>Delivery Rate</th>
                                        @endif
                                        @if($category->invoice_brand_charges != '')
                                            <th>Brand Charges</th>
                                        @endif
                                        @if($category->invoice_cost_of_credit != '')
                                            <th>Credit Charges</th>
                                        @endif
                                        <th scope="col">Net price</th>
                                        <th scope="col">Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($record->products as $product)
                                        @if((float)$product->qty > 0)
                                            <tr>
                                                <td>{{ $sno++ }}</td>
                                                <td>{{ $product->product->name }}</td>
                                                <td>{{ $product->qty }}</td>

                                                @if($category->invoice_whole_sale != '')
                                                    <td>
                                                        {{$product->whole_sale}}
                                                    </td>
                                                @endif
                                                @if($category->invoice_discount != '')
                                                    <td>
                                                        {{$product->discount}}
                                                    </td>
                                                @endif
                                                @if($category->invoice_delivery_rate != '')
                                                    <td>
                                                        {{$product->delivery_rate}}
                                                    </td>
                                                @endif
                                                @if($category->invoice_brand_charges != '')
                                                    <td>
                                                        {{$product->brand_charges}}
                                                    </td>
                                                @endif
                                                @if($category->invoice_cost_of_credit != '')
                                                    <td>
                                                        {{$product->cost_of_credit}}
                                                    </td>
                                                @endif

                                                @php
                                                    $unit = ($product->whole_sale + $product->delivery_rate + $product->brand_charges + $product->cost_of_credit) - $product->discount;
                                                    $amount = $unit * $product->qty;
                                                    $sub_total = $sub_total + $amount;
                                                @endphp
                                                <td class="text-right">{{ number_format(round($unit,4),4) }}</td>
                                                <td class="text-right">${{ number_format(round($amount,2),2) }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-7"></div>
                        <div class="col-md-1"></div>
                        <div class="col-md-4">
                            <p>Sub Total @if($record->gst_status == 'Excluded') (Ex. GST) @else (Inc. GST) @endif: <span style="float: right;">${{ number_format(round($sub_total,2),2) }}</span></p>
                            @if($record->split_load_charges)
                                <hr>
                                <p>Split Load Charges: <span style="float: right;">${{number_format(round($record->split_load_charges,2),2) }}</span></p>
                            @endif
                            <hr>
                            <p>GST: <span style="float: right;">${{ number_format(round($record->gst,2),2) }}</span></p>
                            <hr>
                            @php
                                $gst = ($record->gst_status == 'Excluded') ? $record->gst : 0;
                                $grand = $sub_total + $record->split_load_charges + $gst;
                            @endphp
                            <h4 style="font-size: 18px;">Grand Total (Inc. GST): <span style="float: right;">${{ number_format(round($grand,2),2) }}</span></h4>
                        </div>
                    </div>
                    <br><br>
                    <div style="text-align: center; font-style: italic;margin-left: 5px;"> This is computer generated invoice, hence
                        does
                        not require signature. - All Prices in Australian Dollar <span style="font-size: 15px;">&#36;</span></div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div style="background: #f3f3f3; padding: 15px; height: 270px;">

                                @if($invoicedata->telephone_header_img1 != 'NULL')
                                    <img src="{{URL::asset('/public/uploads/siteinvoice/'.$invoicedata->telephone_header_img1)}}" width="40px">
                                @endif
                                @if($invoicedata->telephone_header_img2 != 'NULL')
                                    <img src="{{URL::asset('/public/uploads/siteinvoice/'.$invoicedata->telephone_header_img2)}}" width="180px">
                                @endif

                                <h5>{{($invoicedata->telephone_header) ? $invoicedata->telephone_header : 'Telephone & Internet Banking -BPAY'}}</h5>
                                <span style="font-size:10px;">{{($invoicedata->telephone_text) ? $invoicedata->telephone_text : 'Telephone & Internet Banking - BPAY
Contact your bank or financial institution to make this payment from your cheque, saving or transaction account.
More info:www.bpay.com.au Any payment must be for the exact amount of this invoice.
Otherwise , any amount paid will not be accepted and will be returned'}}</span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div style="background: #f3f3f3; padding: 15px; height: 270px;">
                                @php
                                    $files = explode("::",$invoicedata->pay_online_imges);
                                @endphp

                                @if($invoicedata->pay_online_imges)
                                    @foreach ($files as $file)
                                        <img src="{{($invoicedata) ? URL::asset('/public/uploads/siteinvoice/'.$file) : '' }}" width="60px">
                                    @endforeach
                                @endif
                                <br/>
                                <p>{{($invoicedata->pay_online_text) ? $invoicedata->pay_online_text : 'Pay online by clicking on Pay Now in
                        your invoice email.'}}</p>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div style="background: #f3f3f3; padding: 15px; height: 270px;">
                                <table class="table">
                                    <tbody>
                                    <tr>
                                        <th style="font-size: 12px;">Bank:</th>
                                        <td style="font-size: 12px;">{{($invoicedata->invoice_bank) ? $invoicedata->invoice_bank : 'COMMONWEALTH BANK AUSTRALIA'}}</td>
                                    </tr>
                                    <tr>
                                        <th style="font-size: 12px;">Name:</th>
                                        <td style="font-size: 12px;">{{($invoicedata->name) ? $invoicedata->name : 'ATLAS FUEL AUSTRALIA PTY LTD'}}</td>
                                    </tr>
                                    <tr>
                                        <th style="font-size: 12px;">BSB:</th>
                                        <td style="font-size: 12px;">{{($invoicedata->invoice_bsb) ? $invoicedata->invoice_bsb : '066115'}}</td>
                                    </tr>
                                    <tr>
                                        <th style="font-size: 12px;">AC#:</th>
                                        <td style="font-size: 12px;">{{($invoicedata->invoice_account_no) ? $invoicedata->invoice_account_no : '10985324'}}</td>
                                    </tr>
                                    <tr>
                                        <th style="font-size: 12px;">Ref#:</th>
                                        <td style="font-size: 12px;">{{ $record->invoice_number }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row text-center">
                        <div class="col-sm-12">
                            Powered By <a href="https://smjunited.com/" target="_blank" style="color:black; font-weight:bold;">
                                {{($invoicedata->powerd_text) ? $invoicedata->powerd_text : 'ATLAS FUEL'}}</a>
                            <div style="float: right; display: contents;">
                                <img src="{{URL:: asset('public/uploads/line-bar.png') }}" width="30%">
                                <span style="border:1px solid #fff; letter-spacing: 6px; color: #02a043; font-size:14px; font-weight:bold; text-align:right;">{{($invoicedata->invoice_web_url) ? $invoicedata->invoice_web_url : 'www.atlasfuel.com.au'}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="background: #111; color: #fff; padding: 20px;">
                        <div class="col-sm-4">
                            <img src="{{ URL::asset('public/uploads/call.jpg') }}" width="40px" height="" width="40px">{{($invoicedata->invoice_phone_no) ? $invoicedata->invoice_phone_no : '(+61) 437 485 565'}}
                        </div>
                        <div class="col-sm-4">
                            <img src="{{ URL::asset('public/uploads/mail.jpg') }}" width="40px">{{($invoicedata->invoice_email) ? $invoicedata->invoice_email : 'logistics@smjunited.com'}}
                        </div>
                        <div class="col-sm-4">
                            <img src="{{ URL::asset('public/uploads/location.jpg') }}" width="40px">{{($invoicedata->invoice_address) ? $invoicedata->invoice_address : '2095 Toodyay rd -Gidgegannup WA 6083'}}
                        </div>
                    </div>

                </div>
            </div>


        <br>
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
            <div class="widget-content container widget-content-area br-6" >
                <div class="widget-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <h3>Transactions History</h3>
                        </div>
                    </div>
                    <hr>
                    <div class="table-responsive">
                        <table id="zero_config" class="table table-bordered table-hover">
                            <thead>
                            <tr style="background-color: #2bbc4a;">
                                <th class="invoice-th">S.No</th>
                                <th class="invoice-th">Date</th>
                                <th class="invoice-th">Amount</th>
                            </tr>
                            </thead>
                            <?php $sno = 1; ?>
                            <tbody class="table table-striped table-bordered">
                            @forelse ($record->transactionHistory as $history)
                                <tr>
                                    <td>{{ $sno++ }}</td>
                                    <td>{{ $history->created_at->format('d-m-Y g:i A') }}</td>
                                    <td>${{ number_format($history->amount,2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">No record found.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
 </div>

        @endsection
        @section('modal')

            <div class="modal fade" id="sendMail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document" style="max-width: 800px">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">E-Mail details</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                x
                            </button>
                        </div>
                        <form action="{{ route('send.invoice.mail') }}" method="POST" onsubmit="return loginLoadingBtn(this)">
                            @csrf
                            <input type="hidden" name="record_id" value="{{ $record->id }}">
                            <div class="modal-body">
                                <p class="modal-text">Sending to:</p>
                                <div style="margin-left: 20px;">
                                    <p class="modal-text">Company E-Mail Addresses:</p>
                                    @foreach ($record->subCompany->company->emails as $mail)
                                        <label style="margin-left: 25px; display: block;">
                                            <input type="checkbox"checked name="companies[]" value="{{ $mail->email_address }}" class="main_company">&nbsp; {{ $mail->email_address }}
                                        </label>
                                    @endforeach
                                    <p class="modal-text">Sub-Company E-Mail Addresses:</p>
                                    @foreach ($record->subCompany->emails as $mail)
                                        <label style="margin-left: 25px; display: block;">
                                            <input type="checkbox"checked name="companies[]" value="{{ $mail->email_address }}" class="sub_company">&nbsp; {{ $mail->email_address }}
                                        </label>
                                    @endforeach
                                </div>
                                <div class="form-group">
                                    <label>Add more Email(s)</label>
                                    <span style="color: red;"> * Put comma for multiple emails.</span>
                                    <input type="text" name="more_emails" id="more_emails" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Subject *</label>
                                    <input type="text" name="subject" id="subject" class="form-control" value="Invoice no {{ $record->invoice_number }}" required>
                                </div>
                                <div class="form-group">
                                    <label>Mail Body *</label>
                                    <textarea name="body" id="mytextarea" class="form-control">{{ ($smtp) ? $smtp->body : '' }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>Confirm The Attachments :</label>
                                    <div style="margin-left: 20px;">
                                        <label style="display: block;">
                                            <input type="checkbox" name="sdd_status" id="sdd_status" checked class="sdd_status" value="1">&nbsp; DELIVERY DOCKET
                                        </label>
                                        <div style="margin-left: 20px;">
                                            @php
                                                $files = explode("::",$record->delivery_docket);
                                                $i = 1;
                                            @endphp
                                            @foreach ($files as $key => $file)
                                                <label>
                                                    <input id="dockets" type="checkbox" checked name="dockets[]" onclick="checkDocket()" class="docket_report dockets" value="{{ $file }}"> Attachment {{ $i++ }} &nbsp; <a data-magnify="gallery" data-caption="Image" href="{{ URL::asset('public/uploads/records/'.$file) }}" class="btn btn-outline-dark button1 mb-2 mr-2" style="padding: 2px 3px;"><svg xmlns="http://www.w3.org/2000/svg" width="20"  height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></a>
                                                </label>
                                            @endforeach
                                        </div>
                                        <label style="display: block;">
                                            <input type="checkbox" name="bol_status" checked id="bol_status" class="bol_status" value="1">&nbsp; BILL OF LADING (BOL)
                                        </label>
                                        <div style="margin-left: 20px;">
                                            @php
                                                $files = explode("::",$record->bill_of_lading);
                                                $i = 1;
                                            @endphp
                                            @foreach ($files as $key => $file)
                                                <label>
                                                    <input id="lading" type="checkbox" checked name="lading[]" onclick="checkLading()" class="lading_report lading" value="{{ $file }}"> Attachment {{ $i++ }} &nbsp; <a data-magnify="gallery" data-caption="Image" href="{{ URL::asset('public/uploads/records/'.$file) }}" class="btn btn-outline-dark button1 mb-2 mr-2" style="padding: 2px 3px;"><svg xmlns="http://www.w3.org/2000/svg" width="20"  height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></a>
                                                </label>
                                            @endforeach
                                        </div>
                                        <label style="display: block;">
                                            <input id="invoice" class="invoice" type="checkbox" checked name="invoice_status" value="1">&nbsp; INVOICE
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group" id="confirmation-text">
                                    <input type="checkbox" id="confirmation" required>&nbsp; Comfirm E-mail Information
                                </div>
                            </div>
                            <div class="modal-footer" id="loading-btn">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="addPayment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add Payment</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                x
                            </button>
                        </div>
                        @php
                            $total = round($record->total_amount,2) - round($record->paid_amount,2);
                            $amount_to_pay = $total;
                        @endphp
                        @if ($amount_to_pay > 0)
                            <form action="{{ route('pay.invoice') }}" method="POST" onsubmit="return loginLoadingBtn1(this)">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <p class="modal-text">
                                            Total Remaining Amount: <br><strong>(${{ $amount_to_pay }})</strong>
                                            <span style="float: right;">
                                  <button type="button" class="btn btn-sm btn-info" onclick="addAllPayment('{{ $amount_to_pay }}')">Add All</button>
                              </span>
                                        </p>
                                    </div>
                                    @csrf
                                    <input type="hidden" name="record_id" value="{{ $record->id }}">

                                    <?php
                                    $acc = \App\ChartAccount::where('title','Sale')->first();
                                    ?>
                                    <div class="form-group">
                                        <label>Amount to Pay</label>
                                        <input type="number" step="any" name="payment_amount" id="payment_amount" class="form-control" oninput="calculateRemaining('{{ $amount_to_pay }}')" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Main Account</label>
                                        <select name="account_id" class="form-control" readonly required id="account_id">
                                            @if($acc)
                                                <option value="{{ $acc->id }}">{{ $acc->title }}</option>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Select Sub Account</label>
                                        <select name="sub_account_id" class="form-control" required id="sub_account_id">
                                            <option value="">--Select--</option>
                                            @if($acc)
                                                @foreach($acc->subaccounts as $subaccount)
                                                    <option value="{{ $subaccount->id }}">{{ $subaccount->title }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>

                                  <div class="form-group">
                                  <label>Select Date Time</label>
						            <small style="color: red;"> *</small>
						            <input type="text" name="payment_datetime" value="{{old('payment_datetime')}}" placeholder="DD-MM-YYYYY" class="form-control"  id="datetimepicker1" required>
                                   </div>

                                   <div class="form-group">
                                        <p>
                                            Remaining amount: <br><strong>$<span id="remaining_amount">{{ $amount_to_pay }}</span></strong>
                                        </p>
                                    </div>
                                    <div class="form-group">
                                        <label>
                                            <input type="checkbox" name="confirmed" required> Confirm payment amount.
                                        </label>
                                    </div>
                                </div>
                                <div class="modal-footer" id="loading-btn1">
                                    <button class="btn btn-danger" data-bs-dismiss="modal">Discard</button>
                                    <button type="submit" class="btn btn-primary">Confirm</button>
                                </div>
                            </form>
                        @else
                            <div class="modal-body">
                                <div class="form-group">
                                    <p>There are no remaining amount.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>


           <div class="modal fade" id="followsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document" style="max-width: 800px">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Follow-up</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                x
                            </button>
                        </div>
                            <form action="{{ url('/invoice-follows-note') }}" method="POST" onsubmit="return loginLoadingBtn2(this)">
                              @csrf
                              <div class="modal-body">
                                    <input type="hidden" name="record_id" value="{{ $record->id }}">
                                    <div class="form-group">
                                        <label>Add Note</label>
                                       <textarea name="note" class="form-control" rows="12">{{ ($record) ? $record->follows_note : '' }}</textarea>
                                    </div>
                                </div>
                                <div class="modal-footer" id="loading-btn2">
                                    <button class="btn btn-danger" data-bs-dismiss="modal">Discard</button>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                    </div>
                </div>
            </div>


        @endsection
        @section('scripts')

           <script src="{{url('/assets/js/jquery.magnify.js')}}"></script>

           <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.9.2/tinymce.min.js" referrerpolicy="origin"></script>

            <script type="text/javascript">

                function loginLoadingBtn() {
                    document.getElementById('loading-btn').innerHTML = '<button type="button" class="btn btn-primary" disabled>Please wait <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-loader spin ml-2"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg></button>';
                    return true;
                }

            function loginLoadingBtn1() {

               document.getElementById('loading-btn1').innerHTML = '<button type="button" class="btn btn-primary" disabled>Please wait <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-loader spin ml-2"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg></button>';
               return true;
            }
              function loginLoadingBtn2() {

               document.getElementById('loading-btn2').innerHTML = '<button type="button" class="btn btn-primary" disabled>Please wait <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-loader spin ml-2"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg></button>';
               return true;
            }
            </script>

            <script>
                tinymce.init({
                    selector: '#mytextarea',
                    height: 500,
                });

            </script>
            <script>

                $('#sdd_status').click(function () {
                    if ($('#sdd_status').prop('checked') == true) {
                        $(".docket_report").prop("checked",true);
                    } else {
                        $(".docket_report").prop("checked",false);
                    }
                });

                function checkDocket()
                {
                    $("#sdd_status").prop("checked",false);
                }

                $('#bol_status').click(function () {
                    if ($('#bol_status').prop('checked') == true) {
                        $(".lading_report").prop("checked",true);
                    } else {
                        $(".lading_report").prop("checked",false);
                    }
                });

                function checkLading()
                {
                    $("#bol_status").prop("checked",false);
                }

                function addAllPayment(pay)
                {
                    $('#payment_amount').val(pay);
                    var input = $('#payment_amount').val();
                    var total = pay - input;
                    total = total.toFixed(2);
                    $('#remaining_amount').html(total);
                }

                function calculateRemaining(pay)
                {
                    var input = $('#payment_amount').val();
                    var total = pay - input;
                    total = total.toFixed(2);
                    $('#remaining_amount').html(total);
                }

            </script>

            <script>
                function printInvoice(InvoiceId) {
                    var printContents = document.getElementById(InvoiceId).innerHTML;
                    var originalContents = document.body.innerHTML;
                    document.body.innerHTML = printContents;
                    window.print();
                    document.body.innerHTML = originalContents;
                }
            </script>
                 <script src="{{URL:: asset('multiple-emails.js') }}"></script>
            <script>

                $(document).ready(function () {
                    $('#more_emails').multiple_emails();
                    $('#more_emails').change(function(){
                        $('#current_emails').text($('#more_emails').val());
                    });

                });



                $('.main_company').click(function () {
                    if ($('.main_company').is(':checked')) {
                        $('#lading').prop('checked',true);
                        $('#dockets').prop('checked',true);
                        $('#bol_status').prop('checked',true);
                        $('#invoice').prop('checked',true);
                        $('#sdd_status').prop('checked',true);

                    } else {
                        if (!$('.sub_company').is(':checked')) {
                            $('#lading').prop('checked',false);
                            $('#dockets').prop('checked',false);
                            $('#bol_status').prop('checked',false);
                            $('#invoice').prop('checked',false);
                            $('#sdd_status').prop('checked',false);
                        }
                    }
                });

                $('.sub_company').click(function () {
                    if ($('.sub_company').is(':checked')) {
                        $('#lading').prop('checked',true);
                        $('#dockets').prop('checked',true);
                        $('#bol_status').prop('checked',true);
                        $('#invoice').prop('checked',true);
                        $('#sdd_status').prop('checked',true);

                    } else {
                        if (!$('.main_company').is(':checked')) {
                            $('#lading').prop('checked',false);
                            $('#dockets').prop('checked',false);
                            $('#bol_status').prop('checked',false);
                            $('#invoice').prop('checked',false);
                            $('#sdd_status').prop('checked',false);
                        }
                    }
                });

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
