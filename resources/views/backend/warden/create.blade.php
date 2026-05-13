@extends('backend.layouts.app')
@section('title', 'Create Warden')
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
            <h5 class="card-title m-0 me-2 text-light">Add Warden</h5>
        </div>
        <form action="{{ route('warden.store') }}" method="post" class="warden_form" id="warden_create_form">
            @csrf
            <div class="card-body pb-0">
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" name="first_name" value="{{ old('first_name') }}"
                            id="first_name" placeholder="Enter first name" required
                            data-parsley-required-message="The first name field is required." />
                        @error('first_name')
                            <small class="red-text ml-10" role="alert">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" name="last_name" value="{{ old('last_name') }}"
                            id="last_name" placeholder="Enter last name" required
                            data-parsley-required-message="The last name field is required." />
                        @error('last_name')
                            <small class="red-text ml-10" role="alert">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="{{ old('email') }}" id="email"
                            placeholder="Enter email" required
                            data-parsley-required-message="The email field is required." />
                        <span id="email_error" class="red-text"></span>
                        @error('email')
                            <small class="red-text ml-10" role="alert">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="form-password-toggle">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password" class="form-control" name="password"
                                    value="{{ old('password') }}"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                    aria-describedby="password" data-parsley-errors-container="#password_errors" required
                                    minlength="8" data-parsley-required-message="The password field is required." />
                                @error('password')
                                    <small class="red-text ml-10" role="alert" style="position: absolute;">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                        </div>
                        <div id="password_errors"></div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="phone" class="form-label">Mobile Number</label>
                        <input type="number" class="form-control" name="phone" value="{{ old('phone') }}" id="phone"
                            placeholder="Enter mobile number" min="0" minlength="7" required
                            data-parsley-required-message="The mobile number field is required."
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
                        <input type="text" class="form-control" name="dob" value="{{ old('dob') }}"
                            placeholder="DD/MM/YYYY" id="dob" required
                            data-parsley-required-message="The dob field is required." autocomplete="off"/>
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
                            <option value="">Select gender</option>
                            <option @if (old('gender') == 'male') selected @endif value="male" selected>Male
                            </option>
                            <option @if (old('gender') == 'female') selected @endif value="female">Female
                            </option>
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
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" name="address" id="address" placeholder="Enter address" required
                            data-parsley-required-message="The address field is required.">{{ old('address') }}</textarea>
                        @error('address')
                            <small class="red-text ml-10" role="alert">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="experience" class="form-label">Experience</label>
                        <input type="text" class="form-control" name="experience" value="{{ old('experience') }}"
                            id="experience" placeholder="Enter experience" required
                            data-parsley-required-message="The experience field is required." />
                        @error('experience')
                            <small class="red-text ml-10" role="alert">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="qualification" class="form-label">Qualification</label>
                        <input type="text" class="form-control" name="qualification"
                            value="{{ old('qualification') }}" id="qualification" placeholder="Enter qualification"
                            required data-parsley-required-message="The qualification field is required." />
                        @error('qualification')
                            <small class="red-text ml-10" role="alert">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="select2 form-select"
                            data-placeholder="Select status" data-parsley-errors-container="#status_errors" required
                            data-parsley-required-message="The status field is required.">
                            <option value="">Select status</option>
                            <option @if (old('status') == '1') selected @endif value="1" selected>
                                Enable
                            </option>
                            <option @if (old('status') == '0') selected @endif value="0">Disable
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
                <button type="submit" class="btn primary_btn ">Save</button>
            </div>
        </form>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/warden/warden.js') }}"></script>
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
