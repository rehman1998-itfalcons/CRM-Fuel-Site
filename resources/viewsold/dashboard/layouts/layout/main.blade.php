<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
<title>Atlas Fuel </title>
<link rel="icon" href="{{ URL::asset('assets/img/fav-icon.png') }}"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> --}}
<link href="{{ URL::asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
{{-- <link rel="stylesheet" href="{{URL::asset('admin/css/bootstrap1.min.css') }}" /> --}}
<link rel="stylesheet" href="{{URL::asset('admin/vendors/themefy_icon/themify-icons.css') }}" />
<link rel="stylesheet" href="{{URL::asset('admin/vendors/swiper_slider/css/swiper.min.css') }}" />
<link rel="stylesheet" href="{{URL::asset('admin/vendors/select2/css/select2.min.css') }}" />
<link rel="stylesheet" href="{{URL::asset('admin/vendors/niceselect/css/nice-select.css') }}" />
<link rel="stylesheet" href="{{URL::asset('admin/vendors/owl_carousel/css/owl.carousel.css') }}" />
<link rel="stylesheet" href="{{URL::asset('admin/vendors/gijgo/gijgo.min.css') }}" />
<link rel="stylesheet" href="{{URL::asset('admin/vendors/font_awesome/css/all.min.css') }}" />
<link rel="stylesheet" href="{{URL::asset('admin/vendors/tagsinput/tagsinput.css') }}" />
<link rel="stylesheet" href="{{URL::asset('admin/vendors/datatable/css/jquery.dataTables.min_6619178.css') }}" />
<link rel="stylesheet" href="{{URL::asset('admin/vendors/datatable/css/responsive.dataTables.min_8323116.css') }}" />
<link rel="stylesheet" href="{{URL::asset('admin/vendors/datatable/css/buttons.dataTables.min_917540.css') }}" />
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
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet"> --}}

  @yield('css')

</head>
{{-- <link rel="stylesheet" href="{{URL::asset('admin/css/colors/default.css')}}" id="colorSkinCSS"> --}}
<body class="crm_body_bg">

    @include('layouts.layout.navbar')


    <section class="main_content dashboard_part">

        @include('layouts.layout.header')

        @yield('contents')

       {{-- @include('layouts.layout.footer') --}}
    </section>




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


{{--
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
<script src="{{URL::asset('admin/vendors/datatable/js/jquery.dataTables.min_2752613.js') }}"></script>
<script src="{{URL::asset('admin/vendors/datatable/js/dataTables.responsive.min_852062.js') }}"></script>
<script src="{{URL::asset('admin/vendors/datatable/js/dataTables.buttons.min_1376319.js') }}"></script>
<script src="{{URL::asset('admin/vendors/datatable/js/buttons.flash.min.js') }}"></script>
<script src="{{URL::asset('admin/vendors/datatable/js/jszip.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.3.0-beta.2/pdfmake.min.js"></script>
<script src="{{URL::asset('admin/vendors/datatable/js/vfs_fonts.js') }}"></script>
<script src="{{URL::asset('admin/vendors/datatable/js/buttons.html5.min.js') }}"></script>
<script src="{{URL::asset('admin/vendors/datatable/js/buttons.print.min.js') }}"></script>
<script src="{{URL::asset('admin/js/chart.min.js') }}"></script>
<script src="{{URL::asset('admin/vendors/progressbar/jquery.barfiller.js') }}"></script>
<script src="{{URL::asset('admin/vendors/tagsinput/tagsinput.js') }}"></script>
<script src="{{URL::asset('admin/vendors/text_editor/summernote-bs4.js') }}"></script>
<script src="{{URL::asset('admin/vendors/apex_chart/apexcharts.js') }}"></script> --}}
{{-- <script src="{{URL::asset('admin/js/custom.js') }}"></script> --}}

 {{-- <script src="{{URL::asset('admin/vendors/apex_chart/apexchart_lists.js') }}"></script> --}}
@yield('scripts')


</body>

</html>
