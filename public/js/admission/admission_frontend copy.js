'use strict';
$('#addmission_date, #college_fees_receipt_date, #arriving_date').datepicker({
    dateFormat: 'dd/mm/yy',
});

function updateFileNameSwap(input, targetId) {
    const label = document.getElementById(targetId);
    const defaultText = label.getAttribute('data-default') || 'Upload'; // Default text stored here
    const fileName = input.files.length > 0 ? input.files[0].name : defaultText;
    label.textContent = fileName;
}

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
    // getCoursesByEducation(education_type);
    displaySemester(education_type);
});

var education_type = $('#education_type').val();
// getCoursesByEducation(education_type);
displaySemester(education_type);

/* function getCoursesByEducation(education_type) {
    $.ajax({
        type: "get",
        url: "/students/getCoursesByEducation",
        data: { education_type: education_type },
        cache: false,
        success: function (response) {
            const courses = response.courses || [];
            const oldCourse = $('#old_course_id').val();
            let coursehtml = '<option value="0">Select Course</option>';

            $.each(courses, function (indexInArray, course) {
                const selected = (course.id == oldCourse) ? ' selected' : '';
                coursehtml += `<option value="${course.id}"${selected}>${course.course_name}</option>`;
            });

            $("#course_id").html(coursehtml);


        }
    });
} */

function displaySemester(education_type) {
    if (education_type == "HSC" || education_type == "Bachelor's Degree" || education_type == "Master's Degree") {
        $('.semester-div').removeClass('d-none');
    } else {
        $('.semester-div').addClass('d-none');
    }
}

