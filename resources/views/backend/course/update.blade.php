@extends('backend.layouts.app')
@section('title', 'Update Course')
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
            <h5 class="card-title m-0 me-2 text-light">Update Course</h5>
        </div>
        <form action="{{ route('course.update', $course->id) }}" method="post" class="course_form">
            @csrf
            @method('PUT')
            <div class="card-body pb-0">
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-4">
                            <label for="course_name" class="form-label">Course Name</label>
                            <input type="text" class="form-control" name="course_name"
                                value="{{ $course->course_name, old('course_name') }}" id="course_name"
                                placeholder="Enter course name" required data-parsley-required-message="The course name field is required." />
                            <span id="course_name_error"></span>

                            @error('course_name')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                            <label for="duration" class="form-label">Duration (in year)</label>

                            <input type="number" class="form-control" name="duration"
                                value="{{ $course->duration, old('duration') }}" id="duration" placeholder="Enter duration (in year)"
                                min="0" required data-parsley-required-message="The duration field is required." />
                            <span id="duration_error"></span>
                            @error('duration')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                            <label for="semester" class="form-label">Semester</label>

                            <input type="number" class="form-control" name="semester"
                                value="{{ $course->semester, old('semester') }}" id="semester" placeholder="Enter semester"
                                min="0" required data-parsley-required-message="The semester field is required." />
                            <span id="semester_error"></span>

                            @error('semester')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                            <label for="education_type" class="form-label">Education</label>

                            <select name="education_type" id="education_type" class="select2 form-select"
                                data-placeholder="Select Education" data-parsley-errors-container="#education_type_errors" required data-parsley-required-message="The education_type field is required.">
                                <option value="">Select Education</option>
                                <option @if ($course->education_type == 'HSC') selected @endif value="HSC" selected>HSC </option>
                                <option @if ($course->education_type == 'Diploma') selected @endif value="Diploma" selected>Diploma </option>
                                <option @if ($course->education_type == "Bachelor's Degree") selected @endif value="Bachelor's Degree" selected>Bachelor's Degree</option>
                                <option @if ($course->education_type == "Master's Degree") selected @endif value="Master's Degree" selected>Master's Degree</option>
                                <option @if ($course->education_type == 'Professional Degree') selected @endif value="Professional Degree" selected>Professional Degree </option>
                                <option @if ($course->education_type == 'Internship') selected @endif value="Internship" selected>Internship </option>
                                <option @if ($course->education_type == 'Job') selected @endif value="Job" selected>Job </option>
                                <option @if ($course->education_type == 'Other') selected @endif value="Other" selected>Other </option>
                            </select>
                            <div id="education_type_errors"></div>
                            @error('education_type')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                            <small id="education_type_error" class="errors red-text"></small>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                            <label for="status" class="form-label">Status</label>

                            <select name="status" id="status" class="select2 form-select"
                                data-placeholder="Select status" data-parsley-errors-container="#status_errors" required data-parsley-required-message="The status field is required.">
                                <option value="">Select Status</option>
                                <option @if ($course->status == '1') selected @endif value="1">Enable</option>
                                <option @if ($course->status == '0') selected @endif value="0">Disable
                                </option>
                            </select>
                            <div id="status_errors"></div>
                            @error('status')
                                <small class="red-text ml-10" role="alert">
                                    {{ $message }}
                                </small>
                            @enderror
                            <small id="status_error" class="errors red-text"></small>
                    </div>
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
    <script src="{{ asset('js/course/course.js') }}"></script>
@endsection
