@extends('layouts.template')
@section('title','Purchase Details')
@section('css')

	<link rel="stylesheet" href="{{url('resources/views/admin/purchases/assets/rotate/jquery.magnify.css')}}" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css">
    <link href="{{ URL::asset('plugins/flatpickr/flatpickr.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('plugins/flatpickr/custom-flatpickr.css') }}" rel="stylesheet" type="text/css">

    <style>
        .dataTables_length {
            display: inline-block;
        }

        .dt-buttons {
            display: inline-block;
            float: right;
        }

       .flatpickr-calendar {
            z-index: 1050 !important;
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
            @if (session('danger'))
                <p class="alert alert-danger">
                    <strong>Failed</strong> Please update smtp setting to send e-mail.
                </p>
            @endif
            <div class="widget-content widget-content-area br-6" >
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Purchase Details
                                <a href="{{ route('purchases') }}" class="btn btn-md btn-primary" style="float: right;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left close-message"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                                    Back
                                </a>
                                <button style="float: right;" class="btn btn-md btn-danger" data-toggle="modal" data-target="#delete">
                                    Delete
                                </button>
                                <a href="{{ route('purchases.edit',$purchase->id) }}" style="float: right;" type="button" class="btn btn-md btn-primary">
                                    Edit
                                </a>
                              @if (! $purchase->total_amount - $purchase->paid_amount == 0)
                                <button style="float: right;" class="btn btn-md btn-outline-primary btn-rounded" data-toggle="modal" data-target="#addPayment">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>&nbsp; Add Payment
                                </button>
                              @endif
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
                    <tr>
                        <td colspan="2"></td>
                      	<td colspan="2"></td>

                       <td width="15%"> <b> Status : </b></td>
                       <td>
                          @if ($purchase->total_amount - $purchase->paid_amount == 0)
                              <span class="badge badge-success">Paid</span>
                          @else
                              <span class="badge badge-danger">Unpaid</span>
                          @endif

                       </td>
                    </tr>
                    </tbody>
                </table>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <label>Purchase Invoices :</label>
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
                    </div>
                </div>
                <br>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Type</th>
                        <th>Quantity</th>
                        <th>Rate</th>
                        <th>GST</th>
                        <th>Subtotal</th>
                        <th>GST Amount</th>
                        <th>Total</th>
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
    @endphp
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
            <div class="widget-content widget-content-area br-6" >
                <div class="widget-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <h3> Invoices Record </h3>
                        </div>
                    </div>
                    <hr>
                    <div class="table-responsive">
                        <table id="zero_config" class="table table-bordered table-hover">
                            <thead>
                            <tr class="bg-primary">
                                <th class="invoice-th">S.No</th>
                                <th class="invoice-th">Invoice #</th>
                                <th class="invoice-th">Company</th>
                                <th class="invoice-th">Sub Company</th>
                                <th class="invoice-th">Date-Time</th>
                                <th class="invoice-th">Trip #</th>
                                <th class="invoice-th">Order #</th>
                                <th class="invoice-th">GST Status</th>
                                <th class="invoice-th">Total Amount</th>
                                <th class="invoice-th">Detail</th>
                            </tr>
                            </thead>
                            <?php $sno = 1; ?>
                            <tbody class="table table-striped table-bordered">
                            @forelse ($sales as $sale)
                                <tr>
                                    <td>{{ $sno++ }}</td>
                                    <td>{{ $sale->record->invoice_number }}</td>
                                    <td>{{ $sale->record->subCompany->company->name }}</td>
                                    <td>{{ $sale->record->subCompany->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($sale->record->datetime)->format('d-m-Y H:i') }}</td>
                                    <td>{{ $sale->record->load_number }}</td>
                                    <td>{{ $sale->record->order_number }}</td>
                                    <td>{{ $sale->record->gst_status }}</td>
                                    <td>${{ number_format($sale->record->total_amount,2) }}</td>
                                    <td>
                                        <a href="{{ route('records.show',$sale->record_id) }}" class="btn btn-sm btn-primary">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10">No invoice record found.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
	</div>

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

        <div class="modal fade" id="addPayment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Payment</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            x
                        </button>
                    </div>
                    @php
                        $total = round($purchase->total_amount,2) - round($purchase->paid_amount,2);
                        $amount_to_pay = $total;
                    @endphp
                    @if ($amount_to_pay > 0)
                        <form action="{{ route('update.payment.record.store') }}" method="POST" onsubmit="return loginLoadingBtn(this)">

                            <?php $item = \App\PurchaseRecord::where('id', $purchase->id)->first();?>
                            <input type="hidden" value="{{ $item->total_amount }}" id="get_total_amount">

                            <div class="modal-body">
                                <div class="form-group">
                                    <p class="modal-text">
                                        Total Remaining Amount: <br><strong>(${{ $amount_to_pay }})</strong>
                                        <span style="float: right;">
                                  <button type="button" class="btn btn-sm btn-info" onclick="addAllPayment('{{ $amount_to_pay }}')">Add All</button>
                              </span>
                                    </p>
                                </div>
                                @csrf
                                <input type="hidden" name="record_id" value="{{ $purchase->id }}">
  <?php
                            $acc = \App\ChartAccount::where('title','Purchase')->first();
                            ?>

                                <div class="form-group">
                                    <label>Amount to Pay</label>
                                  	<span style="color: red;"> *</span>
                                    <input type="number" step="any" name="payment_amount" min="0" id="payment_amount" class="form-control" oninput="calculateRemaining('{{ $amount_to_pay }}')" required>

                                </div>

                                <div class="form-group">
                                <label>Main Account</label>
                                  	<span style="color: red;"> *</span>
                                <select name="account_id" class="form-control" readonly required>
                                    @if($acc)
                                        <option value="{{ $acc->id }}">{{ $acc->title }}</option>
                                    @endif
                                </select>
                            </div>
                          <div class="form-group">
                            <label>Sub Account</label>
                            	<span style="color: red;"> *</span>
                             <select name="sub_account_id" class="form-control" required>
                                <option value="">--Select--</option>
                                @if($acc)
                                @foreach($acc->subaccounts as $subaccount)
                                <option value="{{ $subaccount->id }}">{{ $subaccount->title }}</option>
                                @endforeach
                                @endif
                          	</select>
                          </div>
                               <div class="form-group">
                                  <label>Select Date Time</label>
						            <small style="color: red;"> *</small>
						            <input type="text" name="payment_datetime" value="{{old('payment_datetime')}}" placeholder="DD-MM-YYYYY" class="form-control"  id="datetimepicker1" required>
                                   </div>
                                <div class="form-group">
                                    <p id="r_amount">
                                        Remaining amount: <br><strong>$<span id="remaining_amount">{{ $amount_to_pay }}</span></strong>
                                    </p>
                                    <p id="i_amount" style="display: none;">
                                        <strong><span id="invlaid_amount">{{ $amount_to_pay }}</span></strong>
                                    </p>
                                </div>
                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" name="confirmed" required> Confirm payment amount.
                                    </label>
                                </div>
                            </div>
                            <div class="modal-footer" id="loading-btn">
                                <button class="btn btn-danger" data-dismiss="modal">Discard</button>
                                <button type="submit" class="btn btn-primary">Confirm</button>
                            </div>
                        </form>
                    @else
                        <div class="modal-body">
                            <div class="form-group">
                                <p>There are no remaining amount.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Delete Purchased Record</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            x
                        </button>
                    </div>
                  	<div class="modal-body">
                    	<div class="form-group">
                          	@if($purchase->match_status == 0)
	                        	<p>Deleting Purchased record will delete products, transaction history.</p>
                          	@else
                          		<p>Purchased record has already consiled, it cannot be deleted</p>
                          	@endif
                      	</div>
                  	</div>
                  	@if($purchase->match_status == 0)
                      <div class="modal-footer">
                          <form action="{{ route('purchase.destroy', $purchase->id) }}" method="POST">
                              @csrf
                              <button type="submit" class="btn btn-md btn-success">
                                  Confirm Delete
                              </button>
                          </form>
                      </div>
                  	@endif
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

                function calculateRemaining(pay)
                {
                    var msg = 'amount is not valid';
                    var get_total_amount = $('#get_total_amount').val();
                    var input = $('#payment_amount').val();
                    var total = pay - input;

                    var new_total = get_total_amount-total;
                    if(get_total_amount>=new_total){
                        total = total.toFixed(2);
                        $('#remaining_amount').html(total);
                        $('#i_amount').hide();
                        $('#r_amount').show();
                    }
                    else {
                        $('#invlaid_amount').html(msg);
                        $('#r_amount').hide();
                        $('#i_amount').show();

                    }
                }

                function addAllPayment(pay)
                {
                    $('#payment_amount').val(pay);
                    var input = $('#payment_amount').val();
                    var total = pay - input;
                    total = total.toFixed(2);
                    $('#remaining_amount').html(total);
                }
            </script>
           <script src="{{ URL::asset('plugins/flatpickr/flatpickr.js') }}"></script>
           <script src="{{ URL::asset('plugins/flatpickr/custom-flatpickr.js') }}"></script>
           <script>
            var f1 = flatpickr(document.getElementById('datetimepicker1'), {

             enableTime: true,
             dateFormat: "d-m-Y H:i"
             });

          </script>

@endsection
