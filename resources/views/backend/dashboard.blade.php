@extends('backend.layouts.app')
@section('title', 'Dashboard')
@section('styles')
    <style>
        .highcharts-credits {
            display: none;
        }

        #women-chart {
            width: 100%;
            height: 100%;
            min-height: 300px;
            /* Or adjust as needed */
            position: relative;
        }

        .chart-wrapper {
            width: 100%;
            height: 100%;
        }

        .hostel_count_wrap {
            display: flex;
            flex-direction: row;
            align-items: stretch;
            gap: 1rem;
        }

        .card_box_wrap {
            height: 100%;
        }

        .card_box_wrap .chart-wrapper {
            flex: 1;
        }
    </style>
@endsection
@section('content')
    <div class="dashboard-header-container">
        <div class="d-flex d-board-inr">
            <button class="sidebar-toggle" id="sidebarToggle"><i class="bi bi-list"></i></button>
            <div class="sklps-mb-logo">
                <img src="/assets/images/sklps-logo.png" alt="Logo" class="img-fluid">
            </div>
            <h3 class="dashboard-header">Welcome to Dashboard, {{ auth()->user()->name }}!</h3>
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
                    <div class="stat_title font-18">Admissions Confirmed</div>
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
                        <div class="stat_title font-18">Total Donors</div>
                        <div class="stat-value font-36" id="total_donors"></div>
                    </div>
                    <div class="stat-info">
                        <div class="stat_title font-18">First Half Pending</div>
                        <div class="stat-value font-36" id="first_half_pending"></div>
                    </div>
                    <div class="stat-info">
                        <div class="stat_title font-18">Second Half Pending</div>
                        <div class="stat-value font-36" id="second_half_pending"></div>
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
                    <div class="chart-wrapper" id="women-chart">
                        {{-- <img src="{{ asset('assets/images/women-chart.png') }}"> --}}
                    </div>
                    <div class="hostel-beds-info">
                        <div class="hostel-beds-count">
                            <div class="stat-icon red"><img src="{{ asset('assets/images/women-bed.png') }}"></div>
                            <div class="stat-info">
                                <div class="stat_title font-18 ">Total Bed Capacity</div>
                                <div class="stat-value font-36 women_all_beds"></div>
                            </div>
                        </div>
                        <div class="allocated-beds-info d-flex">
                            <div class="stat-info">
                                <div class="stat_title font-18 "><span class="solid_marker marker_blue"></span>
                                    <span class="spn_txt">Allocated Beds</span>
                                </div>
                                <div class="stat-value font-36 women_allocated_beds"></div>
                            </div>
                            <div class="stat-info">
                                <div class="stat_title font-18"> <span class="solid_marker marker_orange"></span>
                                    <span class="spn_txt">Available Beds</span>
                                </div>
                                <div class="stat-value font-36 women_available_beds"></div>
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
                    <div class="chart-wrapper" id="men-chart">
                        {{-- <img src="{{ asset('assets/images/women-chart.png') }}"> --}}
                    </div>
                    <div class="hostel-beds-info">
                        <div class="hostel-beds-count">
                            <div class="stat-icon red"><img src="{{ asset('assets/images/mens-bed.png') }}"></div>
                            <div class="stat-info">
                                <div class="stat_title font-18">Total Bed Capacity</div>
                                <div class="stat-value font-36 men_all_beds"></div>
                            </div>
                        </div>
                        <div class="allocated-beds-info d-flex">
                            <div class="stat-info">
                                <div class="stat_title font-18 "><span class="solid_marker marker_blue"></span>
                                    <span class="spn_txt">Allocated Beds</span>
                                </div>
                                <div class="stat-value font-36 men_allocated_beds"></div>
                            </div>
                            <div class="stat-info">
                                <div class="stat_title font-18 "> <span class="solid_marker marker_orange"></span>
                                    <span class="spn_txt">Available Beds</span>
                                </div>
                                <div class="stat-value font-36 men_available_beds"></div>
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
                    <div class="chart-wrapper" id="job-chart">
                        {{-- <img src="{{ asset('assets/images/women-chart.png') }}"> --}}
                    </div>
                    <div class="hostel-beds-info">
                        <div class="hostel-beds-count">
                            <div class="stat-icon red"><img src="/assets/images/job-beds.png"></div>
                            <div class="stat-info">
                                <div class="stat_title font-18">Total Bed Capacity</div>
                                <div class="stat-value font-36 job_all_beds"></div>
                            </div>
                        </div>
                        <div class="allocated-beds-info d-flex">
                            <div class="stat-info">
                                <div class="stat_title font-18 "><span class="solid_marker marker_blue"></span><span
                                        class="spn_txt">Allocated Beds</span></div>
                                <div class="stat-value font-36 job_allocated_beds"></div>
                            </div>
                            <div class="stat-info">
                                <div class="stat_title font-18 "><span class="solid_marker marker_orange"></span><span
                                        class="spn_txt">Available Beds</span></div>
                                <div class="stat-value font-36 job_available_beds"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="{{ asset('js/dashboard/dashboard.js') }}"></script>
    <script></script>
@endsection
