@props(['oldAdmissionDocuments', 'oldAdmissionDetails', 'admissionDetail', 'mode'])

<div class="col-12 document-group">
    <label class="form-label" for="otherDoc_upload">Upload Your Document</label>
    <div class="row g-2">
        <table class="table" id="documentTable">
            <tbody>
                {{-- {{ dd($oldAdmissionDetails) }} --}}
                @if (isset($oldAdmissionDocuments) && count($oldAdmissionDocuments) > 0)
                @foreach ($oldAdmissionDocuments as $index => $doc)
                        @if (str_contains($doc->doc_url ?? '', 'Other_Document') && $doc->course_id == $oldAdmissionDetails->course_id)
                            <tr data-id="{{ $doc->id }}">
                                <!-- Document Type Field -->
                                <td class="ps-2">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control" id="type_{{ $index }}"
                                            name="otherDoc[{{ $index }}][type]" value="{{ $doc->doc_type }}"
                                            placeholder="Enter document type" />
                                        <label for="type_{{ $index }}">Type of Document</label>
                                        <small class="red-text ml-10" role="alert"></small>
                                    </div>
                                </td>
                                <!-- Percentage Field -->
                                <td>
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control" id="percentage_{{ $index }}"
                                            name="otherDoc[{{ $index }}][percentage]"
                                            value="{{ $doc->percentile }}" placeholder="Enter Percentage" />
                                        <label for="percentage_{{ $index }}">Percentage</label>
                                        <small class="red-text ml-10" role="alert"></small>
                                    </div>
                                </td>
                                <!-- Upload Field -->
                                <td>
                                    <div class="upload-group">
                                        <div class="error-message drop-area d-flex align-items-center gap-2">
                                            <label for="upload_file_{{ $index }}"
                                                class="custom-file-upload w-100">
                                                <span id="file_label_{{ $index }}"
                                                    data-default="Other Document <i class='las la-plus-circle'></i>">
                                                    @if ($doc->doc_url)
                                                        Document Uploaded
                                                    @else
                                                        Other Document <i class="las la-plus-circle"></i>
                                                    @endif
                                                </span>
                                                <input type="file" name="otherDoc[{{ $index }}][file]"
                                                    id="upload_file_{{ $index }}"
                                                    class="form-control static-crop" data-param="otherDoc_url"
                                                    data-folder="Other Document" data-prefix="other_doc_" />
                                            </label>
                                            <input id="file_image_{{ $index }}" type="hidden"
                                                name="otherDoc[{{ $index }}][image]"
                                                value="{{ $doc->doc_url }}" />
                                            <div class="mb-3 d-none" id="upload_wrapper_{{ $index }}"
                                                style="display: flex;justify-content: flex-start;align-items: center;">
                                                <div class="spinner-border" style="margin-right: 10px;" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                                <label id="upload_label_{{ $index }}" for="">Uploading
                                                    Document...</label>
                                            </div>
                                            <img id="file_preview_{{ $index }}" class="rounded img-fluid"
                                                style="display: none;" />
                                        </div>
                                    </div>
                                    <div class="other-docs">
                                        <div class="col-12">
                                            <div class="OtherDoc-download-wrap d-flex ">
                                                <div class="doc-download-box">
                                                    <a href="{{ route('student.document.download', $doc->id) }}">
                                                        <span>{{ Str::words($doc->doc_type, 2, '') }}</span>
                                                        <img src="{{ asset('assets/images/download-icon.svg') }}">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    {{-- <div class="d-flex align-items-center">
                                        <a class="btn btn-secondary btn-remove-document text-white"
                                            data-id="{{ $doc->id }}">Remove</a>
                                    </div> --}}
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    {{-- @if ($mode == 'create')
                            <tr data-id="doc_0">
                                <!-- Document Type Field -->
                                <td class="ps-2">
                                    <div class="error-message">
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" class="form-control" id="type_0"
                                            name="otherDoc[0][type]" placeholder="Enter document type" />
                                            <label for="type_0">Type of Document</label>
                                            <small class="red-text ml-10" role="alert"></small>
                                        </div>
                                    </div>
                                </td>
                                <!-- Percentage Field -->
                                <td>
                                    <div class="form-floating form-floating-outline error-message">
                                        <input type="text" class="form-control" id="percentage_0"
                                            name="otherDoc[0][percentage]" placeholder="Enter Percentage" />
                                        <label for="percentage_0">Percentage</label>
                                        <small class="red-text ml-10" role="alert"></small>
                                    </div>
                                </td>
                                <!-- Upload Field -->
                                <td>
                                    <div class="upload-group">
                                        <div class="error-message drop-area">
                                            <label for="upload_file_0" class="custom-file-upload w-100">
                                                <span id="file_label_0"
                                                    data-default="Other Document <i class='las la-plus-circle'></i>">Other
                                                    Document <i class="las la-plus-circle"></i></span>
                                                <input type="file" name="otherDoc[0][file]" id="upload_file_0"
                                                    class="form-control static-crop" data-param="otherDoc_url"
                                                    data-folder="Other Document" data-prefix="other_doc_" />
                                            </label>
                                            <input id="file_image_0" type="hidden" name="otherDoc[0][image]" />
                                            <div class="mb-3 d-none" id="upload_wrapper_0"
                                                style="display: flex;justify-content: flex-start;align-items: center;">
                                                <div class="spinner-border" style="margin-right: 10px;"
                                                    role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                                <label id="upload_label_0" for="">Uploading
                                                    Document...</label>
                                            </div>
                                            <img id="file_preview_0" class="rounded img-fluid" src=""
                                                style="display: none;" />
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a class="btn btn-secondary btn-remove-document text-white"
                                            data-id="doc_0">Remove</a>
                                    </div>
                                </td>
                            </tr>
                        @endif --}}
                @else
                    <tr data-id="doc_0">
                        <!-- Document Type Field -->
                        <td class="ps-2">
                            <div class="error-message">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" id="type_0" name="otherDoc[0][type]"
                                        placeholder="Enter document type" />
                                    <label for="type_0">Type of Document</label>
                                    <small class="red-text ml-10" role="alert"></small>
                                </div>
                            </div>
                        </td>
                        <!-- Percentage Field -->
                        <td>
                            <div class="form-floating form-floating-outline error-message">
                                <input type="text" class="form-control" id="percentage_0"
                                    name="otherDoc[0][percentage]" placeholder="Enter Percentage" />
                                <label for="percentage_0">Percentage</label>
                                <small class="red-text ml-10" role="alert"></small>
                            </div>
                        </td>
                        <!-- Upload Field -->
                        <td>
                            <div class="upload-group">
                                <div class="error-message drop-area">
                                    <label for="upload_file_0" class="custom-file-upload w-100">
                                        <span id="file_label_0"
                                            data-default="Other Document <i class='las la-plus-circle'></i>">Other
                                            Document <i class="las la-plus-circle"></i></span>
                                        <input type="file" name="otherDoc[0][file]" id="upload_file_0"
                                            class="form-control static-crop" data-param="otherDoc_url"
                                            data-folder="Other Document" data-prefix="other_doc_" />
                                    </label>
                                    <input id="file_image_0" type="hidden" name="otherDoc[0][image]" />
                                    <div class="mb-3 d-none" id="upload_wrapper_0"
                                        style="display: flex;justify-content: flex-start;align-items: center;">
                                        <div class="spinner-border" style="margin-right: 10px;" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <label id="upload_label_0" for="">Uploading
                                            Document...</label>
                                    </div>
                                    <img id="file_preview_0" class="rounded img-fluid" src=""
                                        style="display: none;" />
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <a class="btn btn-secondary btn-remove-document text-white" data-id="doc_0">Remove</a>
                            </div>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="row mb-2 mt-4">
        <div class="col-md-12">
            <button type="button" class="btn btn-primary" id="btn-add-document">+ Add More</button>
        </div>
    </div>
</div>
