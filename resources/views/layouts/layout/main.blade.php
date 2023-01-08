<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
<title>@yield('title','Dashboard')</title>
<link rel="icon" href="{{ URL::asset('assets/img/fav-icon.png') }}"/>

<link href="{{ URL::asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="{{URL::asset('admin/css/bootstrap1.min.css') }}" />
<link rel="stylesheet" href="{{URL::asset('admin/vendors/themefy_icon/themify-icons.css') }}" />
<link rel="stylesheet" href="{{URL::asset('admin/vendors/swiper_slider/css/swiper.min.css') }}" />
<link rel="stylesheet" href="{{URL::asset('admin/vendors/select2/css/select2.min.css') }}" />
<link rel="stylesheet" href="{{URL::asset('admin/vendors/niceselect/css/nice-select.css') }}" />
<link rel="stylesheet" href="{{URL::asset('admin/vendors/owl_carousel/css/owl.carousel.css') }}" />
<link rel="stylesheet" href="{{URL::asset('admin/vendors/gijgo/gijgo.min.css') }}" />
<link rel="stylesheet" href="{{URL::asset('admin/vendors/font_awesome/css/all.min.css') }}" />
<link rel="stylesheet" href="{{URL::asset('admin/vendors/tagsinput/tagsinput.css') }}" />
<link rel="stylesheet" href="{{URL::asset('admin/vendors/datatable/css/jquery.dataTables.min_6619178.css') }}" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.bootstrap5.min.css" />
<link rel="stylesheet" href="{{URL::asset('admin/vendors/text_editor/summernote-bs4.css') }}" />
<link rel="stylesheet" href="{{URL::asset('admin/vendors/morris/morris.css') }}">
<link rel="stylesheet" href="{{URL::asset('admin/vendors/material_icon/material-icons.css') }}" />
<link rel="stylesheet" href="{{URL::asset('admin/css/metisMenu_6094857.css') }}">
<link rel="stylesheet" href="{{URL::asset('admin/css/style1.css') }}" />
<link rel="stylesheet" href="{{URL::asset('admin/css/colors/default.css') }}" id="colorSkinCSS">
<link
  href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.css"
  rel="stylesheet"  type='text/css'>

  <meta name="csrf-token" content="{{ csrf_token() }}">
<!--<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">-->
<!--<link href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">-->

  @yield('css')

</head>
<body class="crm_body_bg">

    @include('layouts.layout.navbar')


    <section class="main_content dashboard_part">

        @include('layouts.layout.header')
<div class="main_content_iner ">
        @yield('contents')
</div>
       <!--@include('layouts.layout.footer')-->
    </section>





@yield('modal')


<script src="{{URL::asset('admin/js/jquery1-3.4.1.min.js') }}"></script>
<script src="{{URL::asset('admin/js/popper1.min.js') }}"></script>
<script src="{{URL::asset('admin/js/bootstrap1.min.js') }}"></script>
<script src="{{URL::asset('admin/js/metisMenu_1048644.js') }}"></script>
<script src="{{URL::asset('admin/vendors/count_up/jquery.waypoints.min.js') }}"></script>
<script src="{{URL::asset('admin/vendors/chartlist/Chart.min_7733328.js') }}"></script>
<script src="{{URL::asset('admin/vendors/count_up/jquery.counterup.min.js') }}"></script>
<script src="{{URL::asset('admin/vendors/swiper_slider/js/swiper.min.js') }}"></script>
<script src="{{URL::asset('admin/vendors/niceselect/js/jquery.nice-select.min.js') }}"></script>
<script src="{{URL::asset('admin/vendors/owl_carousel/js/owl.carousel.min.js') }}"></script>
<script src="{{URL::asset('admin/vendors/gijgo/gijgo.min.js') }}"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.bootstrap5.min.js"></script>
{{-- <script src="{{URL::asset('admin/vendors/datatable/js/buttons.flash.min.js') }}"></script> --}}
<script src="{{URL::asset('admin/vendors/datatable/js/jszip.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.3.0-beta.2/pdfmake.min.js"></script>
<script src="{{URL::asset('admin/vendors/datatable/js/vfs_fonts.js') }}"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
{{-- <script src="{{URL::asset('admin/js/chart.min.js') }}"></script> --}}
<script src="{{URL::asset('admin/vendors/progressbar/jquery.barfiller.js') }}"></script>
<script src="{{URL::asset('admin/vendors/tagsinput/tagsinput.js') }}"></script>
<script src="{{URL::asset('admin/vendors/text_editor/summernote-bs4.js') }}"></script>
<script src="{{URL::asset('admin/vendors/apex_chart/apexcharts.js') }}"></script>
<script src="{{URL::asset('admin/js/custom.js') }}"></script>
<script src="{{URL::asset('admin/vendors/apex_chart/apexchart_lists.js') }}"></script>
{{-- <script src="{{ URL::asset('plugins/table/datatable/datatables.js') }}"></script> --}}
    <script src="{{ URL::asset('plugins/apex/apexcharts.min.js') }}"></script>

 {{-- <script src="{{URL::asset('admin/vendors/apex_chart/apexchart_lists.js') }}"></script> --}}
 <script src="{{ URL::asset('plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
 <script src="{{ URL::asset('assets/js/app.js') }}"></script>
 <script src="{{ URL::asset('assets/js/custom.js') }}"></script>
 <script src="{{ URL::asset('assets/js/dashboard/dash_2.js') }}"></script>
@yield('scripts')
<script src="{{ URL::asset('plugins/sweetalerts/sweetalert2.min.js') }}"></script>
<script src="{{ URL::asset('plugins/sweetalerts/custom-sweetalert.js') }}"></script>
{{-- @yield('sweetalert::alert') --}}
<script>

// @yield('sweetalert::alert')
    $(document).ready(function() {
        App.init();
        var check = '{{ \Session::has('success') }}';
        var check_error = '{{ \Session::has('error') }}';
        if (check != '') {
            success();
        }
        if(chek_error != ''){
            error();
        }
    });

    function error() {
        const toast = swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            padding: '2em'
        });
        toast({
            type: 'error',
            title: '{{ session('error') }}',
            padding: '2em',
        });
    }
    function success() {
        const toast = swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            padding: '2em'
        });

        toast({
            type: 'success',
            title: '{{ session('success') }}',
            padding: '2em',
        });

    }
    </script>





</body>

</html>
