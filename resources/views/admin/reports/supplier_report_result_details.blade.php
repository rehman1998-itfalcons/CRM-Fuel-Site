@extends('layouts.template')
@section('title',$company->name.' Report')
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
	</style>

@endsection
@section('contents')

	@php
		$type = $_GET['type'];
		$from = $_GET['from'];
		$to = $_GET['to'];
		$dfrom = \Carbon\Carbon::parse($from)->format('Y-m-d');
		$dto = \Carbon\Carbon::parse($to)->format('Y-m-d');

		if ($type == 'Custom Range') {
			$sales = $company->sales->whereBetween('datetime', [$dfrom, $dto])->where('status',1)->where('deleted_status',0);
		} elseif ($type == 'Today') {
  			$today_start = \Carbon\Carbon::now()->format('Y-m-d 00:00:00');
        	$today_end = \Carbon\Carbon::now()->format('Y-m-d 23:59:59');
			$sales = $company->sales->whereBetween('created_at', [$today_start, $today_end])->where('status',1)->where('deleted_status',0);
		} elseif ($type == 'Yesterday') {
			$yesterday = date("Y-m-d", strtotime( '-1 days' ));
			$sales = $company->sales->where('datetime', $yesterday)->where('status',1)->where('deleted_status',0);
		} elseif ($type == 'Last 7 Days') {
			$date = \Carbon\Carbon::today()->subDays(7);
			$sales = $company->sales->where('datetime', '>=', $date)->where('status',1)->where('deleted_status',0);
		} elseif ($type == 'Last 30 Days') {
			$date = \Carbon\Carbon::today()->subDays(30);
			$sales = $company->sales->where('datetime', '>=', $date)->where('status',1)->where('deleted_status',0);
		} elseif ($type == 'This Month') {
			$sales = $company->sales->where('datetime', '>', \Carbon\Carbon::now()->startOfMonth())->where('status',1)->where('deleted_status',0);
		} elseif ($type == 'Last Month') {
			$sales = $company->sales->where('datetime', '=', \Carbon\Carbon::now()->subMonth()->month)->where('status',1)->where('deleted_status',0);
		}

	@endphp
	<div class="row layout-top-spacing" id="cancel-row">
      <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
      	<div class="widget-content widget-content-area br-6">
           <h4>{{ $company->name }} Report</h4>
          <div class="table-responsive mb-4 mt-4">
            <table id="html5-extension" class="table table-hover non-hover" style="width:100%">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Invoice</th>
                  <th>Driver</th>
                  <th>DateTime</th>
                  <th>Trip#</th>
                  <th>Order#</th>
                  @foreach ($products as $product)
                  	<th>{{ $product->name }}</th>
                  @endforeach
                  <th>Total Calculation</th>
                  <th>Details</th>
                </tr>
              </thead>
              <tbody>
                <?php $sno = 1; ?>
                @foreach ($sales as $sale)
                	<?php $total = 0;?>
                	<tr>
                		<td>{{ $sno++ }}</td>
                		<td>{{ $sale->invoice_number }}</td>
                		<td>{{ $sale->user->name }}</td>
                		<td>{{ \Carbon\Carbon::parse($sale->datetime)->format('m-d-Y H:i') }}</td>
                		<td>{{ $sale->load_number }}</td>
                		<td>{{ $sale->order_number }}</td>
                        @foreach ($products as $product)
                      		<?php
								$data = $sale->products->where('product_id',$product->id)->first();
								$qty = ($data) ? $data->qty : 0;
								$total = $total + $qty;
                      		?>
                          	<th>{{ $qty }}</th>
                        @endforeach
                      	<td>{{ $total }}L</td>
                      	<td>
	                      	<a href="{{ route('records.show',$sale->id) }}" target="_blank" class="btn btn-sm btn-primary">
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

@endsection
@section('scripts')

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
