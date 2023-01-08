@extends('layouts.layout.main')
@section('title','Mass Matches')
@section('css')

	<link href="{{ URL::asset('plugins/jquery-ui/jquery-ui.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/apps/contacts.css') }}" rel="stylesheet" type="text/css" />
	<style>
        .purchase_order {
            padding: 15px;
            background-color:  #8ecfd7;
            border-radius: 5px;
            cursor: pointer;
        }

        .purchase_order:hover {

            background-color:  #65c1cd;

        }

        .sale-order {
            padding: 15px;
            background-color: #a8e5b5;
            border-radius: 5px;
            cursor: pointer;
        }

        .sale-order:hover {
            background-color: #89de9b;
        }

        .heading_design {
            text-align: center;
            background-color: #32C36C;
            color: white;
            padding: 6px;
            border-radius: 5px;
            padding-top: 12px;
        }

        .page-item.active .page-link {
            z-index: 3;
            color: #fff;
            background-color: #32C36C;
            border-color: #32C36C;
        }

      	.table > tbody > tr > td {
          	color: #000 !important;
        	font-weight: 900 !important;
        }

      	#content:before {
          background: none !important;
        }

    </style>

@endsection
@section('contents')

  <div class="row layout-top-spacing" id="cancel-row">
      <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
        <div class="white_box mb_20">
        <div class="widget-content widget-content-area br-6" >
          <div class="widget-header">
            <div class="row">
              <div class="col-xl-4 col-md-4 col-sm-4 col-12">
                <h3>List of Purchase order Matched</h3>
              </div>
              <div class="col-xl-8 col-md-8 col-sm-8 col-12">
                <form action="{{ url('/mass-match/all') }}" method="GET" class="">
                	<div class="text-sm-right filtered-list-search layout-spacing">
                        <div class="row">
                            <div class="col-md-5">
                                <?php
                                  $sup_companies = DB::table('supplier_companies')->select('id','name')->orderBy('name')->get();
                                ?>
                                <select name="fuel_company" class="form-control">
                                  <option value="">-- Select --</option>
                                  @foreach ($sup_companies as $value)
                                      <option value="{{ $value->id }}" @if(isset($_GET['fuel_company'])) {{ ($_GET['fuel_company'] == $value->id) ? 'selected' : '' }} @endif>
                                          {{ $value->name }}
                                      </option>
                                  @endforeach
                                </select>
                            </div>
                            <div class="col-md-5">
                            	<div class="" style="width: 100%; border: 1px solid #bfc9d4; border-radius: 6px;">
                                    <input type="text" name="keywords" placeholder="Search..." class="form-control product-search" @if(isset($_GET['keywords'])) value="{{ $_GET['keywords'] }}" @endif id="input-searchs">
                               	</div>
                            </div>
                            <div class="col-md-2">
                            	<button type="submit" class="btn btn-md btn-block btn-primary" style="height: 46.6px;">Search</button>
                          	</div>
                        </div>
                	</div>
                  </form>
              </div>
            </div>
          </div>
          <hr>
          <div class="row text-center" style="background-color: #008e20; padding: 6px; border-radius: 5px; margin: 0px;">
            <div class="col-md-6">
              <h4 style="color:white;">Purchase Orders</h4>
            </div>
            <div class="col-md-6">
              <h4 style="color:white;">
                Invoices
              </h4>
            </div>
          </div>
          <div class="row">
              <div class="col-md-12">
                  <div class="searchable-items grid" id="live_stream" style="display: unset;">
                      @forelse ($purchases as $purchase)
                      	@php
                      		$matches = \App\MassMatch::where('purchase_record_id',$purchase->id)->get();
                      	@endphp
                      <div class="row justify-content-center mt-3 mb-3  items">
                       <div class="col-md-5">
                         <br>
                         <div class="purchase_order">
                           <div class="row mb-1">
                             <div class="col-md-6">
                               <b>Date</b>
                             </div>
                             <div class="col-md-6">{{ \Carbon\Carbon::parse($purchase->datetime)->format('d-m-Y') }}</div>
                           </div>
                           <div class="row mb-1">
                             <div class="col-md-6">
                               <b>Fuel Company</b>
                             </div>
                             <div class="col-md-6">{{ $purchase->fuelCompany->name }}</div>
                            </div>
                           <div class="row mb-1">
                             <div class="col-md-6">
                               <b>Invoice No</b>
                             </div>
                             <div class="col-md-6">{{ $purchase->invoice_number }}</div>
                            </div>
                           <div class="row mb-1">
                             <div class="col-md-6">
                               <b> Purchase Order</b>
                             </div>
                           <div class="col-md-6">{{ $purchase->purchase_no }}</div>
                          </div>
                          <div class="row mb-1">
                            <div class="col-md-6">
                              <b> Total Quantities</b>
                            </div>
                            <div class="col-md-6">{{ $purchase->total_quantity }}</div>
                           </div>
                           <div class="row mb-1">
                             <div class="col-md-6">
                               <b> Total Amount</b>
                             </div>
                             <div class="col-md-6">${{ number_format($purchase->total_amount,2) }}</div>
                            </div>
                           <div class="row mb-1">
                             <div class="col-md-6">
                               <b> Assign Multiple Invoices </b>
                             </div>
                             <div class="col-md-6">
                               @php
                               	$i = 1;
                               	$j = 1;
                               @endphp
                               <a id="purchase-details_{{ $purchase->id }}" class="btn btn-primary mb-2 mr-2" data-bs-toggle="modal" data-bs-target="#viewDetails1" onclick="viewDetails1('{{ $purchase->id }}','{{ $purchase->products->count() }}')" data-fuel="{{ $purchase->fuelCompany->name }}" data-invoice="{{ $purchase->invoice_number }}" data-purchase="{{ $purchase->purchase_no }}" @foreach ($purchase->products as $product) data-product_{{ $purchase->id }}_{{ $i++ }}_name="{{ $product->product->name }}" data-product_{{ $purchase->id }}_{{ $j++ }}_qty="{{ $product->qty }}" @endforeach data-quantities="{{ $purchase->total_quantity }}" data-amount="{{ number_format($purchase->total_amount,2) }}"   style="padding: 5px 8px; color:white">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-eye">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                               </a>
                             </div>
                           </div>
                         </div>
                        </div>
                        <div class="col-md-2">
                          <button type="button" class="text-center btn btn-md btn-primary" data-bs-toggle="modal" data-bs-target="#confirmReconsileMatch" onclick="reConsile('{{ $purchase->id }}')" style=" margin-left: 15%; position: absolute; top: 40%;"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-thumbs-down"><path d="M10 15v4a3 3 0 0 0 3 3l4-9V2H5.72a2 2 0 0 0-2 1.7l-1.38 9a2 2 0 0 0 2 2.3zm7-13h2.67A2.31 2.31 0 0 1 22 4v7a2.31 2.31 0 0 1-2.33 2H17"></path></svg> Re-consile</button>
                          </div>
                        <div class="col-md-5">
                          @foreach ($matches as $match)
                          	@php
                          		$sale = $match->record;
                          	@endphp
                          	<div class="row">
                              <div class="col-md-12">
                                <label style="width: 100%;">
                                <input type="radio" class="sale_order_{{ $purchase->id }}" checked name="sale_order_{{ $purchase->id }}" value="{{ $sale->id }}">
                                <div class="sale-order">
                                  <div class="row mb-1">
                                    <div class="col-md-6">
                                      <b>Date</b>
                                    </div>
                                    <div class="col-md-6">{{ \Carbon\Carbon::parse($sale->datetime)->format('d-m-Y') }}</div>
                                  </div>
                           			<div class="row mb-1">
                             			<div class="col-md-6">
                               				<b>Company</b>
                             			</div>
                             			<div class="col-md-6">{{ $sale->subCompany->company->name ?? '' }}</div>
                                  	</div>
                           			<div class="row mb-1">
                             			<div class="col-md-6">
                               				<b>Sub Company</b>
                             			</div>
                             			<div class="col-md-6">{{ $sale->subCompany->name ?? ''}}</div>
                                  	</div>
                           			<div class="row mb-1">
                             			<div class="col-md-6">
                               				<b>Fuel Company</b>
                             			</div>
                             			<div class="col-md-6">{{ $sale->supplierCompany->name }}</div>
                                  	</div>
                                  <div class="row mb-1">
                                    <div class="col-md-6">
                                      <b>Invoice No</b>
                                    </div>
                                    <div class="col-md-6">{{ $sale->invoice_number }}</div>
                                  </div>
                                  <div class="row mb-1">
                                    <div class="col-md-6">
                                      <b>Total Quantities</b>
                                    </div>
                                    <div class="col-md-6">{{ $sale->products()->sum('qty') }}</div>
                                  </div>
                                  <div class="row mb-1">
                                    <div class="col-md-6">
                                      <b> Total Amount</b>
                                    </div>
                                    <div class="col-md-6">${{ number_format($sale->total_amount,2) }}</div>
                                  </div>
                                   <div class="row mb-1">
                                     <div class="col-md-6">
                                       <b>View Detail</b>
                                     </div>
                                     <div class="col-md-6">
                                       @php
                                       	$i = 1;
                                       	$j = 1;
                                       @endphp
                                       <a data-bs-toggle="modal" data-bs-target="#viewDetails" class="btn btn-primary mb-2 mr-2" id="invoice-details_{{ $sale->id }}" onclick="viewDetails('{{ $sale->id }}','{{ $sale->products->count() }}')" data-invoice="{{ $sale->invoice_number }}" data-company="{{ $sale->subCompany->company->name ??'' }}" data-subcompany="{{ $sale->subCompany->name ?? '' }}" data-driver="{{ $sale->user->name }}" data-time="{{ \Carbon\Carbon::parse($sale->datetime)->format('d-m-Y H:i') }}" data-trip="{{ $sale->load_number }}" data-order="{{ $sale->order_number }}" data-fuelcompany="{{ $sale->supplierCompany->name }}" data-amount="{{ $sale->total_amount }}" data-paidamount="{{ $sale->paid_amount }}" data-load="{{ $sale->splitfullload }}" @foreach ($sale->products as $product) data-product_{{ $sale->id }}_{{ $i++ }}_name="{{ $product->product->name }}" data-product_{{ $sale->id }}_{{ $j++ }}_qty="{{ $product->qty }}" @endforeach  style="padding: 5px 8px; color:white">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-eye">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                       </a>
                                     </div>
                                   </div>
                                </div>
                                </label>
                              </div>
                          	</div>
                        	@endforeach
                        </div>
                        </div>
                      <hr>
                      @empty
                      	<p style="padding: 20px; background: #9999992e;">No purchase order record found.</p>
                      @endforelse
                  </div>
              </div>
          </div>
          <div class="row text-center">
            <div class="col-md-12">
              	<br>
              	@if(isset($_GET['fuel_company']) || isset($_GET['keywords']))
		        	{{ $purchases->appends($_GET)->links() }}
              	@else
		        	{{ $purchases->links() }}
              	@endif
            </div>
          </div>
        </div>
      </div>
  </div>

  <div class="modal fade" id="viewDetails1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Purchases Details</h5>
                  <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
              </div>
              <div class="modal-body">
                  <div class="form-group">
                  		<table id="zero-config-purchase" class="table table-hover text-center table-bordered" style="width:100%" role="grid">
                          	<tbody id="tb-body-purchase"></tbody>
                    	</table>
                  </div>
              </div>
          </div>
        </div>
      </div>
  </div>

  <div class="modal fade" id="viewDetails" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Invoice Details</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
              </div>
              <div class="modal-body">
                  <div class="form-group">
                  		<table id="zero-config" class="table table-hover text-center table-bordered" style="width:100%" role="grid">
                          	<tbody id="tb-body"></tbody>
                    	</table>
                  </div>
              </div>
          </div>
      </div>
  </div>


  <div class="modal fade" id="confirmReconsileMatch" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Reconsile Mass Match</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
              </div>
              <form action="{{ route('mass-match.update','reconsile') }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="purchase_record_id" id="purchase_record_id" value="">
                <div class="modal-body">
                  <div class="form-group">
                    <p id="text-info">Are you sure to re-consile?</p>
                  </div>
                </div>
                <div class="modal-footer" id="loading-btn">
                    <button type="submit" class="btn btn-primary">Confirm</button>
                </div>
              </form>
          </div>
      </div>
  </div>

