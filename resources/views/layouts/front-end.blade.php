<!doctype html>
<html lang="{{ config('app.locale') }}" class="no-focus">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

        <title>{{ config('app.name') }}</title>

        <meta name="description" content="{{ config('app.name') }}">
        <meta name="author" content="MIS">
        <meta name="robots" content="noindex, nofollow">

        {{-- <meta property="og:url"  content="@yield('content_url')" />
		<meta property="og:type" content="article" />
		<meta property="og:title" content="@yield('pageTitle')" />
		<meta property="og:description" content="@yield('pageTitle')" />
		
		<link rel="image_src" type="image/jpg" href="@yield('pageImage')" />
		<meta property="og:image" content="@yield('pageImage')">
		<meta property="og:image:secure_url" content="@yield('pageImage')">
		<meta property="og:image:width" content="@yield('image_width')">
		<meta property="og:image:height" content="@yield('image_height')">
		<meta name="twitter:image" content="@yield('pageImage')"> --}}

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Icons -->
        <link rel="shortcut icon" href="{{ asset('logo-sis.ico') }}">
        <link rel="icon" sizes="192x192" type="image/png" href="{{ asset('logo-sis.ico') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('logo-sis.ico') }}">

        <!-- Fonts and Styles -->
        @yield('css_before')
        <link rel="stylesheet" href="{{ asset('fonts/font-face-muli.css') }}">
        <link rel="stylesheet" id="css-main" href="{{ mix('/css/codebase.css') }}">
        <link rel="stylesheet" id="css-main" href="{{ asset('/css/custom.sis.css') }}">

        <!-- You can include a specific file from public/css/themes/ folder to alter the default color theme of the template. eg: -->
        <!-- <link rel="stylesheet" id="css-theme" href="{{ mix('/css/themes/corporate.css') }}"> -->
        @yield('css_after')

        <!-- Scripts -->
        <script>window.Laravel = {!! json_encode(['csrfToken' => csrf_token(),]) !!};</script>
    </head>
    <body>
        <div id="page-container" class="main-content-boxed side-trans-enabled mw-1080" >
            <main class="main-container">
                <div class="fixed-top bar-fixed-top bg-primary-light">
                    <a class="text-muted ml-2 mr-2" href="{{ route('profiles.index')}}"><i class="fa fa-user mr-2"></i><u>Profile</u></a>
                    | <a class="text-muted ml-2 mr-2" href="{{ route('change_passwords.index')}}"><i class="si si-key mr-2"></i><u>Change Password</u></a>
                    | <a class="text-muted ml-2" href="/logout"><i class="si si-logout mr-2"></i><u>Logout</u></a>
                </div>
                <div class="mt-3 mb-3" style="margin-top: 4rem !important;"><h2 class="fw-normal text-center">SIS MICROSOFT-ESD</h2></div>
                @if(!request()->is('/'))
                <div class="mb-3 container">
                    <div class=" block-themed block-rounded">
                            <div class="row">
                                <div class="col-12">
                                    <button type="button" class="btn btn-secondary min-width-125 js-click-ripple-enabled" data-toggle="click-ripple" 
                                    style="overflow: hidden; position: relative; z-index: 1;"
                                    onclick="location.href='{{ route('order.index')}}'">
                                        <i class="fa fa-arrow-left mr-2"></i>back
                                    </button>
                                </div>
                            </div>
                    </div>
                </div>
                @endif
                @yield('content')
            </main>
        </div>
        <!-- END Page Container -->

        <!-- Codebase Core JS -->
        <script src="{{ mix('js/codebase.app.js') }}"></script>

        <!-- Laravel Scaffolding JS -->
        <script src="{{ mix('js/laravel.app.js') }}"></script>

        <!-- bootstrap notify JS -->
        <script src="{{ asset('/js/plugins/bootstrap-notify/bootstrap-notify.min.js') }}"></script>

        <!-- custom JS -->
        <script src="{{ asset('/js/custom.sis.js') }}"></script>

        <!-- bootstrap sweetalert2 -->
        <script src="{{ asset('js/plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
        <link rel="stylesheet" id="css-main" href="{{ asset('js/plugins/sweetalert2/sweetalert2.min.css') }}">

        @yield('js_after')
        @yield('js_after_noit')
    </body>
    <style>
        .mw-1080 
        {
            max-width: 1080px !important;
        }
        .fw-normal {
            font-weight: 400!important;
        }
        .bar-fixed-top{
            height: 30px;
            text-align: right;
            padding-right: 10%;
            padding-top: 5px;
            
        }
    </style>
</html>
