@extends('backend.layouts.app')
@section('title', 'Leave Listing')
@section('styles')
@endsection
@section('content')
    <div class="dashboard-header-container">
        <div class="d-flex d-board-inr">
            <button class="sidebar-toggle" id="sidebarToggle"><i class="bi bi-list"></i></button>
            <div class="sklps-mb-logo">
                <img src="/assets/images/sklps-logo.png" alt="Logo" class="img-fluid">
            </div>
            <h3 class="dashboard-header">Leaves</h3>
        </div>

        @php
            $chk = \App\Models\Permission::checkCRUDPermissionToUser('Leave', 'create');

            if ($chk) {
                echo "<a href='" . route('leave.create') . "' class='btn primary_btn add_btn'>Add</a>";
            }
        @endphp

    </div>
    <form>
        <div class="admission-filter-bar">
            <input type="text" class="form-control" name="from" value="{{ old('from') }}" id="from"
                placeholder="from" autocomplete="off">
            <input type="text" class="form-control" name="to" value="{{ old('to') }}" id="to"
                placeholder="to" autocomplete="off">
            <select class="form-select select2" name="student_id" id="student_id" aria-label="Default select example"
                data-placeholder="Select Student">
                <option value="" selected>Select Student</option>
                @foreach ($students as $student)
                    <option value="{{ $student->id }}">{{ $student->full_name }}</option>
                @endforeach
            </select>
            <select class="form-select select2" name="leave_status" id="leave_status" aria-label="Default select example"
                data-placeholder="Select Leave Status">
                <option value="" selected>Select Leave Status</option>
                <option @if (old('leave_status') == 'Pending') selected @endif value="Pending">Pending</option>
                <option @if (old('leave_status') == 'Rejected') selected @endif value="Rejected">Rejected</option>
                <option @if (old('leave_status') == 'Approved') selected @endif value="Approved">Approved</option>
            </select>
            <button class="primary_btn" type="button" id="filter" name="filter">Filter</button>
            <button class="secondary_btn" type="button" name="reset" id="reset">Reset</button>
        </div>
    </form>
    <div class="table_content_wrapper">
        <div class="data_table_wrap">
            <table id="leave_table" class="table table-bordered align-middle" style="width: 100%">
                <thead class="table-light">
                    <tr>
                        <th></th>
                        <th>Action</th>
                        <th>Sr. No.</th>
                        <th>Name</th>
                        <th>Hostel</th>
                        <th>Room</th>
                        <th>Leave From</th>
                        <th>Leave To</th>
                        <th>Approved By</th>
                        <th>Leave Status</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <input type="hidden" id="isSuperAdmin" value="{{ \App\Models\Permission::isSuperAdmin() }}">
    <input type="hidden" id="readCheck" value="{{ \App\Models\Permission::checkCRUDPermissionToUser('Leave', 'read') }}">
    <input type="hidden" id="updateCheck"
        value="{{ \App\Models\Permission::checkCRUDPermissionToUser('Leave', 'update') }}">
@endsection
@section('scripts')
    <script src="{{ asset('js/leave/leave.js') }}"></script>
@endsection
