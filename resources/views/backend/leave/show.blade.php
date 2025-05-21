@extends('backend.layouts.app')
@section('title', 'View Leave')
@section('styles')
    <style>
        h6 {
            margin: 10px 0;
        }

        .col-lg-4.col-md-4 label {
            margin-left: 8px;
            display: flex;
            align-items: center;
        }

        .card-body hr {
            margin-top: 15px;
            border: 1px solid #D8D8DD
        }

        .border-right {
            border-right: 1px solid #D8D8DD;
        }

        .nav-item {
            margin: 8px 10px 0 0 !important;
        }

        .responsive_hr {
            display: none;
        }

        div.dataTables_wrapper div.col-sm-12 {
            padding: 0 !important;
        }

        .description-container {
            word-wrap: break-word;
            overflow-wrap: break-word;
            max-width: 100%;
            white-space: normal;
        }

        @media screen and (max-width: 768px) {

            .table-responsive {
                overflow: scroll;
            }

            .border-right-md-none {
                border-right: none;
            }

            .border-right-md-block {
                border-right: 1px solid #D8D8DD;
            }
        }

        @media screen and (max-width: 425px) {
            .col-12 {
                display: flex;
                gap: 10px;
                padding: 8px;
            }

            .responsive_hr {
                display: block;
            }

            .border-right,
            .border-right-md-block {
                border: none;
            }

            h6 {
                margin: 3px 0;
            }
        }
    </style>
@endsection
@section('content')
    <div class="card">
        <div class="card-header d-md-flex d-sm-block align-items-center justify-content-between py-md-2">
            <h5 class="card-title m-0 me-2 text-white d-none d-md-block">View Leave Details</h5>
            <h3 class="card-title m-0 me-2 text-white d-block d-md-none">View Leave Details</h3>

            <div class="d-flex gap-2 mt-4 mt-md-0">
                <button type="button" class="btn secondary_btn back">Back</button>
                <button type="button" class="btn secondary_btn edit" data-id="{{ $leave->id }}">Update</button>
            </div>
        </div>

        <div class="card-body py-2">
            <div class="row">
                <div class="col-lg-3 col-md-4 col-12 border-right">
                    <h6>Student Name</h6>
                    <label>{{ $leave->student->full_name }}</label>
                </div>
                <hr class="responsive_hr my-1">
                <div class="col-lg-3 col-md-4 col-12 border-right">
                    <h6>Email</h6>
                    <label>{{ $leave->student->email }}</label>
                </div>
                <hr class="responsive_hr my-1">
                <div class="col-lg-3 col-md-4 col-12 border-right border-right-md-none">
                    <h6>Mobile number</h6>
                    <label>{{ $leave->student->phone }}</label>
                </div>
                <hr class="responsive_hr my-1">
                <hr class="my-1 d-none d-md-block d-lg-none">
                <div class="col-lg-3 col-md-4 col-12 border-right-md-block">
                    <h6>Leave</h6>
                    <label>{{ date('d/m/Y', strtotime($leave->leave_from)) }} -
                        {{ date('d/m/Y', strtotime($leave->leave_to)) }}</label>
                </div>
                <hr class="my-1 d-block d-md-none d-lg-block">
                <div class="col-lg-3 col-md-4 col-12 border-right">
                    <h6>Reason</h6>
                    <label>{{ $leave->reason }}</label>
                </div>
                <hr class="responsive_hr my-1">
                <div class="col-lg-3 col-md-4 col-12 border-right border-right-md-none">
                    <h6>Approve By</h6>
                    <label>{{ $leave->approve_by }}</label>

                </div>
                <hr class="responsive_hr my-1">
                <hr class="my-1 d-none d-md-block d-lg-none">
                <div class="col-lg-3 col-md-4 col-12">
                    <h6>Leave Status</h6>
                    <label>{{ $leave->leave_status }}</label>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/leave/leave.js') }}"></script>
@endsection
