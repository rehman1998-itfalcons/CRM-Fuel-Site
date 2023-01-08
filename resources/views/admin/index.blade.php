@extends('admin.Layout.main')
@section('contant')
    <?php

    use Carbon\Carbon;
    use App\Record;
    use App\PurchaseRecord;
    use App\RecordProduct;

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
                                            <img src="img/icon/truck.png" alt="">
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
                                            <img src="img/icon/data.png" alt="">
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
                                            <img src="img/icon/inv.png" alt="">
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
                                            <img src="img/icon/dollar.png" alt="">
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
                            <h3 class="mb_25">Hospital Survey</h3>
                        </div>
                    </div>
                    <div class="income_servay">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="count_content">
                                    <h3>$ <span class="counter">305</span> </h3>
                                    <p>Today's Income</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="count_content">
                                    <h3>$ <span class="counter">1005</span> </h3>
                                    <p>This Week's Income</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="count_content">
                                    <h3>$ <span class="counter">5505</span> </h3>
                                    <p>This Month's Income</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="count_content">
                                    <h3>$ <span class="counter">155615</span> </h3>
                                    <p>This Year's Income</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="bar_wev"></div>
                </div>
            </div>
            <div class="col-xl-7">
                <div class="white_box QA_section card_height_100">
                    <div class="white_box_tittle list_header m-0 align-items-center">
                        <div class="main-title mb-sm-15">
                            <h3 class="m-0 nowrap">Patients</h3>
                        </div>
                        <div class="box_right d-flex lms_block">
                            <div class="serach_field-area2">
                                <div class="search_inner">
                                    <form Active="#">
                                        <div class="search_field">
                                            <input type="text" placeholder="Search here...">
                                        </div>
                                        <button type="submit"> <i class="ti-search"></i> </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="QA_table ">

                        <table class="table lms_table_active2">
                            <thead>
                                <tr>
                                    <th scope="col">Patients Name</th>
                                    <th scope="col">department</th>
                                    <th scope="col">Appointment Date</th>
                                    <th scope="col">Serial Number</th>
                                    <th scope="col">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">
                                        <div class="patient_thumb d-flex align-items-center">
                                            <div class="student_list_img mr_20">
                                                <img src="img/patient/pataint.png" alt="" srcset="">
                                            </div>
                                            <p>Jhon Kural</p>
                                        </div>
                                    </th>
                                    <td>Monte Carlo</td>
                                    <td>11/03/2020</td>
                                    <td>MDC65454</td>
                                    <td>
                                        <div class="amoutn_action d-flex align-items-center">
                                            $29,192
                                            <div class="dropdown ms-4">
                                                <a class=" dropdown-toggle hide_pils" href="#" role="button"
                                                    id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right"
                                                    aria-labelledby="dropdownMenuLink">
                                                    <a class="dropdown-item" href="#">View</a>
                                                    <a class="dropdown-item" href="#">Edit</a>
                                                    <a class="dropdown-item" href="#">Delete</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        <div class="patient_thumb d-flex align-items-center">
                                            <div class="student_list_img mr_20">
                                                <img src="img/patient/2.png" alt="" srcset="">
                                            </div>
                                            <p>Jhon Kural</p>
                                        </div>
                                    </th>
                                    <td>Monte Carlo</td>
                                    <td>11/03/2020</td>
                                    <td>MDC65454</td>
                                    <td>
                                        <div class="amoutn_action d-flex align-items-center">
                                            $29,192
                                            <div class="dropdown ms-4">
                                                <a class=" dropdown-toggle hide_pils" href="#" role="button"
                                                    id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right"
                                                    aria-labelledby="dropdownMenuLink">
                                                    <a class="dropdown-item" href="#">View</a>
                                                    <a class="dropdown-item" href="#">Edit</a>
                                                    <a class="dropdown-item" href="#">Delete</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        <div class="patient_thumb d-flex align-items-center">
                                            <div class="student_list_img mr_20">
                                                <img src="img/patient/3.png" alt="" srcset="">
                                            </div>
                                            <p>Jhon Kural</p>
                                        </div>
                                    </th>
                                    <td>Monte Carlo</td>
                                    <td>11/03/2020</td>
                                    <td>MDC65454</td>
                                    <td>
                                        <div class="amoutn_action d-flex align-items-center">
                                            $29,192
                                            <div class="dropdown ms-4">
                                                <a class=" dropdown-toggle hide_pils" href="#" role="button"
                                                    id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right"
                                                    aria-labelledby="dropdownMenuLink">
                                                    <a class="dropdown-item" href="#">View</a>
                                                    <a class="dropdown-item" href="#">Edit</a>
                                                    <a class="dropdown-item" href="#">Delete</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        <div class="patient_thumb d-flex align-items-center">
                                            <div class="student_list_img mr_20">
                                                <img src="img/patient/4.png" alt="" srcset="">
                                            </div>
                                            <p>Jhon Kural</p>
                                        </div>
                                    </th>
                                    <td>Monte Carlo</td>
                                    <td>11/03/2020</td>
                                    <td>MDC65454</td>
                                    <td>
                                        <div class="amoutn_action d-flex align-items-center">
                                            $29,192
                                            <div class="dropdown ms-4">
                                                <a class=" dropdown-toggle hide_pils" href="#" role="button"
                                                    id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right"
                                                    aria-labelledby="dropdownMenuLink">
                                                    <a class="dropdown-item" href="#">View</a>
                                                    <a class="dropdown-item" href="#">Edit</a>
                                                    <a class="dropdown-item" href="#">Delete</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        <div class="patient_thumb d-flex align-items-center">
                                            <div class="student_list_img mr_20">
                                                <img src="img/patient/5.png" alt="" srcset="">
                                            </div>
                                            <p>Jhon Kural</p>
                                        </div>
                                    </th>
                                    <td>Monte Carlo</td>
                                    <td>11/03/2020</td>
                                    <td>MDC65454</td>
                                    <td>
                                        <div class="amoutn_action d-flex align-items-center">
                                            $29,192
                                            <div class="dropdown ms-4">
                                                <a class=" dropdown-toggle hide_pils" href="#" role="button"
                                                    id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right"
                                                    aria-labelledby="dropdownMenuLink">
                                                    <a class="dropdown-item" href="#">View</a>
                                                    <a class="dropdown-item" href="#">Edit</a>
                                                    <a class="dropdown-item" href="#">Delete</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        <div class="patient_thumb d-flex align-items-center">
                                            <div class="student_list_img mr_20">
                                                <img src="img/patient/6.png" alt="" srcset="">
                                            </div>
                                            <p>Jhon Kural</p>
                                        </div>
                                    </th>
                                    <td>Monte Carlo</td>
                                    <td>11/03/2020</td>
                                    <td>MDC65454</td>
                                    <td>
                                        <div class="amoutn_action d-flex align-items-center">
                                            $29,192
                                            <div class="dropdown ms-4">
                                                <a class=" dropdown-toggle hide_pils" href="#" role="button"
                                                    id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right"
                                                    aria-labelledby="dropdownMenuLink">
                                                    <a class="dropdown-item" href="#">View</a>
                                                    <a class="dropdown-item" href="#">Edit</a>
                                                    <a class="dropdown-item" href="#">Delete</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        <div class="patient_thumb d-flex align-items-center">
                                            <div class="student_list_img mr_20">
                                                <img src="img/patient/6.png" alt="" srcset="">
                                            </div>
                                            <p>Jhon Kural</p>
                                        </div>
                                    </th>
                                    <td>Monte Carlo</td>
                                    <td>11/03/2020</td>
                                    <td>MDC65454</td>
                                    <td>
                                        <div class="amoutn_action d-flex align-items-center">
                                            $29,192
                                            <div class="dropdown ms-4">
                                                <a class=" dropdown-toggle hide_pils" href="#" role="button"
                                                    id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right"
                                                    aria-labelledby="dropdownMenuLink">
                                                    <a class="dropdown-item" href="#">View</a>
                                                    <a class="dropdown-item" href="#">Edit</a>
                                                    <a class="dropdown-item" href="#">Delete</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-xl-5 ">
                <div class="white_box card_height_50 mb_30">
                    <div class="box_header border_bottom_1px  ">
                        <div class="main-title">
                            <h3 class="mb_25">Total Recover Report</h3>
                        </div>
                    </div>
                    <div id="chart-7"></div>
                    <div class="row text-center mt-3">
                        <div class="col-sm-6">
                            <h6 class="heading_6 d-block">Last Month</h6>
                            <p class="m-0">358</p>
                        </div>
                        <div class="col-sm-6">
                            <h6 class="heading_6 d-block">Current Month</h6>
                            <p class="m-0">194</p>
                        </div>
                    </div>
                </div>
                <div class="white_box card_height_50 mb_30">
                    <div class="box_header border_bottom_1px  ">
                        <div class="main-title">
                            <h3 class="mb_25">Total Death Report</h3>
                        </div>
                    </div>
                    <div id="chart-8"></div>
                    <div class="row text-center mt-3">
                        <div class="col-sm-6">
                            <h6 class="heading_6 d-block">Last Month</h6>
                            <p class="m-0">358</p>
                        </div>
                        <div class="col-sm-6">
                            <h6 class="heading_6 d-block">Current Month</h6>
                            <p class="m-0">194</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-xl-12">
                <div class="white_box mb_30">
                    <div class="box_header border_bottom_1px  ">
                        <div class="main-title">
                            <h3 class="mb_25"> Sales/Purchases Report (June 2022)</h3>
                        </div>
                    </div>
                    <div id="apex_2"></div>
                </div>
            </div>

            <div class="col-xl-6">
                <div class="white_box card_height_100">
                    <div class="box_header border_bottom_1px  ">
                        <div class="main-title">
                            <h3 class="mb_25">Recent Activity</h3>
                        </div>
                    </div>
                    <div class="Activity_timeline">
                        <ul>
                            <li>
                                <div class="activity_bell"></div>
                                <div class="activity_wrap">
                                    <h6>5 mint</h6>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque
                                        scelerisque
                                    </p>
                                </div>
                            </li>
                            <li>
                                <div class="activity_bell"></div>
                                <div class="activity_wrap">
                                    <h6>5 min ago</h6>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque
                                        scelerisque
                                    </p>
                                </div>
                            </li>
                            <li>
                                <div class="activity_bell"></div>
                                <div class="activity_wrap">
                                    <h6>5 min ago</h6>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque
                                        scelerisque
                                    </p>
                                </div>
                            </li>
                            <li>
                                <div class="activity_bell"></div>
                                <div class="activity_wrap">
                                    <h6>5 min ago</h6>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque
                                        scelerisque
                                    </p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="white_box mb_30">
                    <div class="box_header border_bottom_1px  ">
                        <div class="main-title">
                            <h3 class="mb_25">Recent Activity</h3>
                        </div>
                    </div>
                    <div class="activity_progressbar">
                        <div class="single_progressbar">
                            <h6>Supervisor</h6>
                            <div id="bar1" class="barfiller">
                                <div class="tipWrap">
                                    <span class="tip"></span>
                                </div>
                                <span class="fill" data-percentage="{{ count($records) }}"></span>
                            </div>
                        </div>
                        <div class="single_progressbar">
                            <h6> Accountant</h6>
                            <div id="bar2" class="barfiller">
                                <div class="tipWrap">
                                    <span class="tip"></span>
                                </div>
                                <span class="fill" data-percentage="{{ count($account) }}"></span>
                            </div>
                        </div>
                        {{-- <div class="single_progressbar">
                        <h6></h6>
                        <div id="bar3" class="barfiller">
                            <div class="tipWrap">
                                <span class="tip"></span>
                            </div>
                            <span class="fill" data-percentage="55"></span>
                        </div>
                    </div> --}}
                        <div class="single_progressbar">
                            <h6>Delete</h6>
                            <div id="bar4" class="barfiller">
                                <div class="tipWrap">
                                    <span class="tip"></span>
                                </div>
                                <span class="fill" data-percentage="{{ count($sal) }}"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
