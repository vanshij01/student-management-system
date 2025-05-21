<nav class="layout-navbar navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="mdi mdi-menu mdi-24px"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center d-flex justify-content-between" id="navbar-collapse">
        {{-- @if (Route::current()->getName() == 'dashboard') --}}
        <!-- Welcome Text -->
        <div class="navbar-nav align-items-center">
            {{-- <a href="{{ route('search-detail') }}" class="app-brand-link">
                    <img src="{{ asset('images/header-logo.svg') }}" height="50" alt="">
                </a> --}}
            <div class="{{-- nav-item navbar-search-wrapper mb-0 mt-4 --}}">
                <h3 class="text-dark m-0 welcome-text">Welcome {{ auth()->user()->name }} !</h3>
            </div>
        </div>
        <!-- /Welcome Text -->
        {{-- @endif --}}
        <ul class="navbar-nav flex-row align-items-center {{-- ms-auto --}}">
            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown user-navbar m-0">
                <a class="nav-link dropdown-toggle {{-- hide-arrow --}}" href="javascript:void(0);"
                    data-bs-toggle="dropdown">

                    <div class="text-dark">
                        Admin
                        {{-- <img src="{{ asset('user-profile/'. Auth::user()->picture) }}" alt
                            class="w-px-40 h-auto rounded-circle" /> --}}
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end ul-menu">
                    <li>
                        <a class="dropdown-item" href="{{ route('profile.edit', auth()->user()->id) }}">
                            <i class="mdi mdi-account-outline me-2"></i>
                            <span class="align-middle">My Profile</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('profile.updatePassword') }}">
                            <i class="mdi mdi-key-outline me-2"></i>
                            <span class="align-middle">Change Password</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();  document.getElementById('logout-form').submit();">
                            <i class="mdi mdi-logout me-2"></i>
                            <span class="align-middle">Log Out</span>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </a>

                        {{-- <a class="dropdown-item" href="auth-login-cover.html" target="_blank">
                            <i class="mdi mdi-logout me-2"></i>
                            <span class="align-middle">Log Out</span>
                        </a> --}}
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>

    <!-- Search Small Screens -->
    <div class="navbar-search-wrapper search-input-wrapper d-none">
        <input type="text" class="form-control search-input container-xxl border-0" placeholder="Search..."
            aria-label="Search..." />
        <i class="mdi mdi-close search-toggler cursor-pointer"></i>
    </div>
</nav>
