@extends('layouts.template')
@section('title','Transctions')
@section('css')

    <link rel="stylesheet" type= "text/css" href="{{ URL::asset('plugins/table/table/datatable/datatables.css') }}">
    <link rel="stylesheet" type= "text/css" href="{{ URL::asset('plugins/table/table/datatable/custom_dt_html5.css') }}">
    <link rel="stylesheet" type= "text/css" href="{{ URL::asset('plugins/table/table/datatable/dt-global_style.css') }}">
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
		.table > thead > tr > th {
    		font-weight: 700 !important;
        }
	</style>
    <link href="{{ URL::asset('plugins/flatpickr/flatpickr.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('plugins/flatpickr/custom-flatpickr.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ URL::asset('plugins/loaders/custom-loader.css') }}" rel="stylesheet" type="text/css" />

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
      <div class="widget-content widget-content-area br-6" >
        <div class="widget-header">
          <div class="row">
            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
              <h4>Bank Transctions</h4>
              <hr>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>

@endsection
