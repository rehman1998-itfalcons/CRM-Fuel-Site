@extends('layouts.template')
@section('title','Overdue Sales Report')
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

	<div class="row layout-top-spacing" id="cancel-row">
      <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
      	<div class="widget-content widget-content-area br-6">
          <h4>Overdue Sales Report - {{ $date }}</h4>
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
	<script>

	</script>

@endsection
