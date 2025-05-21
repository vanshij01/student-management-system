@extends('backend.layouts.app')
@section('title', 'Complain Listing')
@section('styles')
@endsection
@section('content')
    <div class="dashboard-header-container">
        <div class="d-flex d-board-inr">
            <button class="sidebar-toggle" id="sidebarToggle"><i class="bi bi-list"></i></button>
            <div class="sklps-mb-logo">
                <img src="/assets/images/sklps-logo.png" alt="Logo" class="img-fluid">
            </div>
            <h3 class="dashboard-header">Complains</h3>
        </div>

        @php
            $chk = \App\Models\Permission::checkCRUDPermissionToUser('Complain', 'create');

            if ($chk) {
                echo "<a href='" . route('complain.create') . "' class='btn primary_btn add_btn'>Add</a>";
            }
        @endphp
    </div>
    <form>
        <div class="admission-filter-bar">
            <select class="form-select select2" name="complain_by" id="complain_by" aria-label="Default select example"
                data-placeholder="Select Student">
                <option value="" selected>Select Student</option>
                @foreach ($students as $student)
                    <option value="{{ $student->id }}">{{ $student->full_name }}</option>
                @endforeach
            </select>
            <select class="form-select select2" name="type" id="type" aria-label="Default select example"
                data-placeholder="Select Type">
                <option value="" selected>Select Type</option>
                <option @if (old('type') == '1') selected @endif value="1">Technical</option>
                <option @if (old('type') == '2') selected @endif value="2">System</option>
                <option @if (old('type') == '3') selected @endif value="3">Management
                </option>
            </select>
            <select name="status" id="status" class="select2 form-select" data-placeholder="Select Status"
                data-parsley-errors-container="#status_errors" required>
                <option value="">Select Status</option>
                <option @if (old('status') == '1') selected @endif value="1">Pending</option>
                <option @if (old('status') == '2') selected @endif value="2">Open</option>
                <option @if (old('status') == '3') selected @endif value="3">In Progress
                </option>
                <option @if (old('status') == '4') selected @endif value="4">Completed</option>
            </select>
            <button class="primary_btn" type="button" id="filter" name="filter">Filter</button>
            <button class="secondary_btn" type="button" name="reset" id="reset">Reset</button>
        </div>
    </form>
    <div class="table_content_wrapper">
        <div class="data_table_wrap">
            <table id="complain_table" class="table table-bordered align-middle" style="width: 100%">
                <thead class="table-light">
                    <tr>
                        <th></th>
                        <th>Action</th>
                        <th>Sr. No.</th>
                        <th>Hostel</th>
                        <th>Room</th>
                        <th>Complain By</th>
                        <th>Complain Type</th>
                        <th>Complain Status</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <input type="hidden" id="isSuperAdmin" value="{{ \App\Models\Permission::isSuperAdmin() }}">
    <input type="hidden" id="readCheck"
        value="{{ \App\Models\Permission::checkCRUDPermissionToUser('Complain', 'read') }}">
    <input type="hidden" id="updateCheck"
        value="{{ \App\Models\Permission::checkCRUDPermissionToUser('Complain', 'update') }}">
@endsection
@section('scripts')
    <script src="{{ asset('js/complain/complain.js') }}"></script>
@endsection
