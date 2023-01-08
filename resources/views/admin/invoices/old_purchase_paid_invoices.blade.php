@extends('layouts.template')
@section('title','Purchase Paid Orders')
@section('css')

    <link rel="stylesheet" type= "text/css" href="{{ URL::asset('plugins/table/table/datatable/datatables.css') }}">
    <link rel="stylesheet" type= "text/css" href="{{ URL::asset('plugins/table/table/datatable/custom_dt_html5.css') }}">
    <link rel="stylesheet" type= "text/css" href="{{ URL::asset('plugins/table/table/datatable/dt-global_style.css') }}">
	<style>
		table.dataTable thead .sorting_asc:before {
          display: none;
        }
	</style>
    <link href="{{ URL::asset('plugins/flatpickr/flatpickr.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('plugins/flatpickr/custom-flatpickr.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ URL::asset('plugins/loaders/custom-loader.css') }}" rel="stylesheet" type="text/css" />
	<style>
		.loader {
          position: sticky !important;
          text-align: center !important;
          left: 50% !important;
          top: 50% !important;
        }
      	thead > tr > th.sorting {
    		white-space: nowrap !important;
        }
		.table > thead > tr > th {
    		font-weight: 700 !important;
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
              <h4>Purchase Paid Orders</h4>
              <hr>
              <div class="row">
              	<div class="col-md-3">
                	<select id="select-range" class="form-control select-change">
                    	<option value="">--Select Date Range --</option>
                        <option value="Today">Today</option>
                        <option value="Yesterday">Yesterday</option>
                        <option value="Last 7 Days">Last 7 Days</option>
                        <option value="Last 30 Days">Last 30 Days</option>
                        <option value="This Month">This Month</option>
                        <option value="Last Month">Last Month</option>
                        <option value="Custom Range">Custom Range</option>
                  	</select>
                </div>

                <div class="col-md-3">
                	<select id="select-company" class="form-control select-change">
                      	<option value="">--Select Company --</option>
                      @foreach($companies as $company)
                        <option value="{{ $company->id }}">{{ $company->name }}</option>
                      @endforeach
                    </select>
                </div>
              	<div class="col-md-6"></div>
              </div>
              <br>
              <div class="row" id="custom-range" style="display: none;">
              	<div class="col-md-3">
                	<input type="text" id="from_date" class="form-control flatpickr flatpickr-input" placeholder="Select From Date">
                </div>
              	<div class="col-md-3">
                	<input type="text" id="to_date" class="form-control flatpickr flatpickr-input" placeholder="Select To Date">
                </div>
              	<div class="col-md-2">
                  <button type="button" id="submit-btn" class="btn btn-md btn-block btn-primary" style="height: 45.4px;">Submit</button>
                </div>
              </div>
              <div class="table-responsive mb-4 mt-4">
                <table id="html5-extension" class="table table-hover non-hover" style="width:100%">
                  <thead>
                    <tr>
                      <th>S.No</th>
                      <th>Invoice no</th>
                      <th>Date/Time</th>
                      <th>Category</th>
                      <th>Fuel Company</th>
                      <th>Total Quantity</th>
                      <th>Total Amount</th>
                      <th>Status</th>
                      <th>Details</th>
                    </tr>
                  </thead>
                    <?php $sno = 1; ?>
                  <tbody id="body_result">
                    @foreach ($purchases as $purchase)
                    	<tr>
                    		<td>{{ $sno++ }}</td>
                    		<td>{{ $purchase->invoice_number }}</td>
                    		<td>{{ \Carbon\Carbon::parse($purchase->datetime)->format('d-m-Y H:i') }}</td>
                    		<td>{{ $purchase->category->name }}</td>
                    		<td>{{ $purchase->fuelCompany->name }}</td>
                    		<td>{{ $purchase->total_quantity }}</td>
                    		<td>${{ number_format($purchase->total_amount,2) }}</td>
                          	<td>
                                  <span class="badge badge-success">Paid</span>
                          	</td>
                    		<td style="white-space: nowrap !important;">
                          		<a href="{{ route('purchases.edit',$purchase->id) }}" class="btn btn-sm btn-primary btn-custom" style="margin-bottom: 4px;">Edit</a>
                          		<a href="{{ route('purchases.show',$purchase->id) }}" class="btn btn-sm btn-primary btn-custom">View</a>
                          	</td>
                    	</tr>
                    @endforeach
                  </tbody>
                </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection
@section('scripts')

    <script src="{{ URL::asset('plugins/table/datatable/datatables.js') }}"></script>
    <script src="{{ URL::asset('plugins/table/datatable/button-ext/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('plugins/table/datatable/button-ext/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('plugins/table/datatable/button-ext/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('plugins/table/datatable/button-ext/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('plugins/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ URL::asset('plugins/flatpickr/custom-flatpickr.js') }}"></script>
    <script src="{{asset('assets/js/jquery.multifile.js')}}"></script>
    <script>
        var f1 = flatpickr(document.getElementById('from_date'), {
             enableTime: true,
             dateFormat: "d-m-Y H:i"
        });

      	var f2 = flatpickr(document.getElementById('to_date'), {
             enableTime: true,
             dateFormat: "d-m-Y H:i"
        });

        $('#html5-extension').DataTable({
          "order": [0, "desc"],
            responsive: true,
            dom:
            '<"row"<"col-md-12"<"row"  <"col-md-4"l>  <"col-md-4 text-center"B>  <"col-md-4"f>>>' +
            '<"col-md-12"tr>' +
            '<"col-md-12 mt-2"<"row mt-3"  <"col-md-5"i>  <"col-md-7 text-right"p>>> >',
            buttons: {
                buttons: [{
                    extend: 'csv',
                    className: 'btn'
                },{
                    extend: 'excel',
                    className: 'btn'
                }]
        },
            "oLanguage": {
                "oPaginate": {
                    "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                    "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'
                },
                "sInfo": "Showing page _PAGE_ of _PAGES_",
                "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                "sSearchPlaceholder": "Search...",
                "sLengthMenu": "Results :  _MENU_",
            },
            "stripeClasses": [],
            "order":[[1, 'desc']],//asc or desc
            "lengthMenu": [10, 20, 30, 50],
            "pageLength": 10
        });
    </script>
    <script>

    	$(".select-change").change(function () {
       		var range = $("#select-range").val();
            $("#select-range").css('border-color', '#bfc9d4');
            var company = $("#select-company").val();
            if (range == 'Custom Range') {
              $('#custom-range').css('display','flex');
            } else{

              $('#custom-range').css('display','none');
              $('#body_result').html('<div class="loader mx-auto"></div>');
              $.post('{{ url('/filter') }}',{_token:'{{ csrf_token() }}', type:range,company:company}, function(data) {
                $('#body_result').html(data);
              });
            }
        });


      	$('#submit-btn').click(function () {
        	var from = $("#from_date").val();
        	var to = $("#to_date").val();
          	var value = 'Custom Range';
          var company = $("#select-company").val();
          	if (from == '')
            	$("#from_date").css('border-color','#e91e63');
          	else
            	$("#from_date").css('border-color','#bfc9d4');

          	if (to == '')
            	$("#to_date").css('border-color','#e91e63');
          	else
            	$("#to_date").css('border-color','#bfc9d4');

          	if (from != '' && to != '') {
                  	$('#body_result').html('<div class="loader mx-auto"></div>');
              	$.post('{{ url('/filter') }}',{_token:'{{ csrf_token() }}', type:value,from:from,to:to,company:company}, function(data) {
                	$('#body_result').html(data);
              	});
            }
        });

    </script>

@endsection
