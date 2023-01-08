<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>@yield('title','Dashboard')</title>
    <link rel="icon" href="{{ URL::asset('assets/img/fav-icon.png') }}"/>
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">
    <link href="{{ URL::asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/css/plugins.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('plugins/apex/apexcharts.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('assets/css/dashboard/dash_2.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('plugins/sweetalerts/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('plugins/sweetalerts/sweetalert.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/css/components/custom-sweetalert.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('plugins/loaders/custom-loader.css') }}" rel="stylesheet" type="text/css" />
	<style>


element.style {
}
		.form-control {
         color:#000 !important;
        }

      .table {
         color:#000 !important;
        }

        .btn .badge {
            position: relative;
            top: 0px;
            right: -12px;
        }

      	th {
    		font-weight: 500 !important;
        }

      	#notification-area {
          height: 370px !important;
          overflow: scroll !important;
          min-width: 260px !important;
          overflow-x: auto !important;
        }
      @media screen and (min-width: 588px) {

        #big_btn{
              display: inline-flex;
        }
        #small_btn{
              display: none;
        }
      }

      @media screen and (min-device-width: 320px) and (max-device-width: 586px) {

        #big_btn{
              display: none;
        }
        #small_btn{
              display: inline-flex;
              width: 70px !important;
              height: 36px !important;
        }
          #trash_btn{
                margin-right: -2px !important;
                width: 56px !important;
                height: 36px !important;
                padding: 5px !important;
        }
        .trash_span{
              right: -5px !important;
        }
      }

      @media (min-width: 992px){
       .topbar-nav.header nav#topbar ul.menu-categories li.menu > a {
          display: flex;
          padding: 0px 18px 0 30px !important;
          height: 100%;
      }
     }
      .topbar-nav.header nav#topbar ul.menu-categories li.menu.active > a > div span {
          color: #0df33b !important;
      }

      .topbar-nav.header nav#topbar ul.menu-categories li.menu .submenu li.active a {
          color: #0f8025 !important;
      }

          .topbar-nav.header nav#topbar ul.menu-categories li.menu.active > a > div svg:not(.feather-chevron-down) {
            color: #0df33b !important;
            fill: transparent !important;
          }
      @media (max-width: 991px)
      .topbar-nav.header nav#topbar ul.menu-categories li.menu.active > .dropdown-toggle {
          background: #484444 !important;
      }
  	</style>
	<link href="{{ URL::asset('assets/css/loader.css') }}" rel="stylesheet" type="text/css" />
	<style>
		div#load_screen{
			  background: rgb(236, 239, 255);
			  opacity: 1;
			  position: fixed;
			  z-index:999999;
			  top: 0px;
			  bottom: 0;
			  left: 0;
			  right: 0;
			  width: 100%;
			  text-align: center !important;
		}
		div#load_screen .loader {
			display: contents;
			justify-content: center;
			height: 100vh;
		}
		div#load_screen .loader-content {
			right: 0;
			align-self: center;
		}
		.spinner-grow {
			top: 30% !important;
          	margin-top: 25% !important;
			color: #29a340;
			width: 5rem !important;
			height: 5rem !important;
		}
      	.btn-custom {
        	padding: 6px 10px !important;
          	margin-bottom: 5px !important;
        }
	</style>
  @yield('css')
  	<style>
  		label {
			font-size: 15px !important;
    		font-weight: 500 !important;
        }
		div.dataTables_wrapper div.dataTables_filter input {
            width: 300px !important;
        }
  	</style>
  </head>
<body class="alt-menu sidebar-noneoverflow" style="background: #F3F2EF !important;">

<!-- BEGIN LOADER -->
<div id="load_screen">
	<div class="loader">
		<div class="loader-content">
			<div class="spinner-grow align-self-center"></div>
		</div>
	</div>
</div>
<!--  END LOADER -->

