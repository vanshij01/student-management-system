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

@extends('backend.layouts.app')
@section('title', 'Change Password')

@section('styles')
@endsection
@section('content')
    <div class="container-fluid">
        <h4 class="py-3 mb-4">
            <span class="text-muted fw-light">Account Settings /</span> Security
        </h4>

        <div class="row">
            <div class="col-md-12">
                <!-- Change Password -->
                <div class="card mb-4">
                    <h5 class="card-header">Change Password</h5>
                    <div class="card-body">
                        @if (session('status') === 'password-updated')
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Password changed successfully!!!</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        <form method="POST" enctype="multipart/form-data" action="{{ route('password.update') }}">
                            @csrf
                            @method('put')

                            <div class="row">
                                <div class="input-field col-sm-12 col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input type="text" class="form-control" name="current_password"
                                            id="current_password" value="{{ old('current_password') }}" placeholder="Current Password" />
                                        <label for="current_password">Current Password</label>
                                        <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" style="color: red;" />
                                    </div>
                                </div>
                                <div class="input-field col-sm-12 col-md-6">
                                </div>
                                <div class="input-field col-sm-12 col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input type="text" class="form-control" name="password"
                                            id="password" value="{{ old('password') }}" placeholder="New Password" />
                                        <label for="password">New Password</label>
                                        <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" style="color: red;"/>
                                    </div>
                                </div>
                                <div class="input-field col-sm-12 col-md-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input type="text" class="form-control" name="password_confirmation"
                                            id="password_confirmation" value="{{ old('password_confirmation') }}" placeholder="Confirm New Password" />
                                        <label for="password_confirmation">Confirm New Password</label>
                                        <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" style="color: red;"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <a href="{{ route('dashboard') }}" class="btn btn-primary">Back</a>
                                </div>
                                <div class="col-6 text-end">
                                    <button type="submit" class="btn btn-primary">Save change<i
                                            class="material-icons right"></i></button>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!--/ Change Password -->
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('.alert').fadeOut('fast');
            }, 3000);
        });
    </script>
@endsection
