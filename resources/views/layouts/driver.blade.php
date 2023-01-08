<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>@yield('title','Dashboard')</title>
    <link rel="icon" href="{{URL::asset('assets/img/fav-icon.png') }}"/>
    <!--link href="{{URL::asset('assets/css/loader.css') }}'" rel="stylesheet" type="text/css"/-->
    <!--script src="{{URL::asset('assets/js/loader.js') }}"></script-->
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">
    <link href="{{URL::asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{URL::asset('assets/css/plugins.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('plugins/apex/apexcharts.css') }}" rel="stylesheet" type="text/css">
    <link href="{{URL::asset('assets/css/dashboard/dash_2.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('plugins/sweetalerts/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('plugins/sweetalerts/sweetalert.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{URL::asset('assets/css/components/custom-sweetalert.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('plugins/loaders/custom-loader.css') }}" rel="stylesheet" type="text/css" />
	<style>
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

      	.nav-logo {
              display: inline !important;
          }

      	.sidebarCollapse {
          display: none !important;
        }
      .navbar .navbar-item .nav-item.user-profile-dropdown .dropdown-menu {
      left: -62px !important;
      top: 38px !important;
      }

       @media screen and (min-device-width: 300px) and (max-device-width: 768px) {
      .navbar .navbar-item .nav-item.user-profile-dropdown .dropdown-menu {
       left: -110px !important;
      }
      }
  	</style>
  @yield('css')
  </head>
<body class="alt-menu sidebar-noneoverflow"><!-- Header -->
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
        <div class="nav-logo align-self-center"><a class="navbar-brand" href="{{ url('/driver') }}"><img
                        src="{{URL::asset('assets/img/90x90-icon.png') }}"> <span class="navbar-brand-name" style="font-weight: bolder;">ATLAS</span></a></div>
      	<ul class="navbar-item flex-row nav-dropdowns mx-auto" style="margin-right: unset !important;">
            <li class="nav-item dropdown">

          	</li>

            <li class="nav-item dropdown user-profile-dropdown order-lg-0 order-1">
              <a href="javascript:void(0);"   class="nav-link dropdown-toggle user" id="user-profile-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="media">
                      	<img src="{{URL::asset('public/uploads/default.png') }}" style="width: 40px; height: 37px; border-radius: 30px;" class="img-fluid">
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
                            <a class="" href="{{ url('/driver/change-password') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-inbox">
                                    <polyline points="22 12 16 12 14 15 10 15 8 12 2 12"></polyline>
                                    <path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path>
                                </svg>
                                change password</a>
                        </div>
                        <div class="dropdown-item">
                            <a class="" href="{{ url('/driver/profile') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-user">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                My Profile</a>
                        </div>
						@if(Auth::user()->attachments)
                        @php
                        	$attachments = explode('::',Auth::user()->attachments);
                        @endphp
						@foreach ($attachments as $key => $value)
                          <div class="dropdown-item">
                              <a class="" href="{{URL::asset('public/uploads/'.$value) }}" style="white-space: initial;">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg> {{ $value }} </a>
                          </div>
						@endforeach
                        @endif
                        <div class="dropdown-item">
                          <a class="" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-log-out">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                    <polyline points="16 17 21 12 16 7"></polyline>
                                    <line x1="21" y1="12" x2="9" y2="12"></line>
                                </svg>
                                Sign Out</a>
                          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                        </div>
                    </div>
                </div>
              </div>
            </li>
        </ul>
    </header>
</div>
<div class="main-container" id="container">
    <div class="overlay"></div>
    <div class="search-overlay"></div>


  <!-- Navs -->

    <div id="content" class="main-content">
        <div class="layout-px-spacing">
          <hr>
          @yield('contents')
      </div>
    </div>
  @yield('modal')
</div>
<script src="{{URL::asset('assets/js/libs/jquery-3.1.1.min.js') }}"></script>
<script src="{{URL::asset('bootstrap/js/popper.min.js') }}"></script>
<script src="{{URL::asset('bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ URL::asset('plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
<script src="{{URL::asset('assets/js/app.js') }}"></script>
<script>    $(document).ready(function () {
        App.init();
    });</script>
<script src="{{URL::asset('assets/js/custom.js') }}"></script>
<script src="{{ URL::asset('plugins/apex/apexcharts.min.js') }}"></script>
<script src="{{URL::asset('assets/js/dashboard/dash_2.js') }}"></script>
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

    </script>
</body>
</html>
