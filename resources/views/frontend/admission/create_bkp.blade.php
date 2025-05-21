@extends('frontend.layouts.app')
@section('title', 'Create Admission')
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/vendor/bs-stepper/bs-stepper.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />

    <style>
        .red-text {
            color: red;
        }

        #upload-demo {
            width: 100%;
            margin-bottom: 20px;
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }

        .bs-stepper.vertical {
            display: block;
        }

        /* body>span.select2-container.select2-container--default.select2-container--open {
                                    width: auto !important;
                                } */

        span.select2.select2-container.select2-container--default.select2-container--above.select2-container--open {
            width: 100%;
        }

        .document-fields {
            display: -webkit-box;
        }

        #upload-demo img {
            max-width: 100%;
            max-height: 100%;
            display: block;
            margin: auto;
        }

        .cropper-canvas,
        .cropper-crop-box,
        .cropper-drag-box,
        .cropper-modal,
        .cropper-wrap-box {
            position: absolute;
            top: 0;
            left: 0;
        }

        .bs-stepper:not(.wizard-icons) .bs-stepper-header .line {
            border-style: none;
        }

        .bs-stepper .step-trigger {
            display: block;
        }

        button.step-trigger {
            width: 100%
        }

        .bs-stepper .step-trigger:disabled {
            opacity: unset !important;
        }                                                                                                                                                                                                  gap: 0px;
                                                                                                                                                                                                                                } */
        /* ///from validation */

        .fv-plugins-bootstrap5 .invalid-feedback,
        .fv-plugins-bootstrap5 .invalid-tooltip {
            display: block;
        }

        .fv-plugins-bootstrap5 .invalid-feedback,
        .fv-plugins-bootstrap5 .invalid-tooltip {
            display: block;
        }

        .was-validated .input-group:has(input:invalid) .invalid-feedback,
        .was-validated .input-group:has(input:invalid) .invalid-tooltip {
            display: block;
        }

        .was-validated :invalid~.invalid-feedback,
        .was-validated :invalid~.invalid-tooltip,
        .is-invalid~.invalid-feedback,
        .is-invalid~.invalid-tooltip {
            display: block;
        }

        .backlog-download-card {
            transition: box-shadow 0.2s;
            box-shadow: 0 1px 4px rgba(27, 182, 193, 0.05);
        }

        .backlog-download-card:hover {
            box-shadow: 0 4px 16px rgba(27, 182, 193, 0.12);
            border-color: #1bb6c1;
        }

        @media (max-width: 991.98px) {
            .bs-stepper .bs-stepper-header {
                flex-direction: row;
                align-items: flex-start;
            }

            .bs-stepper .bs-stepper-header .step .step-trigger {
                padding: 0;
            }
        }

        .bs-stepper .bs-stepper-header {
            padding: 0;
        }

        .bs-stepper .bs-stepper-header .step .step-trigger .bs-stepper-label .bs-stepper-title {
            white-space: break-spaces;
            text-align: center;
        }

        @media (max-width: 520px) {
            .bs-stepper-header {
                text-align: left;
            }

            #cropImagePop .modal-dialog {
                max-width: 75vw;
                margin: 0.5rem auto;
            }

            .cropper-wrapper {
                height: 80vw !important;
            }

            #cropImagePop .modal-body {
                padding: 0;
                overflow: visible !important;
            }

            #upload-demo {
                width: 100% !important;
                /* fill modal */
                max-height: 80vh !important;
                /* don’t exceed 80% of viewport height */
                overflow: visible !important;
                /* allow cropper to show */
                position: relative;
            }

            #upload-demo .cropper-container {
                width: 100% !important;
                height: auto !important;
                max-width: 100% !important;
                max-height: 80vh !important;
                overflow: visible !important;
                margin: 0 auto;
                margin-top: 22px;
            }

            #upload-demo .cropper-canvas img,
            #upload-demo .cropper-view-box {
                max-width: none !important;
            }
        }
    </style>
