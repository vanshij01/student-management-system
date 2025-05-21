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

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-5.3.5/css/bootstrap.min.css') }}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <!-- Icon Library -->
    <link rel="stylesheet"
        href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">

    <link rel="stylesheet" href="{{ asset('assets/css/style_backend.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/fonts/stylesheet.css') }}" />
    {{-- <link rel="stylesheet" href="{{ asset('assets/vendor/flatpickr/flatpickr.css') }}" /> --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/jquery-ui/jquery-ui.css') }}" />
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css" /> --}}

    <!-- DataTables CSS (v1.13.6) -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">


    @yield('styles')
    <style>
        .menu-item {
            transition: height 0.4s ease;
            overflow: hidden;
            height: 0;
            list-style-type: none;
            padding-left: 0;
            margin: 0;
        }

        .menu-item li {
            position: relative;
        }

        .menu-item li a::before {
            content: '';
            display: inline-block;
            background: #898989;
            width: 8px;
            height: 8px;
            border-radius: 100%;
            margin-right: 10px;
        }

        .menu-item li a.active::before,
        .menu-item li a:hover::before {
            background: #fff;
        }

        .menu-item li a.nav-link {
            padding-left: 60px;
        }

        .menu.active > .nav-link{
background: #FFB42D;
    border-radius: 0.5rem;
    color: #fff;
        }
    </style>
</head>

<body>
    <div class="overlay" id="sidebarOverlay"></div>
    <div class="admin-dashboard-main">
        <div class="row m-0 p-0">
            <!-- Sidebar -->
            @include('backend.layouts.navigation')

            <!-- Main content -->
            <div class="col-md-10 main-content">

                @yield('content')

                <!-- Footer -->
                @include('backend.layouts.footer')
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/jquery/jquery.js') }}"></script>
    <script src="{{ asset('js/parsley/parsley.min.js') }}"></script>
    {{-- <script src="{{ asset('assets/vendor/flatpickr/flatpickr.js') }}"></script> --}}
    <script src="{{ asset('assets/vendor/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery-ui/jquery-ui.js') }}"></script>
    <script src="{{ asset('assets/js/moment/moment.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    {{-- <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script> --}}
    <!-- DataTables JS (v1.13.6 + extensions) -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



    <script src="{{ asset('assets/js/index.js') }}"></script>
    @yield('scripts')
    <script>
    const menuButtons = document.querySelectorAll(".menu");

    menuButtons.forEach((btn, index) => {
        const panel = btn.querySelector(".menu-item");

        btn.querySelector(".nav-link").addEventListener("click", (e) => {
            e.preventDefault();
            const isOpen = panel.style.height && panel.style.height !== "0px";

            if (isOpen) {
                panel.style.height = "0px";
                btn.classList.remove("active");
            } else {
                panel.style.height = panel.scrollHeight + "px";
                btn.classList.add("active");
            }
        });
    });

    // Auto-expand if active link
    window.addEventListener("DOMContentLoaded", () => {
        const currentURL = window.location.href;

        document.querySelectorAll(".menu").forEach((menu) => {
            const panel = menu.querySelector(".menu-item");
            let hasActive = false;

            menu.querySelectorAll(".nav-link").forEach(link => {
                const href = link.getAttribute("href");
                if (currentURL.includes(href)) {
                    link.classList.add("active");
                    hasActive = true;
                }
            });

            if (hasActive) {
                panel.style.height = panel.scrollHeight + "px";
                menu.classList.add("active");
            }
        });
    });
</script>

</body>

</html>
