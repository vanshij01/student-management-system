@extends('frontend.layouts.app')
@section('title', 'Create Leave')
@section('styles')
@endsection
@section('content')
    {{-- <div class="card-body pb-0">
        <div class="row">
            <div class="col-sm-6 col-lg-4 mb-4">
                <div class="form-floating form-floating-outline">
                    <div class="form-floating form-floating-outline">
                        <select name="leave_status" id="leave_status" class="select2 form-select"
                            data-placeholder="Select status" data-parsley-errors-container="#leave_status_errors" required
                            data-parsley-required-message="The status field is required.">
                            <option value="">Select Status</option>
                            <option @if (old('leave_status') == 'Pending') selected @endif value="Pending" selected>
                                Pending</option>
                            <option @if (old('leave_status') == 'Rejected') selected @endif value="Rejected">Rejected
                            </option>
                            <option @if (old('leave_status') == 'Approved') selected @endif value="Approved">Approved
                            </option>
                        </select>
                        <div id="leave_status_errors"></div>
                        <label for="leave_status">Status</label>
                        @error('leave_status')
                            <small class="red-text ml-10" role="alert">
                                {{ $message }}
                            </small>
                        @enderror
                        <small id="leave_status_error" class="errors red-text"></small>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <section class="Personal_details_form">
        <div class="container">

            <a href="{{ route('student.dashboard') }}" class="go-back">
                <svg width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M9.76172 1.63672L2.39844 9L9.76172 16.3633L8.86328 17.2617L1.05078 9.44922L0.621094 9L1.05078 8.55078L8.86328 0.738281L9.76172 1.63672Z"
                        fill="#1D1D1B" />
                </svg>
                Go Back</a>

            <div class="text-center com-space ">
                <h2 class="admission_form_title">Leave Application</h2>
            </div>


            <!-- Complaint Form -->
            <form action="{{ route('student.leave.store') }}" method="post" id="leave_create_form"
                class="leave_form form-section" enctype="multipart/form-data">
                @csrf
                <div class="row mb-3">
                    <div class="col">
                        <label for="complaint_subject" class="form-label">Write Title here</label>
                        <input type="text" class="form-control" name="subject" value="{{ old('subject') }}"
                            id="subject" placeholder="Enter subject" required
                            data-parsley-required-message="The subject field is required." />
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <label for="leave_from" class="form-label">Leave From</label>
                        <input type="text" class="form-control" name="leave_from" value="{{ old('leave_from') }}"
                            placeholder="DD/MM/YYYY" id="leave_from" autocomplete="off" required
                            data-parsley-required-message="The from date field is required." />
                    </div>
                    <div class="col">
                        <label for="leave_to" class="form-label">Leave To</label>
                        <input type="text" class="form-control" name="leave_to" value="{{ old('leave_to') }}"
                            placeholder="DD/MM/YYYY" id="leave_to" required autocomplete="off"
                            data-parsley-required-message="The to date field is required." />
                    </div>
                </div>
                <div class="row mb-3">

                    <div class="col">
                        <label for="leave_reason" class="form-label">Reason for leave</label>
                        <textarea class="form-control complaint_desc_field" name="reason" id="reason" placeholder="Enter reason" required
                            data-parsley-required-message="The reason field is required.">{{ old('reason') }}</textarea>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label">Upload your Tickets</label>
                        <label for="ticket-upload" class="custom-file-upload">
                            <span id="ticket-label-text">Upload from gallery <i class="las la-plus-circle"></i></span>
                            <input type="file" id="ticket-upload" name="ticket"
                                onchange="updateFileNameSwap(this, 'ticket-label-text')"
                                accept="image/png, image/jpg, image/jpeg" data-parsley-fileextension="jpeg,jpg,png" required data-parsley-file-size
                                data-parsley-errors-container="#ticket_errors"
                                data-parsley-required-message="The ticket field is required." />
                        </label>
                    </div>
                    <span id="ticket_errors"></span>
                </div>

                <div class="text-left btn-box-wrap mt_60">
                    <button type="submit" class="primary-btn ">Submit</button>
                </div>

            </form>

    </section>
@endsection
@section('scripts')
    <script>
        // Custom validator for file size (5 KB to 1 MB)
        window.Parsley.addValidator('fileSize', {
            validateString: function(_value, _requirement, instance) {
                const file = instance.$element[0].files[0];
                if (!file) return true; // Let 'required' handle empty

                const minSize = 5 * 1024; // 5 KB
                const maxSize = 1 * 1024 * 1024; // 1 MB

                return file.size >= minSize && file.size <= maxSize;
            },
            messages: {
                en: 'File size must be between 5 KB and 1 MB.'
            }
        });

        $(document).ready(function() {
            $(".leave_form").parsley();

            $('#leave_from').datepicker({
                dateFormat: 'dd/mm/yy',
            });

            $('#leave_to').datepicker({
                dateFormat: 'dd/mm/yy',
            });

            $('#leave_from').on('change', function() {
                var fromDate = $(this).datepicker('getDate');
                if (fromDate) {
                    $('#leave_to').datepicker('option', 'minDate', fromDate);
                }
            });
        });

        function updateFileNameSwap(input, targetId) {
            const label = document.getElementById(targetId);
            const defaultText = label.getAttribute('data-default') || 'Upload'; // Default text stored here
            const fileName = input.files.length > 0 ? input.files[0].name : defaultText;
            label.textContent = fileName;
        }

        window.Parsley.addValidator('fileextension', {
        requirementType: 'string',
        validateString: function (value, requirement, parsleyInstance) {
            const file = parsleyInstance.$element[0].files[0];
            if (!file) return false;

            const allowedExtensions = requirement.split(',').map(ext => ext.trim().toLowerCase());
            const extension = file.name.split('.').pop().toLowerCase();
            return allowedExtensions.includes(extension);
        },
        messages: {
            en: 'Only jpeg, jpg, and png files are allowed.'
        }
    });
    </script>
@endsection
