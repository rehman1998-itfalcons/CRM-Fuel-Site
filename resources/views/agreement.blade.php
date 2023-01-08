@extends('layouts.driver')
@section('title','Agreement')
@section('contents')

	<style>
		.btn-success {
            color: #fff !important;
            background-color: #4CAF50 !important;
            border-color: #4CAF50 !important;
        }
      
      	
	</style>
	<div class="widget-content widget-content-area br-6 mt-3 mb-5" >
        <div class="widget-header">
          	<div class="row">
            	<div class="col-xl-12 col-md-12 col-sm-12 col-12">
              		<h4>Company Policy</h4>
            	</div>
        	</div>
      	</div>
      	<hr>
      	<div class="row">
        	<div class="col-md-12" style="height: 300px; overflow: scroll;">
            	<img src="{{ asset('atlas_fuel_cars.png') }}" class="img-fluid"><br><br>
              	<p>Please review and accept the company policies:</p>
              	<ul style="list-style-type: disc;">
                	<li>Driver’s Handbook</li>	  
                	<li>Emergency Response Plan</li>	  
                	<li>Site Refuelling Procedures</li>	  
              	</ul>
              	<p>It is important that as part of the wider community and to service the industry we comply by the industry regulation and policies.</p>
              	<p>Please accept the Policies above to and ensure to read the policy when required.</p>
          	</div>  
      	</div>
      	<hr>
      	<div class="row">
        	<div class="col-md-12" style="">
              	<form action="{{ url('/driver-agreement') }}" method="POST">
                  @csrf
            	@php
              		if (Auth::user()->attachments) {
                    	$attachments = explode('::',Auth::user()->attachments);
                      	foreach ($attachments as $key => $value) {
              				@endphp
              					<div class="form-group">
              						<label>
                                      <input type="checkbox" name="agree[]" value="{{ $value }}" required> <a href="{{ asset('uploads/'.$value) }}"> {{ $value }}</a>
                                  	</label>
              					</div>
              				@php
              			}
              		}
              	@endphp
            </div>
          	<br>
          	<div class="col-md-6">
          		<button type="submit" class="btn btn-md btn-success">Accept</button>
              </form>
              	<a href="{{ url('/driver') }}" class="btn btn-md btn-danger">Decline</a>
          	</div>
        </div>
	</div>

@endsection