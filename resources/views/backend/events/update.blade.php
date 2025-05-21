@extends('backend.layouts.app')
@section('title', 'Update Events')
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
            <h5 class="card-title m-0 me-2 text-light">Update Event</h5>
        </div>
        <form action="{{ route('event.update', $event->id) }}" method="post" id="event_form">
            @csrf
            @method('PUT')
            <div class="card-body pb-0">
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" value="{{ old('name', $event->name) }}"
                            id="name" placeholder="Enter name" required
                            data-parsley-required-message="The name field is required." />
                        <span id="name_error" class="red-text"></span>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" class="form-control" name="location"
                            value="{{ old('location', $event->location) }}" id="location" placeholder="Enter location"
                            required data-parsley-required-message="The location field is required." />
                        <span id="location_error" class="red-text"></span>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="start_datetime" class="form-label">Start Datetime</label>
                        <input type="text" class="form-control time" name="start_datetime"
                            value="{{ date('d/m/Y H:i', old(strtotime('start_datetime'), strtotime($event->start_datetime))) }}"
                            placeholder="DD/MM/YYYY" id="start_datetime" required
                            data-parsley-required-message="The start datetime field is required." />
                        @error('start_datetime')
                            <small class="red-text ml-10" role="alert">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="end_datetime" class="form-label">End Datetime</label>
                        <input type="text" class="form-control time" name="end_datetime"
                            value="{{ date('d/m/Y H:i', old(strtotime('end_datetime'), strtotime($event->end_datetime))) }}"
                            placeholder="DD/MM/YYYY" id="end_datetime" required
                            data-parsley-required-message="The end datetime field is required." />
                        @error('end_datetime')
                            <small class="red-text ml-10" role="alert">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="note" class="form-label">Note</label>
                        <input type="text" class="form-control" name="note" value="{{ old('note', $event->note) }}"
                            id="note" placeholder="Enter note" />
                        @error('note')
                            <small class="red-text ml-10" role="alert">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="card-footer text-end py-2">
                <button type="button" class="btn secondary_btn back">Cancel</button>
                <button type="submit" class="btn primary_btn ">Update</button>
            </div>
        </form>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/event/event.js') }}"></script>
@endsection
