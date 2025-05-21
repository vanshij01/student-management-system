@extends('backend.layouts.app')
@section('title', 'Dashboard')
@section('styles')
    {{-- <style>
        .filter {
            position: fixed;
            top: 12px;
            right: 100px;
            z-index: 1099;
        }

        @media screen and (max-width: 1440px) {
            .table-responsive {
                overflow: scroll;
            }
        }
    </style> --}}
@endsection
@section('content')
    {{-- <div class="filter">
        <select class="form-select select2" name="year" id="year" data-placeholder="Select Year">
            <option value="" selected>Select Year</option>
            @foreach ($yearList as $item)
                <option value="{{ $item }}">{{ $item }}</option>
            @endforeach
        </select>
    </div>
    <h4 class="py-3 mb-4">Dashboard</h4>
    <div class="row">
        <div class="col-sm-6 col-lg-4 mb-4">
            <a href="{{ route('admission.index') }}">
                <div class="card card-border-shadow-primary h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2 pb-1">
                            <div class="avatar me-2">
                                <span class="avatar-initial rounded bg-label-primary"><i
                                        class="mdi mdi-account-group mdi-20px"></i></span>
                            </div>
                            <h4 class="ms-1 mb-0 display-6" id="total_admission"></h4>
                        </div>
                        <p class="mb-0 text-heading">Total Admission</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-lg-4 mb-4">
            <a href="{{ route('student.index') }}">
                <div class="card card-border-shadow-primary h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2 pb-1">
                            <div class="avatar me-2">
                                <span class="avatar-initial rounded bg-label-primary"><i
                                        class="mdi mdi-account-group mdi-20px"></i></span>
                            </div>
                            <h4 class="ms-1 mb-0 display-6" id="total_student"></h4>
                        </div>
                        <p class="mb-0 text-heading">Total Students</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-lg-4 mb-4">
            <a href="{{ route('hostel.index') }}">
                <div class="card card-border-shadow-primary h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2 pb-1">
                            <div class="avatar me-2">
                                <span class="avatar-initial rounded bg-label-primary"><i
                                        class="mdi mdi-account-group mdi-20px"></i></span>
                            </div>
                            <h4 class="ms-1 mb-0 display-6" id="total_hostel"></h4>
                        </div>
                        <p class="mb-0 text-heading">Total Hostel</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-lg-4 mb-4">
            <a href="{{ route('room.index') }}">
                <div class="card card-border-shadow-primary h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2 pb-1">
                            <div class="avatar me-2">
                                <span class="avatar-initial rounded bg-label-primary"><i
                                        class="mdi mdi-account-group mdi-20px"></i></span>
                            </div>
                            <h4 class="ms-1 mb-0 display-6" id="total_room"></h4>
                        </div>
                        <p class="mb-0 text-heading">Total Room</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-lg-4 mb-4">
            <a href="{{ route('bed.index') }}">
                <div class="card card-border-shadow-primary h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2 pb-1">
                            <div class="avatar me-2">
                                <span class="avatar-initial rounded bg-label-primary"><i
                                        class="mdi mdi-account-group mdi-20px"></i></span>
                            </div>
                            <h4 class="ms-1 mb-0 display-6" id="total_bed"></h4>
                        </div>
                        <p class="mb-0 text-heading">Total Bed</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-lg-4 mb-4">
            <a href="{{ route('course.index') }}">
                <div class="card card-border-shadow-primary h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2 pb-1">
                            <div class="avatar me-2">
                                <span class="avatar-initial rounded bg-label-primary"><i
                                        class="mdi mdi-account-group mdi-20px"></i></span>
                            </div>
                            <h4 class="ms-1 mb-0 display-6" id="total_course"></h4>
                        </div>
                        <p class="mb-0 text-heading">Total Course</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-6 mb-4">
            <div class="card">
                <h4 class="card-header">Student</h4>
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table class="table" id="hostelTable" style="overflow-x: auto;">
                            <thead>
                                <tr>
                                    <th>SR No.</th>
                                    <th>Hostel Name</th>
                                    <th>Boys</th>
                                    <th>Girls</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot></tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 mb-4">
            <div class="card">
                <h4 class="card-header">Available Bed</h4>
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table class="table" id="availableBedTable" style="overflow-x: auto;">
                            <thead>
                                <tr>
                                    <th>SR No.</th>
                                    <th>Hostel Name</th>
                                    <th>Bed</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot></tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card">
                <h4 class="card-header">Admission Status</h4>
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table class="table" id="admissionStatusTable" style="overflow-x: auto;">
                            <thead>
                                <tr>
                                    <th>SR No.</th>
                                    <th>Admission Status</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot></tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}






    <div class="dashboard-header-container">
        <div class="d-flex d-board-inr">
            <button class="sidebar-toggle" id="sidebarToggle"><i class="bi bi-list"></i></button>
            <div class="sklps-mb-logo">
                <img src="/assets/images/sklps-logo.png" alt="Logo" class="img-fluid">
            </div>
            <h3 class="dashboard-header">Welcome to Dashboard, Kishan!</h3>
        </div>


        <div class="year-switcher">
            <button class="nav-btn" id="prevYear"><i class="bi bi-chevron-left"></i></button>
            <h6 class="year-label m-0" id="currentYear"></h6>
            <button class="nav-btn" id="nextYear"><i class="bi bi-chevron-right"></i></button>
        </div>

    </div>

    <div class="overview-section">
        <div class="card-header-wrap">
            <h3 class="card-header-title">Overview</h3>
        </div>

        <div class="overview-sec-inr">
            <div class="overview-stat-box">
                <div class="stat-icon blue">
                    <img src="{{ asset('assets/images/total-admission.png') }}">
                </div>
                <div class="stat-info">
                    <div class="stat_title font-18 ">Total Admissions</div>
                    <div class="stat-value font-36" id="total_admission"></div>
                </div>
            </div>
            <div class="overview-stat-box">
                <div class="stat-icon green"><img src="{{ asset('assets/images/admissions-confirmed.png') }}"></div>
                <div class="stat-info">
                    <div class="stat_title font-18 ">Admissions Confirmed</div>
                    <div class="stat-value font-36" id="confirm_admission"></div>
                </div>
            </div>
            <div class="overview-stat-box">
                <div class="stat-icon red"><img src="{{ asset('assets/images/rejected-admissions.png') }}"></div>
                <div class="stat-info">
                    <div class="stat_title font-18 ">Rejected Admissions</div>
                    <div class="stat-value font-36" id="rejected_admission"></div>
                </div>
            </div>
            <div class="overview-stat-box">
                <div class="stat-icon orange"><img src="{{ asset('assets/images/pending-admissions.png') }}"></div>
                <div class="stat-info">
                    <div class="stat_title font-18 ">Pending Admissions</div>
                    <div class="stat-value font-36" id="pending_admission"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Donation and Complaint Summary -->
    <div class=" summary_main_wrap row mb-4">
        <div class=" donation_box cnm_summary_box col-md-6">
            <div class="card_box_wrap">
                <div class="card-header-wrap">
                    <h3 class="card-header-title">Donation Summary</h3>
                </div>
                <div class="summary_info_cnt">
                    <div class="stat-info">
                        <div class="stat_title font-18 ">Total Donors</div>
                        <div class="stat-value font-36" id="total_donors"></div>
                    </div>
                    <div class="stat-info">
                        <div class="stat_title font-18 ">First Half Pending</div>
                        <div class="stat-value font-36">20</div>
                    </div>
                    <div class="stat-info">
                        <div class="stat_title font-18 ">Second Half Pending</div>
                        <div class="stat-value font-36">50</div>
                    </div>
                </div>

            </div>
        </div>
        <div class="complain_box cnm_summary_box col-md-6">
            <div class="card_box_wrap">
                <div class="card-header-wrap">
                    <h3 class="card-header-title">Complain Summary</h3>
                </div>
                <div class="summary_info_cnt">
                    <div class="stat-info">
                        <div class="stat_title font-18 ">Total Complains</div>
                        <div class="stat-value font-36" id="total_complain"></div>
                    </div>
                    <div class="stat-info">
                        <div class="stat_title font-18 ">Solved Complains</div>
                        <div class="stat-value font-36" id="solved_complain"></div>
                    </div>
                    <div class="stat-info">
                        <div class="stat_title font-18 ">Unsolved Complains</div>
                        <div class="stat-value font-36" id="unsolved_complain"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Hostel Charts -->
    <div class="hostel_summary_block row">
        <div class="col-6">
            <div class="card_box_wrap">
                <div class="card-header-wrap">
                    <h3 class="card-header-title">Women Hostel Overview</h3>
                </div>
                <div class="hostel_count_wrap">
                    <div class="chart-wrapper">
                        <img src="{{ asset('assets/images/women-chart.png') }}">
                    </div>
                    <div class="hostel-beds-info">
                        <div class="hostel-beds-count">
                            <div class="stat-icon red"><img src="{{ asset('assets/images/women-bed.png') }}"></div>
                            <div class="stat-info">
                                <div class="stat_title font-18 ">Total Bed Capacity</div>
                                <div class="stat-value font-36">2800</div>
                            </div>
                        </div>
                        <div class="allocated-beds-info d-flex">
                            <div class="stat-info">
                                <div class="stat_title font-18 "><span class="solid_marker marker_blue"></span>
                                    <span class="spn_txt">Allocated Beds</span>
                                </div>
                                <div class="stat-value font-36">2640</div>
                            </div>
                            <div class="stat-info">
                                <div class="stat_title font-18 "> <span class="solid_marker marker_orange"></span>
                                    <span class="spn_txt">Allocated Beds</span>
                                </div>
                                <div class="stat-value font-36">160</div>
                            </div>
                        </div>
                    </div>

                </div>


            </div>
        </div>
        <div class="col-6">
            <div class="card_box_wrap">
                <div class="card-header-wrap">
                    <h3 class="card-header-title">Men Hostel Overview</h3>
                </div>
                <div class="hostel_count_wrap">
                    <div class="chart-wrapper">
                        <img src="{{ asset('assets/images/women-chart.png') }}">
                    </div>
                    <div class="hostel-beds-info">
                        <div class="hostel-beds-count">
                            <div class="stat-icon red"><img src="{{ asset('assets/images/mens-bed.png') }}"></div>
                            <div class="stat-info">
                                <div class="stat_title font-18 ">Total Bed Capacity</div>
                                <div class="stat-value font-36">2800</div>
                            </div>
                        </div>
                        <div class="allocated-beds-info d-flex">
                            <div class="stat-info">
                                <div class="stat_title font-18 "><span class="solid_marker marker_blue"></span>
                                    <span class="spn_txt">Allocated Beds</span>
                                </div>
                                <div class="stat-value font-36">2640</div>
                            </div>
                            <div class="stat-info">
                                <div class="stat_title font-18 "> <span class="solid_marker marker_orange"></span>
                                    <span class="spn_txt">Available Beds</span>
                                </div>
                                <div class="stat-value font-36">160</div>
                            </div>
                        </div>
                    </div>

                </div>


            </div>
        </div>
        <div class="col-6">
            <div class="card_box_wrap">
                <div class="card-header-wrap">
                    <h3 class="card-header-title">Job/Intership Hostel Overview</h3>
                </div>
                <div class="hostel_count_wrap">
                    <div class="chart-wrapper">
                        <img src="{{ asset('assets/images/women-chart.png') }}">
                    </div>
                    <div class="hostel-beds-info">
                        <div class="hostel-beds-count">
                            <div class="stat-icon red"><img src="/assets/images/job-beds.png"></div>
                            <div class="stat-info">
                                <div class="stat_title font-18 ">Total Bed Capacity</div>
                                <div class="stat-value font-36">2800</div>
                            </div>
                        </div>
                        <div class="allocated-beds-info d-flex">
                            <div class="stat-info">
                                <div class="stat_title font-18 "><span class="solid_marker marker_blue"></span><span
                                        class="spn_txt">Allocated Beds</span></div>
                                <div class="stat-value font-36">2640</div>
                            </div>
                            <div class="stat-info">
                                <div class="stat_title font-18 "><span class="solid_marker marker_orange"></span><span
                                        class="spn_txt">Available Beds</span></div>
                                <div class="stat-value font-36">160</div>
                            </div>
                        </div>
                    </div>

                </div>


            </div>
        </div>

    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/dashboard/dashboard.js') }}"></script>
<script>

</script>

@endsection
