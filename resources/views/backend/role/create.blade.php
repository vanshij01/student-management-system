@extends('backend.layouts.app')
@section('title', 'Create Role')
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
    <div class="card h-100">
        <div class="card-header text-white py-3">
            <h5 class="card-title m-0 me-2 text-light">Add Role</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('role.store') }}" method="post" class="role_form" id="role_create_form">
                @csrf
                <div class="row"> 
                    <div class="col-md-6 mb-4">
                        <label for="name" class="form-label">Role</label>
                        <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}"
                            placeholder="Enter role" required />
                        @error('name')
                            <small class="red-text ml-10" role="alert">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                    <div class="card-footer text-end py-2">
                        <button type="button" class="btn secondary_btn back">Cancel</button>
                        <button type="submit" class="btn primary_btn">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/role/role.js') }}"></script>
@endsection
