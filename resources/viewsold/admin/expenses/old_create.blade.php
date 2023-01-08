@extends('layouts.template')
@section('title','Add New Expense')
@section('css')

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
        .custom-file-container__image-preview {
         height: 160px !important;
        }
	</style>
  	<link href="{{ URL::asset('plugins/flatpickr/flatpickr.css') }}" rel="stylesheet" type="text/css">
  	<link href="{{ URL::asset('plugins/flatpickr/custom-flatpickr.css') }}" rel="stylesheet" type="text/css">
 	<link href="{{asset('assets/css/file-upload-with-preview.min.css')}}" rel="stylesheet" type="text/css" />

@endsection
@section('contents')

  <div class="row layout-top-spacing" id="cancel-row">
    <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
      <div class="widget-content widget-content-area br-6" >
        <div class="widget-header">
          <div class="row">
            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
              <h4>
                Add New Expense
                <a href="{{ route('expenses') }}" class="btn btn-md btn-primary" style="float: right;">All Expenses</a>
              </h4>
        @if($errors->any())
        <ul class="alert alert-warning"
            style="background: #eb5a46; color: #fff; font-weight: 300; line-height: 1.7; font-size: 16px; list-style-type: circle;">
            {!! implode('', $errors->all('<li>:message</li>')) !!}
        </ul>
        @endif
              <hr>
              <form action="{{ url('/expenses-store') }}" method="POST" enctype="multipart/form-data" onsubmit="return loginLoadingBtn(this)">
              	@csrf
                <div class="row mb-3">
                	<div class="col-md-6">
                      	<div class="row">
                        	<div class="col-md-12">
                              <div class="form-group">
                                   <?php
                                    $acc = \App\ChartAccount::where('title','Expense')->first();
                                    ?>
                                  <label>Account</label>
                                  <span style="color: red;"> *</span>
                                <select class="form-control" name="chart_account" required readonly>
                                  @if($acc)
                                  <option value="{{ $acc->id }}">{{ $acc->title }}</option>
                                  @endif
                                </select>
                              </div>
                          	</div>
                      	</div>
                      	<div class="row">
                        	<div class="col-md-6">
                              <div class="form-group">
                                  	<label>Sub Account</label>
                                  	<span style="color: red;"> *</span>
		                          	<select class="form-control" name="sub_account_id" required>
                                		<option value="">-- Select Sub Account --</option>
                                        @if($acc)
                                        @foreach($acc->subaccounts as $subaccount)
                                        <option value="{{ $subaccount->id }}">{{ $subaccount->title }}</option>
                                        @endforeach
                                        @endif
                                	</select>
                              </div>
                          	</div>
                        	<div class="col-md-6">
                              <div class="form-group">
                                  	<label>Date</label>
                                  	<span style="color: red;"> *</span>
		                          	<input type="text" class="form-control" name="datetime" id="datetime" value="{{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}" required>
                              </div>
                          	</div>
                      	</div>
                  	</div>
                	<div class="col-md-6">
                    	<div class="form-group">
                        	<label>Description</label>
                          	<span style="color: red;"> *</span>
                          	<textarea class="form-control" name="description" value="{{ old('description') }}" row="10" style="height: 137px;" required></textarea>
                      	</div>
                  	</div>
                </div>
                <div class="row mb-3">
                	<div class="col-md-6">
                      	<div class="row">
                        	<div class="col-md-6">
                              <div class="form-group">
                                  	<label>Amount</label>
                                  	<span style="color: red;"> *</span>
		                          	<input type="number" min="1" step="any" class="form-control" name="amount" value="{{ old('amount') }}" required>
                              </div>
                          	</div>
                        	<div class="col-md-6">
                              <div class="form-group">
                                  	<label>Payee</label>
                                  	<span style="color: red;"> *</span>
                                	<input type="text" class="form-control" name="payee" value="{{ old('payee') }}" required>
                              </div>
                          	</div>
                      	</div>
                  	</div>
                	<div class="col-md-6">
                    	<div class="form-group">
                        	<label>Attach File</label>
                          	<input type="file" class="form-control" name="attach[]" id="attach">
                      	</div>
                  	</div>
                </div>
                <div class="row mb-3">
                	<div class="col-md-6">
                    	<div class="form-group">
                        	<label>Ref#</label>
                          	<span style="color: red;"> *</span>
                          	<input type="text" class="form-control" name="ref_no" value="{{ old('ref_no') }}" placeholder="e.g. transaction ID" required>
                      	</div>
                  	</div>
                	<div class="col-md-6">
                  	</div>
                </div>
                <div class="row mb-3">
                  <div class="col-md-12">
                    	<div class="form-group" id="loading-btn">
                        	<button type="submit" class="btn btn-md btn-primary btn-lg mr-3">
                            	Add Expense
                          	</button>
                      	</div>
                  </div>
                </div>
              </form>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection
@section('scripts')

    <script>

		function loginLoadingBtn() {
        	document.getElementById('loading-btn').innerHTML = '<button class="btn btn-primary btn-lg mr-3 disabled" style="width: auto !important;">Please wait <svg xmlns="http://www.w3.org/2000/svg" width="24" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-loader spin ml-2"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg></button>';
        	return true;
		}

    </script>
    <script src="{{ URL::asset('plugins/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ URL::asset('plugins/flatpickr/custom-flatpickr.js') }}"></script>
    <script src="{{asset('assets/js/jquery.multifile.js')}}"></script>
    <script>
        jQuery(function($) {
            $('#attach').multifile();
        });
        var f1 = flatpickr(document.getElementById('datetime'), {
             enableTime: true,
             dateFormat: "d-m-Y H:i"
        });
    </script>

@endsection
