@extends('backend.layouts.app')
@section('title', 'Allocated Students Listing')
@section('styles')
@endsection
@section('content')
    <div class="dashboard-header-container">
        <div class="d-flex d-board-inr">
            <button class="sidebar-toggle" id="sidebarToggle"><i class="bi bi-list"></i></button>
            <div class="sklps-mb-logo">
                <img src="/assets/images/sklps-logo.png" alt="Logo" class="img-fluid">
            </div>
            <h3 class="dashboard-header">Allocated Studentss</h3>
        </div>
    </div>
    <form>
        <div class="admission-filter-bar">
            <select class="form-select select2" name="year" id="year" aria-label="Default select example"
                data-placeholder="Select Year">
                <option value="" selected>Select Year</option>
                @foreach ($yearList as $item)
                    <option @if ($item == date('Y') . '-' . date('Y', strtotime(' +1 year'))) selected="selected" @endif value="{{ $item }}">
                        {{ $item }}</option>
                @endforeach
            </select>
            <select class="form-select select2" name="hostel_id" id="hostel_id" aria-label="Default select example"
                data-placeholder="Select Hostel">
                <option value="" selected>Select Hostel</option>
                @foreach ($hostels as $hostel)
                    <option value="{{ $hostel->id }}">{{ $hostel->hostel_name }}</option>
                @endforeach
            </select>
            <button class="primary_btn" type="button" id="filter" name="filter">Filter</button>
            <button class="secondary_btn" type="button" name="reset" id="reset">Reset</button>
        </div>
    </form>
    <div class="table_content_wrapper">
        <div class="data_table_wrap">
            <table id="allocated_students_table" class="table table-bordered align-middle" style="width: 100%">
                <thead class="table-light">
                    <tr>
                        <th></th>
                        <th>Action</th>
                        <th>Sr. No.</th>
                        <th>Student Name</th>
                        <th>Mobile Number</th>
                        <th>Father Number</th>
                        <th>Mother Number</th>
                        <th>Local Guardian Number</th>
                        <th>Bed Number</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/report/alloted_students.js') }}"></script>
@endsection
