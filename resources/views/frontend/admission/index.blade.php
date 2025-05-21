@extends('frontend.layouts.app')
@section('title', 'Admission Listing')
@section('styles')
    <style>
        .table tbody tr.odd {
            background-color: #EAF4FF;
        }

        table thead tr th {
            font-weight: 600;
        }

        .dataTables_processing {
            z-index: 99999;
        }

        .light-style .swal2-container {
            z-index: 99999;
        }

        [type="search"]::-webkit-search-cancel-button {
            -webkit-appearance: none;
            appearance: none;
            height: 10px;
            width: 10px;
            background-image: url('{{ asset('assets/img/branding/search-close.png') }}');
            background-size: 10px 10px;
        }

        div.dataTables_wrapper div.dataTables_length select {
            width: 80px;
        }

        div.dataTables_wrapper div.col-sm-12 {
            padding: 0 !important;
        }

        .admission-label {
            border-color: #ff4d49;
            pointer-events: none;
        }

        @media screen and (max-width: 425px) {
            .modal-dialog {
                display: flex;
                align-items: center;
                min-height: calc(100% - var(--bs-modal-margin)* 2);
            }
        }
    </style>
@endsection
@section('content')

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between py-2">
            <h5 class="card-title m-0 me-2 text-secondary">Admission</h5>

            <div class="d-flex gap-2">
                {{ $isStudentAdmissionExist }}
                @if ($admissionDate)
                    @if (date('Y-m-d') <= $admissionDate)
                    @else
                        <button class="btn btn-outline-danger admission-label">{{ $admissionLabel }}</button>
                    @endif
                @endif
                <a href={{ route('student.dashboard') }} class='btn btn-primary waves-effect waves-light text-white'><span
                        class='d-md-none d-sm-inline-block'><i class='mdi mdi-arrow-left me-sm-1'></i></span> <span
                        class='d-none d-sm-inline-block'>Back</span></a>
                @if (!$isStudentAdmissionExist)
                    @if (date('Y-m-d') <= $admissionDate)
                        <a href={{ route('student.admission.create') }}
                            class='btn btn-primary waves-effect waves-light text-white'><span
                                class='d-md-none d-sm-inline-block'><i class='mdi mdi-plus me-sm-1'></i></span> <span
                                class='d-none d-sm-inline-block'>Add
                                Admission</span></a>
                    @elseif(!$admissionDate && count($admissions) == 0)
                        <a href={{ route('student.admission.create') }}
                            class='btn btn-primary waves-effect waves-light text-white'><span
                                class='d-md-none d-sm-inline-block'><i class='mdi mdi-plus me-sm-1'></i></span> <span
                                class='d-none d-sm-inline-block'>Add
                                Admission</span></a>
                    @endif
                @endif
            </div>
        </div>

        @if (session('message'))
            <div class="alert alert-{{ session('status') }} alert-dismissible fade show" role="alert">
                <strong>{{ session('message') }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="card-body pt-0">
            <div class="card-datatable table-responsive pt-0">
                <table class="datatables-basic table table-bordered table-striped" id="admission_table">
                    <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Year</th>
                            <th>Admission Status</th>
                            <th>Student Name</th>
                            <th>Mobile Number</th>
                            <th>Qualification</th>
                            <th>Admission Date</th>
                            <th>Room Allotment</th>
                            <th>Uploaded Documents</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @foreach ($admissions as $key => $admission) --}}
                            {{-- {{ $admission }} --}}
                            <tr>
                                {{-- <td>{{ $key + 1 }}</td> --}}
                                <td>{{ $admission->year_of_addmission . '-' . ($admission->year_of_addmission + 1) }}</td>
                                <td>
                                    @if ($admission->is_admission_confirm == '0')
                                        <strong class="pending">Pending</strong>
                                    @elseif ($admission->is_admission_confirm == '1')
                                        <strong class="complete">Confirm</strong>
                                    @elseif ($admission->is_admission_confirm == '2')
                                        <strong class="rejected">Rejected</strong>
                                    @elseif ($admission->is_admission_confirm == '3')
                                        <strong class="rejected">Cancelled</strong>
                                    @elseif ($admission->is_admission_confirm == '4')
                                        <strong class="complete">Release</strong>
                                    @endif
                                </td>
                                <td>{{ $admission->full_name }}</td>
                                <td>{{ $admission->phone }}</td>
                                <td>{{ $admission->course_name }}</td>
                                <td>{{ $admission->created_at->format('d/m/Y') }}</td>
                                <td>
                                    @if ($admission->room_allocation)
                                        <br>
                                        Hostel Name: {{ $admission->room_allocation->hostel_name }} <br>
                                        Room Number : {{ $admission->room_allocation->room_number }} <br>
                                        Bed Number : {{ $admission->room_allocation->bed_number }}
                                    @else
                                        Not Alloted
                                    @endif
                                </td>
                                <td>
                                    @forelse ($admission->documents as $key => $item)
                                        @if (date('Y', strtotime($item->created_at)) <= $admission->year_of_addmission)
                                            <a href="{{ config('APP_URL') . '/' . $item->doc_url }}" target="_blank"
                                                rel="noopener noreferrer">{{ $item->doc_type }}</a>
                                            @if ($key < count($admission->documents) - 1)
                                                |
                                            @endif
                                        @endif
                                    @empty
                                        No Documents Uploaded
                                    @endforelse
                                </td>
                                <td><a class="waves-effect"
                                        href="{{ route('student.admission.edit', $admission->id) }}">Edit</a></td>
                            </tr>
                        {{-- @endforeach --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('#admission_table').dataTable({
                searching: true,
                lengthMenu: [10, 25, 50, 100, 1000, 10000],
            });

            setTimeout(function() {
                $('.alert').fadeOut('fast');
            }, 3000);
        });
    </script>
@endsection
