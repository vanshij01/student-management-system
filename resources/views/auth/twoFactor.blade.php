{{-- <x-guest-layout>
    <x-slot name="logo">
        <a href="/">
            <x-application-logo class="h-20 w-20 fill-current text-gray-500 dark:text-gray-400" />
        </a>
    </x-slot>
    <div class="mb-4 text-sm text-dark-500 dark:text-gray-400">
        {{ __('A Login authentication Code is Sent to You By Email, Please Enter it in the Field Below:') }}
    </div>
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <form method="POST" action="{{ route('verify.store') }}">
        @csrf
        <div class="mb-4">
            <x-input-label for="two_factor_code" :value="__('Code')" />
            <x-text-input id="two_factor_code" class="mt-1 block w-full" type="text" name="two_factor_code" required
                autofocus />
            <x-input-error :messages="$errors->get('two_factor_code')" class="mt-2" />
        </div>
        <div class="mt-4 text-sm text-dark-500 dark:text-gray-400">
            Haven't Received The Code Yet?
        </div>

        <div class="flex items-center mt-4" style="justify-content: space-between;">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none"
                href="{{ route('verify.resend') }}">
                {{ __('Click Here to Resend') }}
            </a>
            <button class="btn btn-primary btm-sm">{{ __('Submit') }}</button>
        </div>
    </form>
</x-guest-layout> --}}

@extends('backend.layouts.guest')
@section('styles')
    <style>
        .error-message {
            display: block;
            width: 100%;
            text-align: center;
        }
    </style>
@endsection
@section('content')
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="otp-form-wrapper d-flex flex-column justify-content-center align-items-center ">
        <h3 class="form-right-title">Verify Your Account</h3>
        <p class="otp-form-desc text-center">Please enter the 6-digit code we sent to <strong
                class="d-block">{{ auth()->user()->email }}</strong></p>
        <form class="otp-box d-flex flex-wrap justify-content-center p-0" method="POST" action="{{ route('verify.store') }}">
            @csrf
                @for ($i = 0; $i < 6; $i++)
                    <input type="text" name="two_factor_code[{{ $i }}]" maxlength="1" class="otp-input"
                        autocomplete="off" autofocus>
                @endfor
            @error('two_factor_code.5')
                <small class="red-text ml-10 error-message pt-4" role="alert">
                    {{ $message }}
                </small>
            @enderror
            @error('two_factor_code')
                <small class="red-text ml-10 error-message pt-4" role="alert">
                    {{ $message }}
                </small>
            @enderror
            <button class="form-btn">Verify</button>
        </form>
        <p class="form-info-text text-center">Haven't received the OTP? <a href="{{ route('verify.resend') }}"
                class="info-txt">Resend</a></p>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            const $inputs = $('.otp-input');

            $inputs.on('input', function() {
                let $this = $(this);
                if ($this.val().length === 1) {
                    $this.next('.otp-input').focus();
                }
            });

            $inputs.on('keydown', function(e) {
                let $this = $(this);
                if (e.key === 'Backspace' && $this.val() === '') {
                    $this.prev('.otp-input').focus();
                }
            });

            $inputs.on('paste', function(e) {
                e.preventDefault();
                const pasteData = e.originalEvent.clipboardData.getData('text').trim();

                if (!/^\d{6}$/.test(pasteData)) return;

                pasteData.split('').forEach((char, i) => {
                    $inputs.eq(i).val(char);
                });

                $inputs.eq(5).focus(); // Focus last field
            });
        });
    </script>
@endsection
