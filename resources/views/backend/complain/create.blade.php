@extends('backend.layouts.app')
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
    <div class="card">
        <div class="card-header text-white py-3">
            <h5 class="card-title m-0 me-2 text-light">Create Complain</h5>
        </div>
        <form action="{{ route('complain.store') }}" method="post" id="complain_create_form" class="complain_form"
            enctype="multipart/form-data">
            @csrf
            <div class="card-body pb-0">
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="complain_by" class="form-label">Student</label>
                        <select name="complain_by" id="complain_by" class="select2 form-select"
                            data-placeholder="Select student" data-parsley-errors-container="#complain_by_errors" required
                            data-parsley-required-message="The student field is required.">
                            <option value="">Select Student</option>
                            @foreach ($students as $student)
                                <option value="{{ $student->id }}">
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
                            <option @if (old('type') == '1') selected @endif value="1">Technical
                            </option>
                            <option @if (old('type') == '2') selected @endif value="2">System</option>
                            <option @if (old('type') == '3') selected @endif value="3">Management
                            </option>
                        </select>
                        <div id="type_errors"></div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" name="message" id="message" placeholder="Enter message" required
                            data-parsley-required-message="The message field is required.">{{ old('message') }}</textarea>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="select2 form-select" data-placeholder="Select status"
                            data-parsley-errors-container="#status_errors" required>
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
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="document" class="form-label">Document</label>
                        <input class="form-control" name="document" type="file" id="document" />
                    </div>
                </div>
            </div>
            <div class="card-footer text-end py-2">
                <button type="button" class="btn secondary_btn back">Cancel</button>
                <button type="submit" class="btn primary_btn">Save</button>
            </div>
        </form>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/complain/complain.js') }}"></script>
@endsection
