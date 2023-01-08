@extends('layouts.template')
@section('title','Update Purchase')
@section('css')

  	 <link href="{{ URL::asset('plugins/flatpickr/flatpickr.css') }}" rel="stylesheet" type="text/css">
  	 <link href="{{ URL::asset('plugins/flatpickr/custom-flatpickr.css') }}" rel="stylesheet" type="text/css">
     <link rel="stylesheet" href="{{url('resources/views/admin/purchases/assets/rotate/jquery.magnify.css')}}" />
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

      	label {
          color: black;
          font-weight: 700!important;
        }

      	.table > tbody > tr > td {
          color: #000 !important;
          font-size: 14px !important;
        }
	</style>

@endsection
@section('contents')

  <div class="row layout-top-spacing" id="cancel-row">
    <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
      @if($errors->any())
        <ul class="alert alert-warning"
          style="background: #eb5a46; color: #fff; font-weight: 300; line-height: 1.7; font-size: 16px; list-style-type: circle;">
          {!! implode('', $errors->all('<li>:message</li>')) !!}
        </ul>
      @endif
      <div class="widget-content widget-content-area br-6" >
        <div class="widget-header">
          <div class="row">
            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
              <h4>Update Purchase
              <a href="{{ route('purchases.show',$purchase->id) }}" class="btn btn-md btn-primary" style="float: right;">
                                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left close-message"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                                      Back
                                    </a></h4>
            </div>
          </div>
        </div>
        <hr>
        <form action="{{ route('purchase.update') }}" method="POST" onsubmit="return loginLoadingBtn(this)" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="purchase_id" value="{{ $purchase->id }}">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label> Fuel Company </label>
              <select class="form-control" name="supplier_company_id" id="exampleFormControlSelect1" required>
                @foreach($companies as $company)
	                <option value="{{ $company->id }}" {{ ($company->id == $purchase->supplier_company_id) ? 'selected' : '' }}>
                		{{ $company->name }}
                	</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label> Date Time </label>
              <input type="text" name="datetime" class="form-control" value="{{ \Carbon\Carbon::parse($purchase->datetime)->format('d-m-Y H:i') }}" id="duedate" autocomplete="off" required>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Choose file : &nbsp;</label>
                 @php
                            $i = 1;
                        @endphp
                        @if($purchase->purchaseinvoices	)
                            @php
                                $files = explode("::",$purchase->purchaseinvoices)
                            @endphp
                            @foreach ($files as $file)
                                @if($i != 1)
                                    <strong>::</strong>
                                @endif
                                <a data-magnify="gallery" data-caption="Image" href="{{ asset('/uploads/purchases/'.$file) }}" class="btn btn-outline-dark mb-2 mr-2" style="padding: 5px 8px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"  height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></a>
                                <a href="{{ asset('/uploads/purchases/'.$file) }}"
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
              <br>
              <input type="file" name="purchaseinvoices[]" id="fileToUploadbol" class="form-control">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label> Invoice No </label>
              <input type="text" class="form-control" name="invoice_no" placeholder="Invoice no" value="{{ $purchase->invoice_number }}" required>
            </div>
          </div>
        </div>
        <div class="row mt-3 mb-3">
          <div class="col-md-12">
            <h5>Set Products Quantities and their Rates</h5>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Tax Type</label>
              <select class="form-control" name="tax_type" id="tax_type" required="" value="{{ $purchase->get_status }}" >
                <option value="1" {{ ($purchase->gst_status == 'Tax Inclusive') ? 'selected' : '' }}>Tax Inclusive</option>
                <option value="2" {{ ($purchase->gst_status == 'Tax Exclusive') ? 'selected' : '' }}>Tax Exclusive</option>
                <option value="3" {{ ($purchase->gst_status == 'No Tax') ? 'selected' : '' }}>No Tax</option>
              </select>
            </div>
          </div>
        </div>
        @php
        	$arr = [];
        @endphp
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table class="table table-bordered mb-4">
                  <thead>
                    <tr>
                      	<th>Product</th>
                      	<th>Quantity</th>
	                    <th>Rate</th>
    	                <th>GST %</th>
        	            <th>Subtotal</th>
            	        <th>GST Amount</th>
                	    <th>Total</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($purchase->products as $product)
                        @php
                            array_push($arr,$product->product_id);
                        @endphp
                  		<tr>
                    		<td>{{ $product->product->name }}</td>
                    		<td><input type="number" class="form-control" step="any" oninput="calculateRate('{{ $product->product_id }}')" name="product_qty_{{ $product->product_id }}" id="product_qty_{{ $product->product_id }}" value="{{ $product->qty }}" required=""></td>
                    		<td><input type="number" class="form-control" step="any" oninput="calculateRate('{{ $product->product_id }}')" name="product_rate_{{ $product->product_id }}" id="product_rate_{{ $product->product_id }}" value="{{ $product->rate }}" required=""></td>
                    		<td><input type="number" class="form-control" value="{{ $product->gst }}" step="any" name="product_gst_{{ $product->product_id }}" id="product_gst_{{ $product->product_id }}" required></td>
                    		<td><input type="number" class="form-control" value="{{ $product->sub_amount }}" step="any" name="subtotal_{{ $product->product_id }}" id="subtotal_{{ $product->product_id }}" readonly></td>
                    		<td><input type="number" class="form-control" value="{{ $product->gst_amount }}" step="any" name="gst_amount_{{ $product->product_id }}" id="gst_amount_{{ $product->product_id }}" readonly></td>
                          <td><input type="number" class="form-control" value="{{ $product->total_amount }}" step="any" name="total_amount_{{ $product->product_id }}" id="total_amount_{{ $product->product_id }}" readonly></td>
                    	</tr>
                     @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label> Total Quantities </label>
              <input type="number" class="form-control" step="any" name="total_calculation" id="total_calculation" readonly="" value="{{ $purchase->total_quantity }}">
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <div class="form-group">
                <label>Total amount </label>
                <input type="number" class="form-control" step="any" name="total_amount" id="grand_total_amount" readonly="" value="{{ $purchase->total_amount }}">
              </div>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label> Select Category *</label>
          <select class="form-control" name="category_id" required>
            <option value="">--Select--</option>
            @foreach ($categories as $category)
            	<option value="{{ $category->id }}" {{ ($category->id == $purchase->category_id) ? 'selected' : '' }}>
            		{{ $category->name }}
            	</option>
            @endforeach
          </select>
        </div>
        <h4 style="color:black;"> VERIFY AND CONFIRM </h4>
        <hr>
        <div class="form-group form-check">
          <label class="form-check-label" for="exampleCheck1" style="color:black;">
            <input type="checkbox" class="form-check-input" value="confirm1" id="exampleCheck1" name="confirm1" required>
            Have you checked numbers of whole calculation?
          </label>
        </div>
        <div class="form-group form-check">
          <label class="form-check-label" for="exampleCheck2" style="color:black;">
            <input type="checkbox" class="form-check-input" value="confirm2" id="exampleCheck2" name="confirm2" required>
            Have you confirmed the information ?
          </label>
        </div>
        <div class="form-group" id="loading-btn">
	        <button type="submit" class="btn btn-primary btn-lg mr-3">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>

