@extends('layouts.layout.main')
@section('title','Fuel Delivery Approval')
@section('css')

     
          <link rel="stylesheet" href="{{url('assets/js/jquery.magnify.css')}}" />
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
        table{
        table-layout: fixed;
        width: 300px;
         }

     	<style>
        body {
			background-color: #fff !important;
			font-family: 'Quicksand' !important;
			}


          	h1, h2, h3, h4, h5, h6, p, span {
              font-family: 'Quicksand' !important;
		}
    </style>

@endsection
@section('contents')

	<link rel="stylesheet" href="{{ asset('multiple-emails.css') }}">
	<link rel="stylesheet" href="{{ asset('glyphicons.css') }}">

    <div class="row layout-top-spacing" id="cancel-row">
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

              <div class="container-fluid p-10">
                <div class="row justify-content-center">
                    <div class="white_box mb_20">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>
                                Fuel Delivery Approval
                                <a href="{{ route('accountant.record.details.update',$record->id) }}" class="btn btn-md btn-primary"
                                   style="float: right; margin-right: 5px;">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                  Update
                              	</a>
                                <a href="{{ route('accountant.dashboard') }}" class="btn btn-md btn-primary"
                                   style="float: right; margin-right: 5px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round" class="feather feather-arrow-left close-message">
                                        <line x1="19" y1="12" x2="5" y2="12"></line>
                                        <polyline points="12 19 5 12 12 5"></polyline>
                                    </svg>
                                    Back</a>
                            </h4>

                            <div class="row">
                                <div class="col-md-12">
                                  <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Type</label> <span class="badge badge-success">Whole Sale</span>
                                    </div>
                                    </div>
                                     <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Accountant Approval Status</label>
                                        @if($record->status == 1)
                                            <span class="badge badge-success">
                            Approved
                           @else
                              </span>
                                            <span class="badge badge-danger">
                             Still Not Approved
                           </span>
                                        @endif
                                    </div>
                                    </div>
                                  </div>
                                     <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Operator Name</label>
                                        <select name="user_id" class="form-control" disabled>
                                            <option value="">
                                                {{ $record->user->name }}
                                            </option>
                                        </select>
                                    </div>
                                       </div>
                                         <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Date Time</label>
                                        <input type="text" name="date_time" class="form-control"
                                               value="{{ \Carbon\Carbon::parse($record->datetime)->format('d-m-Y H:i') }}" disabled>
                                    </div>
                                       </div>
                                  </div>
                                   <div class="row">
                                <div class="col-md-6">
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
                                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                         class="feather feather-download">
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
                                <div class="col-md-6">
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
                                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                         class="feather feather-download">
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
                                  </div>
                                     <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Trip NUMBER</label>
                                        <input type="text" class="form-control" value="{{$record->load_number}}"
                                               disabled>
                                    </div>
                                       </div>
                                       <div class="col-md-6">
                                    <div class="form-group">
                                        <label>ORDER NUMBER</label>
                                        <input type="text" class="form-control" value="{{ $record->order_number }}"
                                               disabled>
                                    </div>
                                       </div>
                                  </div>
                                  <div class="row">
                         <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Fuel Company</label>
                                        <select class="form-control" readonly>
                                            <option value="">
                                                {{ $record->supplierCompany->name }}
                                            </option>
                                        </select>
                                    </div>
                                    </div>

                         <div class="col-md-6">
                            	<div class="form-group">
                                	<label>Selected Category *</label>
                                      <select name="category_id" id="category_id" class="form-control" readonly>
                                              <option value="{{ $record->category_id }}">{{ $record->category->name }}</option>
                                      </select>
                              	</div>
                        </div>
                       </div>
                             <div class="row">
                        <div class="col-md-6">
                            	<div class="form-group">
                                	<label>Selected Company *</label>
                                      <select class="form-control" readonly>
                                        <option value="">{{ $record->subCompany->company->name }}</option>
                                      </select>
                              	 </div>
                            </div>

                        <div class="col-md-6">
                      			<input type="hidden" name="record_id" value="{{ $record->id }}">
                            	<div class="form-group">
                                	<label>Selected Sub Company *</label>
                                      <select class="form-control" readonly>
                                        <option value="{{$record->subCompany->id}}">{{ $record->subCompany->name }}</option>
                                      </select>
                              	</div>
                          </div>
                       </div>

                              <div class="table-responsive" id="table_previous">
                                    <table class="table table-bordered mb-4">
                                        <thead>
                                           <tr>
                                             @php
                                 $category = $record->category;
                              	@endphp
                                                <th>Products</th>
                                                <th>Quantity</th>
                                              @if($category->rate_whole_sale != '')
                                              	<th>Whole Sale</th>
                                              @endif
                                             @if($category->rate_discount != '')
                                              	<th>Discount</th>
                                              @endif
                                              @if($category->rate_delivery_rate != '')
                                              	<th>Delivery Rate</th>
                                              @endif
                                               @if($category->rate_brand_charges != '')
                                              	<th>Brand Charges</th>
                                              @endif
                                              @if($category->rate_cost_of_credit != '')
                                              	<th>COC/limit</th>
                                              @endif
                                            </tr>
                                        </thead>
                                      	<tbody>
                              	          @foreach ($record->products as $product)
                                          @if($product->qty != 0)
                                              <tr>
                                                <td>{{ $product->product->name }}</td>
                                                <td>
                                                  {{ $product->qty }}
                                                </td>
                                                @if($category->rate_whole_sale != '')
                                                <td>
                                                  <input type="text" name="whole_sale_price" value="{{$product->whole_sale}}" class="form-control" disabled>
                                                </td>
                                                @endif
                                                @if($category->rate_discount != '')
                                                <td>
                                                  <input type="text" class="form-control" value="{{$product->discount}}"  disabled>
                                                </td>
                                                @endif
                                                @if($category->rate_delivery_rate != '')
                                                <td>
                                                  <input type="text" class="form-control" value="{{$product->delivery_rate}}"  disabled>
                                                </td>
                                                @endif
                                                @if($category->rate_brand_charges != '')
                                                <td>
                                                  <input type="text" class="form-control" value="{{$product->brand_charges}}" disabled>
                                                </td>
                                                @endif
                                                @if($category->rate_cost_of_credit != '')
                                                <td>
                                                  <input type="text" class="form-control" value="{{$product->cost_of_credit}}" disabled>
                                                </td>
                                                @endif
                                              </tr>
                                          @endif
                                          @endforeach
                                     </tbody>
                               </table>
                            </div>
                                  <div class="row">
                                    <div class="col-md-4">
                                       <div class="form-group">
                                        <label>Split Load /Full Load</label>
                                        <input type="text" class="form-control" value="{{ $record->splitfullload }}"
                                               disabled>
                                    </div>
                                    </div>
                                    <div class="col-md-4">
                                       <div class="form-group">
                                        <label>Split Load Charges</label>
                                        <input type="text" class="form-control" value="{{ $record->split_load_charges }}"
                                               disabled>
                                    </div>
                                    </div>
                                     <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Company GST Status</label> <span
                                                class="badge badge-info">{{ $record->gst_status }}</span>
                                    </div>
                                    </div>
                                  </div>
                                    <div class="form-group">
                                        <label>Invoice</label><br>
                                        <a href="{{ url('invoice',$record->id) }}" class="btn btn-outline-dark mb-2 mr-2" target="_blank"
                                           style="padding: 5px 8px;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                 stroke-linecap="round" stroke-linejoin="round"
                                                 class="feather feather-download">
                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                <polyline points="7 10 12 15 17 10"></polyline>
                                                <line x1="12" y1="15" x2="12" y2="3"></line>
                                            </svg>
                                            Download Invoice</a>
                                    </div>
                                    <div class="row">
                                    <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Total Invoice amount (without GST)</label>
                                        <input type="text" class="form-control" value="{{ number_format($record->total_without_gst,2) }}"
                                               disabled>
                                    </div>
                                      </div>
                                    <div class="col-md-6">
                                    <div class="form-group">
                                        <label>GST Amount</label>
                                        <input type="text" class="form-control" value="{{ number_format($record->gst,2) }}" disabled>
                                    </div>
                                      </div>
                                  </div>
                                    <div class="row">
                                    <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Total invoice amount (inc GST)</label>
                                        <input type="text" class="form-control" value="{{ number_format($record->total_amount,2) }}" disabled>
                                    </div>
                                      </div>
                                    <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Outstanding Balance</label>
                                        <input type="text" class="form-control" value="{{ number_format($record->total_amount,2) }}" disabled>
                                    </div>
                                      </div>
                                  </div>
                                  	@if($record->status == 0)
                                    <div class="form-group">
                                        <label>
                                            <input type="checkbox" id="confirm" value="1" required> <span id="confirm-text">Have you double
                                          checked the information?</span>
                                        </label>
                                    </div>
                                  	@endif
                                </div>
                            </div>
                          	@if($record->status == 0)
                            <div class="row text-center">
                                <div class="col-md-12">
                                    <h5></h5>
                                    <hr>
                                    <a href="{{ route('accountant.approve.application',$record->id) }}"
                                       id="approve-application" class="btn btn-success btn-rounded mb-2">Approve</a>
                                    <button data-bs-toggle="modal" data-bs-target="#sendMail" class="btn btn-success btn-rounded mb-2">Approve With Email&nbsp;
                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg></button>
                                    <a href="" class="btn btn-danger btn-rounded mb-2" data-bs-toggle="modal" data-bs-target="#disapproveModel">Disapprove</a>
                                </div>
                            </div>
                          	@endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>


 <!-- dissaproveModel -->
            <div class="modal fade" id="disapproveModel" tabindex="-1" role="dialog" aria-labelledby="mainmenuLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Report Cancel Model </h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" class="btn btn-danger">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <form method="post" action="{{route('accountant.record.details.cancel')}}" class="comment_form_reject" id="comment_form1">
                              @csrf
                                <input type="hidden" class="form-control d-none" value="{{$record->id}}" name="record_id">
                                <div class="form-group">
                                    <label for="exampleInputEmail1" style="color:black;" class="font-weight-bold"> Write a Reason For Cancelation </label>
                                    <textarea name="cancel_reason" id="cancel_reason" class="form-control" rows="5" required>
                                     </textarea>
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="confirm1" id="confirm1" value="confirm1" required>
                                        <label class="custom-control-label" for="confirm1" required> Have you Double Check the Information? </label>
                                    </div>
                                </div>
                                <button type="submit" name="send_notification" class="btn btn-primary"> Submit </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
	@php
		$smtp = \App\Smtp::select('body')->first();
	@endphp
	<div class="modal fade" id="sendMail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document" style="max-width: 800px">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Send E-Mail</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                      x
                    </button>
                </div>
              	<form action="{{ route('accountant.approve.withemail') }}" method="POST" onsubmit="return loginLoadingBtn(this)">
                	@csrf
                  	<input type="hidden" name="record_id" value="{{ $record->id }}">
                  	<div class="modal-body">
                      	<p class="modal-text">Sending to:</p>
                      	<div style="margin-left: 20px;">
                          <p class="modal-text">Company E-Mail Addresses:</p>
                          @foreach ($record->subCompany->company->emails as $mail)
                              <label style="margin-left: 25px; display: block;">
                                  <input type="checkbox" class="main_company" name="companies[]" checked value="{{ $mail->email_address }}">&nbsp; {{ $mail->email_address }}
                              </label>
                          @endforeach
                          <p class="modal-text">Sub-Company E-Mail Addresses:</p>
                          @foreach ($record->subCompany->emails as $mail)
                              <label style="margin-left: 25px; display: block;">
                                  <input type="checkbox" class="sub_company" name="companies[]" checked value="{{ $mail->email_address }}">&nbsp; {{ $mail->email_address }}
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
                                    <input type="checkbox" checked name="sdd_status" class="sum" id="sdd_status" value="1">&nbsp; DELIVERY DOCKET
                                </label>
                              	<div style="margin-left: 20px;">
                                	@php
                                  		$files = explode("::",$record->delivery_docket);
                                  		$i = 1;
                                  	@endphp
                                  	@foreach ($files as $key => $file)
                                  		<label>
                                  			<input type="checkbox" checked name="dockets[]" onclick="checkDocket()" class="docket_report" value="{{ $file }}"> Attachment {{ $i++ }} &nbsp; <a data-magnify="gallery" data-caption="Image" href="{{ asset('uploads/records/'.$file) }}" class="btn btn-outline-dark mb-2 mr-2" style="padding: 2px 3px;"><svg xmlns="http://www.w3.org/2000/svg" width="20"  height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></a>
                                  		</label>
                                  	@endforeach
                              	</div>
                                <label style="display: block;">
                                    <input type="checkbox" checked name="bol_status" id="bol_status" value="1">&nbsp; BILL OF LADING (BOL)
                                </label>
                              	<div style="margin-left: 20px;">
                                	@php
                                  		$files = explode("::",$record->bill_of_lading);
                                  		$i = 1;
                                  	@endphp
                                  	@foreach ($files as $key => $file)
                                  		<label>
                                  			<input type="checkbox" checked name="lading[]" onclick="checkLading()" class="lading_report" value="{{ $file }}"> Attachment {{ $i++ }} &nbsp; <a data-magnify="gallery" data-caption="Image" href="{{ asset('uploads/records/'.$file) }}" class="btn btn-outline-dark mb-2 mr-2" style="padding: 2px 3px;"><svg xmlns="http://www.w3.org/2000/svg" width="20"  height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></a>
                                  		</label>
                                  	@endforeach
                          </div>
                             <label style="display: block;">
                            	<input type="checkbox" checked class="sum" name="invoice_status" value="1">&nbsp; INVOICE
                            </label>
                      	</div>
                      	<div class="form-group" id="confirmation-text">
                       		<input type="checkbox" id="confirmation" required>&nbsp; Comfirm E-mail Information
                      	</div>
                  	</div>
                  	<div class="modal-footer" id="loading-btn">
                        <button type="submit" class="btn btn-primary">Send</button>
                  	</div>
              	</form>
            </div>
        </div>
    </div>


