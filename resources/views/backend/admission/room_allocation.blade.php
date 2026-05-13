@extends('backend.layouts.app')

@section('title', 'Room Allocation Import')

@section('styles')
    <style>
        .dataTables_scrollBody {
            overflow: unset !important;
        }

        /* Disable alert fade effect */
        .alert {
            opacity: 1 !important;
            transition: none !important;
            animation: none !important;
        }

        .persist-alert {
            display: block !important;
        }
    </style>
@endsection

@section('content')
    <div class="container mt-5">
        <h3 class="mb-4">Import Room Allocation</h3>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible persist-alert" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Import Errors --}}
        @if (session('importErrors'))
            <div class="alert alert-danger alert-dismissible persist-alert" role="alert">
                <ul class="mb-0">
                    @foreach (session('importErrors') as $error)
                        <li>
                            <strong>Row {{ $error['row'] }}:</strong>
                            {{ implode(', ', $error['messages']) }}
                        </li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- File Validation Error --}}
        @if ($errors->has('import_file'))
            <div class="alert alert-danger alert-dismissible persist-alert" role="alert">
                <strong>Error:</strong> {{ $errors->first('import_file') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Upload Form --}}
        <form action="{{ route('room.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="import_file" class="form-label">Select Excel File</label>
                <input type="file" name="import_file" id="import_file" class="form-control" accept=".xls,.csv" required>
            </div>

            <div class="d-flex align-items-center gap-3">
                <button type="submit" class="btn btn-primary">Import Room Allocation</button>

                <a href="{{ asset('assets/sampleCSV/Sample_Room_Allotment.xls') }}" download class="btn btn-secondary">
                    Sample Sheet
                </a>
            </div>
        </form>

        {{-- Logs Table --}}
        <hr class="my-5">
        <h4>Recent Room Allotment Logs</h4>
        <div class="table_content_wrapper">
            <div class="data_table_wrap">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="room_allotment_logs">
                        <thead>
                            <tr>
                                <th></th>
                                <th>#</th>
                                <th>Event</th>
                                <th>Student</th>
                                <th>Admission ID</th>
                                <th>Student ID</th>
                                <th>Description</th>
                                <th>Hostel</th>
                                <th>Room</th>
                                <th>Bed</th>
                                <th>Logged At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($logs) && $logs->count())
                                @foreach ($logs as $index => $log)
                                    @php
                                        $properties = json_decode($log->properties, true);
                                        $data = $properties['attributes'] ?? [];
                                    @endphp
                                    <tr>
                                        <td></td>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $log->event }}</td>
                                        <td>{{ $data['student_name'] ?? '—' }}</td>
                                        <td>{{ $data['admission_id'] ?? '—' }}</td>
                                        <td>{{ $data['student_id'] ?? '—' }}</td>
                                        <td>{{ $log->description }}</td>
                                        <td>{{ $data['hostel'] ?? '—' }}</td>
                                        <td>{{ $data['room'] ?? '—' }}</td>
                                        <td>{{ $data['bed'] ?? '—' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($log->created_at)->format('d-m-Y h:i A') }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="11" class="text-center">No logs available.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#room_allotment_logs').DataTable({
                responsive: true,
                autoWidth: false,
                scrollX: true,
                ordering: true,
                pageLength: 10,
                dom: '<"data-table-header d-flex justify-content-between align-items-center mb-3"lBf>tip',
                buttons: [{
                    extend: 'csvHtml5',
                    text: '<i class="las la-download"></i> Export Data',
                    className: 'secondary_btn',
                    exportOptions: {
                        columns: ':not(:first-child)' // Exclude index column
                    },
                }],
                columnDefs: [{
                    className: 'control',
                    orderable: false,
                    searchable: false,
                    responsivePriority: 1,
                    targets: 0,
                }],
            });
        });
        $(document).ready(function() {
            $('.persist-alert').each(function() {
                $(this).removeClass('fade show').css({
                    opacity: 1,
                    display: 'block'
                });
            });

            const nativeSetTimeout = window.setTimeout;
            window.setTimeout = function(func, delay) {
                if (typeof func === 'function' && func.toString().includes('alert')) {
                    return;
                }
                return nativeSetTimeout(func, delay);
            };

            if ($.fn.fadeOut) {
                const originalFadeOut = $.fn.fadeOut;
                $.fn.fadeOut = function() {
                    if (this.hasClass('alert') || this.hasClass('persist-alert')) {
                        return this;
                    }
                    return originalFadeOut.apply(this, arguments);
                };
            }
        });
    </script>
@endsection
