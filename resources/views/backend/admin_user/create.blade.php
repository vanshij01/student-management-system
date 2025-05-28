@extends('backend.layouts.app')
@section('title', 'Create Admin User')
@section('styles')
    <style>
        .parsley-errors-list {
            margin: 0;
            padding: 0;
            list-style-type: none;
        }
    </style>
@endsection
@section('content')
    <div class="card">
        <div class="card-header text-white py-3">
            <h5 class="card-title m-0 me-2 text-light">Add Admin User</h5>
        </div>
        <form action="{{ route('admin_user.store') }}" method="post" id="admin_user_create_form" class="admin_user_form">
            @csrf
            <div class="card-body pb-0">
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" value="{{ old('name') }}" id="name"
                            placeholder="Enter name" required data-parsley-required-message="The name field is required." />
                        <span id="name_error" class="red-text"></span>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="{{ old('email') }}" id="email"
                            placeholder="Enter email" required
                            data-parsley-required-message="The email field is required." />
                        <span id="email_error" class="red-text"></span>
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
                        <label for="role_id" class="form-label">Role</label>
                        <select name="role_id" id="role_id" class="select2 form-select" data-placeholder="Select Role"
                            data-parsley-errors-container="#role_id_errors" required
                            data-parsley-required-message="The role field is required.">
                            <option value="">Select Role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{$role->name}}</option>
                            @endforeach
                        </select>
                        <div id="role_id_errors"></div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="status" class="form-label">Status</label>
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
    <script src="{{ asset('js/admin_user/admin_user.js') }}"></script>
@endsection
