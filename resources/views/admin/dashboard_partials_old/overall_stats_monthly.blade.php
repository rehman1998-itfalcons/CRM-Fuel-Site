<?php use Carbon\Carbon; ?>
	<div class="row layout-spacing">
        <div class="col-md-12">
            <div class="widget widget-card-four">
                <div class="widget-heading">
                    <div class="">
                        <h5 class="">
                            Sales/Purchases Monthly Report
                            <div style="display: flex; float:right;">
                                <select class="form-control-custom" onchange="monthlyReport()" id="monthly-report-value">
                                    <option value="0" @if(isset($_GET['month'])) {{ ($_GET['month'] == 0) ? 'selected' : '' }} @endif>Over All</option>
                                    <option value="1" @if(isset($_GET['month'])) {{ ($_GET['month'] == 1) ? 'selected' : '' }} @endif>January</option>
                                    <option value="2" @if(isset($_GET['month'])) {{ ($_GET['month'] == 2) ? 'selected' : '' }} @endif>Febuary</option>
                                    <option value="3" @if(isset($_GET['month'])) {{ ($_GET['month'] == 3) ? 'selected' : '' }} @endif>March</option>
                                    <option value="4" @if(isset($_GET['month'])) {{ ($_GET['month'] == 4) ? 'selected' : '' }} @endif>April</option>
                                    <option value="5" @if(isset($_GET['month'])) {{ ($_GET['month'] == 5) ? 'selected' : '' }} @endif>May</option>
                                    <option value="6" @if(isset($_GET['month'])) {{ ($_GET['month'] == 6) ? 'selected' : '' }} @endif>June</option>
                                    <option value="7" @if(isset($_GET['month'])) {{ ($_GET['month'] == 7) ? 'selected' : '' }} @endif>July</option>
                                    <option value="8" @if(isset($_GET['month'])) {{ ($_GET['month'] == 8) ? 'selected' : '' }} @endif>August</option>
                                    <option value="9" @if(isset($_GET['month'])) {{ ($_GET['month'] == 9) ? 'selected' : '' }} @endif>September</option>
                                    <option value="10" @if(isset($_GET['month'])) {{ ($_GET['month'] == 10) ? 'selected' : '' }} @endif>October</option>
                                    <option value="11" @if(isset($_GET['month'])) {{ ($_GET['month'] == 11) ? 'selected' : '' }} @endif>November</option>
                                    <option value="12" @if(isset($_GET['month'])) {{ ($_GET['month'] == 12) ? 'selected' : '' }} @endif>December</option>
                                </select>
                            </div>
                        </h5>
                        <p><span id="monthly-report-type"></span>{{ Carbon::now()->year }}</p>
                    </div>
                </div>
                <div class="widget-content" style="position: relative;">
                    <div id="monthly-report" style="min-height: 365px;">
                    </div>
                </div>
            </div>
        </div>
    </div>