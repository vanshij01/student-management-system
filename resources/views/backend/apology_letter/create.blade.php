@extends('backend.layouts.app')
@section('title', 'Create Apology Letter')
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
            <h5 class="card-title m-0 me-2 text-light">Add Apology Letter</h5>
        </div>
        <form action="{{ route('apology_letter.store') }}" method="post" enctype="multipart/form-data"
            id="apology_letter_create_form" class="apology_letter_form">
            @csrf
            <div class="card-body pb-0">
                <div class="row">
                    <div class="col-sm-6 col-lg-4 mb-4">
                        <label for="student_id">Student</label>
                        <select name="student_id" id="student_id" class="select2 form-select"
                            data-placeholder="Select student" data-parsley-errors-container="#student_id_errors" required
                            data-parsley-required-message="The student field is required.">
                            <option value="">Select Student</option>
                            @foreach ($students as $student)
                                <option value="{{ $student->id }}">
                                    {{ $student->full_name }}</option>
                            @endforeach
                        </select>
                        <div id="student_id_errors"></div>
                    </div>
                    <div class="col-sm-6 col-lg-4 mb-4">
                        <label for="complaint_subject" class="form-label">Write the Subject for Apology</label>
                        <input type="text" class="form-control" name="subject" value="{{ old('subject') }}"
                            id="subject" placeholder="Enter subject" required
                            data-parsley-required-message="The subject field is required." />
                    </div>
                    <div class="col-sm-6 col-lg-4 mb-4">
                        <label for="complaint_desc" class="form-label">Write your Apology here</label>
                        <textarea class="form-control complaint_desc_field " id="complaint_desc" name="letter_content"
                            value="{{ old('subject') }}" required data-parsley-required-message="The complaint field is required."></textarea>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label class="form-label">Upload your Apology Letter</label>
                        <label for="apology-letter" class="custom-file-upload">
                            <span id="apology-label-text">Upload from gallery <i class="las la-plus-circle"></i></span>
                            <input type="file" id="apology-letter" name="document"
                                onchange="updateFileNameSwap(this, 'apology-label-text')"
                                accept="image/png, image/jpg, image/jpeg" data-parsley-fileextension="jpeg,jpg,png" required
                                data-parsley-file-size data-parsley-errors-container="#document_errors"
                                data-parsley-required-message="The document field is required." />
                        </label>
                        <span id="document_errors"></span>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end pt-0">
                <button type="button" class="btn secondary_btn back">Cancel</button>
                <button type="submit" class="btn primary_btn ">Save</button>
            </div>
        </form>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/apology_letter/apology_letter.js') }}"></script>
    <script>
        var authUserRole = '{{ Auth::user()->role }}';
    </script>
@endsection