@endsection
@section('scripts')

	<script src="{{ URL::asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/js/apps/contact.js') }}"></script>
	<script>

      	function reConsile(purchase) {
        	$("#purchase_record_id").val(purchase);
        }

      	function viewDetails1(id,count)
     	{
          	$('#zero-config-purchase').html('<tbody id="tb-body-purchase"></tbody>');
          	$('#tb-body-purchase').html('');
        	$('#tb-body-purchase').after('<tr><th>Total Amount</th><td>'+$('#purchase-details_'+id).attr('data-amount')+'</td></tr>');
        	$('#tb-body-purchase').after('<tr><th>Total Quantities</th><td>'+$('#purchase-details_'+id).attr('data-quantities')+'</td></tr>');
          	var i;
          	var j = count;
          	var k = count;
          	for (i = count; i >= 1; i--) {
        		var name = $('#purchase-details_'+id).attr('data-product_'+id+'_'+j+'_name');
        		var qty = $('#purchase-details_'+id).attr('data-product_'+id+'_'+k+'_qty');
              	$("#tb-body-purchase").after('<tr><th>'+name+'</th><td>'+qty+'</td></tr>');
              	j--;
              	k--;
            }
          	$('#tb-body-purchase').after('<tr><th>Purchase No</th><td>'+$('#purchase-details_'+id).attr('data-purchase')+'</td></tr>');
        	$('#tb-body-purchase').after('<tr><th>Invoice No</th><td>'+$('#purchase-details_'+id).attr('data-invoice')+'</td></tr>');
          	$('#tb-body-purchase').after('<tr><th>Fuel Company</th><td>'+$('#purchase-details_'+id).attr('data-fuel')+'</td></tr>');

        }

      	function viewDetails(id,count) {
          	$('#zero-config').html('<tbody id="tb-body"></tbody>');
          	$('#tb-body').html('');
        	$('#tb-body').after('<tr><th>Split Load /Full Load</th><td>'+$('#invoice-details_'+id).attr('data-load')+'</td></tr>');
        	$('#tb-body').after('<tr><th>Paid Amount</th><td>$'+$('#invoice-details_'+id).attr('data-paidamount')+'</td></tr>');
        	$('#tb-body').after('<tr><th>Total Amount</th><td>$'+$('#invoice-details_'+id).attr('data-amount')+'</td></tr>');
          	var i;
          	var j = count;
          	var k = count;
          	for (i = count; i >= 1; i--) {
        		var name = $('#invoice-details_'+id).attr('data-product_'+id+'_'+j+'_name');
        		var qty = $('#invoice-details_'+id).attr('data-product_'+id+'_'+k+'_qty');
              	$("#tb-body").after('<tr><th>'+name+'</th><td>'+qty+'</td></tr>');
              	j--;
              	k--;
            }
          	$('#tb-body').after('<tr><th>Fuel Company</th><td>'+$('#invoice-details_'+id).attr('data-fuelcompany')+'</td></tr>');
        	$('#tb-body').after('<tr><th>Order #</th><td>'+$('#invoice-details_'+id).attr('data-order')+'</td></tr>');
        	$('#tb-body').after('<tr><th>Trip #</th><td>'+$('#invoice-details_'+id).attr('data-trip')+'</td></tr>');
        	$('#tb-body').after('<tr><th>Date Time</th><td>'+$('#invoice-details_'+id).attr('data-time')+'</td></tr>');
        	$('#tb-body').after('<tr><th>Operator Name</th><td>'+$('#invoice-details_'+id).attr('data-driver')+'</td></tr>');
        	$('#tb-body').after('<tr><th>Sub Company</th><td>'+$('#invoice-details_'+id).attr('data-subcompany')+'</td></tr>');
        	$('#tb-body').after('<tr><th>Company</th><td>'+$('#invoice-details_'+id).attr('data-company')+'</td></tr>');
          	$('#tb-body').after('<tr><th>Invoice #</th><td>'+$('#invoice-details_'+id).attr('data-invoice')+'</td></tr>');

        }

	</script>

@endsection
