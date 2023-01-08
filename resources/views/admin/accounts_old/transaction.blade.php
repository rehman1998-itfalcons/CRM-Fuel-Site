@extends('layouts.template')
@section('title','Bank Transactions')
@section('css')

    <link rel="stylesheet" type= "text/css" href="{{ URL::asset('plugins/table/table/datatable/datatables.css') }}">
    <link rel="stylesheet" type= "text/css" href="{{ URL::asset('plugins/table/table/datatable/custom_dt_html5.css') }}">
    <link rel="stylesheet" type= "text/css" href="{{ URL::asset('plugins/table/table/datatable/dt-global_style.css') }}">
    <link href="{{ URL::asset('plugins/flatpickr/flatpickr.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('plugins/flatpickr/custom-flatpickr.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ URL::asset('plugins/loaders/custom-loader.css') }}" rel="stylesheet" type="text/css" />
	<style>

		table.dataTable thead .sorting_asc:before {
          display: none;
        }
       .flatpickr-calendar {
            z-index: 1050 !important;
        }
		.loader {
          position: sticky !important;
          text-align: center !important;
          left: 50% !important;
          top: 50% !important;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
        }

        .table > tbody > tr > td {
            color: #000 !important;
        }

      	.bootstrap-select>.dropdown-toggle {
    		border: 1px solid #bfc9d4;
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
      @if(session('warning'))
      	<p class="alert alert-warning">{{ session('warning') }}</p>
      @endif
      <div class="widget-content widget-content-area br-6" >
        <div class="widget-header">
          <div class="row">
            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
              <h4>
                Bank Transactions
                <button type="button" style="float: right;" class="btn btn-md btn-primary" data-bs-toggle="modal" data-bs-target="#addTransactions">
                	<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>&nbsp;
                	Add New Bank Transactions
                </button>
              </h4>
              <hr>
                <div class="row">
                	<div class="col-md-4">
                      	<label>By Date:</label>
                      	<form action="{{ url('/transactions') }}" method="GET">
                        	<input type="text" name="date_search" id="date_select" class="form-control" placeholder="DD-MM-YYYYY">
                      	</form>
                  	</div>
                	<div class="col-md-4">
                      	<label>By Bank Account:</label>
                      <select name="account_search" id="account" class="form-control">
                      	<option value="">--Select Account--</option>
                        @foreach ($accounts as $account)
                        	<option value="{{ $account->id }}">
                        		{{ $account->account_name }}
                        	</option>
                        @endforeach
                      </select>
                  	</div>
                	<div class="col-md-4">
                      	<label>By Description:</label>
                        <input type="text" name="desc_search" id="desc" class="form-control">
                  	</div>
                </div>
              <div class="table-responsive mb-4 mt-4">
                <table id="html5-extension" class="table table-hover non-hover" style="width:100%">
                  <thead class="thead-light">
                    <tr>
                      <th>ID</th>
                      <th>Date</th>
                      <th>Description</th>
                      <th>Withdrawal</th>
                      <th>Deposit</th>
                      <th>Bank</th>
                      <th>Allocate</th>
                    </tr>
                  </thead>
                  <tbody>
                  	@forelse ($transactions as $transaction)
                    	<tr>
                          <?php
                           if($transaction->withdraw != 0){
                             $amount = $transaction->withdraw;
                           }
                              else{
                               $amount = $transaction->deposit;
                              }
                          ?>
                          	<td>{{ $transaction->id }}</td>
                    		<td>{{ \Carbon\Carbon::parse($transaction->date)->format('d-m-Y') }}</td>
                    		<td>{{ $transaction->description }}</td>
                    		<td>${{ number_format($transaction->withdraw,2) }}</td>
                    		<td>${{ number_format($transaction->deposit,2) }}</td>
                    		<td>{{ $transaction->account->account_name }}</td>
                    		<td>
                              @if($transaction->status == 0)
                          		<a style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#allocate" onclick="allocateFunction('{{ $transaction->id }}','{{$amount}}')">Allocate To Me</a>
                              @else
                              <span class="badge outline-badge-success">Allocated</span>
                              @endif
                          	</td>
                    	</tr>
                    @empty
                    	<tr>
                    		<td colspan="7">No record found.</td>
                    	</tr>
                    @endforelse
                  </tbody>
                  <tfoot>
                  	<tr>
                    	<td colspan="7">
                          <br>
                          {{ $transactions->links() }}
                     	</td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="addTransactions" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Extract from Qif file</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
            </div>
            <div class="modal-body">
            	<form action="{{ url('/transactions') }}" method="POST" enctype="multipart/form-data" onsubmit="return loginLoadingBtn(this)">
                  	@csrf
              		<div class="form-group">
                    	<label>Select Account *</label>
                      	<select name="account_id" class="form-control" required>
                        	<option value="">--Select--</option>
                          	@foreach ($accounts as $account)
                          		<option value="{{ $account->id }}">{{ $account->account_name }}</option>
                          	@endforeach
                      	</select>
                  	</div>
              		<div class="form-group">
                      	<input type="file" name="file" class="form-control" required>
                  	</div>
                  	<hr>
              		<div class="form-group" id="loading-btn">
                    	<button type="submit" class="btn btn-md btn-primary" style="width: auto !important;">Extract</button>
                  	</div>
              	</form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="allocate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Match Transactions</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
            </div>
            <div class="modal-body">
            	<form action="{{ url('/allocate-transaction') }}" method="POST" onsubmit="return loginLoadingBtn1(this)">
                  	@csrf
              		<div class="form-group">
                    	<label>Type of Account *</label>
                      	<select name="chart_account_id" id="chart_account_id" onchange="fetchSubAccounts()" class="form-control" required>
                        	<option value="">--Select--</option>
                          	@foreach ($charts as $account)
                          		<option value="{{ $account->id }}">{{ $account->title }}</option>
                          	@endforeach
                      	</select>
                  	</div>
                  	<div class="form-group">
                      	<label>Select Sub Account *</label>
                   		<select name="sub_account_id" id="sub_account_id" class="form-control" onchange="fetchInvoices()" required>
                        	<option value="">-- Select --</option>
                      	</select>
                  	</div>
              		<div class="form-group">
                    	<label>Invoice Multiple *</label>
                      	<span id="multiple-invoices">
                      		<select class="form-control"><option value="">-- Select --</option></select>
                      	</span>
                  	</div>

                    <div class="form-group">
                      <label>Select Date Time</label>
                      <small style="color: red;"> *</small>
                      <input type="text" name="payment_datetime" value="{{old('payment_datetime')}}" placeholder="DD-MM-YYYYY" class="form-control"  id="datetimepicker1" required>
                    </div>

                  	<input type="hidden" name="transactoin_id" id="transactoin_id">
                  	<input type="hidden" name="transactoin_amount" id="transactoin_amount">
                  	<hr>
                    <table class="table">
                    	<tbody>
                      		<tr style="border: 1px solid #e0e6ed;">
                          		<td style="border-right: 1px solid #e0e6ed;">Amount: $<span id="amount"></span></td>
                          		<td>Invoices Total: $<span id="invoices_total"></span></td>
                          	</tr>
                      	</tbody>
                  	</table>
              		<div class="form-group" id="loading-btn1">
                    	<button type="submit" id="submit-btn" class="btn btn-md btn-primary" style="width: auto !important;" disabled>Submit</button>
                  	</div>
              	</form>
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

      $(document).ready(function() {
          var url = '{{ url('/transactions') }}?';

          $("#date_select").on("change",function() {
              window.location.href = url +$(this).attr('name') +"="+ $(this).val();
          });

          $("#account").on("change",function() {
              window.location.href = url +$(this).attr('name') +"="+ $(this).val();
          });

          $("#desc").on("change",function() {
              window.location.href = url +$(this).attr('name') +"="+ $(this).val();
          });
      });

      	function allocateFunction(id,amount)
      	{
        	$('#transactoin_id').val(id);
          	$('#transactoin_amount').val(amount);
            $("#amount").html(amount);
        }

      	function fetchSubAccounts()
      	{
        	var account_id = $('#chart_account_id').val();
          	if (account_id != '') {
              	$('#sub_account_id').html('<option value="">-- Select --</option>');
              	$('#multiple-invoices').html('<select class="form-control"><option value="">-- Select --</option></select>');
	          	$.get('{{ URL::to('/fetch-subaccounts') }}',{_token:'{{ csrf_token() }}', account_id:account_id}, function(data) {
                  	var len = data.length;
                  	for (var i = 0; i < len; i++) {
                    	var account = data[i];
                      	$('#sub_account_id').append('<option value="'+account.id+'">'+account.title+'</option>');
                    }
      			});
            }
        }


      	function countTotal(str)
      	{
          	var count = str.length;
          	var i = 0;
          	var grand = parseFloat(0);
          	for (i; i < count; i++) {
              	var el = $('#option_'+str[i]);
      			var total = parseFloat(el.attr('data-'+ str[i] +'-amount'));
              	grand = grand + total;
            }

          	var grand_tol = grand.toFixed(2);
          	$('#invoices_total').html(grand_tol);
          	var tol = parseFloat($("#amount").text());
          	if (tol == grand_tol)
            	$('#submit-btn').prop("disabled", false);
          	else
            	$('#submit-btn').prop("disabled", true);
        }

      	function fetchInvoices()
      	{
        	var account_id = $('#chart_account_id').val();
          	if (account_id != '') {
              	$.get('{{ URL::to('/fetch-invoices') }}',{_token:'{{ csrf_token() }}', account_id:account_id}, function(data) {
                  		$('#multiple-invoices').html(data);
              	});
            }
        }

        function loginLoadingBtn() {
            document.getElementById('loading-btn').innerHTML = '<button class="btn btn-primary disabled" style="width: auto !important;">Please wait <svg xmlns="http://www.w3.org/2000/svg" width="24" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-loader spin ml-2"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg></button>';
            return true;
        }


        function loginLoadingBtn1() {
            document.getElementById('loading-btn1').innerHTML = '<button class="btn btn-primary disabled" style="width: auto !important;">Please wait <svg xmlns="http://www.w3.org/2000/svg" width="24" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-loader spin ml-2"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg></button>';
            return true;
        }

	</script>

      <script src="{{ URL::asset('plugins/flatpickr/flatpickr.js') }}"></script>
      <script src="{{ URL::asset('plugins/flatpickr/custom-flatpickr.js') }}"></script>
    <script>
       var f1 = flatpickr(document.getElementById('date_select'), {
             enableTime: true,
             dateFormat: "d-m-Y"
        });
    </script>
    <script>
      var f2 = flatpickr(document.getElementById('datetimepicker1'), {
        enableTime: true,
        dateFormat: "d-m-Y H:i"
      });

    </script>
@endsection
