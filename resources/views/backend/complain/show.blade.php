@extends('backend.layouts.app')
@section('title', 'View Complain')
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
            <h5 class="card-title m-0 me-2 text-white d-none d-md-block">View Complain Details</h5>
            <h3 class="card-title m-0 me-2 text-white d-block d-md-none">View Complain Details</h3>

            <div class="d-flex gap-2 mt-4 mt-md-0">
                <button type="button" class="btn secondary_btn back">Back</button>
                <button type="button" class="btn secondary_btn edit" data-id="{{ $complain->id }}">Update</button>
            </div>
        </div>

        <div class="card-body py-2">
            <div class="row">
                <div class="col-lg-3 col-md-4 col-12 border-right">
                    <h6>Student Name</h6>
                    <label>{{ $complain->student->full_name }}</label>
                </div>
                <hr class="responsive_hr my-1">
                <div class="col-lg-3 col-md-4 col-12 border-right">
                    <h6>Email</h6>
                    <label>{{ $complain->student->email }}</label>
                </div>
                <hr class="responsive_hr my-1">
                <div class="col-lg-3 col-md-4 col-12 border-right border-right-md-none">
                    <h6>Mobile number</h6>
                    <label>{{ $complain->student->phone }}</label>
                </div>
                <hr class="responsive_hr my-1">
                <hr class="my-1 d-none d-md-block d-lg-none">
                <div class="col-lg-3 col-md-4 col-12 border-right-md-block">
                    <h6>Message</h6>
                    <label>{{ $complain->message }}</label>
                </div>
                <hr class="my-1 d-block d-md-none d-lg-block">
                <div class="col-lg-3 col-md-4 col-12 border-right">
                    <h6>Type</h6>
                    <label>
                        @switch($complain->type)
                            @case(1)
                                Technical
                            @break

                            @case(2)
                                System
                            @break

                            @case(3)
                                Management
                            @break

                            @default
                        @endswitch
                    </label>
                </div>
                <hr class="responsive_hr my-1">
                <div class="col-lg-3 col-md-4 col-12 border-right border-right-md-none">
                    <h6>Status</h6>
                    <label>
                        @switch($complain->status)
                            @case(1)
                                Pending
                            @break

                            @case(2)
                                Open
                            @break

                            @case(3)
                                In Progress
                            @break

                            @case(4)
                                Completed
                            @break

                            @default
                        @endswitch
                    </label>
                </div>
                <hr class="responsive_hr my-1">
                <hr class="my-1 d-none d-md-block d-lg-none">
                <div class="col-lg-3 col-md-4 col-12 border-right">
                    <h6>Complain Document</h6>
                    @if (!empty($complain->document))
                        <label><a href="{{ asset('uploads/' . $complain->document) }}" target="_blank">Complain
                                Document</a></label>
                    @else
                        <label>-</label>
                    @endif
                </div>
                <hr class="responsive_hr my-1">
                <div class="col-lg-3 col-md-4 col-12 border-right-md-block">
                    <h6>Date</h6>
                    <label>{{ date('d/m/Y', strtotime($complain->created_at)) }}</label>
                </div>
                <hr class="my-1 d-block d-md-none d-lg-block">
                <div class="col-lg-3 col-md-4 col-12">
                    <h6>Admin Comment</h6>
                    <label>{{ $complain->admin_comment }}</label>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/complain/complain.js') }}"></script>
@endsection
