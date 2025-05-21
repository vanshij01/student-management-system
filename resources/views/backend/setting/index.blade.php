@extends('backend.layouts.app')
@section('title', 'Setting Listing')
@section('styles')
    <style>
        h6 {
            margin: 10px;
        }

        .col-lg-3.col-md-4 label {
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

        .responsive_hr {
            display: none;
        }

        @media screen and (max-width: 768px) {
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
            }

            .responsive_hr {
                display: block;
            }

            .border-right,
            .border-right-md-block {
                border: none;
            }
        }
    </style>
@endsection
@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between py-2">
            <h5 class="card-title m-0 me-2 text-white">Settings</h5>
            @php
                $chk = \App\Models\Permission::checkCRUDPermissionToUser('Setting', 'create');
            @endphp
            @if ($chk)
                <button type="button" class="btn secondary_btn edit">Update</button>
            @endif
        </div>

        @if (session('message'))
            <div class="alert alert-{{ session('status') }} alert-dismissible fade show" role="alert">
                <strong>{{ session('message') }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="card-body py-1">
            <div class="row">
                <div class="col-lg-3 col-md-4 col-12 border-right">
                    <h6>New Admission Date</h6>
                    <label>{{ date('d/m/Y', strtotime($newDate)) }}</label>
                </div>
                <hr class="responsive_hr my-1">
                <div class="col-lg-3 col-md-4 col-12 border-right">
                    <h6>Old Admission Date</h6>
                    <label>{{ date('d/m/Y', strtotime($oldDate)) }}</label>
                </div>
                <hr class="responsive_hr my-1">
                <div class="col-lg-3 col-md-4 col-12 border-right border-right-md-none">
                    <h6>New Admission Label</h6>
                    <label>{{ $newLabel }}</label>
                </div>
                <hr class="responsive_hr my-1">
                <hr class="my-1 d-none d-md-block d-lg-none">
                <div class="col-lg-3 col-md-4 col-12 border-right-md-block">
                    <h6>Old Admission Label</h6>
                    <label>{{ $oldLabel }}</label>
                </div>
                <hr class="my-1 d-block d-md-none d-lg-block">
                <div class="col-lg-3 col-md-4 col-12 border-right">
                    <h6>Start Time</h6>
                    <label>{{ $startAttendance }}</label>
                </div>
                <hr class="responsive_hr my-1">
                <div class="col-lg-3 col-md-4 col-12 border-right border-right-md-none">
                    <h6>End Time</h6>
                    <label>{{ $endAttendance }}</label>
                </div>
                <hr class="responsive_hr my-1">
                <hr class="my-1 d-none d-md-block d-lg-none">
                <div class="col-lg-3 col-md-4 col-12 border-right">
                    <h6>Last Updated By</h6>
                    <label>{{ $user->name ?? '' }}</label>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="isSuperAdmin" value="{{ \App\Models\Permission::isSuperAdmin() }}">
    <input type="hidden" id="readCheck"
        value="{{ \App\Models\Permission::checkCRUDPermissionToUser('Setting', 'read') }}">
    <input type="hidden" id="updateCheck"
        value="{{ \App\Models\Permission::checkCRUDPermissionToUser('Setting', 'update') }}">
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('.edit').on('click', function() {
                location.href = "/setting/update";
            });

            setTimeout(function() {
                $('.alert').fadeOut('fast');
            }, 3000);
        });
    </script>
@endsection
