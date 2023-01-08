<?php use Carbon\Carbon; use App\Record; use App\PurchaseRecord; use App\Expense; ?>
<div class="row">
	<div class="col-xl-4 col-lg-4 col-sm-12  layout-spacing">
		<div class="widget widget-card-four">
			<div class="widget-content">
				<div class="w-content">
					<div class="w-info">
						<h6 class="value">$ {{ number_format($gross,2) }}</h6>
						<p class="">GROSS EARNINGS</p>
					</div>
					<div class="">
						<div class="w-icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
								 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
								 stroke-linejoin="round" class="feather feather-bar-chart-2">
								<line x1="18" y1="20" x2="18" y2="10"></line>
								<line x1="12" y1="20" x2="12" y2="4"></line>
								<line x1="6" y1="20" x2="6" y2="14"></line>
							</svg>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xl-4 col-lg-4 col-sm-12  layout-spacing">
		<div class="widget widget-card-four">
			<div class="widget-content">
				<div class="w-content">
					<div class="w-info">
						<h6 class="value">$ {{ number_format($tax,2) }}</h6>
						<p class="">TAX WITHHELD</p>
					</div>
					<div class="">
						<div class="w-icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
								 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
								 stroke-linejoin="round" class="feather feather-trending-down">
								<polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
								<polyline points="17 18 23 18 23 12"></polyline>
							</svg>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xl-4 col-lg-4 col-sm-12  layout-spacing">
		<div class="widget widget-card-four">
			<div class="widget-content">
				<div class="w-content">
					<div class="w-info">
						<h6 class="value">$ {{ number_format($net,2) }}</h6>
						<p class="">NET EARNINGS</p>
					</div>
					<div class="">
						<div class="w-icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
								 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
								 stroke-linejoin="round" class="feather feather-trending-up">
								<polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
								<polyline points="17 6 23 6 23 12"></polyline>
							</svg>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
		<div class="widget-content widget-content-area br-6">
			<div class="widget-header">
				<div class="row">
					<div class="col-md-5">
						<h4 class="card-title">Income Statistics (Last Week)</h4></div>
					<div class="col-md-7">
						<form action="" method="GET" id="income_statiscis_form">
							<div id="custom-range" class="row">
								<div class="col-md-5">
									<input type="text" id="from_date_raf" name="income_from" style="width: 100%;" class="form-control-custom flatpickr flatpickr-input" placeholder="Select From Date">
								</div>
								<div class="col-md-5">
									<input type="text" id="to_date_raf" name="income_to" style="width: 100%;" class="form-control-custom flatpickr flatpickr-input" placeholder="Select To Date">
								</div>
								<div class="col-md-2">
									<button id="submit-btn_raf" class="btn btn-sm btn-success" style="background-color: #2e8e3f; height: 37px;">
										Search
									</button>
									<p style="color: red;" class="mt-2"><strong id="error_raf" style="display: none;">Please select dates</strong></p>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			<br>


			<div class="table-responsive mb-4 mt-4">
				<div id="zero-config_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
					<table id="zero_config_raf" class="table table-hover dataTable" style="width:100%" role="grid" aria-describedby="zero-config_info">
						<thead>
						<tr role="row">
							<th>DATE</th>
							<th>SALES</th>
							<th>PURCHASES</th>
							<th>Expenses</th>
							<th>TAX WITHHELD</th>
							<th>NET EARNINGS</th>
						</tr>
						</thead>
						<?php
						$i_from = (isset($_GET['income_from'])) ? $_GET['income_from'] : '';
						$t_from = (isset($_GET['income_to'])) ? $_GET['income_to'] : '';
						?>
						@if($i_from != '' && $t_from != '')
							@php
								$from = Carbon::parse($_GET['income_from'])->format('Y-m-d');
                                $to = Carbon::parse($_GET['income_to'])->format('Y-m-d');
                              $expenses1 = DB::table('expenses')->select('id','amount')->selectRaw('SUBSTRING(datetime, 1, 10) as datetime')->whereBetween('datetime',[new Carbon($from),new Carbon($to)])->orderBy('datetime','asc')->get()->groupBy('datetime');
                            
                            if(isset($_GET['category'])) {
                            $sales1 = Record::select('id','company_id','sub_company_id','status','paid_status','gst','total_amount','paid_amount','total_without_gst','deleted_status')->selectRaw('SUBSTRING(datetime, 1, 10) as datetime')->where('category_id',$_GET['category'])->whereBetween('datetime', [$from, $to])->orderBy('datetime','asc')->where('status',1)->where('deleted_status',0)
                        ->get()->groupBy('datetime');
  
                        $purchases2 = PurchaseRecord::
                        select('id','category_id','supplier_company_id','invoice_number','total_amount','status','deleted_status')->selectRaw('SUBSTRING(datetime, 1, 10) as datetime')->where('category_id',$_GET['category'])->whereBetween('datetime', [$from, $to])->orderBy('datetime','asc')->where('status',1)->where('deleted_status',0)
                        ->get()->groupBy('datetime');
                       
                             }
                            else{
                $sales1 = Record::
               select('id','company_id','sub_company_id','status','paid_status','gst','total_amount','paid_amount','total_without_gst','deleted_status','gst')->selectRaw('SUBSTRING(datetime, 1, 10) as datetime')->whereBetween('datetime', [$from, $to])->where('status',1)->where('deleted_status',0)->orderBy('datetime','asc')->get()->groupBy('datetime');
  
                $purchases2 = PurchaseRecord::
                select('id','category_id','supplier_company_id','invoice_number','total_amount','status','deleted_status')->selectRaw('SUBSTRING(datetime, 1, 10) as datetime')->whereBetween('datetime', [$from, $to])->where('status',1)->where('deleted_status',0)->orderBy('datetime','asc')->get()->groupBy('datetime'); 
                $sales1 = json_decode($sales1);
                $purchases2 = json_decode($purchases2);
                $expenses1 = json_decode($expenses1);
                            }
							@endphp
							<tbody>
							<?php
							$from_invoice_date = \Carbon\Carbon::parse($_GET['income_from'])->format('Y-m-d');
							$to_invoice_date = \Carbon\Carbon::parse($_GET['income_to'])->format('Y-m-d');
							$diff = strtotime($to_invoice_date) - strtotime($from_invoice_date);
							$count = $diff / (60 * 60 * 24);
							$key = $from_invoice_date;
							for($i = 1;$i <= $count ;$i++)  {
							$sale_records = (isset($sales1->$key)) ? $sales1->$key : '';
							$purchase_records = (isset($purchases2->$key)) ? $purchases2->$key : '';
							$expense_records = isset($expenses1->$key) ? $expenses1->$key : '';
							$sale_total = 0;
							$gst_total = 0;
							$purchase_total = 0;
							$expense_total = 0;
							?>
							@if($sale_records || $purchase_records || $expense_records)
								<tr>
									<td>
										{{ Carbon::parse($key)->format('d-m-Y') }}

									</td>
									<td>
										@if($sale_records)
											<?php
											foreach($sale_records as $key => $value) {
												$sale_total = $sale_total + $value->total_amount;
												$gst_total = $gst_total + $value->gst;
											}
											?>
											${{number_format($sale_total, 2)}}
										@else
											${{number_format($sale_total, 2)}}
										@endif
									</td>

									<td>
										@if($purchase_records)
											<?php
											foreach($purchase_records as $key => $value) {
												$purchase_total = $purchase_total + $value->total_amount;
											}
											?>
											${{number_format($purchase_total, 2)}}
										@else
											${{number_format($purchase_total, 2)}}
										@endif
									</td>
									<td>
										@if($expense_records)
											<?php
											foreach($expense_records as $key => $value) {
												$expense_total = $expense_total + $value->amount;
											}
											?>
											${{number_format($expense_total, 2)}}
										@else
											${{number_format($expense_total, 2)}}
										@endif
									</td>
									@php
										$net_raf = $sale_total - ($purchase_total + $expense_total + $gst_total);
									@endphp
									<td>
										${{ number_format($gst_total,2) }}
									</td>
									<td>${{ number_format($net_raf,2) }}</td>
								</tr>
							@endif
							<?php
							$key = Carbon::parse($from_invoice_date)->adddays($i)->format('Y-m-d');
							}  ?>
							</tbody>

						@else
							<tbody>
							<?php
							$total_amount = $sales->whereBetween('datetime',[$last1.' 00:00:00', $last1.' 24:00:00'])->where('status',1)->where('deleted_status',0)->sum('total_amount');
							$total_purchases = $purchases1->whereBetween('datetime',[$last1.' 00:00:00', $last1.' 24:00:00'])->where('status',1)->where('deleted_status',0)->sum('total_amount');
							$total_expenses = $expenses->whereBetween('datetime',[new Carbon($last1.' 00:00:00'), new Carbon($last1.' 24:00:00')])->sum('amount');
							$total_tax = $sales->whereBetween('datetime',[$last1.' 00:00:00', $last1.' 24:00:00'])->where('status',1)->where('deleted_status',0)->sum('gst');
							$net_earning = $total_amount - ($total_purchases + $total_expenses + $total_tax);
							?>
							<tr>
								<td><?=$last_day_1;?></td>
								<td>
									${{ number_format($total_amount,2) }}
								</td>
								<td>
									${{ number_format($total_purchases,2) }}
								</td>
								<td>
									${{ number_format($total_expenses,2) }}
								</td>
								<td>
									${{ number_format($total_tax,2) }}
								</td>
								<td>${{ number_format($net_earning,2) }}</td>
							</tr>
							<?php
							$total_amount = $sales->whereBetween('datetime',[$last2.' 00:00:00', $last2.' 24:00:00'])->where('status',1)->where('deleted_status',0)->sum('total_amount');
							$total_purchases = $purchases1->whereBetween('datetime',[$last2.' 00:00:00', $last2.' 24:00:00'])->where('status',1)->where('deleted_status',0)->sum('total_amount');
							$total_expenses = $expenses->whereBetween('datetime',[new Carbon($last2.' 00:00:00'), new Carbon($last2.' 24:00:00')])->sum('amount');
							$total_tax = $sales->whereBetween('datetime',[$last2.' 00:00:00', $last2.' 24:00:00'])->where('status',1)->where('deleted_status',0)->sum('gst');
							$net_earning = $total_amount - ($total_purchases + $total_expenses + $total_tax);
							?>
							<tr>
								<td><?=$last_day_2;?></td>
								<td>
									${{ number_format($total_amount,2) }}
								</td>
								<td>
									${{ number_format($total_purchases,2) }}
								</td>
								<td>
									${{ number_format($total_expenses,2) }}
								</td>
								<td>
									${{ number_format($total_tax,2) }}
								</td>
								<td>${{ number_format($net_earning,2) }}</td>
							</tr>
							<?php
							$total_amount = $sales->whereBetween('datetime',[$last3.' 00:00:00', $last3.' 24:00:00'])->where('status',1)->where('deleted_status',0)->sum('total_amount');
							$total_purchases = $purchases1->whereBetween('datetime',[$last3.' 00:00:00', $last3.' 24:00:00'])->where('status',1)->where('deleted_status',0)->sum('total_amount');
							$total_expenses = $expenses->whereBetween('datetime',[new Carbon($last3.' 00:00:00'), new Carbon($last3.' 24:00:00')])->sum('amount');
							$total_tax = $sales->whereBetween('datetime',[$last3.' 00:00:00', $last3.' 24:00:00'])->where('status',1)->where('deleted_status',0)->sum('gst');
							$net_earning = $total_amount - ($total_purchases + $total_expenses + $total_tax);
							?>
							<tr>
								<td><?=$last_day_3;?></td>
								<td>
									${{ number_format($total_amount,2) }}
								</td>
								<td>
									${{ number_format($total_purchases,2) }}
								</td>
								<td>
									${{ number_format($total_expenses,2) }}
								</td>
								<td>
									${{ number_format($total_tax,2) }}
								</td>
								<td>${{ number_format($net_earning,2) }}</td>
							</tr>
							<?php
							$total_amount = $sales->whereBetween('datetime',[$last4.' 00:00:00', $last4.' 24:00:00'])->where('status',1)->where('deleted_status',0)->sum('total_amount');
							$total_purchases = $purchases1->whereBetween('datetime',[$last4.' 00:00:00', $last4.' 24:00:00'])->where('status',1)->where('deleted_status',0)->sum('total_amount');
							$total_expenses = $expenses->whereBetween('datetime',[new Carbon($last4.' 00:00:00'), new Carbon($last4.' 24:00:00')])->sum('amount');
							$total_tax = $sales->whereBetween('datetime',[$last4.' 00:00:00', $last4.' 24:00:00'])->where('status',1)->where('deleted_status',0)->sum('gst');
							$net_earning = $total_amount - ($total_purchases + $total_expenses + $total_tax);
							?>
							<tr>
								<td><?=$last_day_4;?></td>
								<td>
									${{ number_format($total_amount,2) }}
								</td>
								<td>
									${{ number_format($total_purchases,2) }}
								</td>
								<td>
									${{ number_format($total_expenses,2) }}
								</td>
								<td>
									${{ number_format($total_tax,2) }}
								</td>
								<td>${{ number_format($net_earning,2) }}</td>
							</tr>
							<?php
							$total_amount = $sales->whereBetween('datetime',[$last5.' 00:00:00', $last5.' 24:00:00'])->where('status',1)->where('deleted_status',0)->sum('total_amount');
							$total_purchases = $purchases1->whereBetween('datetime',[$last5.' 00:00:00', $last5.' 24:00:00'])->where('status',1)->where('deleted_status',0)->sum('total_amount');
							$total_expenses = $expenses->whereBetween('datetime',[new Carbon($last5.' 00:00:00'), new Carbon($last5.' 24:00:00')])->sum('amount');
							$total_tax = $sales->whereBetween('datetime',[$last5.' 00:00:00', $last5.' 24:00:00'])->where('status',1)->where('deleted_status',0)->sum('gst');
							$net_earning = $total_amount - ($total_purchases + $total_expenses + $total_tax);
							?>
							<tr>
								<td><?=$last_day_5;?></td>
								<td>
									${{ number_format($total_amount,2) }}
								</td>
								<td>
									${{ number_format($total_purchases,2) }}
								</td>
								<td>
									${{ number_format($total_expenses,2) }}
								</td>
								<td>
									${{ number_format($total_tax,2) }}
								</td>
								<td>${{ number_format($net_earning,2) }}</td>
							</tr>
							<?php
							$total_amount = $sales->whereBetween('datetime',[$last6.' 00:00:00', $last6.' 24:00:00'])->where('status',1)->where('deleted_status',0)->sum('total_amount');
							$total_purchases = $purchases1->whereBetween('datetime',[$last6.' 00:00:00', $last6.' 24:00:00'])->where('status',1)->where('deleted_status',0)->sum('total_amount');
							$total_expenses = $expenses->whereBetween('datetime',[new Carbon($last6.' 00:00:00'), new Carbon($last6.' 24:00:00')])->sum('amount');
							$total_tax = $sales->whereBetween('datetime',[$last6.' 00:00:00', $last6.' 24:00:00'])->where('status',1)->where('deleted_status',0)->sum('gst');
							$net_earning = $total_amount - ($total_purchases + $total_expenses + $total_tax);
							?>
							<tr>
								<td><?=$last_day_6;?></td>
								<td>
									${{ number_format($total_amount,2) }}
								</td>
								<td>
									${{ number_format($total_purchases,2) }}
								</td>
								<td>
									${{ number_format($total_expenses,2) }}
								</td>
								<td>
									${{ number_format($total_tax,2) }}
								</td>
								<td>${{ number_format($net_earning,2) }}</td>
							</tr>
							<?php
							$total_amount = $sales->whereBetween('datetime',[$last7.' 00:00:00', $last7.' 24:00:00'])->where('status',1)->where('deleted_status',0)->sum('total_amount');
							$total_purchases = $purchases1->whereBetween('datetime',[new Carbon($last7.' 00:00:00'), new Carbon($last7.' 24:00:00')])->where('status',1)->where('deleted_status',0)->sum('total_amount');
							$total_expenses = $expenses->whereBetween('datetime',[new Carbon($last7.' 00:00:00'), new Carbon($last7.' 24:00:00')])->sum('amount');
							$total_tax = $sales->whereBetween('datetime',[$last7.' 00:00:00', $last7.' 24:00:00'])->where('status',1)->where('deleted_status',0)->sum('gst');
							$net_earning = $total_amount - ($total_purchases + $total_expenses + $total_tax);
							?>
							<tr>
								<td><?=$last_day_7;?></td>
								<td>
									${{ number_format($total_amount,2) }}
								</td>
								<td>
									${{ number_format($total_purchases,2) }}
								</td>
								<td>
									${{ number_format($total_expenses,2) }}
								</td>
								<td>
									${{ number_format($total_tax,2) }}
								</td>
								<td>${{ number_format($net_earning,2) }}</td>
							</tr>
							</tbody>
						@endif
					</table>
				</div>
			</div>
		</div>
	</div>
</div>