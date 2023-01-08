@extends('layouts.layout.main')
@section('title','Client Statment')
@section('css')

<link rel="stylesheet" type="text/css" href="{{ URL::asset('plugins/table/table/datatable/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('plugins/table/table/datatable/custom_dt_html5.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('plugins/table/table/datatable/dt-global_style.css') }}">
<style>
    table.dataTable thead .sorting_asc:before {
        display: none;
    }
</style>
<link href="{{ URL::asset('plugins/flatpickr/flatpickr.css') }}" rel="stylesheet" type="text/css">
<link href="{{ URL::asset('plugins/flatpickr/custom-flatpickr.css') }}" rel="stylesheet" type="text/css">
<link href="{{ URL::asset('plugins/loaders/custom-loader.css') }}" rel="stylesheet" type="text/css" />
<style>
    .loader {
        position: sticky !important;
        text-align: center !important;
        left: 50% !important;
        top: 50% !important;
    }

    table,
    th,
    td {
        border: 1px solid white !important;
    }
</style>

@endsection
@section('contents')

<div class="container-fluid p-10">
    <div class="row justify-content-center">
        <div class="white_box mb_20">
            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                <h4>Client Statment for Account</h4>
                <hr>
                <form action="{{ URL::to('/AccountStatmentReport/report') }}" method="GET" id="myform">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Range Type</label>
                            <span style="color: red;"> *</span>
                            <select id="select-range" class="form-control" name="type">
                                <option value="">--Select Date Range --</option>
                                <option value="Today">Today</option>
                                <option value="Yesterday">Yesterday</option>
                                <option value="Last 7 Days">Last 7 Days</option>
                                <option value="Last 30 Days">Last 30 Days</option>
                                <option value="This Month">This Month</option>
                                <option value="Last Month">Last Month</option>
                                <option value="Custom Range">Custom Range</option>
                            </select>
                        </div>
                        <div class="col-md-9"></div>
                    </div>
                    <br>
                    <div class="row" id="custom-range" style="display: none;">
                        <div class="col-md-3">
                            <input type="text" id="from_date" name="from"
                                class="form-control flatpickr flatpickr-input" placeholder="Select From Date">
                        </div>
                        <div class="col-md-3">
                            <input type="text" id="to_date" name="to"
                                class="form-control flatpickr flatpickr-input" placeholder="Select To Date">
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Select Category</label>
                                <span style="color: red;"> *</span>
                                <select class="form-control" id="category_id" name="category_id" required>
                                    <option value="">-- Select Category --</option>
                                    @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">
                                        {{ $category->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Select Company</label>
                                <span style="color: red;"> *</span>
                                <select class="form-control" id="company_id" name="company_id" required>
                                    <option value="">-- Select Company --</option>

                                    @foreach ($companies as $company)
                                    <option value="{{ $company->id }}">
                                        {{ $company->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Select Sub Company</label>
                                <span style="color: red;"> *</span>
                                
                                <!--<select class="form-control" id="sub_company_id" name="sub_company_id">-->
                                    <!--<option value="">-- Select Sub Company --</option>-->
                                <!--</select>-->
                                <a  class= "d-none" type='button' id='selectAll' style="color:blue;">Select All</a>

                                    <select class="form-control" id="sub_company_id" name="sub_company_id[]"
                                        multiple="multiple" required>
                                        </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-group" id="loading-btn">
                                <!--<button id="submit-btn" class="btn btn-md btn-primary btn-lg mr-3"-->
                                <!--    style="height: 44.57px; width: 10%;">-->
                                <!--    <input type="hidden" id="dates" name="dates" value="3487">-->
                                <!--    Email-->
                                <!--</button>-->
                                <button id="submit-btn" class="btn btn-md btn-primary btn-lg mr-3"
                                    style="height: 44.57px; width: 10%;">
                                    <input type="hidden" id="dates" name="dates" value="3487">
                                   Submit
                                </button>
                                <p style="color: red;" class="mt-2"><strong id="error"
                                        style="display: none;">Please select all required fields...</strong></p>
                            </div>
                        </div>
                    </div>
                </form>
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
<script src="{{ URL::asset('plugins/flatpickr/flatpickr.js') }}"></script>
<script src="{{ URL::asset('plugins/flatpickr/custom-flatpickr.js') }}"></script>
<script src="{{URL::asset('assets/js/jquery.multifile.js')}}"></script>
<script src="{{URL:: asset('loadingoverlay.min.js') }}"></script>
<script>
    $('#submit-btn').click(function (e) {
          	// range
          	var from = '';
          	var to = '';
          	var range_type = $('#select-range').val();
          	if (range_type == '')
            	$('#select-range').css('border-color','#E91E63');
            else  {
            	$('#select-range').css('border-color','#bfc9d4');
              	if (range_type == 'Custom Range') {
                	var from = $('#from_date').val();
                    if (from == '')
                        $('#from_date').css('border-color','#E91E63');
                    else
                        $('#from_date').css('border-color','#bfc9d4');

                  	var to = $('#to_date').val();
                    if (to == '')
                        $('#to_date').css('border-color','#E91E63');
                    else
                        $('#to_date').css('border-color','#bfc9d4');
                }
            }


          	var category_id = $('#category_id').val();
          	if (category_id == '')
            	$('#category_id').css('border-color','#E91E63');
            else
            	$('#category_id').css('border-color','#bfc9d4');


          	var company_id = $('#company_id').val();
          	if (company_id == '')
            	$('#company_id').css('border-color','#E91E63');
            else
            	$('#company_id').css('border-color','#bfc9d4');


    	    var sub_company_id = $('#sub_company_id').val();
          	if (sub_company_id == '')
            	$('#sub_company_id').css('border-color','#E91E63');
            else
            	$('#sub_company_id').css('border-color','#bfc9d4');

          	if (range_type != '' && category_id != '' && company_id != '' && sub_company_id != '') {
                e.preventDefault();
              	$('#error').css('display','none');
                $("#myform").attr('target', '_blank');
                $("#myform").submit();


            } else {
              	$('#error').css('display','block');
            	e.preventDefault();
              	return false;
            }
        });

    	$('#category_id').change(function () {
        	fetchSubCompanies();
        });

    	$('#company_id').change(function () {
        	fetchSubCompanies();
        });

      	function fetchSubCompanies()
      	{
          	var category_id = $('#category_id').val();
          	if (category_id == '')
            	$('#category_id').css('border-color','#E91E63');
            else
            	$('#category_id').css('border-color','#bfc9d4');

          	var company_id = $('#company_id').val();
          	if (company_id == '')
            	$('#company_id').css('border-color','#E91E63');
            else
            	$('#company_id').css('border-color','#bfc9d4');

          	if (category_id != '' && company_id != '') {
              	if (company_id == 'all_companies') {
                // 	$('#sub_company_id').html('<option value="all_sub_companies" selected>You have selected all</option>');
                } else {
                //   $('#sub_company_id').html('<option value="">-- Select Sub Company --</option>');
                  $.get('{{ URL::to('/fetch-sub-companies') }}',{_token:'{{ csrf_token() }}', category_id:category_id,company_id:company_id}, function(data) {
                      var array = JSON.parse(data);
                      var count = array.length;
                      var i = 0;
                      if(count > 0){

                      for (i; i < count; i++) {
                          $('#sub_company_id').append('<option value="'+array[i].id+'">'+array[i].name+'</option>');
                      }
                       $("#selectAll").removeClass('d-none');
                      }
                  });
                }
            }
        }

      	var f1 = flatpickr(document.getElementById('from_date'), {
             enableTime: true,
             dateFormat: "d-m-Y H:i"
        });

      	var f2 = flatpickr(document.getElementById('to_date'), {
             enableTime: true,
             dateFormat: "d-m-Y H:i"
        });

    	$("#select-range").change(function () {
       		var value = $("#select-range").val();
          	if (value != '') {
            	if (value == 'Custom Range') {
                	  $('#custom-range').css('display','flex');
                } else {

                  	if (value == 'Today') {
                    	var d = new Date();
                        var curr_date = d.getDate();
                        var curr_month = d.getMonth();
                        var curr_year = d.getFullYear();
                      	var from = curr_date + "-" + (parseInt(curr_month) + parseInt(1)) + "-" + curr_year + ' ' + '00:00';

                    	var d = new Date();
                        var curr_date = d.getDate();
                        var curr_month = d.getMonth();
                        var curr_year = d.getFullYear();
                      	var to = curr_date + "-" + (parseInt(curr_month) + parseInt(1)) + "-" + curr_year + ' ' + '24:00';
                    }

                  	if (value == 'Yesterday') {
                    	var today = new Date();
                      	var yesterday = new Date(today);
                      	yesterday.setDate(today.getDate() - 1);
                      	const monthNames = [
                            "01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"
                          ];
                      	var month = today.getMonth();
                        yesterday = yesterday.getDate() + '-' + monthNames[month] + '-' + yesterday.getFullYear()
                      	var from = yesterday + ' ' + '00:00';
                      	var to = yesterday + ' ' + '24:00';
                    }

                  	if (value == 'Last 7 Days') {
                    	var days = '7';
                      	var date = new Date();
                      	var last = new Date(date.getTime() - (days * 24 * 60 * 60 * 1000));
                      	var day = last.getDate();
                      	var month = last.getMonth() + 1;
                      	var year = last.getFullYear();
                      	var from = day + '-' + month+'-' + year +' '+ '00:00';

						var d = new Date();
                        var curr_date = d.getDate();
                        var curr_month = d.getMonth();
                        var curr_year = d.getFullYear();
                      	var to = curr_date + "-" + (parseInt(curr_month) + parseInt(1)) + "-" + curr_year + ' ' + '24:00';
                    }

                  	if (value == 'Last 30 Days') {
                    	var days = '30';
                      	var date = new Date();
                      	var last = new Date(date.getTime() - (days * 24 * 60 * 60 * 1000));
                      	var day = last.getDate();
                      	var month = last.getMonth() + 1;
                      	var year = last.getFullYear();
                      	var from = day + '-' + month+'-' + year +' '+ '00:00';

						var d = new Date();
                        var curr_date = d.getDate();
                        var curr_month = d.getMonth();
                        var curr_year = d.getFullYear();
                      	var to = curr_date + "-" + (parseInt(curr_month) + parseInt(1)) + "-" + curr_year + ' ' + '24:00';
                    }

                  	if (value == 'This Month') {
                    	var date = new Date();
                      	var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
                      	var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
                      	var firstDayWithSlashes = (firstDay.getDate()) + '-' + (firstDay.getMonth() + 1) + '-' + firstDay.getFullYear();
                      	var lastDayWithSlashes = (lastDay.getDate()) + '-' + (lastDay.getMonth() + 1) + '-' + lastDay.getFullYear();

                      	var from = firstDayWithSlashes + ' ' + '00:00';
                        var to = lastDayWithSlashes + ' ' + '24:00';
                    }

                  	if (value == 'Last Month') {
                    	var now = new Date();
                        var prevMonthLastDate = new Date(now.getFullYear(), now.getMonth(), 0);
                        var prevMonthFirstDate = new Date(now.getFullYear() - (now.getMonth() > 0 ? 0 : 1), (now.getMonth() - 1 + 12) % 12, 1);
                        var formatDateComponent = function(dateComponent) {
  							return (dateComponent < 10 ? '0' : '') + dateComponent;
						};

                        var formatDate = function(date) {
                          	return formatDateComponent(date.getDate()) + '-' + formatDateComponent(parseInt(date.getMonth()) + 1) + '-' + formatDateComponent(date.getFullYear());
                        };

                        var from = formatDate(prevMonthFirstDate) + ' ' + '00:00';
                        var to = formatDate(prevMonthLastDate) + ' ' + '24:00';
                    }

                  	$('#from_date').val(from);
                  	$('#to_date').val(to);
                	$('#custom-range').css('display','none');
                }
            }
        });



</script>
  <script>
   $('#selectAll').click(function() {
    console.log('clicked');
    $('#sub_company_id option').attr("selected","selected");
});
  </script>

@endsection
