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
    @if (session('message'))
        <div class="alert alert-{{ session('status') }} alert-dismissible fade show mb-0 mt-4" role="alert">
            <strong>{{ session('message') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <h3 class="form-right-title">Login</h3>
    <p class="form-right-desc">Welcome Back!</p>
    <form id="login_form" class="mb-3" method="POST" action="{{ route('login') }}">
        @csrf
        @error('email')
            <p class="red-text ml-10 d-block text-start" role="alert">
                {{ $message }}
            </p>
        @enderror
        <div class="input-box">
            <label class="form-input-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}"
                placeholder="example@gmail.com" required data-parsley-required-message="The email field is required." />
        </div>
        <div class="input-box">
            <label class="form-input-label" for="password">Password</label>
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
        <div class="d-flex forgot-pass-div justify-content-end">
            <a href="{{ route('password.request') }}" class="text-forgot-pass">Forgot Password?</a>
        </div>
        {{-- <button class="form-btn">Login</button> --}}
        <button type="submit" class="form-btn" id="login-btn">Login</button>
        <p class="form-info-text text-center">
            Not registered yet?
            <a href="{{ route('register') }}" class="info-txt">Create an Account</a>
        </p>
    </form>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            const $form = $('#login_form');
            const $btn = $('#login-btn');

            // Initialize Parsley
            $form.parsley();

            // On form submit
            $form.on('submit', function(e) {
                if ($form.parsley().isValid()) {
                    $btn.prop('disabled', true);
                    $btn.text('Logging in...');
                } else {
                    // Stop form submission if invalid
                    e.preventDefault();
                }
            });

            // Re-enable button if any field becomes invalid (e.g., user left a required field blank)
            $form.parsley().on('field:error', function() {
                $btn.prop('disabled', false);
                $btn.text('Login');
            });

            // Optional: Handle form reset or field change if needed
            $form.parsley().on('field:validate', function() {
                if (!$form.parsley().isValid()) {
                    $btn.prop('disabled', false);
                    $btn.text('Login');
                }
            });

            // Password toggle
            $(document).on('click', '.form-password-toggle .input-group-text.cursor-pointer', function() {
                var $input = $(this).closest('.input-group').find('input');
                var $icon = $(this).find('i');

                if ($input.attr('type') === 'password') {
                    $input.attr('type', 'text');
                    $icon.removeClass('mdi-eye-off-outline').addClass('mdi-eye-outline');
                } else {
                    $input.attr('type', 'password');
                    $icon.removeClass('mdi-eye-outline').addClass('mdi-eye-off-outline');
                }
            });

            // Auto dismiss alerts
            setTimeout(function() {
                $('.alert').fadeOut('fast');
            }, 3000);
        });
    </script>
@endsection
