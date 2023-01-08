@extends('layouts.template')
@section('title','Income Statistics Report')
@section('css')

    <link rel="stylesheet" type= "text/css" href="{{ URL::asset('plugins/table/table/datatable/datatables.css') }}">
    <link rel="stylesheet" type= "text/css" href="{{ URL::asset('plugins/table/table/datatable/custom_dt_html5.css') }}">
    <link rel="stylesheet" type= "text/css" href="{{ URL::asset('plugins/table/table/datatable/dt-global_style.css') }}">
	<link rel="stylesheet" type= "text/css" href="{{ URL::asset('pluginsapex/apexcharts.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/dashboard/dash_1.css') }}" />
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
		.widget-card-four .w-info p {
    		color: #444 !important;
        }
        .widget-card-four .w-icon {
            color: #2e8e3f !important;
            background-color: #d2efd7 !important;
        }
        .progress .progress-bar.bg-gradient-secondary {
            background-color: #2e8e3f !important;
            background-image: linear-gradient(to right, #b1d8b8 0%, #2e8e3f 100%) !important;
        }
        .widget-card-four {
            box-shadow: 0 4px 6px 0 rgba(85, 85, 85, 0.0901961), 0 1px 20px 0 rgba(0, 0, 0, 0.08), 0px 1px 11px 0px rgba(0, 0, 0, 0.06);
        }
	</style>

@endsection
@section('contents')

	<div class="row layout-top-spacing" id="cancel-row">
      <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
      	<div class="widget-content widget-content-area br-6">
           <h4>Income Statistics Report</h4>
          <div class="table-responsive mb-4 mt-4">
            <table id="html5-extension" class="table table-hover non-hover" style="width:100%">
              <thead>
                <tr>
                  <th>S.No</th>
                  <th>Date</th>
                  <th>Sales</th>
                  <th>Gross Earnings</th>
                  <th>Tax Withheld</th>
                  <th>Net Earnings</th>
                  <th>Details</th>
                </tr>
              </thead>
              <tbody>
                <?php
					$sno = 1;
                	$sales = json_decode($records,true);
					$count = count($sales);
					$i = 1;
					$total_tax = 0;
					$total_gross = 0;
					$total_net = 0;
					$total_delivereis = 0;

					$whole_tax = 0;
					$whole_gross = 0;
					$whole_net = 0;
					$whole_delivereis = 0;

					$g_dates = [];
					$g_gross = [];
					$g_tax = [];
					$g_net = [];
                ?>
                @foreach ($sales as $key => $sale)
                	<?php
						$date = \Carbon\Carbon::parse($key)->format('d-m-Y');
						foreach ($sale as $k => $v) {
                          	$total_delivereis++;
                          	$total_tax = $total_tax + $v['gst'];
                          	$total_gross = $total_gross + $v['total_amount'];
                          	$total_net = $total_net + $v['total_without_gst'];
                        }

						array_push($g_dates,$key);
						array_push($g_gross,$total_gross);
						array_push($g_tax,$total_tax);
						array_push($g_net,$total_net);

                        $whole_tax = $whole_tax + $total_tax;
                       	$whole_gross = $whole_gross + $total_gross;
                       	$whole_net = $whole_net + $total_net;
						$whole_delivereis = $whole_delivereis + $total_delivereis;
                	?>
                	<tr>
                		<td>{{ $sno++ }}</td>
                		<td>{{ $date }}</td>
                		<td>{{ count($sale) }}</td>
                      	<td>${{ number_format($total_gross,2) }}</td>
                      	<td>${{ number_format($total_tax,2) }}</td>
                      	<td>${{ number_format($total_net,2) }}</td>
                      	<td>
	                      	<a href="{{ route('income.statistics.details',$date) }}" target="_blank" class="btn btn-sm btn-primary">
                          		<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                          	</a>
                      	</td>
                	</tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
	</div>

	<div class="row">
	
 	     <div class="col-xl-3 col-lg-3 col-sm-12  layout-spacing">
        	<div class="widget widget-card-four">
              <div class="widget-content">
                <div class="w-content">
                  <div class="w-info">
                    <h6 class="value">{{ $whole_delivereis }}</h6>
                    <p class="">WHOLE DELIVERIES</p>
                  </div>
                  @php
                  		$percentage = ($whole_delivereis > 100) ? 100 : $whole_delivereis;
                  @endphp
                  <div class="">
                    <div class="w-icon">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-truck"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                    </div>
                  </div>
                </div>
                <div class="progress" data-bs-toggle="tooltip" data-placement="top" data-original-title="{{ $whole_delivereis }}">
                  <div class="progress-bar bg-gradient-secondary" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="57" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>
          </div>
      	</div>
		
 	     <div class="col-xl-3 col-lg-3 col-sm-12  layout-spacing">
        	<div class="widget widget-card-four">
              <div class="widget-content">
                <div class="w-content">
                  <div class="w-info">
                    <h6 class="value">$ {{ number_format($whole_gross,2) }}</h6>
                    <p class="">GROSS EARNINGS</p>
                  </div>
                  <div class="">
                    <div class="w-icon">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bar-chart-2"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                    </div>
                  </div>
                </div>
                <div class="progress" data-bs-toggle="tooltip" data-placement="top" data-original-title="{{ ($whole_gross) ? 100 : 0 }}%">
                  <div class="progress-bar bg-gradient-secondary" role="progressbar" style="width: {{ ($whole_gross) ? 100 : 0 }}%" aria-valuenow="57" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>
          </div>
      	</div>
	
 	     <div class="col-xl-3 col-lg-3 col-sm-12  layout-spacing">
        	<div class="widget widget-card-four">
              <div class="widget-content">
                <div class="w-content">
                  <div class="w-info">
                    <h6 class="value">$ {{ number_format($whole_tax,2) }}</h6>
                    <p class="">TAX WITHHELD</p>
                  </div>
                  @php
                  		$percentage = ($whole_gross) ? number_format((($whole_tax / $whole_gross) * 100),0) : 0;
                  @endphp
                  <div class="">
                    <div class="w-icon">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trending-down"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline><polyline points="17 18 23 18 23 12"></polyline></svg>
                    </div>
                  </div>
                </div>
                <div class="progress" data-bs-toggle="tooltip" data-placement="top" data-original-title="{{ $percentage }}%">
                  <div class="progress-bar bg-gradient-secondary" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="57" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>
          </div>
      	</div>
		
 	     <div class="col-xl-3 col-lg-3 col-sm-12  layout-spacing">
        	<div class="widget widget-card-four">
              <div class="widget-content">
                <div class="w-content">
                  <div class="w-info">
                    <h6 class="value">$ {{ number_format($whole_net,2) }}</h6>
                    <p class="">NET EARNINGS</p>
                  </div>
                  @php
                  		$percentage = ($whole_gross) ? number_format((($whole_net / $whole_gross) * 100),0) : 0;
                  @endphp
                  <div class="">
                    <div class="w-icon">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trending-up"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
                    </div>
                  </div>
                </div>
                <div class="progress" data-bs-toggle="tooltip" data-placement="top" data-original-title="{{ $percentage }}%">
                  <div class="progress-bar bg-gradient-secondary" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="57" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>
          </div>
      	</div>
	</div>

	<div class="row">
    	<div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
			<div class="widget widget-chart-one widget-card-four">
            	<div class="widget-heading">
                  	<h5 class="">Net Income Report</h5>
              	</div>
              	<div class="widget-content">
                	<div class="tabs tab-content">
                    	<div id="content_1" class="tabcontent" style="position: relative;">
                        	<div id="statistics"></div>
                        </div>
                   	</div>
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
    <script src="{{ URL::asset('plugins/apex/apexcharts.min.js') }}"></script>
    <script>

        $('#html5-extension').DataTable({
            responsive: true,
            dom:
            '<"row"<"col-md-12"<"row"  <"col-md-4"l>  <"col-md-4 text-center"B>  <"col-md-4"f>>>' +
            '<"col-md-12"tr>' +
            '<"col-md-12 mt-2"<"row mt-3"  <"col-md-5"i>  <"col-md-7 text-right"p>>> >',
            buttons: {
                buttons: [{
                    extend: 'csv',
                    className: 'btn'
                },{
                    extend: 'excel',
                    className: 'btn'
                }]
        },
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
            "order":[[1, 'desc']],//asc or desc
            "lengthMenu": [10, 20, 30, 50],
            "pageLength": 10
        });

    	$('[data-bs-toggle="tooltip"]').tooltip();

    </script>
	<script>

      var options1 = {
        chart: {
          fontFamily: 'Nunito, sans-serif',
          height: 365,
          type: 'area',
          zoom: {
              enabled: false
          },
          dropShadow: {
            enabled: true,
            opacity: 0.3,
            blur: 5,
            left: -7,
            top: 22
          },
          toolbar: {
            show: false
          },
          events: {
            mounted: function(ctx, config) {
              const highest1 = ctx.getHighestValueInSeries(0);
              const highest2 = ctx.getHighestValueInSeries(1);

              ctx.addPointAnnotation({
                x: new Date(ctx.w.globals.seriesX[0][ctx.w.globals.series[0].indexOf(highest1)]).getTime(),
                y: highest1,
                label: {
                  style: {
                    cssClass: 'd-none'
                  }
                },
                customSVG: {
                    SVG: '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="#1b55e2" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle"><circle cx="12" cy="12" r="10"></circle></svg>',
                    cssClass: undefined,
                    offsetX: -8,
                    offsetY: 5
                }
              })

              ctx.addPointAnnotation({
                x: new Date(ctx.w.globals.seriesX[1][ctx.w.globals.series[1].indexOf(highest2)]).getTime(),
                y: highest2,
                label: {
                  style: {
                    cssClass: 'd-none'
                  }
                },
                customSVG: {
                    SVG: '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="#e7515a" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle"><circle cx="12" cy="12" r="10"></circle></svg>',
                    cssClass: undefined,
                    offsetX: -8,
                    offsetY: 5
                }
              })
            },
          }
        },
        colors: ['#1b55e2', '#e7515a', '#2e8e3f'],
        dataLabels: {
            enabled: false
        },
        markers: {
          discrete: [{
          seriesIndex: 0,
          dataPointIndex: 7,
          fillColor: '#000',
          strokeColor: '#000',
          size: 5
        }, {
          seriesIndex: 2,
          dataPointIndex: 11,
          fillColor: '#000',
          strokeColor: '#000',
          size: 4
        }]
        },
        subtitle: {
          text: 'Statistics',
          align: 'left',
          margin: 0,
          offsetX: -10,
          offsetY: 35,
          floating: false,
          style: {
            fontSize: '14px',
            color:  '#888ea8'
          }
        },
        title: {
          text: 'Income',
          align: 'left',
          margin: 0,
          offsetX: -10,
          offsetY: 0,
          floating: false,
          style: {
            fontSize: '25px',
            color:  '#0e1726'
          },
        },
        stroke: {
            show: true,
            curve: 'smooth',
            width: 2,
            lineCap: 'square'
        },
        series: [{
            name: 'Gross Earnings',
            data: [
        		<?php
        			foreach ($g_gross as $Key => $value) {
                    	?>
        				{{ $value }},
        				<?php
                    }
        		?>
        	]
        }, {
            name: 'Tax Withheld',
            data: [
        		<?php
        			foreach ($g_tax as $Key => $value) {
                    	?>
        				{{ $value }},
        				<?php
                    }
        		?>
          	]
        }, {
            name: 'Net Earnings',
            data: [
        		<?php
        			foreach ($g_net as $Key => $value) {
                    	?>
        				{{ $value }},
        				<?php
                    }
        		?>
          	]
        }],
        labels: [],
        xaxis: {
          axisBorder: {
            show: false
          },
          axisTicks: {
            show: false
          },
          crosshairs: {
            show: true
          },
          labels: {
            offsetX: 0,
            offsetY: 5,
            style: {
                fontSize: '12px',
                fontFamily: 'Nunito, sans-serif',
                cssClass: 'apexcharts-xaxis-title',
            },
          }
        },
        yaxis: {
          labels: {
            formatter: function(value, index) {
              return '$'+(value / 1000) + 'K'
            },
            offsetX: -22,
            offsetY: 0,
            style: {
                fontSize: '12px',
                fontFamily: 'Nunito, sans-serif',
                cssClass: 'apexcharts-yaxis-title',
            },
          }
        },
        grid: {
          borderColor: '#e0e6ed',
          strokeDashArray: 5,
          xaxis: {
              lines: {
                  show: true
              }
          },
          yaxis: {
              lines: {
                  show: false,
              }
          },
          padding: {
            top: 0,
            right: 0,
            bottom: 0,
            left: -10
          },
        },
        legend: {
          position: 'top',
          horizontalAlign: 'right',
          offsetY: -50,
          fontSize: '16px',
          fontFamily: 'Nunito, sans-serif',
          markers: {
            width: 10,
            height: 10,
            strokeWidth: 0,
            strokeColor: '#fff',
            fillColors: undefined,
            radius: 12,
            onClick: undefined,
            offsetX: 0,
            offsetY: 0
          },
          itemMargin: {
            horizontal: 0,
            vertical: 20
          }
        },
        tooltip: {
          theme: 'dark',
          marker: {
            show: true,
          },
          x: {
            show: false,
          }
        },
        fill: {
            type:"gradient",
            gradient: {
                type: "vertical",
                shadeIntensity: 1,
                inverseColors: !1,
                opacityFrom: .28,
                opacityTo: .05,
                stops: [45, 100]
            }
        },
        responsive: [{
          breakpoint: 575,
          options: {
            legend: {
                offsetY: -30,
            },
          },
        }]
      }
      var chart1 = new ApexCharts(
          document.querySelector("#statistics"),
          options1
      );

      chart1.render();

	</script>

@endsection
