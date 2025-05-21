{{-- <x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}

{{-- @extends('backend.layouts.guest')
@section('styles')
@endsection
@section('content')
    <div class="card p-2">
        <div class="app-brand justify-content-center mt-2">
            <a href="/" class="d-flex justify-content-center" style="display: flex; justify-content: center;">
                <img src="{{ asset('images/header-logo.svg') }}" class="main-logo" alt="">
            </a>
        </div>
        <p class="text-center pt-4 m-0">
            Forgot your password? No problem. Just let us know your email address and we will email you a password reset
            link that will allow you to choose a new one.
        </p>

        <!-- Session Status -->
        <x-auth-session-status class="text-center py-4 m-0 text-success" :status="session('status')" style="color: red;" />

        <div class="card-body py-0">
            <form id="formAuthentication" class="mb-3" method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="form-floating form-floating-outline mb-3">
                    <input type="text" class="form-control" id="email" name="email" placeholder="Enter your email"
                        autofocus />
                    <label for="email">Email</label>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" style="color: red;" />
                </div>
                <div class="mb-3">
                    <button class="btn btn-primary d-grid w-100" type="submit">Email Password Reset Link</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
@endsection --}}

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

    <h3 class="form-right-title">Forgot Password</h3>
    <p class="text-center pt-4 m-0">
        Forgot your password? No problem. Just let us know your email address and we will email you a password reset
        link that will allow you to choose a new one.
    </p>
    <x-auth-session-status class="text-center py-4 m-0 text-success" :status="session('status')" style="color: red;" />

    <form id="forgot_password_form" class="mb-3" method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="input-box">
            <label class="form-input-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}"
                placeholder="example@gmail.com" required data-parsley-required-message="The email field is required."
                autofocus />
            {{-- <input type="text" class="form-control" id="email" name="email" placeholder="Enter your email"
                autofocus /> --}}
        </div>
        @error('email')
            <p class="red-text ml-10 d-block text-start" role="alert">
                {{ $message }}
            </p>
        @enderror
        {{-- <x-input-error :messages="$errors->get('email')" class="mt-2" style="color: red;" /> --}}
        <button type="submit" class="form-btn">Email Password Reset Link</button>
        <p class="form-info-text text-center">Already have an account? <a href="{{ route('login') }}"
                class="info-txt">Login here!</a>
    </form>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $("#forgot_password_form").parsley();
        });
    </script>
@endsection
