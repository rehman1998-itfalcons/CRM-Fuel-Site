<?php use Carbon\Carbon; ?>
	<!-- Sales/Purchases Report -->
    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-card-four">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h5 class="">
                                Sales/Purchases Report ({{ Carbon::now()->format('F Y') }})
                                <div style="display: flex; float:right;">
                                    <select class="form-control-custom" onchange="sLine()" id="s-line-value">
                                        <option value="Weekly"  >Weekly</option>
                                        <option value="Monthly" selected >Monthly</option>
                                    </select>
                                </div>
                            </h5>
                            {{-- <p id="s-line-type">Monthly</p> --}}
                            {{-- <p id="apex_3">Monthly</p> --}}
                        </div>
                    </div>
                </div>
                <div class="widget-content">
                    <div id="s-line" class="" style="min-height: 365px;">

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-6 col-sm-6">
            <div class="QA_section">
                <div class="white_box_tittle list_header">
                    <h3> Coming 7 days Overdue Invoices</h3>
                    <div class="box_right d-flex lms_block">
                        <div class="serach_field_2">
                            <div class="search_inner">

                            </div>
                        </div>

                    </div>
                </div>
                <div class="QA_table mb_30">

                            <table class="table lms_table_active  data-table" style="border:none;" >
                                <thead>
                                    <tr role="row">
                                        <th style="color:white !important;">Date</th>
                                        <th style="color:white !important;">Overdue Invoices</th>
                                        <th style="color:white !important;">Details</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    <tr>
                                        <td id="date7"></td>
                                        <td id="d7_total"></td>
                                        <td>
                                            <a href="{{ url('/overdue-sales-report/'.encrypt(implode('::',$array_date_7)).'/'.$date7) }}" target="_blank" class="btn btn-primary" style="color:white !important;">View</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td id="date6"></td>
                                        <td id="d6_total"></td>
                                        <td>
                                            <a href="{{ url('/overdue-sales-report/'.encrypt(implode('::',$array_date_6)).'/'.$date6) }}" target="_blank" class="btn btn-primary" style="color:white !important;">View</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td id="date5"></td>
                                        <td id="d5_total"></td>
                                        <td>
                                            <a href="{{ url('/overdue-sales-report/'.encrypt(implode('::',$array_date_5)).'/'.$date5) }}" target="_blank" class="btn btn-primary"style="color:white !important;">View</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td id="date4"></td>
                                        <td id="d4_total"></td>
                                        <td>
                                            <a href="{{ url('/overdue-sales-report/'.encrypt(implode('::',$array_date_4)).'/'.$date4) }}" target="_blank" class="btn btn-primary"style="color:white !important;">View</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td id="date3"> </td>
                                        <td id="d3_total"></td>
                                        <td>
                                            <a href="{{ url('/overdue-sales-report/'.encrypt(implode('::',$array_date_3)).'/'.$date3) }}" target="_blank" class="btn btn-primary"style="color:white !important;">View</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td id="date2"></td>
                                        <td id="d2_total"></td>
                                        <td>
                                            <a href="{{ url('/overdue-sales-report/'.encrypt(implode('::',$array_date_2)).'/'.$date2) }}" target="_blank" class="btn btn-primary"style="color:white !important;">View</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td id="date1"></td>
                                        <td id="d1_total"></td>
                                        <td>
                                            <a href="{{ url('/overdue-sales-report/'.encrypt(implode('::',$array_date_1)).'/'.$date1) }}" target="_blank" class="btn btn-primary"style="color:white !important;">View</a>
                                        </td>
                                    </tr>
                                    </tbody>
                            </table>
                        </div>
                    </div>
                </div>








            </div>
        </div>
        {{-- <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-card-four">
                <div class="widget-heading">
                    <div class="">
                        <h5 class="">Coming 7 days Overdue Invoices</h5>
                    </div>
                    <div class="">
                        <div class="table-responsive mb-4 mt-4">
                            <div id="zero-config_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                                <table id="overdue-sales" class="table table-hover dataTable" style="width:100%" role="grid" aria-describedby="zero-config_info">
                                    <thead>
                                    <tr role="row">
                                        <th>Date</th>
                                        <th>Overdue Invoices</th>
                                        <th>Details</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>{{ $date7 }}</td>
                                        <td>{{ $d7_total }}</td>
                                        <td>
                                            <a href="{{ url('/overdue-sales-report/'.encrypt(implode('::',$array_date_7)).'/'.$date7) }}" target="_blank" class="badge badge-success" style="background-color: #2e8e3f;">View</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{ $date6 }}</td>
                                        <td>{{ $d6_total }}</td>
                                        <td>
                                            <a href="{{ url('/overdue-sales-report/'.encrypt(implode('::',$array_date_6)).'/'.$date6) }}" target="_blank" class="badge badge-success" style="background-color: #2e8e3f;">View</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{ $date5 }}</td>
                                        <td>{{ $d5_total }}</td>
                                        <td>
                                            <a href="{{ url('/overdue-sales-report/'.encrypt(implode('::',$array_date_5)).'/'.$date5) }}" target="_blank" class="badge badge-success" style="background-color: #2e8e3f;">View</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{ $date4 }}</td>
                                        <td>{{ $d4_total }}</td>
                                        <td>
                                            <a href="{{ url('/overdue-sales-report/'.encrypt(implode('::',$array_date_4)).'/'.$date4) }}" target="_blank" class="badge badge-success" style="background-color: #2e8e3f;">View</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{ $date3 }}</td>
                                        <td>{{ $d3_total }}</td>
                                        <td>
                                            <a href="{{ url('/overdue-sales-report/'.encrypt(implode('::',$array_date_3)).'/'.$date3) }}" target="_blank" class="badge badge-success" style="background-color: #2e8e3f;">View</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{ $date2 }}</td>
                                        <td>{{ $d2_total }}</td>
                                        <td>
                                            <a href="{{ url('/overdue-sales-report/'.encrypt(implode('::',$array_date_2)).'/'.$date2) }}" target="_blank" class="badge badge-success" style="background-color: #2e8e3f;">View</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{ $date1 }}</td>
                                        <td>{{ $d1_total }}</td>
                                        <td>
                                            <a href="{{ url('/overdue-sales-report/'.encrypt(implode('::',$array_date_1)).'/'.$date1) }}" target="_blank" class="badge badge-success" style="background-color: #2e8e3f;">View</a>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                </div>
            </div>
        </div> --}}
    </div>
