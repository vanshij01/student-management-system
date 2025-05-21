@extends('backend.layouts.app')
@section('title', 'View Apology Letter')
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
            <h5 class="card-title m-0 me-2 text-white d-none d-md-block">View Apology Letter Details</h5>
            <h3 class="card-title m-0 me-2 text-white d-block d-md-none">View Apology Letter Details</h3>

            <div class="d-flex gap-2 mt-4 mt-md-0">
                <button type="button" class="btn secondary_btn back">Back</button>
                <button type="button" class="btn secondary_btn edit" data-id="{{ $apology_letter->id }}">Update</button>
            </div>
        </div>

        <div class="card-body py-2">
            <div class="row">
                <div class="col-lg-3 col-md-4 col-12 border-right">
                    <h6>Student Name</h6>
                    <label>{{ $apology_letter->student->full_name }}</label>
                </div>
                <hr class="responsive_hr my-1">
                <div class="col-lg-3 col-md-4 col-12 border-right">
                    <h6>Subject</h6>
                    <label>{{ $apology_letter->subject }}</label>
                </div>
                <hr class="responsive_hr my-1">
                <div class="col-lg-3 col-md-4 col-12 border-right border-right-md-none">
                    <h6>Letter Content</h6>
                    <label>{{ $apology_letter->letter_content }}</label>
                </div>
                <hr class="responsive_hr my-1">
                <hr class="my-1 d-none d-md-block d-lg-none">
                <div class="col-lg-3 col-md-4 col-12 border-right-md-block">
                    <h6>Note</h6>
                    <label>{{ $apology_letter->note }}</label>
                </div>
                <hr class="my-1 d-block d-md-none d-lg-block">
                <div class="col-lg-3 col-md-4 col-12 border-right">
                    <h6>Date</h6>
                    <label>{{ date('d/m/Y', strtotime($apology_letter->created_at)) }}</label>

                </div>
                <hr class="responsive_hr my-1">
                <div class="col-lg-3 col-md-4 col-12">
                    <h6>Apology Letter</h6>
                    @if (!empty($apology_letter->document))
                        <label><a href="{{ asset('uploads/' . $apology_letter->document) }}" target="_blank">Apology Letter</a></label>
                    @else
                        <label>-</label>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/apology_letter/apology_letter.js') }}"></script>
@endsection