(function () {
    const admissonForm = document.querySelector('#admission_form');
    window.isUpdateMode = $('#form_mode').val() == 'update';

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
                        regexp: /^[a-zA-Z0-9]+$/,
                        message: 'Vehicle number can only contain letters and numbers.'
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
        // Property Area
        const FormValidation3 = FormValidation.formValidation(admissonFormValStep3, {
            on: 'submit',
            fields: {
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
                            message: 'Please select a course.'
                        },
                        callback: {
                            message: 'You have already completed this course. Please select a different one.',
                            callback: function (input) {
                                const educationType = $('#education_type').val();
                                const excludedTypes = ['Job', 'Internship'];

                                // Skip this validation for Job or Internship
                                if (excludedTypes.includes(educationType)) {
                                    return true;
                                }

                                // Skip validation for new students or if in update mode
                                if (window.isNewStudent === 'true' || window.isUpdateMode) {
                                    return true;
                                }

                                const selectedCourseId = input.value;
                                const completed = window.completedCourseYears?.[selectedCourseId] || 0;
                                const duration = window.courseDurations?.[selectedCourseId] || 0;
                                console.log('duration', duration);

                                return completed < duration;
                            }
                        }
                    }
                },
                board_type: {
                    validators: {
                        notEmpty: {
                            message: 'Please select the board type.'
                        }
                    }
                },
                /* semester: {
                    validators: {
                        notEmpty: {
                            message: 'Please select a semester.'
                        }
                    }
                }, */
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
                institute_name: {
                    validators: {
                        notEmpty: {
                            message: 'Please enter the institute name.'
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
                addmission_date: {
                    validators: {
                        notEmpty: {
                            message: 'Please select the admission date.'
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
            },
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
        });

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

        /* var course_id = $('#course_id').val();
        getCourseList(course_id);

        $('#course_id').on('change', function () {
            var course_id = $(this).val();
            getCourseList(course_id);
        }); */

        const education_type = $('#education_type').val();
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
                    let coursehtml = '<option value="0">Select Course</option>';

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
                    console.log('course_year', course_year);


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

                    var totalSemesters = course_year * 2;

                    console.log('totalSemesters', totalSemesters);


                    // Hide all semester groups first
                    $('.semester-group').addClass('d-none');

                    // Show semester groups based on course duration
                    for (let i = 1; i <= totalSemesters; i++) {
                        $(`.semester-group[data-sem="${i}"]`).removeClass('d-none');
                    }

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
                    window.isNewStudent === 'false' && (last_course && education_type != 'Professional Degree') ? updateSemesterValidators(parseInt(last_semester) + 1) : updateSemesterValidators(0);

                    $("#semester").html('');
                    var semesterhtml = "";
                    for (let i = 1; i <= totalSemesters; i++) {
                        semesterhtml += '<option value="' + i + '"' + ((current_semester > 0 && current_semester == i && course_id == last_course) ? ' selected' : '') + '>' + i + '</option>';
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

        /* function updateSemesterValidators(totalSemesters) {
            for (let i = 1; i <= 6; i++) {
                const field = `semester_${i}_result`;
                if (FormValidation3.getFields()[field]) {
                    FormValidation3.removeField(field);
                }

                if (i <= totalSemesters) {
                    var semesterVal = $('#semester' + i + 'Img').attr('src');
                    FormValidation3.addField(field, semesterVal == null ? createFileValidator(`Please upload your Semester-${i} result.`) : '');
                }
            }
        } */

        const formMode = $('#form_mode').val();
        var isUpdatePage = formMode === 'update';
        // On update, display only existing uploads and skip validators
        function displayExistingSemesterResults() {
            for (let i = 1; i <= 10; i++) {
                const imgSrc = $(`#semester${i}Img`).attr('src');
                if (imgSrc) {
                    $(`.semester-group[data-sem="${i}"]`).removeClass('d-none').addClass('d-block');
                }
            }
        }
        var oldCourseEducationType = $('#old_course_education_type').val();

        function updateSemesterValidators(currentSemester) {
            const educationType = $('#education_type').val(); // Get latest value
            const isBachelor = educationType === "Bachelor's Degree";
            const isMaster = educationType === "Master's Degree";
            var currentCourse = $('#course_id').val();
            var oldCourse = $('#old_course_id').val();
            var oldCourseEducationType = $('#old_course_education_type').val();
            const courseChanged = currentCourse !== oldCourse;

            if (isUpdatePage) {
                displayExistingSemesterResults();
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
                            // showPreviousSemesters(currentSemester);
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

            // Hide all semester upload groups first
            $('.semester-group').addClass('d-none');

            if (isBachelor) {
                console.log('currentSemester', currentSemester);
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
        }

        function showSemesterUpload(semNumber) {
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
        }

        function showPreviousSemesters(currentSemester) {
            // Hide all semester groups first
            $('.semester-group').addClass('d-none');

            for (let sem = 1; sem < currentSemester; sem++) {
                showSemesterUpload(sem);
            }
        }


        function addSscHscLeavingValidators() {
            ['ssc_result', 'hsc_result', 'leaving_certificate'].forEach(field => {
                if (!FormValidation3.getFields()[field]) {
                    FormValidation3.addField(field, validationRules[field]);
                }
            });
        }

        // Initial course list fetch
        /* const initialCourseId = $('#course_id').val();
        console.log('initialCourseId', initialCourseId);
        if (parseInt(initialCourseId) > 0) {
            console.log('initialCourseId', initialCourseId);

            getCourseList(initialCourseId);
        } */

        // Course selection change handler
        $('#course_id').on('change', function () {
            const course_id = $(this).val();
            if (course_id > 0) {
                getCourseList(course_id);
            }
        });

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
        };


        const docClasses = [
            'ssc-doc', 'hsc-doc', 'last_qualification-doc',
            'degree_certificate-docs', 'degree-results', 'ca-results', 'internship_letter-doc', 'job_letter-doc'
        ];

        const educationDocMap = {
            HSC: (board_type) => board_type === 'Other' ? ['last_qualification_result', 'last_qualification_percentage', 'leaving_certificate'] : ['ssc_result', 'ssc_percentage', 'leaving_certificate'],
            Diploma: (board_type) => board_type === 'Other' ? ['last_qualification_result', 'last_qualification_percentage', 'leaving_certificate'] : ['ssc_result', 'ssc_percentage', 'hsc_result', 'hsc_percentage', 'leaving_certificate'],
            "Bachelor's Degree": (board_type) => board_type === 'Other' ? ['last_qualification_result', 'last_qualification_percentage', 'leaving_certificate'] : ['ssc_result', 'ssc_percentage', 'hsc_result', 'hsc_percentage', 'leaving_certificate'],
            "Master's Degree": (board_type) => board_type === 'Other' ? ['last_qualification_result', 'last_qualification_percentage', 'leaving_certificate', 'degree_certificate'] : ['ssc_result', 'ssc_percentage', 'hsc_result', 'hsc_percentage', 'leaving_certificate', 'last_qualification_result', 'last_qualification_percentage', 'degree_certificate'],
            "Master's Degree": (board_type) => board_type === 'Other' ? ['last_qualification_result', 'last_qualification_percentage', 'leaving_certificate', 'degree_certificate'] : ['ssc_result', 'ssc_percentage', 'hsc_result', 'hsc_percentage', 'leaving_certificate', 'last_qualification_result', 'last_qualification_percentage', 'degree_certificate'],
            "Professional Degree": (board_type) => board_type === 'Other' ? ['last_qualification_result', 'last_qualification_percentage', 'leaving_certificate', 'degree_certificate'] : ['ssc_result', 'ssc_percentage', 'hsc_result', 'hsc_percentage', 'leaving_certificate', 'last_qualification_result', 'last_qualification_percentage', 'degree_certificate'],
            Internship: (board_type) => board_type === 'Other' ? ['last_qualification_result', 'last_qualification_percentage', 'leaving_certificate', 'degree_certificate', 'internship_letter'] : ['ssc_result', 'ssc_percentage', 'hsc_result', 'hsc_percentage', 'leaving_certificate', 'last_qualification_result', 'last_qualification_percentage', 'degree_certificate', 'internship_letter'],
            Job: (board_type) => board_type === 'Other' ? ['last_qualification_result', 'last_qualification_percentage', 'leaving_certificate', 'degree_certificate', 'job_letter'] : ['ssc_result', 'ssc_percentage', 'hsc_result', 'hsc_percentage', 'leaving_certificate', 'last_qualification_result', 'last_qualification_percentage', 'degree_certificate', 'job_letter'],
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

        // Handle change event
        $('#education_type, #board_type').on('change', function () {
            const education_type = $('#education_type').val();
            var board_type = $('#board_type').val();
            validateEducationDocuments(education_type, board_type, oldCourseEducationType);
            getCoursesByEducation(education_type);
        });

        function validateEducationDocuments(education_type, board_type, oldCourseEducationType) {

            resetValidation();

            const excludedTypes = ['Job', 'Internship'];
            const isJobOrInternship = excludedTypes.includes(education_type);

            if (isJobOrInternship) {
                console.log('education_type ==> ', education_type);

                $('.semester-group').addClass('d-none');
                $('.degree-results').addClass('d-none');
                $('.ca-results').addClass('d-none');
                $('.ca-backlog-results').addClass('d-none');
                $('.backlog-option').addClass('d-none');
            }

            // const requiredFields = educationDocMap[education_type] || [];
            const docRule = educationDocMap[education_type];
            const requiredFields = typeof docRule === 'function' ? docRule(board_type) : (docRule || []);

            toggleDocs(requiredFields);

            requiredFields.forEach(field => {
                FormValidation3.addField(field, validationRules[field]);
            });

            // Degree results visibility is controlled by student type

            if (studentNew === 'true') {
                $('.degree-results').addClass('d-none');
                $('.ca-results').addClass('d-none');
                $('.backlog-option').addClass('d-none');
                $('.ca-backlog-results').addClass('d-none');
            } else {
                /* if (oldCourseEducationType == 'HSC') {
                    $('.degree-results').addClass('d-none');
                    $('.backlog-option').addClass('d-none');
                    $('.ca-backlog-results').addClass('d-none');
                } else {

                    $('.degree-results').removeClass('d-none');
                    $('.backlog-option').removeClass('d-none');
                    $('.ca-backlog-results').addClass('d-none');

                    if (education_type === 'Professional Degree') {
                        $('.ca-results').removeClass('d-none');

                        const ipccImg = $('#ipccImg').attr('src') || '';
                        const cptImg = $('#cptImg').attr('src') || '';
                        const ca_finalImg = $('#ca_finalImg').attr('src') || '';

                        // Remove fields before re-adding
                        ['ipcc_result', 'cpt_result', 'ca_final_result'].forEach(field => {
                            if (FormValidation3.getFields()[field]) {
                                FormValidation3.removeField(field);
                            }
                        });

                        // Always require IPCC result
                        FormValidation3.addField('ipcc_result', !ipccImg ? createFileValidator('Please upload your IPCC result.') : '');

                        // If IPCC uploaded, require CPT
                        if (ipccImg) {
                            FormValidation3.addField('cpt_result', !cptImg ? createFileValidator('Please upload your CPT result.') : '');
                        }

                        // If CPT uploaded, require CA Final
                        if (cptImg) {
                            FormValidation3.addField('ca_final_result', !ca_finalImg ? createFileValidator('Please upload your CA Final result.') : '');
                        }

                    } else {
                        $('.ca-results').addClass('d-none');

                        // Always clean up unused CA fields
                        ['ipcc_result', 'cpt_result', 'ca_final_result'].forEach(field => {
                            if (FormValidation3.getFields()[field]) {
                                FormValidation3.removeField(field);
                            }
                        });
                    }
                }
            } */

                /* if (oldCourseEducationType === 'HSC') {
                    $('.degree-results').addClass('d-none');
                    $('.backlog-option').addClass('d-none');
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

                } else {
                    $('.degree-results').removeClass('d-none');
                    $('.backlog-option').removeClass('d-none');

                    if (education_type === 'Professional Degree') {
                        $('.ca-results').removeClass('d-none');
                        $('.ca-backlog-results').removeClass('d-none');

                        const pairs = [
                            { main: 'ipcc_result', backlog: 'ipcc_backlog_result', mainImg: 'ipccImg', backlogImg: 'ipccBacklogImg' },
                            { main: 'cpt_result', backlog: 'cpt_backlog_result', mainImg: 'cptImg', backlogImg: 'cptBacklogImg' },
                            { main: 'ca_final_result', backlog: 'ca_final_backlog_result', mainImg: 'ca_finalImg', backlogImg: 'caFinalBacklogImg' },
                        ];

                        // Remove all existing validations first
                        pairs.forEach(({ main, backlog }) => {
                            if (FormValidation3.getFields()[main]) FormValidation3.removeField(main);
                            if (FormValidation3.getFields()[backlog]) FormValidation3.removeField(backlog);
                        });

                        pairs.forEach(({ main, backlog, mainImg, backlogImg }) => {
                            const mainInput = document.getElementById(`${main}_upload`);
                            const backlogInput = document.getElementById(`${backlog}_upload`);

                            const hasMainFile = mainInput?.files?.length > 0;
                            const hasBacklogFile = backlogInput?.files?.length > 0;

                            const hasMainImg = $(`#${mainImg}`).attr('src') || '';
                            const hasBacklogImg = $(`#${backlogImg}`).attr('src') || '';

                            const mainRequired = !hasMainFile && !hasMainImg && !hasBacklogFile && !hasBacklogImg;
                            const backlogRequired = !hasBacklogFile && !hasBacklogImg && !hasMainFile && !hasMainImg;

                            // If neither main nor backlog is uploaded, require one (main by default)
                            if (mainRequired) {
                                FormValidation3.addField(main, createFileValidator(`Please upload your ${main.replace(/_/g, ' ').toUpperCase()}.`));
                            }

                            // If user uploaded backlog only, validate it
                            if (!hasMainFile && !hasMainImg && (hasBacklogFile || hasBacklogImg)) {
                                FormValidation3.addField(backlog, createFileValidator(`Please upload your ${backlog.replace(/_/g, ' ').toUpperCase()}.`));
                            }
                        });

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
                } */
        }

        const backlog = $('input[name="having_any_backlog"]:checked').val();
        displayBacklogResultFields(backlog);

        $('input[name="having_any_backlog"]').on('change', function () {
            const backlog = $('input[name="having_any_backlog"]:checked').val();
            displayBacklogResultFields(backlog);
        });

        function displayBacklogResultFields(backlog) {
            if (backlog === 'true') {
                $('.degree-results').addClass('d-none');
                $('.degree-backlog_results').removeClass('d-none');
                $('.ca-backlog-results').removeClass('d-none');
                $('.ca-results').addClass('d-none');
            } else {
                $('.degree-backlog_results').addClass('d-none');
                $('.degree-results').removeClass('d-none');
                $('.ca-backlog-results').addClass('d-none');
                $('.ca-results').removeClass('d-none');
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
            // admissonFormVal.submit()
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
                            $('.semester-group:visible').each(function () {
                                const semNumber = $(this).data('sem');
                                if (semNumber) showSemesterUpload(semNumber);
                            });
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
    /* $('.admission_form').on('submit', function (e) {
        console.log('inside the function');

        e.preventDefault(); $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var formData = new FormData(this);
        console.log('formData', formData);

        $.ajax({
            type: "post",
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                variable.dataTable.ajax.reload();
                var toastHTML = response.message;
                // M.toast({html: toastHTML});
                window.location.href = "admission";
                localStorage.removeItem("admissionObj");
            },
            error: function (data) {
                console.log("error");
                console.log(data);
            }
        });

    }); */

    // Enhanced form submit with full debugging
    $('.admission_form').on('submit', function (e) {
        e.preventDefault(); // prevent default form submit

        // Grab CSRF token
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        const actionUrl = $(this).attr('action');

        // Setup CSRF token for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        });

        const formData = new FormData(this);

        // Log FormData keys and values (for debugging)
        for (var pair of formData.entries()) {
            console.log(pair[0] + ':', pair[1]);
        }

        $.ajax({
            type: "POST",
            url: actionUrl,
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            // Enable the following if you're making cross-domain requests
            // xhrFields: {
            //     withCredentials: true
            // },
            success: function (response) {
                console.log("Success:", response);

                if (typeof variable !== 'undefined' && variable.dataTable) {
                    variable.dataTable.ajax.reload();
                }

                var toastHTML = response.message || "Admission submitted successfully!";
                // M.toast({html: toastHTML}); // Uncomment if using Materialize
                window.location.href = "admission";
                localStorage.removeItem("admissionObj");
            },
            error: function (xhr) {
                console.error("AJAX Error - Status:", xhr.status);
                console.error("Response Text:", xhr.responseText);

                alert("Submission failed: " + (xhr.responseJSON?.message || "Check console for details."));
            }
        });
    });


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
