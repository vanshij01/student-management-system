@extends('frontend.layouts.app')
@section('title', 'Create Apology Letter')
@section('styles')
@endsection
@section('content')
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
                <h2 class="admission_form_title">Apology Letter</h2>
            </div>

            <!-- Complaint Form -->
            <form action="{{ route('student.apology_letter.store') }}" method="post" enctype="multipart/form-data"
                id="apology_letter_create_form" class="apology_letter_form form-section">
                @csrf
                <div class="row mb-3">
                    <div class="col">
                        <label for="complaint_subject" class="form-label">Write the Subject for Apology</label>
                        <input type="text" class="form-control" name="subject" value="{{ old('subject') }}"
                            id="subject" placeholder="Enter subject" required
                            data-parsley-required-message="The subject field is required." />
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <label for="complaint_desc" class="form-label">Write your Apology here</label>
                        <textarea class="form-control complaint_desc_field " id="complaint_desc" name="letter_content"
                            value="{{ old('subject') }}" required data-parsley-required-message="The complaint field is required."></textarea>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label">Upload your Apology Letter</label>
                        <label for="apology-letter" class="custom-file-upload">
                            <span id="apology-label-text">Upload from gallery <i class="las la-plus-circle"></i></span>
                            <input type="file" id="apology-letter" name="document"
                                onchange="updateFileNameSwap(this, 'apology-label-text')"
                                accept="image/png, image/jpg, image/jpeg" data-parsley-fileextension="jpeg,jpg,png" required data-parsley-file-size
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
            $(".apology_letter_form").parsley();
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
