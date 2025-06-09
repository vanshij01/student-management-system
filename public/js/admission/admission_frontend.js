'use strict';
$('#addmission_date, #college_fees_receipt_date, #arriving_date').datepicker({
    dateFormat: 'dd/mm/yy',
});

$('.semester-group').addClass('d-none');
function updateFileNameSwap(input, targetId) {
    const label = document.getElementById(targetId);
    const defaultHtml = label.getAttribute('data-default') || 'Upload';
    const fileName = input.files.length > 0 ? input.files[0].name : defaultHtml;

    if (input.files.length > 0) {
        label.textContent = fileName; // Plain filename when selected
    } else {
        label.innerHTML = defaultHtml; // Restore full HTML with icon
    }
}
window.selectedCourseId = null;

$('#dob').datepicker({
    dateFormat: 'dd/mm/yy',
    changeMonth: true,
    changeYear: true,
    maxDate: 'Today',
    yearRange: "-120:+10",
});

$('.select2').select2();

$('#education_type').on('change', function () {
    var education_type = $('#education_type').val();
    displaySemester(education_type);

    const excludedTypes = ['Job', 'Internship', 'Other'];
    const isJobOrInternship = excludedTypes.includes(education_type);

    if (isJobOrInternship) {
        $('.fees-section').addClass('d-none');
    } else {
        $('.fees-section').removeClass('d-none');
    }
});

var education_type = $('#education_type').val();
displaySemester(education_type);

function displaySemester(education_type) {
    if (education_type == "HSC" || education_type == "Bachelor's Degree" || education_type == "Master's Degree") {
        $('.semester-div').removeClass('d-none');
    } else {
        $('.semester-div').addClass('d-none');
    }

    // Initialize sections visibility on page load
    if (education_type === 'Other') {
        $('.otherDocs').removeClass('d-none').addClass('d-block');
    } else {
        $('.otherDocs').addClass('d-none').removeClass('d-block');
    }
}

