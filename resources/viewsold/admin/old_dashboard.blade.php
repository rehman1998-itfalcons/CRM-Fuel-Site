@extends('layouts.template')
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
    $set_category = (isset($_GET['category'])) ? $_GET['category'] : 0;
    $categories = DB::table('categories')->select('id','name')->orderBy('name','asc')->get();
    $from = Carbon::now()->subdays(7)->format('Y-m-d').' 00:00:00';
    $to = Carbon::now()->subdays(1)->format('Y-m-d'). ' 24:00:00';
    $expenses = DB::table('expenses')->select('datetime','amount')->whereBetween('datetime',[new Carbon($from),new Carbon($to)])->get();

    if($set_category != '' && $set_category != 0) {

        $sales = Record::
        select('id','company_id','sub_company_id','datetime','status','paid_status','gst','total_amount','paid_amount','total_without_gst','deleted_status')
            ->where('category_id',$_GET['category'])
            ->get();

        $purchases = PurchaseRecord::
        select('id','category_id','supplier_company_id','invoice_number','total_amount','datetime','status','deleted_status')
            ->where('category_id',$_GET['category'])
            ->get();

    } else {

        $sales = Record::
        select('id','company_id','sub_company_id','datetime','status','paid_status','gst','total_amount','paid_amount','total_without_gst','deleted_status')
            ->get();

        $purchases = PurchaseRecord::
        select('id','category_id','supplier_company_id','invoice_number','total_amount','datetime','status','deleted_status')
            ->get();

    }

    $purchases1 = $purchases;
    $tax = $sales->where('status',1)->where('deleted_status',0)->sum('gst');
    $gross = $sales->where('status',1)->where('deleted_status',0)->sum('total_amount');
    //$net = $sales->where('status',1)->where('deleted_status',0)->sum('total_without_gst');
    $net = $gross - $tax;

    /* Overdue Sales */
    $date1 = Carbon::now()->addday(1)->format('d-m-Y');
    $date2 = Carbon::now()->addday(2)->format('d-m-Y');
    $date3 = Carbon::now()->addday(3)->format('d-m-Y');
    $date4 = Carbon::now()->addday(4)->format('d-m-Y');
    $date5 = Carbon::now()->addday(5)->format('d-m-Y');
    $date6 = Carbon::now()->addday(6)->format('d-m-Y');
    $date7 = Carbon::now()->addday(7)->format('d-m-Y');
    $yesterday = Carbon::now()->subdays(1)->format('d-m-Y');
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
    @foreach ($sales->where('status',1)->where('paid_status',0)->where('deleted_status',0) as $record)
        <?php
        $record_date = Carbon::parse($record->datetime)->format('d-m-Y');
        $sub_company_days = $record->subCompany->inv_due_days;
        if ($sub_company_days > 0) {
            $due = date('d-m-Y', strtotime($record_date. ' + '.$sub_company_days.' days'));
        } elseif($sub_company_days < 0) {
            $timestamp = strtotime($record_date);
            $daysRemaining = (int)date('t', $timestamp) - (int)date('j', $timestamp);
            $positive_value =  abs($sub_company_days);
            $original_date = $positive_value + $daysRemaining;
            $due = date('d-m-Y', strtotime($record_date. ' + '.$original_date.' days'));
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
                array_push($array_date_1,$record->id);
                $d1_total++;
                break;
            case $date2:
                array_push($array_date_2,$record->id);
                $d2_total++;
                break;
            case $date3:
                array_push($array_date_3,$record->id);
                $d3_total++;
                break;
            case $date4:
                array_push($array_date_4,$record->id);
                $d4_total++;
                break;
            case $date5:
                array_push($array_date_5,$record->id);
                $d5_total++;
                break;
            case $date6:
                array_push($array_date_6,$record->id);
                $d6_total++;
                break;
            case $date7:
                array_push($array_date_7,$record->id);
                $d7_total++;
                break;
        }

        ?>
    @endforeach
	<?php
		$category_url = (isset($_GET['category'])) ? $_GET['category'] : '';
		$month_url = (isset($_GET['month'])) ? $_GET['month'] : 0;
		$income_from = (isset($_GET['income_from'])) ? $_GET['income_from'] : '';
        $income_to = (isset($_GET['income_to'])) ? $_GET['income_to'] : '';
		$statsfrom = (isset($_GET['statsfrom'])) ? $_GET['statsfrom'] : '';
        $statsto = (isset($_GET['statsto'])) ? $_GET['statsto'] : '';
	?>
    <div class="page-header">
        <div class="page-title">
            <h3></h3>
        </div>
        <div class="dropdown filter custom-dropdown-icon">
            <select class="form-control-custom category-select" style="height: 50px;">
                <option value="{{ url('/home') }}<?php echo '?month='.$month_url.'&income_from='.$income_from.'&income_to='.$income_to.'&statsfrom='.$statsfrom.'&statsto='.$statsto; ?>">-- All Categories --</option>
                @foreach ($categories as $category)
                    <option value="{{ url('/home') }}?category={{ $category->id }}<?php echo '&month='.$month_url.'&income_from='.$income_from.'&income_to='.$income_to.'&statsfrom='.$statsfrom.'&statsto='.$statsto; ?>" @if(isset($_GET['category'])) {{ ($_GET['category'] == $category->id) ? 'selected' : '' }} @endif>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Stats -->
	@include('admin.dashboard_partials.top_stats')

    <!-- Sales/Purchases Report -->
	@include('admin.dashboard_partials.sales_purchases_month_stats')


    @php
        $companies = [];
        $purchases_companies = [];
        $products = \App\Product::select('id','name')->where('status',1)->get();
      if ($set_category != 0 && $set_category != '') {
            $records = Record::select('id','supplier_company_id')->where('category_id',$_GET['category'])->where('status',1)->where('deleted_status',0)->get()->groupBy('supplier_company_id');
            $purchases = PurchaseRecord::select('id','supplier_company_id')->where('category_id',$_GET['category'])->where('status',1)->where('deleted_status',0)->get()->groupBy('supplier_company_id');
      } else {
            $records = Record::select('id','supplier_company_id')->where('status',1)->where('deleted_status',0)->get()->groupBy('supplier_company_id');
            $purchases = PurchaseRecord::select('id','supplier_company_id')->where('status',1)->where('deleted_status',0)->get()->groupBy('supplier_company_id');
      }

      $array = [];
      $per_total = 0;
    @endphp
    @foreach ($records as $supplier_record)
        @php
            $company = $supplier_record->first();
            $total_liters = 0;
        @endphp
        @foreach ($products as $product)
            <?php ${"prod_$product->id"} = 0; ?>
        @endforeach
        @foreach($supplier_record as $rec)
            @foreach ($products as $product)
                @php
                    $data = $rec->products->where('product_id',$product->id)->first();
                    $qty = ($data) ? $data->qty : 0;
                    ${"prod_$product->id"} = ${"prod_$product->id"} + $qty;
                    $total_liters = $total_liters + ${"prod_$product->id"};
                @endphp
            @endforeach
        @endforeach
        @php
            $companies[$company->supplierCompany->name] = $total_liters;
        @endphp
    @endforeach
    @foreach ($purchases as $supplier_record)
        @php
            $company = $supplier_record->first();
            $total_liters = 0;
        @endphp
        @foreach ($products as $product)
            <?php ${"prod_$product->id"} = 0; ?>
        @endforeach
        @foreach($supplier_record as $rec)
            @foreach ($products as $product)
                @php
                    $data = $rec->products->where('product_id',$product->id)->first();
                    $qty = ($data) ? $data->qty : 0;
                    ${"prod_$product->id"} = ${"prod_$product->id"} + $qty;
                    $total_liters = $total_liters + ${"prod_$product->id"};
                @endphp
            @endforeach
        @endforeach
        @php
            $purchases_companies[$company->fuelCompany->name] = $total_liters;
        @endphp
    @endforeach

	<!-- Total Product Sales Report -->
	@include('admin.dashboard_partials.products_suppliers_stats')

	<!-- Total Product Sales Report -->
	@include('admin.dashboard_partials.bottom_stats')

    <!-- Sales/Purchases Monthly Report -->
	@include('admin.dashboard_partials.overall_stats_monthly')

    <?php

    /* Current Month & Year */
    $current_month_name = Carbon::now()->month()->format('M');
    $current_month = Carbon::now()->month;
    $current_year = Carbon::now()->year;
    $date = new DateTime('now');
    $date->modify('last day of this month');

    /* Current Month Weeks */
    $first_day = Carbon::parse('01-'.$current_month.'-'.$current_year)->format('Y-m-d');
    $seven_day = Carbon::parse('07-'.$current_month.'-'.$current_year)->format('Y-m-d');
    $fourteen_day = Carbon::parse('14-'.$current_month.'-'.$current_year)->format('Y-m-d');
    $twentyone_day = Carbon::parse('21-'.$current_month.'-'.$current_year)->format('Y-m-d');
    $thirty_day = $date->format('Y-m-d');

    /* Last Week */
    $last1 = Carbon::now()->subdays(1)->format('Y-m-d');
    $last2 = Carbon::now()->subdays(2)->format('Y-m-d');
    $last3 = Carbon::now()->subdays(3)->format('Y-m-d');
    $last4 = Carbon::now()->subdays(4)->format('Y-m-d');
    $last5 = Carbon::now()->subdays(5)->format('Y-m-d');
    $last6 = Carbon::now()->subdays(6)->format('Y-m-d');
    $last7 = Carbon::now()->subdays(7)->format('Y-m-d');

    /* Last Week Income */
    $last_day_1 = Carbon::now()->subdays(1)->format('d-m-Y');
    $last_day_2 = Carbon::now()->subdays(2)->format('d-m-Y');
    $last_day_3 = Carbon::now()->subdays(3)->format('d-m-Y');
    $last_day_4 = Carbon::now()->subdays(4)->format('d-m-Y');
    $last_day_5 = Carbon::now()->subdays(5)->format('d-m-Y');
    $last_day_6 = Carbon::now()->subdays(6)->format('d-m-Y');
    $last_day_7 = Carbon::now()->subdays(7)->format('d-m-Y');

    ?>

    <!-- Sales Income Stats -->
	{{-- @include('admin.dashboard_partials.income_stats') --}}

    <!-- Companies Overall Stats -->
	{{-- @include('admin.dashboard_partials.companies_stats') --}}

