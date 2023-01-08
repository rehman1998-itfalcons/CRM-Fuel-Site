@extends('layouts.layout.main')
@section('title','Sales Orders')

@section('contents')

<div class="container-fluid p-10">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="QA_section">
                <div class="white_box_tittle list_header">
                    <h4>Records</h4>
                    <div class="box_right d-flex lms_block">
                        <div class="serach_field_2">
                            <div class="search_inner">
                                <form Active="#">
                                    <div class="search_field">
                                        <input type="text" placeholder="Search content here...">
                                    </div>
                                    <button type="submit"> <i class="ti-search"></i> </button>
                                </form>
                            </div>
                        </div>
                        <div class="add_button ms-2">
                            <a href="{{ url('records/create') }}" data-bs-toggle="modal" data-bs-target="#addcategory"
                                class="btn-primary">Add New</a>
                        </div>
                    </div>
                </div>
                <button  id="more" name=searchb>load more</button>
                <div class="QA_table mb_30">

                    <table id="html5-extension"  class="table lms_table_active">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Invoice no</th>
                                <th>Date/Time</th>
                                <th>Trip Number</th>
                                <th>Order Number</th>
                                <th>Category</th>
                                <th>Sub Company</th>
                                <th>GST Status</th>
                                <th>Total Amount</th>
                                <th>Details</th>

                              </tr>
                        </thead>
                        <tbody id="body_result">
                            <?php $sno = 1; ?>
                            @foreach ($sales as $sale)
                                <tr>
                                    <td>{{ $sno++}}</td>
                                    <td>{{ $sale->invoice_number }}</td>
                                    <td>{{ \Carbon\Carbon::parse($sale->datetime)->format('d-m-Y') }}</td>
                                    <td>{{ $sale->load_number }}</td>
                                    <td>{{ $sale->order_number }}</td>
                                      <td>{{ $sale->category->name }}</td>
                                    <td>{{ $sale->subCompany->name }}</td>
                                    <td>{{ $sale->gst_status }}</td>
                                    <td>${{ number_format($sale->total_amount,2) }}</td>
                                  <td style="white-space: nowrap !important;">
                                          <a  href="{{ route('records.edit',$sale->id) }}" class="btn btn-sm status_btn btn-custom" style="margin-right: 5px;">Edit</a>
                                          <a href="{{ route('records.show',$sale->id) }}" class="btn btn-sm status_btn btn-custom">View</a>
                                      </td>
                                </tr>
                            @endforeach
                          </tbody>

                    </table>

                </div>
                {{-- {!! $sale->links() !!} --}}

            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')




<script src="{{URL:: href="{{ URL::asset('plugins/table/datatable/datatables.js') }}"></script>
<script src="{{URL:: href="{{ URL::asset('plugins/table/datatable/button-ext/dataTables.buttons.min.js') }}"></script>
<script src="{{URL:: href="{{ URL::asset('plugins/table/datatable/button-ext/jszip.min.js') }}"></script>
<script src="{{URL:: href="{{ URL::asset('plugins/table/datatable/button-ext/buttons.html5.min.js') }}"></script>
<script src="{{URL:: href="{{ URL::asset('plugins/table/datatable/button-ext/buttons.print.min.js') }}"></script>

 {{-- <script src="{{URL:: href="{{ URL::asset('plugins/flatpickr/flatpickr.js') }}"></script>
<script src="{{URL:: href="{{ URL::asset('plugins/flatpickr/custom-flatpickr.js') }}"></script>  --}}

<script type="text/javascript">

    $(document).ready(function(){

        $("#more").click(function(){
            alert('request');
            $.ajax({
                    type:'GET',
                    url: "{{ url('load-record') }}",

                    data: {

                        // $("#empTable").html(data);

                    },

                    success: function(data) {

                        console.log(data);
                    }

            });
         });
        });
</script>
    <script>
        $('#html5-extension').DataTable({
          	"order": [[ 0, "desc" ]],
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
                    "sPrevious": '</button><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                    "sNext": '<button id="load-more"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg></button>'
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
    </script>
    <script>

// var f1 = flatpickr(document.getElementById('from_date'), {
//      enableTime: true,
//      dateFormat: "d-m-Y H:i"
// });

//   var f2 = flatpickr(document.getElementById('to_date'), {
//      enableTime: true,
//      dateFormat: "d-m-Y H:i"
// });

$("#select-range").change(function () {
       var value = $("#select-range").val();
      if (value != '') {
        if (value == 'Custom Range') {
              $('#custom-range').css('display','flex');
        } else {
            $('#custom-range').css('display','none');
              $('#body_result').html('<div class="loader mx-auto"></div>');
              $.post('{{ url('/sales-filter') }}',{_token:'{{ csrf_token() }}', type:value}, function(data) {
                $('#body_result').html(data);
              });
        }
    }
});

  $('#submit-btn').click(function () {
    var from = $("#from_date").val();
    var to = $("#to_date").val();
      var value = 'Custom Range';

      if (from == '')
        $("#from_date").css('border-color','#e91e63');
      else
        $("#from_date").css('border-color','#bfc9d4');

      if (to == '')
        $("#to_date").css('border-color','#e91e63');
      else
        $("#to_date").css('border-color','#bfc9d4');

      if (from != '' && to != '') {
              $('#body_result').html('<div class="loader mx-auto"></div>');
          $.post('{{ url('/sales-filter') }}',{_token:'{{ csrf_token() }}', type:value,from:from,to:to}, function(data) {
            $('#body_result').html(data);
          });
    }
});

</script>







@endsection

