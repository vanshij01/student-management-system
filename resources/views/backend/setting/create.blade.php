@extends('backend.layouts.app')
@section('title', 'Create Setting')
@section('styles')
    <style>
        .red-text {
            color: red;
        }
    </style>
@endsection
@section('content')
    <div class="card">
        <div class="card-header text-white py-3">
            <h5 class="card-title m-0 me-2 text-light">Update Setting</h5>
        </div>
        <form action="{{ route('setting.store') }}" method="post" id="setting_form">
            @csrf
            <input type="hidden" name="setting[0][key]" value="new_admission_date">
            <input type="hidden" name="setting[1][key]" value="old_admission_date">
            <input type="hidden" name="setting[2][key]" value="new_admission_label">
            <input type="hidden" name="setting[3][key]" value="old_admission_label">
            <input type="hidden" name="setting[4][key]" value="start_attendance">
            <input type="hidden" name="setting[5][key]" value="end_attendance">
            <input type="hidden" name="setting[6][key]" value="last_updated_by">
            <div class="card-body pb-0">
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="new_admission_date" class="form-label">New Admission Date</label>
                        <input type="text" class="form-control date" name="setting[0][value]"
                            value="{{ date('d/m/Y', strtotime(old('new_admission_date', $newDate))) }}"
                            placeholder="DD/MM/YYYY" id="new_admission_date" required />
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="old_admission_date" class="form-label">Old Admission Date</label>
                        <input type="text" class="form-control date" name="setting[1][value]"
                            value="{{ date('d/m/Y', strtotime(old('old_admission_date', $oldDate))) }}"
                            placeholder="DD/MM/YYYY" id="old_admission_date" required />
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="new_admission_label" class="form-label">New Admission Label</label>
                        <input type="text" class="form-control" name="setting[2][value]"
                            value="{{ old('new_admission_label', $newLabel) }}" id="new_admission_label"
                            placeholder="Enter new admission label" required />
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="old_admission_label" class="form-label">Old Admission Label</label>
                        <input type="text" class="form-control" name="setting[3][value]"
                            value="{{ old('old_admission_label', $oldLabel) }}" id="old_admission_label"
                            placeholder="Enter old admission label" required />
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="start_time" class="form-label">Start Time</label>
                        <input type="text" class="form-control time" name="setting[4][value]"
                            value="{{ old('start_time', $startAttendance) }}" placeholder="DD/MM/YYYY" id="start_time"
                            required />
                        @error('start_time')
                            <small class="red-text ml-10" role="alert">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="end_time" class="form-label">End Time</label>
                        <input type="text" class="form-control time" name="setting[5][value]"
                            value="{{ old('end_time', $endAttendance) }}" placeholder="DD/MM/YYYY" id="end_time"
                            required />
                        @error('end_time')
                            <small class="red-text ml-10" role="alert">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="card-footer text-end py-2">
                <button type="button" class="btn secondary_btn back">Cancel</button>
                <button type="submit" class="btn primary_btn">Update</button>
            </div>
        </form>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('.back').on('click', function() {

                location.href = "/setting";
            });
            $('.date').flatpickr({
                dateFormat: 'd/m/Y',
            });

            $('#start_time').flatpickr({
                enableTime: true,
                noCalendar: true,
            });

            $('#start_time').on('change', function() {
                var fromDate = $(this).val();
                $('#end_time').flatpickr({
                    enableTime: true,
                    noCalendar: true,
                    minDate: fromDate
                });
            });
        });
    </script>
@endsection
