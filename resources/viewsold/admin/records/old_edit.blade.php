@extends('layouts.template')
@section('title','Edit Record')
@section('css')
     <link rel="stylesheet" href="{{url('resources/views/admin/records/assets/rotate/jquery.magnify.css')}}" />
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

    table{
    table-layout: fixed;
    width: 300px;
    }
	</style>
<link href="{{ URL::asset('plugins/flatpickr/flatpickr.css') }}" rel="stylesheet" type="text/css">
<link href="{{ URL::asset('plugins/flatpickr/custom-flatpickr.css') }}" rel="stylesheet" type="text/css">

@endsection
@section('contents')

    <div class="row layout-top-spacing">
        <div class="col-md-12 col-sm-12 col-12 layout-spacing">
        	<div id="flFormsGrid" class="col-lg-12 layout-spacing">
        		<div class="statbox widget box box-shadow">
        			<div class="widget-header">
        				<div class="row">
        					<div class="col-xl-12 col-md-12 col-sm-12 col-12">
        						<h4>
                                  Edit Record
                                  <a href="{{ route('records.index') }}" class="btn btn-md btn-primary" style="float: right;">
                                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left close-message"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                                      Back
                                    </a>
                              	</h4>
                          </div>
        				</div>
                        @if($errors->any())
                                <ul class="alert alert-warning"
                                    style="background: #eb5a46; color: #fff; font-weight: 300; line-height: 1.7; font-size: 16px; list-style-type: circle;">
                                    {!! implode('', $errors->all('<li>:message</li>')) !!}
                                </ul>
                            @endif
                      <hr>
        			</div>

        			<div class="widget-content widget-content-area">
						<form action="{{ route('records.update',$record->id) }}" method="POST" onsubmit="return loginLoadingBtn(this)" enctype="multipart/form-data">
							@csrf
                          	@method('PUT')

                                  <div class="row mb-4">
                                        <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Operator Name *</label>
                                            <span style="color: red;"> (Previous: {{ $record->user->name }})</span>
                                            <select name="driver_id" class="form-control" required>
                                                @foreach($drivers as $driver)
                                                    <option value="{{ $driver->id }}" {{ ($record->user_id == $driver->id) ? 'selected' : '' }}>
                                                        {{$driver->name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                      </div>
                                        <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Date Time *</label>
                                            <input type="text" value="{{ \Carbon\Carbon::parse($record->datetime)->format('d-m-Y H:i') }}" name="datetime" id="datetimepicker1" class="form-control" required>
                                        </div>
                                      </div>
                                      </div>

                            <div class="form-row mb-4">
                             <div class="col-md-6">
						            <label>BILL OF LADING (BOL) :</label><br>
                                          @php
                                           $i = 1;
                                           @endphp
                                            @if($record->bill_of_lading)
                                                @php
                                                    $files = explode("::",$record->bill_of_lading);
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
                               						<button type="button" class="btn btn-outline-danger mb-2" data-toggle="modal" data-target="#delete" onclick="deleteAttachment('{{ $file }}')">Delete</button>
                                               @php
                                               $i++;
                                               @endphp
                                                @endforeach
                                            @endif
                               <br>
                               <br>
						            <input type="file" name="billoflading[]" id="file_input" class="form-control file_input @error('billoflading.*') is-invalid @enderror">
                            @error('billoflading.*')
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $message }}</strong>
	                                    </span>
	                                @enderror
						        </div>

                            <div class="col-md-6">
						            <label>DELIVERY DOCKET :</label><br>
                                @php
                                           $i = 1;
                                           @endphp
                                            @if($record->delivery_docket)
                                                @php
                                                    $files = explode("::",$record->delivery_docket);
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
                              						<button type="button" class="btn btn-outline-danger mb-2" data-toggle="modal" data-target="#deleteDocket" onclick="deleteAttachment1('{{ $file }}')">Delete</button>
                                              @php
                                              $i++;
                                              @endphp
                                                @endforeach
                                            @endif
						               <br>
                                    <br>
						            <input type="file" name="deleverydocket[]" id="deliverydocket" class="form-control file_input @error('deleverydocket.*') is-invalid @enderror">
	                                @error('deleverydocket.*')
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $message }}</strong>
	                                    </span>
	                                @enderror
						        </div>
                          </div>


                           <div class="row mb-4">
                                      <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Trip Number *</label>
                                            <input type="text" name="load_number" value="{{ $record->load_number }}" class="form-control" required>
                                        </div>
                                      </div>
                                        <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Order Number *</label>
                                            <input type="text" name="order_number" value="{{ $record->order_number }}" class="form-control" required>
                                        </div>
                                      </div>
                                      </div>
                                      <div class="row mb-4">
                                        <div class="col-md-6">
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
                                      </div>

                                        <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Select Category *</label>
                                          <span style="color: red;"> (Previous: {{ $record->category->name }})</span>
                                            <select name="category_id" id="category_id" class="form-control">
                                                <option value="">--Select--</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}" {{($record->category_id == $category->id) ? 'selected': ''}}>{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                      </div>
                                      </div>
                                      <div class="row mb-4">
                                      <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Select Company *</label>
                                           <span style="color: red;"> (Previous: {{ $record->company->name }})</span>
                                            <select name="company_id" id="company_id" class="form-control">
                                                <option value="">--Select--</option>
                                                @foreach ($companies as $company)
                                                    <option value="{{ $company->id }}" {{($record->subCompany->company->id == $company->id) ? 'selected': ''}}>{{ $company->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                      </div>
                                        <input type="hidden" name="record_id" id="record_id" value="{{ $record->id }}">
                                        <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Select Sub Company *</label>
                                            <span style="color: red;"> (Previous: {{ ($record->sub_company_id) ? $record->subCompany->name : 'No previous sub company' }})</span>
                                            <select name="sub_company_id" id="sub_company_id" class="form-control">
                                                <option value="">--Select--</option>
                                                @php
                                                    $subcompany = \App\SubCompany::where('status',1)->where('id',$record->sub_company_id)->first();
                                                @endphp
                                                    <option value="{{ $subcompany->id }}" selected>{{ $subcompany->name }}</option>
                                            </select>
                                        </div>
                                      </div>
                                      </div>


                                  <div class="table-responsive" id="table_prices">
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
                                              <tr>
                                                <td>{{ $product->product->name }}</td>
                                                <td>
                                                <input type="number" name="product_{{ $product->id }}" value="{{ $product->qty }}" class="form-control">
                                                </td>
                                                @if($category->rate_whole_sale != '')
                                                <td>
                                                <input type="number" name="whole_sale_price_{{$product->id}}" value="{{($product->whole_sale ? $product->whole_sale: '')}}" class="form-control" @if($category->whole_sale_display == 2) disabled @endif>
                                                </td>
                                                @endif
                                                @if($category->rate_discount != '')
                                                <td>
                                                  <input type="number" class="form-control" value="{{$product->discount}}" disabled>
                                                </td>
                                                @endif
                                                @if($category->rate_delivery_rate != '')
                                                <td>
                                                  <input type="number" class="form-control" value="{{$product->delivery_rate}}" disabled>
                                                </td>
                                                @endif
                                                @if($category->rate_brand_charges != '')
                                                <td>
                                                  <input type="number" class="form-control" value="{{$product->brand_charges}}" disabled>
                                                </td>
                                                @endif
                                                @if($category->rate_cost_of_credit != '')
                                                <td>
                                                  <input type="number" class="form-control" value="{{$product->cost_of_credit}}" disabled>
                                                </td>
                                                @endif
                                              </tr>
                                          @endforeach
                                     </tbody>
                               </table>
                            </div>
                                        <div class="row mb-4">
                                        <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Split Load /Full Load</label>
                                            <small style="color: red;"> *</small>
                                            <select name="splitfullload" class="form-control" required>
                                                <option value="">--Select--</option>
                                                <option value="Split Load" {{ ($record->splitfullload == 'Split Load') ? 'selected' : '' }}> Split Load</option>
                                                <option value="Full Load" {{ ($record->splitfullload == 'Full Load') ? 'selected' : '' }}> Full Load </option>
                                            </select>
                                        </div>
                                       </div>
                                       <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Include Split Load Charges?</label> <span style="color: red;"> (Previous:
                                                @if ($record->split_load_status == 1)
                                                    <span class="badge badge-block badge-success">Included</span>
                                                @else
                                                    <span class="badge badge-block badge-danger">Not Included</span>
                                                @endif )</span>
                                            <br>
                                            <input type="radio" name="split_load_tax" value="1" {{ ($record->split_load_status== 1)? "checked" : "" }}> Yes <br>
                                            <input type="radio" name="split_load_tax" value="0" {{ ($record->split_load_status== 0)? "checked" : "" }}> No
                                        </div>
                                        </div>
                                      </div>
                                        <div class="form-group" id="split_desc" style="display: none;">
                                            <label>Write Some Description</label>
                                            <textarea class="form-control" name="split_load_des" rows="3">{{$record->split_load_des}}</textarea>
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
						    		<button type="submit" class="btn btn-primary btn-lg mr-3" id="submit-btn">
						    			Submit
						    		</button>
						    	</div>
						    </div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>


 <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Delete Attachment</h5>
                  <a data-dismiss="modal" aria-label="Close">X</a>
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
                  <h5 class="modal-title" id="exampleModalLabel">Delete Attachment</h5>
                  <a data-dismiss="modal" aria-label="Close">X</a>
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

    <!-- Scripts -->
	<script>

		function deleteAttachment1(id)
      	{
        	$('#attachment1').val(id);
        }
		function deleteAttachment(id)
      	{
        	$('#attachment').val(id);
        }

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
    <script src="{{url('public/jquery-3.4.1.min.js')}}"></script>
    <script src="{{asset('assets/js/jquery.multifile.js')}}"></script>
    <script src="{{url('resources/views/admin/records/assets/rotate/jquery.magnify.js')}}"></script>
    <script>
        var f1 = flatpickr(document.getElementById('datetimepicker1'), {

             enableTime: true,
             dateFormat: "d-m-Y H:i"
        });

       $('#company_id').change(function() {
        var cat = $("#category_id").val();
        var id = $("#company_id").val();
        if (id != '' && cat != '') {
          $.get('{{ route('sub.companies.get') }}',{_token:'{{ csrf_token() }}',id:id,cat:cat}, function(data) {
            var companies = JSON.parse(data);
            var i =0;
            var len = companies.length;
            if(len != 0){
             var o = ' <option value="">--Select--</option>';
             $("#sub_company_id").html(o);
            for (i = 0; i < len; i++) {
              var o = new Option(companies[i].name, companies[i].id);
              $("#sub_company_id").append(o);
            }
            }
            else{
               var o = ' <option value="">--Select--</option>';
               $("#sub_company_id").html(o);
            }
          });
        }
      });


      $('#sub_company_id').change(function() {
        var id = $("#sub_company_id").val();
        var record_id = $("#record_id").val();
        if (id != '') {
          $.post('{{ route('record.sub.companies.prices') }}',{_token:'{{ csrf_token() }}',id:id,record_id:record_id}, function(data) {
            $("#table_prices").html(data);
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



        function loginLoadingBtn()
      	{
          document.getElementById('loading-btn').innerHTML = '<button class="btn btn-primary btn-lg mr-3 disabled" style="width: auto !important;">Please wait <svg xmlns="http://www.w3.org/2000/svg" width="24" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-loader spin ml-2"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg></button>';
          return true;
       }

	</script>

  <script>
        $(document).ready(function(){
            var i = $("input[type=radio][name=split_load_tax]:checked").val();
            if (i == '1') {
                $('#split_desc').css('display', 'block');
            } else if (i == '0') {
                $('#split_desc').css('display', 'none');
            }
        });
    </script>


 <script type="text/javascript">
        jQuery(function($)
        {
            $('#file_input').multifile();
            $('#deliverydocket').multifile();
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
