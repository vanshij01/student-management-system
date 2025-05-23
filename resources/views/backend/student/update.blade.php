@extends('backend.layouts.app')
@section('title', 'Update Student')
@section('styles')
    <style>
        .parsley-errors-list {
            margin: 0;
            padding: 0;
            list-style-type: none;
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
@endsection
@section('content')
    <div class="card">
        <div class="card-header text-white py-3">
            <h5 class="card-title m-0 me-2 text-light">Update Student</h5>
        </div>
        <form action="{{ route('student.update', $student->id) }}" method="post" class="student_form">
            @csrf
            @method('PUT')
            <div class="card-body pb-0">
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" name="first_name"
                            value="{{ old('first_name', $student->first_name) }}" id="first_name"
                            placeholder="Enter first name" required
                            data-parsley-required-message="The first name field is required." />
                        @error('first_name')
                            <small class="red-text ml-10" role="alert">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="middle_name" class="form-label">Middle Name (Father's Name)</label>
                        <input type="text" class="form-control" name="middle_name"
                            value="{{ old('middle_name', $student->middle_name) }}" id="middle_name"
                            placeholder="Enter middle name (father's name)" required
                            data-parsley-required-message="The middle name field is required." />
                        @error('middle_name')
                            <small class="red-text ml-10" role="alert">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="last_name" class="form-label">Last Name (Surname)</label>
                        <input type="text" class="form-control" name="last_name"
                            value="{{ old('last_name', $student->last_name) }}" id="last_name"
                            placeholder="Enter last name (surname)" required
                            data-parsley-required-message="The last name field is required." />
                        @error('last_name')
                            <small class="red-text ml-10" role="alert">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email"
                            value="{{ old('email', $student->email) }}" id="email" placeholder="Enter email" required
                            data-parsley-required-message="The email field is required." />
                        <span id="email_error" style="color: red; position: relative;"></span>
                        @error('email')
                            <small class="red-text ml-10" role="alert">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="phone" class="form-label">Mobile Number</label>
                        <input type="number" class="form-control" name="phone"
                            value="{{ old('phone', $student->phone) }}" id="phone" placeholder="Enter mobile number"
                            minlength="8" required data-parsley-required-message="The mobile number field is required."
                            data-parsley-pattern="^\d{7,12}$"
                            data-parsley-pattern-message="The mobile number must be between 7 to 12 digits." />
                        @error('phone')
                            <small class="red-text ml-10" role="alert">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="dob" class="form-label">DOB</label>
                        <input type="text" class="form-control" name="dob"
                            value="{{ date('d/m/Y', strtotime($student->dob)) }}" placeholder="DD/MM/YYYY" id="dob"
                            required data-parsley-required-message="The dob field is required." autocomplete="off" />
                        @error('dob')
                            <small class="red-text ml-10" role="alert">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="gender" class="form-label">Gender</label>
                        <select name="gender" id="gender" class="select2 form-select" data-placeholder="Select gender"
                            data-parsley-errors-container="#gender_errors" required
                            data-parsley-required-message="The gender field is required.">
                            <option value="">Select Gender</option>
                            <option @if ($student->gender == 'boy') selected @endif value="boy">Boy</option>
                            <option @if ($student->gender == 'girl') selected @endif value="girl">Girl</option>
                        </select>
                        <div id="gender_errors"></div>
                        @error('gender')
                            <small class="red-text ml-10" role="alert">
                                {{ $message }}
                            </small>
                        @enderror
                        <small id="gender_error" class="errors red-text"></small>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="address" class="form-label">Address (As per ID proof)</label>
                        <textarea class="form-control" name="address" id="address" placeholder="Enter address" required
                            data-parsley-required-message="The address field is required.">{{ old('address', $student->address) }}</textarea>
                        @error('address')
                            <small class="red-text ml-10" role="alert">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="village_id" class="form-label">Village</label>
                        <select name="village_id" id="village_id" class="select2 form-select"
                            data-placeholder="Select village" data-parsley-errors-container="#village_id_errors" required
                            data-parsley-required-message="The village field is required.">
                            <option value="">Select Village</option>
                            @foreach ($villages as $village)
                                <option value="{{ $village->id }}" @if ($village->id == $student->village_id) selected @endif>
                                    {{ $village->name }}</option>
                            @endforeach
                        </select>
                        <div id="village_id_errors"></div>
                        @error('village_id')
                            <small class="red-text ml-10" role="alert">
                                {{ $message }}
                            </small>
                        @enderror
                        <small id="village_errors" class="errors red-text"></small>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="country_id" class="form-label">Country</label>
                        <select name="country_id" id="country_id" class="select2 form-select"
                            data-placeholder="Select country" data-parsley-errors-container="#country_id_errors" required
                            data-parsley-required-message="The country field is required.">
                            <option value="">Select Country</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}" @if ($country->id == $student->country_id) selected @endif>
                                    {{ $country->name }}</option>
                            @endforeach
                        </select>
                        <div id="country_id_errors"></div>
                        @error('country_id')
                            <small class="red-text ml-10" role="alert">
                                {{ $message }}
                            </small>
                        @enderror
                        <small id="country_errors" class="errors red-text"></small>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="select2 form-select"
                            data-placeholder="Select status" data-parsley-errors-container="#status_errors" required
                            data-parsley-required-message="The status field is required.">
                            <option value="">Select Status</option>
                            <option @if ($student->status == '1') selected @endif value="1" selected>
                                Enable</option>
                            <option @if ($student->status == '0') selected @endif value="0">Disable
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
    <script src="{{ asset('js/student/student.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#dob').datepicker({
                dateFormat: 'dd/mm/yy',
                changeMonth: true,
                changeYear: true,
                maxDate: 'Today',
                yearRange: "-120:+10",
            });
        });
    </script>
@endsection
