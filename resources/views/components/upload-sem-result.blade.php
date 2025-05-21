@props(['label', 'admissionDocuments', 'admissionDetail', 'admissionDetail', 'formType'])
@php
    $percentageField = 'sem' . $label . '_percentage';
@endphp
@if ($formType == 'create')
    <div class="col-md-6 semester-group" data-sem="{{ $label }}">
        <label class="form-label" for="semester_{{ $label }}_result_upload">Upload your
            Semester-{{ $label }}
            Result</label>
        <div class="row g-2 justify-content-between">
            <!-- Percentile Field -->
            <div class="col-12 col-md-4 percentage_box">
                <div class="d-flex">
                    <label class="form-label mb-0 me-2 align-self-center"
                        for="semester_{{ $label }}_percentage">Percentage:</label>
                    <div class="percentage-error">
                        @if ($admissionDetail && $admissionDocuments)
                            @php
                                $document = $admissionDocuments
                                    ->where('doc_type', 'Semester ' . $label)
                                    ->where('course_id', $admissionDetail->course_id)
                                    ->first();
                            @endphp
                            @if (
                                !empty($document) &&
                                    $document->doc_type == 'Semester ' . $label &&
                                    $document->course_id == $admissionDetail->course_id)
                                <input type="text" style="width: 100px" class="form-control"
                                    id="semester_{{ $label }}_percentage"
                                    name="semester_{{ $label }}_percentage" value="{{ $document->percentile }}">
                            @else
                                <input type="text" style="width: 100px" class="form-control"
                                    id="semester_{{ $label }}_percentage"
                                    name="semester_{{ $label }}_percentage"
                                    value="{{ data_get($admissionDetail, $label . '_percentage', '') }}">
                            @endif
                        @else
                            {{ $admissionDetail && $admissionDocuments }}
                            <input type="text" style="width: 100px" class="form-control"
                                id="semester_{{ $label }}_percentage"
                                name="semester_{{ $label }}_percentage"
                                value="{{ data_get($admissionDetail, $label . '_percentage', '') }}">
                        @endif
                    </div>
                </div>
            </div>

            <!-- Upload Field -->
            <div class="col-12 col-md-8 upload_document">
                <div class="upload-group">
                    <div class="error-message drop-area">
                        <label for="semester_{{ $label }}_result_upload" class="custom-file-upload">
                            <span id="semester_{{ $label }}-result-label">SEM-{{ $label }} Result <i
                                    class="las la-plus-circle"></i></span>

                            <input type="file" name="semester_{{ $label }}_result"
                                id="semester_{{ $label }}_result_upload"
                                onchange="updateFileNameSwap(this, 'semester_{{ $label }}-result-label')"
                                class="form-control static-crop" data-param="semester_{{ $label }}_url"
                                data-folder="Semester {{ $label }}"
                                data-prefix="semester_{{ $label }}_" />
                        </label>
                        <input id="semester_{{ $label }}_resultimage" type="hidden" />
                        <div class="mb-3 d-none" id="semester_{{ $label }}_upload_wrapper"
                            style="display: flex;justify-content: flex-start;align-items: center;">
                            <div class="spinner-border" style="margin-right: 10px;" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <label id="lbUploadedImge" for="">Uploading Student Photo...</label>
                        </div>
                        <img id="semester_{{ $label }}_resultImage" class="rounded img-fluid" src=""
                            style="display: none;" />

                        @if ($admissionDetail && $admissionDocuments)
                            @foreach ($admissionDocuments as $document)
                                @if ($document->doc_type == 'Semester ' . $label && $document->course_id == $admissionDetail->course_id)
                                    <div class="semester-download-box" data-course-id="{{ $document->course_id }}">
                                        <div class="doc-download-box">
                                            <a href="{{ route('student.document.download', $document->id) }}">
                                                <span>Semester {{ $label }}</span>
                                                <img src="{{ asset('assets/images/download-icon.svg') }}">
                                            </a>
                                            <img src="{{ asset($document->doc_url) }}" alt=""
                                                id="semester{{ $label }}Img" class="uploaded-img">
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="col-md-6 semester-group" data-sem="{{ $label }}">
        <label class="form-label" for="semester_{{ $label }}_result_upload">Upload your
            Semester-{{ $label }}
            Result</label>
        <div class="row g-2 justify-content-between">
            <!-- Percentile Field -->
            <div class="col-12 col-md-4 percentage_box">
                <div class="d-flex">
                    <label class="form-label mb-0 me-2 align-self-center"
                        for="semester_{{ $label }}_percentage">Percentage:</label>
                    <div class="percentage-error">
                        @if ($admissionDetail && $admissionDocuments)
                            {{-- {{ dd($admissionDocuments) }} --}}
                            @php
                                $document = $admissionDocuments
                                    ->where('doc_type', 'Semester ' . $label)
                                    ->where('course_id', $admissionDetail->course_id)
                                    ->first();
                            @endphp
                            @if (!empty($document) && $document->doc_type == 'Semester ' . $label && $document->course_id == $admissionDetail->course_id)
                                <input type="text" style="width: 100px" class="form-control"
                                    id="semester_{{ $label }}_percentage"
                                    name="semester_{{ $label }}_percentage"
                                    value="{{ $document->percentile }}">
                            @else
                                <input type="text" style="width: 100px" class="form-control"
                                    id="semester_{{ $label }}_percentage"
                                    name="semester_{{ $label }}_percentage"
                                    value="{{ data_get($admissionDetail, $label . '_percentage', '') }}">
                            @endif
                        @else
                            {{ $admissionDetail && $admissionDocuments }}
                            <input type="text" style="width: 100px" class="form-control"
                                id="semester_{{ $label }}_percentage"
                                name="semester_{{ $label }}_percentage"
                                value="{{ data_get($admissionDetail, $label . '_percentage', '') }}">
                        @endif
                    </div>
                </div>
            </div>

            <!-- Upload Field -->
            <div class="col-12 col-md-8 upload_document">
                <div class="upload-group">
                    <div class="error-message drop-area">
                        <label for="semester_{{ $label }}_result_upload" class="custom-file-upload">
                            <span id="semester_{{ $label }}-result-label">SEM-{{ $label }} Result <i
                                    class="las la-plus-circle"></i></span>

                            <input type="file" name="semester_{{ $label }}_result"
                                id="semester_{{ $label }}_result_upload"
                                onchange="updateFileNameSwap(this, 'semester_{{ $label }}-result-label')"
                                class="form-control static-crop" data-param="semester_{{ $label }}_url"
                                data-folder="Semester {{ $label }}"
                                data-prefix="semester_{{ $label }}_" />
                        </label>
                        <input id="semester_{{ $label }}_resultimage" type="hidden" />
                        <div class="mb-3 d-none" id="semester_{{ $label }}_upload_wrapper"
                            style="display: flex;justify-content: flex-start;align-items: center;">
                            <div class="spinner-border" style="margin-right: 10px;" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <label id="lbUploadedImge" for="">Uploading Student Photo...</label>
                        </div>
                        <img id="semester_{{ $label }}_resultImage" class="rounded img-fluid" src=""
                            style="display: none;" />

                        @if ($admissionDetail && $admissionDocuments)
                            @php
                                $document = $admissionDocuments
                                    ->where('doc_type', 'Semester ' . $label)
                                    ->where('course_id', $admissionDetail->course_id)
                                    ->first();
                            @endphp
                            @if ($document)
                                <div class="semester-download-box" data-course-id="{{ $document->course_id }}">
                                    <div class="doc-download-box">
                                        <a href="{{ route('student.document.download', $document->id) }}">
                                            <span>Semester {{ $label }}</span>
                                            <img src="{{ asset('assets/images/download-icon.svg') }}">
                                        </a>
                                        <img src="{{ asset($document->doc_url) }}" alt=""
                                            id="semester{{ $label }}Img" class="uploaded-img">
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
