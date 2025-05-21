@extends('backend.layouts.app')
@section('title', 'Update Complain')
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
            <h5 class="card-title m-0 me-2 text-light">Create Complain</h5>
        </div>
        <form action="{{ route('complain.update', $complain->id) }}" method="post" class="complain_form"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body pb-0">
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="complain_by" class="form-label">Student</label>
                        <select name="complain_by" id="complain_by" class="select2 form-select"
                            data-placeholder="Select student" data-parsley-errors-container="#complain_by_errors" required
                            data-parsley-required-message="The student field is required.">
                            <option value="">Select Student</option>
                            @foreach ($students as $student)
                                <option value="{{ $student->id }}" @if ($student->id == $complain->complain_by) selected @endif>
                                    {{ $student->full_name }}</option>
                            @endforeach
                        </select>
                        <div id="complain_by_errors"></div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="type" class="form-label">Type</label>
                        <select name="type" id="type" class="select2 form-select" data-placeholder="Select type"
                            data-parsley-errors-container="#type_errors" required
                            data-parsley-required-message="The type field is required.">
                            <option value="">Select Type</option>
                            <option @if ($complain->type == '1') selected @endif value="1">Technical
                            </option>
                            <option @if ($complain->type == '2') selected @endif value="2">System</option>
                            <option @if ($complain->type == '3') selected @endif value="3">Management
                            </option>
                        </select>
                        <div id="type_errors"></div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" name="message" id="message" placeholder="Enter message" required
                            data-parsley-required-message="The message field is required.">{{ old('message', $complain->message) }}</textarea>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="select2 form-select" data-placeholder="Select status"
                            data-parsley-errors-container="#status_errors" required>
                            <option value="">Select Status</option>
                            <option @if ($complain->status == '1') selected @endif value="1" selected>
                                Pending
                            </option>
                            <option @if ($complain->status == '2') selected @endif value="2">Open</option>
                            <option @if ($complain->status == '3') selected @endif value="3">In Progress
                            </option>
                            <option @if ($complain->status == '4') selected @endif value="4">Completed
                            </option>
                        </select>
                        <div id="status_errors"></div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="admin_comment" class="form-label">Admin comment</label>
                        <textarea class="form-control" name="admin_comment" id="admin_comment" placeholder="Enter admin comment" required
                            data-parsley-required-message="The admin comment field is required.">{{ old('admin_comment', $complain->admin_comment) }}</textarea>
                    </div>
                    <div class="col-sm-6 col-lg-4 mb-4">
                        <label class="form-label">Upload your document</label>
                        <label for="document-letter" class="custom-file-upload">
                            <span id="document-label-text">Upload from gallery <i class="las la-plus-circle"></i></span>
                            <input type="file" id="document-letter" name="document"
                                onchange="updateFileNameSwap(this, 'document-label-text')"
                                accept="image/png, image/jpg, image/jpeg" data-parsley-fileextension="jpeg,jpg,png"
                                data-parsley-file-size data-parsley-errors-container="#document_errors"
                                data-parsley-required-message="The document field is required." />
                        </label>
                        <span id="document_errors"></span>
                        @if (!empty($complain->document))
                            <a href="{{ asset($complain->document) }}" target="_blank">document</a>
                        @endif
                    </div>
                    <input type="hidden" name="old_document" value="{{ $complain->document }}">
                </div>
            </div>
            <div class="card-footer text-end py-2">
                <button type="button" class="btn secondary_btn back">Cancel</button>
                <button type="submit" class="btn primary_btn ">Update</button>
            </div>
        </form>
    </div>


@endsection
@section('scripts')
    <script src="{{ asset('js/complain/complain.js') }}"></script>
@endsection
