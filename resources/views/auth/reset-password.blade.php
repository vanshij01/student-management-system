@extends('backend.layouts.guest')
@section('styles')
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
    </style>
@endsection
@section('content')
    <form id="reset_password" class="student_form" method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="row ">
            <div class="col input-box">
                <label class="form-input-label" for="email">Email Address</label>
                <input type="email" class="form-control" id="email" name="email"
                    value="{{ old('email', $request->email) }}" placeholder="example@gmail.com" required
                    data-parsley-required-message="The email field is required." />
                @error('email')
                    <p class="red-text ml-10 d-block text-start" role="alert">
                        {{ $message }}
                    </p>
                @enderror
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
        </div>
        <div class="row ">
            <div class="col input-box">
                <label class="form-input-label" for="retypePassword">Retype Password</label>
                <div class="form-password-toggle">
                    <div class="input-group input-group-merge d-block">
                        <div>
                            <input type="password" id="password_confirmation" class="form-control"
                                name="password_confirmation" value="{{ old('password_confirmation') }}"
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
        <button class="form-btn">Reset Password</button>
    </form>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('#reset_password').parsley();
            $(document).on('click', '.form-password-toggle .input-group-text.cursor-pointer', function() {
                var $input = $(this).closest('.input-group').find(
                    'input[type="password"], input[type="text"]');
                var $icon = $(this).find('i');

                if ($input.attr('type') === 'password') {
                    $input.attr('type', 'text');
                    $icon.removeClass('mdi-eye-off-outline').addClass('mdi-eye-outline');
                } else {
                    $input.attr('type', 'password');
                    $icon.removeClass('mdi-eye-outline').addClass('mdi-eye-off-outline');
                }
            });
        });
    </script>
@endsection
