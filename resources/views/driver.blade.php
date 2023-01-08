@extends('layouts.driver')
@section('title','Record')
@section('css')
 <link href="{{asset('public/assets/css/file-upload-with-preview.min.css')}}" rel="stylesheet" type="text/css" />
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
@endsection
@section('contents')



       <div class="widget-content widget-content-area br-6 mt-3 mb-5" >
        <div class="widget-header">
          <div class="row">
                   		@if($errors->any())
                      		<ul class="alert alert-warning" style="background: #eb5a46; color: #fff; font-weight: 300; line-height: 1.7; font-size: 16px; list-style-type: circle;">
                            	{!! implode('', $errors->all('<li>:message</li>')) !!}
                            </ul>
                      	@endif
            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
              <h4>Record Details</h4>
                          </div>
        				</div>
</div>
                      <hr>


        			<div class="widget-content widget-content-area">
						<form action="{{ route('driver.submit') }}" method="POST" onsubmit="return loginLoadingBtn(this)" id="driver_form" enctype="multipart/form-data">
							@csrf
						    <div class="form-row mb-4">
                            <div class="col-md-6">
                              <label>Driver Name</label>
                              <small style="color: red;"> *</small>
                              <select name="driver_id" class="form-control" readonly required>
                                    <option selected value="{{ Auth::id() }}">{{ Auth::user()->name}}</option>
                              </select>
                            </div>
                               <div class="col-md-6">
                                 <label>Date Time</label>
						            <small style="color: red;"> *</small>
						            <input type="text" name="date_time" value="{{ date('d-m-Y H:i') }}" class="form-control"  id="datetimepicker1" requried>
                                 </div>
                          	</div>

                         <div class="form-row mb-4">
                             <div class="col-md-6">
                               <div class="form-group">
                                 <label style="color:black;">BILL OF LADING (BOL) (Allow Multiple)</label>
                                 <input type="file" name="billoflading[]" id="file_input1" class="form-control file_input @error('billoflading.*') is-invalid @enderror">
                               </div>
                           	</div>
                            <div class="col-md-6">
                              <div class="form-group">
                                <label style="color:black;">DELIVERY DOCKET (Allow Multiple)</label>
                                <input type="file" name="deleverydocket[]" id="deliverydocket1" class="form-control file_input @error('deleverydocket.*') is-invalid @enderror">
						        </div>
                           	</div>
                          </div>

                          	<div class="form-row mb-4">
						        <div class="col-md-6">
						            <label>Trip NUMBER</label>
						            <small style="color: red;"> *</small>
						            <input type="number" name="load_number" class="form-control @error('load_number') is-invalid @enderror" required value="{{ old('load_number') }}">
	                                @error('load_number')
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $message }}</strong>
	                                    </span>
	                                @enderror
						        </div>
                                <div class="col-md-6">
						            <label>ORDER NUMBER</label>
						            <small style="color: red;"> *</small>
						            <input type="number"  name="order_number" class="form-control @error('order_number') is-invalid @enderror" required value="{{ old('order_number') }}">
	                                @error('order_number')
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $message }}</strong>
	                                    </span>
	                                @enderror
						        </div>
                          	</div>

                            <div class="form-row mb-4">
						        <div class="form-group col-md-6">
						            <label>Fuel Company</label>
						            <small style="color: red;"> *</small>
						            <select class="form-control" name="fuel_company" value="{{ old('fuel_company') }}" requried>
                                      <option value="">--Select--</option>
                                      @foreach($supplycompanies as $company)
                                      	<option value="{{$company->id}}">{{$company->name}}</option>
                                      @endforeach
                                  </select>
						        </div>
						        <div class="form-group col-md-6"></div>
                            </div>

                          <div class="form-row mb-4">
                            @php
                                $array = [];
                            @endphp
                            @foreach($products as $product)
                              @php
                                  array_push ($array,$product->id);
                              @endphp
                              <div class="col-md-6 form-group">
                              	<label>{{ $product->name }}</label>
                                <small style="color: red;" id="error_{{ $product->id }}"></small>
                                <input type="number" name="product_{{ $product->id }}" id="product_{{ $product->id }}" onchange="calculateTotal('{{ $product->id }}')" value="{{ old('product_'.$product->id) }}" class="form-control">
                              </div>
                            @endforeach
                          </div>

                          <div class="form-row mb-4">
						        <div class="col-md-6">
						            <label>Total Calculation</label>
						            <input type="text" id="total_calculation" style="color: #000; font-weight: 900;" name="total" class="form-control" value="{{old('total')}}" disabled>
						        </div>
                                <div class="col-md-6">
						            <label>Split Load /Full Load</label>
						            <small style="color: red;"> *</small>
						            <select name="splitfullload" class="form-control" required value="{{ old('splitfullload') }}">
                                      <option value="">--Select--</option>
                                        <option value="Split Load"> Split Load</option>
                                        <option value="Full Load"> Full Load </option>
                                  </select>
						        </div>
                          	</div>

                           <h4 style="color:black;"> VERIFY AND CONFIRM </h4>
                           <hr>

                                <div class="form-group form-check">

                                    <label id="confirm1-label">
                                      <input type="checkbox" class="form-check-input" value="confirm1" id="confirm1" name="confirm1" required>
                                        Did you double check the numbers of whole calculation?
                                    </label>
                                </div>

                                <div class="form-group form-check">

                                    <label id="confirm2-label">
                                      <input type="checkbox" class="form-check-input" value="confirm2" id="confirm2" name="confirm2" required>
                                        Have you confirmed the information ?
                                    </label>
                                </div>

						        <div class="form-row mb-4">
						    	<div class="form-group col-md-12" id="loading-btn">
						    		<button type="submit" class="btn btn-primary" id="submit-btn">
						    			Submit
						    		</button>
						    	</div>
						    </div>
						</form>
					</div>



@endsection
@section('scripts')

    <!-- Scripts -->
	<script>

      $("#submit-btn").click(function (e) {
          var status1 = $('#confirm1').is(':checked');
          var status2 = $('#confirm2').is(':checked');
          if (status1 == false) {
              $('#confirm1-label').css('color','#fd0909');
              e.preventDefault();
              e.stopPropagation();
              return false;
          } else {
              $('#confirm1-label').css('color','#333');
      	  }

          if (status2 == false) {
              $('#confirm2-label').css('color','#fd0909');
              e.preventDefault();
              e.stopPropagation();
              return false;
          } else {
              $('#confirm1-label').css('color','#333');
      	  }

      		if (status1 == true && status2 != true) {
              e.preventDefault();
              e.stopPropagation();
              return false;
      		}
      });

	</script>
    <script src="{{ URL::asset('plugins/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ URL::asset('plugins/flatpickr/custom-flatpickr.js') }}"></script>
    <script src="{{asset('assets/js/file-upload-with-preview.min.js')}}"></script>
    <script src="{{ asset('loadingoverlay.min.js') }}"></script>
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

        function loginLoadingBtn()
      	{
          // Show full page LoadingOverlay
          $.LoadingOverlay("show");

          document.getElementById('loading-btn').innerHTML = '<button class="btn btn-primary disabled" style="width: auto !important;">Please wait <svg xmlns="http://www.w3.org/2000/svg" width="24" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-loader spin ml-2"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg></button>';
          return true;
       }

      	$('[data-toggle="tooltip"]').tooltip()

        jQuery(function($)
        {
            $('#file_input1').multifile();
            $('#deliverydocket1').multifile();
        });
    </script>
	<script src="{{ URL::asset('assets/js/jquery.multifile.js')}}"></script>

@endsection
