@extends('layouts.template')
@section('title','Supplier Companies Report')
@section('css')

    <link rel="stylesheet" type= "text/css" href="{{ URL::asset('plugins/table/table/datatable/datatables.css') }}">
    <link rel="stylesheet" type= "text/css" href="{{ URL::asset('plugins/table/table/datatable/custom_dt_html5.css') }}">
    <link rel="stylesheet" type= "text/css" href="{{ URL::asset('plugins/table/table/datatable/dt-global_style.css') }}">
	<style>
		table.dataTable thead .sorting_asc:before {
          display: none;
        }
      	.table > tbody > tr > td {
          color: #000 !important;
        }
	</style>
	<style>
		.btn-cust {
          padding: 2px 6px !important;
          font-size: 12px !important;
          box-shadow: 0px 5px 20px 0 rgba(0, 0, 0, 0.2) !important;
        }
        .table > tbody:before {
            content: "" !important;
        }
        .page-item:first-child .page-link, .page-item:last-child .page-link {
            border-radius: 5px !important;
            padding: 7px 12px !important;
        }
		.widget-card-four .w-info p {
    		color: #1e1e1f !important;
        }
		.widget-card-four .w-icon {
    		color: #2e8e3f !important;
    		background-color: #d2efd7 !important;
        }
	</style>
    <link href="{{ URL::asset('plugins/apex/apexcharts.css') }}" rel="stylesheet" type="text/css">
    <style>
        .apexcharts-canvas {
            margin: 0 auto;
        }
    </style>

@endsection
@section('contents')

	<div class="row layout-top-spacing" id="cancel-row">
      <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
      	<div class="widget-content widget-content-area br-6">
           <h4>Supplier Companies Report</h4>
          <div class="table-responsive mb-4 mt-4">
            <table id="html5-extension" class="table table-hover non-hover" style="width:100%">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Company</th>
                  @foreach ($products as $product)
                  	<th>{{ $product->name }}</th>
                  @endforeach
                  <th>Total Calculation</th>
                  <th>Details</th>
                </tr>
              </thead>
              <tbody>
                <?php
					$sno = 1;
					$companies = [];
					$type = $_GET['type'];
					$from = $_GET['from'];
                    $to = $_GET['to'];
					$url = 'type='.$type.'&from='.$from.'&to='.$to;
                ?>
                @foreach ($records as $supplier_record)
                	@php
                		$company = $supplier_record->first();
                		$total_liters = 0;
                	@endphp
	                @foreach ($products as $product)
                  	<?php
                  		${"prod_$product->id"} = 0;
                  	?>
                	@endforeach
                	@foreach($supplier_record as $rec)
                          @foreach ($products as $product)
                              @php
                                  	$data = $rec->products->where('product_id',$product->id)->first();
                                  	$qty = ($data) ? $data->qty : 0;
                					${"prod_$product->id"} = ${"prod_$product->id"} + $qty;
                					$total_liters = $total_liters + ${"prod_$product->id"};
                              @endphp
                          @endforeach
                	@endforeach
                	@php
                		$companies[$company->supplierCompany->name] = $total_liters;
                	@endphp
                	<tr>
                		<td>{{ $sno++ }}</td>
                		<td>{{ $company->supplierCompany->name }}</td>
                      	@foreach ($products as $product)
                      		<td>{{ ${"prod_$product->id"} }}</td>
                      	@endforeach
                		<td>{{ $total_liters }}L</td>
                		<td>
                      		<a href="{{ url('/supplier-report-details/'.$company->supplierCompany->id.'?'.$url) }}" target="_blank" class="btn btn-sm btn-primary">
                          		<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                          	</a>
                      	</td>
                	</tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
	</div>

    @if($records->count() > 0)
    	<div class="row">
      <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
      	<div class="widget-content widget-content-area br-6">
           <h4>Overall Report</h4>
          	<hr>
              <div class="row">
                @foreach ($records as $supplier_record)
                	@php
                		$company = $supplier_record->first();
					@endphp
                	<div class="col-md-4 mb-3">
                        <div class="widget widget-card-four">
                        	<div class="widget-content">
                            	<div class="w-content">
                                	<div class="w-info">
                                    	<h6 class="value">{{ $companies[$company->supplierCompany->name] }}L</h6>
                                        <p class="">{{ $company->supplierCompany->name }}</p>
                                    </div>
                                  	<div class="">
                                    	<div class="w-icon">
                                        	<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trending-up"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
                                        </div>
                                   	</div>
                               	</div>
                           	</div>
                      	</div>
    	        	</div>
                @endforeach
              </div>
        </div>
      </div>
	</div>
    @endif

	@if (!empty($companies))
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
          <div class="statbox widget box box-shadow" style="padding: 20px; background-color: #fff;">
            <div class="widget-header">
              <h4 style="font-size: 1.5rem; font-weight: 500; padding: 0px;">Graph Statistics</h4>
            </div>
            <div class="widget-content">
              <div id="s-line" class="" style="min-height: 400px;">
              </div>
            </div>
          </div>
        </div>
      </div>
	@endif

@endsection
@section('scripts')

	<script src="{{ URL::asset('plugins/apex/apexcharts.min.js') }}"></script>
	@if (!empty($companies))
      <script type="text/javascript">

        var sline = {
          chart: {
            height: 400,
            type: 'bar',
            zoom: {
              enabled: false
            },
            toolbar: {
              show: false,
            }
          },
          dataLabels: {
            enabled: false
          },
          stroke: {
            curve: 'straight'
          },
          series: [{
            name: "Total Liters",
            data: [
              	<?php
              		foreach ($companies as $company => $liter) {
                      	?>
              			'{{ $liter }}',
              			<?php
                    }
              	?>
            ]
          }],
          title: {
            text: 'Total Liters',
            align: 'left'
          },
          grid: {
            row: {
              colors: ['#f1f2f3', 'transparent'],
              opacity: 0.5,
              margin: ['0px 20px']
            },
          },
          xaxis: {
            categories: [
            	
              	<?php
              		foreach ($companies as $company => $liter) {
                      	?>
              			'{{ $company }}',
              			<?php
                    }
              	?>
            ],
            align: 'center'
          },
          colors: ['#2e8e3f','#b1d8b8'],
        }
        var chart = new ApexCharts(
          document.querySelector("#s-line"),
          sline
        );
        chart.render();

      </script>
	@endif
    <script src="{{ URL::asset('plugins/table/datatable/datatables.js') }}"></script>
    <script src="{{ URL::asset('plugins/table/datatable/button-ext/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('plugins/table/datatable/button-ext/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('plugins/table/datatable/button-ext/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('plugins/table/datatable/button-ext/buttons.print.min.js') }}"></script>
    <script>
        $('#html5-extension').DataTable({
           dom: '<"row"<"col-md-12"<"row"<"col-md-6"B><"col-md-6"f> > ><"col-md-12"rt> <"col-md-12"<"row"<"col-md-5"i><"col-md-7"p>>> >',
            buttons: {
                buttons: [
                    { extend: 'csv', className: 'btn' },
                    { extend: 'excel', className: 'btn' },
                    { extend: 'print', className: 'btn' }
                ]

            }
        });
    </script>

@endsection
