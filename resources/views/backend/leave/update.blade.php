@extends('backend.layouts.app')
@section('title', 'Update Leave')
@section('styles')
    <style>
        .parsley-errors-list {
            margin: 0;
            padding: 0;
            list-style-type: none;
        }
    </style>
@endsection
@section('content')
    <div class="card">
        <div class="card-header text-white py-3">
            <h5 class="card-title m-0 me-2 text-light">Update Leave</h5>
        </div>
        <form action="{{ route('leave.update', $leave->id) }}" method="post" class="leave_form" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body pb-0">
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="gender" class="form-label">Student</label>
                        <select name="leave_apply_by" id="leave_apply_by" class="select2 form-select"
                            data-placeholder="Select student" data-parsley-errors-container="#leave_apply_by_errors"
                            required data-parsley-required-message="The student field is required.">
                            <option value="">Select Student</option>
                            @foreach ($students as $student)
                                <option value="{{ $student->id }}" @if ($student->id == $leave->leave_apply_by) selected @endif>
                                    {{ $student->full_name }}</option>
                            @endforeach
                        </select>
                        <div id="leave_apply_by_errors"></div>
                        @error('leave_apply_by')
                            <small class="red-text ml-10" role="alert">
                                {{ $message }}
                            </small>
                        @enderror
                        <small id="student_errors" class="errors red-text"></small>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="complaint_subject" class="form-label">Write Title here</label>
                        <input type="text" class="form-control" name="subject"
                            value="{{ old('subject', $leave->subject) }}" id="subject" placeholder="Enter title" required
                            data-parsley-required-message="The title field is required." />
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="leave_from" class="form-label">Leave From</label>
                        <input type="text" class="form-control" name="leave_from"
                            value="{{ date('d/m/Y', strtotime($leave->leave_from)) }}" placeholder="DD/MM/YYYY"
                            id="leave_from" autocomplete="off" required
                            data-parsley-required-message="The from date field is required." />
                        @error('leave_from')
                            <small class="red-text ml-10" role="alert">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="leave_to" class="form-label">Leave To</label>
                        <input type="text" class="form-control" name="leave_to"
                            value="{{ date('d/m/Y', strtotime($leave->leave_to)) }}" placeholder="DD/MM/YYYY" id="leave_to"
                            autocomplete="off" required data-parsley-required-message="The to date field is required." />
                        @error('leave_to')
                            <small class="red-text ml-10" role="alert">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="leave_reason" class="form-label">Reason for leave</label>
                        <textarea class="form-control complaint_desc_field" name="reason" id="reason" placeholder="Enter reason" required
                            data-parsley-required-message="The reason field is required.">{{ old('reason', $leave->reason) }}</textarea>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label class="form-label">Upload your Tickets</label>
                        <label for="ticket-upload" class="custom-file-upload">
                            <span id="ticket-label-text">Upload from gallery <i class="las la-plus-circle"></i></span>
                            <input type="file" id="ticket-upload" name="ticket"
                                onchange="updateFileNameSwap(this, 'ticket-label-text')"
                                accept="image/png, image/jpg, image/jpeg" data-parsley-fileextension="jpeg,jpg,png"
                                data-parsley-file-size data-parsley-errors-container="#ticket_errors"
                                data-parsley-required-message="The ticket field is required." />
                        </label>
                        <span id="ticket_errors"></span>
                        @if ($leave->ticket != null)
                            <a href="{{ asset($leave->ticket) }}" target="_blank">Ticket</a>
                        @endif
                    </div>
                    <input type="hidden" name="old_ticket" value="{{ $leave->ticket }}">
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="leave_status">Status</label>
                        <select name="leave_status" id="leave_status" class="select2 form-select"
                            data-placeholder="Select status" data-parsley-errors-container="#leave_status_errors" required
                            data-parsley-required-message="The status field is required.">
                            <option value="">Select Status</option>
                            <option @if ($leave->leave_status == 'Pending') selected @endif value="Pending" selected>
                                Pending</option>
                            <option @if ($leave->leave_status == 'Rejected') selected @endif value="Rejected">Rejected
                            </option>
                            <option @if ($leave->leave_status == 'Approved') selected @endif value="Approved">Approved
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
                </div>
            </div>
            <div class="card-footer text-end py-2">
                <button type="button" class="btn secondary_btn back">Cancel</button>
                <button type="submit" class="btn primary_btn">Update</button>
            </div>
        </form>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/leave/leave.js') }}"></script>
@endsection
