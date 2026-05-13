@extends('backend.layouts.app')
@section('title', 'View Admission')
@section('styles')
    <style>
        h6 {
            margin: 10px;
        }

        label {
            margin-left: 8px;
        }

        .card-body hr {
            margin-top: 15px;
            border: 1px solid #D8D8DD
        }

        .border-right {
            border-right: 1px solid #D8D8DD;
        }

        .student_img {
            width: 150px;
            height: 150px;
        }

        .student_img_div {
            width: 150px;
            flex-wrap: wrap;
            justify-content: center;
            gap: 50px;
        }

        .image-container {
            position: relative;
            width: 100%;
            height: auto;
            overflow: hidden;
        }

        .parsley-errors-list {
            margin: 0;
            padding: 0;
            list-style-type: none;
        }

        .nav-pills .nav-link.active {
            background-color: #FFB42D;
        }

        .nav-link,
        .nav-link:focus,
        .nav-link:hover {
            color: #898989;
        }

        .view_img.magnified {
            transform: scale(2);
            /* adjust scale as needed */
            transition: transform 0.2s ease;
            cursor: zoom-out;
        }

        .view_img {
            transition: transform 0.2s ease;
            cursor: zoom-in;
        }

        table.dataTable {
            padding-top: 20px;
        }

        table.dataTable>thead>tr>th {
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
            color: #636578;
            font-weight: 500px;
            background-color: #F8F9FA;
        }

        table.dataTable>thead>tr>td {
            font-size: 15px;
            letter-spacing: 1px;
            color: #636578;
        }

        .dataTables_filter label input {
            width: auto !important;
        }

        .isAdmissionNew {
            /* padding: 6px 20px;
                                                    min-height: auto; */
            pointer-events: none;
        }

        .data_table_wrap {
            overflow-x: scroll;
        }

        @media screen and (max-width: 1024px) {
            .img-display {
                flex-wrap: wrap;
                justify-content: center;
                flex-direction: column-reverse;
                align-items: center;
            }

            .student_img_div {
                flex-wrap: nowrap;
                width: 100%;
                justify-content: space-around;
            }
        }

        @media screen and (max-width: 767px) {
            .header-button {
                flex-wrap: wrap;
                justify-content: space-between;
            }

            .back,
            .edit {
                width: 48%;
            }

            .status_btn {
                width: 100%
            }

            .data_table_wrap {
                overflow-x: auto;
            }
        }

        @media screen and (max-width: 426px) {
            .border-right {
                border-right: none;
            }
        }
    </style>