<!-- Header -->
<div class="header-container">
    <header class="header navbar navbar-expand-sm">
      <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                 class="feather feather-menu">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </a>
        <div class="nav-logo align-self-center"><a class="navbar-brand" href="{{ url('/home') }}"><img
                        src="{{ URL::asset('assets/img/90x90-icon.png') }}"> <span class="navbar-brand-name" style="font-weight: bolder;">ATLAS</span></a></div>
        <ul class="navbar-item flex-row mr-auto">
            <li class="nav-item align-self-center search-animated" style="display: none;">
                <form class="form-inline search-full form-inline search" role="search">
                    <div class="search-bar"><input type="text" class="form-control search-form-control  ml-lg-auto"
                                                   placeholder="Search..."></div>
                </form>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="feather feather-search toggle-search">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </li>
        </ul>
        @php
        	$notifications = \App\Notification::orderBy('created_at','desc')->get();
        	$supervisor_count = \App\Record::where('supervisor_status',0)->where('deleted_status',0)->count();
            $accountant_count = \App\Record::where('supervisor_status',1)->where('deleted_status',0)->where('status',0)->count();
        @endphp
      	<ul class="navbar-item flex-row nav-dropdowns">
            <li class="nav-item dropdown" id="extra">
              <a href="{{ route('supervisor.dashboard') }}" class="btn btn-rounded btn-sm btn-warning" id="big_btn">Supervisor <span class="badge badge-dark">{{ $supervisor_count }}</span></a>
              <a href="{{ route('supervisor.dashboard') }}" class="btn btn-rounded btn-sm btn-warning" id="small_btn">Sup<span class="badge badge-dark" style="right: -1px;">{{ $supervisor_count }}</span></a>
              <a href="{{ route('accountant.dashboard') }}" class="btn btn-rounded btn-sm btn-info" id="big_btn">Accountant <span class="badge badge-dark">{{ $accountant_count }}</span></a>
               <a href="{{ route('accountant.dashboard') }}" class="btn btn-rounded btn-sm btn-info" id="small_btn">Acc<span class="badge badge-dark" style="right: -6px;">{{ $accountant_count }}</span></a>
              <a href="{{ route('trash') }}" class="btn btn-sm btn-rounded btn-danger" id="trash_btn">
              	<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg> <span class="badge badge-dark trash_span">{{ \DB::table('records')->where('deleted_status',1)->count() }}</span>
              </a>
          	</li>
            <li class="nav-item dropdown notification-dropdown">
              <a href="javascript:void(0);" class="nav-link dropdown-toggle" id="notificationDropdown" data-bs-toggle="dropdown"aria-haspopup="true" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-bell">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                	<sup id="new-notification-count">{{ $notifications->count() }}</sup>
              	</a>
                <div class="dropdown-menu position-absolute animated fadeInUp" id="notification-area" aria-labelledby="notificationDropdown">
                    <div class="notification-scroll">
                      	@forelse ($notifications as $notification)
                        <a href="{{ $notification->url }}" class="dropdown-item">
                            <div class="media file-upload">
                                <div class="media-body">
                                    <div class="data-info">
                                      	<h6 class="">{{ $notification->user->name }}</h6>
                                      	<p class="">{{ $notification->comment }}</p>
                                      	<small class="text-primary" style="float: right; font-size: 11px;">{{ $notification->created_at->diffForHumans() }}</small>
                                  	</div>
                                </div>
                            </div>
                        </a>
                      	@empty
							<p style="text-align: center; margin-top: 10%;">No New Notifications</p>
                      		<p style="text-align: center;">
                              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-meh"><circle cx="12" cy="12" r="10"></circle><line x1="8" y1="15" x2="16" y2="15"></line><line x1="9" y1="9" x2="9.01" y2="9"></line><line x1="15" y1="9" x2="15.01" y2="9"></line></svg>
                      		</p>
                      	@endforelse
                    </div>
                </div>
            </li>
            <li class="nav-item dropdown user-profile-dropdown order-lg-0 order-1">
              <a href="javascript:void(0);"   class="nav-link dropdown-toggle user" id="user-profile-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="media">
                      	<img src="@if(Auth::user()->photo) {{ URL::asset('public/uploads/profile/'.Auth::user()->photo) }} @else {{ URL::asset('public/uploads/default.png') }} @endif" style="width: 40px; height: 37px; border-radius: 30px;" class="img-fluid">
                        <div class="media-body align-self-center"><h6><span></span>{{Auth::user()->username}}</h6></div>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-chevron-down">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </a>
                <div class="dropdown-menu position-absolute animated fadeInUp" aria-labelledby="user-profile-dropdown">
                    <div class="">
                      <div class="">
                        <div class="dropdown-item">
                            <a class="" href="{{ route('profile.index') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-user">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                My Profile
                          </a>
                        </div>
                        <div class="dropdown-item">
                            <a class="" href="{{ route('change-password.index') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-inbox">
                                    <polyline points="22 12 16 12 14 15 10 15 8 12 2 12"></polyline>
                                    <path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path>
                                </svg>
                                Change Password
                          </a>
                        </div>
                        <div class="dropdown-item">
                            <a class="" href="{{ url('/2fa') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-inbox">
                                    <polyline points="22 12 16 12 14 15 10 15 8 12 2 12"></polyline>
                                    <path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path>
                                </svg>
                                2FA Settings
                          </a>
                        </div>
                       <div class="dropdown-item" style="display: none;">
                         <a class="" href="#">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-lock">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                </svg>
                                File Manager
                         </a>
                        </div>
                       <div class="dropdown-item" style="display: none;">
                         <a class="" href="#">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-lock">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                </svg>
                                Record Manager
                         </a>
                        </div>
                        <div class="dropdown-item">
                          	<a class="" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-log-out">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                    <polyline points="16 17 21 12 16 7"></polyline>
                                    <line x1="21" y1="12" x2="9" y2="12"></line>
                                </svg>
                                Sign Out
                          	</a>
                          	<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
                        </div>
                    </div>
                </div>
              </div>
            </li>
        </ul>
    </header>
