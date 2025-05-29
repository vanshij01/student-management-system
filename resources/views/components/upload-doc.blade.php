@props(['label', 'docType', 'admissionDetail', 'admissionDocuments', 'formType'])
@if ($formType == 'create')
    <div class="col-6 {{ $label }}-doc">
        <label class="form-label" for="{{ $docType }}_upload">Upload your {{ $docType }}</label>
        <div class="row g-2 justify-content-between">
            <!-- Percentage Field -->
            <div class="col-12 col-md-4 percentage_box">
                <div class="d-flex">
                    <label class="form-label mb-0 me-2 align-self-center"
                        for="{{ $label }}_percentage">Percentage:</label>
                    <div class="percentage-error">
                        @if ($admissionDetail && $admissionDocuments)
                            @php
                                $document = $admissionDocuments
                                    ->where('doc_type', $docType)
                                    ->where('course_id', $admissionDetail->course_id)
                                    ->first();
                            @endphp
                            @if ($document)
                                <input type="text" style="width: 100px" class="form-control"
                                    id="{{ $label }}_percentage" name="{{ $label }}_percentage"
                                    value="{{ $document->percentile }}">
                            @else
                                <input type="text" style="width: 100px" class="form-control"
                                    id="{{ $label }}_percentage" name="{{ $label }}_percentage"
                                    value="{{ data_get($admissionDetail, $label . '_percentage', '') }}">
                            @endif
                        @else
                            {{ $admissionDetail && $admissionDocuments }}
                            <input type="text" style="width: 100px" class="form-control"
                                id="{{ $label }}_percentage" name="{{ $label }}_percentage"
                                value="{{ data_get($admissionDetail, $label . '_percentage', '') }}">
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-8 upload_document">
                <div class="upload-group">
                    <div class="error-message drop-area">
                        <label for="{{ $label }}_result_upload" class="custom-file-upload w-100">
                            <span id="{{ $label }}-result-label"
                                data-default="{{ $docType }} Result">{{ $docType }}
                                Result <i class="las la-plus-circle"></i></span>
                            {{-- <input type="file" class="form-control static-crop" name="{{ $label }}_result"
                            id="{{ $label }}_result_upload"
                            onchange="updateFileNameSwap(this, '{{ $label }}-result-label')" /> --}}


                            <input type="file" name="{{ $label }}_result"
                                id="{{ $label }}_result_upload"
                                onchange="updateFileNameSwap(this, '{{ $label }}-result-label')"
                                class="static-crop" data-param="{{ $label }}_result_url"
                                data-folder="{{ $docType }}" data-prefix="{{ $label }}_result_" />
                        </label>
                        <input id="{{ $label }}_resultimage" type="hidden" />
                        <div class="mb-3 d-none" id="{{ $label }}_result_upload_wrapper"
                            style="display: flex;justify-content: flex-start;align-items: center;">
                            <div class="spinner-border" style="margin-right: 10px;" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <label id="lbUploadedImge" for="">Uploading Student Photo...</label>
                        </div>
                        <img id="{{ $label }}_resultImage" class="rounded img-fluid" src=""
                            style="display: none;" />
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
                                    @endif
                                    </a>
                                    <img src="{{ asset($document->doc_url) }}" alt=""
                                        id="{{ $label }}Img" class="uploaded-img">
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="col-6 {{ $label }}-doc">
        <label class="form-label" for="{{ $docType }}_upload">Upload your {{ $docType }}</label>
        <div class="row g-2 justify-content-between">
            <!-- Percentage Field -->
            <div class="col-12 col-md-4 percentage_box">
                <div class="d-flex">
                    <label class="form-label mb-0 me-2 align-self-center"
                        for="{{ $label }}_percentage">Percentage:</label>
                    <div class="percentage-error">
                        @if ($admissionDetail && $admissionDocuments)
                            @php
                                $document = $admissionDocuments
                                    ->where('doc_type', $docType)
                                    ->where('course_id', $admissionDetail->course_id)
                                    ->first();
                            @endphp

                            @if ($document)
                                <input type="text" style="width: 100px" class="form-control"
                                    id="{{ $label }}_percentage" name="{{ $label }}_percentage"
                                    value="{{ $document->percentile }}">
                            @else
                                <input type="text" style="width: 100px" class="form-control"
                                    id="{{ $label }}_percentage" name="{{ $label }}_percentage"
                                    value="{{ data_get($admissionDetail, $label . '_percentage', '') }}">
                            @endif
                        @else
                            {{ $admissionDetail && $admissionDocuments }}
                            <input type="text" style="width: 100px" class="form-control"
                                id="{{ $label }}_percentage" name="{{ $label }}_percentage"
                                value="{{ data_get($admissionDetail, $label . '_percentage', '') }}">
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-8 upload_document">
                <div class="upload-group">
                    <div class="error-message drop-area">
                        <label for="{{ $label }}_result_upload" class="custom-file-upload w-100">
                            <span id="{{ $label }}-result-label"
                                data-default="{{ $docType }} Result">{{ $docType }}
                                Result <i class="las la-plus-circle"></i></span>
                            {{-- <input type="file" class="form-control static-crop" name="{{ $label }}_result"
                            id="{{ $label }}_result_upload"
                            onchange="updateFileNameSwap(this, '{{ $label }}-result-label')" /> --}}


                            <input type="file" name="{{ $label }}_result"
                                id="{{ $label }}_result_upload"
                                onchange="updateFileNameSwap(this, '{{ $label }}-result-label')"
                                class="static-crop" data-param="{{ $label }}_result_url"
                                data-folder="{{ $docType }}" data-prefix="{{ $label }}_result_" />
                        </label>
                        <input id="{{ $label }}_resultimage" type="hidden" />
                        <div class="mb-3 d-none" id="{{ $label }}_result_upload_wrapper"
                            style="display: flex;justify-content: flex-start;align-items: center;">
                            <div class="spinner-border" style="margin-right: 10px;" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <label id="lbUploadedImge" for="">Uploading Student Photo...</label>
                        </div>
                        <img id="{{ $label }}_resultImage" class="rounded img-fluid" src=""
                            style="display: none;" />
                        @if ($admissionDetail && $admissionDocuments)
                            @php
                                $document = $admissionDocuments
                                    ->where('doc_type', $docType)
                                    ->where('course_id', $admissionDetail->course_id)
                                    ->first();
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
                                    <img src="{{ asset($document->doc_url) }}" alt=""
                                        id="{{ $label }}Img" class="uploaded-img">
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
