@extends('layouts.template')
@section('title','Overdue Invoices')
@section('css')

    <link rel="stylesheet" type= "text/css" href="{{ URL::asset('plugins/table/table/datatable/datatables.css') }}">
    <link rel="stylesheet" type= "text/css" href="{{ URL::asset('plugins/table/table/datatable/custom_dt_html5.css') }}">
    <link rel="stylesheet" type= "text/css" href="{{ URL::asset('plugins/table/table/datatable/dt-global_style.css') }}">
	<link href="{{ URL::asset('plugins/loaders/custom-loader.css') }}" rel="stylesheet" type="text/css" />
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
      	thead > tr > th.sorting {
    		white-space: nowrap !important;
        }
		.table > thead > tr > th {
    		font-weight: 700 !important;
        }
	</style>

@endsection
@section('contents')

	<?php
		use App\Record;
		$category = (isset($_GET['category'])) ? $_GET['category'] : 0;
		if ($category == 0 && $category == '')
			$records = Record::where('paid_status',0)->where('status',1)->where('deleted_status',0)->get();
		elseif($category != 0 && $category != '')
			$records = Record::where('category_id',$category)->where('paid_status',0)->where('status',1)->where('deleted_status',0)->get();
	?>
  <div class="row layout-top-spacing" id="cancel-row">
    <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
      <div class="widget-content widget-content-area br-6" >
        <div class="widget-header">
          <div class="row">
            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
              <h4>Overdue Invoices</h4>
              <div class="table-responsive mb-4 mt-4">
                <table id="html5-extension" class="table table-hover non-hover" style="width:100%">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Invoice</th>
                  <th>Operator</th>
                  <th>DateTime</th>
                  <th>Company</th>
                  <th>Sub-Company</th>
                  <th>Trip#</th>
                  <th>Order#</th>
                  <th>Outstanding Balance</th>
                  <th>Email</th>
                  <th>Status</th>
                  <th>Invoice</th>
                </tr>
              </thead>
               <tbody>
                <?php $sno = 1; ?>

                @foreach ($records as $record)
                 @php
                 	$date = \Carbon\Carbon::parse($record->datetime)->format('d-m-Y');
          			$days = $record->subCompany->inv_due_days;
                 	if($days > 0)
                    {
                      $due = date('d-m-Y', strtotime($date. ' + '.$days.' days'));
                    } elseif($days < 0){

                      $timestamp = strtotime($date);
                      $daysRemaining = (int)date('t', $timestamp) - (int)date('j', $timestamp);
                      $positive_value =  abs($days);
                      $original_date = $positive_value+$daysRemaining;

                      $date = substr($date,0,10);
                      $due = date('d-m-Y', strtotime($date. ' + '.$original_date.' days'));
                    } else {
                    	$due = date('d-m-Y', strtotime($date. ' + '.$days.' days'));
                    }

                    $remaining_date = strtotime($due) - strtotime(date('d-m-Y'));
                    if ($remaining_date >= 0)
                 		continue;

                 @endphp
                  <tr class="yellow-tooltip" @if($record->follows_note) title="{{ $record->follows_note }}"  @endif>
                      <td>{{ $sno++ }}</td>
                      <td>{{ $record->invoice_number }}</td>
                      <td>{{ $record->user->name }}</td>
                      <td>{{ \Carbon\Carbon::parse($record->datetime)->format('d-m-Y H:i')}}</td>
                      <td>{{ $record->subCompany->company->name }}</td>
                      <td>{{ $record->subCompany->name }}</td>
                      <td>{{ $record->load_number }}</td>
                      <td>{{ $record->order_number }}</td>
                      <td>${{ number_format($record->total_amount,2) }}</td>
                      <td>
                        @if ($record->email_status == 1)
                            <span class="badge badge-success">Sent</span>
                        @else
                            <span class="badge badge-danger">Not Sent</span>
                        @endif
                      </td>
                      <td>
                        @if ($record->paid_status == 1)
                            <span class="badge badge-success">Paid</span>
                        @else
                            <span class="badge badge-danger">Unpaid</span>
                        @endif
                      </td>
                      <td>
                        <a href="{{ route('invoice.details',$record->id) }}" class="btn btn-sm btn-primary btn-custom">View</a>
                      </td>
                  </tr>
                @endforeach
              </tbody>
            </div>
          </div>
        </div>
        <hr>
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
          	"order": [[ 0, "desc" ]],
            responsive: true,
            dom:
            '<"row"<"col-md-12"<"row"  <"col-md-4"l>  <"col-md-4 text-center"B>  <"col-md-4"f>>>' +
            '<"col-md-12"tr>' +
            '<"col-md-12 mt-2"<"row mt-3"  <"col-md-5"i>  <"col-md-7 text-right"p>>> >',
            buttons: {
                buttons: [{
                    extend: 'csv',
                    className: 'btn'
                },{
                    extend: 'excel',
                    className: 'btn'
                }]
        },
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
            "order":[[1, 'desc']],//asc or desc
            "lengthMenu": [10, 20, 30, 50],
            "pageLength": 10

        });
    </script>



@endsection
