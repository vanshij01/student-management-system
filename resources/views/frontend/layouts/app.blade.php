<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @yield('title')
    </title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.png') }}" />

    <!-- Fonts -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-5.3.5/css/bootstrap.min.css') }}" />
    <link rel="stylesheet"
        href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">

    <link rel="stylesheet" href="{{ asset('assets/css/style_frontend.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/fonts/stylesheet.css') }}" />
    {{-- <link rel="stylesheet" href="{{ asset('assets/vendor/flatpickr/flatpickr.css') }}" /> --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/sweetalert2/sweetalert2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/jquery-ui/jquery-ui.css') }}" />

    <style>
        .red-text {
            color: red;
        }

        ul.parsley-errors-list {
            margin: 0px;
            color: red;
        }

        .parsley-errors-list {
            margin: 0;
            padding: 0;
            list-style-type: none;
        }

        /* ::-webkit-scrollbar-track {
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
        } */
        ::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px #f5f5f5;
            border-radius: 10px;
            background-color: #f5f5f5;
        }

        ::-webkit-scrollbar {
            width: 8px;
            background-color: #f5f5f5;
        }

        ::-webkit-scrollbar-thumb {
            border-radius: 10px;
            -webkit-box-shadow: inset 0 0 6px #18a8b0;
            background-color: #18a8b0;
            height: 20px;
        }

        .light-style .select2-container--default .select2-selection--single {
            /* height: 100%; */
            border: 1px solid #18a8b0;
            font-weight: 400;
            font-size: 18px;
            line-height: normal;
            letter-spacing: 0%;
            padding: 15.5px 20px;
            border-radius: 10px;
            background-color: transparent;
            min-height: 60px;
        }

        .light-style .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: normal;
            padding-left: 0px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 10px;
        }

        .light-style .select2-container--default .select2-selection__placeholder {
            color: #282529;
        }

        .select2-selection__rendered:before {
            content: "";
            background-image: url('{{ asset('assets/images/arrow-icon.svg') }}');
            position: absolute;
            height: 30px;
            width: 30px;
            display: block;
            background-repeat: no-repeat;
            right: 0px;
            top: 5px;
        }

        span.select2-selection__rendered {
            position: relative;
        }

        .select2-selection__arrow {
            display: none;
        }

        span.select2.select2-container.select2-container--default {
            width: 100% !important;
        }

        .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
            background-color: #18a8b0 !important;
        }
    </style>
    @yield('styles')
</head>

<body>
    @include('frontend.layouts.header')

    <main class="middle-content">
        @yield('content')
    </main>

    @include('frontend.layouts.footer')

    <script src="{{ asset('assets/js/jquery/jquery.js') }}"></script>
    <script src="{{ asset('js/parsley/parsley.min.js') }}"></script>
    {{-- <script src="{{ asset('assets/vendor/flatpickr/flatpickr.js') }}"></script> --}}
    <script src="{{ asset('assets/vendor/bootstrap-5.3.5/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/js/index.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery-ui/jquery-ui.js') }}"></script>
    <script src="{{ asset('assets/vendor/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('assets/js/moment/moment.js') }}"></script>

    @yield('scripts')
</body>

</html>
