@extends('layouts.template')
@section('title','Month Report Details')
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

        .table > tfoot > tr > th {
          color: #000 !important;
          font-weight: 800 !important;
          background: #f5f5f5 !important;
        }

	</style>

@endsection
@section('contents')

	<?php
		$from = (isset($_GET['from'])) ? $_GET['from'] : '';
		$to = (isset($_GET['to'])) ? $_GET['to'] : '';
		if ($series == 'sales')
        	$records = \App\Record::whereBetween('datetime',[$from, $to])->where('status',1)->where('deleted_status',0)->get();
		else
        	$records = \App\PurchaseRecord::whereBetween('datetime',[$from, $to])->where('status',1)->where('deleted_status',0)->get();

	?>
	<div class="row layout-top-spacing" id="cancel-row">
      <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
      	<div class="widget-content widget-content-area br-6">
          <h4>{{ ucfirst($series) }} {{ ucfirst($month) }} Report</h4>
          <div class="table-responsive mb-4 mt-4">
            @if($series == 'sales')
              <table id="html5-extension" class="table table-hover non-hover" style="width:100%">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Company</th>
                    <th>Sub Company</th>
                    <th>Invoice</th>
                    <th>Driver</th>
                    <th>DateTime</th>
                    <th>Trip#</th>
                    <th>Order#</th>
                    @foreach ($products as $product)
                      <th>{{ $product->name }}</th>
                      <?php
                          ${"prod_$product->id"} = 0;
                      ?>
                    @endforeach
                    <th>Total Liters</th>
                    <th>Total Amount</th>
                    <th>Total Paid</th>
                    <th>Split Load</th>
                    <th>Email</th>
                    <th>Details</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                      $sno = 1;
                      $liters = 0;
                      $amount = 0;
                      $paid = 0;
                  ?>
                  @foreach ($records as $sale)
                      <?php $total = 0;?>
                      <tr>
                          <td>{{ $sno++ }}</td>
                          <td>{{ ($sale->company_id) ? $sale->company->name : $sale->subCompany->company->name }}</td>
                          <td>{{ $sale->subCompany->name }}</td>
                          <td>{{ $sale->invoice_number }}</td>
                          <td>{{ $sale->user->name }}</td>
                          <td>{{ \Carbon\Carbon::parse($sale->datetime)->format('d-m-Y H:i') }}</td>
                          <td>{{ $sale->load_number }}</td>
                          <td>{{ $sale->order_number }}</td>
                          @foreach ($products as $product)
                              <?php
                                  $data = $sale->products->where('product_id',$product->id)->first();
                                  $qty = ($data) ? $data->qty : 0;
                                  $total = $total + $qty;
                                  ${"prod_$product->id"} = ${"prod_$product->id"} + $qty;
                              ?>
                              <th>{{ $qty }}</th>
                          @endforeach
                          <?php
                              $liters = $liters + $total;
                              $amount = $amount + $sale->total_amount;
                              $paid = $paid + $sale->paid_amount;
                          ?>
                          <td>{{ $total }}L</td>
                          <td>${{ number_format($sale->total_amount,2) }}</td>
                          <td>${{ number_format($sale->paid_amount,2) }}</td>
                          <td>{{ $sale->splitfullload }}</td>
                          <td>
                              @if ($sale->email_status == 1)
                                  <span class="badge badge-success">Sent</span>
                              @else
                                  <span class="badge badge-danger">Not Sent</span>
                              @endif
                          </td>
                          <td>
                              <a href="{{ route('records.show',$sale->id) }}" target="_blank" class="badge badge-primary">
                                  View
                              </a>
                          </td>
                      </tr>
                  @endforeach
                </tbody>
              </table>
            @else
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
                      <th>Details</th>
                    </tr>
                  </thead>
                    <?php $sno = 1; ?>
                  <tbody id="body_result">
                    @foreach ($records as $purchase)
                    	<tr>
                    		<td>{{ $sno++ }}</td>
                    		<td>{{ $purchase->invoice_number }}</td>
                    		<td>{{ \Carbon\Carbon::parse($purchase->datetime)->format('d-m-Y H:i') }}</td>
                    		<td>{{ $purchase->category->name }}</td>
                    		<td>{{ $purchase->fuelCompany->name }}</td>
                    		<td>{{ $purchase->total_quantity }}</td>
                    		<td>${{ number_format($purchase->total_amount,2) }}</td>
                    		<td>
                          		<a href="{{ route('purchases.edit',$purchase->id) }}" class="badge badge-primary" style="margin-bottom: 4px;">Edit</a>
                          		<a href="{{ route('purchases.show',$purchase->id) }}" class="badge badge-primary">View</a>
                          	</td>
                    	</tr>
                    @endforeach
                  </tbody>
                </table>
            @endif
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
            "lengthMenu": [10, 20, 30, 50],
            "pageLength": 10
        });
    </script>

@endsection
