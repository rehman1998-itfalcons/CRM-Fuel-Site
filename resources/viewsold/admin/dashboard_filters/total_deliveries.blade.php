@extends('layouts.template')
@section('title','Total Deliveries')
@section('css')

    <link rel="stylesheet" type= "text/css" href="{{ URL::asset('plugins/table/table/datatable/datatables.css') }}">
    <link rel="stylesheet" type= "text/css" href="{{ URL::asset('plugins/table/table/datatable/custom_dt_html5.css') }}">
    <link rel="stylesheet" type= "text/css" href="{{ URL::asset('plugins/table/table/datatable/dt-global_style.css') }}">
	<link href="{{ URL::asset('plugins/loaders/custom-loader.css') }}" rel="stylesheet" type="text/css" />
	<style>
		table.dataTable thead .sorting_asc:before {
          display: none;
        }

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

	<?php
		use App\Record;
		$category = (isset($_GET['category'])) ? $_GET['category'] : 0;
		if ($category == 0)
			$records = Record::where('deleted_status',0)->where('status',1)->get();
		elseif($category != 0)
			$records = Record::where('category_id',$category)->where('deleted_status',0)->where('status',1)->get();
	?>
  <div class="row layout-top-spacing" id="cancel-row">
    <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
      <div class="widget-content widget-content-area br-6" >
        <div class="widget-header">
          <div class="row">
            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
              <h4>Total Deliveries</h4>
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
                      <th>Trip Number</th>
                      <th>Order Number</th>
                      <th>Category</th>
                      <th>Sub Company</th>
                      <th>GST Status</th>
                      <th>Total Amount</th>
                      <th>Details</th>

                    </tr>
                  </thead>
                  <tbody id="body_result">
                    <?php $sno = 1; ?>
                    @foreach ($records as $sale)
                    	<tr>
                    		<td>{{ $sno++}}</td>
                    		<td>{{ $sale->invoice_number }}</td>
                    		<td>{{ \Carbon\Carbon::parse($sale->datetime)->format('d-m-Y H:i') }}</td>
                    		<td>{{ $sale->load_number }}</td>
                    		<td>{{ $sale->order_number }}</td>
                          	<td>{{ ($sale->category_id) ? $sale->category->name : '-' }}</td>
                            <td>{{ ($sale->sub_company_id) ? $sale->subCompany->name : '-' }}</td>
                    		<td>{{ $sale->gst_status }}</td>
                    		<td>${{ number_format($sale->total_amount,2) }}</td>
                          <td style="white-space: nowrap !important;">
                          		<a href="{{ route('records.edit',$sale->id) }}" class="btn btn-sm btn-primary btn-custom" style="margin-right: 5px;">Edit</a>
                          		<a href="{{ route('records.show',$sale->id) }}" class="btn btn-sm btn-primary btn-custom">View</a>
                          	</td>
                    	</tr>
                    @endforeach
                  </tbody>
                </table>
            </div>
          </div>
        </div>
        <hr>
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
    <script>
        $('#html5-extension').DataTable({
          	"order": [[ 0, "desc" ]],
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



@endsection
