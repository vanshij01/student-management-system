@extends('backend.layouts.app')
@section('title', 'Complain Listing')
@section('styles')
    <style>
        .dataTables_scrollBody {
            overflow: unset !important;
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
            <div class="d-flex gap-1">
                <button class="primary_btn" type="button" id="filter" name="filter">Filter</button>
                <button class="secondary_btn" type="button" name="reset" id="reset">Reset</button>
            </div>
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
                    <input type="hidden" name="complain_id" class="complain_id">
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
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="select2 form-select statusChange"
                                    data-placeholder="Select status" data-parsley-errors-container="#status_errors"
                                    required>
                                    <option value="">Select Status</option>
                                    <option @if (old('status') == '1') selected @endif value="1" selected>
                                        Pending
                                    </option>
                                    <option @if (old('status') == '2') selected @endif value="2">Open</option>
                                    <option @if (old('status') == '3') selected @endif value="3">In Progress
                                    </option>
                                    <option @if (old('status') == '4') selected @endif value="4">Completed
                                    </option>
                                </select>
                                <div id="status_errors"></div>
                                @error('status')
                                    <small class="red-text ml-10" role="alert">
                                        {{ $message }}
                                    </small>
                                @enderror
                                <small id="leave_status_error" class="errors red-text"></small>
                            </div>
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <label for="admin_comment" class="form-label">Admin comment</label>
                                <textarea class="form-control" name="admin_comment" id="admin_comment" placeholder="Enter admin comment" required
                                    data-parsley-required-message="The admin comment field is required.">{{ old('admin_comment') }}</textarea>
                                <a href="" target="_blank" class="d-none ticket">document</a>
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
        value="{{ \App\Models\Permission::checkCRUDPermissionToUser('Complain', 'read') }}">
    <input type="hidden" id="updateCheck"
        value="{{ \App\Models\Permission::checkCRUDPermissionToUser('Complain', 'update') }}">
@endsection
@section('scripts')
    <script src="{{ asset('js/complain/complain.js') }}"></script>  
    <script>
        var assetBaseUrl = "{{ asset('') }}"; // Outputs: http://192.168.0.129:8000/
    </script>

@endsection