@endsection
@section('scripts')

    <script src="{{ URL::asset('plugins/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ URL::asset('plugins/flatpickr/custom-flatpickr.js') }}"></script>
    <script src="{{url('public/jquery-3.4.1.min.js')}}"></script>
    <script src="{{asset('assets/js/jquery.multifile.js')}}"></script>
    <script src="{{url('resources/views/admin/purchases/assets/rotate/jquery.magnify.js')}}"></script>

  <script>

      function loginLoadingBtn() {
          document.getElementById('loading-btn').innerHTML = '<button class="btn btn-primary btn-lg mr-3 disabled" style="width: auto !important;">Creating <svg xmlns="http://www.w3.org/2000/svg" width="24" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-loader spin ml-2"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg></button>';
          return true;
      }

    var f2 = flatpickr(document.getElementById('duedate'), {
    	enableTime: true,
    	dateFormat: "d-m-Y H:i",
    });

    function calculateRate(id)
    {
      total_calculation();
    }

    $('#tax_type').change(function () {
      total_calculation();
    });

    function total_calculation()
    {
		var tax = $('#tax_type').val();
    	var products = '{{ json_encode($arr) }}';
      	var array = JSON.parse(products);
      console.log(array);
      	var total_cal = 0;
        var grand_cal = 0;
      	var gst_total_amount;

      	for (i = 0; i < array.length; i++) {
        	var id = array[i];
          	var qty = $('#product_qty_'+id).val();
          	if (qty == '')
              qty = 0;
          else
            parseFloat(qty);

          	var rate = $('#product_rate_'+id).val();
          	if (rate == '')
              rate = 0;
          	else
              parseFloat(rate);

          	var gst = parseFloat($('#product_gst_'+id).val());
          	var total = qty * rate;

			if (tax == '1') {
        		// Tax inclusive
        		var tol_amount = gst * total / (100 + gst);
        		var amount = tol_amount.toFixed(2);
        		$('#gst_amount_'+id).val(amount);
              	gst_total_amount = 0;
      		}

      		if (tax == '2') {
        		// Tax exclusive
        		var tol_amount = (gst * total) / 100;
        		var amount = tol_amount.toFixed(2);
              	gst_total_amount = amount;
        		$('#gst_amount_'+id).val(amount);
      		}

      		if (tax == '3') {
        		// No tax
              	var gst_total_amount = 0;
        		$('#gst_amount_'+id).val(0);
      		}

		  var grand_total = parseFloat(total) + parseFloat(gst_total_amount);
		  $('#subtotal_'+id).val(total.toFixed(2));
          $('#total_amount_'+id).val(grand_total.toFixed(2));
          total_cal = parseFloat(total_cal) + parseFloat(qty);
          grand_cal = parseFloat(grand_cal) + parseFloat(grand_total.toFixed(2));
        }

      	$('#total_calculation').val(total_cal.toFixed(2));
      	$('#grand_total_amount').val(grand_cal.toFixed(2));
    }

</script>

 	<script type="text/javascript">
        jQuery(function($) {
          $('#fileToUploadbol').multifile();
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
