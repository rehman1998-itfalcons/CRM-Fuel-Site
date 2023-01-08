<?php 
	use Carbon\Carbon;
	use App\Record;
?>
<div class="row layout-top-spacing" id="cancel-row">
	<div class="container-fluid p-0">
		<div class="row justify-content-center">
			<div class="white_box mb_20">
	<div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
		<div class="widget-content widget-content-area br-6">
			<div class="widget-header">
				<div class="row">
					<div class="col-md-5">
						<h4 class="card-title">Companies Overall Stats</h4>
					</div>
					<div class="col-md-7">
						<form action="{{ route('home') }}" method="Get">
							<div class="row" id="custom-range" style="">
								<div class="col-md-5">
									<input type="text" id="from_date" name="statsfrom" style="width: 100%;" class="form-control-custom flatpickr flatpickr-input" placeholder="Select From Date">
								</div>
								<div class="col-md-5">
									<input type="text" id="to_date" name="statsto" style="width: 100%;" class="form-control-custom flatpickr flatpickr-input" placeholder="Select To Date">
								</div>
								<div class="col-md-2">
									<button type="button" id="submit-btn" class="btn btn-sm btn-success" style="background-color: #2e8e3f; height: 37px;">
										Search
									</button>
									<p style="color: red;" class="mt-2"><strong id="error_ish" style="display: none;">Please select dates</strong></p>
								</div>
							</div>
					    </div>
					</form>
				</div>
			</div>
			<br>
			<div class="table-responsive mb-4 mt-4">
				<div id="zero-config_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
					<table id="zero-config21" class="table table-hover dataTable" style="width:100%" role="grid" aria-describedby="zero-config_info">
						<thead>
						<tr role="row">
							<th class="sorting">Main Company</th>
							<th class="sorting">Total Sales</th>
							<th class="sorting">Paid Amount</th>
							<th class="sorting">Balance</th>
							<th class="sorting">Total</th>
							<th class="sorting">View Details</th>
						</tr>
						</thead>
                      	<?php
                      		$companies_ishf = \DB::table('companies')->select('id','name')->orderBy('name','asc')->get();
							$sf_from = (isset($_GET['statsfrom'])) ? $_GET['statsfrom'] : '';
							$st = (isset($_GET['statsto'])) ? $_GET['statsto'] : '';
						?>
						@if ($sf_from != '' && $st != '')
							<?php
							$getfrom = $_GET['statsfrom'];
							$getto = $_GET['statsto'];
							$gfrom = Carbon::parse($getfrom)->format('Y-m-d');
							$gto = Carbon::parse($getto)->format('Y-m-d');
							$companies_ish = App\Company::whereBetween('created_at',array($gfrom,$gto))->select('id','name')->orderBy('name','asc')->get();

							?>
							<tbody id="report_result">
							@foreach($companies_ish as $company)
								<tr>
									@php
										$paid = Record::where('status',1)->where('deleted_status',0)->where('company_id',$company->id)->sum('paid_amount');
                                        $total = Record::where('status',1)->where('deleted_status',0)->where('company_id',$company->id)->sum('total_amount');
                                        $balance = $total - $paid;
									@endphp
									<td>{{ $company->name }}</td>
									<td>{{ Record::where('company_id',$company->id)->count() }}</td>
									<td>${{ number_format($paid,2) }}</td>
									<td>${{ number_format($balance,2) }}</td>
									<td>${{ number_format($total,2) }}</td>
									<td>
										<a href="{{ url('main-company-report',$company->id) }}" target="_blank" class="badge badge-success" style="background-color: #2e8e3f;">
											View
										</a>
									</td>
								</tr>
							@endforeach
							</tbody>
						@else
							<tbody id="report_result">
							@foreach($companies_ishf as $company)
								<tr>
									@php
										$paid = Record::where('status',1)->where('deleted_status',0)->where('company_id',$company->id)->sum('paid_amount');
                                        $total = Record::where('status',1)->where('deleted_status',0)->where('company_id',$company->id)->sum('total_amount');
                                        $balance = $total - $paid;
									@endphp
									<td>{{ $company->name }}</td>
									<td>{{ Record::where('company_id',$company->id)->count() }}</td>
									<td>${{ number_format($paid,2) }}</td>
									<td>${{ number_format($balance,2) }}</td>
									<td>${{ number_format($total,2) }}</td>
									<td>
										<a href="{{ url('main-company-report',$company->id) }}" target="_blank" class="badge badge-success" style="background-color: #2e8e3f;">
											View
										</a>
									</td>
								</tr>
							@endforeach
							</tbody>
						@endif
					</table>
				</div>
			</div>
		</div>
	</div>
	</div>
	</div>
	</div>
</div>
