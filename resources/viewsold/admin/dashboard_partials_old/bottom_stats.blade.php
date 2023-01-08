<?php

	use App\Record;
?>
	<div class="row">
        <div class="col-xl-3 col-lg-3 col-sm-12  layout-spacing">
            <div class="widget widget-card-four">
                <div class="widget-content">
                    <div class="w-content">
                        <div class="w-info">
                            <h6 class="value">{{ $sales->where('status',1)->where('paid_status',0)->where('deleted_status',0)->count() }}</h6>
                            <p class="">UNPAID INVOICES</p>
                        </div>
                        <a href="{{ url('/dashboard-unpaid-invoices')}}<?php echo '?category='.$category_url; ?> " target="_blank">
                            <div class="w-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-archive">
                                    <polyline points="21 8 21 21 3 21 3 8"></polyline>
                                    <rect x="1" y="3" width="22" height="5"></rect>
                                    <line x1="10" y1="12" x2="14" y2="12"></line>
                                </svg>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-sm-12  layout-spacing">
            <div class="widget widget-card-four">
                <div class="widget-content">
                    <div class="w-content">
                        <div class="w-info">
                            <h6 class="value">${{ number_format($unpaid_balance,2) }}</h6>
                            <p class="">UNPAID BALANCE</p>
                        </div>
                        <div class="">
                            <div class="w-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-dollar-sign">
                                    <line x1="12" y1="1" x2="12" y2="23"></line>
                                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-sm-12  layout-spacing">
            <div class="widget widget-card-four">
                <div class="widget-content">
                    <div class="w-content">
                        <div class="w-info">
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
                            <h6 class="value">{{ $getoverdues }}</h6>
                            <p class="">OVERDUE INVOICES</p>
                        </div>
                        <a href="{{ url('/all-overdue-invoices') }}?@if(isset($_GET['category']))&category={{ $_GET['category'] }}@endif" target="_blank">
                            <div class="w-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-archive">
                                    <polyline points="21 8 21 21 3 21 3 8"></polyline>
                                    <rect x="1" y="3" width="22" height="5"></rect>
                                    <line x1="10" y1="12" x2="14" y2="12"></line>
                                </svg>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-sm-12  layout-spacing">
            <div class="widget widget-card-four">
                <div class="widget-content">
                    <div class="w-content">
                        <div class="w-info">
                            <h6 class="value">${{ number_format($overdue_balance,2) }}</h6>
                            <p class="">OVERDUE BALANCE</p>
                        </div>
                        <div class="">
                            <div class="w-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-dollar-sign">
                                    <line x1="12" y1="1" x2="12" y2="23"></line>
                                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>