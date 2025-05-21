<div id="education-details" class="content form-section">
    <div class="row mb-3">
        <div class="col">
            <label for="education_type" class="form-label">Education</label>
            <select class="select2 form-select" id="edit_education_type" disabled>
                <option value="">Select Education</option>
                <option value="HSC" @if ($admissionDetail->education_type == 'HSC') selected @endif>HSC
                </option>
                <option value="Diploma" @if ($admissionDetail->education_type == 'Diploma') selected @endif>Diploma
                </option>
                <option value="Bachelor's Degree" @if ($admissionDetail->education_type == "Bachelor's Degree") selected @endif>
                    Bachelor's Degree</option>
                <option value="Master's Degree" @if ($admissionDetail->education_type == "Master's Degree") selected @endif>
                    Master's Degree</option>
                <option value="Professional Degree" @if ($admissionDetail->education_type == 'Professional Degree') selected @endif>Professional
                    Degree</option>
                <option value="Internship" @if ($admissionDetail->education_type == 'Internship') selected @endif>
                    Internship</option>
                <option value="Job" @if ($admissionDetail->education_type == 'Job') selected @endif>Job
                </option>
                <option value="Other" @if ($admissionDetail->education_type == 'Other') selected @endif>Other
                </option>
            </select>
            <input type="hidden" name="education_type" id="education_type"
                value="{{ $admissionDetail->education_type }}">
        </div>
        <div class="col">
            <input type="hidden" name="course_id" id="course_id" value="{{ $admissionDetail->course_id ?? 0 }}">
            <label for="course_name" class="form-label">Course</label>
            <div class="error-message">
                <select id="edit_course_id" class="select2 form-select" data-placeholder="Select course" required
                    disabled>
                    <option value="">Select Course</option>
                </select>
            </div>
        </div>
        <div class="col">
            <label for="institute_name" class="form-label">Institute Name</label>
            <input type="text" class="form-control" name="institute_name"
                value="{{ $admissionDetail->institute_name ?? '' }}" placeholder="Enter Institute name"
                id="institute_name" />
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label for="admission_date" class="form-label">Admission Date</label>
            <input type="text" class="form-control" name="addmission_date"
                value="{{ $admissionDetail->addmission_date ? date('d/m/Y', strtotime($admissionDetail->addmission_date)) : '' }}"
                placeholder="DD/MM/YYYY" id="addmission_date" />
        </div>
        <div class="col">
            <label for="admission_year" class="form-label">Admission Year</label>
            <select name="year_of_addmission" id="year_of_addmission" class="select2 form-select">
                <option value="">Select Admission Year</option>
                @foreach ($addmission_years as $addmission_year)
                    <option value="{{ $addmission_year }}" @if (date('Y') . '-' . date('Y', strtotime(' +1 year')) == $addmission_year) selected @endif>
                        {{ $addmission_year }}
                    </option>
                @endforeach
            </select>
        </div>
        <input type="hidden" id="last_semester" value="{{ $admissionDetail ? $admissionDetail->semester : 0 }}">
        <input type="hidden" id="last_course" value="{{ $admissionDetail ? $admissionDetail->course_id : 0 }}">
        <div class="col semester-div">
            <label for="semester_name" class="form-label">Semester</label>
            <select name="semester" id="semester" class="select2 form-select" data-placeholder="Select semester">
                <option value="">Select Semester</option>
            </select>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label for="institute_start_time" class="form-label">Institute Start Time</label>
            <input type="time" class="form-control" name="college_start_time"
                value="{{ old('college_start_time', $admissionDetail->college_start_time) }}" placeholder="H:i"
                id="college_start_time" />
        </div>
        <div class="col">
            <label for="institute_end_time" class="form-label">Institute End Time</label>
            <input type="time" class="form-control" name="college_end_time"
                value="{{ old('college_end_time', $admissionDetail->college_end_time) }}" placeholder="H:i"
                id="college_end_time" />
        </div>
        <div class="col">
            <label for="arriving_date" class="form-label">Arriving Date at Hostel</label>
            <input type="text" class="form-control" name="arriving_date"
                value="{{ $admissionDetail->arriving_date ? date('d/m/Y', strtotime($admissionDetail->arriving_date)) : '' }}"
                placeholder="DD/MM/YYYY" id="arriving_date" />
        </div>
    </div>

    <hr style="background-color:#1D1D1B33; margin-top: 30px; margin-bottom: 30px; ">

    <div class="row mb-3 fees-section">
        <h3 class="form-sm-title">Fees Details</h3>
        <div class="col">
            <label for="fees_receipt_date" class="form-label">Fees Receipt Date</label>
            <input type="text" class="form-control" name="college_fees_receipt_date" {{-- value="{{ $admissionDetail->college_fees_receipt_date ? date('d/m/Y', strtotime($admissionDetail->college_fees_receipt_date)): '' }}" placeholder="DD/MM/YYYY" --}}
                id="college_fees_receipt_date" />
        </div>
        <div class="col">
            <label class="form-label">Upload your current Fee Receipt</label>
            <div class="upload-group">
                <div class="error-message drop-area">
                    <label for="fee_receipt_upload" class="custom-file-upload">
                        <span id="fee-receipt-label">Current Fee Receipt <i class="las la-plus-circle"></i>
                        </span>
                        {{-- <input type="file" class="form-control static-crop" name="fee_receipt"
                            id="fee_receipt_upload" onchange="updateFileNameSwap(this, 'fee-receipt-label')" /> --}}
                        <input type="file" name="fee_receipt" id="fee_receipt_upload"
                            onchange="updateFileNameSwap(this, 'fee-receipt-label')" class="static-crop"
                            data-param="fee_receipt_url" data-folder="Fee Receipt" data-prefix="fee_receipt_" />
                    </label>
                    <input id="fee_receiptimage" type="hidden" />
                    <div class="mb-3 d-none" id="fee_receipt_upload_wrapper"
                        style="display: flex;justify-content: flex-start;align-items: center;">
                        <div class="spinner-border" style="margin-right: 10px;" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <label id="lbUploadedImge" for="">Uploading Student Photo...</label>
                    </div>
                    <img id="fee_receiptImage" class="rounded img-fluid" src="" style="display: none;" />
                </div>
            </div>
        </div>
        {{-- <div class="semester-fees" data-course-id="{{ $document->course_id ?? 0 }}">
            @if ($documents)
                <div class="col-12">
                    <div class="Fees-download-wrap d-flex ">
                        @foreach ($documents as $document)
                            @if ($admissionDetail->course_id == $document->course_id)
                                @if (Str::startsWith($document->doc_type, 'Semester') && Str::endsWith($document->doc_type, 'Fees Receipt'))
                                    <div class="fees-download-box">
                                        <a href="{{ route('student.document.download', $document->id) }}">
                                            <span>{{ Str::words($document->doc_type, 2, '') }}</span>
                                            <img src="{{ asset('assets/images/download-icon.svg') }}">
                                        </a>
                                    </div>
                                @endif
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        </div> --}}
    </div>
    <hr style="background-color:#1D1D1B33; margin-top: 30px; margin-bottom: 30px; ">
    <div class="row mb-3">
        <h3 class="form-sm-title">Required Documents</h3>
        <div class="row mb-3">
            <div class="col-md-6 col-sm-12">
                <label for="board_type" class="form-label">Type of Board</label>
                <select class="select2 form-select" id="board_type" name="board_type">
                    <option value="">Select Type of Board</option>
                    <option value="GSEB" @if ($admissionDetail->board_type == 'GSEB') selected @endif>
                        GSEB
                    </option>
                    <option value="CBSE" @if ($admissionDetail->board_type == 'CBSE') selected @endif>
                        CBSE
                    </option>
                    <option value="CAIE" @if ($admissionDetail->board_type == 'CAIE') selected @endif>
                        CAIE
                    </option>
                    <option value="Other" @if ($admissionDetail->board_type == 'Other') selected @endif>
                        Other</option>
                </select>
            </div>
            <div class="col-md-6 col-sm-12 board_name_field">
                <label for="board_name" class="form-label">Mention your Board</label>
                <input type="text" class="form-control" name="board_name"
                    value="{{ $admissionDetail->board_name ?? '' }}" placeholder="Enter your Board"
                    id="board_name" />
            </div>
        </div>

        <x-upload-doc-no-result label="job_letter" docType="Job Offer Letter" :admissionDetail="$admissionDetail" :admissionDocuments="$documents"
            formType="update" />
        <x-upload-doc-no-result label="internship_letter" docType="Internship Offer Letter" :admissionDetail="$admissionDetail"
            :admissionDocuments="$documents" formType="update" />
        <x-upload-doc-no-result label="degree_certificate" docType="Degree Certificate" :admissionDetail="$admissionDetail"
            :admissionDocuments="$documents" formType="update" />
        <x-upload-doc label="last_qualification" docType="Qualification Result" percentageFieldName="hsc_percentage"
            :admissionDetail="$admissionDetail" :admissionDocuments="$documents" formType="update" />
        <x-upload-doc-no-result label="leaving_certificate" docType="Leaving Certificate" :admissionDetail="$admissionDetail"
            :admissionDocuments="$documents" formType="update" />
        <x-upload-doc label="ssc" docType="SSC" percentageFieldName="ssc_percentage" :admissionDetail="$admissionDetail"
            :admissionDocuments="$documents" formType="update" />
        <x-upload-doc label="hsc" docType="HSC" percentageFieldName="hsc_percentage" :admissionDetail="$admissionDetail"
            :admissionDocuments="$documents" formType="update" />

    </div>
    <hr style="background-color:#1D1D1B33; margin-top: 30px; margin-bottom: 30px; ">
    <div class="row mb-3 backlog-option">
        <div class="col d-flex align-items-center radio-field-wrap">
            <label class="form-label mb-0">Having any Backlog ?</label>
            <div class="d-flex gap-3">
                <div class="form-check m-0">
                    <input class="form-check-input" type="radio" name="having_any_backlog" id="backlog_yes"
                        value="true">
                    <label class="form-check-label" for="backlog_yes">Yes</label>
                </div>
                <div class="form-check m-0">
                    <input class="form-check-input" type="radio" name="having_any_backlog" id="backlog_no"
                        value="false" checked>
                    <label class="form-check-label" for="backlog_no">No</label>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3 degree-results">
        <h3 class="form-sm-title">Results</h3>
        <x-upload-sem-result label="1" :admissionDetail="$admissionDetail" :admissionDocuments="$documents" formType="update" />
        <x-upload-sem-result label="2" :admissionDetail="$admissionDetail" :admissionDocuments="$documents" formType="update" />
        <x-upload-sem-result label="3" :admissionDetail="$admissionDetail" :admissionDocuments="$documents" formType="update" />
        <x-upload-sem-result label="4" :admissionDetail="$admissionDetail" :admissionDocuments="$documents" formType="update" />
        <x-upload-sem-result label="5" :admissionDetail="$admissionDetail" :admissionDocuments="$documents" formType="update" />
        <x-upload-sem-result label="6" :admissionDetail="$admissionDetail" :admissionDocuments="$documents" formType="update" />
        <x-upload-sem-result label="7" :admissionDetail="$admissionDetail" :admissionDocuments="$documents" formType="update" />
        <x-upload-sem-result label="8" :admissionDetail="$admissionDetail" :admissionDocuments="$documents" formType="update" />
        <x-upload-sem-result label="9" :admissionDetail="$admissionDetail" :admissionDocuments="$documents" formType="update" />
        <x-upload-sem-result label="10" :admissionDetail="$admissionDetail" :admissionDocuments="$documents" formType="update" />
    </div>

    <div class="row mb-3 degree-backlog_results">
        <h3 class="form-sm-title">Backlog Results</h3>
        <x-upload-sem-backlog-result label="1" :admissionDetail="$admissionDetail" :admissionDocuments="$documents" formType="update" />
        <x-upload-sem-backlog-result label="2" :admissionDetail="$admissionDetail" :admissionDocuments="$documents" formType="update" />
        <x-upload-sem-backlog-result label="3" :admissionDetail="$admissionDetail" :admissionDocuments="$documents" formType="update" />
        <x-upload-sem-backlog-result label="4" :admissionDetail="$admissionDetail" :admissionDocuments="$documents" formType="update" />
        <x-upload-sem-backlog-result label="5" :admissionDetail="$admissionDetail" :admissionDocuments="$documents" formType="update" />
        <x-upload-sem-backlog-result label="6" :admissionDetail="$admissionDetail" :admissionDocuments="$documents" formType="update" />
        <x-upload-sem-backlog-result label="7" :admissionDetail="$admissionDetail" :admissionDocuments="$documents" formType="update" />
        <x-upload-sem-backlog-result label="8" :admissionDetail="$admissionDetail" :admissionDocuments="$documents" formType="update" />
        <x-upload-sem-backlog-result label="9" :admissionDetail="$admissionDetail" :admissionDocuments="$documents" formType="update" />
        <x-upload-sem-backlog-result label="10" :admissionDetail="$admissionDetail" :admissionDocuments="$documents" formType="update" />
    </div>

    <div class="row mb-3 otherDocs">
        <h3 class="form-sm-title">Other Documents</h3>
        <x-upload-other-result :oldAdmissionDetails="$admissionDetail" :oldAdmissionDocuments="$documents" :admissionDetail="$admissionDetail" mode="update"/>
    </div>

    <div id="degreeResultsSection" data-old-course-id="{{ $admissionDetail ? $admissionDetail->course_id : '' }}">
        <div class="row mb-3 degree-backlog_results">
            @if ($documents)
                <h3 class="form-sm-title">{{ $admissionDetail->course->course_name ?? '' }}
                    Backlog Results</h3>
                <div class="col-12">
                    <div class="Fees-download-wrap d-flex flex-wrap gap-3">
                        @foreach ($documents as $document)
                            @if (Str::contains($document->doc_type, '(Backlog)'))
                                <div class="semester-download-box" data-course-id="{{ $document->course_id }}"
                                    style="background: #eaf9fa; border-radius: 10px; min-width: 260px; max-height: 46px; padding: 12px 24px; display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
                                    <span style="color: #18b6c1; font-weight: 600; font-size: 16px;">
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
            @if ($documents)
                <h3 class="form-sm-title">{{ $admissionDetail->course->course_name ?? '' }}
                    Results</h3>
                <div style="display: flex; flex-wrap: wrap; gap: 16px;">
                    @foreach ($documents as $document)
                        @if (Str::contains($document->doc_type, 'Semester') &&
                                !Str::contains($document->doc_type, 'Fees Receipt') &&
                                !Str::contains($document->doc_type, '(Backlog)'))
                            <div class="semester-download-box" data-course-id="{{ $document->course_id }}"
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

    <div class="row mb-3 ca-results">
        <x-upload-doc label="ipcc" docType="IPCC" :admissionDetail="$admissionDetail" :admissionDocuments="$documents" formType="update" />
        <x-upload-doc label="cpt" docType="CPT" :admissionDetail="$admissionDetail" :admissionDocuments="$documents" formType="update" />
        <x-upload-doc label="ca_final" docType="CA Final" :admissionDetail="$admissionDetail" :admissionDocuments="$documents" formType="update" />
    </div>

    <div class="row mb-3 ca-backlog-results">
        <x-upload-doc label="ipcc_backlog" docType="IPCC Backlog" :admissionDetail="$admissionDetail" :admissionDocuments="$documents"
            formType="update" />
        <x-upload-doc label="cpt_backlog" docType="CPT Backlog" :admissionDetail="$admissionDetail" :admissionDocuments="$documents"
            formType="update" />
        <x-upload-doc label="ca_final_backlog" docType="CA Final Backlog" :admissionDetail="$admissionDetail" :admissionDocuments="$documents"
            formType="update" />
    </div>
    <div class="d-flex step-btn-wrapper justify-content-between">
        <button type="button" class="btn btn-prev">Previous</button>
        <button type="button" class="btn btn-reset"><i class="las la-redo-alt"></i> Reset
            Form</button>
        <button type="button" class="btn btn-next">Next</button>
    </div>
</div>
