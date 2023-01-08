<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
<title>Hospital</title>
<link rel="icon" href="img/logo.png" type="image/png">
<link rel="stylesheet" href="{{ asset('css/bootstrap1.min.css') }}" />
<link rel="stylesheet" href="{{ asset('vendors/themefy_icon/themify-icons.css') }}" />
<link rel="stylesheet" href="{{ asset('vendors/swiper_slider/css/swiper.min.css') }}" />
<link rel="stylesheet" href="{{ asset('vendors/select2/css/select2.min.css') }}" />
<link rel="stylesheet" href="{{ asset('vendors/niceselect/css/nice-select.css') }}" />
<link rel="stylesheet" href="{{ asset('vendors/owl_carousel/css/owl.carousel.css') }}" />
<link rel="stylesheet" href="{{ asset('vendors/gijgo/gijgo.min.css') }}" />
<link rel="stylesheet" href="{{ asset('vendors/font_awesome/css/all.min.css') }}" />
<link rel="stylesheet" href="{{ asset('vendors/tagsinput/tagsinput.css') }}" />
<link rel="stylesheet" href="{{ asset('vendors/datatable/css/jquery.dataTables.min_6619178.css') }}" />
<link rel="stylesheet" href="{{ asset('vendors/datatable/css/responsive.dataTables.min_8323116.css') }}" />
<link rel="stylesheet" href="{{ asset('vendors/datatable/css/buttons.dataTables.min_917540.css') }}" />
<link rel="stylesheet" href="{{ asset('vendors/text_editor/summernote-bs4.css') }}" />
<link rel="stylesheet" href="{{ asset('vendors/morris/morris.css') }}">
<link rel="stylesheet" href="{{ asset('vendors/material_icon/material-icons.css') }}" />
<link rel="stylesheet" href="{{ asset('css/metisMenu_6094857.css') }}">
<link rel="stylesheet" href="{{ asset('css/style1.css') }}" />
<link rel="stylesheet" href="{{ asset('css/colors/default.css') }}" id="colorSkinCSS">

<link rel="stylesheet" href="{{ asset('css/colors/default.css') }}" id="colorSkinCSS">

</head>

<body class="crm_body_bg">

    @include('admin.Layout.navbar')


    <section class="main_content dashboard_part">

        @include('admin.Layout.header')

        @yield('contant')

       @include('admin.Layout.footer')
    </section>





<script src="{{ asset('js/jquery1-3.4.1.min.js') }}"></script>
<script src="{{ asset('js/popper1.min.js') }}"></script>
<script src="{{ asset('js/bootstrap1.min.js') }}"></script>
<script src="{{ asset('js/metisMenu_1048644.js') }}"></script>



<script src="{{ asset('vendors/count_up/jquery.waypoints.min.js') }}"></script>
<script src="{{ asset('vendors/chartlist/Chart.min_7733328.js') }}"></script>
<script src="{{ asset('vendors/count_up/jquery.counterup.min.js') }}"></script>
<script src="{{ asset('vendors/swiper_slider/js/swiper.min.js') }}"></script>
<script src="{{ asset('vendors/niceselect/js/jquery.nice-select.min.js') }}"></script>
<script src="{{ asset('vendors/owl_carousel/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('vendors/gijgo/gijgo.min.js') }}"></script>
<script src="{{ asset('vendors/datatable/js/jquery.dataTables.min_2752613.js') }}"></script>
<script src="{{ asset('vendors/datatable/js/dataTables.responsive.min_852062.js') }}"></script>
<script src="{{ asset('vendors/datatable/js/dataTables.buttons.min_1376319.js') }}"></script>
<script src="{{ asset('vendors/datatable/js/buttons.flash.min.js') }}"></script>
<script src="{{ asset('vendors/datatable/js/jszip.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.3.0-beta.2/pdfmake.min.js"></script>
<script src="{{ asset('vendors/datatable/js/vfs_fonts.js') }}"></script>
<script src="{{ asset('vendors/datatable/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('vendors/datatable/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('js/chart.min.js') }}"></script>
<script src="{{ asset('vendors/progressbar/jquery.barfiller.js') }}"></script>
<script src="{{ asset('vendors/tagsinput/tagsinput.js') }}"></script>
<script src="{{ asset('vendors/text_editor/summernote-bs4.js') }}"></script>
<script src="{{ asset('vendors/apex_chart/apexcharts.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>
<script src="{{ asset('vendors/apex_chart/apexchart_lists.js') }}"></script>
</body>

</html>