</div>
<div class="main-container" id="container">
    <div class="overlay" id="overlay"></div>
    <div class="search-overlay"></div>
  	<!-- Navs -->
    <div class="topbar-nav header navbar" role="banner">
        <nav id="topbar" class="">
            <ul class="navbar-nav theme-brand flex-row  text-center">
                <li class="nav-item theme-logo"><a href="{{ url('/dashboard') }}">
                  <img src="{{ URL::asset('assets/img/90x90-icon.png') }}" class="navbar-logo" alt="SMJ"> </a></li>
                <li class="nav-item theme-text"><a href="{{ url('/home') }}" class="nav-link"> ATLAS </a></li>
            </ul>
            <ul class="list-unstyled menu-categories" id="topAccordion">
                <li class="menu single-menu {{ Request::is('home') ? 'active' : '' }}">
                  <a href="{{url('/home')}}">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-home">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                            <span>Dashboard</span>
                    	</div>
                    </a>
                </li>
               <li class="menu single-menu {{ route::currentRouteName() == 'client.statement'|| route::currentRouteName() == 'purchase.statment'|| route::currentRouteName() == 'supplier.report'|| route::currentRouteName() == 'income.report'|| route::currentRouteName() == 'accounting.summary.report'|| route::currentRouteName() == 'company.detail.report'|| route::currentRouteName() == 'delivery.detail.view' || route::currentRouteName() == 'deliveries.summary' || route::currentRouteName() == 'purchases.vs.sales.company' || Request::is('income-statistics-report') ? 'active' : '' }}">
                  <a href="#settings" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                            <span>Reports</span></div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-chevron-down">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </a>
                    <ul class="collapse submenu list-unstyled" id="settings" data-parent="#topAccordion" style="width: 230px;">
                       <li class="{{ route::currentRouteName() == 'purchase.vs.sales' ? 'active' : '' }}">
                         <a href="{{ route('purchases.vs.sales') }}">Purchase Vs Sales</a>
                      </li>
                      <li class="{{ route::currentRouteName() == 'manual.purchase.vs.sales' ? 'active' : '' }}">

                         <a href="{{ route('manual.purchases.vs.sales') }}">Manual Purchase Vs Sales</a>

                      </li>
                      <li class="{{ route::currentRouteName() == 'purchases.vs.sales.company' ? 'active' : '' }}">
                         <a href="{{ route('purchases.vs.sales.company') }}">Purchase Vs Sales Company</a>
                      </li>
                       <li class="{{ route::currentRouteName() == 'client.statement' ? 'active' : '' }}">
                         <a href="{{ route('client.statement') }}">Client Statment</a>
                       </li>
                      <li class="{{ route::currentRouteName() == 'purchase.statement' ? 'active' : '' }}">
                         <a href="{{ route('purchase.statement') }}">Purchase Statment</a>
                      </li>
                      <li class="{{ route::currentRouteName() == 'supplier.report' ? 'active' : '' }}">
                          <a href="{{ route('supplier.report') }}">Supplier Report</a>
                      	</li>
                      <li class="{{ route::currentRouteName() == 'income.report' ? 'active' : '' }}">
                          <a href="{{ route('income.report') }}">Sales Report</a>
                      	</li>
                       <li class="{{ route::currentRouteName() == 'prchase.report' ? 'active' : '' }}">
                          <a href="{{ route('purchase.report') }}">Purchase Report</a>
                      	</li>
                      <li class="{{ route::currentRouteName() == 'accounting.summary.report' ? 'active' : '' }}">
                          <a href="{{ route('accounting.summary.report') }}">Accountant Summary</a>
                      	</li>
                      <li class="{{ route::currentRouteName() == 'client.detail.report' ? 'active' : '' }}">
                          <a href="{{ route('client.detail.report') }}">Client Detail Report</a>
                      	</li>
                      <li class="{{ route::currentRouteName() == 'company.detail.report' ? 'active' : '' }}">
                          <a href="{{ route('company.detail.report') }}">Company Detail Report</a>
                      	</li>
                      <li class="{{ route::currentRouteName() == 'delivery.detail.view' ? 'active' : '' }}">
                          <a href="{{route('delivery.detail.view')}}">Delivery Detail Report</a>
                      	</li>
                      <li class="{{ Request::is('income-statistics-report') ? 'active' : '' }}">
                          <a href="{{ url('/income-statistics-report') }}">Income Statistics Report</a>
                      	</li>
                      <li class="{{ route::currentRouteName() == 'deliveries.summary' ? 'active' : '' }}">
                          <a href="{{ route('deliveries.summary') }}">Deliveries Summary</a>
                      	</li>
                    </ul>
                </li>
                <li class="menu single-menu {{ route::currentRouteName() == 'categories.index'|| route::currentRouteName() == 'companies.index'|| route::currentRouteName() == 'supplier-companies.index'|| route::currentRouteName() == 'products.index'|| route::currentRouteName() == 'smtp'|| Request::is('invoice-settings') ? 'active' : '' }}">
                  <a href="#settings" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                            <span>Settings</span></div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-chevron-down">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </a>
                    <ul class="collapse submenu list-unstyled" id="settings" data-parent="#topAccordion">
                        <li class="{{ route::currentRouteName() == 'categories.index' ? 'active' : '' }}">
                          <a href="{{ route('categories.index') }}">Categories</a>
                      	</li>
                        <li class="{{ route::currentRouteName() == 'companies.index' ? 'active' : '' }}">
                          <a href="{{ route('companies.index') }}">Companies</a>
                      </li>
                        <li class="{{ route::currentRouteName() == 'supplier-companies.index' ? 'active' : '' }}">
                          <a href="{{ route('supplier-companies.index') }}">Supplier Companies</a>
                      </li>
                        <li class="{{ route::currentRouteName() == 'products.index' ? 'active' : '' }}">
                          <a href="{{ route('products.index') }}">Products</a>
                      </li>
                        <li class="{{ route::currentRouteName() == 'smtp' ? 'active' : '' }}">
                          <a href="{{ route('smtp') }}">SMTP Settings</a>
                      </li>
                       <li class="{{ Request::is('invoice-settings') ? 'active' : '' }}">
                          <a href="{{ url('invoice-settings') }}">Invoice Settings</a>
                      </li>
                    </ul>
                </li>
                <li class="menu single-menu {{ route::currentRouteName() == 'roles.index' || route::currentRouteName() == 'users.index' ? 'active' : '' }}">
                  <a href="#users" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                            <span>Users</span></div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-chevron-down">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </a>
                    <ul class="collapse submenu list-unstyled" id="users" data-parent="#topAccordion">
                        <li class="{{ route::currentRouteName() == 'roles.index' ? 'active' : '' }}">
                            <a href="{{ route('roles.index') }}">Roles</a>
                      	</li>
                        <li class="{{ route::currentRouteName() == 'users.index' ? 'active' : '' }}">
                            <a href="{{ route('users.index') }}">Users</a>
                        </li>
                        <li class="{{ route::currentRouteName() == 'logs.index' ? 'active' : '' }}">
                            <a href="{{ route('logs.index') }}">Logs Activity</a>
                        </li>
                    </ul>
                </li>
              	<li class="menu single-menu {{ route::currentRouteName() == 'all.invoices' || route::currentRouteName() == 'paid.invoices' || route::currentRouteName() == 'unpaid.invoices' || route::currentRouteName() == 'overdue.invoices' ? 'active' : '' }}">
                  <a href="#invoices" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-archive"><polyline points="21 8 21 21 3 21 3 8"></polyline><rect x="1" y="3" width="22" height="5"></rect><line x1="10" y1="12" x2="14" y2="12"></line></svg>
                            <span>Sale Invoices</span></div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-chevron-down">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </a>
                    <ul class="collapse submenu list-unstyled" id="invoices" data-parent="#topAccordion">
                        <li class="{{ route::currentRouteName() == 'all.invoices' ? 'active' : '' }}">
                          <a href="{{ route('all.invoices') }}">All Invoices</a>
                      	</li>
                        <li class="{{ route::currentRouteName() == 'paid.invoices' ? 'active' : '' }}">
                          <a href="{{ route('paid.invoices') }}">Paid Invoices</a>
                      </li>
                        <li class="{{ route::currentRouteName() == 'unpaid.invoices' ? 'active' : '' }}">
                          <a href="{{ route('unpaid.invoices') }}">Unpaid Invoices</a>
                      </li>
                        <li class="{{ route::currentRouteName() == 'overdue.invoices' ? 'active' : '' }}">
                          <a href="{{ route('overdue.invoices') }}">Overdue Invoices</a>
                      </li>
                    </ul>
                </li>
                <li class="menu single-menu {{ route::currentRouteName() == 'purchase.all.invoices' || route::currentRouteName() == 'purchase.paid.invoices' || route::currentRouteName() == 'purchase.unpaid.invoices' || route::currentRouteName() == 'purchase.overdue.invoices' ? 'active' : '' }}">
                    <a href="#invoices" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                          <div class="">
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-archive"><polyline points="21 8 21 21 3 21 3 8"></polyline><rect x="1" y="3" width="22" height="5"></rect><line x1="10" y1="12" x2="14" y2="12"></line></svg>
                              <span>Purchase Invoices</span></div>
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                               stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                               class="feather feather-chevron-down">
                              <polyline points="6 9 12 15 18 9"></polyline>
                          </svg>
                      </a>
                      <ul class="collapse submenu list-unstyled" id="invoices" data-parent="#topAccordion">
                          <li class="{{ route::currentRouteName() == 'purchase.all.invoices' ? 'active' : '' }}">
                            <a href="{{ route('purchase.all.invoices') }}">All Invoices</a>
                          </li>
                          <li class="{{ route::currentRouteName() == 'purchase.paid.invoices' ? 'active' : '' }}">
                            <a href="{{ route('purchase.paid.invoices') }}">Paid Invoices</a>
                        </li>
                          <li class="{{ route::currentRouteName() == 'purchase.unpaid.invoices' ? 'active' : '' }}">
                            <a href="{{ route('purchase.unpaid.invoices') }}">Unpaid Invoices</a>
                        </li>
                      </ul>
                  </li>

               	<li class="menu single-menu {{ route::currentRouteName() == 'records.create' || route::currentRouteName() == 'records.index' ? 'active' : '' }}">
                  <a href="#sales" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <div class="">
                           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                            <span>Records</span></div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-chevron-down">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </a>
                    <ul class="collapse submenu list-unstyled" id="sales" data-parent="#topAccordion">
                       <li class="{{ route::currentRouteName() == 'records.create' ? 'active' : '' }}">
                          <a href="{{ route('records.create') }}">Add Sales Record</a>
                      </li>
                       <li class="{{ route::currentRouteName() == 'records.index' ? 'active' : '' }}">
                          <a href="{{ route('records.index') }}">View Sales Records</a>
                      </li>
                    </ul>
                </li>
               	<li class="menu single-menu {{ route::currentRouteName() == 'mass-match.index' || route::currentRouteName() == 'mass-match.show' || route::currentRouteName() == 'manual-mass-match.show' || route::currentRouteName() == 'purchase.create' || route::currentRouteName() == 'purchases' ? 'active' : '' }}">
                  <a href="#purchase" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <div class="">
                           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                            <span>Purchase</span></div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-chevron-down">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </a>
                    <ul class="collapse submenu list-unstyled" id="purchase" data-parent="#topAccordion">
                      <li class="{{ route::currentRouteName() == 'mass-match.index' ? 'active' : '' }}">
                          <a href="{{ route('mass-match.index') }}">Mass Match</a>
                      </li>
                      <li class="{{ route::currentRouteName() == 'mass-match.show' ? 'active' : '' }}">
                          <a href="{{ route('mass-match.show','all') }}">View Mass Match</a>
                      </li>
                      <li class="{{ route::currentRouteName() == 'manual-mass-match.show' ? 'active' : '' }}">
                          <a href="{{ route('manual-mass-match.show') }}">View Manual Mass Match</a>
                      </li>
                      <li class="{{ route::currentRouteName() == 'purchase.create' ? 'active' : '' }}">
                          <a href="{{ route('purchase.create') }}">Add Purchase Order</a>
                      </li>
                      <li class="{{ route::currentRouteName() == 'purchases' ? 'active' : '' }}">
                        <a href="{{ route('purchases') }}">View Purchase Orders</a>
                      </li>

                    </ul>
                </li>
               	<li class="menu single-menu">
                  <a href="#expenses" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <div class="">
                        	<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trending-down"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline><polyline points="17 18 23 18 23 12"></polyline></svg>
                            <span>Expenses</span></div>
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-chevron-down">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </a>
                    <ul class="collapse submenu list-unstyled" id="expenses" data-parent="#topAccordion">
                      <li class="">
                          <a href="{{ route('expenses.create') }}">Add Expense</a>
                      </li>
                      <li class="">
                          <a href="{{ route('expenses') }}">View Expenses</a>
                      </li>
                    </ul>
                </li>
               	<li class="menu single-menu {{ Request::is('manage-account-type') || route::currentRouteName() == 'manage.chart.accounts' || Request::is('manage-subAccounts') || route::currentRouteName() == 'add.account' || route::currentRouteName() == 'manage.accounts' || route::currentRouteName() == 'transactions' ? 'active' : '' }}">
                    <a href="#coa" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-cpu"><rect x="4" y="4" width="16" height="16" rx="2" ry="2"></rect><rect x="9" y="9" width="6" height="6"></rect><line x1="9" y1="1" x2="9" y2="4"></line><line x1="15" y1="1" x2="15" y2="4"></line><line x1="9" y1="20" x2="9" y2="23"></line><line x1="15" y1="20" x2="15" y2="23"></line><line x1="20" y1="9" x2="23" y2="9"></line><line x1="20" y1="14" x2="23" y2="14"></line><line x1="1" y1="9" x2="4" y2="9"></line><line x1="1" y1="14" x2="4" y2="14"></line></svg>
                            <span>COA</span></div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-chevron-down">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </a>
                    <ul class="collapse submenu list-unstyled" id="coa" data-parent="#topAccordion">
                        <li class="{{ Request::is('manage-account-type') ? 'active' : '' }}" style="display: none;">
                            <a href="{{url('/manage-account-type')}}">Manage COA Types</a>
                        </li>
                        <li class="{{ route::currentRouteName() == 'manage.chart.accounts' ? 'active' : '' }}">
                            <a href="{{ route('manage.chart.accounts') }}">Manage Chart Of Accounts</a>
                       </li>
                        <li class="{{ Request::is('manage-subAccounts') ? 'active' : '' }}">
                            <a href="{{url('/manage-subAccounts')}}">Manage Sub Accounts</a>
                        </li>
                       <li class="{{ route::currentRouteName() == 'add.account' ? 'active' : '' }}">
                            <a href="{{ route('add.account') }}">Add New Bank Account</a>
                        </li>
                        <li class="{{ route::currentRouteName() == 'manage.accounts' ? 'active' : '' }}">
                            <a href="{{ route('manage.accounts') }}">Manage Bank Account</a>
                        </li>
                        <li class="{{ route::currentRouteName() == 'transactions' ? 'active' : '' }}">
                            <a href="{{ route('transactions') }}">Manage Transactions</a>
                        </li>
                    </ul>
                </li>
              	<li class="menu single-menu" style="display: none;">
                  <a href="#time" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clock"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                            <span>Time</span></div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-chevron-down">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </a>
                    <ul class="collapse submenu list-unstyled" id="time" data-parent="#topAccordion">
                        <li>
                          <a href="#">Time Clock</a>
                      	</li>
                        <li>
                          <a href="#">Time Sheet</a>
                      </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
    <div id="content" class="main-content">
        <div class="layout-px-spacing">
          @yield('contents')
      </div>
    </div>
  @yield('modal')
