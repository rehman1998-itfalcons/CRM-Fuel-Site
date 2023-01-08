@extends('dashboard.layouts.layout.main')
{{-- @extends('layouts.layout.main') --}}
@section('title','Dashboard')
@section('css')


      <link href="{{ URL::asset('plugins/apex/apexcharts.css') }}" rel="stylesheet" type="text/css">


@endsection
@section('contents')
    <?php

    use Carbon\Carbon;
    use App\Record;
    use App\PurchaseRecord;
    use App\RecordProduct;

    $purchases_record = [];
$set_category = isset($_GET['category']) ? $_GET['category'] : 0;


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
    //for Recent Activity
    $records = Record::where('supervisor_status', 0)
        ->where('deleted_status', 0)
        ->get();
    $account = Record::where('supervisor_status', 1)
        ->where('status', 0)
        ->where('deleted_status', 0)
        ->get();
    $sal = Record::where('deleted_status', 1)->get();

    ?>
<?php
$category_url = (isset($_GET['category'])) ? $_GET['category'] : '';
$month_url = (isset($_GET['month'])) ? $_GET['month'] : 0;
$income_from = (isset($_GET['income_from'])) ? $_GET['income_from'] : '';
$income_to = (isset($_GET['income_to'])) ? $_GET['income_to'] : '';
$statsfrom = (isset($_GET['statsfrom'])) ? $_GET['statsfrom'] : '';
$statsto = (isset($_GET['statsto'])) ? $_GET['statsto'] : '';
?>


<?php
    
    $current_month_name = Carbon::now()->month()->format('M');
    $current_month = Carbon::now()->month;
    $current_year = Carbon::now()->year;
    $date = new DateTime('now');
    $date->modify('last day of this month');

    
    $first_day = Carbon::parse('01-'.$current_month.'-'.$current_year)->format('Y-m-d');
    $seven_day = Carbon::parse('07-'.$current_month.'-'.$current_year)->format('Y-m-d');
    $fourteen_day = Carbon::parse('14-'.$current_month.'-'.$current_year)->format('Y-m-d');
    $twentyone_day = Carbon::parse('21-'.$current_month.'-'.$current_year)->format('Y-m-d');
    $thirty_day = $date->format('Y-m-d');

   
    $last1 = Carbon::now()->subdays(1)->format('Y-m-d');
    $last2 = Carbon::now()->subdays(2)->format('Y-m-d');
    $last3 = Carbon::now()->subdays(3)->format('Y-m-d');
    $last4 = Carbon::now()->subdays(4)->format('Y-m-d');
    $last5 = Carbon::now()->subdays(5)->format('Y-m-d');
    $last6 = Carbon::now()->subdays(6)->format('Y-m-d');
    $last7 = Carbon::now()->subdays(7)->format('Y-m-d');

    
    $last_day_1 = Carbon::now()->subdays(1)->format('d-m-Y');
    $last_day_2 = Carbon::now()->subdays(2)->format('d-m-Y');
    $last_day_3 = Carbon::now()->subdays(3)->format('d-m-Y');
    $last_day_4 = Carbon::now()->subdays(4)->format('d-m-Y');
    $last_day_5 = Carbon::now()->subdays(5)->format('d-m-Y');
    $last_day_6 = Carbon::now()->subdays(6)->format('d-m-Y');
    $last_day_7 = Carbon::now()->subdays(7)->format('d-m-Y');
?>
  <div class="dropdown filter custom-dropdown-icon">
    
</div>
@include('admin.dashboard_partials.top_stats')
@include('admin.dashboard_partials.bottom_stats')


<div class="container-fluid p-0">
    <div class="row justify-content-center">
        <div class="white_box mb_20">
        <div class="col-xl-12">
            @include('admin.dashboard_partials.sales_purchases_month_stats')
        </div>
    </div>
    </div>
</div>

