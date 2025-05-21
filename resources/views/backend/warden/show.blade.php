@extends('backend.layouts.app')
@section('title', 'View Warden')
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

        .nav-pills .nav-link.active {
            background-color: #FFB42D;
        }

        .nav-link,
        .nav-link:focus,
        .nav-link:hover {
            color: #898989;
        }

        .dataTables_filter label input {
            width: auto !important;
        }

        @media screen and (max-width: 768px) {
            .data_table_wrap {
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
    <div class="card mb-2">
        <div class="card-header d-md-flex d-sm-block align-items-center justify-content-between py-md-2">
            <h5 class="card-title m-0 me-2 text-white d-none d-md-block">View Warden Details</h5>
            <h3 class="card-title m-0 me-2 text-white d-block d-md-none">View Warden Details</h3>

            <div class="d-flex gap-2 mt-4 mt-md-0">
                <button type="button" class="btn secondary_btn back">Back</button>
                <button type="button" class="btn secondary_btn edit" data-id="{{ $warden->id }}">Update</button>
            </div>
        </div>
        <div class="card-body py-2">
            <div class="row">
                <div class="col-lg-3 col-md-4 col-12 border-right">
                    <h6>Full Name</h6>
                    <label>{{ $warden->full_name }}</label>
                </div>
                <hr class="responsive_hr my-1">
                <div class="col-lg-3 col-md-4 col-12 border-right">
                    <h6>Contact</h6>
                    <label>{{ $warden->phone }}</label>
                </div>
                <hr class="responsive_hr my-1">
                <div class="col-lg-3 col-md-4 col-12 border-right border-right-md-none">
                    <h6>Email</h6>
                    <label>{{ $warden->email }}</label>
                </div>
                <hr class="responsive_hr my-1">
                <hr class="my-1 d-none d-md-block d-lg-none">
                <div class="col-lg-3 col-md-4 col-12 border-right-md-block">
                    <h6>Date of birth</h6>
                    <label>{{ date('d/m/Y', strtotime($warden->dob)) }}</label>
                </div>
                <hr class="my-1 d-block d-md-none d-lg-block">
                <div class="col-lg-3 col-md-4 col-12 border-right">
                    <h6>Gender</h6>
                    <label>{{ $warden->gender }}</label>
                </div>
                <hr class="responsive_hr my-1">
                <div class="col-lg-3 col-md-4 col-12 border-right border-right-md-none">
                    <h6>Address</h6>
                    <label>{{ $warden->address }}</label>
                </div>
                <hr class="responsive_hr my-1">
                <hr class="my-1 d-none d-md-block d-lg-none">
                <div class="col-lg-3 col-md-4 col-12 border-right">
                    <h6>Experience</h6>
                    <label>{{ $warden->experience }}</label>
                </div>
                <hr class="responsive_hr my-1">
                <div class="col-lg-3 col-md-4 col-12 border-right-md-block">
                    <h6>Qualification</h6>
                    <label>{{ $warden->qualification }}</label>
                </div>
                <hr class="my-1 d-block d-md-none d-lg-block">
                <div class="col-lg-3 col-md-4 col-12">
                    <h6>Status</h6>
                    <label>{{ $warden->status == 1 ? 'Enable' : 'Disable' }}</label>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="nav-align-top mb-4">
                <ul class="nav nav-pills mb-3" role="tablist">
                    <li class="nav-item">
                        <button type="button" class="nav-link active mr-5" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-pills-top-hostel" aria-controls="navs-pills-top-hostel"
                            aria-selected="true">
                            Hostel
                        </button>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="navs-pills-top-hostel" role="tabpanel">
                        <div class="table_content_wrapper">
                            <div class="data_table_wrap">
                                <table class="table" id="hostel_table">
                                    <thead>
                                        <tr>
                                            <th>Sr No.</th>
                                            <th>Name</th>
                                            <th>Location</th>
                                            <th>Contact</th>
                                            <th>Mobile</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        @foreach ($hostels as $key => $hostel)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $hostel->hostel_name }}</td>
                                                <td>
                                                    <p class="description-container m-0">{{ $hostel->location }}</p>
                                                </td>
                                                <td>{{ $hostel->contact_number }}</td>
                                                <td>{{ $hostel->mobile_number }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/warden/warden.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#hostel_table').DataTable();
        });
    </script>
@endsection
