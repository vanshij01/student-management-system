@extends('backend.layouts.guest')
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/vendor/select2/select2.css') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css" />
    <style>
        .parsley-required,
        .parsley-type,
        ul.parsley-errors-list,
        .red-text {
            color: red;
        }

        .parsley-errors-list {
            margin: 0;
            padding: 0;
            list-style-type: none;
        }

        .light-style .select2-container--default .select2-selection--single {
            /* height: 100%; */
            border: 1px solid #18a8b0;
            font-weight: 400;
            font-size: 18px;
            line-height: normal;
            letter-spacing: 0%;
            padding: 15.5px 20px;
            border-radius: 10px;
            background-color: transparent;
            min-height: 60px;
        }

        .light-style .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: normal;
            padding-left: 0px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 10px;
        }

        body>span.select2-container.select2-container--default.select2-container--open {
            width: auto !important;
        }

        .light-style .select2-container--default .select2-selection__placeholder {
            color: #282529;
        }

        .select2-selection__rendered:before {
            content: "";
            background-image: url('{{ asset('assets/images/arrow-icon.svg') }}');
            position: absolute;
            height: 30px;
            width: 30px;
            display: block;
            background-repeat: no-repeat;
            right: 0px;
            top: 5px;
        }

        span.select2-selection__rendered {
            position: relative;
        }

        .select2-selection__arrow {
            display: none;
        }

        .select2-container {

            width: 100% !important;
        }
    </style>
@endsection
@section('content')
    <h3 class="form-right-title">Register</h3>

    <form action="{{ route('register') }}" method="post" class="student_form" id="student_create_form">
        @csrf
        <div class="row ">
            <div class="col input-box">
                <label class="form-input-label" for="firstName">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name') }}"
                    placeholder="First Name" required data-parsley-required-message="The first name field is required."
                    data-parsley-pattern="^[a-zA-Z ]+$"
                    data-parsley-pattern-message="The first name can only contain letters and spaces." />
            </div>
            <div class="col input-box">
                <label class="form-input-label" for="middleName">Middle Name</label>
                <input type="text" class="form-control" id="middle_name" name="middle_name"
                    value="{{ old('middle_name') }}" placeholder="Middle Name" required
                    data-parsley-required-message="The middle name field is required." data-parsley-pattern="^[a-zA-Z ]+$"
                    data-parsley-pattern-message="The middle name can only contain letters and spaces." />
            </div>
        </div>
        <div class="row ">
            <div class="col input-box">
                <label class="form-input-label" for="lastName">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name') }}"
                    placeholder="Last Name" required data-parsley-required-message="The last name field is required."
                    data-parsley-pattern="^[a-zA-Z ]+$"
                    data-parsley-pattern-message="The last name can only contain letters and spaces." />
            </div>
            <div class="col input-box">
                <label class="form-input-label" for="gender">Gender</label>
                <select name="gender" id="gender" class="select2 form-select" data-placeholder="Select Gender"
                    data-parsley-errors-container="#gender_errors" required
                    data-parsley-required-message="The gender field is required.">
                    <option value="">Select Gender</option>
                    <option @if (old('gender') == 'boy') selected @endif value="boy">Boy</option>
                    <option @if (old('gender') == 'girl') selected @endif value="girl">Girl</option>
                </select>
                <div id="gender_errors"></div>
                <small id="gender_error" class="errors red-text"></small>
            </div>
        </div>
        <div class="row ">
            <div class="col input-box">
                <label class="form-input-label" for="email">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}"
                    placeholder="example@gmail.com" required data-parsley-required-message="The email field is required." />
                @error('email')
                    <p class="red-text ml-10 d-block text-start" role="alert">
                        {{ $message }}
                    </p>
                @enderror
            </div>
        </div>
        <div class="row ">
            <div class="col input-box">
                <label class="form-input-label" for="phone">Contact Number</label>
                <input type="tel" class="form-control" name="phone" value="{{ old('phone') }}" id="phone"
                    placeholder="Enter Mobile Number" minlength="8" min="0"
                    onkeyup="this.value = this.value.replace(/[^0-9-]/g, '');" required
                    data-parsley-required-message="The mobile number field is required." data-parsley-pattern="^\d{7,12}$"
                    data-parsley-pattern-message="The mobile number must be between 7 to 12 digits." />
            </div>
            <div class="col input-box">
                <label class="form-input-label" for="village">Village</label>
                <select name="village_id" id="village_id" class="select2 form-select" data-placeholder="Select Village"
                    data-parsley-errors-container="#village_id_errors" required required
                    data-parsley-required-message="The village field is required.">
                    <option value="">Select Village</option>
                    @foreach ($villages as $village)
                        <option value="{{ $village->id }}" @if (old('village_id') == $village->id) selected @endif>
                            {{ $village->name }}</option>
                    @endforeach
                </select>
                <div id="village_id_errors"></div>
            </div>
        </div>
        <div class="row ">
            <div class="col input-box">
                <label class="form-input-label" for="password">Create Password</label>
                <div class="form-password-toggle">
                    <div class="input-group input-group-merge d-block">
                        <div>
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
                            <span class="input-group-text cursor-pointer password-eye-icon"><i
                                    class="mdi mdi-eye-off-outline"></i></span>
                        </div>
                    </div>
                    <div id="password_errors"></div>
                </div>
            </div>
            <div class="col input-box">
                <label class="form-input-label" for="retypePassword">Retype Password</label>
                <div class="form-password-toggle">
                    <div class="input-group input-group-merge d-block">
                        <div>
                            <input type="password" id="password_confirmation" class="form-control"
                                name="password_confirmation" value="{{ old('password_confirmation') }}"
                                autocomplete="off"
                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                aria-describedby="password" data-parsley-errors-container="#password_confirmation_errors"
                                required minlength="8" data-parsley-required-message="The password field is required." />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" style="color: red;" />
                            <span class="input-group-text cursor-pointer password-eye-icon"><i
                                    class="mdi mdi-eye-off-outline"></i></span>
                        </div>
                    </div>
                    <div id="password_confirmation_errors"></div>
                </div>
            </div>
        </div>
        <button class="form-btn">Register</button>
        <p class="form-info-text text-center">Already have an account? <a href="{{ route('login') }}"
                class="info-txt">Login here!</a>
        </p>
    </form>
@endsection
@section('scripts')
    <script src="{{ asset('assets/vendor/select2/select2.js') }}"></script>
    <script>
        $('#student_create_form').parsley();
        $('.select2').select2();

        $(document).on('click', '.form-password-toggle .input-group-text.cursor-pointer', function() {
            var $input = $(this).closest('.input-group').find('input[type="password"], input[type="text"]');
            var $icon = $(this).find('i');

            if ($input.attr('type') === 'password') {
                $input.attr('type', 'text');
                $icon.removeClass('mdi-eye-off-outline').addClass('mdi-eye-outline');
            } else {
                $input.attr('type', 'password');
                $icon.removeClass('mdi-eye-outline').addClass('mdi-eye-off-outline');
            }
        });
    </script>
@endsection
