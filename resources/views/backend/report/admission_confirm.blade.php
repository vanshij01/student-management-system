@extends('backend.layouts.app')
@section('title', 'Admission Listing')
@section('styles')
@endsection
@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title m-0 me-2 text-secondary">Admission</h5>
        </div>
        @if (session('message'))
            <div class="alert alert-{{ session('status') }} alert-dismissible fade show" role="alert">
                <strong>{{ session('message') }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="card-body">
            <div class="card-datatable table-responsive pt-0">
                <table class="datatables-basic table table-bordered table-striped" id="admission_table">
                    <div id="overlay"></div>
                    <thead>
                        <tr>
                            <th></th>
                            <th>Student Name</th>
                            <th>Gender</th>
                            <th>Mobile Number</th>
                            <th>Email Id</th>
                            <th>Village</th>
                            <th>Father's Name</th>
                            <th>Father's No</th>
                            <th>Qualification</th>
                            <th>Semester</th>
                            <th>Collage Name/Institute Name</th>
                            <th>Admission Date</th>
                            <th>Admission Old/New</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/report/admission.js') }}"></script>
@endsection
