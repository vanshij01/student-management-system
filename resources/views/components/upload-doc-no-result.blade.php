@props(['label', 'docType', 'percentageFieldName', 'admissionDetail', 'admissionDocuments', 'formType'])

@if ($formType == 'create')
    <div class="col-6 {{ $label }}-doc mb-4">
        <label class="form-label" for="{{ $docType }}_upload">Upload your {{ $docType }}</label>
        <div class="upload-group">
            <div class="error-message drop-area">
                <label for="{{ $label }}_upload" class="custom-file-upload w-100">
                    <span id="{{ $label }}-result-label"
                        data-default="{{ $docType }} Result">{{ $docType }}
                        Result <i class="las la-plus-circle"></i></span>
                    <input type="file" name="{{ $label }}" id="{{ $label }}_upload"
                        onchange="updateFileNameSwap(this, '{{ $label }}-result-label')" class="static-crop"
                        data-param="{{ $label }}_url" data-folder="{{ $docType }}"
                        data-prefix="{{ $label }}_" />
                </label>
                <input id="{{ $label }}image" type="hidden" />
                <div class="mb-3 d-none" id="{{ $label }}_upload_wrapper"
                    style="display: flex;justify-content: flex-start;align-items: center;">
                    <div class="spinner-border" style="margin-right: 10px;" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <label id="lbUploadedImge" for="">Uploading Student Photo...</label>
                </div>
                <img id="{{ $label }}Image" class="rounded img-fluid" src="" style="display: none;" />
                @if ($admissionDetail && $admissionDocuments)
                    @php
                        $document = $admissionDocuments->firstWhere('doc_type', $docType);
                    @endphp
                    @if ($document)
                        <div class="doc-download-box">
                            @if (auth()->user()->role_id != 4)
                                <a href="{{ route('admission.document.download', $document->id) }}"><span>{{ $docType }}</span>
                                    <img src="{{ asset('assets/images/download-icon.svg') }}">
                                @else
                                    <a href="{{ route('student.document.download', $document->id) }}"><span>{{ $docType }}</span>
                                        <img src="{{ asset('assets/images/download-icon.svg') }}">
                            @endif
                            </a>
                            <img src="{{ asset($document->doc_url) }}" alt="" id="{{ $label }}Img"
                                class="uploaded-img">
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
@else
    <div class="col-6 {{ $label }}-doc mb-4">
        <label class="form-label" for="{{ $docType }}_upload">Upload your {{ $docType }}</label>
        <div class="upload-group">
            <div class="error-message drop-area">
                <label for="{{ $label }}_upload" class="custom-file-upload w-100">
                    <span id="{{ $label }}-result-label"
                        data-default="{{ $docType }} Result">{{ $docType }}
                        Result <i class="las la-plus-circle"></i></span>
                    <input type="file" name="{{ $label }}" id="{{ $label }}_upload"
                        onchange="updateFileNameSwap(this, '{{ $label }}-result-label')" class="static-crop"
                        data-param="{{ $label }}_url" data-folder="{{ $docType }}"
                        data-prefix="{{ $label }}_" />
                </label>
                <input id="{{ $label }}image" type="hidden" />
                <div class="mb-3 d-none" id="{{ $label }}_upload_wrapper"
                    style="display: flex;justify-content: flex-start;align-items: center;">
                    <div class="spinner-border" style="margin-right: 10px;" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <label id="lbUploadedImge" for="">Uploading Student Photo...</label>
                </div>
                <img id="{{ $label }}Image" class="rounded img-fluid" src="" style="display: none;" />
                @if ($admissionDetail && $admissionDocuments)
                    @php
                        $document = $admissionDocuments->firstWhere('doc_type', $docType);
                    @endphp
                    @if ($document)
                        <div class="doc-download-box">
                            @if (auth()->user()->role_id != 4)
                                <a href="{{ route('admission.document.download', $document->id) }}"><span>{{ $docType }}</span>
                                    <img src="{{ asset('assets/images/download-icon.svg') }}">
                                </a>
                            @else
                                <a href="{{ route('student.document.download', $document->id) }}"><span>{{ $docType }}</span>
                                    <img src="{{ asset('assets/images/download-icon.svg') }}">
                                </a>
                            @endif
                            <img src="{{ asset($document->doc_url) }}" alt="" id="{{ $label }}Img"
                                class="uploaded-img">
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
@endif
