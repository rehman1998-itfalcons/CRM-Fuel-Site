<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
        <title>Login | Atlas Fuel</title>
        <link rel="icon" href="{{URL::asset('public/assets/img/fav-icon.png') }}"/>
        <!-- Css -->
        <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@400;700&display=swap" rel="stylesheet">
        <link href="{{URL::asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ URL::asset('assets/css/plugins.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ URL::asset('assets/css/authentication/form-2.css') }}" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="{{URL::asset('assets/css/forms/theme-checkbox-radio.css') }}">
        <link rel="stylesheet" type="text/css" href="{{URL::asset('assets/css/forms/switches.css') }}">
        <style>
            body {font-family:"Ubuntu",sans-serif;background:#eee;}
            .form-form .form-container .form-content {
                border-radius: 0px;
                border: 0;
                -webkit-box-shadow: none;
                -moz-box-shadow: none;
                box-shadow: none;
                padding-bottom: 50px;
            }
            .form-form .form-container .form-content h1 {
                color: #333;
                font-weight:700;
            }
            .form-form .form-form-wrap form .field-wrapper label {
                font-size: 14px;
                font-weight: 700;
                color: #333;
            }
            .toggle-password {
                position: absolute;
                top: 49px; right: 13px;
                color: #888ea8;
                fill: rgba(0, 23, 55, 0.08);
                width: 17px;
                cursor: pointer;
              	left: unset !important;
            }

        </style>
    </head>
    <body class="form">
        <div class="form-container outer">
            <div class="form-form">
                <div class="form-form-wrap">
                    <div class="form-container">
                        <div class="form-content">
                            <h1 class="">Sign In</h1>
                            <p class="">
                                Log in to your account to continue. <br>
                                <span>Atlas Fuel</span>
                            </p>
                            <form action="{{ route('login') }}" method="POST" class="text-left">
                                @csrf
                                <div class="form">
                                    <div id="email-field" class="field-wrapper input">
                                        <label for="email">Username</label>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-at-sign register"><circle cx="12" cy="12" r="4"></circle><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94"></path></svg>
                                        <input id="username" name="username" type="text" class="form-control @error('username') is-invalid @enderror" placeholder="Username">
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div id="password-field" class="field-wrapper input mb-2">
                                        <div class="d-flex justify-content-between">
                                            <label for="password">Password</label>
                                            <a href="{{ route('password.request') }}" style="display: none;" class="forgot-pass-link">Forgot Password?</a>
                                        </div>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                        <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password">					<span id="password-eye">
                                            <svg style="left: unset !important;" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="toggle-password" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                      		</span>
                                    </div>
                                    <div class="d-sm-flex justify-content-between">
                                        <div class="field-wrapper">
                                            <button type="submit" class="btn btn-primary" style="background-color: #1c6f2b !important; border-color: #1c6f2b !important;">Log In</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- Scripts -->
    <script src="{{URL:: asset('assets/js/libs/jquery-3.1.1.min.js') }}"></script>
    <script src="{{URL:: asset('bootstrap/js/popper.min.js') }}"></script>
    <script src="{{ URL::asset('bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{URL:: asset('assets/js/authentication/form-2.js') }}"></script>
</body>
</html>
