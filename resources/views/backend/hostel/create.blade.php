@extends('backend.layouts.app')
@section('title', 'Create Hostel')
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
    <div class="card h-100">
        <div class="card-header text-white py-3">
            <h5 class="card-title m-0 me-2 text-light">Add Hostel</h5>
        </div>
        <form action="{{ route('hostel.store') }}" method="post" id="hostel_create_form" class="hostel_form">
            @csrf
            <div class="card-body pb-0">
                <div class="row">
                    <div class="col-sm-6 col-lg-4 mb-4">
                        <label for="hostel_name">Hostel Name</label>
                        <input type="text" class="form-control" name="hostel_name" value="{{ old('hostel_name') }}"
                            id="hostel_name" placeholder="Enter hostel name" required
                            data-parsley-required-message="The hostel name field is required." />
                        <span id="hostel_error" class="red-text"></span>
                        @error('hostel_name')
                            <small class="red-text ml-10" role="alert">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                    <div class="col-sm-6 col-lg-4 mb-4">
                        <label for="location">Location</label>
                        <textarea class="form-control complaint_desc_field" name="location" id="location" placeholder="Enter location" required
                            data-parsley-required-message="The location field is required.">{{ old('location') }}</textarea>
                        @error('location')
                            <small class="red-text ml-10" role="alert">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                    <div class="col-sm-6 col-lg-4 mb-4">
                        <label for="contact_number">Contact Number</label>
                        <input type="number" class="form-control" name="contact_number" value="{{ old('contact_number') }}"
                            id="contact_number" placeholder="Enter contact number" min="0" minlength="7" required
                            data-parsley-required-message="The contact number field is required."
                            data-parsley-pattern="^\d{7,12}$"
                            data-parsley-pattern-message="The contact number must be between 7 to 12 digits." />
                        @error('contact_number')
                            <small class="red-text ml-10" role="alert">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                    <div class="col-sm-6 col-lg-4 mb-4">
                        <label for="mobile_number">Mobile Number</label>
                        <input type="number" class="form-control" name="mobile_number" value="{{ old('mobile_number') }}"
                            id="mobile_number" placeholder="Enter mobile number" min="0" minlength="7" required
                            data-parsley-required-message="The mobile number field is required."
                            data-parsley-pattern="^\d{7,12}$"
                            data-parsley-pattern-message="The mobile number must be between 7 to 12 digits." />
                        @error('mobile_number')
                            <small class="red-text ml-10" role="alert">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                    <div class="col-sm-6 col-lg-4 mb-4">
                        <label for="warden_id">Warden</label>
                        <select name="warden_id" id="warden_id" class="select2 form-select" data-placeholder="Select warden"
                            data-parsley-errors-container="#warden_id_errors" required
                            data-parsley-required-message="The warden name field is required.">
                            <option value="">Select Warden</option>
                            @foreach ($wardens as $warden)
                                <option value="{{ $warden->id }}">
                                    {{ $warden->first_name . ' ' . $warden->last_name }}</option>
                            @endforeach
                        </select>
                        <div id="warden_id_errors"></div>
                        @error('warden_id')
                            <small class="red-text ml-10" role="alert">
                                {{ $message }}
                            </small>
                        @enderror
                        <small id="warden_errors" class="errors red-text"></small>
                    </div>
                    <div class="col-sm-6 col-lg-4 mb-4">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="select2 form-select" data-placeholder="Select status"
                            data-parsley-errors-container="#status_errors" required
                            data-parsley-required-message="The status field is required.">
                            <option value="">Select Status</option>
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
            <div class="card-footer text-end pt-0">
                <button type="button" class="btn secondary_btn back">Cancel</button>
                <button type="submit" class="btn primary_btn ">Save</button>
            </div>
        </form>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/hostel/hostel.js') }}"></script>
@endsection