(function () {
    const admissonForm = document.querySelector('#admission_form');
    window.isUpdateMode = $('#form_mode').val() == 'update';
    const oldCourse = $('#old_course_id').val();
    const oldCourseName = $('#old_course_name').val();

    // if (typeof admissonForm !== undefined && admissonForm !== null) {
    if (admissonForm) {
        // Wizard form
        const admissonFormVal = admissonForm.querySelector('#admission_form');
        // Wizard steps
        const admissonFormValStep1 = admissonFormVal.querySelector('#personal-details');
        const admissonFormValStep2 = admissonFormVal.querySelector('#family-details');
        const admissonFormValStep3 = admissonFormVal.querySelector('#education-details');
        const admissonFormValStep4 = admissonFormVal.querySelector('#declaration');
        // Wizard next prev button
        const admissonFormNext = [].slice.call(admissonFormVal.querySelectorAll('.btn-next'));
        const admissonFormPrev = [].slice.call(admissonFormVal.querySelectorAll('.btn-prev'));

        const validationStepper = new Stepper(admissonForm, {
            linear: true
        });

        // Personal Details

        // Utility: Add file validators
        const getFileValidators = (label = 'file') => ({
            notEmpty: {
                message: `Please upload your ${label}.`
            },
            file: {
                extension: 'jpg,jpeg,png',
                type: 'image/jpeg,image/png',
                message: 'Only JPG, JPEG, and PNG image files are allowed.'
            },
            fileSize: {
                max: '1MB',
                message: 'The file size must not exceed 2MB.'
            }
        });

        // Utility: Add or remove multiple fields
        const manageFields = (fields, action) => {
            fields.forEach(([name, validators]) => {
                if (action === 'add') {
                    FormValidation1.addField(name, { validators });
                } else if (action === 'remove' && FormValidation1.getFields()[name]) {
                    FormValidation1.removeField(name);
                }
            });
        };

        var studentImg = $('#studentImg').attr('src');
        var fatherImg = $('#fatherImg').attr('src');
        var motherImg = $('#motherImg').attr('src');
        var studentAadharFrontImg = $('#studentAadharFrontImg').attr('src');
        var studentAadharBackImg = $('#studentAadharBackImg').attr('src');
        var studentPassportFrontImg = $('#studentPassportFrontImg').attr('src');
        var studentPassportBackImg = $('#studentPassportBackImg').attr('src');
        var parentAadharFrontImg = $('#parentAadharFrontImg').attr('src');
        var parentAadharBackImg = $('#parentAadharBackImg').attr('src');
        var parentPassportFrontImg = $('#parentPassportFrontImg').attr('src');
        var parentPassportBackImg = $('#parentPassportBackImg').attr('src');
        var licenceImg = $('#licenceImg').attr('src');
        var insuranceImg = $('#insuranceImg').attr('src');
        var rcBookFrontImg = $('#rcBookFrontImg').attr('src');
        var rcBookBackImg = $('#rcBookBackImg').attr('src');
        var sscImg = $('#sscImg').attr('src');
        var hscImg = $('#hscImg').attr('src');
        var leaving_certificateImg = $('#leaving_certificateImg').attr('src');
        var last_qualificationImg = $('#last_qualificationImg').attr('src');
        var degree_certificateImg = $('#degree_certificateImg').attr('src');
        var job_letterImg = $('#job_letterImg').attr('src');
        var internship_letterImg = $('#internship_letterImg').attr('src');

        // Step 1 form validation
        const FormValidation1 = FormValidation.formValidation(admissonFormValStep1, {
            fields: {
                dob: { validators: { notEmpty: { message: 'Please select your date of birth.' } } },
                residence_address: { validators: { notEmpty: { message: 'Please enter your address.' } } },
                country: { validators: { notEmpty: { message: 'Please select a country.' } } },
                passport_photo: (studentImg == null) ? { validators: getFileValidators('passport size photo') } : '',
                is_any_illness: { validators: { notEmpty: { message: 'Please select.' } } },
                is_used_vehicle: { validators: { notEmpty: { message: 'Please select.' } } },
                is_indian_citizen: { validators: { notEmpty: { message: 'Please select.' } } },
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap5: new FormValidation.plugins.Bootstrap5({
                    eleValidClass: '',
                    rowSelector: '.col, .upload-group, .error-message'
                }),
                autoFocus: new FormValidation.plugins.AutoFocus(),
                submitButton: new FormValidation.plugins.SubmitButton()
            },
            init: instance => {
                instance.on('plugins.message.placed', e => {
                    if (e.element.parentElement.classList.contains('input-group')) {
                        e.element.parentElement.insertAdjacentElement('afterend', e.messageElement);
                    }
                });
            }
        }).on('core.form.valid', () => validationStepper.next());

        // Citizenship toggle
        function displayStudentAadharPassport(isIndian) {
            const aadhaarFields = [
                ['adhaar_number', {
                    notEmpty: { message: 'Aadhaar number is required.' },
                    stringLength: {
                        min: 12,
                        max: 12,
                        message: 'Aadhaar number must be exactly 12 digits long.'
                    }
                }],
                ['aadhar_front', (studentAadharFrontImg == null) ? getFileValidators('aadhar front') : ''],
                ['aadhar_back', (studentAadharBackImg == null) ? getFileValidators('aadhar back') : ''],
            ];
            const passportFields = [
                ['passport_number', {
                    notEmpty: { message: 'Passport number is required.' },
                    stringLength: {
                        min: 6,
                        max: 9,
                        message: 'Passport number must be between 6 and 9 characters long.'
                    }
                }],
                ['passport_front', (studentPassportFrontImg == null) ? getFileValidators('passport front') : ''],
                ['passport_back', (studentPassportBackImg == null) ? getFileValidators('passport back') : '']
            ];

            if (isIndian === 'true') {
                $('.passport_number_student_field').addClass('d-none');
                $('.adhaar_number_field').removeClass('d-none');
                $('#passport_number, #passport_front, #passport_back').val('');
                manageFields(passportFields, 'remove');
                manageFields(aadhaarFields, 'add');
            } else {
                $('.adhaar_number_field').addClass('d-none');
                $('.passport_number_student_field').removeClass('d-none');
                $('#adhaar_number, #aadhar_front, #aadhar_back').val('');
                manageFields(aadhaarFields, 'remove');
                manageFields(passportFields, 'add');
            }
        }

        // Illness field toggle
        function displayIllnessField(hasIllness) {
            const field = ['illness_description', {
                notEmpty: { message: 'Please provide details about the illness.' }
            }];
            if (hasIllness === 'true') {
                $('.student_illness_field').removeClass('d-none');
                manageFields([field], 'add');
            } else {
                $('.student_illness_field').addClass('d-none');
                manageFields([field], 'remove');
            }
        }

        // Vehicle field toggle
        function displayVehicleField(hasVehicle) {
            const vehicleFields = [
                ['is_have_helmet', {
                    callback: {
                        message: 'You must have a helmet to use a vehicle.',
                        callback: function (input) {
                            // Get the selected value
                            const value = $('input[name="is_have_helmet"]:checked').val();

                            // Show/hide inline error message
                            if (value === 'false') {
                                $('.helmet-error').show();
                                return false;
                            } else {
                                $('.helmet-error').hide();
                                return true;
                            }
                        }
                    }
                }],
                ['vehicle_number', {
                    notEmpty: { message: 'Please enter your vehicle number.' },
                    regexp: {
                        regexp: /^[A-Z]{2}[0-9]{2}[A-Z]{1,2}[0-9]{4}$/,
                        message: "Vehicle number must contain only uppercase letters and digits, with no spaces or special characters."
                    }
                }],
                ['licence_doc_url', (licenceImg == null) ? getFileValidators('driving license') : ''],
                ['rcbook_front_doc_url', (rcBookFrontImg == null) ? getFileValidators('front of RC book') : ''],
                ['rcbook_back_doc_url', (rcBookBackImg == null) ? getFileValidators('back of RC book') : ''],
                ['insurance_doc_url', (insuranceImg == null) ? getFileValidators('insurance document') : '']
            ];

            if (hasVehicle === 'true') {
                $('.vehicle_details_field').removeClass('d-none');
                manageFields(vehicleFields, 'add');
            } else {
                $('.vehicle_details_field').addClass('d-none');
                manageFields(vehicleFields, 'remove');
            }
        }

        // Event listeners
        $('input[name="is_indian_citizen"]').on('change', () =>
            displayStudentAadharPassport($('input[name="is_indian_citizen"]:checked').val())
        );
        $('input[name="is_any_illness"]').on('change', () =>
            displayIllnessField($('input[name="is_any_illness"]:checked').val())
        );
        $('input[name="is_used_vehicle"]').on('change', () =>
            displayVehicleField($('input[name="is_used_vehicle"]:checked').val())
        );

        // Initial state on load
        displayStudentAadharPassport($('input[name="is_indian_citizen"]:checked').val());
        displayIllnessField($('input[name="is_any_illness"]:checked').val());
        displayVehicleField($('input[name="is_used_vehicle"]:checked').val());

        // Family Details
        const validators = {
            name: (label) => ({
                notEmpty: { message: `Please enter ${label}.` },
                regexp: {
                    regexp: /^[A-Za-z\s]+$/,
                    message: `${label} can only contain letters and spaces.`
                }
            }),
            phone: (label) => ({
                notEmpty: { message: `Please enter ${label}.` },
                stringLength: {
                    min: 7,
                    max: 12,
                    message: `${label} must be between 7 and 12 digits long.`
                }
            }),
            radio: (label) => ({
                notEmpty: { message: `Please select ${label}.` },
            }),
            fileImage: (label) => ({
                notEmpty: { message: `Please upload ${label}.` },
                file: {
                    extension: 'jpg,jpeg,png',
                    type: 'image/jpeg,image/png',
                    message: `${label} must be a JPG, JPEG, or PNG image file.`
                },
                fileSize: {
                    max: '1MB',
                    message: `${label} must not exceed 2MB in size.`
                }
            })
        };

        const FormValidation2 = FormValidation.formValidation(admissonFormValStep2, {
            fields: {
                father_full_name: { validators: validators.name("Father's full name") },
                father_phone: { validators: validators.phone("Father's contact number") },
                father_occupation: { validators: validators.name("Father's occupation") },
                mother_full_name: { validators: validators.name("Mother's full name") },
                is_local_guardian_in_ahmedabad: { validators: validators.radio("Please select") },
                is_parent_indian_citizen: { validators: validators.radio("Please select") },
                mother_phone: { validators: validators.phone("Mother's contact number") },
                mother_occupation: { validators: validators.name("Mother's occupation") },
                annual_income: {
                    validators: {
                        notEmpty: { message: 'Please enter Annual Income.' },
                        greaterThan: {
                            min: 1,
                            message: 'Annual income must be greater than 0.'
                        }
                    }
                },
                father_photo: { validators: (fatherImg == null) ? validators.fileImage("Father's passport photo") : '' },
                mother_photo: { validators: (motherImg == null) ? validators.fileImage("Mother's passport photo") : '' }
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap5: new FormValidation.plugins.Bootstrap5({
                    eleValidClass: '',
                    rowSelector: '.col, .col-4, .upload-group, .error-message'
                }),
                autoFocus: new FormValidation.plugins.AutoFocus(),
                submitButton: new FormValidation.plugins.SubmitButton()
            }
        }).on('core.form.valid', () => {
            validationStepper.next();
        });

        // ---- LOCAL GUARDIAN FIELDS ---- //

        function toggleLocalGuardianFields(isShown) {
            if (isShown) {
                $('.local_guardian_fields').removeClass('d-none');
                FormValidation2.addField('guardian_name', { validators: validators.name("Guardian's name") });
                FormValidation2.addField('guardian_relation', { validators: validators.name("Guardian's relationship") });
                FormValidation2.addField('guardian_phone', { validators: validators.phone("Guardian's contact number") });
            } else {
                ['guardian_name', 'guardian_relation', 'guardian_phone'].forEach(field => {
                    if (FormValidation2.getFields()[field]) {
                        FormValidation2.removeField(field);
                    }
                });
                $('.local_guardian_fields').addClass('d-none');
            }
        }

        // Initial state for guardian fields
        toggleLocalGuardianFields($('input[name="is_local_guardian_in_ahmedabad"]:checked').val() === 'true');

        // Toggle on change
        $('input[name="is_local_guardian_in_ahmedabad"]').on('change', function () {
            toggleLocalGuardianFields(this.value === 'true');
        });

        // ---- PARENT CITIZENSHIP FIELDS (AADHAR/PASSPORT) ---- //

        function toggleParentCitizenFields(isIndian) {
            const aadharFields = ['parents_aadhar_front', 'parents_aadhar_back'];
            const passportFields = ['parents_passport_front', 'parents_passport_back'];

            if (isIndian) {
                $('.passport_number_parent_field').addClass('d-none');
                $('.adhaar_number_parent_field').removeClass('d-none');
                passportFields.forEach(id => $(`#${id}`).val(''));
                passportFields.forEach(field => {
                    if (FormValidation2.getFields()[field]) {
                        FormValidation2.removeField(field);
                    }
                });

                FormValidation2.addField('parents_aadhar_front', { validators: (parentAadharFrontImg == null) ? validators.fileImage('Aadhar front (Parent)') : '' });
                FormValidation2.addField('parents_aadhar_back', { validators: (parentAadharBackImg == null) ? validators.fileImage('Aadhar back (Parent)') : '' });
            } else {
                $('.adhaar_number_parent_field').addClass('d-none');
                $('.passport_number_parent_field').removeClass('d-none');
                aadharFields.forEach(id => $(`#${id}`).val(''));
                aadharFields.forEach(field => {
                    if (FormValidation2.getFields()[field]) {
                        FormValidation2.removeField(field);
                    }
                });

                FormValidation2.addField('parents_passport_front', { validators: (parentPassportFrontImg == null) ? validators.fileImage('Passport front (Parent)') : '' });
                FormValidation2.addField('parents_passport_back', { validators: (parentPassportBackImg == null) ? validators.fileImage('Passport back (Parent)') : '' });
            }
        }

        // Initial state for parent citizenship
        toggleParentCitizenFields($('input[name="is_parent_indian_citizen"]:checked').val() === 'true');

        // Toggle on change
        $('input[name="is_parent_indian_citizen"]').on('change', function () {
            toggleParentCitizenFields(this.value === 'true');
        });
        const studentNew = $('input[name="is_admission_new"]:checked').val();
        window.isNewStudent = studentNew;
        var education_type = $('#education_type').val();

        // Property Area
        const formValidationFields = {
            education_type: {
                validators: {
                    notEmpty: {
                        message: 'Please select an education type.'
                    }
                }
            },
            course_id: {
                validators: {
                    notEmpty: {
                        message: 'Please select a course.',
                        callback: function (input) {
                            const educationType = $('#education_type').val();
                            // Skip course validation for 'Other' education type
                            if (educationType === 'Other') {
                                return true;
                            }
                            return input.value !== '';
                        }
                    },
                    callback: {
                        message: 'You have already completed this course. Please select a different one.',
                        callback: function (input) {
                            const educationType = $('#education_type').val();
                            const excludedTypes = ['Job', 'Internship'];
                            window.selectedCourseId = input.value;

                            // Always enable first
                            $('#course_id').prop('disabled', false);

                            // Skip this validation for excluded types
                            if (excludedTypes.includes(educationType)) {
                                return true;
                            }

                            // Skip for new students or update mode
                            if (window.isNewStudent === 'true' || window.isUpdateMode) {
                                return true;
                            }

                            const completed = window.completedCourseYears?.[selectedCourseId] || 0;
                            const duration = window.courseDurations?.[selectedCourseId] || 0;

                            const isCompleted = completed >= duration;

                            if (isCompleted) {

                                // Optionally show a toast/alert here
                                if (completed == 0) {
                                    $('#course_id').prop('disabled', false);
                                }

                                return false;
                            } else {
                                $('#course_id').prop('disabled', !isCompleted);
                                // Optionally show a toast/alert here
                                if (completed == 0) {
                                    $('#course_id').prop('disabled', false);
                                }
                            }

                            return true;
                        }
                    }
                },
                trigger: 'change' // Important: ensure validation runs on change
            },
            board_type: {
                validators: {
                    callback: {
                        message: 'Please select the board type.',
                        callback: function (input) {
                            const educationType = $('#education_type').val();
                            if (educationType !== 'Job') {
                                return input.value !== '';
                            }
                            return true;
                        }
                    }
                }
            },
            semester: {
                validators: {
                    callback: {
                        message: 'Please select a semester.',
                        callback: function (input) {
                            const educationType = $('#education_type').val();
                            const requiredTypes = ["HSC", "Bachelor's Degree", "Master's Degree"];
                            if (requiredTypes.includes(educationType)) {
                                return input.value !== '';
                            }
                            return true; // Not required for other education types
                        }
                    }
                }
            },
            year_of_addmission: {
                validators: {
                    notEmpty: {
                        message: 'Please select the admission year.'
                    }
                }
            },
            arriving_date: {
                validators: {
                    notEmpty: {
                        message: 'Please select the arriving date.'
                    }
                }
            }
        };

        // Add institute_name and admission_date validation only for old students
        if (window.isNewStudent === 'false') {
            formValidationFields.institute_name = {
                validators: {
                    notEmpty: {
                        message: 'Please enter the institute name.'
                    }
                }
            };

            formValidationFields.addmission_date = {
                validators: {
                    notEmpty: {
                        message: 'Please select the admission date.'
                    }
                }
            };
        }

        // Property Area
        const FormValidation3 = FormValidation.formValidation(admissonFormValStep3, {
            on: 'submit',
            fields: formValidationFields,
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap5: new FormValidation.plugins.Bootstrap5({
                    eleValidClass: '',
                    rowSelector: '.col, .col-4, .col-sm-12, .upload-group, .error-message, .percentage-error'
                }),
                autoFocus: new FormValidation.plugins.AutoFocus(),
                submitButton: new FormValidation.plugins.SubmitButton()
            }
        }).on('core.form.valid', function () {
            // Proceed to the next step when the current step is valid
            validationStepper.next();
        }).on('core.form.invalid', function () {
            console.log('Form validation failed');
            // Log which fields are invalid
            const fields = FormValidation3.getFields();
            for (const field in fields) {
                FormValidation3.validateField(field).then(status => {
                    if (status !== 'Valid') {
                        console.log(`Field ${field} is invalid:`, status);
                    }
                });
            }
        });

        // const FormValidation3 = FormValidation.formValidation(admissonFormValStep3, {
        //     on: 'submit',
        //     fields: {
        //         education_type: {
        //             validators: {
        //                 notEmpty: {
        //                     message: 'Please select an education type.'
        //                 }
        //             }
        //         },
        //         course_id: {
        //             validators: {
        //                 notEmpty: {
        //                     message: 'Please select a course.',
        //                     callback: function (input) {
        //                         const educationType = $('#education_type').val();
        //                         // Skip course validation for 'Other' education type
        //                         if (educationType === 'Other') {
        //                             return true;
        //                         }
        //                         return input.value !== '';
        //                     }
        //                 },
        //                 callback: {
        //                     message: 'You have already completed this course. Please select a different one.',
        //                     callback: function (input) {
        //                         const educationType = $('#education_type').val();
        //                         const excludedTypes = ['Job', 'Internship'];
        //                         window.selectedCourseId = input.value;

        //                         // Always enable first
        //                         $('#course_id').prop('disabled', false);

        //                         // Skip this validation for excluded types
        //                         if (excludedTypes.includes(educationType)) {
        //                             return true;
        //                         }

        //                         // Skip for new students or update mode
        //                         if (window.isNewStudent === 'true' || window.isUpdateMode) {
        //                             return true;
        //                         }

        //                         const completed = window.completedCourseYears?.[selectedCourseId] || 0;
        //                         const duration = window.courseDurations?.[selectedCourseId] || 0;

        //                         const isCompleted = completed >= duration;

        //                         if (isCompleted) {

        //                             // Optionally show a toast/alert here
        //                             if (completed == 0) {
        //                                 $('#course_id').prop('disabled', false);
        //                             }

        //                             return false;
        //                         } else {
        //                             $('#course_id').prop('disabled', !isCompleted);
        //                             // Optionally show a toast/alert here
        //                             if (completed == 0) {
        //                                 $('#course_id').prop('disabled', false);
        //                             }

        //                         }

        //                         return true;
        //                     }
        //                 }
        //             },
        //             trigger: 'change' // Important: ensure validation runs on change
        //         },
        //         board_type: {
        //             validators: {
        //                 notEmpty: {
        //                     message: 'Please select the board type.'
        //                 }
        //             }
        //         },
        //         semester: {
        //             validators: {
        //                 callback: {
        //                     message: 'Please select a semester.',
        //                     callback: function (input) {
        //                         const educationType = $('#education_type').val();
        //                         const requiredTypes = ["HSC", "Bachelor's Degree", "Master's Degree"];
        //                         if (requiredTypes.includes(educationType)) {
        //                             return input.value !== '';
        //                         }
        //                         return true; // Not required for other education types
        //                     }
        //                 }
        //             }
        //         },
        //         /* institute_name: {
        //             validators: {
        //                 notEmpty: {
        //                     message: 'Please enter the institute name.'
        //                 }
        //             }
        //         }, */
        //         year_of_addmission: {
        //             validators: {
        //                 notEmpty: {
        //                     message: 'Please select the admission year.'
        //                 }
        //             }
        //         },
        //         /* addmission_date: {
        //             validators: {
        //                 notEmpty: {
        //                     message: 'Please select the admission date.'
        //                 }
        //             }
        //         }, */
        //         arriving_date: {
        //             validators: {
        //                 notEmpty: {
        //                     message: 'Please select the arriving date.'
        //                 }
        //             }
        //         }
        //     },
        //     plugins: {
        //         trigger: new FormValidation.plugins.Trigger(),
        //         bootstrap5: new FormValidation.plugins.Bootstrap5({
        //             eleValidClass: '',
        //             rowSelector: '.col, .col-4, .col-sm-12, .upload-group, .error-message, .percentage-error'
        //         }),
        //         autoFocus: new FormValidation.plugins.AutoFocus(),
        //         submitButton: new FormValidation.plugins.SubmitButton()
        //     }
        // }).on('core.form.valid', function () {
        //     // Proceed to the next step when the current step is valid
        //     validationStepper.next();
        // }).on('core.form.invalid', function () {
        //     console.log('Form validation failed');
        //     // Log which fields are invalid
        //     const fields = FormValidation3.getFields();
        //     for (const field in fields) {
        //         FormValidation3.validateField(field).then(status => {
        //             if (status !== 'Valid') {
        //                 console.log(`Field ${field} is invalid:`, status);
        //             }
        //         });
        //     }
        // });

        function ensureOtherDoc0Validation() {
            const typeSelector = '[name="otherDoc[0][type]"]';
            const fileSelector = '[name="otherDoc[0][file]"]';
            // Only add if not already added
            if ($(typeSelector).is(':visible') && !FormValidation3.getFields()['otherDoc[0][type]']) {
                FormValidation3.addField('otherDoc[0][type]', {
                    validators: {
                        notEmpty: {
                            message: 'Please enter document type.'
                        }
                    }
                });
            }
            if ($(fileSelector).is(':visible') && !FormValidation3.getFields()['otherDoc[0][file]']) {
                FormValidation3.addField('otherDoc[0][file]', {
                    validators: {
                        notEmpty: {
                            message: 'Please upload a document.'
                        },
                        file: {
                            extension: 'jpg,jpeg,png',
                            type: 'image/jpeg,image/png',
                            message: 'Only JPG, JPEG, PNG files allowed.'
                        },
                        fileSize: {
                            max: '2MB',
                            message: 'File must be <= 2MB.'
                        }
                    }
                });
            }
        }
        $(document).ready(function () {
            let docCounter = 0;

            ensureOtherDoc0Validation();

            $('#btn-add-document').on('click', function () {
                docCounter++;
                var documentItemHtml = '';
                documentItemHtml += '<tr data-id="doc_' + docCounter + '">';

                // Document Type Field
                documentItemHtml += '<td class="ps-2">' +
                    '<div class="error-message"><div class="form-floating form-floating-outline">' +
                    '<input type="text" class="form-control" name="otherDoc[' + docCounter + '][type]" ' +
                    'id="type_' + docCounter + '" placeholder="Enter document type" />' +
                    '<label for="type_' + docCounter + '">Type of Document</label>' +
                    '<small class="red-text ml-10" role="alert"></small>' +
                    '</div></div>' +
                    '</td>';

                // Percentage Field
                documentItemHtml += '<td>' +
                    '<div class="form-floating form-floating-outline error-message">' +
                    '<input type="text" class="form-control" id="percentage_' + docCounter + '" ' +
                    'name="otherDoc[' + docCounter + '][percentage]" placeholder="Enter Percentage" />' +
                    '<label for="percentage_' + docCounter + '">Percentage</label>' +
                    '<small class="red-text ml-10" role="alert"></small>' +
                    '</div>' +
                    '</td>';

                // Upload Field
                documentItemHtml += '<td>' +
                    '<div class="upload-group">' +
                    '<div class="error-message drop-area">' +
                    '<label for="upload_file_' + docCounter + '" class="custom-file-upload w-100">' +
                    '<span id="file_label_' + docCounter +
                    '" data-default="Other Document <i class=\'las la-plus-circle\'></i>">Other Document <i class="las la-plus-circle"></i></span>' +
                    '<input type="file" name="otherDoc[' + docCounter + '][file]" id="upload_file_' +
                    docCounter + '" ' +
                    'class="form-control static-crop" data-param="otherDoc_url" ' +
                    'data-folder="Other Document" data-prefix="other_doc_" />' +
                    '</label>' +
                    '<input id="file_image_' + docCounter + '" type="hidden" name="otherDoc[' + docCounter +
                    '][image]" />' +
                    '<div class="mb-3 d-none" id="upload_wrapper_' + docCounter + '" ' +
                    'style="display: flex;justify-content: flex-start;align-items: center;">' +
                    '<div class="spinner-border" style="margin-right: 10px;" role="status">' +
                    '<span class="sr-only">Loading...</span>' +
                    '</div>' +
                    '<label id="upload_label_' + docCounter + '" for="">Uploading Document...</label>' +
                    '</div>' +
                    '<img id="file_preview_' + docCounter +
                    '" class="rounded img-fluid" src="" style="display: none;" />' +
                    '</div>' +
                    '</div>' +
                    '</td>';

                // Remove Button
                documentItemHtml += '<td>' +
                    '<div class="d-flex align-items-center">' +
                    '<a class="btn btn-secondary btn-remove-document text-white" ' +
                    'data-id="doc_' + docCounter + '">Remove</a>' +
                    '</div>' +
                    '</td>';

                documentItemHtml += '</tr>';

                $('#documentTable tbody').append(documentItemHtml);
                setTimeout(() => {
                    const typeField = `otherDoc[${docCounter}][type]`;
                    const fileField = `otherDoc[${docCounter}][file]`;

                    if ($(`[name="${typeField}"]`).length) {
                        FormValidation3.addField(typeField, {
                            validators: {
                                notEmpty: {
                                    message: 'Please enter document type.'
                                }
                            }
                        });
                    }
                    if ($(`[name="${fileField}"]`).length) {
                        FormValidation3.addField(fileField, {
                            validators: {
                                notEmpty: {
                                    message: 'Please upload a document.'
                                },
                                file: {
                                    extension: 'jpg,jpeg,png',
                                    type: 'image/jpeg,image/png',
                                    message: 'Only JPG, JPEG, PNG files allowed.'
                                },
                                fileSize: {
                                    max: '2MB',
                                    message: 'File must be <= 2MB.'
                                }
                            }
                        });
                    }
                }, 100);
                return false;
            });

            $(document).on('click', '.btn-remove-document', function () {
                var id = $(this).data('id');
                var removeRow = $(this).closest('tr');
                const index = id ? id.replace('doc_', '') : null;

                if (id && !isNaN(id) && Number(id) > 0) {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You want to remove.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, remove it!',
                        cancelButtonText: 'No, Please!',
                        customClass: {
                            confirmButton: 'btn btn-primary me-3 waves-effect waves-light',
                            cancelButton: 'btn btn-outline-secondary waves-effect'
                        },
                        buttonsStyling: false
                    }).then(function (result) {
                        if (result.value) {
                            $.ajax({
                                type: "post",
                                url: "{{ route('admission.removeOtherDoc') }}",
                                data: {
                                    id: id,
                                    _token: '{{ csrf_token() }}'
                                }
                            }).done(function (data) {
                                if (!data.status) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Cancelled!',
                                        text: data.message,
                                        customClass: {
                                            confirmButton: 'btn btn-primary waves-effect'
                                        }
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Deleted!',
                                        text: data.message,
                                        customClass: {
                                            confirmButton: 'btn btn-primary waves-effect'
                                        }
                                    });
                                    removeRow.remove();
                                }
                            }).fail(function () {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Cancelled!',
                                    text: 'Something wrong.',
                                    customClass: {
                                        confirmButton: 'btn btn-primary waves-effect'
                                    }
                                });
                            });
                        } else {
                            Swal.fire({
                                title: 'Cancelled!',
                                text: 'Record is safe',
                                icon: 'error',
                                customClass: {
                                    confirmButton: 'btn btn-primary'
                                }
                            });
                        }
                    });
                } else {
                    if (index) {
                        // Only remove if the field exists in FormValidation3
                        if (FormValidation3.getFields()[`otherDoc[${index}][type]`]) {
                            FormValidation3.removeField(`otherDoc[${index}][type]`);
                        }
                        if (FormValidation3.getFields()[`otherDoc[${index}][file]`]) {
                            FormValidation3.removeField(`otherDoc[${index}][file]`);
                        }
                    }
                    removeRow.remove();
                }
            });
        });

        $('#education_type').on('change', function () {
            var education_type = $(this).val();
            displaySemester(education_type);

            // First, remove any existing otherDoc validations to prevent duplicates
            if (FormValidation3.getFields()['otherDoc[0][type]']) {
                FormValidation3.removeField('otherDoc[0][type]');
            }
            if (FormValidation3.getFields()['otherDoc[0][file]']) {
                FormValidation3.removeField('otherDoc[0][file]');
            }

            if (education_type === 'Other') {
                $('.backlog-option').hide();
                $('.degree-results').hide();
                $('.degree-backlog_results').hide();

                // First show the fields, then validate them
                $('.otherDocs').show();

                // Remove ALL semester-related fields from validation
                for (let i = 1; i <= 10; i++) {
                    const regularField = `semester_${i}_result`;
                    const backlogField = `semester_${i}_backlog_result`;
                    const percentageField = `semester_${i}_percentage`;
                    const backlogPercentageField = `semester_${i}_backlog_percentage`;

                    // Check if fields exist before removing them
                    if (FormValidation3.getFields()[regularField]) {
                        FormValidation3.removeField(regularField);
                    }
                    if (FormValidation3.getFields()[backlogField]) {
                        FormValidation3.removeField(backlogField);
                    }
                    if (FormValidation3.getFields()[percentageField]) {
                        FormValidation3.removeField(percentageField);
                    }
                    if (FormValidation3.getFields()[backlogPercentageField]) {
                        FormValidation3.removeField(backlogPercentageField);
                    }

                    // Clear any values in these fields
                    $(`#${regularField}_upload`).val('');
                    $(`#${backlogField}_upload`).val('');
                    $(`#${percentageField}`).val('');
                    $(`#${backlogPercentageField}`).val('');
                }

                // Hide all semester groups
                $('.semester-group').addClass('d-none');

                // Also remove semester field validation if it exists
                if (FormValidation3.getFields()['semester']) {
                    FormValidation3.removeField('semester');
                }

                // Wait for DOM to update before adding validation
                setTimeout(() => {
                    ensureOtherDoc0Validation();
                    // Revalidate course_id with the new context
                    FormValidation3.revalidateField('course_id');
                }, 100); // Slightly longer timeout to ensure DOM is updated
            } else {
                // Hide otherDocs and show regular fields
                $('.otherDocs').hide();
                $('.backlog-option').show();

                // Remove validation for otherDoc fields
                if (FormValidation3.getFields()['otherDoc[0][type]']) {
                    FormValidation3.removeField('otherDoc[0][type]');
                }
                if (FormValidation3.getFields()['otherDoc[0][file]']) {
                    FormValidation3.removeField('otherDoc[0][file]');
                }
            }
        });

        // Also update the initial setup code
        displaySemester(education_type);
        if (education_type === 'Other') {
            $('.backlog-option').hide();
            $('.degree-results').hide();
            $('.degree-backlog_results').hide();
            $('.otherDocs').show();

            // Remove ALL semester-related fields from validation
            for (let i = 1; i <= 10; i++) {
                const regularField = `semester_${i}_result`;
                const backlogField = `semester_${i}_backlog_result`;
                const percentageField = `semester_${i}_percentage`;
                const backlogPercentageField = `semester_${i}_backlog_percentage`;

                // Check if fields exist before removing them
                if (FormValidation3.getFields()[regularField]) {
                    FormValidation3.removeField(regularField);
                }
                if (FormValidation3.getFields()[backlogField]) {
                    FormValidation3.removeField(backlogField);
                }
                if (FormValidation3.getFields()[percentageField]) {
                    FormValidation3.removeField(percentageField);
                }
                if (FormValidation3.getFields()[backlogPercentageField]) {
                    FormValidation3.removeField(backlogPercentageField);
                }

                // Clear any values in these fields
                $(`#${regularField}_upload`).val('');
                $(`#${backlogField}_upload`).val('');
                $(`#${percentageField}`).val('');
                $(`#${backlogPercentageField}`).val('');
            }

            // Hide all semester groups
            $('.semester-group').addClass('d-none');

            // Also remove semester field validation if it exists
            if (FormValidation3.getFields()['semester']) {
                FormValidation3.removeField('semester');
            }

            setTimeout(ensureOtherDoc0Validation, 200);
        } else {
            $('.backlog-option').show();
            $('.otherDocs').hide();

            // Remove validation for otherDoc fields
            if (FormValidation3.getFields()['otherDoc[0][type]']) {
                FormValidation3.removeField('otherDoc[0][type]');
            }
            if (FormValidation3.getFields()['otherDoc[0][file]']) {
                FormValidation3.removeField('otherDoc[0][file]');
            }
        }

        // Manage dynamic addition or removal of fields
        const manageFieldsForm3 = (fields, action) => {
            fields.forEach(([name, validators]) => {
                if (action === 'add') {
                    FormValidation3.addField(name, { validators });
                } else if (action === 'remove' && FormValidation3.getFields()[name]) {
                    FormValidation3.removeField(name);
                }
            });
        };

        // Create a file validator with specified message
        function createFileValidator(message) {
            return {
                validators: {
                    notEmpty: { message },
                    file: {
                        extension: 'jpg,jpeg,png',
                        type: 'image/jpeg,image/png',
                        message: 'Only JPG, JPEG, and PNG image files are allowed.'
                    },
                    fileSize: {
                        max: '2MB',
                        message: 'The file size must not exceed 2MB.'
                    }
                }
            };
        }

        // var education_type = $('#education_type').val();
        getCoursesByEducation(education_type);

        function getCoursesByEducation(education_type) {
            $.ajax({
                type: "get",
                url: "/students/getCoursesByEducation",
                data: { education_type: education_type },
                cache: false,
                success: function (response) {
                    const courses = response.courses || [];
                    const oldCourse = $('#old_course_id').val();
                    let coursehtml = '<option value="">Select Course</option>';

                    $.each(courses, function (indexInArray, course) {
                        const selected = (course.id == oldCourse) ? ' selected' : '';
                        coursehtml += `<option value="${course.id}"${selected}>${course.course_name}</option>`;
                    });

                    $("#course_id").html(coursehtml);
                    // Trigger semester logic if a course is pre-selected
                    if (oldCourse && oldCourse != 0) {
                        getCourseList(oldCourse);
                    }
                }
            });
        }

        function getCourseList(course_id) {
            course_id = course_id === "" ? '' : course_id;

            $.ajax({
                type: "get",
                url: "/students/admission/getStudentAdmissionData/" + course_id,
                cache: false,
                success: function (response) {
                    var course = response.course
                    var admissions = response.admissions
                    var course_year = parseInt(course.duration); // duration is in years
                    var completedYears = [];
                    $.each(admissions, function (indexInArray, admission) {
                        if (admission.course_id == course.id) {
                            completedYears.push(admission.id);
                        }
                    });

                    // Store globally for callback validator
                    window.completedCourseYears = {
                        [course.id]: completedYears.length
                    };
                    window.courseDurations = {
                        [course.id]: course_year
                    };

                    // Revalidate the field after updating data
                    FormValidation3.revalidateField('course_id');
                    const educationType = $('#education_type').val();
                    if (educationType === 'Other') {
                        $('.semester-group').addClass('d-none');
                        return;
                    }
                    var totalSemesters = course_year * 2;

                    // Hide all semester groups first
                    $('.semester-group').addClass('d-none');

                    // Show semester groups based on course duration
                    /* for (let i = 1; i <= totalSemesters; i++) {
                        $(`.semester-group[data-sem="${i}"]`).removeClass('d-none');
                    } */

                    var last_semester = $('#last_semester').val();
                    var last_course = $('#last_course').val();
                    var current_semester;

                    if (isUpdatePage) {
                        // In update mode, use the exact semester that was selected during creation
                        current_semester = parseInt($('#semester').val()) || parseInt(last_semester) || 0;
                    } else {
                        // In create mode, increment by 2 as before
                        current_semester = parseInt(last_semester) > 0 ? parseInt(last_semester) + 2 : 0;
                    }

                    // course_id == (last_course && education_type != 'Professional Degree') ? updateSemesterValidators(parseInt(last_semester) + 1) : updateSemesterValidators(0);
                    // window.isNewStudent === 'false' && (last_course && education_type != 'Professional Degree') ? updateSemesterValidators(parseInt(last_semester) + 1) : updateSemesterValidators(0);

                    $("#semester").html('');
                    var semesterhtml = "";
                    for (let i = 1; i <= totalSemesters; i++) {
                        semesterhtml += '<option value="' + i + '"' + ((current_semester > 0 && current_semester == i && course_id == last_course) ? ' selected' : '') + '>' + i + '</option>';
                        if (last_course != 0 && last_course != $('#course_id').val()) {
                            $.ajax({
                                type: "get",
                                url: "/students/getCoursesById",
                                data: { course_id: oldCourse },
                                cache: false,
                                success: function (response) {
                                    var course = response.course;
                                    displaySemesterFields((course.duration * 2));
                                }
                            });
                        } else {

                            displaySemesterFields(current_semester > totalSemesters ? totalSemesters : current_semester);

                        }
                    }
                    $("#semester").append(semesterhtml);
                }
            });
        }

        var board_type = $('#board_type').val();
        displayBoardNameField(board_type);

        $('#board_type').on('change', function () {
            var board_type = $(this).val();
            displayBoardNameField(board_type);
        });

        function displayBoardNameField(board_type) {
            const field = ['board_name', {
                notEmpty: { message: 'Please enter board name.' }
            }];
            if (board_type == 'Other') {
                $('.board_name_field').removeClass('d-none');
                manageFieldsForm3([field], 'add');
            } else {
                $('.board_name_field').addClass('d-none');
                manageFieldsForm3([field], 'remove');
            }
        }

        const formMode = $('#form_mode').val();
        var isUpdatePage = formMode === 'update';


        var oldCourseEducationType = $('#old_course_education_type').val();

        /* function updateSemesterValidators(currentSemester) {
            const educationType = $('#education_type').val(); // Get latest value
            if (educationType === 'Other') {
                // Hide all semester groups
                $('.semester-group').addClass('d-none');

                // Remove all semester-related fields from validation
                for (let i = 1; i <= 10; i++) {
                    const regularField = `semester_${i}_result`;
                    const backlogField = `semester_${i}_backlog_result`;
                    const percentageField = `semester_${i}_percentage`;
                    const backlogPercentageField = `semester_${i}_backlog_percentage`;

                    if (FormValidation3.getFields()[regularField]) {
                        FormValidation3.removeField(regularField);
                    }
                    if (FormValidation3.getFields()[backlogField]) {
                        FormValidation3.removeField(backlogField);
                    }
                    if (FormValidation3.getFields()[percentageField]) {
                        FormValidation3.removeField(percentageField);
                    }
                    if (FormValidation3.getFields()[backlogPercentageField]) {
                        FormValidation3.removeField(backlogPercentageField);
                    }
                }
                return; // Exit early
            }
            const isBachelor = educationType === "Bachelor's Degree";
            const isMaster = educationType === "Master's Degree";
            var currentCourse = $('#course_id').val();
            var oldCourse = $('#old_course_id').val();
            var oldCourseEducationType = $('#old_course_education_type').val();
            const courseChanged = currentCourse !== oldCourse;

            if (isUpdatePage) {
                if (!courseChanged) {
                    addSscHscLeavingValidators();
                    if (currentSemester >= 1) {
                        for (let sem = 1; sem < currentSemester; sem++) {
                            if (sem % 2 === 1) {
                                showSemesterUpload(sem);
                                if (sem + 1 < currentSemester) {
                                    showSemesterUpload(sem + 1);
                                }
                            }
                        }
                    }
                }

                return; // Only return once after handling everything
            }

            // Always remove all semester validators first
            for (let i = 1; i <= 10; i++) { // Increased to 10 semesters
                const field = `semester_${i}_result`;
                if (FormValidation3.getFields()[field]) {
                    FormValidation3.removeField(field);
                }
            }
            $('.semester-group').addClass('d-none');
            // Hide all semester upload groups first


            if (currentSemester >= 1) {
                for (let sem = 1; sem <= currentSemester; sem++) {
                    showSemesterUpload(sem);
                }
            }

            if (isBachelor) {
                if (currentSemester === 0) {

                    // 1st year Bachelor: No semester uploads needed
                    addSscHscLeavingValidators();
                } else if (currentSemester === 1 || currentSemester === 2) {
                    if (oldCourseEducationType != 'HSC') {
                        showSemesterUpload(1);
                        showSemesterUpload(2);
                    }
                } else if (currentSemester === 3 || currentSemester === 4) {
                    // 3rd year Bachelor: Show Semester 3 and 4
                    showSemesterUpload(3);
                    showSemesterUpload(4);
                } else if (currentSemester === 5 || currentSemester === 6) {
                    // 3rd year Bachelor: Show Semester 5 and 6
                    showSemesterUpload(5);
                    showSemesterUpload(6);
                } else if (currentSemester === 7 || currentSemester === 8) {
                    // 4th year Bachelor: Show Semester 7 and 8
                    showSemesterUpload(7);
                    showSemesterUpload(8);
                } else if (currentSemester === 9 || currentSemester === 10) {
                    // 5th year Bachelor: Show Semester 9 and 10
                    showSemesterUpload(9);
                    showSemesterUpload(10);
                }
            } else if (isMaster && (currentCourse != oldCourse)) {
                // Master's degree with different course
                if (currentSemester === 0) {
                    // 1st year Master: No semester uploads needed
                    addSscHscLeavingValidators();
                } else if (currentSemester === 1 || currentSemester === 2) {
                    // Show Semester 1 and 2
                    showSemesterUpload(1);
                    showSemesterUpload(2);
                } else if (currentSemester === 3 || currentSemester === 4) {
                    // Show Semester 3 and 4
                    showSemesterUpload(3);
                    showSemesterUpload(4);
                } else if (currentSemester === 5 || currentSemester === 6) {
                    // Show Semester 5 and 6
                    showSemesterUpload(5);
                    showSemesterUpload(6);
                } else if (currentSemester === 7 || currentSemester === 8) {
                    // Show Semester 7 and 8
                    showSemesterUpload(7);
                    showSemesterUpload(8);
                } else if (currentSemester === 9 || currentSemester === 10) {
                    // Show Semester 9 and 10
                    showSemesterUpload(9);
                    showSemesterUpload(10);
                }
            } else if (isMaster && (currentCourse == oldCourse)) {
                // Master's degree with same course
                if (currentSemester === 0) {
                    // No semester uploads needed
                    addSscHscLeavingValidators();
                } else if (currentSemester === 1 || currentSemester === 2) {
                    // Show Semester 1 and 2
                    showSemesterUpload(1);
                    showSemesterUpload(2);
                }
            }
        } */

        /* function showSemesterUpload(semNumber) {
            const regularField = `semester_${semNumber}_result`;
            const backlogField = `semester_${semNumber}_backlog_result`;

            const regularInput = $(`#${regularField}_upload`).get(0);
            const backlogInput = document.getElementById(`sem${semNumber}_backlog_result_upload`);

            const regularFile = regularInput?.files?.length > 0;
            const backlogFile = backlogInput && backlogInput.files && backlogInput.files.length > 0;

            const existingRegularImg = $(`#semester${semNumber}Img`).attr('src');
            const existingBacklogImg = $(`#semester${semNumber}BacklogImg`).attr('src');

            $(`.semester-group[data-sem="${semNumber}"]`).removeClass('d-none').addClass('d-block');

            if (FormValidation3.getFields()[regularField]) {
                FormValidation3.removeField(regularField);
            }
            if (FormValidation3.getFields()[backlogField]) {
                FormValidation3.removeField(backlogField);
            }
            if (existingRegularImg || existingBacklogImg) {
                return;
            } else if (backlogFile) {
                FormValidation3.addField(backlogField, createFileValidator(`Please upload your Semester-${semNumber} backlog result.`));
            } else if (regularFile) {
                FormValidation3.addField(regularField, createFileValidator(`Please upload your Semester-${semNumber} result.`));
            } else {
                FormValidation3.addField(regularField, createFileValidator(`Please upload Semester-${semNumber} result or backlog.`));
            }
        } */
        function isBacklogModeActive() {
            return $('.degree-backlog_results').is(':visible');
        }

        const percentileValidator = {
            validators: {
                notEmpty: {
                    message: 'Please enter a percentage.'
                },
                numeric: {
                    message: 'Please enter a valid number.'
                },
                between: {
                    min: 0,
                    max: 100,
                    message: 'Percentage must be between 0 and 100.'
                }
            }
        };

        displaySemesterFields(semester);
        $('#semester').on('change', function () {
            const semester = parseInt($(this).val());
            displaySemesterFields(semester);

        });

        function displaySemesterFields(semester) {

            const excludedTypes = ['Job', 'Internship', 'Other'];
            const isJobOrInternship = excludedTypes.includes(education_type);

            if (!isJobOrInternship) {
                // Hide all semester groups and remove validation
                $('.semester-group').each(function () {
                    const sem = parseInt($(this).data('sem'));
                    $(this).removeClass('d-block').addClass('d-none');

                    const regularField = `semester_${sem}_result`;
                    const backlogField = `semester_${sem}_backlog_result`;
                    const percentageField = `semester_${sem}_percentage`;
                    const backlogPercentageField = `semester_${sem}_backlog_percentage`;

                    // Remove validation fields
                    if (FormValidation3.getFields()[regularField]) {
                        FormValidation3.removeField(regularField);
                    }
                    if (FormValidation3.getFields()[backlogField]) {
                        FormValidation3.removeField(backlogField);
                    }
                    if (FormValidation3.getFields()[percentageField]) {
                        FormValidation3.removeField(percentageField);
                    }
                    if (FormValidation3.getFields()[backlogPercentageField]) {
                        FormValidation3.removeField(backlogPercentageField);
                    }
                });

                // Show and validate semester fields from 1 to (semester - 1)
                for (let i = 1; i < semester; i++) {
                    if (i != (semester - 1)) {
                        showSemesterUpload(i, false, false);  // Required fields
                    } else {
                        showSemesterUpload(i, false, true);  // Required fields
                    }
                }
            }

        }

        function showSemesterUpload(semNumber, isBacklog = false, optional = false) {
            const oldCourseName = $('#old_course_name').val();
            const excludedTypes = ['Job', 'Internship'];
            const isJobOrInternship = excludedTypes.includes(education_type);

            if (oldCourseEducationType != 'HSC' && oldCourse != 27 && !isJobOrInternship) {
                const regularField = `semester_${semNumber}_result`;
                const backlogField = `semester_${semNumber}_backlog_result`;
                const percentageField = `semester_${semNumber}_percentage`; // Assume this is your percentage input field
                const backlogPercentageField = `semester_${semNumber}_backlog_percentage`; // Assume this is your percentage input field

                const regularInput = $(`#${regularField}_upload`).get(0);
                const backlogInput = $(`#${backlogField}_upload`).get(0);

                const regularFile = regularInput?.files?.length > 0;
                const backlogFiles = backlogInput?.files?.length > 0;
                const backlogFile = backlogInput && backlogInput.files && backlogInput.files.length > 0;

                const existingRegularImg = $(`#semester${semNumber}Img`).attr('src');
                const existingBacklogImg = $(`#semester${semNumber}BacklogImg`).attr('src');

                const regularPercentage = $(`#${percentageField}`).val();
                const backlogPercentage = $(`#${backlogPercentageField}`).val();

                $(`.semester-group[data-sem="${semNumber}"]`).removeClass('d-none').addClass('d-block');

                // Remove previous validators if any
                if (FormValidation3.getFields()[regularField]) {
                    FormValidation3.removeField(regularField);
                }
                if (FormValidation3.getFields()[backlogField]) {
                    FormValidation3.removeField(backlogField);
                }
                if (FormValidation3.getFields()[percentageField]) {
                    FormValidation3.removeField(percentageField);
                }
                if (FormValidation3.getFields()[backlogPercentageField]) {
                    FormValidation3.removeField(backlogPercentageField);
                }

                // Skip if existing images found
                if (existingRegularImg || /* existingBacklogImg || */ regularPercentage /* || backlogPercentage */) {
                    return;
                }

                // File upload validator
                const fileValidators = {
                    validators: {
                        ...(optional ? {} : {
                            notEmpty: {
                                message: `Please upload Semester-${semNumber} result or backlog ${oldCourseName}.`
                            }
                        }),
                        file: {
                            extension: 'jpg,jpeg,png',
                            type: 'image/jpeg,image/png',
                            message: 'Only image are allowed.'
                        }
                    }
                };
                const semPercentileValidator = {
                    validators: {
                        ...(optional ? {} : {
                            notEmpty: {
                                message: 'Please enter a percentage.'
                            }
                        }),
                        numeric: {
                            message: 'Please enter a valid number.'
                        },
                        between: {
                            min: 0,
                            max: 100,
                            message: 'Percentage must be between 0 and 100.'
                        }
                    }
                };

                // Apply file validators to appropriate field
                if (backlogFile || isBacklog) {
                    FormValidation3.addField(backlogField, fileValidators);
                    FormValidation3.addField(backlogPercentageField, semPercentileValidator);
                    if (FormValidation3.getFields()[percentageField]) {
                        FormValidation3.removeField(percentageField);
                    }
                } else if (regularFile) {
                    FormValidation3.addField(regularField, fileValidators);
                    // FormValidation3.removeField(backlogPercentageField, semPercentileValidator);
                    FormValidation3.addField(percentageField, semPercentileValidator);
                } else {
                    FormValidation3.addField(regularField, fileValidators);
                    // FormValidation3.removeField(backlogPercentageField, semPercentileValidator);
                    FormValidation3.addField(percentageField, semPercentileValidator);
                }
            }
        }

        function showCAUpload() {
            const ipccResultField = 'ipcc_result';
            const ipccBacklogField = 'ipcc_backlog_result';
            const cptResultField = 'cpt_result';
            const cptBacklogField = 'cpt_backlog_result';
            const ca_finalResultField = 'ca_final_result';
            const ca_finalBacklogField = 'ca_final_backlog_result';

            const ipccResultPercentage = 'ipcc_percentage';
            const ipccBacklogPercentage = 'ipcc_backlog_percentage';
            const cptResultPercentage = 'cpt_percentage';
            const cptBacklogPercentage = 'cpt_backlog_percentage';
            const ca_finalResultPercentage = 'ca_final_percentage';
            const ca_finalBacklogPercentage = 'ca_final_backlog_percentage';

            const ipccImg = $('#ipccImg').attr('src') || '';
            const cptImg = $('#cptImg').attr('src') || '';
            const ca_finalImg = $('#ca_finalImg').attr('src') || '';

            const ipccResultInput = $(`#${ipccResultField}_upload`).get(0);
            const ipccBacklogInput = document.getElementById(`${ipccBacklogField}_upload`);
            const cptResultInput = $(`#${cptResultField}_upload`).get(0);
            const cptBacklogInput = document.getElementById(`${cptBacklogField}_upload`);
            const ca_finalResultInput = $(`#${ca_finalResultField}_upload`).get(0);
            const ca_finalBacklogInput = document.getElementById(`${ca_finalBacklogField}_upload`);

            const ipccResultFile = ipccResultInput?.files?.length > 0;
            const ipccBacklogFile = ipccBacklogInput && ipccBacklogInput.files && ipccBacklogInput.files.length > 0;
            const cptResultFile = cptResultInput?.files?.length > 0;
            const cptBacklogFile = cptBacklogInput && cptBacklogInput.files && cptBacklogInput.files.length > 0;
            const ca_finalResultFile = ca_finalResultInput?.files?.length > 0;
            const ca_finalBacklogFile = ca_finalBacklogInput && ca_finalBacklogInput.files && ca_finalBacklogInput.files.length > 0;

            // Show the CA upload group if it's not shown yet
            $('.ca-upload-group').removeClass('d-none').addClass('d-block');

            // Cleanup existing validations
            [ipccResultField, ipccBacklogField, cptResultField, cptBacklogField, ca_finalResultField, ca_finalBacklogField, ipccResultPercentage, ipccBacklogPercentage, cptResultPercentage, cptBacklogPercentage, ca_finalResultPercentage, ca_finalBacklogPercentage].forEach(field => {
                if (FormValidation3.getFields()[field]) {
                    FormValidation3.removeField(field);
                }
            });

            if (ipccBacklogFile) {
                if (FormValidation3.getFields()[ipccResultField]) {
                    FormValidation3.removeField(ipccResultField);
                }
                if (FormValidation3.getFields()[ipccBacklogField]) {
                    FormValidation3.removeField(ipccBacklogField);
                }
            } else if (!ipccImg || !ipccResultFile) {
                FormValidation3.addField('ipcc_result', !ipccImg ? createFileValidator('Please upload your IPCC result.') : '');
                FormValidation3.addField('ipcc_percentage', !ipccImg ? createFileValidator('Please enter your IPCC percentile.') : '');
            }

            if (ipccImg) {
                if (cptImg || cptBacklogFile) {
                    if (FormValidation3.getFields()[cptResultField]) {
                        FormValidation3.removeField(cptResultField);
                    }
                    if (FormValidation3.getFields()[cptBacklogField]) {
                        FormValidation3.removeField(cptBacklogField);
                    }
                } else if (!cptImg || !cptResultFile) {
                    // FormValidation3.addField(cptResultField, createFileValidator('Please upload your CPT result.'));
                    FormValidation3.addField(cptResultField, !cptImg ? createFileValidator('Please upload your CPT result.') : '');
                    FormValidation3.addField(cptResultPercentage, !cptImg ? createFileValidator('Please enter your CPT percentile.') : '');
                }
            }

            if (ipccImg && cptImg) {
                if (ca_finalImg || ca_finalBacklogFile) {
                    if (FormValidation3.getFields()[ca_finalResultField]) {
                        FormValidation3.removeField(ca_finalResultField);
                    }
                    if (FormValidation3.getFields()[ca_finalBacklogField]) {
                        FormValidation3.removeField(ca_finalBacklogField);
                    }
                } else if (!ca_finalImg || !ca_finalResultFile) {
                    // FormValidation3.addField(ca_finalResultField, createFileValidator('Please upload your ca_final result.'));
                    FormValidation3.addField(ca_finalResultField, !ca_finalImg ? createFileValidator('Please upload your CA Final result.') : '');
                    FormValidation3.addField(ca_finalResultPercentage, !ca_finalImg ? createFileValidator('Please enter your CA Final percentile.') : '');
                }
            }
        }

        function addSscHscLeavingValidators() {
            ['ssc_result', 'hsc_result', 'leaving_certificate'].forEach(field => {
                if (!FormValidation3.getFields()[field]) {
                    FormValidation3.addField(field, validationRules[field]);
                }
            });
        }

        // Course selection change handler
        $('#course_id').on('change', function () {
            const course_id = $(this).val();
            $('#course_id').prop('disabled', false);

            if (course_id > 0) {
                getCourseList(course_id);
            }

            var currentCourse = $('#course_id').val();

            if (oldCourse != 0 && (currentCourse != oldCourse) && !isNewStudent) {
                $.ajax({
                    type: "get",
                    url: "/students/getCoursesById",
                    data: { course_id: oldCourse },
                    cache: false,
                    success: function (response) {
                        var course = response.course;
                        displaySemesterFields((course.duration * 2));
                    }
                });
            }
        });

        const validationRules = {
            ssc_result: (sscImg == null) ? createFileValidator('Please upload your SSC result.') : '',
            hsc_result: (hscImg == null) ? createFileValidator('Please upload your HSC result.') : '',
            leaving_certificate: (leaving_certificateImg == null) ? createFileValidator('Please upload your leaving certificate.') : '',
            last_qualification_result: (last_qualificationImg == null) ? createFileValidator('Please upload your last qualification result.') : '',
            degree_certificate: (degree_certificateImg == null) ? createFileValidator('Please upload your degree certificate.') : '',
            internship_letter: (internship_letterImg == null) ? createFileValidator('Please upload your internship letter.') : '',
            job_letter: (job_letterImg == null) ? createFileValidator('Please upload your job letter.') : '',
            ipcc_result: createFileValidator('Please upload your ipcc result.'),
            cpt_result: createFileValidator('Please upload your cpt result.'),
            ca_final_result: createFileValidator('Please upload your ca_final result.'),
            ssc_percentage: percentileValidator,
            hsc_percentage: percentileValidator,
            last_qualification_percentage: percentileValidator,
            ipcc_percentage: percentileValidator,
            cpt_percentage: percentileValidator,
            ca_final_percentage: percentileValidator,
        };

        const docClasses = [
            'ssc-doc', 'hsc-doc', 'last_qualification-doc',
            'degree_certificate-doc', 'degree-results', 'ca-results', 'internship_letter-doc', 'job_letter-doc'
        ];

        const educationDocMap = {
            // HSC: (board_type) => board_type === 'Other' ? ['last_qualification_result', 'last_qualification_percentage', 'leaving_certificate'] : ['ssc_result', 'ssc_percentage', 'leaving_certificate'],
            HSC: ['last_qualification_result', 'last_qualification_percentage', 'leaving_certificate'],
            Diploma: (board_type) => board_type === 'Other' ? ['last_qualification_result', 'last_qualification_percentage', 'leaving_certificate'] : ['ssc_result', 'ssc_percentage', 'hsc_result', 'hsc_percentage', 'leaving_certificate'],
            "Bachelor's Degree": (board_type) => board_type === 'Other' ? ['last_qualification_result', 'last_qualification_percentage', 'leaving_certificate'] : ['ssc_result', 'ssc_percentage', 'hsc_result', 'hsc_percentage', 'leaving_certificate'],
            "Master's Degree": (board_type) => board_type === 'Other' ? ['last_qualification_result', 'last_qualification_percentage', 'leaving_certificate', 'degree_certificate'] : ['ssc_result', 'ssc_percentage', 'hsc_result', 'hsc_percentage', 'leaving_certificate', 'last_qualification_result', 'last_qualification_percentage', 'degree_certificate'],
            // "Professional Degree": (board_type) => board_type === 'Other' ? ['last_qualification_result', 'last_qualification_percentage', 'leaving_certificate', 'degree_certificate'] : ['ssc_result', 'ssc_percentage', 'hsc_result', 'hsc_percentage', 'leaving_certificate', 'last_qualification_result', 'last_qualification_percentage', 'degree_certificate'],
            "Professional Degree": (board_type) => board_type === 'Other' ? ['last_qualification_result', 'last_qualification_percentage', 'leaving_certificate'] : ['ssc_result', 'ssc_percentage', 'hsc_result', 'hsc_percentage', 'leaving_certificate'],
            Internship: (board_type) => board_type === 'Other' ? ['last_qualification_result', 'last_qualification_percentage', 'leaving_certificate', 'degree_certificate', 'internship_letter'] : ['ssc_result', 'ssc_percentage', 'hsc_result', 'hsc_percentage', 'leaving_certificate', 'last_qualification_result', 'last_qualification_percentage', 'degree_certificate', 'internship_letter'],
            Job: (board_type) => board_type === 'Other' ? ['last_qualification_result', 'last_qualification_percentage', 'leaving_certificate', 'degree_certificate', 'job_letter'] : ['ssc_result', 'ssc_percentage', 'hsc_result', 'hsc_percentage', 'leaving_certificate', 'last_qualification_result', 'last_qualification_percentage', 'degree_certificate', 'job_letter'],
            Other: (board_type) => ['last_qualification_result', 'last_qualification_percentage'],
        };

        // Function to show/hide document sections
        function toggleDocs(fields) {
            docClasses.forEach(docClass => {
                if (fields.some(f => docClass.includes(f.split('_')[0]))) {
                    $(`.${docClass}`).removeClass('d-none');
                } else {
                    $(`.${docClass}`).addClass('d-none');
                }
            });
        }

        // Clear existing fields
        function resetValidation() {
            Object.keys(validationRules).forEach(field => {
                if (FormValidation3.getFields()[field]) {
                    FormValidation3.removeField(field);
                }
            });
        }

        validateEducationDocuments(education_type, board_type, oldCourseEducationType);

        $('#education_type').on('change', function () {
            const education_type = $(this).val();
            getCoursesByEducation(education_type);

        });
        // Handle change event
        $('#education_type, #board_type').on('change', function () {
            const education_type = $('#education_type').val();
            var board_type = $('#board_type').val();
            // validateEducationDocuments(education_type, board_type, oldCourseEducationType);
            // Delay validation until DOM updates & image/file is actually uploaded
            setTimeout(() => {
                validateEducationDocuments(education_type, board_type, oldCourseEducationType);
            }, 300);
        });

        $('#ipcc_backlog_result').on('change', function () {
            setTimeout(() => {
                validateEducationDocuments($('#education_type').val(), $('#board_type').val(), $('#old_course_type').val());
            }, 300); // small delay to allow file/image preview to appear
        });

        function validateEducationDocuments(education_type, board_type, oldCourseEducationType) {
            resetValidation();

            const excludedTypes = ['Job', 'Internship', 'Other'];
            const isJobOrInternship = excludedTypes.includes(education_type);

            const docRule = educationDocMap[education_type];

            const requiredFields = typeof docRule === 'function' ? docRule(board_type) : (docRule || []);

            toggleDocs(requiredFields);

            /* requiredFields.forEach(field => {
                FormValidation3.addField(field, validationRules[field]);
            }); */

            // Apply validators only if not 'Job'
            if (education_type !== 'Job') {
                requiredFields.forEach(field => {
                    FormValidation3.addField(field, validationRules[field]);
                });
            } else {
                // Still show fields, but remove their validation if previously added
                requiredFields.forEach(field => {
                    if (FormValidation3.getFields()[field]) {
                        FormValidation3.removeField(field);
                    }
                });
            }

            if (isJobOrInternship) {
                $('.semester-group').addClass('d-none');
                $('.degree-results').addClass('d-none');
                $('.ca-results').addClass('d-none');
                $('.ca-backlog-results').addClass('d-none');
                $('.backlog-option').addClass('d-none');

                // Special handling for 'Other' type
                if (education_type === 'Other') {
                    $('.otherDocs').removeClass('d-none').addClass('d-block');

                    // Make sure we add the required fields for 'Other' type
                    const docRule = educationDocMap[education_type];
                    const requiredFields = typeof docRule === 'function' ? docRule(board_type) : (docRule || []);

                    toggleDocs(requiredFields);

                    requiredFields.forEach(field => {
                        FormValidation3.addField(field, validationRules[field]);
                    });

                    return; // Exit early to prevent other validations from being applied
                }
            }

            // Degree results visibility is controlled by student type
            if (studentNew === 'true') {
                $('.degree-results').removeClass('d-none');
                $('.ca-results').addClass('d-none');
                if (isJobOrInternship) {
                    $('.backlog-option').addClass('d-none');
                } else {
                    $('.backlog-option').removeClass('d-none');
                }
                $('.ca-backlog-results').addClass('d-none');
            } else {
                if (oldCourseEducationType == 'HSC') {
                    $('.degree-results').addClass('d-none');
                    $('.backlog-option').addClass('d-none');
                    $('.ca-results').addClass('d-none');
                    $('.ca-backlog-results').addClass('d-none');
                    [
                        'ipcc_result', 'cpt_result', 'ca_final_result',
                        'ipcc_percentage', 'cpt_percentage', 'ca_final_percentage',
                        'ipcc_backlog_result', 'cpt_backlog_result', 'ca_final_backlog_result',
                    ].forEach(field => {
                        if (FormValidation3.getFields()[field]) {
                            FormValidation3.removeField(field);
                        }
                    });
                } else {
                    $('.degree-results').removeClass('d-none');
                    $('.backlog-option').removeClass('d-none');

                    if (parseInt($('#old_course_id').val()) == 27) {
                        $('.ca-results').removeClass('d-none');
                        $('.ca-backlog-results').addClass('d-none');
                        showCAUpload();
                    } else {
                        $('.ca-results').addClass('d-none');
                        $('.ca-backlog-results').addClass('d-none');

                        [
                            'ipcc_result', 'cpt_result', 'ca_final_result',
                            'ipcc_backlog_result', 'cpt_backlog_result', 'ca_final_backlog_result'
                        ].forEach(field => {
                            if (FormValidation3.getFields()[field]) {
                                FormValidation3.removeField(field);
                            }
                        });
                    }
                }
            }
        }

        const backlog = $('input[name="having_any_backlog"]:checked').val();
        displayBacklogResultFields(backlog, oldCourse);

        $('input[name="having_any_backlog"]').on('change', function () {
            const backlog = $('input[name="having_any_backlog"]:checked').val();
            displayBacklogResultFields(backlog, oldCourse);
        });

        function displayBacklogResultFields(backlog, oldCourse) {
            if (backlog === 'true') {
                if (oldCourse == 27) {
                    $('.degree-results').addClass('d-none');
                    $('.degree-backlog_results').addClass('d-none');
                    $('.ca-backlog-results').removeClass('d-none');
                    $('.ca-results').addClass('d-none');
                } else {
                    $('.degree-results').addClass('d-none');
                    $('.degree-backlog_results').removeClass('d-none');
                    $('.ca-backlog-results').addClass('d-none');
                    $('.ca-results').addClass('d-none');
                }
            } else {
                if (oldCourse == 27) {
                    $('.degree-backlog_results').addClass('d-none');
                    $('.degree-results').addClass('d-none');
                    $('.ca-backlog-results').addClass('d-none');
                    $('.ca-results').removeClass('d-none');
                } else {
                    $('.degree-backlog_results').addClass('d-none');
                    $('.degree-results').removeClass('d-none');
                    $('.ca-backlog-results').addClass('d-none');
                    $('.ca-results').addClass('d-none');
                }
            }
        }

        // Run on page load (in case of pre-filled value)
        $('#education_type').trigger('change');

        // Price Details
        const FormValidation4 = FormValidation.formValidation(admissonFormValStep4, {
            fields: {
                chk_declaration: {
                    validators: {
                        notEmpty: {
                            message: 'Please confirm our T&C'
                        }
                    }
                }
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap5: new FormValidation.plugins.Bootstrap5({
                    // Use this for enabling/changing valid/invalid class
                    // eleInvalidClass: '',
                    eleValidClass: '',
                    rowSelector: '.col, .checkbox_field_box'
                }),
                autoFocus: new FormValidation.plugins.AutoFocus(),
                submitButton: new FormValidation.plugins.SubmitButton()
            }
        }).on('core.form.valid', function () {
            const $form = $('#admission_form');
            const $submitBtn = $form.find('button[type="submit"]');

            const originalBtnText = $submitBtn.html();

            $form.on('submit', function (e) {
                $submitBtn.prop('disabled', true);
                $submitBtn.html('Processing...');

                setTimeout(function () {
                    $submitBtn.prop('disabled', false);
                    $submitBtn.html(originalBtnText);
                }, 60000);
            });

            if ($('.alert-danger').length > 0 || $('.invalid-feedback:visible').length > 0 || $('[data-error-message]').length > 0) {
                $submitBtn.prop('disabled', false);
                $submitBtn.html(originalBtnText);
            }
            console.log('Submitted..!!');
        });

        admissonFormNext.forEach(item => {
            item.addEventListener('click', event => {
                // When click the Next button, we will validate the current step
                switch (validationStepper._currentIndex) {
                    case 0:
                        FormValidation1.validate();
                        break;

                    case 1:
                        FormValidation2.validate();
                        break;

                    case 2:
                        if (!isUpdatePage) {
                            // Skip semester validation for 'Other' education type
                            const educationType = $('#education_type').val();
                            if (educationType !== 'Other') {
                                const isBacklog = isBacklogModeActive();
                                $('.semester-group:visible').each(function () {
                                    const semNumber = $(this).data('sem');
                                    const semester = $('#semester').val();;
                                    if (semNumber != (parseInt(semester) - 1)) {
                                        if (semNumber) showSemesterUpload(semNumber, isBacklog);
                                    } else {
                                        if (semNumber) showSemesterUpload(semNumber, isBacklog, true);
                                    }
                                });
                                if (parseInt($('#old_course_id').val()) == 27) {
                                    showCAUpload();
                                }
                            }
                        }
                        FormValidation3.validate();
                        break;

                    case 3:
                        FormValidation4.validate();
                        break;

                    default:
                        break;
                }
            });
        });

        admissonFormPrev.forEach(item => {
            item.addEventListener('click', event => {
                switch (validationStepper._currentIndex) {
                    case 3:
                        validationStepper.previous();
                        break;

                    case 2:
                        validationStepper.previous();
                        break;

                    case 1:
                        validationStepper.previous();
                        break;

                    case 0:

                    default:
                        break;
                }
            });
        });
    }
})();