</div>
  <script src="{{ URL::asset('assets/js/libs/jquery-3.1.1.min.js') }}"></script>
  <script src="{{ URL::asset('bootstrap/js/popper.min.js') }}"></script>
  <script src="{{ URL::asset('bootstrap/js/bootstrap.min.js') }}"></script>
  <script src="{{ URL::asset('plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
  <script src="{{ URL::asset('assets/js/app.js') }}"></script>
  <script src="{{ URL::asset('assets/js/custom.js') }}"></script>
  <script>    $(document).ready(function () {
          App.init();
      });</script>
  <script src="{{ URL::asset('plugins/apex/apexcharts.min.js') }}"></script>
  <script src="{{ URL::asset('assets/js/dashboard/dash_2.js') }}"></script>
  @yield('scripts')
    <script src="{{ URL::asset('plugins/sweetalerts/sweetalert2.min.js') }}"></script>
    <script src="{{ URL::asset('plugins/sweetalerts/custom-sweetalert.js') }}"></script>
    <script>
        $(document).ready(function() {
            App.init();
            var check = '{{ \Session::has('success') }}';
            if (check != '') {
                success();
            }
        });

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

	window.addEventListener("load", function(){
		var load_screen = document.getElementById("load_screen");
		document.body.removeChild(load_screen);
	});

    </script>
  <script>
    $('.sidebarCollapse').click(function(){

       $('#container').toggleClass('topbar-closed');
       $('#overlay').toggleClass('show');
    });
  </script>
</body>
</html>
