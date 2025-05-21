@extends('backend.layouts.app')
@section('title', 'Create Bed')
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
            <h5 class="card-title m-0 me-2 text-light">Add Bed</h5>
        </div>
        <form action="{{ route('bed.store') }}" method="post" id="bed_create_form" class="bed_form">
            @csrf
            <div class="card-body pb-0">
                <div class="row">
                    <div class="col-sm-6 col-lg-4 mb-4">
                        <label for="hostel_id">Hostel</label>
                        <select name="hostel_id" id="hostel_id" class="select2 form-select" data-placeholder="Select hostel"
                            data-parsley-errors-container="#hostel_id_errors" required
                            data-parsley-required-message="The hostel name field is required.">
                            <option value="">Select Hostel</option>
                            @foreach ($hostels as $hostel)
                                <option value="{{ $hostel->id }}">
                                    {{ $hostel->hostel_name }}</option>
                            @endforeach
                        </select>
                        <div id="hostel_id_errors"></div>
                    </div>
                    <div class="col-sm-6 col-lg-4 mb-4">
                        <label for="room_id">Room</label>
                        <select name="room_id" id="room_id" class="select2 form-select" data-placeholder="Select room"
                            data-parsley-errors-container="#room_id_errors" required
                            data-parsley-required-message="The room name field is required.">
                            <option value="">Select Room</option>
                        </select>
                        <div id="room_id_errors"></div>
                    </div>
                    <div class="col-sm-6 col-lg-4 mb-4">
                        <label for="bed_number">Bed Number</label>
                        <input type="text" class="form-control" name="bed_number" value="{{ old('bed_number') }}"
                            id="bed_number" placeholder="Enter bed number" required
                            data-parsley-required-message="The bed number field is required." />
                        <span id="bed_error" style="color: red; position: relative;"></span>
                    </div>
                    <div class="col-sm-6 col-lg-4 mb-4">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="select2 form-select" data-placeholder="Select status"
                            data-parsley-errors-container="#status_errors" required
                            data-parsley-required-message="The status field is required.">
                            <option value="">Select status</option>
                            <option @if (old('status') == '1') selected @endif value="1" selected>
                                Available
                            </option>
                            <option @if (old('status') == '0') selected @endif value="0">Not Available
                            </option>
                        </select>
                        <div id="status_errors"></div>
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
    <script src="{{ asset('js/bed/bed.js') }}"></script>
@endsection
