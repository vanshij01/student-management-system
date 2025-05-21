@extends('backend.layouts.app')
@section('title', 'Update Document Type')
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
        <div class="card-header d-flex align-items-center justify-content-between py-3">
            <h5 class="card-title m-0 me-2 text-secondary">Update Document Type</h5>
            {{-- <a href="{{ route('document_type.index') }}" class="btn btn-primary waves-effect waves-light">Back</a> --}}
        </div>
        <form action="{{ route('document_type.update', $document_type->id) }}" method="post" class="document_type_form">
            @csrf
            @method('PUT')
            <div class="card-body pb-0">
                <div class="row">
                    <div class="col-sm-6 col-lg-6 mb-4">
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" name="type"
                                value="{{ $document_type->type, old('type') }}" id="type"
                                placeholder="Enter document type" required
                                data-parsley-required-message="The name field is required." />
                            <span id="type_error"></span>
                            <label for="type">Document Type</label>
                            @error('type')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-6 mb-4 mb-md-0">
                        <div class="form-floating form-floating-outline">
                            <select name="status" id="status" class="select2 form-select"
                                data-placeholder="Select status" data-parsley-errors-container="#status_errors" required
                                data-parsley-required-message="The status field is required.">
                                <option value="">Select Status</option>
                                <option @if ($document_type->status == '1') selected @endif value="1">Enable</option>
                                <option @if ($document_type->status == '0') selected @endif value="0">Disable</option>
                            </select>
                            <div id="status_errors"></div>
                            <label for="status">Status</label>
                            @error('status')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                            <small id="status_error" class="errors red-text"></small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end pt-0">
                <a href="{{ route('document_type.index') }}" class="btn btn-outline-secondary waves-effect waves-light me-1">Cancel</a>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/document_type/document_type.js') }}"></script>
@endsection
