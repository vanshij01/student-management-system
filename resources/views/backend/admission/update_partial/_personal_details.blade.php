<div id="personal-details" class="content form-section">
    <div class="mb_30">
        <div class="d-flex gap-3">
            <div class="form-check ps-0 m-0">
                <input class="form-check-input" type="radio" name="is_admission_new" id="new_is_"
                    @if ($admissionDetail->is_admission_new == true) checked @endif value="true" disabled>
                <label class="form-check-label" for="new_student">New Student</label>
            </div>
            <div class="form-check m-0">
                <input class="form-check-input" type="radio" name="is_admission_new" id="old_student"
                    @if ($admissionDetail->is_admission_new == false) checked @endif value="false" disabled>
                <label class="form-check-label" for="old_student">Old Student</label>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label for="student_id" class="form-label">Student</label>
            <select name="student_id" id="student_id" class="select2 form-select" data-placeholder="Select student" disabled>
                <option value="">Select Student</option>
                @foreach ($students as $student)
                    <option value="{{ $student->id }}" @if ($studentData->id == $student->id) selected @endif>
                        {{ $student->full_name }}
                    </option>
                @endforeach
            </select>
            <input type="hidden" name="student_id" value="{{ $studentData->id }}">
        </div>
        <div class="col">
            <label for="first_name" class="form-label">First Name</label>
            <input type="text" class="form-control" name="first_name"
                value="{{ old('first_name', $studentData->first_name) }}" id="first_name" placeholder="Enter first name"
                readonly />
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
            <select name="gender" id="gender" class="select2 form-select" data-placeholder="Select gender" disabled>
                <option value="">Select Gender</option>
                <option @if ($studentData->gender == 'boy') selected @endif value="boy">
                    Boy
                </option>
                <option @if ($studentData->gender == 'girl') selected @endif value="girl">
                    Girl
                </option>
            </select>
            <input type="hidden" name="gender" value="{{ $studentData->gender }}">
        </div>
        <div class="col">
            <label for="phone_number" class="form-label">Phone Number</label>
            <input type="number" class="form-control" name="phone" value="{{ old('phone', $studentData->phone) }}"
                id="phone" placeholder="Enter mobile number" readonly />
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
            <input type="email" class="form-control" name="email" value="{{ old('email', $studentData->email) }}"
                id="email" placeholder="Enter email" readonly />
        </div>
        <div class="col">
            <label for="address" class="form-label">Permanent Address</label>
            <input type="text" class="form-control" name="residence_address"
                value="{{ old('residence_address', $studentData->address) }}" id="residence_address"
                placeholder="Enter address (as per ID proof)" />
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label for="village" class="form-label">Village</label>
            <select id="village_id" class="select2 form-select" data-placeholder="Select village" disabled>
                <option value="">Select Village</option>
                @foreach ($villages as $village)
                    <option value="{{ $village->id }}" @if ($village->id == $studentData->village_id) selected @endif>
                        {{ $village->name }}
                    </option>
                @endforeach
            </select>
            <input type="hidden" name="village_id" value="{{ $studentData->village_id }}">
        </div>
        <div class="col">
            <label for="country" class="form-label">Country</label>
            <select name="country" id="country_id" class="select2 form-select" data-placeholder="Select country"
                readonly>
                <option value="">Select Country</option>
                @foreach ($countries as $country)
                    <option value="{{ $country->id }}" @if ($country->id == $studentData->country_id) selected @endif>
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
                    <input class="form-check-input" type="radio" name="is_indian_citizen" id="citizen_yes"
                        value="true" @if ($admissionDetail && $admissionDetail->is_indian_citizen == true) checked @endif>
                    <label class="form-check-label" for="citizen_yes">YES</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="is_indian_citizen" id="citizen_no"
                        value="false" @if ($admissionDetail && $admissionDetail->is_indian_citizen == false) checked @endif>
                    <label class="form-check-label" for="citizen_no">NO</label>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="error-message drop-area">
                <label for="passport_photo" class="form-label">Upload your Passport Size
                    Photo</label>
                <label for="passport_photo_upload" class="custom-file-upload">
                    <span id="passport-photo-label"
                        data-default='Upload Passport Size Photo <i class="las la-plus-circle"></i>'>
                        Upload Passport Size Photo <i class="las la-plus-circle"></i>
                    </span>
                    <input type="file" name="passport_photo" id="passport_photo_upload"
                        onchange="updateFileNameSwap(this, 'passport-photo-label')" class="static-crop"
                        data-param="student_photo_url" data-folder="student_photo" data-prefix="student_photo_" />
                </label>
            </div>
            <input id="passport_photoimage" type="hidden" />
            <div class="mb-3 d-none" id="passport_photo_upload_wrapper"
                style="display: flex;justify-content: flex-start;align-items: center;">
                <div class="spinner-border" style="margin-right: 10px;" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <label id="lbUploadedImge" for="">Uploading Student Photo...</label>
            </div>
            <img id="passport_photoImage" class="rounded img-fluid" src="" style="display: none;" />
            @if ($admissionDetail && $admissionDetail->student_photo_url != '')
            {{-- {{$admissionDetail->student_photo_url}} --}}
            {{-- {{$admissionDetail->id}} --}}
                <div class="doc-download-box">
                    <a
                        href="{{ route('student.images.download', ['id' => $admissionDetail->id, 'fieldName' => 'student_photo_url']) }}"><span>Passport
                            Size Photo</span>
                        <img src="{{ asset('assets/images/download-icon.svg') }}">
                    </a>
                    <img src="{{ asset($admissionDetail->student_photo_url) }}" alt="" id="studentImg"
                        class="uploaded-img">
                </div>
            @endif
        </div>
    </div>
    <div class="row mb-3 adhaar_number_field">
        <div class="col">
            <label for="adhaar_number" class="form-label">Aadhar Number</label>
            <input type="number" class="form-control" id="adhaar_number" name="adhaar_number"
                value="{{ $admissionDetail->adhaar_number ?? '' }}" placeholder="Aadhar Number">
        </div>
        <div class="col">
            <label class="form-label">Upload your Aadhar Card</label>
            <div class="upload-group">
                <div class="error-message drop-area">
                    <label for="aadhar_front_upload" class="custom-file-upload">
                        <span id="aadhar-front-label" data-default="Upload Aadhar Card Front">Aadhar Card Front <i
                                class="las la-plus-circle"></i></span>
                        <input type="file" data-param="aadhar_front_url" data-folder="Aadhar Card Front"
                            data-prefix="aadhar_front_" name="aadhar_front" id="aadhar_front_upload"
                            onchange="updateFileNameSwap(this, 'aadhar-front-label')" class="static-crop" />
                    </label>
                    <input id="aadhar_frontimage" type="hidden" />
                    <div class="mb-3 d-none" id="aadhar_front_upload_wrapper"
                        style="display: flex;justify-content: flex-start;align-items: center;">
                        <div class="spinner-border" style="margin-right: 10px;" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <label id="lbUploadedImge" for="">Uploading Student Photo...</label>
                    </div>
                    <div class="mb-3 d-none" id="aadhar_front_upload_wrapper"
                        style="display: flex;justify-content: flex-start;align-items: center;">
                        <div class="spinner-border" style="margin-right: 10px;" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <label id="lbUploadedImge" for="">Uploading Student Photo...</label>
                    </div>
                    <img id="aadhar_frontImage" class="rounded img-fluid" src="" style="display: none;" />
                    @php
                        $document = $documents->firstWhere('doc_type', 'Aadhar Card Front');
                    @endphp
                    @if ($document)
                        <div class="doc-download-box">
                            <a href="{{ route('student.document.download', $document->id) }}"><span>Aadhar
                                    Card Front</span>
                                <img src="{{ asset('assets/images/download-icon.svg') }}">
                            </a>
                            <img src="{{ asset($document->doc_url) }}" alt="" id="studentAadharFrontImg"
                                class="uploaded-img">
                        </div>
                    @endif
                </div>
                <div class="error-message drop-area">
                    <label for="aadhar_back_upload" class="custom-file-upload">
                        <span id="aadhar-back-label">Aadhar Card Back <i class="las la-plus-circle"></i></span>
                        <input type="file" data-param="aadhar_back_url" data-folder="Aadhar Card Back"
                            data-prefix="aadhar_back_" name="aadhar_back" id="aadhar_back_upload"
                            onchange="updateFileNameSwap(this, 'aadhar-back-label')" class="static-crop" />
                    </label>
                    <input id="aadhar_backimage" type="hidden" />
                    <div class="mb-3 d-none" id="aadhar_back_upload_wrapper"
                        style="display: flex;justify-content: flex-start;align-items: center;">
                        <div class="spinner-border" style="margin-right: 10px;" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <label id="lbUploadedImge" for="">Uploading Student Photo...</label>
                    </div>
                    <img id="aadhar_backImage" class="rounded img-fluid" src="" style="display: none;" />
                    @if ($admissionDetail && $documents)
                        @php
                            $document = $documents->firstWhere('doc_type', 'Aadhar Card Back');
                        @endphp
                        @if ($document)
                            <div class="doc-download-box">
                                <a href="{{ route('student.document.download', $document->id) }}"><span>Aadhar
                                        Card Back</span>
                                    <img src="{{ asset('assets/images/download-icon.svg') }}">
                                </a>
                                <img src="{{ asset($document->doc_url) }}" alt="" id="studentAadharBackImg"
                                    class="uploaded-img">
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3 passport_number_student_field d-none">
        <div class="col">
            <label for="passport_number" class="form-label">Passport Number</label>
            <input type="text" class="form-control" id="passport_number" name="passport_number"
                value="{{ $admissionDetail->passport_number ?? '' }}" placeholder="Passport Number">
        </div>
        <div class="col">
            <label class="form-label">Upload your Passport</label>
            <div class="upload-group">
                <div class="error-message drop-area">
                    <label for="passport_front_upload" class="custom-file-upload">
                        <span id="passport-front-label">Passport Front <i class="las la-plus-circle"></i></span>
                        <input type="file" name="passport_front" id="passport_front_upload"
                            onchange="updateFileNameSwap(this, 'passport-front-label')" class="static-crop"
                            data-param="passport_front_url" data-folder="Passport Front"
                            data-prefix="passport_front_" />
                    </label>
                    <input id="passport_frontimage" type="hidden" />
                    <div class="mb-3 d-none" id="passport_front_upload_wrapper"
                        style="display: flex;justify-content: flex-start;align-items: center;">
                        <div class="spinner-border" style="margin-right: 10px;" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <label id="lbUploadedImge" for="">Uploading Student Photo...</label>
                    </div>
                    <img id="passport_frontImage" class="rounded img-fluid" src="" style="display: none;" />
                    @if ($admissionDetail && $documents)
                        @php
                            $document = $documents->firstWhere('doc_type', 'Passport Front');
                        @endphp
                        @if ($document)
                            <div class="doc-download-box">
                                <a href="{{ route('student.document.download', $document->id) }}"><span>Passport
                                        Front</span>
                                    <img src="{{ asset('assets/images/download-icon.svg') }}">
                                </a>
                                <img src="{{ asset($document->doc_url) }}" alt=""
                                    id="studentPassportFrontImg" class="uploaded-img">
                            </div>
                        @endif
                    @endif
                </div>
                <div class="error-message drop-area">
                    <label for="passport_back_upload" class="custom-file-upload">
                        <span id="passport-back-label">Passport Back <i class="las la-plus-circle"></i></span>
                        <input type="file" name="passport_back" id="passport_back_upload"
                            onchange="updateFileNameSwap(this, 'passport-back-label')" class="static-crop"
                            data-param="passport_back_url" data-folder="Passport Back"
                            data-prefix="passport_back_" />
                        <input id="passport_backimage" type="hidden" />
                        <div class="mb-3 d-none" id="passport_back_upload_wrapper"
                            style="display: flex;justify-content: flex-start;align-items: center;">
                            <div class="spinner-border" style="margin-right: 10px;" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <label id="lbUploadedImge" for="">Uploading Student Photo...</label>
                        </div>
                    </label>
                    <img id="passport_backImage" class="rounded img-fluid" src="" style="display: none;" />
                    @if ($admissionDetail && $documents)
                        @php
                            $document = $documents->firstWhere('doc_type', 'Passport Back');
                        @endphp
                        @if ($document)
                            <div class="doc-download-box">
                                <a href="{{ route('student.document.download', $document->id) }}"><span>Passport
                                        Back</span>
                                    <img src="{{ asset('assets/images/download-icon.svg') }}">
                                </a>
                                <img src="{{ asset($document->doc_url) }}" alt=""
                                    id="studentPassportBackImg" class="uploaded-img">
                            </div>
                        @endif
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
                    <input class="form-check-input" type="radio" name="is_any_illness" id="illness_yes"
                        value="true" @if ($admissionDetail && $admissionDetail->is_any_illness == true) checked @endif>
                    <label class="form-check-label" for="illness_yes">YES</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="is_any_illness" id="illness_no"
                        value="false" @if ($admissionDetail && $admissionDetail->is_any_illness == false) checked @endif>
                    <label class="form-check-label" for="illness_no">NO</label>
                </div>
            </div>
        </div>
        <div class="col student_illness_field d-none">
            <label for="illness_desc" class="form-label">Describe your illness in
                brief</label>
            <textarea class="form-control" id="illness_desc" name="illness_description"
                placeholder="Describe your illness in brief">{{ $admissionDetail->illness_description ?? '' }}</textarea>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col d-flex align-items-center radio-field-wrap">
            <label class="form-label mb-0">Will you be using a vehicle in Ahmedabad?</label>
            <div class="d-flex gap-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="is_used_vehicle" id="vehicle_yes"
                        value="true" @if ($admissionDetail && $admissionDetail->is_used_vehicle == true) checked @endif>
                    <label class="form-check-label" for="vehicle_yes">YES</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="is_used_vehicle" id="vehicle_no"
                        value="false" @if ($admissionDetail && $admissionDetail->is_used_vehicle == false) checked @endif>
                    <label class="form-check-label" for="vehicle_no">NO</label>
                </div>
            </div>
        </div>
        <div class="col vehicle_details_field">
            <label for="vehicle_number" class="form-label">Vehicle Number</label>
            <input type="text" class="form-control" id="vehicle_number" name="vehicle_number"
                value="{{ $admissionDetail->vehicle_number ?? '' }}" placeholder="Vehicle Number">
        </div>
    </div>
    <div class="row mb-3 vehicle_details_field">
        <div class="col d-flex align-items-center radio-field-wrap">
            <label class="form-label mb-0">Do you have a helmet?</label>
            <div class="d-flex gap-3 form-check-error">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="is_have_helmet" id="helmet_yes"
                        value="true" checked>
                    <label class="form-check-label" for="helmet_yes">YES</label>
                </div>
                <div class="form-check position-relative">
                    <input class="form-check-input" type="radio" name="is_have_helmet" id="helmet_no"
                        value="false">
                    <label class="form-check-label" for="helmet_no">NO</label>
                    <span class="helmet-error text-danger ms-2" style="display: none;"></span>
                </div>
            </div>
        </div>
        <div class="col">
            <label class="form-label">Upload necessary documents</label>
            <div class="upload-group">
                <div class="error-message drop-area">
                    <label for="license_upload" class="custom-file-upload">
                        <span id="license-label">License <i class="las la-plus-circle"></i></span>
                        <input type="file" name="licence_doc_url" id="license_upload"
                            onchange="updateFileNameSwap(this, 'license-label')" class="static-crop"
                            data-param="licence_doc_url" data-folder="licence_photo" data-prefix="licence_doc_" />
                    </label>
                    <input id="licenseimage" type="hidden" />
                    <div class="mb-3 d-none" id="license_upload_wrapper"
                        style="display: flex;justify-content: flex-start;align-items: center;">
                        <div class="spinner-border" style="margin-right: 10px;" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <label id="lbUploadedImge" for="">Uploading Student Photo...</label>
                    </div>
                    <img id="licenseImage" class="rounded img-fluid" src="" style="display: none;" />
                    @if ($admissionDetail && $admissionDetail->licence_doc_url != '')
                        <div class="doc-download-box">
                            <a
                                href="{{ route('student.images.download', ['id' => $admissionDetail->id, 'fieldName' => 'licence_doc_url']) }}"><span>Licence</span>
                                <img src="{{ asset('assets/images/download-icon.svg') }}">
                            </a>
                            <img src="{{ asset($admissionDetail->licence_doc_url) }}" alt="" id="licenceImg"
                                class="uploaded-img">
                        </div>
                    @endif
                </div>
                <div class="error-message drop-area">
                    <label for="insurance_upload" class="custom-file-upload">
                        <span id="insurance-label">Insurance <i class="las la-plus-circle"></i></span>
                        <input type="file" name="insurance_doc_url" id="insurance_upload"
                            onchange="updateFileNameSwap(this, 'insurance-label')" class="static-crop"
                            data-param="insurance_doc_url" data-folder="insurance_photo"
                            data-prefix="insurance_doc_" />
                    </label>
                    <input id="insuranceimage" type="hidden" />
                    <div class="mb-3 d-none" id="insurance_upload_wrapper"
                        style="display: flex;justify-content: flex-start;align-items: center;">
                        <div class="spinner-border" style="margin-right: 10px;" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <label id="lbUploadedImge" for="">Uploading Student Photo...</label>
                    </div>
                    <img id="insuranceImage" class="rounded img-fluid" src="" style="display: none;" />
                    @if ($admissionDetail && $admissionDetail->insurance_doc_url != '')
                        <div class="doc-download-box">
                            <a
                                href="{{ route('student.images.download', ['id' => $admissionDetail->id, 'fieldName' => 'insurance_doc_url']) }}"><span>Insurance</span>
                                <img src="{{ asset('assets/images/download-icon.svg') }}">
                            </a>
                            <img src="{{ asset($admissionDetail->insurance_doc_url) }}" alt=""
                                id="insuranceImg" class="uploaded-img">
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
                <div class="error-message drop-area">
                    <label for="rc_front_upload" class="custom-file-upload">
                        <span id="rc-front-label">RC Book Front <i class="las la-plus-circle"></i>
                        </span>
                        <input type="file" name="rcbook_front_doc_url" id="rc_front_upload"
                            onchange="updateFileNameSwap(this, 'rc-front-label')" class="static-crop"
                            data-param="rcbook_front_doc_url" data-folder="rcbook_front_photo"
                            data-prefix="rcbook_front_doc_" />
                    </label>
                    <input id="rc_frontimage" type="hidden" />
                    <div class="mb-3 d-none" id="rc_front_upload_wrapper"
                        style="display: flex;justify-content: flex-start;align-items: center;">
                        <div class="spinner-border" style="margin-right: 10px;" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <label id="lbUploadedImge" for="">Uploading Student Photo...</label>
                    </div>
                    <img id="rc_frontImage" class="rounded img-fluid" src="" style="display: none;" />
                    @if ($admissionDetail && $admissionDetail->rcbook_front_doc_url != '')
                        <div class="doc-download-box">
                            <a
                                href="{{ route('student.images.download', ['id' => $admissionDetail->id, 'fieldName' => 'rcbook_front_doc_url']) }}"><span>RC
                                    Book Front</span>
                                <img src="{{ asset('assets/images/download-icon.svg') }}">
                            </a>
                            <img src="{{ asset($admissionDetail->rcbook_front_doc_url) }}" alt=""
                                id="rcBookFrontImg" class="uploaded-img">
                        </div>
                    @endif
                </div>

                <div class="error-message drop-area">
                    <label for="rc_back_upload" class="custom-file-upload">
                        <span id="rc-back-label">RC Book Back <i class="las la-plus-circle"></i>
                        </span>
                        <input type="file" name="rcbook_back_doc_url" id="rc_back_upload"
                            onchange="updateFileNameSwap(this, 'rc-back-label')" class="static-crop"
                            data-param="rcbook_back_doc_url" data-folder="rcbook_back_photo"
                            data-prefix="rcbook_back_doc_" />
                    </label>
                    <input id="rc_backimage" type="hidden" />
                    <div class="mb-3 d-none" id="rc_back_upload_wrapper"
                        style="display: flex;justify-content: flex-start;align-items: center;">
                        <div class="spinner-border" style="margin-right: 10px;" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <label id="lbUploadedImge" for="">Uploading Student Photo...</label>
                    </div>
                    <img id="rc_backImage" class="rounded img-fluid" src="" style="display: none;" />
                    @if ($admissionDetail && $admissionDetail->rcbook_back_doc_url != '')
                        <div class="doc-download-box">
                            <a
                                href="{{ route('student.images.download', ['id' => $admissionDetail->id, 'fieldName' => 'rcbook_back_doc_url']) }}"><span>RC
                                    Book Back</span>
                                <img src="{{ asset('assets/images/download-icon.svg') }}">
                            </a>
                            <img src="{{ asset($admissionDetail->rcbook_back_doc_url) }}" alt=""
                                id="rcBookBackImg" class="uploaded-img">
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