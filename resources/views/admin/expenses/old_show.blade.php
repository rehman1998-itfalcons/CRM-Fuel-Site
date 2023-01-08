@extends('layouts.template')
@section('title','Expense Details')
@section('css')

	<style>
		p {
        	font-size: 15px !important;
          	color: #000 !important;
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
              	<h4>
                	Expense Details
                	<a href="{{ route('expenses') }}" class="btn btn-md btn-primary" style="float: right;">All Expenses</a>
              	</h4>
              	<hr>
				<div class="row mb-3">
                	<div class="col-md-6">
                    	<div class="form-group">
                        	<h5>Account</h5>
                        	<p>{{ $expense->chartAccount->title }}</p>
                      	</div>
                  	</div>
                	<div class="col-md-6">
                    	<div class="form-group">
                        	<h5>Sub Account</h5>
                        	<p>{{ $expense->subaccount->title }}</p>
                      	</div>
                  	</div>
              	</div>
				<div class="row mb-3">
                	<div class="col-md-6">
                    	<div class="form-group">
                        	<h5>Amount</h5>
                        	<p>${{ number_format($expense->amount,2) }}</p>
                      	</div>
                  	</div>
                	<div class="col-md-6">
                    	<div class="form-group">
                        	<h5>Payee</h5>
                        	<p>{{ $expense->payee }}</p>
                      	</div>
                  	</div>
              	</div>
				<div class="row mb-3">
                	<div class="col-md-6">
                    	<div class="form-group">
                        	<h5>Ref#</h5>
                        	<p>{{ $expense->ref_no }}</p>
                      	</div>
                  	</div>
                	<div class="col-md-6">
                    	<div class="form-group">
                        	<h5>Date</h5>
                        	<p>{{ \Carbon\Carbon::parse($expense->datetime)->format('d-m-Y H:i') }}</p>
                      	</div>
                  	</div>
              	</div>
				<div class="row mb-3">
                	<div class="col-md-12">
                    	<div class="form-group">
                        	<h5>Description</h5>
                        	<p>{{ $expense->description }}</p>
                      	</div>
                  	</div>
              	</div>
              	@if($expense->attachment)
                  <div class="row">
                      <div class="col-md-12">
                          <div class="form-group">
                              <h5 class="mb-3">Attachment(s)</h5>
                              <p>
                                @php
                                	$files = explode("::",$expense->attachment);
                                @endphp
                                @foreach ($files as $key  => $value)
                                	 <a href="{{ asset('uploads/expenses/'.$value) }}" class="text-primary" style="padding: 5px; border: 1px solid #999;" target="_blank">
                                      {{ $value }}
                                	</a> &nbsp;&nbsp;
                            	@endforeach
                            </p>
                          </div>
                      </div>
                  </div>
              	@endif
          	</div>
        </div>
      </div>
    </div>
  </div>

@endsection