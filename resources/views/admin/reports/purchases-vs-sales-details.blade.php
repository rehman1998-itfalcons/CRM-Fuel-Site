@extends('layouts.template')
@section('title','Report Details')
@section('css')

	<link rel="stylesheet" href="{{url('resources/views/admin/purchases/assets/rotate/jquery.magnify.css')}}" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css">

    <style>
        .dataTables_length {
            display: inline-block;
        }

        .dt-buttons {
            display: inline-block;
            float: right;
        }

        .table-responsive {
            cursor: grab !important
            overflow: scroll !important;
            overflow-y: hidden !important;
            display: block !important;
            width: 100% !important;
            overflow-x: auto !important;
        }

        .paging_simple_numbers {
            float: right;
        }


        .list-group-item {
            padding: 6px;
        }

        .invoice-th {
            color: #dee2e6 !important;
        }
      
      	.table > tbody > tr > td {
        	color: #000 !important;
          	font-weight: 500;
        }
      
      	.purchase-record {
        	background-color: #1b55e2 !important;
          	color: #fff !important;
          	font-weight: 500 !important;
        }

    </style>

@endsection
@section('contents')

    <div class="row layout-top-spacing" id="cancel-row">
        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
            <div class="widget-content widget-content-area br-6" >
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Purchase Details
                                <a href="{{ route('purchases.vs.sales') }}" class="btn btn-md btn-primary" style="float: right;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left close-message"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                                    Back
                                </a>
                            </h4>
                        </div>
                    </div>
                    <hr>
                </div>
                <table class="table table-bordered">
                    <tbody><tr>
                        <td width="15%"> <b>Fuel Company</b></td>
                        <td>
                            <p class="card-text">{{ $purchase->fuelCompany->name }}</p>
                        </td>
                        <td width="20%"> <b> Date-Time </b></td>
                        <td>
                            <p class="card-text">{{\Carbon\Carbon::parse($purchase->datetime)->format('d-m-Y H:i') }}</p>
                        </td>
                        <td width="10%"> <b> Tax Type </b></td>
                        <td>
                            <p class="card-text">{{ $purchase->gst_status }}</p>
                        </td>
                    </tr>
                    </tbody>
                    <tbody><tr>
                        <td width="15%"> <b>Invoice Number</b></td>
                        <td>
                            <p class="card-text">{{ $purchase->invoice_number }}</p>
                        </td>
                        <td width="20%"> <b> Purchase Number</b></td>
                        <td>
                            <p class="card-text">{{ $purchase->purchase_no }}</p>
                        </td>
                        <td width="10%"> <b> Category</b></td>
                        <td>
                            <p class="card-text">{{ $purchase->category->name }}</p>
                        </td>
                    </tr>
                      <tr>
                        <td colspan="2"></td>
                        <td width="15%"> <b> Total quantity : </b></td>
                        <td>
                            <p class="card-text">{{ $purchase->total_quantity }}</p>
                        </td>
                        <td width="15%"> <b> Total amount : </b></td>
                        <td>
                            <p class="card-text">${{ number_format($purchase->total_amount,2) }}</p>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-md-12">
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
                                <a data-magnify="gallery" data-caption="Image" href="{{URL:: asset('/uploads/purchases/'.$file) }}" class="btn btn-outline-dark mb-2 mr-2" style="padding: 5px 8px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"  height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></a>
                                <a href="{{URL:: asset('/uploads/purchases/'.$file) }}"
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
                    </div>
                </div>
                <br>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th class="purchase-record">Type</th>
                        <th class="purchase-record">Quantity</th>
                        <th class="purchase-record">Rate</th>
                        <th class="purchase-record">GST</th>
                        <th class="purchase-record">Subtotal</th>
                        <th class="purchase-record">GST Amount</th>
                        <th class="purchase-record">Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($purchase->products as $product)
                        @if($product->qty != 0)
                            <tr>
                                <td>{{ $product->product->name }}</td>
                                <td>{{ $product->qty }}</td>
                                <td>{{ $product->rate }}</td>
                                <td>{{ $product->gst }}</td>
                                <td>${{ number_format($product->sub_amount,2) }}</td>
                                <td>${{ number_format($product->gst_amount,2) }}</td>
                                <td>${{ number_format($product->total_amount,2) }}</td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
                
            </div>
        </div>
    </div>
    @php
        $sales = \App\MassMatch::where('purchase_record_id',$purchase->id)->get();
		$sno = 1;
    @endphp
	@forelse ($sales as $sale)
      <?php $record = $sale->record; ?>
      <div class="row">
          <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
              <div class="widget-content widget-content-area br-6" >
                  <div class="widget-header">
                      <div class="row">
                          <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                              <h4>Sale #{{ $sno++ }} Details</h4>
                          </div>
                      </div>
                  </div>
                	<hr>
                  <table class="table table-bordered">
                      <tbody><tr>
                          <td width="15%"> <b>Categoy</b></td>
                          <td>
                              <p class="card-text">{{ $record->category->name }}</p>
                          </td>
                          <td width="15%"> <b>Sub Company</b></td>
                          <td>
                              <p class="card-text">{{ $record->subCompany->name }}</p>
                          </td>
                          <td width="15%"> <b>Fuel Company</b></td>
                          <td>
                              <p class="card-text">{{ $record->supplierCompany->name }}</p>
                          </td>

                      </tr>
                        <tr>

                          <td width="15%"> <b> Tax Type </b></td>
                          <td>
                              <p class="card-text">
                                  <span class="badge badge-info">{{ $record->gst_status }}</span>
                              </p>
                          </td>
                          <td width="15%"> <b> Trip Number </b></td>
                          <td>
                              <p class="card-text">{{ $record->load_number}}
                              </p>
                          </td>
                          <td width="15%"> <b> Order Number </b></td>
                          <td>
                              <p class="card-text">{{ $record->order_number }}
                              </p>
                          </td>


                      </tr>
                      <tr>
                          <td width="15%"> <b> Split Load</b></td>
                          <td>
                              <p class="card-text">{{ $record->splitfullload }}</p>
                          </td>

                          <td width="15%"> <b> Split Load Status </b></td>
                          <td>
                              <p class="card-text">{{ $record->load_number}}
                              </p>
                          </td>
                          <td width="15%"> <b> Split Load Charges </b></td>
                          <td>
                              <p class="card-text">${{ number_format($record->split_load_charges,2) }}</p>
                          </td>
                      </tr>
                      <tr>
                          <td width="15%"> <b> Date-Time </b></td>
                          <td>
                              <p class="card-text">{{ \Carbon\Carbon::parse($record->datetime)->format('d-m-Y H:i') }}</p>
                          </td>
                          <td width="15%"> <b> Email Status</b></td>
                          <td>
                              @if ($record->email_status == 1)
                                  <span class="badge badge-success">Sent</span>
                              @else
                                  <span class="badge badge-danger">Not Sent</span>
                              @endif
                          </td>
                          <td width="15%"> <b> Paid Status</b></td>
                          <td>
                              @if ($record->paid_status == 1)
                                  <span class="badge badge-success">Paid</span>
                              @else
                                  <span class="badge badge-danger">Unpaid</span>
                              @endif
                          </td>

                      </tr>
                      <tr>
                          <td width="15%"><b>Invoice no</b></td>
                          <td width="15%"><p class="card-text">{{ $record->invoice_number }}</p></td>
                          <td width="15%"> <b> Total amount : </b></td>
                          <td>
                              <p class="card-text">${{ number_format($record->total_amount,2) }}</p>
                          </td>
                          <td width="15%"> <b> Paid amount : </b></td>
                          <td>
                              <p class="card-text">${{ number_format($record->paid_amount,2) }}</p>
                          </td>
                      </tr>
                      </tbody>
                  </table>

                  <br>
                  <div class="row">
                      <div class="col-md-6">
                          <label>BILL OF LADING (BOL) :</label><br>
                          @php
                              $i = 1;
                          @endphp
                          @if($record->bill_of_lading)
                              @php
                                  $files = explode("::",$record->bill_of_lading)
                              @endphp
                              @foreach ($files as $file)
                                  @if($i != 1)
                                      <strong>::</strong>
                                  @endif
                                  <a data-magnify="gallery" data-caption="Image" href="{{URL:: asset('uploads/records/'.$file) }}" class="btn btn-outline-dark mb-2 mr-2" style="padding: 5px 8px;"><svg xmlns="http://www.w3.org/2000/svg" width="24"  height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></a>
                                  <a href="{{ URL::asset('/uploads/records/'.$file) }}"
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
                      </div>
                      <div class="col-md-6">
                          <label>DELIVERY DOCKET :</label><br>
                          @php
                              $i = 1;
                          @endphp
                          @if($record->delivery_docket)
                              @php
                                  $files = explode("::",$record->delivery_docket)
                              @endphp
                              @foreach ($files as $file)
                                  @if($i != 1)
                                      <strong>::</strong>
                                  @endif
                                  <a data-magnify="gallery" data-caption="Image" href="{{URL:: asset('uploads/records/'.$file) }}" class="btn btn-outline-dark mb-2 mr-2" style="padding: 5px 8px;">
                                      <svg xmlns="http://www.w3.org/2000/svg" width="24"  height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></a>
                                  <a href="{{URL:: asset('/uploads/records/'.$file) }}"
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
                      </div>
                  </div>
                  <br>
                  <table class="table table-bordered">
                      <thead>
                      <tr>
                          <th class="purchase-record">Product</th>
                          <th class="purchase-record">Quantity</th>
                          <th class="purchase-record">Whole Sale</th>
                          <th class="purchase-record">Discount</th>
                          <th class="purchase-record">Delivery Rate</th>
                          <th class="purchase-record">Brand Charges</th>
                          <th class="purchase-record">Cost</th>
                      </tr>
                      </thead>
                      <tbody>
                      @foreach ($record->products as $product)
                          <tr>
                              <td>{{ $product->product->name }}</td>
                              <td>{{ $product->qty }}</td>
                              <td>${{ $product->whole_sale }}</td>
                              <td>${{ $product->discount }}</td>
                              <td>${{ $product->delivery_rate }}</td>
                              <td>${{ $product->brand_charges  }}</td>
                              <td>${{ $product->cost_of_credit }}</td>
                          </tr>
                      @endforeach
                      </tbody>
                  </table>
              </div>
          </div>
      </div>
	@endforeach

    <div class="row">
        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
            <div class="widget-content widget-content-area br-6" >
                <div class="widget-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <h3>Transactions History</h3>
                        </div>
                    </div>
                    <hr>
                    <div class="table-responsive">
                        <table id="zero_config" class="table table-bordered table-hover">
                            <thead>
                            <tr class="bg-primary">
                                <th class="invoice-th">S.No</th>
                                <th class="invoice-th">Date</th>
                                <th class="invoice-th">Amount</th>
                            </tr>
                            </thead>
                            <?php $sno = 1; ?>
                            <tbody class="table table-striped table-bordered">
                            @forelse ($purchase->transactionHistory as $history)
                                <tr>
                                    <td>{{ $sno++ }}</td>
                                    <td>{{ $history->created_at->format('d-m-Y g:i A') }}</td>
                                    <td>${{ number_format($history->amount,2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">No history record found.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
      
@endsection
@section('scripts')
      
	<script src="{{url('public/jquery-3.4.1.min.js')}}"></script>
    <script src="{{url('resources/views/admin/purchases/assets/rotate/jquery.magnify.js')}}"></script>
    <script type="text/javascript">
      
      function loginLoadingBtn() {
        document.getElementById('loading-btn').innerHTML = '<button type="button" class="btn btn-primary" disabled>Please wait <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-loader spin ml-2"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg></button>';
        return true;
      }
      
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