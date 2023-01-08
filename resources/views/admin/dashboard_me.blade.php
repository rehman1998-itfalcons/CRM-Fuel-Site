@extends('layouts.layout.main')
@section('title','Dashboard')
@section('css')

    <style>

        .apexcharts-canvas {
            margin: 0 auto;
        }

        .widget-card-four .w-info p {
            color: #444 !important;
        }

        .widget-card-four .w-icon {
            color: #2e8e3f !important;
            background-color: #d2efd7 !important;
        }

        .progress .progress-bar.bg-gradient-secondary {
            background-color: #2e8e3f !important;
            background-image: linear-gradient(to right, #2e8e3f 0%, #2e8e3f 100%) !important;
        }

        .widget-card-four {
            padding: 20px 25px !important;
            box-shadow: 0 4px 6px 0 rgba(85, 85, 85, 0.0901961), 0 1px 20px 0 rgba(0, 0, 0, 0.08), 0px 1px 11px 0px rgba(0, 0, 0, 0.06);
        }

        .form-control-custom {
            position: relative;
            padding: 9px 35px 10px 15px;
            border: 1px solid #e0e6ed;
            border-radius: 8px;
            transform: none;
            font-size: 13px;
            line-height: 17px;
            background-color: #fff;
            letter-spacing: normal;
            min-width: 115px;
            text-align: inherit;
            color: #3b3f5c;
            box-shadow: none;
            font-weight: 600;
            letter-spacing: 0px;
        }

        .progress .progress-bar.bg-gradient-primary {
            background-color: #4d9f5c !important;
            background: linear-gradient(to right, #9ed0a7 0%, #4d9f5c 100%) !important;
        }

        .widget-card-four .progress {
            margin-top: 0px !important;
        }

        .apexcharts-yaxis-label {
            font-size: 13px !important;
        }

        .apexcharts-menu-icon {
            display: none !important;
        }

        .table > thead > tr > th {
            color: #000000 !important;
            font-weight: 700 !important;
        }

        .page-item.active .page-link {
            background-color: #2e8e3f !important;
        }

        div.dataTables_wrapper div.dataTables_info {
            color: #2e8e3f !important;
        }

    </style>

    <style>
        .badge {
            cursor: pointer;
        }
        .table > thead > tr > th {
            font-weight: 700 !important;
        }

        .flatpickr-day.today:hover, .flatpickr-day.today:focus {
            border-color: #2e8e3f !important;
            background: #2e8e3f !important;
        }

        .flatpickr-day.today {
            border-color: #2e8e3f !important;
          	color: #fff !important;
        }
      .QA_table  th{
            color:white;
        }

    </style>
      <link href="{{ URL::asset('plugins/apex/apexcharts.css') }}" rel="stylesheet" type="text/css">
      <link href="{{ URL::asset('plugins/table/datatable/datatables.css') }}" rel="stylesheet" type="text/css">
      <link href="{{ URL::asset('plugins/table/datatable/dt-global_style.css') }}" rel="stylesheet" type="text/css">
      <link rel="stylesheet" type="text/css" href="{{ URL::asset('plugins/table/datatable/datatables.css') }}">
      <link rel="stylesheet" type="text/css" href="{{ URL::asset('plugins/table/datatable/dt-global_style.css') }}">
      <link href="{{ URL::asset('plugins/flatpickr/flatpickr.css') }}" rel="stylesheet" type="text/css">
      <link href="{{ URL::asset('plugins/flatpickr/custom-flatpickr.css') }}" rel="stylesheet" type="text/css">

@endsection
@section('contents')
    <?php

   
    use Carbon\Carbon;
    use App\Record;
    use App\PurchaseRecord;
    use App\RecordProduct;
    use App\Log;
        $activity_logs = Log::latest()->take(3)->get();
     

    $set_category = isset($_GET['category']) ? $_GET['category'] : 0;
    $categories = DB::table('categories')
        ->select('id', 'name')
        ->orderBy('name', 'asc')
        ->get();
    $from =
        Carbon::now()
            ->subdays(7)
            ->format('Y-m-d') . ' 00:00:00';
    $to =
        Carbon::now()
            ->subdays(1)
            ->format('Y-m-d') . ' 24:00:00';
    $expenses = DB::table('expenses')
        ->select('datetime', 'amount')
        ->whereBetween('datetime', [new Carbon($from), new Carbon($to)])
        ->get();

    if ($set_category != '' && $set_category != 0) {
        $sales = Record::select('id', 'company_id', 'sub_company_id', 'datetime', 'status', 'paid_status', 'gst', 'total_amount', 'paid_amount', 'total_without_gst', 'deleted_status')
            ->where('category_id', $_GET['category'])
            ->get();

        $purchases = PurchaseRecord::select('id', 'category_id', 'supplier_company_id', 'invoice_number', 'total_amount', 'datetime', 'status', 'deleted_status')
            ->where('category_id', $_GET['category'])
            ->get();
    } else {
        $sales = Record::select('id', 'company_id', 'sub_company_id', 'datetime', 'status', 'paid_status', 'gst', 'total_amount', 'paid_amount', 'total_without_gst', 'deleted_status')->get();

        $purchases = PurchaseRecord::select('id', 'category_id', 'supplier_company_id', 'invoice_number', 'total_amount', 'datetime', 'status', 'deleted_status')->get();
    }

    $purchases1 = $purchases;
    $tax = $sales
        ->where('status', 1)
        ->where('deleted_status', 0)
        ->sum('gst');
    $gross = $sales
        ->where('status', 1)
        ->where('deleted_status', 0)
        ->sum('total_amount');

    $net = $gross - $tax;

    /* Overdue Sales */
    $date1 = Carbon::now()
        ->addday(1)
        ->format('d-m-Y');
    $date2 = Carbon::now()
        ->addday(2)
        ->format('d-m-Y');
    $date3 = Carbon::now()
        ->addday(3)
        ->format('d-m-Y');
    $date4 = Carbon::now()
        ->addday(4)
        ->format('d-m-Y');
    $date5 = Carbon::now()
        ->addday(5)
        ->format('d-m-Y');
    $date6 = Carbon::now()
        ->addday(6)
        ->format('d-m-Y');
    $date7 = Carbon::now()
        ->addday(7)
        ->format('d-m-Y');
    $yesterday = Carbon::now()
        ->subdays(1)
        ->format('d-m-Y');
    $d1_total = 0;
    $d2_total = 0;
    $d3_total = 0;
    $d4_total = 0;
    $d5_total = 0;
    $d6_total = 0;
    $d7_total = 0;
    $array_date_1 = [];
    $array_date_2 = [];
    $array_date_3 = [];
    $array_date_4 = [];
    $array_date_5 = [];
    $array_date_6 = [];
    $array_date_7 = [];
    $overdue_invoices = 0;
    $overdue_balance = 0;
    $unpaid_balance = 0;
    ?>
    @foreach ($sales->where('status', 1)->where('paid_status', 0)->where('deleted_status', 0)
        as $record)
        <?php
        $record_date = Carbon::parse($record->datetime)->format('d-m-Y');
        $sub_company_days = $record->subCompany->inv_due_days;
        if ($sub_company_days > 0) {
            $due = date('d-m-Y', strtotime($record_date . ' + ' . $sub_company_days . ' days'));
        } elseif ($sub_company_days < 0) {
            $timestamp = strtotime($record_date);
            $daysRemaining = (int) date('t', $timestamp) - (int) date('j', $timestamp);
            $positive_value = abs($sub_company_days);
            $original_date = $positive_value + $daysRemaining;
            $due = date('d-m-Y', strtotime($record_date . ' + ' . $original_date . ' days'));
        } else {
            $due = $record_date;
        }

        $over_date = strtotime($yesterday) - strtotime($due);
        if ($over_date < 0) {
            $overdue_invoices++;
            $amount = $record->total_amount - $record->paid_amount;
            $overdue_balance = $overdue_balance + $amount;
        } else {
            $amount = $record->total_amount - $record->paid_amount;
            $unpaid_balance = $unpaid_balance + $amount;
        }

        switch ($due) {
            case $date1:
                array_push($array_date_1, $record->id);
                $d1_total++;
                break;
            case $date2:
                array_push($array_date_2, $record->id);
                $d2_total++;
                break;
            case $date3:
                array_push($array_date_3, $record->id);
                $d3_total++;
                break;
            case $date4:
                array_push($array_date_4, $record->id);
                $d4_total++;
                break;
            case $date5:
                array_push($array_date_5, $record->id);
                $d5_total++;
                break;
            case $date6:
                array_push($array_date_6, $record->id);
                $d6_total++;
                break;
            case $date7:
                array_push($array_date_7, $record->id);
                $d7_total++;
                break;
        }

        ?>
    @endforeach

    <?php

    $records = Record::where('supervisor_status', 0)
        ->where('deleted_status', 0)
        ->get();
    $account = Record::where('supervisor_status', 1)
        ->where('status', 0)
        ->where('deleted_status', 0)
        ->get();
    $sal = Record::where('deleted_status', 1)->get();

    ?>
    <div class="container-fluid p-0">
        <div class="row justify-content-center">
            <div class="white_box mb_20">
            <div class="col-lg-12">
                <div class="single_element">
                    <div class="quick_activity">
                        <div class="row">
                            <div class="col-12">
                                <div class="quick_activity_wrap">
                                    <div class="single_quick_activity d-flex">
                                        <div class="icon">
                                            <img src="{{URL::asset('img/icon/truck.png')}}" alte="">
                                        </div>
                                        <div class="count_content">
                                            <h3><span
                                                    class="counter">{{ $sales->where('deleted_status', 0)->where('status', 1)->count() }}</span>
                                            </h3>
                                            <p>TOTAL DELIVERIES</p>
                                        </div>
                                    </div>
                                    <div class="single_quick_activity d-flex">
                                        <div class="icon">
                                            <img src="{{URL::asset('img/icon/data.png')}}" alte="">
                                        </div>
                                        <div class="count_content">
                                            <h3><span class="counter">
                                                    {{ $sales->where('status', 0)->where('deleted_status', 0)->count() }}</span>
                                            </h3>
                                            <p>PENDING RECORDS</p>
                                        </div>
                                    </div>
                                    <div class="single_quick_activity d-flex">
                                        <div class="icon">
                                            <img src="{{URL::asset('img/icon/inv.png')}}" alte="">
                                        </div>
                                        <div class="count_content">
                                            <h3><span
                                                    class="counter">{{ $sales->where('status', 1)->where('paid_status', 1)->where('deleted_status', 0)->count() }}</span>
                                            </h3>
                                            <p>PAID INVOICES</p>
                                        </div>
                                    </div>
                                    <div class="single_quick_activity d-flex">
                                        <div class="icon">
                                            <img src="{{URL::asset('img/icon/dollar.png')}}" alte="">
                                        </div>
                                        <div class="count_content">
                                            <h3><span class="counter">{{ number_format($gross, 2) }}</span> </h3>
                                            <p>BALANCE AMOUNT</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-xl-12">
                <div class="white_box mb_30 ">
                    <div class="box_header border_bottom_1px  ">
                        <div class="main-title">
                            <h3 class="mb_25">BALANCE & INVOICES</h3>
                        </div>
                    </div>
                    <div class="income_servay">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="count_content">
                                    <h3> <span class="counter">{{ $sales->where('status',1)->where('paid_status',0)->where('deleted_status',0)->count() }}</span> </h3>
                                    <p>UNPAID INVOICES</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="count_content">
                                    <h3>$ <span class="counter">{{ number_format($unpaid_balance,2) }}</span> </h3>
                                    <p>UNPAID BALANCE</p>
                                </div>
                            </div>
                            <?php
                            $getoverdues=0;
                            if($set_category != '' && $set_category != 0) {
                                $num_records = Record::where('category_id',$_GET['category'])->where('status',1)->where('paid_status',0)->where('deleted_status',0)->get();
                            }
                            else{
                                $num_records = Record::where('status',1)->where('paid_status',0)->where('deleted_status',0)->get();
                            }

                            ?>
                            @foreach ($num_records as $record)
                                @php
                                    $date = \Carbon\Carbon::parse($record->datetime)->format('d-m-Y');
                                     $days = $record->subCompany->inv_due_days;
                                    if($days > 0)
                                   {
                                     $date = \Carbon\Carbon::parse($record->datetime)->format('d-m-Y');
                                     $due = date('d-m-Y', strtotime($date. ' + '.$days.' days'));
                                   }
                                    elseif($days < 0){

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
                                  	$getoverdues =   $getoverdues+1;

                                @endphp
                            @endforeach
                            <div class="col-md-3">
                                <div class="count_content">
                                    <h3> <span class="counter">{{ $getoverdues }}</span> </h3>
                                    <p>OVERDUE INVOICES</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="count_content">
                                    <h3>$ <span class="counter">{{ number_format($overdue_balance,2) }}</span> </h3>
                                    <p>OVERDUE BALANCE</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="bar_wev"></div>
                </div>
            </div>
            <div class="col-lg-12 col-xl-12">
                <div class="white_box mb_30">
                    <div class="box_header border_bottom_1px  ">
                        <div class="main-title">
                            <h3 class="mb_25"> Sales/Purchases Report</h3>
                        </div>
                    </div>
                    <div id="apex_2"></div>
                </div>
            </div>
            {{-- @include('admin.dashboard_partials.overall_stats_monthly') --}}
            {{-- <div class="col-lg-12 col-xl-12">
                <div class="white_box mb_30">
                    <div class="box_header border_bottom_1px  ">
                        <div class="main-title">
                            <h3 class="mb_25"> Supplier Companies</h3>
                        </div>
                    </div>
                    <div id="s_companies"></div>
                </div>
            </div> --}}
            <div class="col-lg-12 col-xl-12">
                <div class="row">
            <div class="col-xl-6 ">
                <div class="white_box mb_5">
                    <div class="box_header border_bottom_1px  ">
                        <div class="main-title">
                            <h3 class="mb_25"> Report (June 2022)</h3>
                        </div>
                    </div>
                    <div id="apex_1"></div>
                </div>
            </div>
            <div class="col-xl-6 ">
                <div class="white_box mb_5">
                    <div class="box_header border_bottom_1px  ">
                        <div class="main-title">
                            <h3 class="mb_25"> Supplier Companies </h3>
                        </div>
                    </div>
                    <div id="chart4556"></div>
                </div>
            </div>
         
        </div>
        </div>
        {{-- js chart  --}}
         <div class="col-xl-12 col-12 col-lg-12 ">
                <div class="white_box mb_5">
                    <div class="box_header border_bottom_1px  ">
                        <div class="main-title">
                            <h3 class="mb_25"> Demo Chart </h3>
                        </div>
                    </div>
                    <div id="chart"></div>
                </div>
            </div>


        {{-- //starting table --}}
        <div class="col-12">
            <div class="row">
        <div class="col-md-6 col-lg-6 col-sm-6">
            <div class="QA_section">
                <div class="white_box_tittle list_header">
                    <h3> Activity Logs</h3>
                    <div class="box_right d-flex lms_block">
                        <div class="serach_field_2">
                            <div class="search_inner">
                                
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="QA_table mb_30">

                            <table class="table lms_table_active table-bordered data-table" >
                                <thead>
                                <tr role="row">
                                    <th class="sorting" style="color:white !important;">#</th>
                                    <th class="sorting" style="color:white !important;">User</th>
                                    <th class="sorting" style="color:white !important;">IP Address</th>

                                    <th class="sorting" style="color:white !important;">Location</th>
                                    <th class="sorting" style="color:white !important;">Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i = 1; ?>
                                @foreach ($activity_logs as $log)
                                    <?php $user = $log->user; ?>
                                    <tr role="row">
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $log->ip_address }}</td>

                                          <td>
                                              {{ $log->city_name .', '. $log->region_name }} <br>
                                              {{ $log->country_name }}, {{ $log->zip_code }}.
                                          </td>
                                        <td>{{ $log->created_at->diffForHumans() }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>








            </div>
        </div>

    </div>
    </div>




        </div>
    </div>
    </div>



@endsection
@section('scripts')

    <script src="{{ URL::asset('plugins/table/datatable/datatables.js') }}"></script>
    <script src="{{ URL::asset('plugins/apex/apexcharts.min.js') }}"></script>
    <script>

         $('#zero_config_raf').DataTable({
          "oLanguage": {
              "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
              "sInfo": "Showing page _PAGE_ of _PAGES_",
              "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
              "sSearchPlaceholder": "Search...",
              "sLengthMenu": "Results :  _MENU_",
          },
          "stripeClasses": [],
          "lengthMenu": [10, 20, 30, 50],
          "pageLength": 10
      });


        $('#zero-config21').DataTable({
            "oLanguage": {
                "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
                "sInfo": "Showing page _PAGE_ of _PAGES_",
                "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                "sSearchPlaceholder": "Search...",
                "sLengthMenu": "Results :  _MENU_",
            },
            "stripeClasses": [],
            "lengthMenu": [10, 20, 30, 50],
            "pageLength": 10
        });

        $('[data-toggle="tooltip"]').tooltip();
        $(document).ready(function () {
            sLineInitial();
            monthltyReportInitial();
            supplierInital();
        });

        $('#submit-btn').click(function(e) {
            e.preventDefault();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();

                    if (from_date == '')
                        $('#from_date').css('border-color','#E91E63');
                    else
                        $('#from_date').css('border-color','#bfc9d4');

                    if (to_date == '')
                        $('#to_date').css('border-color','#E91E63');
                    else
                        $('#to_date').css('border-color','#bfc9d4');

			if (from_date != '' && to_date != '') {
              $('#error_ish').css('display','none');
              var category = "&category="+'{{ (isset($_GET['category'])) ? $_GET['category'] : '' }}';
              var month = "&month="+'{{ (isset($_GET['month'])) ? $_GET['month'] : 0 }}';
              var url = '{{ route('home') }}?statsfrom='+from_date+"&statsto="+to_date+category+month;
              window.open(url, '_self');
            } else {
              $('#error_ish').css('display','block');
            }
        });


    	$('#submit-btn_raf').click(function (e) {
          	var from = '';
          	var to = '';
                	var from = $('#from_date_raf').val();
                    if (from == '')
                        $('#from_date_raf').css('border-color','#E91E63');
                    else
                        $('#from_date_raf').css('border-color','#bfc9d4');

                  	var to = $('#to_date_raf').val();
                    if (to == '')
                        $('#to_date_raf').css('border-color','#E91E63');
                    else
                        $('#to_date_raf').css('border-color','#bfc9d4');

          	if (from != '' && to != '') {
              	e.preventDefault();
              	$('#error_raf').css('display','none');
                var category = "&category="+'{{ (isset($_GET['category'])) ? $_GET['category'] : '' }}';
                var month = "&month="+'{{ (isset($_GET['month'])) ? $_GET['month'] : 0 }}';
                var url = '{{ route('home') }}?income_from='+from+"&income_to="+to+category+month;
                window.open(url, '_self');

            } else {
              	$('#error_raf').css('display','block');
            	e.preventDefault();
              	return false;
            }
        });

    </script>


    <script src="{{ URL::asset('plugins/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ URL::asset('plugins/flatpickr/custom-flatpickr.js') }}"></script>
    <script>
        var f1 = flatpickr(document.getElementById('from_date'), {
            enableTime: true,
            dateFormat: "d-m-Y H:i"
        });
        var f = flatpickr(document.getElementById('to_date'), {
            enableTime: true,
            dateFormat: "d-m-Y H:i"
        });

	var f1 = flatpickr(document.getElementById('from_date_raf'), {
             enableTime: true,
             dateFormat: "d-m-Y H:i"
        });
    var f = flatpickr(document.getElementById('to_date_raf'), {
             enableTime: true,
             dateFormat: "d-m-Y H:i"
        });





@endsection