@endsection
@section('scripts')

 <script src="{{url('/assets/js/jquery.magnify.js')}}"></script>
    <script>
        function loginLoadingBtn() {
            document.getElementById('loading-btn').innerHTML = '<button class="btn btn-primary disabled" style="width: auto !important;">Please wait <svg xmlns="http://www.w3.org/2000/svg" width="24" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-loader spin ml-2"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg></button>';
            return true;
        }

        $('#approve-application').click(function(e) {
        	if (!$("#confirm").is(':checked')) {
            	$("#confirm").focus();
				$("#confirm-text").css('color','red');
                e.preventDefault();
                e.stopPropagation();
                return false;
			}
		});

    </script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.9.2/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#mytextarea',
            height: 500,
        });
    </script>
    <script>

        $('.main_company').click(function () {
            if ($('.main_company').is(':checked')) {
                $('.sum').prop('checked',true);
                $('.lading_report').prop('checked',true);
                $('#bol_status').prop('checked',true);
                $('.docket_report').prop('checked',true);

            } else {
                if (!$('.sub_company').is(':checked')) {
                    $('.sum').prop('checked',false);
                    $('.lading_report').prop('checked',false);
                    $('#bol_status').prop('checked',false);
                    $('.docket_report').prop('checked',false);
                }
            }
        });

        $('.sub_company').click(function () {
            if ($('.sub_company').is(':checked')) {
                $('.sum').prop('checked',true);
                $('.lading_report').prop('checked',true);
                $('#bol_status').prop('checked',true);
                $('.docket_report').prop('checked',true);

            } else {
                if (!$('.main_company').is(':checked')) {
                    $('.sum').prop('checked',false);
                    $('.lading_report').prop('checked',false);
                    $('#bol_status').prop('checked',false);
                    $('.docket_report').prop('checked',false);
                }
            }
        });

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
	<script src="{{ url('multiple-emails.js') }}"></script>
	<script>

      	$(document).ready(function () {
			$('#more_emails').multiple_emails();
			$('#more_emails').change(function(){
				$('#current_emails').text($('#more_emails').val());
            });

        });

	</script>

@endsection
