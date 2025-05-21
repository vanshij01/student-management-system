@extends('backend.layouts.app')
@section('title', 'Create Room')
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
            <h5 class="card-title m-0 me-2 text-light">Add Room</h5>
        </div>
        <form action="{{ route('room.store') }}" method="post" id="room_create_form" class="room_form">
            @csrf
            <div class="card-body pb-0">
                <div class="row">
                    <div class="col-sm-6 col-lg-4 mb-4">
                        <label for="hostel_id">Hostel</label>
                        <select name="hostel_id" id="hostel_id" class="select2 form-select" data-placeholder="Select hostel"
                            data-parsley-errors-container="#hostel_id_errors" required
                            data-parsley-required-message="The hostel name field is required.">
                            <option value="">Select hostel</option>
                            @foreach ($hostels as $hostel)
                                <option value="{{ $hostel->id }}">
                                    {{ $hostel->hostel_name }}</option>
                            @endforeach
                        </select>
                        <div id="hostel_id_errors"></div>
                        @error('hostel_id')
                            <small class="red-text ml-10" role="alert">
                                {{ $message }}
                            </small>
                        @enderror
                        <small id="warden_errors" class="errors red-text"></small>
                    </div>
                    <div class="col-sm-6 col-lg-4 mb-4">
                        <label for="room_number">Room Number</label>
                        <input type="text" class="form-control" name="room_number" value="{{ old('room_number') }}"
                            id="room_number" placeholder="Enter room number" required
                            data-parsley-required-message="The room number field is required." />
                        <span id="room_error" style="color: red; position: relative;"></span>
                        @error('room_number')
                            <small class="red-text ml-10" role="alert">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                    <div class="col-sm-6 col-lg-4 mb-4">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="select2 form-select" data-placeholder="Select status"
                            data-parsley-errors-container="#status_errors" required
                            data-parsley-required-message="The status field is required.">
                            <option value="">Select status</option>
                            <option @if (old('status') == '1') selected @endif value="1" selected>
                                Enable
                            </option>
                            <option @if (old('status') == '0') selected @endif value="0">Disable
                            </option>
                        </select>
                        <div id="status_errors"></div>
                        @error('status')
                            <small class="red-text ml-10" role="alert">
                                {{ $message }}
                            </small>
                        @enderror
                        <small id="status_error" class="errors red-text"></small>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end pt-0">
                <button type="button" class="btn secondary_btn back">Cancel</button>
                <button type="submit" class="btn primary_btn ">Save</button>
            </div>
        </form>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/room/room.js') }}"></script>
@endsection