@endsection
@section('content')
    @php
        $updateCheck = \App\Models\Permission::checkCRUDPermissionToUser('Admission', 'update');
        $isSuperAdmin = \App\Models\Permission::isSuperAdmin();

    @endphp
    <div class="card mb-4">
        <div id="alertBox" class="alert alert-success alert-dismissible fade show d-none" role="alert">
            <strong id="alertMessage"></strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <div class="card-header d-md-flex d-sm-block align-items-center justify-content-between py-md-2">
            <h5 class="card-title m-0 me-2 text-white d-none d-md-block">View Admission Details <button type="button"
                    class="btn secondary_btn isAdmissionNew">{{ $admission->is_admission_new == 1 ? 'New' : 'Old' }}</button>
            </h5>
            <h3 class="card-title m-0 me-2 text-white d-block d-md-none">View Admission Details</h3>

            <div class="d-flex gap-2 mt-4 mt-md-0 header-button">
                <button type="button" class="btn secondary_btn back">Back</button>
                @if ($updateCheck)
                    <button type="button" class="btn secondary_btn edit"
                        data-id="{{ $admission->admission_id }}">Update</button>
                @endif
                @if ($isSuperAdmin)
                    <button type="button" class="btn secondary_btn status_btn"
                        onclick="sendStatusRemark({{ $admission->admission_id }})">Admission Status</button>
                    <button type="button" class="btn secondary_btn status_btn"
                        onclick="sendBacklogStatus({{ $admission->admission_id }})">Update Backlog Status</button>
                @endif
                <button type="button" class="btn secondary_btn status_btn"
                    onclick="sendComment({{ $admission->admission_id }})">Add Comment</button>
            </div>
        </div>

        <div class="card-body">
            <h4>Student Details</h4>
            <div class="d-flex gap-5 img-display">
                <div class="row w-100">
                    <div class="col-md-4 border-right mb-2 mb-md-0">
                        <h6>Name</h6>
                        <label>{{ $admission->full_name }}</label>
                    </div>
                    <hr class="d-md-none m-0">
                    <div class="col-md-4 border-right mb-2 mb-md-0">
                        <h6>Gender</h6>
                        <label>{{ $admission->gender }}</label>
                    </div>
                    <hr class="d-md-none m-0">
                    <div class="col-md-4 mb-2 mb-md-0">
                        <h6>DOB</h6>
                        <label>{{ date('d/m/Y', strtotime($admission->dob)) }}</label>
                    </div>
                    <hr class="mb-0 mt-0 mb-md-4 mt-md-4">
                    <div class="col-md-4 border-right mb-2 mb-md-0">
                        <h6>Age (in years)</h6>
                        <label>{{ $admission->age }}</label>
                    </div>
                    <hr class="d-md-none m-0">
                    <div class="col-md-4 border-right mb-2 mb-md-0">
                        <h6>Contact No</h6>
                        <label>{{ $admission->phone }}</label>
                    </div>
                    <hr class="d-md-none m-0">
                    <div class="col-md-4 mb-2 mb-md-0">
                        <h6>Email Id</h6>
                        <label>{{ $admission->email }}</label>
                    </div>
                    <hr class="mb-0 mt-0 mb-md-4 mt-md-4">
                    <div class="col-md-4 border-right mb-2 mb-md-0">
                        <h6>Permanent Address</h6>
                        <label>{{ $admission->residence_address }}</label>
                    </div>
                    <hr class="d-md-none m-0">
                    <div class="col-md-4 border-right mb-2 mb-md-0">
                        <h6>Village</h6>
                        <label>{{ $admission->village_name }}</label>
                    </div>
                    <hr class="d-md-none m-0">
                    <div class="col-md-4 mb-2 mb-md-0">
                        <h6>Country</h6>
                        <label>{{ $admission->country_name }}</label>
                    </div>
                    <hr class="mb-0 mt-0 mb-md-4 mt-md-4">
                    <div class="col-md-4 border-right mb-2 mb-md-0">
                        <h6>Citizen of India</h6>
                        <label>{{ $admission->is_indian_citizen == 1 ? 'Yes' : 'No' }}</label>
                    </div>
                    <hr class="d-md-none m-0">
                    @if ($admission->is_indian_citizen == 1)
                        <div class="col-md-4 border-right mb-2 mb-md-0">
                            <h6>Aadhar No</h6>
                            <label>{{ $admission->adhaar_number }}</label>
                        </div>
                    @else
                        <div class="col-md-4 border-right mb-2 mb-md-0">
                            <h6>Passport No</h6>
                            <label>{{ $admission->passport_number }}</label>
                        </div>
                    @endif
                    <hr class="d-md-none m-0">
                    <div class="col-md-4 mb-2 mb-md-0">
                        <h6>Any physical or mental illness</h6>
                        <label>{{ $admission->is_any_illness == 1 ? 'Yes' : 'No' }}</label>
                    </div>
                    @if ($admission->is_any_illness == 1)
                        <hr class="mb-0 mt-0 mb-md-4 mt-md-4">
                        <div class="col-md-4 border-right mb-2 mb-md-0">
                            <h6>Illness in brief</h6>
                            <label>{{ $admission->illness_description ?? '' }}</label>
                        </div>
                    @endif
                </div>
                <div class="d-flex align-items-center">
                    {{-- <a href="img"><img src="{{ asset($admission->student_photo_url) }}" alt="" class="student_img"></a> --}}
                    <a class="img">
                        <img src="{{ asset($admission->student_photo_url) }}" alt="Student Photo" class="student_img">
                    </a>
                </div>
            </div>
        </div>
    </div>
    @if ($admission->is_used_vehicle)
        <div class="card mb-4">
            <div class="card-body">
                <h4>Vehicle Details</h4>
                <div class="row">
                    <div class="col-md-4 border-right mb-2 mb-md-0">
                        <h6>Is student have a helmet?</h6>
                        <label>{{ $admission->is_have_helmet == 1 ? 'Yes' : 'No' }}</label>
                    </div>
                    <hr class="d-md-none m-0">
                    <div class="col-md-4 border-right mb-2 mb-md-0">
                        <h6>Vehicle Number</h6>
                        <label>{{ $admission->vehicle_number }}</label>
                    </div>
                    <hr class="d-md-none m-0">
                    <div class="col-md-4">
                        <h6>Licence</h6>
                        <a class="img"><img src="{{ asset($admission->licence_doc_url) }}" alt="Licence"
                                class="student_img"></a>
                    </div>
                    <hr class="mb-0 mt-0 border-right mb-md-4 mt-md-4">
                    <div class="col-md-4 border-right mb-2 mb-md-0">
                        <h6>Rcbook Front</h6>
                        <a class="img"><img src="{{ asset($admission->rcbook_front_doc_url) }}" alt="Rcbook Front"
                                class="student_img"></a>
                    </div>
                    <hr class="d-md-none m-0">
                    <div class="col-md-4 border-right mb-2 mb-md-0">
                        <h6>Rcbook Back</h6>
                        <a class="img"><img src="{{ asset($admission->rcbook_back_doc_url) }}" alt="Rcbook Back"
                                class="student_img"></a>
                    </div>
                    <hr class="d-md-none m-0">
                    <div class="col-md-4 mb-2 mb-md-0">
                        <h6>Insurance</h6>
                        <a class="img"><img src="{{ asset($admission->insurance_doc_url) }}" alt="Insurance"
                                class="student_img"></a>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="card mb-4">
        <div class="card-body">
            <h4>Parent Details</h4>
            <div class="d-flex gap-5 align-items-center img-display">
                <div class="row w-100">
                    <div class="col-md-4 border-right mb-2 mb-md-0">
                        <h6>Father Full Name</h6>
                        <label>{{ $admission->father_full_name }}</label>
                    </div>
                    <hr class="d-md-none m-0">
                    <div class="col-md-4 border-right mb-2 mb-md-0">
                        <h6>Father Contact No</h6>
                        <label>{{ $admission->father_phone }}</label>
                    </div>
                    <hr class="d-md-none m-0">
                    <div class="col-md-4 mb-2 mb-md-0">
                        <h6>Father Occupation</h6>
                        <label>{{ $admission->father_occupation }}</label>
                    </div>
                    <hr class="mb-0 mt-0 mb-md-4 mt-md-4">
                    <div class="col-md-4 border-right mb-2 mb-md-0">
                        <h6>Annual Income</h6>
                        <label>{{ $admission->annual_income }}</label>
                    </div>
                    <hr class="d-md-none m-0">
                    <div class="col-md-4 border-right mb-2 mb-md-0">
                        <h6>Mother Full Name</h6>
                        <label>{{ $admission->mother_full_name }}</label>
                    </div>
                    <hr class="d-md-none m-0">
                    <div class="col-md-4 mb-2 mb-md-0">
                        <h6>Mother Contact No</h6>
                        <label>{{ $admission->mother_phone }}</label>
                    </div>
                    <hr class="mb-0 mt-0 mb-md-4 mt-md-4">
                    <div class="col-md-4 border-right mb-2 mb-md-0">
                        <h6>Mother Occupation</h6>
                        <label>{{ $admission->mother_occupation }}</label>
                    </div>
                    <hr class="d-md-none m-0">
                    <div class="col-md-4 border-right mb-2 mb-md-0">
                        <h6>Parent’s Nationality Indian</h6>
                        <label>{{ $admission->is_parent_indian_citizen == 1 ? 'Yes' : 'No' }}</label>
                    </div>
                    <hr class="d-md-none m-0">
                    <div class="col-md-4 mb-2 mb-md-0">
                        <h6>Local guardian in ahmedabad</h6>
                        <label>{{ $admission->is_local_guardian_in_ahmedabad == 1 ? 'Yes' : 'No' }}</label>
                    </div>
                    @if ($admission->is_local_guardian_in_ahmedabad == 1)
                        <hr class="mb-0 mt-0 mb-md-4 mt-md-4">
                        <div class="col-md-4 border-right mb-2 mb-md-0">
                            <h6>Local Guardian Name</h6>
                            <label>{{ $admission->guardian_name ?? '-' }}</label>
                        </div>
                        <hr class="d-md-none m-0">
                        <div class="col-md-4 border-right mb-2 mb-md-0">
                            <h6>Local Guardian Relation</h6>
                            <label>{{ $admission->guardian_relation ?? '-' }}</label>
                        </div>
                        <hr class="d-md-none m-0">
                        <div class="col-md-4">
                            <h6>Local Guardian Contact No</h6>
                            <label>{{ $admission->guardian_phone ?? '-' }}</label>
                        </div>
                    @endif
                </div>
                <div class="d-flex align-items-center student_img_div">
                    <a class="img">
                        <img src="{{ asset($admission->father_photo_url) }}" alt="Father photo" class="student_img">
                    </a>
                    <a class="img">
                        <img src="{{ asset($admission->mother_photo_url) }}" alt="Mother Photo" class="student_img">
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h4>Education Details</h4>
            <div class="row">
                <div class="col-md-4 border-right mb-2 mb-md-0">
                    <h6>Education</h6>
                    <label>{{ $admission->education_type }}</label>
                </div>
                <hr class="d-md-none m-0">
                <div class="col-md-4 border-right mb-2 mb-md-0">
                    <h6>Course</h6>
                    <label>{{ $admission->course_name }}</label>
                </div>
                <hr class="d-md-none m-0">
                <div class="col-md-4">
                    <h6>Institute /College Name</h6>
                    <label>{{ $admission->institute_name }}</label>
                </div>
                <hr class="mb-0 mt-0 mb-md-4 mt-md-4">
                <div class="col-md-4 border-right mb-2 mb-md-0">
                    <h6>Admission Date</h6>
                    <label>{{ date('d/m/Y', strtotime($admission->addmission_date)) }}</label>
                </div>
                <hr class="d-md-none">
                <div class="col-md-4 border-right mb-2 mb-md-0">
                    <h6>Year of Admission</h6>
                    @php
                        $year = $admission->year_of_addmission;
                    @endphp

                    @if (strpos($year, '-') !== false)
                        <label>{{ $year }}</label>
                    @else
                        <label>{{ (int) $year }}-{{ (int) $year + 1 }}</label>
                    @endif
                </div>
                <hr class="d-md-none">
                <div class="col-md-4">
                    <h6>Semester</h6>
                    <label>{{ $admission->semester }}</label>
                </div>
                <hr class="mb-0 mt-0 mb-md-4 mt-md-4">
                <div class="col-md-4 border-right mb-2 mb-md-0">
                    <h6>Institute Start Time</h6>
                    <label>{{ $admission->college_start_time ?? '-' }}</label>
                </div>
                <hr class="d-md-none">
                <div class="col-md-4 border-right mb-2 mb-md-0">
                    <h6>Institute End Time</h6>
                    <label>{{ $admission->college_end_time ?? '-' }}</label>
                </div>
                <hr class="d-md-none">
                <div class="col-md-4">
                    <h6>Arriving Date at hostel</h6>
                    <label>{{ date('d/m/Y', strtotime($admission->arriving_date)) }}</label>
                </div>
                <hr class="mb-0 mt-0 mb-md-4 mt-md-4">
                <div class="col-md-4 border-right mb-2 mb-md-0">
                    <h6>Fees Receipt Date</h6>
                    <label>{{ $admission->college_fees_receipt_date ? date('d/m/Y', strtotime($admission->college_fees_receipt_date)) : '-' }}</label>
                </div>
                <div class="col-md-4 border-right mb-2 mb-md-0">
                    <h6>Backlog</h6>
                    <label>{{ $hasBacklog ? 'Yes' : 'No' }}</label>
                </div>
                <hr class="d-md-none">
                <hr class="mb-0 mt-0 mb-md-4 mt-md-4">
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="nav-align-top mb-4">
                        <ul class="nav nav-pills" role="tablist">
                            <li class="nav-item">
                                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#navs-pills-top-document" aria-controls="navs-pills-top-document"
                                    aria-selected="true">
                                    Documents
                                </button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#navs-pills-top-comment" aria-controls="navs-pills-top-comment"
                                    aria-selected="true">
                                    Comments
                                </button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#navs-pills-top-history" aria-controls="navs-pills-top-history"
                                    aria-selected="true">
                                    History
                                </button>
                            </li>
                        </ul>
                        <hr>
                        <div class="tab-content pt-0">
                            <div class="tab-pane fade show active" id="navs-pills-top-document" role="tabpanel">
                                <div class="table_content_wrapper">
                                    <div class="data_table_wrap">
                                        <table class="table table-bordered" id="studentDocumentTable">
                                            <thead>
                                                <tr>
                                                    <th>SR No.</th>
                                                    <th>Type</th>
                                                    <th>Image</th>
                                                    <th>Percentile</th>
                                                    {{-- <th>Status</th> --}}
                                                    {{-- <th>Description</th> --}}
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-border-bottom-0">
                                                @foreach ($documents as $key => $document)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ $document->doc_type }}</td>
                                                        <td> <a class="img"><img src="{{ asset($document->doc_url) }}"
                                                                    alt="{{ $document->doc_type }}" height="50"
                                                                    width="50"></a></td>
                                                        <td>{{ $document->percentile ?? '-' }}</td>
                                                        {{-- <td>{{ $document->result_status }}</td> --}}
                                                        {{-- <td>{{ $document->description }}</td> --}}
                                                        <td>{{ date('d/m/Y', strtotime($document->created_at)) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show" id="navs-pills-top-comment" role="tabpanel">
                                <div class="table_content_wrapper">
                                    <div class="data_table_wrap">
                                        <table class="table table-bordered" id="commentTable">
                                            <thead>
                                                <tr>
                                                    <th>SR No.</th>
                                                    <th>Admin Comment</th>
                                                    <th>Student Comment</th>
                                                    <th>Commented By</th>
                                                    <th>Comment Type</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-border-bottom-0">
                                                @foreach ($comments as $key => $comment)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ $comment->admin_comment ?? '-' }}</td>
                                                        <td>{{ $comment->student_comment ?? '-' }}</td>
                                                        <td>{{ $comment->user->name }}</td>
                                                        <td>{{ ucfirst(str_replace('_', ' ', $comment->comment_type)) }}
                                                        </td>
                                                        <td>{{ date('d/m/Y H:i', strtotime($comment->created_at)) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show" id="navs-pills-top-history" role="tabpanel">
                                <div class="table_content_wrapper">
                                    <div class="data_table_wrap">
                                        <table class="table table-bordered" id="historyTable">
                                            <thead>
                                                <tr>
                                                    <th>Sr. No</th>
                                                    <th>Module Name</th>
                                                    <th>Actioned by</th>
                                                    <th>Action</th>
                                                    <th style="width: 600px;">Old</th>
                                                    <th>New</th>
                                                    <th>Updated At</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-border-bottom-0">
                                                @php
                                                    if (!function_exists('formatValue')) {
                                                        function formatValue($key, $value)
                                                        {
                                                            if (in_array($key, ['dob', 'membership_start_date'])) {
                                                                return $value ? date('d/m/Y', strtotime($value)) : null;
                                                            }

                                                            if ($key === 'gaam_id') {
                                                                return optional(\App\Models\Gaam::find($value))->name;
                                                            }

                                                            if ($key === 'member_type_id') {
                                                                return optional(\App\Models\MemberType::find($value))
                                                                    ->name;
                                                            }

                                                            return $value;
                                                        }
                                                    }

                                                    if (!function_exists('prepareDisplay')) {
                                                        function prepareDisplay($data, $excludedKeys)
                                                        {
                                                            $result = '';
                                                            foreach ($data as $key => $value) {
                                                                if (in_array($key, $excludedKeys)) {
                                                                    continue;
                                                                }

                                                                $formattedValue = formatValue($key, $value);
                                                                $label = ucwords(
                                                                    str_replace('_', ' ', str_replace(' Id', '', $key)),
                                                                );
                                                                $result .= "$label - $formattedValue, ";
                                                            }
                                                            return rtrim($result, ', ');
                                                        }
                                                    }
                                                @endphp
                                                @foreach ($activities as $key => $item)
                                                    @php
                                                        $index = $key;
                                                        $activityLog = json_decode($item->properties, true);
                                                        $oldValues = $activityLog['old'] ?? [];
                                                        $newValues = $activityLog['attributes'] ?? [];

                                                        $excludedKeys = [
                                                            'created_by',
                                                            'updated_at',
                                                            'created_at',
                                                            'deleted_at',
                                                        ];

                                                        $changesOld = '';
                                                        $changesNew = '';

                                                        foreach ($newValues as $key => $newValue) {
                                                            if (in_array($key, $excludedKeys)) {
                                                                continue;
                                                            }

                                                            $oldValue = $oldValues[$key] ?? null;
                                                            if ($oldValue != $newValue) {
                                                                $changesOld .=
                                                                    ucwords(str_replace('_', ' ', $key)) .
                                                                    ' - ' .
                                                                    formatValue($key, $oldValue) .
                                                                    ', ';
                                                                $changesNew .=
                                                                    ucwords(str_replace('_', ' ', $key)) .
                                                                    ' - ' .
                                                                    formatValue($key, $newValue) .
                                                                    ', ';
                                                            }
                                                        }

                                                        $changesOld = rtrim($changesOld, ', ');
                                                        $changesNew = rtrim($changesNew, ', ');

                                                        $updatedAt =
                                                            $newValues['updated_at'] ??
                                                            ($oldValues['updated_at'] ?? null);
                                                        $updatedAt = $updatedAt
                                                            ? date('d/m/Y H:i:s', strtotime($updatedAt))
                                                            : '-';
                                                    @endphp


                                                    <tr>
                                                        <td>{{ (int) $index + 1 }}</td>
                                                        <td>{{ $item->log_name }}</td>
                                                        <td>{{ $item->user->name ?? '' }}</td>
                                                        <td>{{ ucwords($item->event) }}</td>
                                                        <td>{!! $changesOld ?: '-' !!}</td>
                                                        <td>{!! $changesNew ?: '-' !!}</td>
                                                        <td>{{ $updatedAt }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- View img -->
    <div class="modal fade viewDocumentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="row">
                        <div class="col-12">
                            <div class="image-container">
                                <img src="" alt="" class="view_img">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Send Admin Comment -->
    <div class="modal fade createCommentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Send Comments</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="createCommentForm" action="{{ route('admission.sendComment') }}" method="post"
                    class="fees_form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="student_id" class="student_id">
                    <input type="hidden" name="admission_id" class="admission_id">
                    <div class="modal-body pb-0">
                        <div class="row">
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <label for="name">Student Name</label>
                                <input type="text" class="form-control student_name" disabled />
                            </div>
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <label for="name">Student Email</label>
                                <input type="text" class="form-control student_email" disabled />
                            </div>
                            <div class="col-sm-12 col-lg-12 mb-4">
                                <label for="admin_comment">Admin Comment</label>
                                <textarea class="form-control admin_comment complaint_desc_field" name="admin_comment"
                                    placeholder="Enter Admin Comment" data-parsley-required-message="The admin comment field is required.">{{ old('admin_comment') }}</textarea>
                            </div>
                            <div class="col-sm-12 col-lg-12 mb-4">
                                <div class="form-check checkbox_field_box">
                                    <input class="form-check-input" type="checkbox" id="send_email" name="send_email">
                                    <label class="checkbox_field_label" for="send_email">
                                        Send email to student?
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn secondary_btn" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn primary_btn submitCommentBtn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Send Admin Remark -->
    <div class="modal fade createStatusRemarkModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Send Remarks</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="createStatusRemarkForm" action="{{ route('admission.sendStatusRemark') }}" method="post"
                    class="fees_form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="student_id" class="student_id">
                    <input type="hidden" name="admission_id" class="admission_id">
                    <div class="modal-body pb-0">
                        <div class="row">
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <label for="name">Student Name</label>
                                <input type="text" class="form-control student_name" disabled />
                            </div>
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <label for="name">Student Email</label>
                                <input type="text" class="form-control student_email" disabled />
                            </div>
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <label for="admission_status">Admission Status</label>
                                <select name="admission_status" id=""
                                    class="select2 form-select admission_status"
                                    data-placeholder="Select Admission Status"
                                    data-parsley-errors-container="#admission_status_errors" required>
                                    <option value="">Select Admission Status</option>
                                    <option @if (old('admission_status') == '0') selected @endif value="0" selected>
                                        Pending
                                    </option>
                                    <option @if (old('admission_status') == '1') selected @endif value="1">
                                        Confirm
                                    </option>
                                    <option @if (old('admission_status') == '2') selected @endif value="2">Reject
                                    </option>
                                    <option @if (old('admission_status') == '3') selected @endif value="3">
                                        Cancelled
                                    </option>
                                    <option @if (old('admission_status') == '4') selected @endif value="4">Release
                                    </option>
                                </select>
                                <div id="admission_status_errors"></div>
                            </div>
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <label for="admin_comment">Admin Comment</label>
                                <textarea class="form-control admin_comment complaint_desc_field" name="admin_comment" id="" cols="30"
                                    rows="10" placeholder="Enter Admin Comment"
                                    data-parsley-required-message="The admin comment field is required.">{{ old('admin_comment') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn secondary_btn" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn primary_btn submitStatusRemarkBtn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Change Backlog Status Modal -->
    <div class="modal fade createBacklogStatusModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Update Backlog Status</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="createBacklogStatusForm" action="{{ route('admission.sendBacklogStatus') }}"
                    method="POST">
                    @csrf
                    <input type="hidden" name="student_id" class="student_id">
                    <input type="hidden" name="admission_id" class="admission_id">
                    <div class="modal-body pb-0">
                        <div class="row">
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <label for="name">Student Name</label>
                                <input type="text" class="form-control student_name" disabled />
                            </div>
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <label for="name">Student Email</label>
                                <input type="text" class="form-control student_email" disabled />
                            </div>
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <label for="has_backlog">Backlog Status</label>
                                <select name="has_backlog" class="form-select has_backlog select2" required>
                                    <option value="">Select Backlog Status</option>
                                    <option value="true">Yes</option>
                                    <option value="false">No</option>
                                </select>
                            </div>
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <label for="admin_comment">Admin Comment</label>
                                <textarea class="form-control complaint_desc_field" name="admin_comment" cols="30"
                                    rows="10" placeholder="Enter Comment">{{ old('admin_comment') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn secondary_btn" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn primary_btn submitBacklogStatusBtn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/admission/admission.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#studentDocumentTable').DataTable();
            $('#commentTable').DataTable();
            $('#historyTable').DataTable();

            // Delegate click for dynamically rendered .img elements
            $(document).on('click', '.img', function() {
                $('.viewDocumentModal').modal('show');
                var src = $(this).find('img').prop('src');
                var alt = $(this).find('img').prop('alt');
                $('.view_img').attr('src', src);
                $('.view_img').attr('alt', alt);
                $('.modal-title').text(alt);

                console.log('src', src);
                console.log('alt', alt);

                // Reset magnified state
                $('.view_img').removeClass('magnified');
            });

            // Toggle zoom on click
            $(document).on('click', '.view_img', function() {
                $(this).toggleClass('magnified');
            });

            // Zoom follows mouse only if magnified
            $(document).on('mousemove', '.image-container', function(e) {
                let image = $(this).find('.view_img');
                if (!image.hasClass('magnified')) return;

                let containerWidth = $(this).width();
                let containerHeight = $(this).height();
                let offsetX = e.offsetX;
                let offsetY = e.offsetY;
                let xPercent = (offsetX / containerWidth) * 100;
                let yPercent = (offsetY / containerHeight) * 100;

                image.css('transform-origin', `${xPercent}% ${yPercent}%`);
            });

            // Optional: remove zoom when modal closes
            $('.viewDocumentModal').on('hidden.bs.modal', function() {
                $('.view_img').removeClass('magnified');
            });
        });
    </script>
@endsection