$(document).ready(function () {
    $('.btn-reset').click(function (e) {
        e.preventDefault();
        var element = $(this).closest('div').parent('div').attr('id');

        if (element == 'personal-details') {
            $("#dob").val('');
            $("#residence_address").val('');
            $("#country_id").val('').trigger('change');
            $("#citizen_yes").prop("checked", true);
            $("#citizen_no").prop("checked", false);
            $('#passport-photo-label').text('Upload Passport Size Photo');
            $('#passport_photoimage').val('');
            $("#passport_photoImage").attr("src", "");
            $("#passport_photoImage").css("display", "none");
            $("#passport_photo_upload").files.length;
            $("#illness_yes").prop("checked", false);
            $("#illness_no").prop("checked", true);
            $("#illness_desc").val('');
            $("#vehicle_yes").prop("checked", true);
            $("#vehicle_no").prop("checked", false);
            $("#vehicle_number").val('');
        } else if (element == 'family-details') {
            $("#father_full_name").val('');
            $("#father_phone").val('');
            $("#father_occupation").val('');
            $("#mother_full_name").val('');
            $("#mother_phone").val('');
            $("#mother_occupation").val('');
            $("#annual_income").val('');
            $("#local_guardian_yes").prop("checked", false);
            $("#local_guardian_no").prop("checked", true);
            $("#guardian_name").val('');
            $("#guardian_relation").val('');
            $("#guardian_phone").val('');
            $("#nationality_indian_yes").prop("checked", true);
            $("#nationality_indian_no").prop("checked", false);
        } else if (element == 'education-details') {
            $("#education_type").val("").trigger('change');
            $("#course_id").val("").trigger('change');
            $("#institute_name").val('');
            $("#addmission_date").val('');
            $("#year_of_addmission").val('').trigger('change');
            $("#semester").val('0').trigger('change');
            $("#college_start_time").val('');
            $("#college_end_time").val('');
            $("#arriving_date").val('');
            $("#college_fees_receipt_no").val('');
            $("#college_fees_receipt_date").val('');
        } else {
            $("#chk_declaration").prop('checked', false);
            $("#additional_notes").val('');
        }
    });
});