<div class="container-fluid p-0">
    <div class="row justify-content-center">
        <div class="white_box mb_20">
        <div class="col-xl-12">
             
	            @include('admin.dashboard_partials.overall_stats_monthly')
        </div>
    </div>
    </div>
</div>
<div class="col-lg-12 col-xl-12 col-md-12">
    <div class="white_box mb_30">
        <div id="apex_3"></div>
    </div>
</div>

<div class="container-fluid p-0">
    <div class="row justify-content-center">
        <div class="white_box mb_20">
        <div class="col-xl-12 ">
           
	@include('admin.dashboard_partials.products_suppliers_stats')
        </div>
    </div>
    </div>
</div>

@include('admin.dashboard_partials.income_stats')

 {{-- //starting table --}}
 @php

 use App\Log;
        $activity_logs = Log::latest()->take(3)->get();
       // dd($activity_logs);
 @endphp
 <div class="container-fluid p-0">
    <div class="row justify-content-center">
        <div class="white_box mb_20">

<div class="col-md-12 col-lg-12 col-sm-12">
    <div class="QA_section">
        <div class="white_box_tittle list_header">
            <h3> Activity Logs</h3>
            <div class="box_right d-flex lms_block">
                <div class="serach_field_2">
                    <div class="search_inner">
                        {{-- <form Active="#">
                            <div class="search_field">
                                <input type="text" placeholder="Search content here...">
                            </div>
                            <button type="submit"> <i class="ti-search"></i> </button>
                        </form> --}}
                    </div>
                </div>
                {{-- <div class="add_button ms-2">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#addcategory"
                        class="btn-primary">Add New</a>
                </div> --}}
            </div>
        </div>
        <div class="QA_table mb_30">

                    <table class="table lms_table_active data-table" style="border:none;" >
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








 {{-- @include('admin.dashboard_partials.companies_stats') --}}

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



@endsection
@section('scripts')

    {{-- <script src="{{ URL::asset('plugins/table/datatable/datatables.js') }}"></script> --}}
    {{-- <script src="{{ URL::asset('plugins/apex/apexcharts.min.js') }}"></script> --}}
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
               
                "sSearchPlaceholder": "Search...",
                "sLengthMenu": "Results :  _MENU_",
            },
            "stripeClasses": [],
            "lengthMenu": [10, 20, 30, 50],
            "pageLength": 10
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

        $(document).ready(function () {
            sLineInitial();
            monthltyReportInitial();
            supplierInital();
        });
        function sLine() {
            $("#s-line").html('');
            var param = $('#s-line-value').val();
            if (param == 'Monthly') {
                $('#s-line-value').html('Monthly');
                sLineInitial();

            } else {

                $('#s-line-value').html('Last Week');
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
                    colors: ['#00E396', '#008FFB'],
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '45%',
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
                            colors: ['#00E396', '#008FFB'],
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
                    colors: ['#00E396', '#008FFB'],
                }
                var chart = new ApexCharts(
                    document.querySelector("#s-line"),
                    sline
                );
                chart.render();
            }
        }

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
                colors: ['#008ffb', '#FEB019'],
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
                colors: ['#008ffb', '#FEB019'],
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
                colors: ['#008ffb', '#FEB019'],
            }

            var chart = new ApexCharts(
                document.querySelector("#s_companies"),
                options
            );
            chart.render();
        }

        $('#from_date').datepicker();
        $('#to_date').datepicker();
        $('#from_date_raf').datepicker();
        $('#to_date_raf').datepicker();
    </script>

    <script>
    $(document).ready(function () {
    var options = {
        series: [
            { name: "Sales",   data: [
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
                        ] },
            { name: "Purchases", data: [
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
                        ] },
        ],
        chart: { height: 350, type: "area" },
        dataLabels: { enabled: false },
        stroke: { curve: "smooth" },
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
        },
        
    };
    var chart = new ApexCharts(document.querySelector("#apex_3"), options);
    chart.render();
});
    
</script>

    




@endsection
