@extends('backend.layouts.app')
@section('title', 'Update Apology Letter')
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
            <h5 class="card-title m-0 me-2 text-light">Update Apology Letter</h5>
        </div>
        <form action="{{ route('apology_letter.update', $apology_letter->id) }}" method="post" enctype="multipart/form-data"
            class="apology_letter_form">
            @csrf
            @method('PUT')
            <div class="card-body pb-0">
                <div class="row">
                    <input type="hidden" name="student_id" value="{{ $apology_letter->student_id }}">
                    <div class="col-sm-6 col-lg-4 mb-4">
                        <label for="student_id">Student</label>
                        <select name="student_id" id="student_id" class="select2 form-select"
                            data-placeholder="Select student" data-parsley-errors-container="#student_id_errors" required
                            disabled data-parsley-required-message="The student field is required.">
                            <option value="">Select Student</option>
                            @foreach ($students as $student)
                                <option value="{{ $student->id }}" @if ($apology_letter->student_id == $student->id) selected @endif>
                                    {{ $student->full_name }}</option>
                            @endforeach
                        </select>
                        <div id="student_id_errors"></div>
                    </div>

                    <div class="col-sm-6 col-lg-4 mb-4">
                        <label for="complaint_subject" class="form-label">Write the Subject for Apology</label>
                        <input type="text" class="form-control" name="subject"
                            value="{{ old('subject', $apology_letter->subject) }}" id="subject"
                            placeholder="Enter subject" required
                            data-parsley-required-message="The subject field is required." />
                    </div>
                    <div class="col-sm-6 col-lg-4 mb-4">
                        <label for="complaint_desc" class="form-label">Write your Apology here</label>
                        <textarea class="form-control complaint_desc_field " id="complaint_desc" name="letter_content" required
                            data-parsley-required-message="The complaint field is required.">{{ $apology_letter->letter_content }}</textarea>
                    </div>
                    <div class="col-sm-6 col-lg-4 mb-4">
                        <label class="form-label">Upload your Apology Letter</label>
                        <label for="apology-letter" class="custom-file-upload">
                            <span id="apology-label-text">Upload from gallery <i class="las la-plus-circle"></i></span>
                            <input type="file" id="apology-letter" name="document"
                                onchange="updateFileNameSwap(this, 'apology-label-text')"
                                accept="image/png, image/jpg, image/jpeg" data-parsley-fileextension="jpeg,jpg,png"
                                data-parsley-file-size data-parsley-errors-container="#document_errors"
                                data-parsley-required-message="The document field is required." />
                        </label>
                        <span id="document_errors"></span>
                        @if (!empty($apology_letter->document))
                            <a href="{{ asset($apology_letter->document) }}" target="_blank">Apology Latter</a>
                        @endif
                    </div>
                    <input type="hidden" name="old_apology" value="{{ $apology_letter->document }}">
                </div>
            </div>
            <div class="card-footer text-end pt-0">
                <button type="button" class="btn secondary_btn back">Cancel</button>
                <button type="submit" class="btn primary_btn">Update</button>
            </div>
        </form>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/apology_letter/apology_letter.js') }}"></script>
@endsection
