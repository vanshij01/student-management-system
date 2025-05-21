@extends('frontend.layouts.app')
@section('title', 'Create Complain')
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
    {{-- <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between py-3">
            <h5 class="card-title m-0 me-2 text-secondary">Create Complain</h5>
        </div>
        <form action="{{ route('student.complain.store') }}" method="post" id="complain_create_form" class="complain_form"
            enctype="multipart/form-data">
            @csrf
            <div class="card-body pb-0">
                <div class="row">
                    <div class="col-sm-6 col-lg-4 mb-4">
                        <div class="form-floating form-floating-outline">
                            <select name="type" id="type" class="select2 form-select" data-placeholder="Select type"
                                data-parsley-errors-container="#type_errors" required
                                data-parsley-required-message="The type field is required.">
                                <option value="">Select Type</option>
                                <option @if (old('type') == '1') selected @endif value="1">Technical
                                </option>
                                <option @if (old('type') == '2') selected @endif value="2">System</option>
                                <option @if (old('type') == '3') selected @endif value="3">Management
                                </option>
                            </select>
                            <label for="type">Type</label>
                            <div id="type_errors"></div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 mb-4">
                        <div class="form-floating form-floating-outline">
                            <textarea class="form-control" name="message" id="message" placeholder="Enter message" required
                                data-parsley-required-message="The message field is required.">{{ old('message') }}</textarea>
                            <label for="message">Message</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 mb-4">
                        <div class="form-floating form-floating-outline">
                            <input class="form-control" name="document" type="file" id="document" />
                            <label for="document">Document</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end pt-0">
                <a href="{{ route('student.complain.index') }}"
                    class="btn btn-outline-secondary waves-effect waves-light me-1">Cancel</a>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
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
                <h2 class="admission_form_title">Complaint Form</h2>
            </div>

            <!-- Complaint Form -->
            <form action="{{ route('student.complain.store') }}" method="post" id="complain_create_form"
                class="complain_form form-section" enctype="multipart/form-data">
                @csrf
                <div class="row mb-3">
                    <div class="col-4">
                        <label for="complaint_type" class="form-label">Choose Type of Complaint</label>
                        <select name="type" id="type" class="select2 form-select" data-placeholder="Select type"
                            data-parsley-errors-container="#type_errors" required
                            data-parsley-required-message="The type field is required.">
                            <option value="">Select Type</option>
                            <option @if (old('type') == '1') selected @endif value="1">Technical
                            </option>
                            <option @if (old('type') == '2') selected @endif value="2">System</option>
                            <option @if (old('type') == '3') selected @endif value="3">Management
                            </option>
                        </select>
                        <div id="type_errors"></div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <label for="complaint_desc" class="form-label">Write your complaint here</label>
                        <textarea class="form-control complaint_desc_field " id="complaint_desc" name="message" value="{{ old('message') }}"
                            required data-parsley-required-message="The message field is required."></textarea>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label">Document</label>
                        <label for="document" class="custom-file-upload">
                            <span id="complain-label-text">Upload from gallery <i class="las la-plus-circle"></i></span>
                            <input type="file" name="document" id="document"
                                onchange="updateFileNameSwap(this, 'complain-label-text')"
                                accept="image/png, image/jpg, image/jpeg" data-parsley-fileextension="jpeg,jpg,png" data-parsley-file-size
                                data-parsley-errors-container="#document_errors"
                                data-parsley-required-message="The document field is required." />
                        </label>
                    </div>
                    <span id="document_errors"></span>
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
            $(".complain_form").parsley();
            $('.select2').select2();
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
