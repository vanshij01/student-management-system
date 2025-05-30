@extends('backend.layouts.app')
@section('title', 'Leave Listing')
@section('styles')
    <style>
        .dataTables_scrollBody {
            overflow: unset !important;
        }

        .select2-container--open .select2-dropdown--below {
            z-index: 9999;
        }

        body>span.select2-container.select2-container--default.select2-container--open {
            z-index: 9999;
        }
    </style>
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
            <div class="d-flex gap-1">
                <button class="primary_btn" type="button" id="filter" name="filter">Filter</button>
                <button class="secondary_btn" type="button" name="reset" id="reset">Reset</button>
            </div>
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

    <!-- Send Admin Remark -->
    <div class="modal fade changeLeaveStatusModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Change Leave Status</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="changeLeaveStatusForm" method="post">
                    @csrf
                    <input type="hidden" name="student_id" class="student_id">
                    <input type="hidden" name="leave_id" class="leave_id">
                    <div class="modal-body pb-0">
                        <div class="row">
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <label for="name">Student Name</label>
                                <input type="text" class="form-control student_name" disabled />
                            </div>
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <label for="name">Student Email</label>
                                <input type="text" class="form-control student_email" disabled />
                            </div>
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <label for="leave_status">Status</label>
                                <select name="leave_status" id="leave_status" class="leave_status select2 form-select"
                                    data-placeholder="Select status" data-parsley-errors-container="#leave_status_errors"
                                    required data-parsley-required-message="The status field is required.">
                                    <option value="">Select Status</option>
                                    <option @if (old('leave_status') == 'Pending') selected @endif value="Pending" selected>
                                        Pending</option>
                                    <option @if (old('leave_status') == 'Rejected') selected @endif value="Rejected">Rejected
                                    </option>
                                    <option @if (old('leave_status') == 'Approved') selected @endif value="Approved">Approved
                                    </option>
                                </select>
                                <div id="leave_status_errors"></div>
                                @error('leave_status')
                                    <small class="red-text ml-10" role="alert">
                                        {{ $message }}
                                    </small>
                                @enderror
                                <small id="leave_status_error" class="errors red-text"></small>
                            </div>
                            <div class="col-sm-12 col-lg-6 mb-4">
                                Leave Duration: <span class="from_date"></span> to <span class="to_date"></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn secondary_btn" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn primary_btn ">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <input type="hidden" id="isSuperAdmin" value="{{ \App\Models\Permission::isSuperAdmin() }}">
    <input type="hidden" id="readCheck"
        value="{{ \App\Models\Permission::checkCRUDPermissionToUser('Leave', 'read') }}">
    <input type="hidden" id="updateCheck"
        value="{{ \App\Models\Permission::checkCRUDPermissionToUser('Leave', 'update') }}">
@endsection
@section('scripts')
    <script src="{{ asset('js/leave/leave.js') }}"></script>
@endsection
