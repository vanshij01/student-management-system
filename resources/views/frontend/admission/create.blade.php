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
        }

        gap: 0px;
        }

        */
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
            @if (session('message'))
                <div class="alert alert-{{ session('status') }} alert-dismissible fade show" role="alert">
                    <strong>{{ session('message') }}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

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
                    <input type="hidden" name="hdnCollegeFeesReceipturl" id="hdnCollegeFeesReceipturl"
                        value="{{ $oldAdmissionDetails->college_fees_receipt_url ?? '' }}">
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
                        @include('frontend.admission.create_partial._personal_details')
                        <!-- family-details -->
                        @include('frontend.admission.create_partial._family_details')
                        <!-- education-details -->
                        @include('frontend.admission.create_partial._education_details')
                        <!-- declaration -->
                        @include('frontend.admission.create_partial._declaration')
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
                    <button type="button" class="btn btn-outline-secondary close-modal"
                        data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="cropImageBtn">Save</button>
                </div>

            </div>
        </div>
    </div>
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
                            } else if (inputId.includes('upload_file_')) {
                                // For other document uploads
                                const index = inputId.split('_').pop();
                                imgId = 'file_preview_' + index;
                                hiddenId = 'file_image_' + index;

                                // Update the file label to show the file is selected
                                $('#file_label_' + index).text('Document Selected');
                            } else {
                                // Fallback for other naming patterns
                                imgId = inputId + 'Image';
                                hiddenId = inputId + 'image';
                            }

                            $('#course_id').on('change', function() {
                                var current_course_id = $(this).val();
                                var old_course_id = $('#old_course_id').val();

                                var course_id;
                                if (current_course_id == old_course_id) {
                                    course_id = old_course_id;
                                } else {
                                    course_id = current_course_id;
                                }
                            });

                            var course_id = $('#course_id').val() == $('#old_course_id').val() ?
                                $('#old_course_id').val() :
                                $('#course_id').val();

                            console.log('course_id', course_id);

                            var jsonObj = {
                                'base64File': base64data,
                                'admissionId': "{{ $oldAdmissionDetails->id ?? '0' }}",
                                'studentId': '{{ $studentData->id }}',
                                'courseId': course_id,
                                'defaultDocumentPath': "Uploads/Admission/",
                                "config": JSON.stringify({
                                    'param': $('#' + inputId).data('param'),
                                    'folder': $('#' + inputId).data('folder'),
                                    'prefix': $('#' + inputId).data('prefix'),
                                    'semester': $('#semester').val(),
                                })
                            };

                            // Show loading spinner
                            if (inputId.includes('upload_file_')) {
                                const index = inputId.split('_').pop();
                                $('#upload_wrapper_' + index).removeClass('d-none');
                            } else {
                                $('#' + inputId + '_wrapper').removeClass('d-none');
                            }

                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                        'content')
                                }
                            });

                            $.post("{{ route('admission.file-upload') }}", jsonObj, function(
                                data, textStatus, jqXHR) {
                                // Hide loading spinner
                                if (inputId.includes('upload_file_')) {
                                    const index = inputId.split('_').pop();
                                    $('#upload_wrapper_' + index).addClass('d-none');
                                } else {
                                    $('#' + inputId + '_wrapper').addClass('d-none');
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
                            });

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

            $(document).on('click', '.close-modal', function() {
                if (currentFileInput) {
                    const inputId = currentFileInput.attr('id');
                    let labelId = currentFileInput.attr('onchange')?.match(/'([^']+)'/)?.[1];

                    if (!labelId && inputId.includes('_upload')) {
                        labelId = inputId.replace('_upload', '-label').replaceAll('_', '-');
                    }

                    if (labelId) {
                        const labelEl = document.getElementById(labelId);
                        if (labelEl) {
                            const defaultText = labelEl.getAttribute('data-default') || 'Upload';
                            labelEl.innerHTML = defaultText;
                        }
                    }

                    currentFileInput.val('');
                }

                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }

                currentFileInput = null;
                isStaticCrop = false;
            });

            /* $(document).on('click', '.close-modal', function() {
                if (currentFileInput) {
                    const inputId = currentFileInput.attr('id');
                    let labelId = currentFileInput.attr('onchange')?.match(/'([^']+)'/)?.[1];

                    // Fallback if onchange not usable
                    if (!labelId && inputId.includes('_upload')) {
                        labelId = inputId.replace('_upload', '-backlog_result-label');
                    }

                    // Reset label
                    if (labelId) {
                        updateFileNameSwap(currentFileInput[0], labelId);
                    }

                    // Clear file input
                    currentFileInput.val('');
                }

                // Cleanup
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }

                currentFileInput = null;
                isStaticCrop = false;
            }); */

            $('.close-modal').on('click', function() {
                if (currentFileInput) {
                    const inputId = currentFileInput.attr('id');
                    const labelId = inputId.replace('_upload', '-label');
                    const labelEl = document.getElementById(labelId);
                    if (labelEl) {
                        const defaultText = labelEl.getAttribute('data-default') || 'Upload';
                        labelEl.innerHTML = defaultText;
                    }

                    currentFileInput.val(''); // Clear file input
                }
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
                'passport_back',
                'otherDoc' // Add otherDoc to the list
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

            // Add debug console logs
            console.log('Document upload handlers initialized');
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
