<!DOCTYPE html>

<html lang="en" class="light-style layout-wide customizer-hide" dir="ltr" data-theme="theme-default"
    data-assets-path="../../assets/" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Student</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.png') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('assets/css/style_frontend.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/fonts/stylesheet.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-5.3.5/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css" />

    <style>
        ::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px #18a8b0;
            border-radius: 10px;
            background-color: #18a8b0;
        }

        ::-webkit-scrollbar {
            width: 8px;
            padding-top: 10px;
            background-color: #18a8b0;
        }

        ::-webkit-scrollbar-thumb {
            border-radius: 10px;
            -webkit-box-shadow: inset 0 0 6px #18a8b0;
            background-color: #f5f5f5;
            height: 20px;
        }

        .red-text {
            color: red;
        }

        .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
            background-color: #18a8b0 !important;
        }
    </style>
    @yield('styles')
</head>

<body>
    <div class="form-main-block container-fluid">
        <div class="row h-100">
            <div class="col-md-6 d-flex flex-column justify-content-center align-items-center form-left-panel">
                <img class="site_logo" src="{{ asset('assets/images/sklps-icon.png') }}" class="sklps-form-logo"
                    alt="Logo">
                <p class="form-welcom-text mb-0">WELCOME TO</p>
                <h3 class="form-main-title fw-bold text-white">Shree Kutchi Leva Patel Samaj</h3>
                <hr class="hr-line" />
                <h5 class="text-btm text-white">Ahmedabad</h5>
            </div>
            <div class="col-md-6 d-flex flex-column justify-content-center align-items-md-center align-items-start form-right-panel">
                @yield('content')
            </div>
        </div>
    </div>
    @include('backend.layouts.auth-footer')

    <script src="{{ asset('assets/js/jquery/jquery.js') }}"></script>
    <script src="{{ asset('js/parsley/parsley.min.js') }}"></script>

    @yield('scripts')
</body>

</html>
