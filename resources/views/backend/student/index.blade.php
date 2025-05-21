@extends('backend.layouts.app')
@section('title', 'Student Listing')
@section('styles')
@endsection
@section('content')
    <div class="dashboard-header-container">
        <div class="d-flex d-board-inr">
            <button class="sidebar-toggle" id="sidebarToggle"><i class="bi bi-list"></i></button>
            <div class="sklps-mb-logo">
                <img src="/assets/images/sklps-logo.png" alt="Logo" class="img-fluid">
            </div>
            <h3 class="dashboard-header">Students</h3>
        </div>

        @php
            $chk = \App\Models\Permission::checkCRUDPermissionToUser('Student', 'create');

            if ($chk) {
                echo "<a href='" . route('student.create') . "' class='btn primary_btn add_btn'>Add</a>";
            }
        @endphp

    </div>
    <form>
        <div class="admission-filter-bar">
            <select class="select2 form-select" id="gender" name="gender" data-placeholder="Select Gender">
                <option value="">Select Gender</option>
                <option value="girl">Girl</option>
                <option value="boy">Boy</option>
            </select>
            <select class="form-select select2" name="country_id" id="country_id" aria-label="Default select example"
                data-placeholder="Select Country">
                <option value="" selected>Select Country</option>
                @foreach ($countries as $country)
                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                @endforeach
            </select>
            <button class="primary_btn" type="button" id="filter" name="filter">Filter</button>
            <button class="secondary_btn" type="button" name="reset" id="reset">Reset</button>
        </div>
    </form>
    <div class="table_content_wrapper">
        <div class="data_table_wrap">
            <table id="student_table" class="table table-bordered align-middle" style="width: 100%">
                <thead class="table-light">
                    <tr>
                        <th></th>
                        <th>Action</th>
                        <th>Sr. No.</th>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Email</th>
                        <th>Mobile Number</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <input type="hidden" id="isSuperAdmin" value="{{ \App\Models\Permission::isSuperAdmin() }}">
    <input type="hidden" id="readCheck" value="{{ \App\Models\Permission::checkCRUDPermissionToUser('Student', 'read') }}">
    <input type="hidden" id="updateCheck"
        value="{{ \App\Models\Permission::checkCRUDPermissionToUser('Student', 'update') }}">
@endsection
@section('scripts')
    <script src="{{ asset('js/student/student.js') }}"></script>
@endsection