@endsection
@section('scripts')

    {{-- <script src="{{ URL::asset('plugins/table/datatable/datatables.js') }}"></script>
    <script src="{{ URL::asset('plugins/apex/apexcharts.min.js') }}"></script> --}}
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
    <script>

        function sLineInitial() {
            var sline = {
                chart: {
                    height: 400,
                    type: 'bar',
                    zoom: {
                        enabled: false
                    },
                    toolbar: {
                        show: false,
                    },events: {
                        dataPointSelection: function(event, chartContext, config) {
                            if(config.seriesIndex == 1)
                                var series = 'purchases';
                            else
                                var series = 'sales';
                            var d = new Date();
                            var month = d.getMonth();
                            var name = '';
                            switch (month) {
                                case 0:
                                    name = 'janauary';
                                    break;
                                case 1:
                                    name = 'febuary';
                                    break;
                                case 2:
                                    name = 'march';
                                    break;
                                case 3:
                                    name = 'april';
                                    break;
                                case 4:
                                    name = 'may';
                                    break;
                                case 5:
                                    name = 'june';
                                    break;
                                case 6:
                                    name = 'july';
                                    break;
                                case 7:
                                    name = 'august';
                                    break;
                                case 8:
                                    name = 'september';
                                    break;
                                case 9:
                                    name = 'october';
                                    break;
                                case 10:
                                    name = 'november';
                                    break;
                                case 11:
                                    name = 'december';
                                    break;
                            }
                            var index = config.w.config.xaxis.categories[config.dataPointIndex];
                            switch (index) {
                                case '1-7 Days':
                                    var from = '{{ $first_day }}';
                                    var to = '{{ $seven_day }}';
                                    break;
                                case '7-14 Days':
                                    var from = '{{ $seven_day }}';
                                    var to = '{{ $fourteen_day }}';
                                    break;
                                case '13-21 Days':
                                    var from = '{{ $fourteen_day }}';
                                    var to = '{{ $twentyone_day }}';
                                    break;
                                case '21-Last Days':
                                    var from = '{{ $twentyone_day }}';
                                    var to = '{{ $thirty_day }}';
                                    break;
                            }

                            var url = '{{ url('/month-report') }}/'+series+'/'+name+'?from='+from+'&to='+to+'&category='+'{{ $category_url }}';
                            window.open(url);
                        }
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth'
                },
                series: [{
                    name: "Sales",
                    data: [
                      <?php
                            if ($set_category != 0 && $set_category != '') {
                         ?>
                        '{{ Record::where('category_id',$_GET['category'])->whereBetween('datetime',[$first_day, $seven_day])->where('status',1)->where('deleted_status',0)->count() }}',
                        '{{ Record::where('category_id',$_GET['category'])->whereBetween('datetime',[$seven_day, $fourteen_day])->where('status',1)->where('deleted_status',0)->count() }}',
                        '{{ Record::where('category_id',$_GET['category'])->whereBetween('datetime',[$fourteen_day, $twentyone_day])->where('status',1)->where('deleted_status',0)->count() }}',
                        '{{ Record::where('category_id',$_GET['category'])->whereBetween('datetime',[$twentyone_day, $thirty_day])->where('status',1)->where('deleted_status',0)->count() }}',
                      <?php } else {
                        	?>
                        '{{ Record::whereBetween('datetime',[$first_day, $seven_day])->where('status',1)->where('deleted_status',0)->count() }}',
                        '{{ Record::whereBetween('datetime',[$seven_day, $fourteen_day])->where('status',1)->where('deleted_status',0)->count() }}',
                        '{{ Record::whereBetween('datetime',[$fourteen_day, $twentyone_day])->where('status',1)->where('deleted_status',0)->count() }}',
                        '{{ Record::whereBetween('datetime',[$twentyone_day, $thirty_day])->where('status',1)->where('deleted_status',0)->count() }}',
                      <?php
                      	}
                      ?>
                    ]
                },{
                    name: "Purchases",
                    data: [
                      <?php
                            if ($set_category != 0 && $set_category != '') {
                         ?>
                        '{{ PurchaseRecord::where('category_id',$_GET['category'])->whereBetween('datetime',[$first_day, $seven_day])->where('status',1)->count() }}',
                        '{{ PurchaseRecord::where('category_id',$_GET['category'])->whereBetween('datetime',[$seven_day, $fourteen_day])->where('status',1)->count() }}',
                        '{{ PurchaseRecord::where('category_id',$_GET['category'])->whereBetween('datetime',[$fourteen_day, $twentyone_day])->where('status',1)->count() }}',
                        '{{ PurchaseRecord::where('category_id',$_GET['category'])->whereBetween('datetime',[$twentyone_day, $thirty_day])->where('status',1)->count() }}',
                      <?php } else {
                        	?>
                        '{{ PurchaseRecord::whereBetween('datetime',[$first_day, $seven_day])->where('status',1)->count() }}',
                        '{{ PurchaseRecord::whereBetween('datetime',[$seven_day, $fourteen_day])->where('status',1)->count() }}',
                        '{{ PurchaseRecord::whereBetween('datetime',[$fourteen_day, $twentyone_day])->where('status',1)->count() }}',
                        '{{ PurchaseRecord::whereBetween('datetime',[$twentyone_day, $thirty_day])->where('status',1)->count() }}',
                      <?php
                      	}
                      ?>
                    ]
                }],
                title: {
                    text: '',
                    align: 'left'
                },
                grid: {
                    row: {
                        colors: ['#f1f2f3', 'transparent'],
                        opacity: 0.5,
                        margin: ['0px 20px']
                    },
                },
                xaxis: {
                    categories: ['1-7 Days', '7-14 Days', '13-21 Days', '21-Last Days'],
                    align: 'center'
                },
                colors: ['#2e8e3f', '#b1d8b8'],
            }
            var chart = new ApexCharts(
                document.querySelector("#s-line"),
                sline
            );
            chart.render();
        }

    </script>
    <script>

        function monthltyReportInitial() {
            var d_1options1 = {
                chart: {
                    height: 350,
                    type: 'bar',
                    toolbar: {
                        show: false,
                    },
                    dropShadow: {
                        enabled: true,
                        top: 1,
                        left: 1,
                        blur: 2,
                        color: '#acb0c3',
                        opacity: 0.7,
                    },events: {
                        dataPointSelection: function(event, chartContext, config) {
                            if(config.seriesIndex == 1)
                                var series = 'purchases';
                            else
                                var series = 'sales'
                            var month = config.w.config.xaxis.categories[config.dataPointIndex];
                            var name = '';
                            switch (month) {
                                case 'Jan':
                                    name = 'janauary';
                                    break;
                                case 'Feb':
                                    name = 'febuary';
                                    break;
                                case 'Mar':
                                    name = 'march';
                                    break;
                                case 'Apr':
                                    name = 'april';
                                    break;
                                case 'May':
                                    name = 'may';
                                    break;
                                case 'Jun':
                                    name = 'june';
                                    break;
                                case 'Jul':
                                    name = 'july';
                                    break;
                                case 'Aug':
                                    name = 'august';
                                    break;
                                case 'Sep':
                                    name = 'september';
                                    break;
                                case 'Oct':
                                    name = 'october';
                                    break;
                                case 'Nov':
                                    name = 'november';
                                    break;
                                case 'Dec':
                                    name = 'december';
                                    break;
                            }
                            var url = '{{ url('/monthly-report') }}/'+series+'/'+name;
                            window.open(url);
                        }
                    }
                },
                colors: ['#5c1ac3', '#ffbb44'],
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        endingShape: 'rounded'
                    },
                },
                dataLabels: {
                    enabled: false
                },
                legend: {
                    position: 'bottom',
                    horizontalAlign: 'center',
                    fontSize: '14px',
                    markers: {
                        width: 10,
                        height: 10,
                    },
                    itemMargin: {
                        horizontal: 0,
                        vertical: 8
                    }
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                series: [{
                    name: 'Sales',
                    data: [
                        <?php
                            if ($set_category != 0 && $set_category != '') {
                            ?>
                            '{{ Record::where('category_id',$_GET['category'])->whereMonth('datetime', '01')->whereYear('datetime',$current_year)->where('status',1)->where('deleted_status',0)->count() }}',
                        '{{ Record::where('category_id',$_GET['category'])->whereMonth('datetime', '02')->whereYear('datetime',$current_year)->where('status',1)->where('deleted_status',0)->count() }}',
                        '{{ Record::where('category_id',$_GET['category'])->whereMonth('datetime', '03')->whereYear('datetime',$current_year)->where('status',1)->where('deleted_status',0)->count() }}',
                        '{{ Record::where('category_id',$_GET['category'])->whereMonth('datetime', '04')->whereYear('datetime',$current_year)->where('status',1)->where('deleted_status',0)->count() }}',
                        '{{ Record::where('category_id',$_GET['category'])->whereMonth('datetime', '05')->whereYear('datetime',$current_year)->where('status',1)->where('deleted_status',0)->count() }}',
                        '{{ Record::where('category_id',$_GET['category'])->whereMonth('datetime', '06')->whereYear('datetime',$current_year)->where('status',1)->where('deleted_status',0)->count() }}',
                        '{{ Record::where('category_id',$_GET['category'])->whereMonth('datetime', '07')->whereYear('datetime',$current_year)->where('status',1)->where('deleted_status',0)->count() }}',
                        '{{ Record::where('category_id',$_GET['category'])->whereMonth('datetime', '08')->whereYear('datetime',$current_year)->where('status',1)->where('deleted_status',0)->count() }}',
                        '{{ Record::where('category_id',$_GET['category'])->whereMonth('datetime', '09')->whereYear('datetime',$current_year)->where('status',1)->where('deleted_status',0)->count() }}',
                        '{{ Record::where('category_id',$_GET['category'])->whereMonth('datetime', '10')->whereYear('datetime',$current_year)->where('status',1)->where('deleted_status',0)->count() }}',
                        '{{ Record::where('category_id',$_GET['category'])->whereMonth('datetime', '11')->whereYear('datetime',$current_year)->where('status',1)->where('deleted_status',0)->count() }}',
                        '{{ Record::where('category_id',$_GET['category'])->whereMonth('datetime', '12')->whereYear('datetime',$current_year)->where('status',1)->where('deleted_status',0)->count() }}',
                        <?php
                            } else {
                            ?>
                            '{{ Record::whereMonth('datetime', '01')->whereYear('datetime',$current_year)->where('status',1)->where('deleted_status',0)->count() }}',
                        '{{ Record::whereMonth('datetime', '02')->whereYear('datetime',$current_year)->where('status',1)->where('deleted_status',0)->count() }}',
                        '{{ Record::whereMonth('datetime', '03')->whereYear('datetime',$current_year)->where('status',1)->where('deleted_status',0)->count() }}',
                        '{{ Record::whereMonth('datetime', '04')->whereYear('datetime',$current_year)->where('status',1)->where('deleted_status',0)->count() }}',
                        '{{ Record::whereMonth('datetime', '05')->whereYear('datetime',$current_year)->where('status',1)->where('deleted_status',0)->count() }}',
                        '{{ Record::whereMonth('datetime', '06')->whereYear('datetime',$current_year)->where('status',1)->where('deleted_status',0)->count() }}',
                        '{{ Record::whereMonth('datetime', '07')->whereYear('datetime',$current_year)->where('status',1)->where('deleted_status',0)->count() }}',
                        '{{ Record::whereMonth('datetime', '08')->whereYear('datetime',$current_year)->where('status',1)->where('deleted_status',0)->count() }}',
                        '{{ Record::whereMonth('datetime', '09')->whereYear('datetime',$current_year)->where('status',1)->where('deleted_status',0)->count() }}',
                        '{{ Record::whereMonth('datetime', '10')->whereYear('datetime',$current_year)->where('status',1)->where('deleted_status',0)->count() }}',
                        '{{ Record::whereMonth('datetime', '11')->whereYear('datetime',$current_year)->where('status',1)->where('deleted_status',0)->count() }}',
                        '{{ Record::whereMonth('datetime', '12')->whereYear('datetime',$current_year)->where('status',1)->where('deleted_status',0)->count() }}',
                        <?php
                        }
                        ?>
                    ]
                }, {
                    name: 'Purchases',
                    data: [
                        <?php
                            if ($set_category != 0 && $set_category != '') {
                            ?>
                            '{{ PurchaseRecord::where('category_id',$_GET['category'])->whereMonth('datetime', '01')->whereYear('datetime',$current_year)->where('status',1)->count() }}',
                        '{{ PurchaseRecord::where('category_id',$_GET['category'])->whereMonth('datetime', '02')->whereYear('datetime',$current_year)->where('status',1)->count() }}',
                        '{{ PurchaseRecord::where('category_id',$_GET['category'])->whereMonth('datetime', '03')->whereYear('datetime',$current_year)->where('status',1)->count() }}',
                        '{{ PurchaseRecord::where('category_id',$_GET['category'])->whereMonth('datetime', '04')->whereYear('datetime',$current_year)->where('status',1)->count() }}',
                        '{{ PurchaseRecord::where('category_id',$_GET['category'])->whereMonth('datetime', '05')->whereYear('datetime',$current_year)->where('status',1)->count() }}',
                        '{{ PurchaseRecord::where('category_id',$_GET['category'])->whereMonth('datetime', '06')->whereYear('datetime',$current_year)->where('status',1)->count() }}',
                        '{{ PurchaseRecord::where('category_id',$_GET['category'])->whereMonth('datetime', '07')->whereYear('datetime',$current_year)->where('status',1)->count() }}',
                        '{{ PurchaseRecord::where('category_id',$_GET['category'])->whereMonth('datetime', '08')->whereYear('datetime',$current_year)->where('status',1)->count() }}',
                        '{{ PurchaseRecord::where('category_id',$_GET['category'])->whereMonth('datetime', '09')->whereYear('datetime',$current_year)->where('status',1)->count() }}',
                        '{{ PurchaseRecord::where('category_id',$_GET['category'])->whereMonth('datetime', '10')->whereYear('datetime',$current_year)->where('status',1)->count() }}',
                        '{{ PurchaseRecord::where('category_id',$_GET['category'])->whereMonth('datetime', '11')->whereYear('datetime',$current_year)->where('status',1)->count() }}',
                        '{{ PurchaseRecord::where('category_id',$_GET['category'])->whereMonth('datetime', '12')->whereYear('datetime',$current_year)->where('status',1)->count() }}',
                        <?php
                            } else {
                            ?>
                            '{{ PurchaseRecord::whereMonth('datetime', '01')->whereYear('datetime',$current_year)->where('status',1)->count() }}',
                        '{{ PurchaseRecord::whereMonth('datetime', '02')->whereYear('datetime',$current_year)->where('status',1)->count() }}',
                        '{{ PurchaseRecord::whereMonth('datetime', '03')->whereYear('datetime',$current_year)->where('status',1)->count() }}',
                        '{{ PurchaseRecord::whereMonth('datetime', '04')->whereYear('datetime',$current_year)->where('status',1)->count() }}',
                        '{{ PurchaseRecord::whereMonth('datetime', '05')->whereYear('datetime',$current_year)->where('status',1)->count() }}',
                        '{{ PurchaseRecord::whereMonth('datetime', '06')->whereYear('datetime',$current_year)->where('status',1)->count() }}',
                        '{{ PurchaseRecord::whereMonth('datetime', '07')->whereYear('datetime',$current_year)->where('status',1)->count() }}',
                        '{{ PurchaseRecord::whereMonth('datetime', '08')->whereYear('datetime',$current_year)->where('status',1)->count() }}',
                        '{{ PurchaseRecord::whereMonth('datetime', '09')->whereYear('datetime',$current_year)->where('status',1)->count() }}',
                        '{{ PurchaseRecord::whereMonth('datetime', '10')->whereYear('datetime',$current_year)->where('status',1)->count() }}',
                        '{{ PurchaseRecord::whereMonth('datetime', '11')->whereYear('datetime',$current_year)->where('status',1)->count() }}',
                        '{{ PurchaseRecord::whereMonth('datetime', '12')->whereYear('datetime',$current_year)->where('status',1)->count() }}',
                        <?php
                        }
                        ?>
                    ]
                }],
                xaxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                },
                colors: ['#2e8e3f', '#b1d8b8'],
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return val
                        }
                    }
                }
            }

            var d_1C_3 = new ApexCharts(
                document.querySelector("#monthly-report"),
                d_1options1
            );

            d_1C_3.render();
        }

    </script>
    <script>

        function sLine() {
            $("#s-line").html('');
            var param = $('#s-line-value').val();
            if (param == 'Monthly') {
                $('#s-line-type').html('Monthly');
                sLineInitial();

            } else {

                $('#s-line-type').html('Last Week');
                var sline = {
                    chart: {
                        height: 400,
                        type: 'bar',
                        zoom: {
                            enabled: false
                        },
                        toolbar: {
                            show: false,
                        },events: {
                            dataPointSelection: function(event, chartContext, config) {
                                if(config.seriesIndex == 1)
                                    var series = 'purchases';
                                else
                                    var series = 'sales';
                                var d = new Date();
                                var month = d.getMonth();
                                var day = config.w.config.xaxis.categories[config.dataPointIndex];
                                var name = '';
                                switch (month) {
                                    case 0:
                                        name = 'janauary';
                                        break;
                                    case 1:
                                        name = 'febuary';
                                        break;
                                    case 2:
                                        name = 'march';
                                        break;
                                    case 3:
                                        name = 'april';
                                        break;
                                    case 4:
                                        name = 'may';
                                        break;
                                    case 5:
                                        name = 'june';
                                        break;
                                    case 6:
                                        name = 'july';
                                        break;
                                    case 7:
                                        name = 'august';
                                        break;
                                    case 8:
                                        name = 'september';
                                        break;
                                    case 9:
                                        name = 'october';
                                        break;
                                    case 10:
                                        name = 'november';
                                        break;
                                    case 11:
                                        name = 'december';
                                        break;
                                }
                                var url = '{{ url('/monthly-report-filter') }}/'+series+'/'+name+'/'+day.substring(0,2);
                                window.open(url);
                            }
                        }
                    },
                    colors: ['#5c1ac3', '#ffbb44'],
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '55%',
                            endingShape: 'rounded'
                        },
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        curve: 'smooth'
                    },
                    series: [{
                        name: "Sales",
                        data: [
                      <?php
                            if ($set_category != 0 && $set_category != '') {
                         ?>
                          	'{{ $sales->where('category_id',$_GET['category'])->whereBetween('datetime',[$last7.' 00:00:00', $last7.' 24:00:00'])->where('status',1)->where('deleted_status',0)->count() }}',
                            '{{ $sales->where('category_id',$_GET['category'])->whereBetween('datetime',[$last6.' 00:00:00', $last6.' 24:00:00'])->where('status',1)->where('deleted_status',0)->count() }}',
                            '{{ $sales->where('category_id',$_GET['category'])->whereBetween('datetime',[$last5.' 00:00:00', $last5.' 24:00:00'])->where('status',1)->where('deleted_status',0)->count() }}',
                            '{{ $sales->where('category_id',$_GET['category'])->whereBetween('datetime',[$last4.' 00:00:00', $last4.' 24:00:00'])->where('status',1)->where('deleted_status',0)->count() }}',
                            '{{ $sales->where('category_id',$_GET['category'])->whereBetween('datetime',[$last3.' 00:00:00', $last3.' 24:00:00'])->where('status',1)->where('deleted_status',0)->count() }}',
                            '{{ $sales->where('category_id',$_GET['category'])->whereBetween('datetime',[$last2.' 00:00:00', $last2.' 24:00:00'])->where('status',1)->where('deleted_status',0)->count() }}',
                            '{{ $sales->where('category_id',$_GET['category'])->whereBetween('datetime',[$last1.' 00:00:00', $last1.' 24:00:00'])->where('status',1)->where('deleted_status',0)->count() }}',
                          <?php
                            } else {
                              	?>
                          	'{{ $sales->whereBetween('datetime',[$last7.' 00:00:00', $last7.' 24:00:00'])->where('status',1)->where('deleted_status',0)->count() }}',
                            '{{ $sales->whereBetween('datetime',[$last6.' 00:00:00', $last6.' 24:00:00'])->where('status',1)->where('deleted_status',0)->count() }}',
                            '{{ $sales->whereBetween('datetime',[$last5.' 00:00:00', $last5.' 24:00:00'])->where('status',1)->where('deleted_status',0)->count() }}',
                            '{{ $sales->whereBetween('datetime',[$last4.' 00:00:00', $last4.' 24:00:00'])->where('status',1)->where('deleted_status',0)->count() }}',
                            '{{ $sales->whereBetween('datetime',[$last3.' 00:00:00', $last3.' 24:00:00'])->where('status',1)->where('deleted_status',0)->count() }}',
                            '{{ $sales->whereBetween('datetime',[$last2.' 00:00:00', $last2.' 24:00:00'])->where('status',1)->where('deleted_status',0)->count() }}',
                            '{{ $sales->whereBetween('datetime',[$last1.' 00:00:00', $last1.' 24:00:00'])->where('status',1)->where('deleted_status',0)->count() }}',
                          		<?php
                            }
                          ?>
                        ]
                    },{
                        name: "Purchases",
                        data: [
                      <?php
                            if ($set_category != 0 && $set_category != '') {
                         ?>
                          	'{{ $purchases->where('category_id',$_GET['category'])->whereBetween('datetime',[$last7.' 00:00:00', $last7.' 24:00:00'])->where('status',1)->where('deleted_status',0)->count() }}',
                            '{{ $purchases->where('category_id',$_GET['category'])->whereBetween('datetime',[$last6.' 00:00:00', $last6.' 24:00:00'])->where('status',1)->where('deleted_status',0)->count() }}',
                            '{{ $purchases->where('category_id',$_GET['category'])->whereBetween('datetime',[$last5.' 00:00:00', $last5.' 24:00:00'])->where('status',1)->where('deleted_status',0)->count() }}',
                            '{{ $purchases->where('category_id',$_GET['category'])->whereBetween('datetime',[$last4.' 00:00:00', $last4.' 24:00:00'])->where('status',1)->where('deleted_status',0)->count() }}',
                            '{{ $purchases->where('category_id',$_GET['category'])->whereBetween('datetime',[$last3.' 00:00:00', $last3.' 24:00:00'])->where('status',1)->where('deleted_status',0)->count() }}',
                            '{{ $purchases->where('category_id',$_GET['category'])->whereBetween('datetime',[$last2.' 00:00:00', $last2.' 24:00:00'])->where('status',1)->where('deleted_status',0)->count() }}',
                            '{{ $purchases->where('category_id',$_GET['category'])->whereBetween('datetime',[$last1.' 00:00:00', $last1.' 24:00:00'])->where('status',1)->where('deleted_status',0)->count() }}',
                          <?php
                            } else {
                              	?>
                          	'{{ $purchases->whereBetween('datetime',[$last7.' 00:00:00', $last7.' 24:00:00'])->where('status',1)->where('deleted_status',0)->count() }}',
                            '{{ $purchases->whereBetween('datetime',[$last6.' 00:00:00', $last6.' 24:00:00'])->where('status',1)->where('deleted_status',0)->count() }}',
                            '{{ $purchases->whereBetween('datetime',[$last5.' 00:00:00', $last5.' 24:00:00'])->where('status',1)->where('deleted_status',0)->count() }}',
                            '{{ $purchases->whereBetween('datetime',[$last4.' 00:00:00', $last4.' 24:00:00'])->where('status',1)->where('deleted_status',0)->count() }}',
                            '{{ $purchases->whereBetween('datetime',[$last3.' 00:00:00', $last3.' 24:00:00'])->where('status',1)->where('deleted_status',0)->count() }}',
                            '{{ $purchases->whereBetween('datetime',[$last2.' 00:00:00', $last2.' 24:00:00'])->where('status',1)->where('deleted_status',0)->count() }}',
                            '{{ $purchases->whereBetween('datetime',[$last1.' 00:00:00', $last1.' 24:00:00'])->where('status',1)->where('deleted_status',0)->count() }}',
                          		<?php
                            }
                          ?>
                        ]
                    }],
                    title: {
                        text: '',
                        align: 'left'
                    },
                    grid: {
                        row: {
                            colors: ['#f1f2f3', 'transparent'],
                            opacity: 0.5,
                            margin: ['0px 20px']
                        },
                    },
                    xaxis: {
                        categories: [
                            '{{ date('d', strtotime($last7)) }} {{ $current_month_name }}',
                            '{{ date('d', strtotime($last6)) }} {{ $current_month_name }}',
                            '{{ date('d', strtotime($last5)) }} {{ $current_month_name }}',
                            '{{ date('d', strtotime($last4)) }} {{ $current_month_name }}',
                            '{{ date('d', strtotime($last3)) }} {{ $current_month_name }}',
                            '{{ date('d', strtotime($last2)) }} {{ $current_month_name }}',
                            '{{ date('d', strtotime($last1)) }} {{ $current_month_name }}'
                        ],
                        align: 'center'
                    },
                    colors: ['#2e8e3f', '#b1d8b8'],
                }
                var chart = new ApexCharts(
                    document.querySelector("#s-line"),
                    sline
                );
                chart.render();
            }
        }

    </script>
    <script>

        function supplierInital() {
            var options = {
                series:
                    [
                        {
                            name: "Supplier Sales",
                            data: [
                                <?php
                                foreach ($companies as $company => $liter) {
                                ?>
                                {{ $liter }},
                                <?php
                                }
                                ?>
                            ]
                        },
                        {
                            name: "Supplier Purchases",
                            data: [
                                <?php
                                foreach ($purchases_companies as $company => $liter) {
                                ?>
                                {{ $liter }},
                                <?php
                                }
                                ?>
                            ]
                        }
                    ],
                chart: {
                    type: 'bar',
                    height: 550,
                    events: {
                        dataPointSelection: function(event, chartContext, config) {
                            if(config.seriesIndex == 1)
                                var series = 'purchases';
                            else
                                var series = 'sales';
                            var company = config.w.config.xaxis.categories[config.dataPointIndex];
                            var url = '{{ url('/supplier-company-report') }}/'+series+'/'+company;
                            window.open(url);
                        }
                    },
                }, plotOptions: {
                    bar: {
                        horizontal: true,
                    }
                }, dataLabels: {
                    enabled: false
                },                 xaxis: {
                    categories: [
                        <?php
                            foreach ($companies as $company => $liter){
                            ?>
                            '{{ $company }}',
                        <?php
                        }
                        ?>
                    ],
                },
                colors: ['#2e8e3f', '#b1d8b8'],
            }

            var chart = new ApexCharts(
                document.querySelector("#s_companies"),
                options
            );
            chart.render();
        }

    </script>
    <script>
        function monthlyReport() {
            var param = $('#monthly-report-value').val();
            window.location.href = '/home?month='+param+'&category='+'{{ $category_url }}'+'&income_from='+'{{ $income_from }}'+'&income_to='+'{{ $income_to }}'+'&statsfrom='+'{{ $statsfrom }}'+'&statsto='+'{{ $statsto }}';
        }

        $('.category-select').on('change', function(){
            window.location = $(this).val();
        });

    </script>
    @if(isset($_GET['month']))
        <script>

            $(document).ready(function () {
                var param = '{{ $_GET['month'] }}';
                $("#monthly-report").html('');
                if (param == 0) {
                    monthltyReportInitial();
                } else {

                    var d = new Date();
                    var days = new Date(d.getFullYear(), param, 0).getDate();
                    var days_array = [];

                    // All Days
                    for (var i = 1; i <= days; i++)
                        days_array.push(i);

                    var d_1options1 = {
                        chart: {
                            height: 350,
                            type: 'bar',
                            toolbar: {
                                show: false,
                            },
                            dropShadow: {
                                enabled: true,
                                top: 1,
                                left: 1,
                                blur: 2,
                                color: '#acb0c3',
                                opacity: 0.7,
                            },events: {
                                dataPointSelection: function(event, chartContext, config) {
                                    if(config.seriesIndex == 1)
                                        var series = 'purchases';
                                    else
                                        var series = 'sales';
                                        <?php
                                        $month = ($_GET['month']) ? $_GET['month'] : 0;
                                        $days = ($month > 0) ? cal_days_in_month(CAL_GREGORIAN,$month,date("Y")) : 0;
                                        ?>
                                    var m = 0;
                                    switch ('{{ $month }}') {
                                        case '1':
                                            m = 'janauary';
                                            break;
                                        case '2':
                                            m = 'febuary';
                                            break;
                                        case '3':
                                            m = 'march';
                                            break;
                                        case '4':
                                            m = 'april';
                                            break;
                                        case '5':
                                            m = 'may';
                                            break;
                                        case '6':
                                            m = 'june';
                                            break;
                                        case '7':
                                            m = 'july';
                                            break;
                                        case '8':
                                            m = 'august';
                                            break;
                                        case '9':
                                            m = 'september';
                                            break;
                                        case '10':
                                            m = 'october';
                                            break;
                                        case '11':
                                            m = 'november';
                                            break;
                                        case '12':
                                            m = 'december';
                                            break;
                                    }
                                    var name = config.w.config.xaxis.categories[config.dataPointIndex];
                                    var url = '{{ url('/monthly-report-filter') }}/'+ series +'/'+ m +'/'+ name;
                                    window.open(url);
                                }
                            }
                        },
                        colors: ['#5c1ac3', '#ffbb44'],
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                columnWidth: '55%',
                                endingShape: 'rounded'
                            },
                        },
                        dataLabels: {
                            enabled: false
                        },
                        legend: {
                            position: 'bottom',
                            horizontalAlign: 'center',
                            fontSize: '14px',
                            markers: {
                                width: 10,
                                height: 10,
                            },
                            itemMargin: {
                                horizontal: 0,
                                vertical: 8
                            }
                        },
                        stroke: {
                            show: true,
                            width: 2,
                            colors: ['transparent']
                        },
                        series: [{
                            name: 'Sales',
                            data: [
                                <?php
                                    for ($i = 1; $i <= $days; $i++) {
                                    if ($set_category != 0 && $set_category != '') {
                                    ?>
                                    '{{ \App\Record::where('category_id',$_GET['category'])->whereDay('datetime', $i)->whereMonth('datetime', $month)->whereYear('datetime',\Carbon\Carbon::now()->year)->where('status',1)->where('deleted_status',0)->count() }}',
                                <?php
                                    } else {
                                    ?>
                                    '{{ \App\Record::whereDay('datetime', $i)->whereMonth('datetime', $month)->whereYear('datetime',\Carbon\Carbon::now()->year)->where('status',1)->where('deleted_status',0)->count() }}',
                                <?php
                                }
                                }
                                ?>
                            ]
                        }, {
                            name: 'Purchases',
                            data: [
                                <?php
                                    for ($i = 1; $i <= $days; $i++) {
                                    if ($set_category != 0 && $set_category != '') {
                                    ?>
                                    '{{ \App\PurchaseRecord::where('category_id',$_GET['category'])->whereDay('datetime', $i)->whereMonth('datetime', $month)->whereYear('datetime',\Carbon\Carbon::now()->year)->where('status',1)->where('deleted_status',0)->count() }}',
                                <?php
                                    } else {
                                    ?>
                                    '{{ \App\PurchaseRecord::whereDay('datetime', $i)->whereMonth('datetime', $month)->whereYear('datetime',\Carbon\Carbon::now()->year)->where('status',1)->where('deleted_status',0)->count() }}',
                                <?php
                                }
                                }
                                ?>
                            ]
                        }],
                        xaxis: {
                            categories: days_array,
                        },
                        colors: ['#2e8e3f', '#b1d8b8'],

                    }

                    var d_1C_3 = new ApexCharts(
                        document.querySelector("#monthly-report"),
                        d_1options1
                    );

                    d_1C_3.render();
                }

            });

        </script>
    @endif
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
</script>
@endsection
