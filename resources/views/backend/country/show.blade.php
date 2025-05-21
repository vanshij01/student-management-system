@extends('backend.layouts.app')
@section('title', 'View Country')
@section('styles')
    <style>
        h6 {
            margin: 10px;
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
            <h5 class="card-title m-0 me-2 text-secondary d-none d-md-block">View Country Details</h5>
            <h3 class="card-title m-0 me-2 text-secondary d-block d-md-none">View Country Details</h3>

            <div class="d-flex gap-2 mt-4 mt-md-0">
                <a href="{{ route('country.index') }}" class="btn btn-primary waves-effect waves-light addButton">Back</a>
                <a href="{{ route('country.edit', $country->id) }}"
                    class="btn btn-primary waves-effect waves-light addButton">Update</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-12 border-right">
                    <h6>Name</h6>
                    <label>{{ $country->name }}</label>
                </div>
                <hr class="responsive_hr my-1">
                <div class="col-lg-4 col-md-4 col-12 border-right">
                    <h6>Status</h6>
                    <label>{{ $country->status == 1 ? 'Enabled' : 'Disabled' }}</label>
                </div>
                <hr class="responsive_hr my-1">
                <div class="col-lg-4 col-md-4 col-12">
                    <h6>Created By</h6>
                    <label>{{ $country->user->name }}</label>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@endsection
