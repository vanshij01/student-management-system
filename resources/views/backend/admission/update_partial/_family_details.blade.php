<div id="family-details" class="content form-section">
    <div class="row mb-3">
        <div class="col">
            <label for="father_name" class="form-label">Father’s Name</label>
            <input type="text" class="form-control" name="father_full_name"
                value="{{ $admissionDetail->father_full_name ?? '' }}" id="father_full_name"
                placeholder="Enter father full name" />
        </div>
        <div class="col">
            <label for="father_contact_number" class="form-label">Father’s Contact Number</label>
            <input type="number" class="form-control" name="father_phone"
                value="{{ $admissionDetail->father_phone ?? '' }}" id="father_phone"
                placeholder="Enter father contact number" />
        </div>
        <div class="col">
            <label for="father_occupation" class="form-label">Father’s Occupation</label>
            <input type="text" class="form-control" name="father_occupation"
                value="{{ $admissionDetail->father_occupation ?? '' }}" id="father_occupation"
                placeholder="Enter father occupation" />
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label for="mother_name" class="form-label">Mother’s Name</label>
            <input type="text" class="form-control" name="mother_full_name"
                value="{{ $admissionDetail->mother_full_name ?? '' }}" id="mother_full_name"
                placeholder="Enter mother full name" />
        </div>
        <div class="col">
            <label for="mother_contact_number" class="form-label">Mother’s Contact Number</label>
            <input type="number" class="form-control" name="mother_phone"
                value="{{ $admissionDetail->mother_phone ?? '' }}" id="mother_phone"
                placeholder="Enter mother contact number" />
        </div>
        <div class="col">
            <label for="mother_occupation" class="form-label">Mother’s Occupation</label>
            <input type="text" class="form-control" name="mother_occupation"
                value="{{ $admissionDetail->mother_occupation ?? '' }}" id="mother_occupation"
                placeholder="Enter mother occupation" />
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-4">
            <label for="aadhar_number" class="form-label">Annual Income</label>
            <input type="number" class="form-control" name="annual_income" min="1"
                value="{{ $admissionDetail->annual_income ?? '' }}" id="annual_income"
                placeholder="Enter annual income" />
        </div>
        <div class="col-8">
            <label class="form-label">Upload Parent’s Passport Size Photo</label>
            <div class="upload-group">
                <div class="error-message drop-area">
                    <label for="father_photo_upload" class="custom-file-upload">
                        <span id="father-photo-label">Father’s Passport Size Photo <i class="las la-plus-circle"></i>
                        </span>
                        <input type="file" data-param="father_photo_url" data-folder="father_photo"
                            data-prefix="father_photo_" name="father_photo" id="father_photo_upload"
                            onchange="updateFileNameSwap(this, 'father-photo-label')" class="static-crop" />
                    </label>
                    <input id="father_photoimage" value="" type="hidden" />
                    <div class="mb-3 d-none" id="father_photo_upload_wrapper"
                        style="display: flex;justify-content: flex-start;align-items: center;">
                        <div class="spinner-border" style="margin-right: 10px;" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <label id="lbUploadedImge" for="">Uploading Student Photo...</label>
                    </div>
                    <img id="father_photoImage" class="rounded img-fluid" src="" style="display: none;" />
                    @if ($admissionDetail->father_photo_url != '')
                        <div class="doc-download-box">
                            <a
                                href="{{ route('student.images.download', ['id' => $admissionDetail->id, 'fieldName' => 'father_photo_url']) }}"><span>Father’s
                                    Passport Size Photo</span>
                                <img src="{{ asset('assets/images/download-icon.svg') }}">
                            </a>
                            <img src="{{ asset($admissionDetail->father_photo_url) }}" alt="" id="fatherImg"
                                class="uploaded-img">
                        </div>
                    @endif
                </div>
                <div class="error-message drop-area">
                    <label for="mother_photo_upload" class="custom-file-upload">
                        <span id="mother-photo-label">Mother’s Passport Size Photo <i class="las la-plus-circle"></i>
                        </span>
                        <input type="file" data-param="mother_photo_url" data-folder="mother_photo"
                            data-prefix="mother_photo_" name="mother_photo" id="mother_photo_upload"
                            onchange="updateFileNameSwap(this, 'mother-photo-label')" class="static-crop" />
                    </label>
                    <input id="mother_photoimage" value="" type="hidden" />
                    <div class="mb-3 d-none" id="mother_photo_upload_wrapper"
                        style="display: flex;justify-content: flex-start;align-items: center;">
                        <div class="spinner-border" style="margin-right: 10px;" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <label id="lbUploadedImge" for="">Uploading Student Photo...</label>
                    </div>
                    <img id="mother_photoImage" class="rounded img-fluid" src="" style="display: none;" />
                    @if ($admissionDetail->mother_photo_url != '')
                        <div class="doc-download-box">
                            <a
                                href="{{ route('student.images.download', ['id' => $admissionDetail->id, 'fieldName' => 'mother_photo_url']) }}"><span>Mother’s
                                    Passport Size Photo</span>
                                <img src="{{ asset('assets/images/download-icon.svg') }}">
                            </a>
                            <img src="{{ asset($admissionDetail->mother_photo_url) }}" alt="" id="motherImg"
                                class="uploaded-img">
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        {{$admissionDetail->local_guardian_yes}}
        <div class="col d-flex align-items-center radio-field-wrap">
            <label class="form-label mb-0">Do you have a Local Guardian in Ahmedabad ?</label>
            <div class="d-flex gap-3">
                <div class="form-check m-0">
                    <input class="form-check-input" type="radio" name="is_local_guardian_in_ahmedabad"
                        id="local_guardian_yes" value="true" @if ($admissionDetail->is_local_guardian_in_ahmedabad == true) checked @endif>
                    <label class="form-check-label" for="local_guardian_yes">YES</label>
                </div>
                <div class="form-check m-0">
                    <input class="form-check-input" type="radio" name="is_local_guardian_in_ahmedabad"
                        id="local_guardian_no" value="false" @if ($admissionDetail->is_local_guardian_in_ahmedabad == false) checked @endif>
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
                value="{{ $admissionDetail->guardian_name ?? '' }}" id="guardian_name"
                placeholder="Enter guardian name" />
        </div>
        <div class="col">
            <label for="local_guardian_relation" class="form-label">Guardian Relation
                (Ahmedabad
                only)</label>
            <input type="text" class="form-control" name="guardian_relation"
                value="{{ $admissionDetail->guardian_relation ?? '' }}" id="guardian_relation"
                placeholder="Enter guardian relation" />
        </div>
        <div class="col">
            <label for="local_guardian_contact" class="form-label">Guardian Contact Number
                (Ahmedabad
                only)</label>
            <input type="number" class="form-control" name="guardian_phone"
                value="{{ $admissionDetail->guardian_phone ?? '' }}" id="guardian_phone"
                placeholder="Enter guardian contact number" />
        </div>
    </div>
    <div class="row mb-3">
        <div class="col d-flex align-items-center radio-field-wrap">
            <label class="form-label mb-0">Is your Parent’s Nationality Indian? </label>
            <div class="d-flex gap-3">
                <div class="form-check m-0">
                    <input class="form-check-input" type="radio" name="is_parent_indian_citizen"
                        id="nationality_indian_yes" value="true" @if ($admissionDetail->is_parent_indian_citizen == true) checked @endif>
                    <label class="form-check-label" for="nationality_indian_yes">YES</label>
                </div>
                <div class="form-check m-0">
                    <input class="form-check-input" type="radio" name="is_parent_indian_citizen"
                        id="nationality_indian_no" value="false" @if ($admissionDetail->is_parent_indian_citizen == false) checked @endif>
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
                    <div class="error-message drop-area">
                        <label for="parents_aadhar_front_upload" class="custom-file-upload">
                            <span id="parents-aadhar-front-label">Aadhar Card Front <i
                                    class="las la-plus-circle"></i></span>
                            {{-- <input type="file" name="parents_aadhar_front" id="parents_aadhar_front_upload"
                                onchange="updateFileNameSwap(this, 'parents-aadhar-front-label')"
                                class="static-crop" /> --}}
                            <input type="file" data-param="parents_aadhar_front_url"
                                data-folder="Parent Aadhar Card Front" data-prefix="parents_aadhar_front_"
                                name="parents_aadhar_front" id="parents_aadhar_front_upload"
                                onchange="updateFileNameSwap(this, 'parents-aadhar-front-label')"
                                class="static-crop" />
                        </label>
                        <input id="parents_aadhar_frontimage" type="hidden" />
                        <div class="mb-3 d-none" id="parents_aadhar_front_upload_wrapper"
                            style="display: flex;justify-content: flex-start;align-items: center;">
                            <div class="spinner-border" style="margin-right: 10px;" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <label id="lbUploadedImge" for="">Uploading Student Photo...</label>
                        </div>
                        <img id="parents_aadhar_frontImage" class="rounded img-fluid" src=""
                            style="display: none;" />
                        @if ($documents)
                            @php
                                $document = $documents->firstWhere('doc_type', 'Parent Aadhar Card Front');
                            @endphp
                            @if ($document)
                                <div class="doc-download-box">
                                    <a href="{{ route('student.document.download', $document->id) }}"><span>Aadhar
                                            Card Front</span>
                                        <img src="{{ asset('assets/images/download-icon.svg') }}">
                                    </a>
                                    <img src="{{ asset($document->doc_url) }}" alt=""
                                        id="parentAadharFrontImg" class="uploaded-img">
                                </div>
                            @endif
                        @endif
                    </div>
                    <div class="error-message drop-area">
                        <label for="parents_aadhar_back_upload" class="custom-file-upload">
                            <span id="parents-aadhar-back-label">Aadhar Card Back <i
                                    class="las la-plus-circle"></i></span>
                            {{-- <input type="file" name="parents_aadhar_back" id="parents_aadhar_back_upload"
                                onchange="updateFileNameSwap(this, 'parents-aadhar-back-label')"
                                class="static-crop" /> --}}
                            <input type="file" data-param="parents_aadhar_back_url"
                                data-folder="Parent Aadhar Card Back" data-prefix="parents_aadhar_back_"
                                name="parents_aadhar_back" id="parents_aadhar_back_upload"
                                onchange="updateFileNameSwap(this, 'parents-aadhar-back-label')"
                                class="static-crop" />
                        </label>
                        <input id="parents_aadhar_backimage" type="hidden" />
                        <div class="mb-3 d-none" id="parents_aadhar_back_upload_wrapper"
                            style="display: flex;justify-content: flex-start;align-items: center;">
                            <div class="spinner-border" style="margin-right: 10px;" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <label id="lbUploadedImge" for="">Uploading Student Photo...</label>
                        </div>
                        <img id="parents_aadhar_backImage" class="rounded img-fluid" src=""
                            style="display: none;" />
                        @if ($documents)
                            @php
                                $document = $documents->firstWhere('doc_type', 'Parent Aadhar Card Back');
                            @endphp
                            @if ($document)
                                <div class="doc-download-box">
                                    <a href="{{ route('student.document.download', $document->id) }}"><span>Aadhar
                                            Card Back</span>
                                        <img src="{{ asset('assets/images/download-icon.svg') }}">
                                    </a>
                                    <img src="{{ asset($document->doc_url) }}" alt=""
                                        id="parentAadharBackImg" class="uploaded-img">
                                </div>
                            @endif
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
                <div class="error-message drop-area">
                    <label for="parents_passport_front_upload" class="custom-file-upload">
                        <span id="parents-passport-front-label">Passport Front <i
                                class="las la-plus-circle"></i></span>
                        {{-- <input type="file" name="parents_passport_front" id="parents_passport_front_upload"
                            onchange="updateFileNameSwap(this, 'parents-passport-front-label')"
                            class="static-crop" /> --}}

                        <input type="file" data-param="parents_passport_front_url"
                            data-folder="Parent Passport Front" data-prefix="parents_passport_front_"
                            name="parents_passport_front" id="parents_passport_front_upload"
                            onchange="updateFileNameSwap(this, 'parents-passport-front-label')" class="static-crop" />
                    </label>
                    <input id="parents_passport_frontimage" type="hidden" />
                    <div class="mb-3 d-none" id="parents_passport_front_upload_wrapper"
                        style="display: flex;justify-content: flex-start;align-items: center;">
                        <div class="spinner-border" style="margin-right: 10px;" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <label id="lbUploadedImge" for="">Uploading Student Photo...</label>
                    </div>
                    <img id="parents_passport_frontImage" class="rounded img-fluid" src=""
                        style="display: none;" />
                    @if ($documents)
                        @php
                            $document = $documents->firstWhere('doc_type', 'Parent Passport Front');
                        @endphp
                        @if ($document)
                            <div class="doc-download-box">
                                <a href="{{ route('student.document.download', $document->id) }}"><span>Passport
                                        Front</span>
                                    <img src="{{ asset('assets/images/download-icon.svg') }}">
                                </a>
                                <img src="{{ asset($document->doc_url) }}" alt=""
                                    id="parentPassportFrontImg" class="uploaded-img">
                            </div>
                        @endif
                    @endif
                </div>
                <div class="error-message drop-area">
                    <label for="parents_passport_back_upload" class="custom-file-upload">
                        <span id="parents-passport-back-label">Passport Card Back <i
                                class="las la-plus-circle"></i></span>
                        {{-- <input type="file" name="parents_passport_back" id="parents_passport_back_upload"
                            onchange="updateFileNameSwap(this, 'parents-passport-back-label')" class="static-crop" /> --}}

                        <input type="file" data-param="parents_passport_back_url"
                            data-folder="Parent Passport Back" data-prefix="parents_passport_back_"
                            name="parents_passport_back" id="parents_passport_back_upload"
                            onchange="updateFileNameSwap(this, 'parents-passport-back-label')" class="static-crop" />
                    </label>
                    <input id="parents_passport_backimage" type="hidden" />
                    <div class="mb-3 d-none" id="parents_passport_back_upload_wrapper"
                        style="display: flex;justify-content: flex-start;align-items: center;">
                        <div class="spinner-border" style="margin-right: 10px;" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <label id="lbUploadedImge" for="">Uploading Student Photo...</label>
                    </div>
                    <img id="parents_passport_backImage" class="rounded img-fluid" src=""
                        style="display: none;" />
                    @if ($documents)
                        @php
                            $document = $documents->firstWhere('doc_type', 'Parent Passport Back');
                        @endphp
                        @if ($document)
                            <div class="doc-download-box">
                                <a href="{{ route('student.document.download', $document->id) }}"><span>Passport
                                        Back</span>
                                    <img src="{{ asset('assets/images/download-icon.svg') }}">
                                </a>
                                <img src="{{ asset($document->doc_url) }}" alt="" id="parentPassportBackImg"
                                    class="uploaded-img">
                            </div>
                        @endif
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
