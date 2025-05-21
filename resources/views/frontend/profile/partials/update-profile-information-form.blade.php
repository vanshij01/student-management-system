{{-- <section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
 --}}

@extends('frontend.layouts.app')
@section('title', 'Update User')

@section('styles')
    <style>
        .user-profile-header {
            margin-top: 2rem !important;
        }
    </style>
@endsection

@section('content')
    <section class="Personal_details_form">
        <div class="container">
            <a href="{{ route('student.dashboard') }}" class="go-back">
                <svg width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M9.76172 1.63672L2.39844 9L9.76172 16.3633L8.86328 17.2617L1.05078 9.44922L0.621094 9L1.05078 8.55078L8.86328 0.738281L9.76172 1.63672Z"
                        fill="#1D1D1B" />
                </svg>
                Go Back</a>

            <div class="text-center com-space ">
                <h2 class="admission_form_title">Personal Details</h2>
            </div>

            <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                @csrf
            </form>

            @if (session('status') === 'profile-updated')
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Profile update successfully!!!</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <form method="POST" enctype="multipart/form-data" action="{{ route('student.profile.updateProfileStudent') }}"
                class="form-section">
                @csrf
                @method('patch')
                <div class="row mb-3">
                    <div class="col">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" name="first_name"
                            value="{{ old('first_name', $student->first_name) }}" id="first_name"
                            placeholder="Enter First Name" required
                            data-parsley-required-message="The first name field is required." />
                    </div>
                    <div class="col">
                        <label for="middle_name" class="form-label">Middle Name</label>
                        <input type="text" class="form-control" name="middle_name"
                            value="{{ old('middle_name', $student->middle_name) }}" id="middle_name"
                            placeholder="Enter Middle Name" required
                            data-parsley-required-message="The middle name field is required." />
                    </div>
                    <div class="col">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" name="last_name"
                            value="{{ old('last_name', $student->last_name) }}" id="last_name" placeholder="Enter Last Name"
                            required data-parsley-required-message="The last name field is required." />
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email"
                            value="{{ old('email', $student->email) }}" id="email" placeholder="Enter Email" required
                            data-parsley-required-message="The email field is required." />
                        @if (Session::has('emailExists'))
                            <span id="email_error" style="color: red;">The email has already
                                been taken.</span>
                        @endif
                    </div>
                    <div class="col">
                        <label for="phone" class="form-label">Mobile Number</label>
                        <input type="number" class="form-control" name="phone"
                            value="{{ old('phone', $student->phone) }}" id="phone" placeholder="Enter Mobile Number"
                            required data-parsley-required-message="The mobile number field is required."
                            data-parsley-pattern="^\d{7,12}$"
                            data-parsley-pattern-message="The mobile number must be between 7 to 12 digits." />
                    </div>
                    <div class="col">
                        <label for="dob" class="form-label">DOB</label>
                        <input type="text" class="form-control" name="dob"
                            value="{{ $student->dob ? date('d/m/Y', strtotime($student->dob)) : '' }}"
                            placeholder="DD/MM/YYYY" id="dob" autocomplete="off" required
                            data-parsley-required-message="The dob field is required." />
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <label for="gender" class="form-label">Gender</label>
                        <select name="gender" id="gender" class="select2 form-select" data-placeholder="Select Gender"
                            data-parsley-errors-container="#gender_errors" required>
                            <option value="">Select Gender</option>
                            <option @if ($student->gender == 'boy') selected @endif value="boy">Boy</option>
                            <option @if ($student->gender == 'girl') selected @endif value="girl">Girl</option>
                        </select>
                        <div id="gender_errors"></div>
                    </div>
                    <div class="col">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" name="address" id="address" placeholder="Enter Address" required
                            data-parsley-required-message="The address field is required.">{{ old('address', $student->address) }}</textarea>
                    </div>
                    <div class="col">
                        <label for="village_id" class="form-label">Village</label>
                        <select name="village_id" id="village_id" class="select2 form-select"
                            data-placeholder="Select Village" data-parsley-errors-container="#village_id_errors" required
                            data-parsley-required-message="The village field is required.">
                            <option value="">Select Village</option>
                            @foreach ($villages as $village)
                                <option value="{{ $village->id }}" @if ($village->id == $student->village_id) selected @endif>
                                    {{ $village->name }}</option>
                            @endforeach
                        </select>
                        <div id="village_id_errors"></div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <label for="country_id" class="form-label">Country</label>
                        <select name="country_id" id="country_id" class="select2 form-select"
                            data-placeholder="Select Country" data-parsley-errors-container="#country_id_errors" required
                            data-parsley-required-message="The country field is required.">
                            <option value="">Select Country</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}" @if ($country->id == $student->country_id) selected @endif>
                                    {{ $country->name }}</option>
                            @endforeach
                        </select>
                        <div id="country_id_errors"></div>
                    </div>
                    <div class="col"></div>
                    <div class="col"></div>
                </div>
                <div class="text-left btn-box-wrap mt_60">
                    <button type="submit" class="primary-btn ">Update</button>
                </div>
            </form>
        </div>
    </section>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $("#dob").datepicker({
                dateFormat: 'dd/mm/yy',
                changeMonth: true,
                changeYear: true,
                maxDate: 0,
                yearRange: "1950:c"
            });
            $('.select2').select2();
            $('.form-section').parsley();
        });
    </script>
@endsection
