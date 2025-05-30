@extends('backend.layouts.app')
@section('title', 'Create Notice')
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
            <h5 class="card-title m-0 me-2 text-light">Add Notice</h5>
        </div>
        <form action="{{ route('notices.store') }}" method="post" id="notice_create_form" class="notice_form"
            enctype="multipart/form-data">
            @csrf
            <div class="card-body pb-0">
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="title" class="form-label">Write Title here</label>
                        <input type="text" class="form-control" name="title" value="{{ old('title') }}" id="title"
                            placeholder="Enter title" required
                            data-parsley-required-message="The title field is required." />
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="content" class="form-label">Content</label>
                        <textarea class="form-control complaint_desc_field" name="content" id="content" placeholder="Enter content" required
                            data-parsley-required-message="The content field is required.">{{ old('content') }}</textarea>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="is_visible_for_student"
                                name="is_visible_for_student" checked />
                            <label class="form-check-label" for="is_visible_for_student"><span><b>Is visible for
                                        student?</b> </span></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end py-2">
                <button type="button" class="btn secondary_btn back">Cancel</button>
                <button type="submit" class="btn primary_btn ">Save</button>
            </div>
        </form>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/notice/notice.js') }}"></script>
@endsection