@endsection
@section('content')
    <section class="Personal_details_form">
        <div class="container">
            <a href="{{ route('student.dashboard') }}" class="go-back">
                <svg width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M9.76172 1.63672L2.39844 9L9.76172 16.3633L8.86328 17.2617L1.05078 9.44922L0.621094 9L1.05078 8.55078L8.86328 0.738281L9.76172 1.63672Z"
                        fill="#1D1D1B" />
                </svg>
                Go Back
            </a>

            <div class="text-center pt_60 ">
                <h2 class="admission_form_title">Admission Form</h2>
            </div>
            <div id="admission_form" class="bs-stepper mt-2">
                <form action="{{ route('student.admission.store') }}" method="post" enctype="multipart/form-data"
                    id="admission_form">
                    @csrf
                    <input type="hidden" value="{{ $studentData->id }}" id="student_id">
                    <input type="hidden" name="hdnStudentPhotourl" id="hdnStudentPhotourl"
                        value="{{ $oldAdmissionDetails->student_photo_url ?? '' }}">
                    <input type="hidden" name="hdnFatherPhotourl" id="hdnFatherPhotourl"
                        value="{{ $oldAdmissionDetails->father_photo_url ?? '' }}">
                    <input type="hidden" name="hdnMotherPhotourl" id="hdnMotherPhotourl"
                        value="{{ $oldAdmissionDetails->mother_photo_url ?? '' }}">
                    <input type="hidden" name="hdnLicencePhotourl" id="hdnLicencePhotourl"
                        value="{{ $oldAdmissionDetails->licence_doc_url ?? '' }}">
                    <input type="hidden" name="hdnInsurancePhotourl" id="hdnInsurancePhotourl"
                        value="{{ $oldAdmissionDetails->insurance_doc_url ?? '' }}">
                    <input type="hidden" name="hdnRcbookFrontDocPhotourl" id="hdnRcbookFrontDocPhotourl"
                        value="{{ $oldAdmissionDetails->rcbook_front_doc_url ?? '' }}">
                    <input type="hidden" name="hdnRcbookBackDocPhotourl" id="hdnRcbookBackDocPhotourl"
                        value="{{ $oldAdmissionDetails->rcbook_back_doc_url ?? '' }}">
                    <div class="bs-stepper-header stepper-wrapper">
                        <div class="step stepper-item" data-target="#personal-details">
                            <button type="button" class="step-trigger flex-lg-wrap gap-lg-2 px-lg-0">
                                <span class="step-counter">1</span>
                                <span class="bs-stepper-label ms-lg-0">
                                    <span class="d-flex flex-column gap-1 text-lg-center">
                                        <span class="bs-stepper-title step-name">Personal Details</span>
                                    </span>
                                </span>
                            </button>
                        </div>
                        {{-- <div class="stepper-class"></div> --}}
                        <div class="step stepper-item" data-target="#family-details">
                            <button type="button" class="step-trigger flex-lg-wrap gap-lg-2 px-lg-0">
                                <span class="step-counter">2</span>
                                <span class="bs-stepper-label ms-lg-0">
                                    <span class="d-flex flex-column gap-1 text-lg-center">
                                        <span class="bs-stepper-title step-name">Family Details</span>
                                    </span>
                                </span>
                            </button>
                        </div>
                        {{-- <div class="stepper-class"></div> --}}
                        <div class="step stepper-item" data-target="#education-details">
                            <button type="button" class="step-trigger flex-lg-wrap gap-lg-2 px-lg-0">
                                <span class="step-counter">3</span>
                                <span class="bs-stepper-label ms-lg-0">
                                    <span class="d-flex flex-column gap-1 text-lg-center">
                                        <span class="bs-stepper-title step-name">Education Details</span>
                                    </span>
                                </span>
                            </button>
                        </div>
                        {{-- <div class="stepper-class"></div> --}}
                        <div class="step stepper-item" data-target="#declaration">
                            <button type="button" class="step-trigger flex-lg-wrap gap-lg-2 px-lg-0">
                                <span class="step-counter">4</span>
                                <span class="bs-stepper-label ms-lg-0">
                                    <span class="d-flex flex-column gap-1 text-lg-center">
                                        <span class="bs-stepper-title step-name">Declaration</span>
                                    </span>
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="bs-stepper-content p-0">
                        <!-- personal Details -->
                        <div id="personal-details" class="content form-section">
                            <div class="mb_30">
                                <div class="d-flex gap-3">
                                    <div class="form-check ps-0 m-0">
                                        <input class="form-check-input" type="radio" name="is_admission_new"
                                            id="new_is_" @if (!$studentAdmissionCheck) checked @endif value="true"
                                            disabled>
                                        <label class="form-check-label" for="new_student">New Student</label>
                                    </div>
                                    <div class="form-check m-0">
                                        <input class="form-check-input" type="radio" name="is_admission_new"
                                            id="old_student" @if ($studentAdmissionCheck) checked @endif
                                            value="false" disabled>
                                        <label class="form-check-label" for="old_student">Old Student</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="first_name" class="form-label">First Name</label>
                                    <input type="text" class="form-control" name="first_name"
                                        value="{{ old('first_name', $studentData->first_name) }}" id="first_name"
                                        placeholder="Enter first name" readonly />
                                </div>
                                <div class="col">
                                    <label for="middle_name" class="form-label">Middle Name</label>
                                    <input type="text" class="form-control" name="middle_name"
                                        value="{{ old('middle_name', $studentData->middle_name) }}" id="middle_name"
                                        placeholder="Enter middle name (father's name)" readonly />
                                </div>
                                <div class="col">
                                    <label for="last_name" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" name="last_name"
                                        value="{{ old('last_name', $studentData->last_name) }}" id="last_name"
                                        placeholder="Enter last name (surname)" readonly />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select name="gender" id="gender" class="select2 form-select"
                                        data-placeholder="Select gender" readonly>
                                        <option value="">Select Gender</option>
                                        <option @if ($studentData->gender == 'boy') selected @endif value="boy">
                                            Boy
                                        </option>
                                        <option @if ($studentData->gender == 'girl') selected @endif value="girl">
                                            Girl
                                        </option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="phone_number" class="form-label">Phone Number</label>
                                    <input type="number" class="form-control" name="phone"
                                        value="{{ old('phone', $studentData->phone) }}" id="phone"
                                        placeholder="Enter mobile number" readonly />
                                </div>
                                <div class="col">
                                    <label for="dob" class="form-label">Date-of-Birth</label>
                                    <input type="text" class="form-control" name="dob"
                                        value="{{ $studentData->dob ? date('d/m/Y', strtotime($studentData->dob)) : '' }}"
                                        placeholder="DD/MM/YYYY" id="dob" autocomplete="off" />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" name="email"
                                        value="{{ old('email', $studentData->email) }}" id="email"
                                        placeholder="Enter email" readonly />
                                </div>
                                <div class="col">
                                    <label for="address" class="form-label">Permanent Address</label>
                                    <input type="text" class="form-control" name="residence_address"
                                        value="{{ old('residence_address', $studentData->address) }}"
                                        id="residence_address" placeholder="Enter address (as per ID proof)" />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="village" class="form-label">Village</label>
                                    <select name="village_id" id="village_id" class="select2 form-select"
                                        data-placeholder="Select village" readonly>
                                        <option value="">Select Village</option>
                                        @foreach ($villages as $village)
                                            <option value="{{ $village->id }}"
                                                @if ($village->id == $studentData->village_id) selected @endif>
                                                {{ $village->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="country" class="form-label">Country</label>
                                    <select name="country" id="country_id" class="select2 form-select"
                                        data-placeholder="Select country" readonly>
                                        <option value="">Select Country</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->id }}"
                                                @if ($country->id == $studentData->country_id) selected @endif>
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col d-flex align-items-center radio-field-wrap">
                                    <label class="form-label mb-0">Are you a citizen of India?</label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="is_indian_citizen"
                                                id="citizen_yes" value="true"
                                                @if ($oldAdmissionDetails && $oldAdmissionDetails->is_indian_citizen == true) checked @endif checked>
                                            <label class="form-check-label" for="citizen_yes">YES</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="is_indian_citizen"
                                                id="citizen_no" value="false"
                                                @if ($oldAdmissionDetails && $oldAdmissionDetails->is_indian_citizen == false) checked @endif>
                                            <label class="form-check-label" for="citizen_no">NO</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="error-message">
                                        <label for="passport_photo" class="form-label">Upload your Passport Size
                                            Photo</label>
                                        <!-- <input type="file" class="form-control" id="passport_photo"> -->
                                        <label for="passport_photo_upload" class="custom-file-upload">
                                            <span id="passport-photo-label">Upload Passport Size Photo <i
                                                    class="las la-plus-circle"></i></span>
                                            <input type="file" name="passport_photo" id="passport_photo_upload"
                                                onchange="updateFileNameSwap(this, 'passport-photo-label')"
                                                class="static-crop" />
                                        </label>
                                    </div>
                                    <input name="passport_photoimage" id="passport_photoimage" value=""
                                        type="hidden" class="form-control" />
                                    <img id="passport_photoImage" class="rounded img-fluid" src=""
                                        style="display: none;" />
                                    @if ($oldAdmissionDetails && $oldAdmissionDetails->student_photo_url != '')
                                        <div class="doc-download-box">
                                            <a
                                                href="{{ route('student.images.download', ['id' => $oldAdmissionDetails->id, 'fieldName' => 'student_photo_url']) }}"><span>Passport
                                                    Size Photo</span>
                                                <img src="{{ asset('assets/images/download-icon.svg') }}">
                                            </a>
                                            <img src="{{ asset($oldAdmissionDetails->student_photo_url) }}"
                                                alt="" id="studentImg" class="uploaded-img">
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3 adhaar_number_field">
                                <div class="col">
                                    <label for="adhaar_number" class="form-label">Aadhar Number</label>
                                    <input type="number" class="form-control" id="adhaar_number" name="adhaar_number"
                                        value="{{ $oldAdmissionDetails->adhaar_number ?? '' }}"
                                        placeholder="Aadhar Number">
                                </div>
                                <div class="col">
                                    <label class="form-label">Upload your Aadhar Card</label>
                                    <div class="upload-group">
                                        <div class="error-message">
                                            <label for="aadhar_front_upload" class="custom-file-upload">
                                                <span id="aadhar-front-label">Aadhar Card Front <i
                                                        class="las la-plus-circle"></i></span>
                                                <input type="file" name="aadhar_front" id="aadhar_front_upload"
                                                    onchange="updateFileNameSwap(this, 'aadhar-front-label')"
                                                    class="static-crop" />
                                                <input name="aadhar_frontimage" id="aadhar_frontimage" value=""
                                                    type="hidden" class="form-control" />
                                            </label>
                                            <img id="aadhar_frontImage" class="rounded img-fluid" src=""
                                                style="display: none;" />
                                            @if ($oldAdmissionDetails && $oldAdmissionDocuments)
                                                @foreach ($oldAdmissionDocuments as $document)
                                                    @if ($document->doc_type == 'Aadhar Card Front')
                                                        <div class="doc-download-box">
                                                            <a
                                                                href="{{ route('student.document.download', $document->id) }}"><span>Aadhar
                                                                    Card Front</span>
                                                                <img src="{{ asset('assets/images/download-icon.svg') }}">
                                                            </a>
                                                            <img src="{{ asset($document->doc_url) }}" alt=""
                                                                id="studentAadharFrontImg" class="uploaded-img">
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="error-message">
                                            <label for="aadhar_back_upload" class="custom-file-upload">
                                                <span id="aadhar-back-label">Aadhar Card Back <i
                                                        class="las la-plus-circle"></i></span>
                                                <input type="file" name="aadhar_back" id="aadhar_back_upload"
                                                    onchange="updateFileNameSwap(this, 'aadhar-back-label')"
                                                    class="static-crop" />
                                                <input name="aadhar_backimage" id="aadhar_backimage" value=""
                                                    type="hidden" class="form-control" />
                                            </label>
                                            <img id="aadhar_backImage" class="rounded img-fluid" src=""
                                                style="display: none;" />
                                            @if ($oldAdmissionDetails && $oldAdmissionDocuments)
                                                @foreach ($oldAdmissionDocuments as $document)
                                                    @if ($document->doc_type == 'Aadhar Card Back')
                                                        <div class="doc-download-box">
                                                            <a
                                                                href="{{ route('student.document.download', $document->id) }}"><span>Aadhar
                                                                    Card Back</span>
                                                                <img src="{{ asset('assets/images/download-icon.svg') }}">
                                                            </a>
                                                            <img src="{{ asset($document->doc_url) }}" alt=""
                                                                id="studentAadharBackImg" class="uploaded-img">
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3 passport_number_student_field d-none">
                                <div class="col">
                                    <label for="passport_number" class="form-label">Passport Number</label>
                                    <input type="text" class="form-control" id="passport_number"
                                        name="passport_number" value="{{ $oldAdmissionDetails->passport_number ?? '' }}"
                                        placeholder="Passport Number">
                                </div>
                                <div class="col">
                                    <label class="form-label">Upload your Passport</label>
                                    <div class="upload-group">
                                        <div class="error-message">
                                            <label for="passport_front_upload" class="custom-file-upload">
                                                <span id="passport-front-label">Passport Front <i
                                                        class="las la-plus-circle"></i></span>
                                                <input type="file" name="passport_front" id="passport_front_upload"
                                                    onchange="updateFileNameSwap(this, 'passport-front-label')"
                                                    class="static-crop" />
                                                <input name="passport_frontimage" id="passport_frontimage" value=""
                                                    type="hidden" class="form-control" />
                                            </label>
                                            <img id="passport_frontImage" class="rounded img-fluid" src=""
                                                style="display: none;" />
                                            @if ($oldAdmissionDetails && $oldAdmissionDocuments)
                                                @foreach ($oldAdmissionDocuments as $document)
                                                    @if ($document->doc_type == 'Passport Front')
                                                        <div class="doc-download-box">
                                                            <a
                                                                href="{{ route('student.document.download', $document->id) }}"><span>Passport
                                                                    Front</span>
                                                                <img src="{{ asset('assets/images/download-icon.svg') }}">
                                                            </a>
                                                            <img src="{{ asset($document->doc_url) }}" alt=""
                                                                id="studentPassportFrontImg" class="uploaded-img">
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="error-message">
                                            <label for="passport_back_upload" class="custom-file-upload">
                                                <span id="passport-back-label">Passport Back <i
                                                        class="las la-plus-circle"></i></span>
                                                <input type="file" name="passport_back" id="passport_back_upload"
                                                    onchange="updateFileNameSwap(this, 'passport-back-label')"
                                                    class="static-crop" />
                                                <input name="passport_backimage" id="passport_backimage" value=""
                                                    type="hidden" class="form-control" />
                                            </label>
                                            <img id="passport_backImage" class="rounded img-fluid" src=""
                                                style="display: none;" />
                                            @if ($oldAdmissionDetails && $oldAdmissionDocuments)
                                                @foreach ($oldAdmissionDocuments as $document)
                                                    @if ($document->doc_type == 'Passport Back')
                                                        <div class="doc-download-box">
                                                            <a
                                                                href="{{ route('student.document.download', $document->id) }}"><span>Passport
                                                                    Back</span>
                                                                <img src="{{ asset('assets/images/download-icon.svg') }}">
                                                            </a>
                                                            <img src="{{ asset($document->doc_url) }}" alt=""
                                                                id="studentPassportBackImg" class="uploaded-img">
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col d-flex align-items-center radio-field-wrap">
                                    <label class="form-label mb-0">Do you have any physical or mental illness?</label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="is_any_illness"
                                                id="illness_yes" value="true"
                                                @if ($oldAdmissionDetails && $oldAdmissionDetails->is_any_illness == true) checked @endif>
                                            <label class="form-check-label" for="illness_yes">YES</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="is_any_illness"
                                                id="illness_no" value="false"
                                                @if ($oldAdmissionDetails && $oldAdmissionDetails->is_any_illness == false) checked @endif checked>
                                            <label class="form-check-label" for="illness_no">NO</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col student_illness_field d-none">
                                    <label for="illness_desc" class="form-label">Describe your illness in
                                        brief</label>
                                    <textarea class="form-control" id="illness_desc" name="illness_description"
                                        placeholder="Describe your illness in brief">{{ $oldAdmissionDetails->illness_description ?? '' }}</textarea>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col d-flex align-items-center radio-field-wrap">
                                    <label class="form-label mb-0">Will you be using a vehicle in Ahmedabad?</label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="is_used_vehicle"
                                                id="vehicle_yes" value="true"
                                                @if ($oldAdmissionDetails && $oldAdmissionDetails->is_used_vehicle == true) checked @endif checked>
                                            <label class="form-check-label" for="vehicle_yes">YES</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="is_used_vehicle"
                                                id="vehicle_no" value="false"
                                                @if ($oldAdmissionDetails && $oldAdmissionDetails->is_used_vehicle == false) checked @endif>
                                            <label class="form-check-label" for="vehicle_no">NO</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col vehicle_details_field">
                                    <label for="vehicle_number" class="form-label">Vehicle Number</label>
                                    <input type="text" class="form-control" id="vehicle_number" name="vehicle_number"
                                        value="{{ $oldAdmissionDetails->vehicle_number ?? '' }}"
                                        placeholder="Vehicle Number">
                                </div>
                            </div>
                            <div class="row mb-3 vehicle_details_field">
                                <div class="col d-flex align-items-center radio-field-wrap">
                                    <label class="form-label mb-0">Do you have a helmet?</label>
                                    <div class="d-flex gap-3 form-check-error">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="is_have_helmet"
                                                id="helmet_yes" value="true" checked>
                                            <label class="form-check-label" for="helmet_yes">YES</label>
                                        </div>
                                        <div class="form-check position-relative">
                                            <input class="form-check-input" type="radio" name="is_have_helmet"
                                                id="helmet_no" value="false">
                                            <label class="form-check-label" for="helmet_no">NO</label>
                                            <span class="helmet-error text-danger ms-2" style="display: none;"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <label class="form-label">Upload necessary documents</label>
                                    <div class="upload-group">
                                        <div class="error-message">
                                            <label for="license_upload" class="custom-file-upload">
                                                <span id="license-label">License <i class="las la-plus-circle"></i></span>
                                                <input type="file" name="licence_doc_url" id="license_upload"
                                                    onchange="updateFileNameSwap(this, 'license-label')"
                                                    class="static-crop" />
                                                <input name="licenseimage" id="licenseimage" value=""
                                                    type="hidden" class="form-control" />
                                            </label>
                                            <img id="licenseImage" class="rounded img-fluid" src=""
                                                style="display: none;" />
                                            @if ($oldAdmissionDetails && $oldAdmissionDetails->licence_doc_url != '')
                                                <div class="doc-download-box">
                                                    <a
                                                        href="{{ route('student.images.download', ['id' => $oldAdmissionDetails->id, 'fieldName' => 'licence_doc_url']) }}"><span>Licence</span>
                                                        <img src="{{ asset('assets/images/download-icon.svg') }}">
                                                    </a>
                                                    <img src="{{ asset($oldAdmissionDetails->licence_doc_url) }}"
                                                        alt="" id="licenceImg" class="uploaded-img">
                                                </div>
                                            @endif
                                        </div>
                                        <div class="error-message">
                                            <label for="insurance_upload" class="custom-file-upload">
                                                <span id="insurance-label">Insurance <i
                                                        class="las la-plus-circle"></i></span>
                                                <input type="file" name="insurance_doc_url" id="insurance_upload"
                                                    onchange="updateFileNameSwap(this, 'insurance-label')"
                                                    class="static-crop" />
                                                <input name="insuranceimage" id="insuranceimage" value=""
                                                    type="hidden" class="form-control" />
                                            </label>
                                            <img id="insuranceImage" class="rounded img-fluid" src=""
                                                style="display: none;" />
                                            @if ($oldAdmissionDetails && $oldAdmissionDetails->insurance_doc_url != '')
                                                <div class="doc-download-box">
                                                    <a
                                                        href="{{ route('student.images.download', ['id' => $oldAdmissionDetails->id, 'fieldName' => 'insurance_doc_url']) }}"><span>Insurance</span>
                                                        <img src="{{ asset('assets/images/download-icon.svg') }}">
                                                    </a>
                                                    <img src="{{ asset($oldAdmissionDetails->insurance_doc_url) }}"
                                                        alt="" id="insuranceImg" class="uploaded-img">
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3 vehicle_details_field">
                                <div class="col">
                                    <label class="form-label">Upload RC Book</label>
                                    <div class="upload-group">
                                        <div class="error-message">
                                            <label for="rc_front_upload" class="custom-file-upload">
                                                <span id="rc-front-label">RC Book Front <i class="las la-plus-circle"></i>
                                                </span>
                                                <input type="file" name="rcbook_front_doc_url" id="rc_front_upload"
                                                    onchange="updateFileNameSwap(this, 'rc-front-label')"
                                                    class="static-crop" />
                                                <input name="rc_frontimage" id="rc_frontimage" value=""
                                                    type="hidden" class="form-control" />
                                            </label>
                                            <img id="rc_frontImage" class="rounded img-fluid" src=""
                                                style="display: none;" />
                                            @if ($oldAdmissionDetails && $oldAdmissionDetails->rcbook_front_doc_url != '')
                                                <div class="doc-download-box">
                                                    <a
                                                        href="{{ route('student.images.download', ['id' => $oldAdmissionDetails->id, 'fieldName' => 'rcbook_front_doc_url']) }}"><span>RC
                                                            Book Front</span>
                                                        <img src="{{ asset('assets/images/download-icon.svg') }}">
                                                    </a>
                                                    <img src="{{ asset($oldAdmissionDetails->rcbook_front_doc_url) }}"
                                                        alt="" id="rcBookFrontImg" class="uploaded-img">
                                                </div>
                                            @endif
                                        </div>

                                        <div class="error-message">
                                            <label for="rc_back_upload" class="custom-file-upload">
                                                <span id="rc-back-label">RC Book Back <i class="las la-plus-circle"></i>
                                                </span>
                                                <input type="file" name="rcbook_back_doc_url" id="rc_back_upload"
                                                    onchange="updateFileNameSwap(this, 'rc-back-label')"
                                                    class="static-crop" />
                                                <input name="rc_backimage" id="rc_backimage" value=""
                                                    type="hidden" class="form-control" />
                                            </label>
                                            <img id="rc_backImage" class="rounded img-fluid" src=""
                                                style="display: none;" />
                                            @if ($oldAdmissionDetails && $oldAdmissionDetails->rcbook_back_doc_url != '')
                                                <div class="doc-download-box">
                                                    <a
                                                        href="{{ route('student.images.download', ['id' => $oldAdmissionDetails->id, 'fieldName' => 'rcbook_back_doc_url']) }}"><span>RC
                                                            Book Back</span>
                                                        <img src="{{ asset('assets/images/download-icon.svg') }}">
                                                    </a>
                                                    <img src="{{ asset($oldAdmissionDetails->rcbook_back_doc_url) }}"
                                                        alt="" id="rcBookBackImg" class="uploaded-img">
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex step-btn-wrapper justify-content-between">
                                <button type="button" class="btn btn-prev order-sm-1 order-2" disabled>Previous</button>
                                <button type="button" class="btn btn-reset order-sm-2 order-1"><i class="las la-redo-alt"></i> Reset
                                    Form</button>
                                <button type="button" class="btn btn-next order-sm-3 order-3">Next</button>
                            </div>
                        </div>
                        <!-- family-details -->
                        <div id="family-details" class="content form-section">
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="father_name" class="form-label">Father’s Name</label>
                                    <input type="text" class="form-control" name="father_full_name"
                                        value="{{ $oldAdmissionDetails->father_full_name ?? '' }}" id="father_full_name"
                                        placeholder="Enter father full name" />
                                </div>
                                <div class="col">
                                    <label for="father_contact_number" class="form-label">Father’s Contact Number</label>
                                    <input type="number" class="form-control" name="father_phone"
                                        value="{{ $oldAdmissionDetails->father_phone ?? '' }}" id="father_phone"
                                        placeholder="Enter father contact number" />
                                </div>
                                <div class="col">
                                    <label for="father_occupation" class="form-label">Father’s Occupation</label>
                                    <input type="text" class="form-control" name="father_occupation"
                                        value="{{ $oldAdmissionDetails->father_occupation ?? '' }}"
                                        id="father_occupation" placeholder="Enter father occupation" />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="mother_name" class="form-label">Mother’s Name</label>
                                    <input type="text" class="form-control" name="mother_full_name"
                                        value="{{ $oldAdmissionDetails->mother_full_name ?? '' }}" id="mother_full_name"
                                        placeholder="Enter mother full name" />
                                </div>
                                <div class="col">
                                    <label for="mother_contact_number" class="form-label">Mother’s Contact Number</label>
                                    <input type="number" class="form-control" name="mother_phone"
                                        value="{{ $oldAdmissionDetails->mother_phone ?? '' }}" id="mother_phone"
                                        placeholder="Enter mother contact number" />
                                </div>
                                <div class="col">
                                    <label for="mother_occupation" class="form-label">Mother’s Occupation</label>
                                    <input type="text" class="form-control" name="mother_occupation"
                                        value="{{ $oldAdmissionDetails->mother_occupation ?? '' }}"
                                        id="mother_occupation" placeholder="Enter mother occupation" />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-4">
                                    <label for="aadhar_number" class="form-label">Annual Income</label>
                                    <input type="number" class="form-control" name="annual_income" min="1"
                                        value="{{ $oldAdmissionDetails->annual_income ?? '' }}" id="annual_income"
                                        placeholder="Enter annual income" />
                                </div>
                                <div class="col-8">
                                    <label class="form-label">Upload Parent’s Passport Size Photo</label>
                                    <div class="upload-group">
                                        <div class="error-message">
                                            <label for="father_photo_upload" class="custom-file-upload">
                                                <span id="father-photo-label">Father’s Passport Size Photo <i
                                                        class="las la-plus-circle"></i> </span>
                                                <input type="file" name="father_photo" id="father_photo_upload"
                                                    onchange="updateFileNameSwap(this, 'father-photo-label')"
                                                    class="static-crop" />
                                            </label>
                                            <input name="father_photoimage" id="father_photoimage" value=""
                                                type="hidden" class="form-control" />
                                            <img id="father_photoImage" class="rounded img-fluid" src=""
                                                style="display: none;" />
                                            @if ($oldAdmissionDetails && $oldAdmissionDetails->father_photo_url != '')
                                                <div class="doc-download-box">
                                                    <a
                                                        href="{{ route('student.images.download', ['id' => $oldAdmissionDetails->id, 'fieldName' => 'father_photo_url']) }}"><span>Father’s
                                                            Passport Size Photo</span>
                                                        <img src="{{ asset('assets/images/download-icon.svg') }}">
                                                    </a>
                                                    <img src="{{ asset($oldAdmissionDetails->father_photo_url) }}"
                                                        alt="" id="fatherImg" class="uploaded-img">
                                                </div>
                                            @endif
                                        </div>
                                        <div class="error-message">
                                            <label for="mother_photo_upload" class="custom-file-upload">
                                                <span id="mother-photo-label">Mother’s Passport Size Photo <i
                                                        class="las la-plus-circle"></i> </span>
                                                <input type="file" name="mother_photo" id="mother_photo_upload"
                                                    onchange="updateFileNameSwap(this, 'mother-photo-label')"
                                                    class="static-crop" />
                                            </label>
                                            <input name="mother_photoimage" id="mother_photoimage" value=""
                                                type="hidden" class="form-control" />
                                            <img id="mother_photoImage" class="rounded img-fluid" src=""
                                                style="display: none;" />
                                            @if ($oldAdmissionDetails && $oldAdmissionDetails->mother_photo_url != '')
                                                <div class="doc-download-box">
                                                    <a
                                                        href="{{ route('student.images.download', ['id' => $oldAdmissionDetails->id, 'fieldName' => 'mother_photo_url']) }}"><span>Mother’s
                                                            Passport Size Photo</span>
                                                        <img src="{{ asset('assets/images/download-icon.svg') }}">
                                                    </a>
                                                    <img src="{{ asset($oldAdmissionDetails->mother_photo_url) }}"
                                                        alt="" id="motherImg" class="uploaded-img">
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col d-flex align-items-center radio-field-wrap">
                                    <label class="form-label mb-0">Do you have a Local Guardian in Ahmedabad ?</label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check m-0">
                                            <input class="form-check-input" type="radio"
                                                name="is_local_guardian_in_ahmedabad" id="local_guardian_yes"
                                                value="true" @if ($oldAdmissionDetails && $oldAdmissionDetails->local_guardian_yes == true) checked @endif>
                                            <label class="form-check-label" for="local_guardian_yes">YES</label>
                                        </div>
                                        <div class="form-check m-0">
                                            <input class="form-check-input" type="radio"
                                                name="is_local_guardian_in_ahmedabad" id="local_guardian_no"
                                                value="false" @if ($oldAdmissionDetails && $oldAdmissionDetails->local_guardian_yes == false) checked @endif checked>
                                            <label class="form-check-label" for="local_guardian_no">NO</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3 local_guardian_fields">
                                <div class="col">
                                    <label for="local_guardian_name" class="form-label">Guardian Name (Ahmedabad
                                        only)</label>
                                    <input type="text" class="form-control" name="guardian_name"
                                        value="{{ $oldAdmissionDetails->guardian_name ?? '' }}" id="guardian_name"
                                        placeholder="Enter guardian name" />
                                </div>
                                <div class="col">
                                    <label for="local_guardian_relation" class="form-label">Guardian Relation
                                        (Ahmedabad
                                        only)</label>
                                    <input type="text" class="form-control" name="guardian_relation"
                                        value="{{ $oldAdmissionDetails->guardian_relation ?? '' }}"
                                        id="guardian_relation" placeholder="Enter guardian relation" />
                                </div>
                                <div class="col">
                                    <label for="local_guardian_contact" class="form-label">Guardian Contact Number
                                        (Ahmedabad
                                        only)</label>
                                    <input type="number" class="form-control" name="guardian_phone"
                                        value="{{ $oldAdmissionDetails->guardian_phone ?? '' }}" id="guardian_phone"
                                        placeholder="Enter guardian contact number" />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col d-flex align-items-center radio-field-wrap">
                                    <label class="form-label mb-0">Is your Parent’s Nationality Indian? </label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check m-0">
                                            <input class="form-check-input" type="radio"
                                                name="is_parent_indian_citizen" id="nationality_indian_yes"
                                                value="true" @if ($oldAdmissionDetails && $oldAdmissionDetails->is_parent_indian_citizen == true) checked @endif checked>
                                            <label class="form-check-label" for="nationality_indian_yes">YES</label>
                                        </div>
                                        <div class="form-check m-0">
                                            <input class="form-check-input" type="radio"
                                                name="is_parent_indian_citizen" id="nationality_indian_no" value="false"
                                                @if ($oldAdmissionDetails && $oldAdmissionDetails->is_parent_indian_citizen == false) checked @endif>
                                            <label class="form-check-label" for="nationality_indian_no">NO</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3 adhaar_number_parent_field">
                                <div class="col">
                                    <div class="error-message">
                                        <label class="form-label">Upload your Father/Mother’s Aadhar Card</label>
                                        <div class="upload-group">
                                            <div class="error-message">
                                                <label for="parents_aadhar_front_upload" class="custom-file-upload">
                                                    <span id="parents-aadhar-front-label">Aadhar Card Front <i
                                                            class="las la-plus-circle"></i></span>
                                                    <input type="file" name="parents_aadhar_front"
                                                        id="parents_aadhar_front_upload"
                                                        onchange="updateFileNameSwap(this, 'parents-aadhar-front-label')"
                                                        class="static-crop" />
                                                    <input name="parents_aadhar_frontimage" id="parents_aadhar_frontimage"
                                                        value="" type="hidden" class="form-control" />
                                                </label>
                                                <img id="parents_aadhar_frontImage" class="rounded img-fluid"
                                                    src="" style="display: none;" />
                                                @if ($oldAdmissionDetails && $oldAdmissionDocuments)
                                                    @foreach ($oldAdmissionDocuments as $document)
                                                        @if ($document->doc_type == 'Parent Aadhar Card Front')
                                                            <div class="doc-download-box">
                                                                <a
                                                                    href="{{ route('student.document.download', $document->id) }}"><span>Aadhar
                                                                        Card Front</span>
                                                                    <img
                                                                        src="{{ asset('assets/images/download-icon.svg') }}">
                                                                </a>
                                                                <img src="{{ asset($document->doc_url) }}" alt=""
                                                                    id="parentAadharFrontImg" class="uploaded-img">
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </div>
                                            <div class="error-message">
                                                <label for="parents_aadhar_back_upload" class="custom-file-upload">
                                                    <span id="parents-aadhar-back-label">Aadhar Card Back <i
                                                            class="las la-plus-circle"></i></span>
                                                    <input type="file" name="parents_aadhar_back"
                                                        id="parents_aadhar_back_upload"
                                                        onchange="updateFileNameSwap(this, 'parents-aadhar-back-label')"
                                                        class="static-crop" />
                                                    <input name="parents_aadhar_backimage" id="parents_aadhar_backimage"
                                                        value="" type="hidden" class="form-control" />
                                                </label>
                                                <img id="parents_aadhar_backImage" class="rounded img-fluid"
                                                    src="" style="display: none;" />
                                                @if ($oldAdmissionDetails && $oldAdmissionDocuments)
                                                    @foreach ($oldAdmissionDocuments as $document)
                                                        @if ($document->doc_type == 'Parent Aadhar Card Back')
                                                            <div class="doc-download-box">
                                                                <a
                                                                    href="{{ route('student.document.download', $document->id) }}"><span>Aadhar
                                                                        Card Back</span>
                                                                    <img
                                                                        src="{{ asset('assets/images/download-icon.svg') }}">
                                                                </a>
                                                                <img src="{{ asset($document->doc_url) }}" alt=""
                                                                    id="parentAadharBackImg" class="uploaded-img">
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3 passport_number_parent_field">
                                <div class="col">
                                    <label class="form-label">Upload your Father/Mother’s Passport</label>
                                    <div class="upload-group">
                                        <div class="error-message">
                                            <label for="parents_passport_front_upload" class="custom-file-upload">
                                                <span id="parents-passport-front-label">Passport Front <i
                                                        class="las la-plus-circle"></i></span>
                                                <input type="file" name="parents_passport_front"
                                                    id="parents_passport_front_upload"
                                                    onchange="updateFileNameSwap(this, 'parents-passport-front-label')"
                                                    class="static-crop" />
                                                <input name="parents_passport_frontimage" id="parents_passport_frontimage"
                                                    value="" type="hidden" class="form-control" />
                                            </label>
                                            <img id="parents_passport_frontImage" class="rounded img-fluid"
                                                src="" style="display: none;" />
                                            @if ($oldAdmissionDetails && $oldAdmissionDocuments)
                                                @foreach ($oldAdmissionDocuments as $document)
                                                    @if ($document->doc_type == 'Parent Passport Front')
                                                        <div class="doc-download-box">
                                                            <a
                                                                href="{{ route('student.document.download', $document->id) }}"><span>Passport
                                                                    Front</span>
                                                                <img src="{{ asset('assets/images/download-icon.svg') }}">
                                                            </a>
                                                            <img src="{{ asset($document->doc_url) }}" alt=""
                                                                id="parentPassportFrontImg" class="uploaded-img">
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="error-message">
                                            <label for="parents_passport_back_upload" class="custom-file-upload">
                                                <span id="parents-passport-back-label">Passport Card Back <i
                                                        class="las la-plus-circle"></i></span>
                                                <input type="file" name="parents_passport_back"
                                                    id="parents_passport_back_upload"
                                                    onchange="updateFileNameSwap(this, 'parents-passport-back-label')"
                                                    class="static-crop" />
                                                <input name="parents_passport_backimage" id="parents_passport_backimage"
                                                    value="" type="hidden" class="form-control" />
                                            </label>
                                            <img id="parents_passport_backImage" class="rounded img-fluid" src=""
                                                style="display: none;" />
                                            @if ($oldAdmissionDetails && $oldAdmissionDocuments)
                                                @foreach ($oldAdmissionDocuments as $document)
                                                    @if ($document->doc_type == 'Parent Passport Back')
                                                        <div class="doc-download-box">
                                                            <a
                                                                href="{{ route('student.document.download', $document->id) }}"><span>Passport
                                                                    Back</span>
                                                                <img src="{{ asset('assets/images/download-icon.svg') }}">
                                                            </a>
                                                            <img src="{{ asset($document->doc_url) }}" alt=""
                                                                id="parentPassportBackImg" class="uploaded-img">
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex step-btn-wrapper justify-content-between">
                                <button type="button" class="btn btn-prev">Previous</button>
                                <button type="button" class="btn btn-reset"><i class="las la-redo-alt"></i> Reset
                                    Form</button>
                                <button type="button" class="btn btn-next">Next</button>
                            </div>
                        </div>
                        <!-- education-details -->
                        <div id="education-details" class="content form-section">
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="education_type" class="form-label">Education</label>
                                    <select class="select2 form-select" id="education_type" name="education_type">
                                        <option value="">Select Education</option>
                                        <option value="HSC" @if ($oldAdmissionDetails && $oldAdmissionDetails->education_type == 'HSC') selected @endif>HSC
                                        </option>
                                        <option value="Diploma" @if ($oldAdmissionDetails && $oldAdmissionDetails->education_type == 'Diploma') selected @endif>Diploma
                                        </option>
                                        <option value="Bachelor's Degree"
                                            @if ($oldAdmissionDetails && $oldAdmissionDetails->education_type == "Bachelor's Degree") selected @endif>
                                            Bachelor's Degree</option>
                                        <option value="Master's Degree"
                                            @if ($oldAdmissionDetails && $oldAdmissionDetails->education_type == "Master's Degree") selected @endif>
                                            Master's Degree</option>
                                        <option value="Professional Degree"
                                            @if ($oldAdmissionDetails && $oldAdmissionDetails->education_type == 'Professional Degree') selected @endif>Professional Degree</option>
                                        <option value="Internship" @if ($oldAdmissionDetails && $oldAdmissionDetails->education_type == 'Internship') selected @endif>
                                            Internship</option>
                                        <option value="Job" @if ($oldAdmissionDetails && $oldAdmissionDetails->education_type == 'Job') selected @endif>Job
                                        </option>
                                    </select>
                                </div>
                                <div class="col">
                                    <input type="hidden" name="old_course_id" id="old_course_id"
                                        value="{{ $oldAdmissionDetails->course_id ?? 0 }}">
                                    <label for="course_name" class="form-label">Course</label>
                                    <div class="error-message">
                                        <select name="course_id" id="course_id" class="select2 form-select"
                                            data-placeholder="Select course" required>
                                            <option value="">Select Course</option>
                                            @foreach ($courses as $course)
                                                <option value="{{ $course->id }}"
                                                    @if ($oldAdmissionDetails && $oldAdmissionDetails->course_id == $course->id) selected @endif>
                                                    {{ $course->course_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <label for="institute_name" class="form-label">Institute Name</label>
                                    <input type="text" class="form-control" name="institute_name"
                                        value="{{ $oldAdmissionDetails->institute_name ?? '' }}"
                                        placeholder="Enter Institute name" id="institute_name" />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="admission_date" class="form-label">Admission Date</label>
                                    <input type="text" class="form-control" name="addmission_date"
                                        value="{{ $oldAdmissionDetails && $oldAdmissionDetails->addmission_date ? date('d/m/Y', strtotime($oldAdmissionDetails->addmission_date)) : '' }}"
                                        placeholder="DD/MM/YYYY" id="addmission_date" />
                                </div>
                                <div class="col">
                                    {{ date('Y') . '-' . date('Y', strtotime(' +1 year')) }}
                                    <label for="admission_year" class="form-label">Admission Year</label>
                                    <select name="year_of_addmission" id="year_of_addmission"
                                        class="select2 form-select">
                                        <option value="">Select Admission Year</option>
                                        @foreach ($addmission_years as $addmission_year)
                                            <option value="{{ $addmission_year }}"
                                                @if (date('Y') . '-' . date('Y', strtotime(' +1 year')) == $addmission_year) selected @endif>{{ $addmission_year }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <input type="hidden" id="last_semester"
                                    value="{{ $oldAdmissionDetails ? $oldAdmissionDetails->semester : 0 }}">
                                <input type="hidden" id="last_course"
                                    value="{{ $oldAdmissionDetails ? $oldAdmissionDetails->course_id : 0 }}">
                                <div class="col">
                                    <label for="semester_name" class="form-label">Semester</label>
                                    <select name="semester" id="semester" class="select2 form-select"
                                        data-placeholder="Select semester">
                                        <option value="">Select Semester</option>
                                        {{-- @foreach ($semesters as $semester)
                                                <option value="{{ $semester }}"
                                                    @if (!$studentAdmissionCheck && $semester == 1) selected @endif
                                                    @if ($oldAdmissionDetails && $oldAdmissionDetails->semester + 2 == $semester) selected @endif>
                                                    {{ $semester }}</option>
                                            @endforeach --}}
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="institute_start_time" class="form-label">Institute Start Time</label>
                                    <input type="time" class="form-control" name="college_start_time"
                                        value="{{ old('college_start_time') }}" placeholder="H:i"
                                        id="college_start_time" />
                                </div>
                                <div class="col">
                                    <label for="institute_end_time" class="form-label">Institute End Time</label>
                                    <input type="time" class="form-control" name="college_end_time"
                                        value="{{ old('college_end_time') }}" placeholder="H:i" id="college_end_time" />
                                </div>
                                <div class="col">
                                    <label for="arriving_date" class="form-label">Arriving Date at Hostel</label>
                                    <input type="text" class="form-control" name="arriving_date"
                                        value="{{ $oldAdmissionDetails && $oldAdmissionDetails->arriving_date ? date('d/m/Y', strtotime($oldAdmissionDetails->arriving_date)) : '' }}"
                                        placeholder="DD/MM/YYYY" id="arriving_date" />
                                </div>
                            </div>

                            <hr style="background-color:#1D1D1B33; margin-top: 30px; margin-bottom: 30px; ">

                            <div class="row mb-3">
                                <h3 class="form-sm-title">Fees Details</h3>
                                <div class="col">
                                    <label for="fees_receipt_date" class="form-label">Fees Receipt Date</label>
                                    <input type="text" class="form-control" name="college_fees_receipt_date"
                                        {{-- value="{{ $oldAdmissionDetails->college_fees_receipt_date ? date('d/m/Y', strtotime($oldAdmissionDetails->college_fees_receipt_date)): '' }}" placeholder="DD/MM/YYYY" --}} id="college_fees_receipt_date" />
                                </div>
                                <div class="col">
                                    <label class="form-label">Upload your current Fee Receipt</label>
                                    <div class="upload-group">
                                        <div class="error-message">
                                            <label for="fee_receipt_upload" class="custom-file-upload">
                                                <span id="fee-receipt-label">Current Fee Receipt <i
                                                        class="las la-plus-circle"></i>
                                                </span>
                                                <input type="file" class="form-control static-crop"
                                                    name="fee_receipt" id="fee_receipt_upload"
                                                    onchange="updateFileNameSwap(this, 'fee-receipt-label')" />
                                                <input name="fee_receiptimage" id="fee_receiptimage" value=""
                                                    type="hidden" class="form-control" />
                                            </label>
                                            <img id="fee_receiptImage" class="rounded img-fluid" src=""
                                                style="display: none;" />
                                        </div>
                                    </div>
                                </div>
                                <div class="semester-fees" data-course-id="{{ $document->course_id ?? 0 }}">
                                    @if ($oldAdmissionDetails && $oldAdmissionDocuments)
                                        <div class="col-12">
                                            <div class="Fees-download-wrap d-flex ">
                                                @foreach ($oldAdmissionDocuments as $document)
                                                    @if ($oldAdmissionDetails->course_id == $document->course_id)
                                                        @if (Str::startsWith($document->doc_type, 'Semester') && Str::endsWith($document->doc_type, 'Fees Receipt'))
                                                            <div class="fees-download-box">
                                                                <a
                                                                    href="{{ route('student.document.download', $document->id) }}">
                                                                    <span>{{ Str::words($document->doc_type, 2, '') }}</span>
                                                                    <img
                                                                        src="{{ asset('assets/images/download-icon.svg') }}">
                                                                </a>
                                                            </div>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <hr style="background-color:#1D1D1B33; margin-top: 30px; margin-bottom: 30px; ">
                            <div class="row mb-3">
                                <h3 class="form-sm-title">Required Documents</h3>

                                <div class="row mb-3">
                                    <div class="col-md-6 col-sm-12">
                                        <label for="board_type" class="form-label">Type of Board</label>
                                        <select class="select2 form-select" id="board_type" name="board_type">
                                            <option value="">Select Type of Board</option>
                                            <option value="GSEB" @if ($oldAdmissionDetails && $oldAdmissionDetails->board_type == 'GSEB') selected @endif>
                                                GSEB
                                            </option>
                                            <option value="CBSE" @if ($oldAdmissionDetails && $oldAdmissionDetails->board_type == 'CBSE') selected @endif>
                                                CBSE
                                            </option>
                                            <option value="CAIE" @if ($oldAdmissionDetails && $oldAdmissionDetails->board_type == 'CAIE') selected @endif>
                                                CAIE
                                            </option>
                                            <option value="Other" @if ($oldAdmissionDetails && $oldAdmissionDetails->board_type == 'Other') selected @endif>
                                                Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-sm-12 board_name_field">
                                        <label for="board_name" class="form-label">Mention your Board</label>
                                        <input type="text" class="form-control" name="board_name"
                                            value="{{ $oldAdmissionDetails->board_name ?? '' }}"
                                            placeholder="Enter your Board" id="board_name" />
                                    </div>
                                </div>
                                <div class="col-6 hsc-doc">
                                    <label class="form-label" for="degree_certificate_upload">Upload your HSC
                                        Result</label>
                                    <div class="upload-group">
                                        <div class="error-message">
                                            <label for="hsc_result_upload" class="custom-file-upload">
                                                <span id="hsc-result-label">HSC Result <i
                                                        class="las la-plus-circle"></i></span>
                                                <input type="file" class="form-control static-crop"
                                                    name="hsc_result" id="hsc_result_upload"
                                                    onchange="updateFileNameSwap(this, 'hsc-result-label')" />
                                                <input name="hsc_resultimage" id="hsc_resultimage" value=""
                                                    type="hidden" class="form-control" />
                                            </label>
                                            <img id="hsc_resultImage" class="rounded img-fluid" src=""
                                                style="display: none;" />
                                            @if ($oldAdmissionDetails && $oldAdmissionDocuments)
                                                @foreach ($oldAdmissionDocuments as $document)
                                                    @if ($document->doc_type == 'HSC')
                                                        <div class="doc-download-box">
                                                            <a
                                                                href="{{ route('student.document.download', $document->id) }}"><span>HSC</span>
                                                                <img
                                                                    src="{{ asset('assets/images/download-icon.svg') }}">
                                                            </a>
                                                            <img src="{{ asset($document->doc_url) }}" alt=""
                                                                id="hscImg" class="uploaded-img">
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6 ssc-doc">
                                    <label class="form-label" for="ssc_result_upload">Upload your SSC Result</label>
                                    <div class="upload-group">
                                        <div class="error-message">
                                            <label for="ssc_result_upload" class="custom-file-upload">
                                                <span id="ssc-result-label">SSC Result <i
                                                        class="las la-plus-circle"></i></span>
                                                <input type="file" class="form-control static-crop"
                                                    name="ssc_result" id="ssc_result_upload"
                                                    onchange="updateFileNameSwap(this, 'ssc-result-label')" />
                                                <input name="ssc_resultimage" id="ssc_resultimage" value=""
                                                    type="hidden" class="form-control" />
                                            </label>
                                            <img id="ssc_resultImage" class="rounded img-fluid" src=""
                                                style="display: none;" />
                                            @if ($oldAdmissionDetails && $oldAdmissionDocuments)
                                                @foreach ($oldAdmissionDocuments as $document)
                                                    @if ($document->doc_type == 'SSC')
                                                        <div class="doc-download-box">
                                                            <a
                                                                href="{{ route('student.document.download', $document->id) }}"><span>SSC</span>
                                                                <img
                                                                    src="{{ asset('assets/images/download-icon.svg') }}">
                                                            </a>
                                                            <img src="{{ asset($document->doc_url) }}" alt=""
                                                                id="sscImg" class="uploaded-img">
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                {{-- </div>
                            <div class="row mb-3"> --}}
                                <div class="col-6 last-qualification-doc">
                                    <label class="form-label" for="last_qualification_upload">Upload your Last
                                        Qualification
                                        Result</label>
                                    <div class="upload-group">
                                        <div class="error-message">
                                            <label for="last_qualification_upload" class="custom-file-upload">
                                                <span id="last-qualification-label">Result <i
                                                        class="las la-plus-circle"></i></span>
                                                <input type="file" class="form-control static-crop"
                                                    name="last_qualification_result" id="last_qualification_upload"
                                                    onchange="updateFileNameSwap(this, 'last-qualification-label')" />
                                                <input name="last_qualificationimage" id="last_qualificationimage"
                                                    value="" type="hidden" class="form-control" />
                                            </label>
                                            <img id="last_qualificationImage" class="rounded img-fluid" src=""
                                                style="display: none;" />
                                            @if ($oldAdmissionDetails && $oldAdmissionDocuments)
                                                @foreach ($oldAdmissionDocuments as $document)
                                                    @if ($document->doc_type == 'Last Qualification')
                                                        <div class="doc-download-box">
                                                            <a
                                                                href="{{ route('student.document.download', $document->id) }}"><span>Last
                                                                    Qualification</span>
                                                                <img
                                                                    src="{{ asset('assets/images/download-icon.svg') }}">
                                                            </a>
                                                            <img src="{{ asset($document->doc_url) }}" alt=""
                                                                id="lastQualificationImg" class="uploaded-img">
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6 leaving-doc">
                                    <label class="form-label" for="leaving_certificate_upload">Upload your Leaving
                                        Certificate</label>
                                    <div class="upload-group">
                                        <div class="error-message">
                                            <label for="leaving_certificate_upload" class="custom-file-upload">
                                                <span id="leaving-certificate-label">Leaving Certificate <i
                                                        class="las la-plus-circle"></i></span>
                                                <input type="file" class="form-control static-crop"
                                                    name="leaving_certificate" id="leaving_certificate_upload"
                                                    onchange="updateFileNameSwap(this, 'leaving-certificate-label')" />
                                                <input name="leaving_certificateimage" id="leaving_certificateimage"
                                                    value="" type="hidden" class="form-control" />
                                            </label>
                                            <img id="leaving_certificateImage" class="rounded img-fluid"
                                                src="" style="display: none;" />
                                            @if ($oldAdmissionDetails && $oldAdmissionDocuments)
                                                @foreach ($oldAdmissionDocuments as $document)
                                                    @if ($document->doc_type == 'Leaving')
                                                        <div class="doc-download-box">
                                                            <a
                                                                href="{{ route('student.document.download', $document->id) }}"><span>Leaving</span>
                                                                <img
                                                                    src="{{ asset('assets/images/download-icon.svg') }}">
                                                            </a>
                                                            <img src="{{ asset($document->doc_url) }}" alt=""
                                                                id="leavingImg" class="uploaded-img">
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3 degree-docs">
                                <div class="col-6">
                                    <label class="form-label" for="hsc_result_upload">Upload your Degree
                                        Certificate</label>
                                    <div class="upload-group">
                                        <div class="error-message">
                                            <label for="degree_certificate_upload" class="custom-file-upload">
                                                <span id="degree-certificate-label">Degree Certificate <i
                                                        class="las la-plus-circle"></i></span>
                                                <input type="file" class="form-control static-crop"
                                                    name="degree_certificate" id="degree_certificate_upload"
                                                    onchange="updateFileNameSwap(this, 'degree-certificate-label')" />
                                                <input name="degree_certificateimage" id="degree_certificateimage"
                                                    value="" type="hidden" class="form-control" />
                                            </label>
                                            <img id="degree_certificateImage" class="rounded img-fluid" src=""
                                                style="display: none;" />
                                            @if ($oldAdmissionDetails && $oldAdmissionDocuments)
                                                @foreach ($oldAdmissionDocuments as $document)
                                                    @if ($document->doc_type == 'Degree Certificate')
                                                        <div class="doc-download-box">
                                                            <a
                                                                href="{{ route('student.document.download', $document->id) }}"><span>Degree
                                                                    Certificate</span>
                                                                <img
                                                                    src="{{ asset('assets/images/download-icon.svg') }}">
                                                            </a>
                                                            <img src="{{ asset($document->doc_url) }}" alt=""
                                                                id="degreeCertificateImg" class="uploaded-img">
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col internship-doc">
                                    <label class="form-label" for="internship_letter_upload">Upload Your Internship
                                        Offer
                                        Letter</label>
                                    <div class="upload-group">
                                        <div class="error-message">
                                            <label for="internship_letter_upload" class="custom-file-upload">
                                                <span id="last-qualification-label">Internship Offer Letter <i
                                                        class="las la-plus-circle"></i></span>
                                                <input type="file" class="form-control static-crop"
                                                    name="internship_letter" id="internship_letter_upload"
                                                    onchange="updateFileNameSwap(this, 'last-qualification-label')" />
                                                <input name="internship_letterimage" id="internship_letterimage"
                                                    value="" type="hidden" class="form-control" />
                                            </label>
                                            <img id="internship_letterImage" class="rounded img-fluid" src=""
                                                style="display: none;" />
                                            @if ($oldAdmissionDetails && $oldAdmissionDocuments)
                                                @foreach ($oldAdmissionDocuments as $document)
                                                    @if ($document->doc_type == 'Internship Offer Letter')
                                                        <div class="doc-download-box">
                                                            <a
                                                                href="{{ route('student.document.download', $document->id) }}"><span>Internship
                                                                    Offer Letter</span>
                                                                <img
                                                                    src="{{ asset('assets/images/download-icon.svg') }}">
                                                            </a>
                                                            <img src="{{ asset($document->doc_url) }}" alt=""
                                                                id="degreeCertificateImg" class="uploaded-img">
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col job-doc">
                                    <label class="form-label" for="job_letter_upload">Upload your Job Offer
                                        Letter</label>
                                    <div class="upload-group">
                                        <div class="error-message">
                                            <label for="job_letter_upload" class="custom-file-upload">
                                                <span id="leaving-certificate-label">Job Offer Letter <i
                                                        class="las la-plus-circle"></i></span>
                                                <input type="file" class="form-control static-crop"
                                                    name="job_letter" id="job_letter_upload"
                                                    onchange="updateFileNameSwap(this, 'leaving-certificate-label')" />
                                                <input name="job_letterimage" id="job_letterimage" value=""
                                                    type="hidden" class="form-control" />
                                            </label>
                                            <img id="job_letterImage" class="rounded img-fluid" src=""
                                                style="display: none;" />
                                            @if ($oldAdmissionDetails && $oldAdmissionDocuments)
                                                @foreach ($oldAdmissionDocuments as $document)
                                                    @if ($document->doc_type == 'Job Offer Letter')
                                                        <div class="doc-download-box">
                                                            <a
                                                                href="{{ route('student.document.download', $document->id) }}"><span>Job
                                                                    Offer Letter</span>
                                                                <img
                                                                    src="{{ asset('assets/images/download-icon.svg') }}">
                                                            </a>
                                                            <img src="{{ asset($document->doc_url) }}" alt=""
                                                                id="degreeCertificateImg" class="uploaded-img">
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr style="background-color:#1D1D1B33; margin-top: 30px; margin-bottom: 30px; ">
                            <div class="row mb-3 backlog-option">
                                <div class="col d-flex align-items-center radio-field-wrap">
                                    <label class="form-label mb-0">Having any Backlog ?</label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check m-0">
                                            <input class="form-check-input" type="radio" name="having_any_backlog"
                                                id="backlog_yes" value="true">
                                            <label class="form-check-label" for="backlog_yes">Yes</label>
                                        </div>
                                        <div class="form-check m-0">
                                            <input class="form-check-input" type="radio" name="having_any_backlog"
                                                id="backlog_no" value="false" checked>
                                            <label class="form-check-label" for="backlog_no">N0</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3 degree-results">
                                <h3 class="form-sm-title">Results</h3>
                                <div class="col semester-group" data-sem="1">
                                    <label class="form-label" for="sem1_result_upload">Upload your Semester-1
                                        Result</label>
                                    <div class="upload-group">
                                        <div class="error-message">
                                            <label for="sem1_result_upload" class="custom-file-upload">
                                                <span id="sem1-result-label">SEM-1 Result <i
                                                        class="las la-plus-circle"></i></span>
                                                <input type="file" class="form-control static-crop"
                                                    name="semester_1_result" id="sem1_result_upload"
                                                    onchange="updateFileNameSwap(this, 'sem1-result-label')" />
                                                <input name="sem1_resultimage" id="sem1_resultimage" value=""
                                                    type="hidden" class="form-control" />
                                            </label>
                                            <img id="sem1_resultImage" class="rounded img-fluid" src=""
                                                style="display: none;" />
                                            @if ($oldAdmissionDetails && $oldAdmissionDocuments)
                                                @foreach ($oldAdmissionDocuments as $document)
                                                    @if ($document->doc_type == 'Semester 1' && $document->course_id == $oldAdmissionDetails->course_id)
                                                        <div class="semester-download-box"
                                                            data-course-id="{{ $document->course_id }}">
                                                            <div class="doc-download-box ">
                                                                <a
                                                                    href="{{ route('student.document.download', $document->id) }}"><span>Semester
                                                                        1</span>
                                                                    <img
                                                                        src="{{ asset('assets/images/download-icon.svg') }}">
                                                                </a>
                                                                <img src="{{ asset($document->doc_url) }}"
                                                                    alt="" id="semester1Img"
                                                                    class="uploaded-img">
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col semester-group" data-sem="2">
                                    <label class="form-label" for="sem2_result_upload">Upload your Semester-2
                                        Result</label>
                                    <div class="upload-group">
                                        <div class="error-message">
                                            <label for="sem2_result_upload" class="custom-file-upload">
                                                <span id="sem2-result-label">SEM-2 Result <i
                                                        class="las la-plus-circle"></i></span>
                                                <input type="file" class="form-control static-crop"
                                                    name="semester_2_result" id="sem2_result_upload"
                                                    onchange="updateFileNameSwap(this, 'sem2-result-label')" />
                                                <input name="sem2_resultimage" id="sem2_resultimage" value=""
                                                    type="hidden" class="form-control" />
                                            </label>
                                            <img id="sem2_resultImage" class="rounded img-fluid" src=""
                                                style="display: none;" />
                                            @if ($oldAdmissionDetails && $oldAdmissionDocuments)
                                                @foreach ($oldAdmissionDocuments as $document)
                                                    @if ($document->doc_type == 'Semester 2' && $document->course_id == $oldAdmissionDetails->course_id)
                                                        <div class="semester-download-box"
                                                            data-course-id="{{ $document->course_id }}">
                                                            <div class="doc-download-box">
                                                                <a
                                                                    href="{{ route('student.document.download', $document->id) }}"><span>Semester
                                                                        2</span>
                                                                    <img
                                                                        src="{{ asset('assets/images/download-icon.svg') }}">
                                                                </a>
                                                                <img src="{{ asset($document->doc_url) }}"
                                                                    alt="" id="semester2Img"
                                                                    class="uploaded-img">
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3 degree-results">
                                <div class="col semester-group" data-sem="3">
                                    <label class="form-label" for="sem3_result_upload">Upload your Semester-3
                                        Result</label>
                                    <div class="upload-group">
                                        <div class="error-message">
                                            <label for="sem3_result_upload" class="custom-file-upload">
                                                <span id="sem3-result-label">SEM-3 Result <i
                                                        class="las la-plus-circle"></i></span>
                                                <input type="file" class="form-control static-crop"
                                                    name="semester_3_result" id="sem3_result_upload"
                                                    onchange="updateFileNameSwap(this, 'sem3-result-label')" />
                                                <input name="sem3_resultimage" id="sem3_resultimage" value=""
                                                    type="hidden" class="form-control" />
                                            </label>
                                            <img id="sem3_resultImage" class="rounded img-fluid" src=""
                                                style="display: none;" />
                                            @if ($oldAdmissionDetails && $oldAdmissionDocuments)
                                                @foreach ($oldAdmissionDocuments as $document)
                                                    @if ($document->doc_type == 'Semester 3' && $document->course_id == $oldAdmissionDetails->course_id)
                                                        <div class="semester-download-box"
                                                            data-course-id="{{ $document->course_id }}">
                                                            <div class="doc-download-box ">
                                                                <a
                                                                    href="{{ route('student.document.download', $document->id) }}"><span>Semester
                                                                        3</span>
                                                                    <img
                                                                        src="{{ asset('assets/images/download-icon.svg') }}">
                                                                </a>
                                                                <img src="{{ asset($document->doc_url) }}"
                                                                    alt="" id="semester3Img"
                                                                    class="uploaded-img">
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col semester-group" data-sem="4">
                                    <label class="form-label" for="sem4_result_upload">Upload your Semester-4
                                        Result</label>
                                    <div class="upload-group">
                                        <div class="error-message">
                                            <label for="sem4_result_upload" class="custom-file-upload">
                                                <span id="sem4-result-label">SEM-4 Result <i
                                                        class="las la-plus-circle"></i></span>
                                                <input type="file" class="form-control static-crop"
                                                    name="semester_4_result" id="sem4_result_upload"
                                                    onchange="updateFileNameSwap(this, 'sem4-result-label')" />
                                                <input name="sem4_resultimage" id="sem4_resultimage" value=""
                                                    type="hidden" class="form-control" />
                                            </label>
                                            <img id="sem4_resultImage" class="rounded img-fluid" src=""
                                                style="display: none;" />
                                            @if ($oldAdmissionDetails && $oldAdmissionDocuments)
                                                @foreach ($oldAdmissionDocuments as $document)
                                                    @if ($document->doc_type == 'Semester 4' && $document->course_id == $oldAdmissionDetails->course_id)
                                                        <div class="semester-download-box"
                                                            data-course-id="{{ $document->course_id }}">
                                                            <div class="doc-download-box">
                                                                <a
                                                                    href="{{ route('student.document.download', $document->id) }}"><span>Semester
                                                                        4</span>
                                                                    <img
                                                                        src="{{ asset('assets/images/download-icon.svg') }}">
                                                                </a>
                                                                <img src="{{ asset($document->doc_url) }}"
                                                                    alt="" id="semester4Img"
                                                                    class="uploaded-img">
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3 degree-results">
                                <div class="col semester-group" data-sem="5">
                                    <label class="form-label" for="sem5_result_upload">Upload your Semester-5
                                        Result</label>
                                    <div class="upload-group">
                                        <div class="error-message">
                                            <label for="sem5_result_upload" class="custom-file-upload">
                                                <span id="sem5-result-label">SEM-5 Result <i
                                                        class="las la-plus-circle"></i></span>
                                                <input type="file" class="form-control static-crop"
                                                    name="semester_5_result" id="sem5_result_upload"
                                                    onchange="updateFileNameSwap(this, 'sem5-result-label')" />
                                                <input name="sem5_resultimage" id="sem5_resultimage" value=""
                                                    type="hidden" class="form-control" />
                                            </label>
                                            <img id="sem5_resultImage" class="rounded img-fluid" src=""
                                                style="display: none;" />
                                            @if ($oldAdmissionDetails && $oldAdmissionDocuments)
                                                @foreach ($oldAdmissionDocuments as $document)
                                                    @if ($document->doc_type == 'Semester 5')
                                                        <div class="semester-download-box"
                                                            data-course-id="{{ $document->course_id }}">
                                                            <div class="doc-download-box ">
                                                                <a
                                                                    href="{{ route('student.document.download', $document->id) }}"><span>Semester
                                                                        5</span>
                                                                    <img
                                                                        src="{{ asset('assets/images/download-icon.svg') }}">
                                                                </a>
                                                                <img src="{{ asset($document->doc_url) }}"
                                                                    alt="" id="semester5Img"
                                                                    class="uploaded-img">
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col semester-group" data-sem="6">
                                    <label class="form-label" for="sem6_result_upload">Upload your Semester-6
                                        Result</label>
                                    <div class="upload-group">
                                        <div class="error-message">
                                            <label for="sem6_result_upload" class="custom-file-upload">
                                                <span id="sem6-result-label">SEM-6 Result <i
                                                        class="las la-plus-circle"></i></span>
                                                <input type="file" class="form-control static-crop"
                                                    name="semester_6_result" id="sem6_result_upload"
                                                    onchange="updateFileNameSwap(this, 'sem6-result-label')" />
                                                <input name="sem6_resultimage" id="sem6_resultimage" value=""
                                                    type="hidden" class="form-control" />
                                            </label>
                                            <img id="sem6_resultImage" class="rounded img-fluid" src=""
                                                style="display: none;" />
                                            @if ($oldAdmissionDetails && $oldAdmissionDocuments)
                                                @foreach ($oldAdmissionDocuments as $document)
                                                    @if ($document->doc_type == 'Semester 6')
                                                        <div class="semester-download-box"
                                                            data-course-id="{{ $document->course_id }}">
                                                            <div class="doc-download-box">
                                                                <a
                                                                    href="{{ route('student.document.download', $document->id) }}"><span>Semester
                                                                        6</span>
                                                                    <img
                                                                        src="{{ asset('assets/images/download-icon.svg') }}">
                                                                </a>
                                                                <img src="{{ asset($document->doc_url) }}"
                                                                    alt="" id="semester6Img"
                                                                    class="uploaded-img">
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3 degree-results">
                                <div class="col semester-group" data-sem="7">
                                    <label class="form-label" for="sem7_result_upload">Upload your Semester-7
                                        Result</label>
                                    <div class="upload-group">
                                        <div class="error-message">
                                            <label for="sem7_result_upload" class="custom-file-upload">
                                                <span id="sem7-result-label">SEM-7 Result <i
                                                        class="las la-plus-circle"></i></span>
                                                <input type="file" class="form-control static-crop"
                                                    name="semester_7_result" id="sem7_result_upload"
                                                    onchange="updateFileNameSwap(this, 'sem7-result-label')" />
                                                <input name="sem7_resultimage" id="sem7_resultimage" value=""
                                                    type="hidden" class="form-control" />
                                            </label>
                                            <img id="sem7_resultImage" class="rounded img-fluid" src=""
                                                style="display: none;" />
                                            @if ($oldAdmissionDetails && $oldAdmissionDocuments)
                                                @foreach ($oldAdmissionDocuments as $document)
                                                    @if ($document->doc_type == 'Semester 7')
                                                        <div class="semester-download-box"
                                                            data-course-id="{{ $document->course_id }}">
                                                            <div class="doc-download-box">
                                                                <a
                                                                    href="{{ route('student.document.download', $document->id) }}"><span>Semester
                                                                        7</span>
                                                                    <img
                                                                        src="{{ asset('assets/images/download-icon.svg') }}">
                                                                </a>
                                                                <img src="{{ asset($document->doc_url) }}"
                                                                    alt="" id="semester7Img"
                                                                    class="uploaded-img">
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col semester-group" data-sem="8">
                                    <label class="form-label" for="sem8_result_upload">Upload your Semester-8
                                        Result</label>
                                    <div class="upload-group">
                                        <div class="error-message">
                                            <label for="sem8_result_upload" class="custom-file-upload">
                                                <span id="sem8-result-label">SEM-8 Result <i
                                                        class="las la-plus-circle"></i></span>
                                                <input type="file" class="form-control static-crop"
                                                    name="semester_8_result" id="sem8_result_upload"
                                                    onchange="updateFileNameSwap(this, 'sem8-result-label')" />
                                                <input name="sem8_resultimage" id="sem8_resultimage" value=""
                                                    type="hidden" class="form-control" />
                                            </label>
                                            <img id="sem8_resultImage" class="rounded img-fluid" src=""
                                                style="display: none;" />
                                            @if ($oldAdmissionDetails && $oldAdmissionDocuments)
                                                @foreach ($oldAdmissionDocuments as $document)
                                                    @if ($document->doc_type == 'Semester 8')
                                                        <div class="semester-download-box"
                                                            data-course-id="{{ $document->course_id }}">
                                                            <div class="doc-download-box">
                                                                <a
                                                                    href="{{ route('student.document.download', $document->id) }}"><span>Semester
                                                                        8</span>
                                                                    <img
                                                                        src="{{ asset('assets/images/download-icon.svg') }}">
                                                                </a>
                                                                <img src="{{ asset($document->doc_url) }}"
                                                                    alt="" id="semester8Img"
                                                                    class="uploaded-img">
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3 degree-results">
                                <div class="col semester-group" data-sem="9">
                                    <label class="form-label" for="sem9_result_upload">Upload your Semester-9
                                        Result</label>
                                    <div class="upload-group">
                                        <div class="error-message">
                                            <label for="sem9_result_upload" class="custom-file-upload">
                                                <span id="sem9-result-label">SEM-9 Result <i
                                                        class="las la-plus-circle"></i></span>
                                                <input type="file" class="form-control static-crop"
                                                    name="semester_9_result" id="sem9_result_upload"
                                                    onchange="updateFileNameSwap(this, 'sem9-result-label')" />
                                                <input name="sem9_resultimage" id="sem9_resultimage" value=""
                                                    type="hidden" class="form-control" />
                                            </label>
                                            <img id="sem9_resultImage" class="rounded img-fluid" src=""
                                                style="display: none;" />
                                            @if ($oldAdmissionDetails && $oldAdmissionDocuments)
                                                @foreach ($oldAdmissionDocuments as $document)
                                                    @if ($document->doc_type == 'Semester 9')
                                                        <div class="semester-download-box"
                                                            data-course-id="{{ $document->course_id }}">
                                                            <div class="doc-download-box">
                                                                <a
                                                                    href="{{ route('student.document.download', $document->id) }}"><span>Semester
                                                                        9</span>
                                                                    <img
                                                                        src="{{ asset('assets/images/download-icon.svg') }}">
                                                                </a>
                                                                <img src="{{ asset($document->doc_url) }}"
                                                                    alt="" id="semester9Img"
                                                                    class="uploaded-img">
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col semester-group" data-sem="10">
                                    <label class="form-label" for="sem10_result_upload">Upload your Semester-10
                                        Result</label>
                                    <div class="upload-group">
                                        <div class="error-message">
                                            <label for="sem10_result_upload" class="custom-file-upload">
                                                <span id="sem10-result-label">SEM-10 Result <i
                                                        class="las la-plus-circle"></i></span>
                                                <input type="file" class="form-control static-crop"
                                                    name="semester_10_result" id="sem10_result_upload"
                                                    onchange="updateFileNameSwap(this, 'sem10-result-label')" />
                                                <input name="sem10_resultimage" id="sem10_resultimage" value=""
                                                    type="hidden" class="form-control" />
                                            </label>
                                            <img id="sem10_resultImage" class="rounded img-fluid" src=""
                                                style="display: none;" />
                                            @if ($oldAdmissionDetails && $oldAdmissionDocuments)
                                                @foreach ($oldAdmissionDocuments as $document)
                                                    @if ($document->doc_type == 'Semester 10')
                                                        <div class="semester-download-box"
                                                            data-course-id="{{ $document->course_id }}">
                                                            <div class="doc-download-box">
                                                                <a
                                                                    href="{{ route('student.document.download', $document->id) }}"><span>Semester
                                                                        10</span>
                                                                    <img
                                                                        src="{{ asset('assets/images/download-icon.svg') }}">
                                                                </a>
                                                                <img src="{{ asset($document->doc_url) }}"
                                                                    alt="" id="semester10Img"
                                                                    class="uploaded-img">
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3 degree-backlog_results">
                                <h3 class="form-sm-title">Backlog Results</h3>
                                <div class="col semester-group" data-sem="1">
                                    <label class="form-label" for="sem1_backlog_result_upload">Upload your Semester-1
                                        Backlog Result</label>
                                    <div class="upload-group">
                                        <label for="sem1_backlog_result_upload" class="custom-file-upload">
                                            <span id="sem1-backlog_result-label">SEM-1 Backlog Result <i
                                                    class="las la-plus-circle"></i></span>
                                            <input type="file" class="form-control static-crop"
                                                name="semester_1_backlog_result" id="sem1_backlog_result_upload"
                                                onchange="updateFileNameSwap(this, 'sem1-backlog_result-label')" />
                                            <input name="sem1_backlog_resultimage" id="sem1_backlog_resultimage"
                                                value="" type="hidden" class="form-control" />
                                        </label>
                                        <img id="sem1_backlog_resultImage" class="rounded img-fluid" src=""
                                            style="display: none;" />
                                        @if ($oldAdmissionDetails && $oldAdmissionDocuments)
                                            @foreach ($oldAdmissionDocuments as $document)
                                                @if ($document->doc_type == 'Semester 1 (Backlog)')
                                                    <div class="semester-download-box"
                                                        data-course-id="{{ $document->course_id }}">
                                                        <div class="doc-download-box">
                                                            <a
                                                                href="{{ route('student.document.download', $document->id) }}"><span>Semester
                                                                    1</span>
                                                                <img
                                                                    src="{{ asset('assets/images/download-icon.svg') }}">
                                                            </a>
                                                            <img src="{{ asset($document->doc_url) }}" alt=""
                                                                id="semester8Img" class="uploaded-img">
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>

                                <div class="col semester-group" data-sem="2">
                                    <label class="form-label" for="sem2_backlog_result_upload">Upload your Semester-2
                                        Backlog Result</label>
                                    <div class="upload-group">
                                        <label for="sem2_backlog_result_upload" class="custom-file-upload">
                                            <span id="sem2-backlog_result-label">SEM-2 Backlog Result <i
                                                    class="las la-plus-circle"></i></span>
                                            <input type="file" class="form-control static-crop"
                                                name="semester_2_backlog_result" id="sem2_backlog_result_upload"
                                                onchange="updateFileNameSwap(this, 'sem2-backlog_result-label')" />
                                            <input name="sem2_backlog_resultimage" id="sem2_backlog_resultimage"
                                                value="" type="hidden" class="form-control" />
                                        </label>
                                        <img id="sem2_backlog_resultImage" class="rounded img-fluid" src=""
                                            style="display: none;" />
                                        @if ($oldAdmissionDetails && $oldAdmissionDocuments)
                                            @foreach ($oldAdmissionDocuments as $document)
                                                @if ($document->doc_type == 'Semester 2 (Backlog)')
                                                    <div class="semester-download-box"
                                                        data-course-id="{{ $document->course_id }}">
                                                        <div class="doc-download-box">
                                                            <a
                                                                href="{{ route('student.document.download', $document->id) }}"><span>Semester
                                                                    2</span>
                                                                <img
                                                                    src="{{ asset('assets/images/download-icon.svg') }}">
                                                            </a>
                                                            <img src="{{ asset($document->doc_url) }}" alt=""
                                                                id="semester8Img" class="uploaded-img">
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3 degree-backlog_results">
                                <div class="col semester-group" data-sem="3">
                                    <label class="form-label" for="sem3_backlog_result_upload">Upload your Semester-3
                                        Backlog Result</label>
                                    <div class="upload-group">
                                        <label for="sem3_backlog_result_upload" class="custom-file-upload">
                                            <span id="sem3-backlog_result-label">SEM-3 Backlog Result <i
                                                    class="las la-plus-circle"></i></span>
                                            <input type="file" class="form-control static-crop"
                                                name="semester_3_backlog_result" id="sem3_backlog_result_upload"
                                                onchange="updateFileNameSwap(this, 'sem3-backlog_result-label')" />
                                            <input name="sem3_backlog_resultimage" id="sem3_backlog_resultimage"
                                                value="" type="hidden" class="form-control" />
                                        </label>
                                        <img id="sem3_backlog_resultImage" class="rounded img-fluid" src=""
                                            style="display: none;" />
                                        @if ($oldAdmissionDetails && $oldAdmissionDocuments)
                                            @foreach ($oldAdmissionDocuments as $document)
                                                @if ($document->doc_type == 'Semester 3 (Backlog)')
                                                    <div class="semester-download-box"
                                                        data-course-id="{{ $document->course_id }}">
                                                        <div class="doc-download-box">
                                                            <a
                                                                href="{{ route('student.document.download', $document->id) }}"><span>Semester
                                                                    3</span>
                                                                <img
                                                                    src="{{ asset('assets/images/download-icon.svg') }}">
                                                            </a>
                                                            <img src="{{ asset($document->doc_url) }}" alt=""
                                                                id="semester8Img" class="uploaded-img">
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>

                                <div class="col semester-group" data-sem="4">
                                    <label class="form-label" for="sem4_backlog_result_upload">Upload your Semester-4
                                        Backlog Result</label>
                                    <div class="upload-group">
                                        <label for="sem4_backlog_result_upload" class="custom-file-upload">
                                            <span id="sem4-backlog_result-label">SEM-4 Backlog Result <i
                                                    class="las la-plus-circle"></i></span>
                                            <input type="file" class="form-control static-crop"
                                                name="semester_4_backlog_result" id="sem4_backlog_result_upload"
                                                onchange="updateFileNameSwap(this, 'sem4-backlog_result-label')" />
                                            <input name="sem4_backlog_resultimage" id="sem4_backlog_resultimage"
                                                value="" type="hidden" class="form-control" />
                                        </label>
                                        <img id="sem4_backlog_resultImage" class="rounded img-fluid" src=""
                                            style="display: none;" />
                                        @if ($oldAdmissionDetails && $oldAdmissionDocuments)
                                            @foreach ($oldAdmissionDocuments as $document)
                                                @if ($document->doc_type == 'Semester 4 (Backlog)')
                                                    <div class="semester-download-box"
                                                        data-course-id="{{ $document->course_id }}">
                                                        <div class="doc-download-box">
                                                            <a
                                                                href="{{ route('student.document.download', $document->id) }}"><span>Semester
                                                                    4</span>
                                                                <img
                                                                    src="{{ asset('assets/images/download-icon.svg') }}">
                                                            </a>
                                                            <img src="{{ asset($document->doc_url) }}" alt=""
                                                                id="semester8Img" class="uploaded-img">
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3 degree-backlog_results">
                                <div class="col semester-group" data-sem="5">
                                    <label class="form-label" for="sem5_backlog_result_upload">Upload your Semester-5
                                        Backlog Result</label>
                                    <div class="upload-group">
                                        <label for="sem5_backlog_result_upload" class="custom-file-upload">
                                            <span id="sem5-backlog_result-label">SEM-5 Backlog Result <i
                                                    class="las la-plus-circle"></i></span>
                                            <input type="file" class="form-control static-crop"
                                                name="semester_5_backlog_result" id="sem5_backlog_result_upload"
                                                onchange="updateFileNameSwap(this, 'sem5-backlog_result-label')" />
                                            <input name="sem5_backlog_resultimage" id="sem5_backlog_resultimage"
                                                value="" type="hidden" class="form-control" />
                                        </label>
                                        <img id="sem5_backlog_resultImage" class="rounded img-fluid" src=""
                                            style="display: none;" />
                                        @if ($oldAdmissionDetails && $oldAdmissionDocuments)
                                            @foreach ($oldAdmissionDocuments as $document)
                                                @if ($document->doc_type == 'Semester 5 (Backlog)')
                                                    <div class="semester-download-box"
                                                        data-course-id="{{ $document->course_id }}">
                                                        <div class="doc-download-box">
                                                            <a
                                                                href="{{ route('student.document.download', $document->id) }}"><span>Semester
                                                                    5</span>
                                                                <img
                                                                    src="{{ asset('assets/images/download-icon.svg') }}">
                                                            </a>
                                                            <img src="{{ asset($document->doc_url) }}" alt=""
                                                                id="semester8Img" class="uploaded-img">
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>

                                <div class="col semester-group" data-sem="6">
                                    <label class="form-label" for="sem6_backlog_result_upload">Upload your Semester-6
                                        Backlog Result</label>
                                    <div class="upload-group">
                                        <label for="sem6_backlog_result_upload" class="custom-file-upload">
                                            <span id="sem6-backlog_result-label">SEM-6 Backlog Result <i
                                                    class="las la-plus-circle"></i></span>
                                            <input type="file" class="form-control static-crop"
                                                name="semester_6_backlog_result" id="sem6_backlog_result_upload"
                                                onchange="updateFileNameSwap(this, 'sem6-backlog_result-label')" />
                                            <input name="sem6_backlog_resultimage" id="sem6_backlog_resultimage"
                                                value="" type="hidden" class="form-control" />
                                        </label>
                                        <img id="sem6_backlog_resultImage" class="rounded img-fluid" src=""
                                            style="display: none;" />
                                        @if ($oldAdmissionDetails && $oldAdmissionDocuments)
                                            @foreach ($oldAdmissionDocuments as $document)
                                                @if ($document->doc_type == 'Semester 6 (Backlog)')
                                                    <div class="semester-download-box"
                                                        data-course-id="{{ $document->course_id }}">
                                                        <div class="doc-download-box">
                                                            <a
                                                                href="{{ route('student.document.download', $document->id) }}"><span>Semester
                                                                    6</span>
                                                                <img
                                                                    src="{{ asset('assets/images/download-icon.svg') }}">
                                                            </a>
                                                            <img src="{{ asset($document->doc_url) }}" alt=""
                                                                id="semester8Img" class="uploaded-img">
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3 degree-backlog_results">
                                <div class="col semester-group" data-sem="7">
                                    <label class="form-label" for="sem7_backlog_result_upload">Upload your Semester-7
                                        Backlog Result</label>
                                    <div class="upload-group">
                                        <label for="sem7_backlog_result_upload" class="custom-file-upload">
                                            <span id="sem7-backlog_result-label">SEM-7 Backlog Result <i
                                                    class="las la-plus-circle"></i></span>
                                            <input type="file" class="form-control static-crop"
                                                name="semester_7_backlog_result" id="sem7_backlog_result_upload"
                                                onchange="updateFileNameSwap(this, 'sem7-backlog_result-label')" />
                                            <input name="sem7_backlog_resultimage" id="sem7_backlog_resultimage"
                                                value="" type="hidden" class="form-control" />
                                        </label>
                                        <img id="sem7_backlog_resultImage" class="rounded img-fluid" src=""
                                            style="display: none;" />
                                        @if ($oldAdmissionDetails && $oldAdmissionDocuments)
                                            @foreach ($oldAdmissionDocuments as $document)
                                                @if ($document->doc_type == 'Semester 7 (Backlog)')
                                                    <div class="semester-download-box"
                                                        data-course-id="{{ $document->course_id }}">
                                                        <div class="doc-download-box">
                                                            <a
                                                                href="{{ route('student.document.download', $document->id) }}"><span>Semester
                                                                    7</span>
                                                                <img
                                                                    src="{{ asset('assets/images/download-icon.svg') }}">
                                                            </a>
                                                            <img src="{{ asset($document->doc_url) }}" alt=""
                                                                id="semester8Img" class="uploaded-img">
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>

                                <div class="col semester-group" data-sem="8">
                                    <label class="form-label" for="sem8_backlog_result_upload">Upload your Semester-8
                                        Backlog Result</label>
                                    <div class="upload-group">
                                        <label for="sem8_backlog_result_upload" class="custom-file-upload">
                                            <span id="sem8-backlog_result-label">SEM-8 Backlog Result <i
                                                    class="las la-plus-circle"></i></span>
                                            <input type="file" class="form-control static-crop"
                                                name="semester_8_backlog_result" id="sem8_backlog_result_upload"
                                                onchange="updateFileNameSwap(this, 'sem8-backlog_result-label')" />
                                            <input name="sem8_backlog_resultimage" id="sem8_backlog_resultimage"
                                                value="" type="hidden" class="form-control" />
                                        </label>
                                        <img id="sem8_resultImage" class="rounded img-fluid" src=""
                                            style="display: none;" />
                                        @if ($oldAdmissionDetails && $oldAdmissionDocuments)
                                            @foreach ($oldAdmissionDocuments as $document)
                                                @if ($document->doc_type == 'Semester 8 (Backlog)')
                                                    <div class="semester-download-box"
                                                        data-course-id="{{ $document->course_id }}">
                                                        <div class="doc-download-box">
                                                            <a
                                                                href="{{ route('student.document.download', $document->id) }}"><span>Semester
                                                                    8</span>
                                                                <img
                                                                    src="{{ asset('assets/images/download-icon.svg') }}">
                                                            </a>
                                                            <img src="{{ asset($document->doc_url) }}" alt=""
                                                                id="semester8Img" class="uploaded-img">
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3 degree-backlog_results">
                                <div class="col semester-group" data-sem="9">
                                    <label class="form-label" for="sem9_backlog_result_upload">Upload your Semester-9
                                        Backlog Result</label>
                                    <div class="upload-group">
                                        <label for="sem9_backlog_result_upload" class="custom-file-upload">
                                            <span id="sem9-backlog_result-label">SEM-9 Backlog Result <i
                                                    class="las la-plus-circle"></i></span>
                                            <input type="file" class="form-control static-crop"
                                                name="semester_9_backlog_result" id="sem9_backlog_result_upload"
                                                onchange="updateFileNameSwap(this, 'sem9-backlog_result-label')" />
                                            <input name="sem9_backlog_resultimage" id="sem9_backlog_resultimage"
                                                value="" type="hidden" class="form-control" />
                                        </label>
                                        <img id="sem9_backlog_resultImage" class="rounded img-fluid" src=""
                                            style="display: none;" />
                                        @if ($oldAdmissionDetails && $oldAdmissionDocuments)
                                            @foreach ($oldAdmissionDocuments as $document)
                                                @if ($document->doc_type == 'Semester 9 (Backlog)')
                                                    <div class="semester-download-box"
                                                        data-course-id="{{ $document->course_id }}">
                                                        <div class="doc-download-box">
                                                            <a
                                                                href="{{ route('student.document.download', $document->id) }}"><span>Semester
                                                                    9</span>
                                                                <img
                                                                    src="{{ asset('assets/images/download-icon.svg') }}">
                                                            </a>
                                                            <img src="{{ asset($document->doc_url) }}" alt=""
                                                                id="semester9Img" class="uploaded-img">
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>

                                <div class="col semester-group" data-sem="10">
                                    <label class="form-label" for="sem10_backlog_result_upload">Upload your Semester-10
                                        Backlog Result</label>
                                    <div class="upload-group">
                                        <label for="sem10_backlog_result_upload" class="custom-file-upload">
                                            <span id="sem10-backlog_result-label">SEM-10 Backlog Result <i
                                                    class="las la-plus-circle"></i></span>
                                            <input type="file" class="form-control static-crop"
                                                name="semester_10_backlog_result" id="sem10_backlog_result_upload"
                                                onchange="updateFileNameSwap(this, 'sem10-backlog_result-label')" />
                                            <input name="sem10_backlog_resultimage" id="sem10_backlog_resultimage"
                                                value="" type="hidden" class="form-control" />
                                        </label>
                                        <img id="sem10_resultImage" class="rounded img-fluid" src=""
                                            style="display: none;" />
                                        @if ($oldAdmissionDetails && $oldAdmissionDocuments)
                                            @foreach ($oldAdmissionDocuments as $document)
                                                @if ($document->doc_type == 'Semester 10 (Backlog)')
                                                    <div class="semester-download-box"
                                                        data-course-id="{{ $document->course_id }}">
                                                        <div class="doc-download-box">
                                                            <a
                                                                href="{{ route('student.document.download', $document->id) }}"><span>Semester
                                                                    10</span>
                                                                <img
                                                                    src="{{ asset('assets/images/download-icon.svg') }}">
                                                            </a>
                                                            <img src="{{ asset($document->doc_url) }}" alt=""
                                                                id="semester10Img" class="uploaded-img">
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div id="degreeResultsSection"
                                data-old-course-id="{{ $oldAdmissionDetails ? $oldAdmissionDetails->course_id : '' }}">
                                <div class="row mb-3 degree-backlog_results">
                                    @if ($oldAdmissionDetails && $oldAdmissionDocuments)
                                        <h3 class="form-sm-title">{{ $oldAdmissionDetails->course->course_name }}
                                            Backlog Results</h3>
                                        <div class="col-12">
                                            <div class="Fees-download-wrap d-flex flex-wrap gap-3">
                                                @foreach ($oldAdmissionDocuments as $document)
                                                    @if (Str::contains($document->doc_type, '(Backlog)'))
                                                        <div class="semester-download-box"
                                                            data-course-id="{{ $document->course_id }}"
                                                            style="background: #eaf9fa; border-radius: 10px; min-width: 260px; max-height: 46px; padding: 12px 24px; display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
                                                            <span
                                                                style="color: #18b6c1; font-weight: 600; font-size: 16px;">
                                                                {{ $document->doc_type }}
                                                            </span>
                                                            <a href="{{ route('student.document.download', $document->id) }}"
                                                                style="color: #18b6c1; display: flex; align-items: center;">
                                                                <img src="{{ asset('assets/images/download-icon.svg') }}"
                                                                    style="width: 15px; height: 15px;" alt="Download">
                                                            </a>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="row mb-3 degree-results">
                                    @if ($oldAdmissionDetails && $oldAdmissionDocuments)
                                        <h3 class="form-sm-title">{{ $oldAdmissionDetails->course->course_name }}
                                            Results</h3>
                                        <div style="display: flex; flex-wrap: wrap; gap: 16px;">
                                            @foreach ($oldAdmissionDocuments as $document)
                                                @if (Str::contains($document->doc_type, 'Semester') &&
                                                        !Str::contains($document->doc_type, 'Fees Receipt') &&
                                                        !Str::contains($document->doc_type, '(Backlog)'))
                                                    <div class="semester-download-box"
                                                        data-course-id="{{ $document->course_id }}"
                                                        style="background: #eaf9fa; border-radius: 10px; min-width: 170px;max-height: 46px; padding: 12px 24px; display: flex; align-items: center; justify-content: space-between;">
                                                        <span style="color: #18b6c1; font-weight: 600; font-size: 16px;">
                                                            {{ $document->doc_type }}
                                                        </span>
                                                        <a href="{{ route('student.document.download', $document->id) }}"
                                                            style="margin-left: 16px; color: #18b6c1; display: flex; align-items: center;">
                                                            <img src="{{ asset('assets/images/download-icon.svg') }}"
                                                                style="width: 15px; height: 15px;" alt="Download">
                                                        </a>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>


                            {{-- <div class="row mb-3 degree-results">
                                <div class="col semester-group" data-sem="3">
                                    <label class="form-label" for="sem3_result_upload">Upload your Semester-3
                                        Result</label>
                                    <div class="upload-group">
                                        <label for="sem3_result_upload" class="custom-file-upload">
                                            <span id="sem3-result-label">SEM-3 Result <i
                                                    class="las la-plus-circle"></i></span>
                                            <input type="file" class="form-control static-crop"
                                                name="semester_3_result" id="sem3_result_upload"
                                                onchange="updateFileNameSwap(this, 'sem3-result-label')" />
                                            <input name="sem3_resultimage" id="sem3_resultimage" value=""
                                                type="hidden" class="form-control" />
                                        </label>
                                        <img id="sem3_resultImage" class="rounded img-fluid" src=""
                                            style="display: none;" />
                                        @if ($oldAdmissionDetails && $oldAdmissionDocuments)
                                            @foreach ($oldAdmissionDocuments as $document)
                                                @if ($document->doc_type == 'Semester 3')
                                                        <div class="doc-download-box">
                                                            <a
                                                                href="{{ route('student.document.download', $document->id) }}"><span>Semester
                                                                    3</span>
                                                                <img src="{{ asset('assets/images/download-icon.svg') }}">
                                                            </a>
                                                            <img src="{{ asset($document->doc_url) }}" alt=""
                                                                id="semester3Img" class="uploaded-img">
                                                        </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>

                                <div class="col semester-group" data-sem="4">
                                    <label class="form-label" for="sem4_result_upload">Upload your Semester-4
                                        Result</label>
                                    <div class="upload-group">
                                        <label for="sem4_result_upload" class="custom-file-upload">
                                            <span id="sem4-result-label">SEM-4 Result <i
                                                    class="las la-plus-circle"></i></span>
                                            <input type="file" class="form-control static-crop"
                                                name="semester_4_result" id="sem4_result_upload"
                                                onchange="updateFileNameSwap(this, 'sem4-result-label')" />
                                            <input name="sem4_resultimage" id="sem4_resultimage" value=""
                                                type="hidden" class="form-control" />
                                        </label>
                                        <img id="sem4_resultImage" class="rounded img-fluid" src=""
                                            style="display: none;" />
                                        @if ($oldAdmissionDetails && $oldAdmissionDocuments)
                                            @foreach ($oldAdmissionDocuments as $document)
                                                @if ($document->doc_type == 'Semester 4')
                                                        <div class="doc-download-box">
                                                            <a
                                                                href="{{ route('student.document.download', $document->id) }}"><span>Semester
                                                                    4</span>
                                                                <img src="{{ asset('assets/images/download-icon.svg') }}">
                                                            </a>
                                                            <img src="{{ asset($document->doc_url) }}" alt=""
                                                                id="semester4Img" class="uploaded-img">
                                                        </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div> --}}
                            <div class="row mb-3 ca-results">
                                <div class="col">
                                    <label class="form-label" for="ipcc_result_upload">Upload your IPCC Result</label>
                                    <div class="upload-group">
                                        <div class="error-message">
                                            <label for="ipcc_result_upload" class="custom-file-upload">
                                                <span id="ipcc-result-label">IPCC Result <i
                                                        class="las la-plus-circle"></i></span>
                                                <input type="file" class="form-control static-crop"
                                                    name="ipcc_result" id="ipcc_result_upload"
                                                    onchange="updateFileNameSwap(this, 'ipcc-result-label')" />
                                                <input name="ipcc_resultimage" id="ipcc_resultimage" value=""
                                                    type="hidden" class="form-control" />
                                            </label>
                                            <img id="ipcc_resultImage" class="rounded img-fluid" src=""
                                                style="display: none;" />
                                            @if ($oldAdmissionDetails && $oldAdmissionDocuments)
                                                @foreach ($oldAdmissionDocuments as $document)
                                                    @if ($document->doc_type == 'IPCC')
                                                        <div class="doc-download-box">
                                                            <a
                                                                href="{{ route('student.document.download', $document->id) }}"><span>IPCC</span>
                                                                <img
                                                                    src="{{ asset('assets/images/download-icon.svg') }}">
                                                            </a>
                                                            <img src="{{ asset($document->doc_url) }}" alt=""
                                                                id="ipccImg" class="uploaded-img">
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col">
                                    <label class="form-label" for="sem6_result_upload">Upload your CPT
                                        Result</label>
                                    <div class="upload-group">
                                        <div class="error-message">
                                            <label for="cpt_result_upload" class="custom-file-upload">
                                                <span id="cpt-result-label">CPT Result <i
                                                        class="las la-plus-circle"></i></span>
                                                <input type="file" class="form-control static-crop"
                                                    name="cpt_result" id="cpt_result_upload"
                                                    onchange="updateFileNameSwap(this, 'cpt-result-label')" />
                                                <input name="cpt_resultimage" id="cpt_resultimage" value=""
                                                    type="hidden" class="form-control" />
                                            </label>
                                            <img id="cpt_resultImage" class="rounded img-fluid" src=""
                                                style="display: none;" />
                                            @if ($oldAdmissionDetails && $oldAdmissionDocuments)
                                                @foreach ($oldAdmissionDocuments as $document)
                                                    @if ($document->doc_type == 'CPT')
                                                        <div class="doc-download-box">
                                                            <a
                                                                href="{{ route('student.document.download', $document->id) }}"><span>CPT</span>
                                                                <img
                                                                    src="{{ asset('assets/images/download-icon.svg') }}">
                                                            </a>
                                                            <img src="{{ asset($document->doc_url) }}" alt=""
                                                                id="cptImg" class="uploaded-img">
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3 ca-results">
                                <div class="col">
                                    <label class="form-label" for="ca_final_result_upload">Upload your CA Final
                                        Result</label>
                                    <div class="upload-group">
                                        <div class="error-message">
                                            <label for="ca_final_result_upload" class="custom-file-upload">
                                                <span id="ca_final-result-label">CA Final Result <i
                                                        class="las la-plus-circle"></i></span>
                                                <input type="file" class="form-control static-crop"
                                                    name="ca_final_result" id="ca_final_result_upload"
                                                    onchange="updateFileNameSwap(this, 'ca_final-result-label')" />
                                                <input name="ca_final_resultimage" id="ca_final_resultimage"
                                                    value="" type="hidden" class="form-control" />
                                            </label>
                                            <img id="ca_final_resultImage" class="rounded img-fluid" src=""
                                                style="display: none;" />
                                            @if ($oldAdmissionDetails && $oldAdmissionDocuments)
                                                @foreach ($oldAdmissionDocuments as $document)
                                                    @if ($document->doc_type == 'CA Final')
                                                        <div class="doc-download-box">
                                                            <a
                                                                href="{{ route('student.document.download', $document->id) }}"><span>CA
                                                                    Final</span>
                                                                <img
                                                                    src="{{ asset('assets/images/download-icon.svg') }}">
                                                            </a>
                                                            <img src="{{ asset($document->doc_url) }}" alt=""
                                                                id="caFinalImg" class="uploaded-img">
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex step-btn-wrapper justify-content-between">
                                <button type="button" class="btn btn-prev">Previous</button>
                                <button type="button" class="btn btn-reset"><i class="las la-redo-alt"></i> Reset
                                    Form</button>
                                <button type="button" class="btn btn-next">Next</button>
                            </div>
                        </div>
                        <!-- declaration -->
                        <div id="declaration" class="content form-section">
                            @include('frontend.admission._declaration')
                            <div class="row mb-3">
                                <div class="col declaration_textbox ">
                                    <label for="additional_notes" class="checkbox_field_label">Additional Notes</label>
                                    <textarea class="form-control" id="additional_notes" name="note"></textarea>
                                </div>
                            </div>
                            <div class="row g-4">
                                <div class="d-flex step-btn-wrapper justify-content-between">
                                    <button type="button" class="btn btn-prev">Previous</button>
                                    <button type="button" class="btn btn-reset"><i class="las la-redo-alt"></i> Reset
                                        Form</button>
                                    <button type="submit" class="btn btn-next">Next</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <div class="modal fade" id="cropImagePop" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Preview Image</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body pb-0">
                    <div class="row">
                        <div class="col-12 d-flex justify-content-center">
                            <!-- Square background wrapper -->
                            <div class="cropper-wrapper"
                                style="width: 400px; height: 400px; background: #000; display: flex; align-items: center; justify-content: center;">
                                <div id="upload-demo" class="licence_upload_demo w-100 h-100"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer justify-content-center">
                    <button type="button" data-deg="90" class="btn btn-warning rotateImageBtn">
                        <i class="las la-undo-alt"></i>
                    </button>
                    <button type="button" data-deg="-90" class="btn btn-warning rotateImageBtn">
                        <i class="las la-redo-alt"></i>
                    </button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="cropImageBtn">Save</button>
                </div>

            </div>
        </div>
    </div>
    {{-- <div class="modal fade" id="cropImagePop" tabindex="-1" aria-labelledby="cropImagePopLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered custom-cropper-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cropImagePopLabel">Preview Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-2">
                    <div class="cropper-wrapper"
                    style="width: 400px; height: 400px; background: #000; display: flex; align-items: center; justify-content: center;">
                    <div id="upload-demo" class="licence_upload_demo w-100 h-100"></div>
                </div>
                </div>
                <div class="modal-footer justify-content-center flex-wrap">
                    <button type="button" data-deg="90" class="btn btn-warning rotateImageBtn">
                        <i class="las la-undo-alt"></i>
                    </button>
                    <button type="button" data-deg="-90" class="btn btn-warning rotateImageBtn">
                        <i class="las la-redo-alt"></i>
                    </button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="cropImageBtn">Save</button>
                </div>
            </div>
        </div>
    </div> --}}
@endsection
@section('scripts')
    <!-- Page JS -->

    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendor/bs-stepper/bs-stepper.js') }}"></script>
    <script src="{{ asset('assets/vendor/bundle/popular.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/plugin-bootstrap5/index.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/plugin-auto-focus/index.min.js') }}"></script>
    <script src="{{ asset('js/admission/admission_frontend.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <script>
        var currentImageInputId;
        var $uploadCrop;

        $(document).ready(function() {
            // var $uploadCrop, tempFilename, rawImg, imageId;
            let cropper;
            let currentFileInput = null;
            let isStaticCrop = false;
            let image = document.createElement('img');
            image.style.maxWidth = '100%';
            image.style.display = 'block';

            $(document).on('change', 'input[type="file"]', function() {
                const file = this.files[0];
                currentFileInput = $(this);
                isStaticCrop = currentFileInput.hasClass('static-crop');

                if (file && /^image\//.test(file.type)) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        // Always create a new image element for cropper
                        let image = document.createElement('img');
                        image.style.maxWidth = '100%';
                        image.style.display = 'block';
                        image.src = e.target.result;

                        $('#upload-demo').html(image);

                        // Show modal
                        var cropModal = new bootstrap.Modal(document.getElementById('cropImagePop'));
                        cropModal.show();

                        if (cropper) cropper.destroy();

                        const screenWidth = window.innerWidth;
                        let cropperOptions;

                        if (screenWidth > 767) {
                            cropperOptions = {
                                aspectRatio: NaN,
                                viewMode: 2,
                                autoCropArea: 1,
                                responsive: true,
                                background: false,
                                movable: true,
                                zoomable: true,
                                rotatable: true,
                                scalable: true,
                                minContainerWidth: 400,
                                minContainerHeight: 400,
                                maxContainerWidth: 400,
                                maxContainerHeight: 400,
                                minCanvasWidth: 400,
                                minCanvasHeight: 400
                            };
                        } else {
                            const vw75 = window.innerWidth * 0.75;
                            cropperOptions = {
                                aspectRatio: NaN,
                                viewMode: 2,
                                autoCropArea: 1,
                                responsive: true,
                                background: false,
                                movable: true,
                                zoomable: true,
                                rotatable: true,
                                scalable: true,
                                minContainerWidth: vw75,
                                minContainerHeight: vw75,
                                maxContainerWidth: vw75,
                                maxContainerHeight: vw75,
                                minCanvasWidth: vw75,
                                minCanvasHeight: vw75
                            };
                        }

                        cropper = new Cropper(image, cropperOptions);

                    };
                    reader.readAsDataURL(file);
                }
            });

            // Rotate Buttons
            $('.rotateImageBtn').on('click', function() {
                const degree = parseInt($(this).data('deg'));
                if (cropper) cropper.rotate(degree);
            });

            // Save cropped image
            $('#cropImageBtn').on('click', function() {
                if (!cropper || !currentFileInput) return;

                cropper.getCroppedCanvas().toBlob(function(blob) {
                    const reader = new FileReader();
                    reader.onloadend = function() {
                        const base64data = reader.result;

                        if (isStaticCrop) {
                            const inputId = currentFileInput.attr('id');
                            let imgId, hiddenId;

                            // Handle different naming patterns
                            if (inputId.includes('_upload')) {
                                // For custom file upload structure like passport_photo_upload
                                const baseName = inputId.replace('_upload', '');
                                imgId = baseName + 'Image';
                                hiddenId = baseName + 'image';
                            } else if (inputId.includes('_photo_url')) {
                                // For form-floating structure like student_photo_url
                                imgId = inputId.replace('_photo_url', 'Image');
                                hiddenId = inputId.replace('_photo_url', 'image');
                            } else {
                                // Fallback for other naming patterns
                                imgId = inputId + 'Image';
                                hiddenId = inputId + 'image';
                            }

                            // Find and update the image element
                            $('#' + imgId)
                                .attr('src', base64data)
                                .css({
                                    display: 'block',
                                    width: 75,
                                    height: 75
                                });

                            // Update the hidden input with base64 data
                            $('#' + hiddenId).val(base64data);
                        } else {
                            // dynamic field handling
                            const parentDiv = currentFileInput.closest('.form-floating');
                            const previewImg = parentDiv.find('img');
                            const hiddenInput = parentDiv.find('input[type="hidden"]');

                            previewImg.attr('src', base64data).css({
                                display: 'block',
                                width: 75,
                                height: 75
                            });
                            hiddenInput.val(base64data);
                        }
                        // Use Bootstrap 5 modal API to hide
                        var cropModal = bootstrap.Modal.getInstance(document.getElementById(
                            'cropImagePop'));
                        cropModal.hide();

                        cropper.destroy();
                        cropper = null;
                        currentFileInput = null;
                        isStaticCrop = false;
                    };
                    reader.readAsDataURL(blob);
                }, 'image/jpeg');

                // Remove the name attribute to prevent the original file from being submitted
                currentFileInput.removeAttr('name');
            });
        });
        $(document).ready(function() {
            // List of all field base names
            const fieldBases = [
                'hsc_result',
                'ssc_result',
                'last_qualification',
                'leaving_certificate',
                'degree_certificate',
                'parents_passport_front',
                'parents_passport_back',
                'parents_aadhar_front',
                'parents_aadhar_back',
                'aadhar_front',
                'aadhar_back',
                'passport_front',
                'passport_back'
            ];

            // Dynamically build uploadFields array
            const uploadFields = fieldBases.map(function(base) {
                return {
                    fileInput: '#' + base + '_upload',
                    hiddenInput: '#' + base + 'image',
                    imgPreview: '#' + base + 'Image'
                };
            });

            uploadFields.forEach(function(field) {
                $(field.fileInput).on('change', function() {
                    const input = this;
                    if (input.files && input.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            $(field.hiddenInput).val(e.target.result);
                        };
                        reader.readAsDataURL(input.files[0]);
                    }
                });
            });
        });

        $(document).ready(function() {
            function toggleDegreeResultsSection() {
                var selectedCourseId = $('#course_id').val();
                var oldCourseId = $('#degreeResultsSection').data('old-course-id');
                if (selectedCourseId == oldCourseId) {
                    $('#degreeResultsSection').show();
                    $('.semester-download-box,.semester-fees').each(function() {
                        var docCourseId = $(this).data('course-id');
                        if (selectedCourseId == docCourseId) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                } else {
                    $('#degreeResultsSection').hide();
                }
            }

            $('#course_id').on('change', function() {
                toggleDegreeResultsSection();
            });

            // Initial state
            $('#course_id').trigger('change');
            toggleDegreeResultsSection();
        });
    </script>
@endsection
