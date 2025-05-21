{{-- <section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Current Password')" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('New Password')" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'password-updated')
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
</section> --}}

@extends('frontend.layouts.app')
@section('title', 'Change Password')

@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css" />
@endsection
@section('content')
    @if (Auth::user()->role_id != 4)
        <h4 class="py-3 mb-4">
            <span class="text-muted fw-light">Account Settings /</span> Security
        </h4>
    @endif

    {{-- <div class="row">
        <div class="col-md-12">
            <!-- Change Password -->
            <div class="card mb-4">
                <h5 class="card-header text-secondary">Change Password</h5>
                <div class="card-body">
                    @if (session('status') === 'password-updated')
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Password changed successfully!!!</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <form method="POST" enctype="multipart/form-data" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')

                        <div class="row">
                            <div class="input-field col-sm-12 col-md-6">
                                <div class="form-floating form-floating-outline mb-4">
                                    <input autocomplete="off" type="text" class="form-control" name="current_password"
                                        id="current_password" value="{{ old('current_password') }}"
                                        placeholder="Current Password" />
                                    <label for="current_password">Current Password</label>
                                    <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" style="color: red;" />
                                </div>
                            </div>
                            <div class="input-field col-sm-12 col-md-6">
                            </div>
                            <div class="input-field col-sm-12 col-md-6">
                                <div class="form-floating form-floating-outline mb-4">
                                    <input autocomplete="off" type="text" class="form-control" name="password"
                                        id="password" value="{{ old('password') }}" placeholder="New Password" />
                                    <label for="password">New Password</label>
                                    <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" style="color: red;" />
                                </div>
                            </div>
                            <div class="input-field col-sm-12 col-md-6">
                                <div class="form-floating form-floating-outline mb-4">
                                    <input autocomplete="off" type="text" class="form-control"
                                        name="password_confirmation" id="password_confirmation"
                                        value="{{ old('password_confirmation') }}" placeholder="Confirm New Password" />
                                    <label for="password_confirmation">Confirm New Password</label>
                                    <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" style="color: red;" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-4">
                                @if (Auth::user()->role_id == 4)
                                    <a href="{{ route('student.dashboard') }}" class="btn btn-primary">Back</a>
                                @else
                                    <a href="{{ route('dashboard') }}" class="btn btn-primary">Back</a>
                                @endif
                            </div>
                            <div class="col-md-6 col-sm-8 text-end">
                                <button type="submit" class="btn btn-primary">Save change<i
                                        class="material-icons right"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!--/ Change Password -->
        </div>
    </div> --}}


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
                <h2 class="admission_form_title">Change Password</h2>
            </div>

            @if (session('status') === 'password-updated')
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Password changed successfully!!!</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="POST" enctype="multipart/form-data" action="{{ route('password.update') }}" class="form-section"
                id="change_password">
                @csrf
                @method('put')

                <div class="row mb-4">
                    <div class="col input-box">
                        <label class="form-input-label" for="password">Current Password</label>
                        <div class="form-password-toggle">
                            <div class="input-group input-group-merge d-block">
                                <div class="position-relative">
                                    <input type="password" id="current_password" class="form-control"
                                        name="current_password" value="{{ old('current_password') }}"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password" data-parsley-errors-container="#current_password_errors"
                                        required minlength="8"
                                        data-parsley-required-message="The current password field is required." />
                                    <span class="input-group-text cursor-pointer password-eye-icon"><i
                                            class="mdi mdi-eye-off-outline"></i></span>
                                </div>
                            </div>
                            @if ($errors->updatePassword->has('current_password'))
                                <small class="red-text ml-10 mt-2" role="alert" style="color: red;">
                                    {{ $errors->updatePassword->first('current_password') }}
                                </small>
                            @endif
                            <div id="current_password_errors"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col input-box">
                        <label class="form-input-label" for="password">New Password</label>
                        <div class="form-password-toggle">
                            <div class="input-group input-group-merge d-block">
                                <div class="position-relative">
                                    <input type="password" id="password" class="form-control" name="password"
                                        value="{{ old('password') }}"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password" data-parsley-errors-container="#password_errors"
                                        required minlength="8"
                                        data-parsley-required-message="The password field is required." />
                                    <span class="input-group-text cursor-pointer password-eye-icon"><i
                                            class="mdi mdi-eye-off-outline"></i></span>
                                </div>
                            </div>
                            @if ($errors->updatePassword->has('password'))
                                <small class="red-text ml-10 mt-2" role="alert" style="color: red;">
                                    {{ $errors->updatePassword->first('password') }}
                                </small>
                            @endif
                            <div id="password_errors"></div>
                        </div>
                    </div>
                    <div class="col input-box">
                        <label class="form-input-label" for="retypePassword">Confirm New Password</label>
                        <div class="form-password-toggle">
                            <div class="input-group input-group-merge d-block">
                                <div class="position-relative">
                                    <input type="password" id="password_confirmation" class="form-control"
                                        name="password_confirmation" value="{{ old('password_confirmation') }}"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password"
                                        data-parsley-errors-container="#password_confirmation_errors" required
                                        minlength="8" data-parsley-required-message="The password field is required." />
                                    <span class="input-group-text cursor-pointer password-eye-icon"><i
                                            class="mdi mdi-eye-off-outline"></i></span>
                                </div>
                            </div>
                            @if ($errors->updatePassword->has('password_confirmation'))
                                <small class="red-text ml-10 mt-2" role="alert" style="color: red;">
                                    {{ $errors->updatePassword->first('password') }}
                                </small>
                            @endif
                            <div id="password_confirmation_errors"></div>
                        </div>
                    </div>
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
            $('#change_password').parsley();

            setTimeout(function() {
                $('.alert').fadeOut('fast');
            }, 3000);

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
