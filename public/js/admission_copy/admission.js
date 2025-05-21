var AdmissionOverview = function () {

    var variable = {
        dataTable: null,
        varHostelId: null,
        varRoomId: null,
        varBedId: null,
    };

    var selector = {
        selHostel: '#hostel_id',
        selRoom: '#room_id',
        frmResevation: '#frm_reservation',
        reservationModal: "#reservation",
        btn_search: '#btn_search',
        wrapper: '.input_fields_wrap',
        btnAdd: '.add_more_document',
    };

    var bindTable = function () {
        var postData = {
            gender: $('#gender_sel').val(),
            year: $('#year_sel').val(),
            status: $('#status_sel').val(),
            course: $('#course_sel').val(),
            roomAlloted: $('#room_allocation').val(),
            isAdmissionNew: $('#is_admission_new').val(),
        }
        variable.dataTable = $('#admission_table').DataTable({
            // lengthChange: false,
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    title: 'Admission',
                    text: '<i class="fa fa-file-excel-o"></i> Excel',
                    className: "waves-effect waves-light btn gradient-45deg-light-blue-cyan z-depth-4 mr-1 mb-2",
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14]
                    }
                }
            ],
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            ajax: { url: "/admissions", data: postData, dataSrc: "" },
            "bDestroy": true,
            select: {
                'style': 'multi'
            },
            columns: [
                {
                    data: '',
                },
                {
                    data: 'id',
                    orderable: false,
                    targets: 0,
                    className: 'cbId',
                    checkboxes: {
                        "selectRow": true
                    }
                },
                { data: 'id' },
                {
                    data: 'first_name',
                    render: function (data, type, row) {
                        return row.first_name + ' ' + row.middle_name + ' ' + row.last_name;
                    }
                },
                {
                    data: 'gender', render: function (data, type, row) {
                        return data == 'boy' ? 'Boy' : 'Girl'
                    }
                },
                { data: 'phone' },
                { data: 'email' },
                { data: 'village_name' },
                { data: 'father_full_name' },
                { data: 'father_phone' },
                { data: 'course_name' },
                { data: 'semester' },
                { data: 'institute_name' },
                {
                    data: 'created_at', render: function (data, type, row) {
                        return moment(data).format('YYYY-MM-DD')
                    }
                },
                {
                    data: 'is_admission_confirm', render: function (data, type, row) {
                        var html = '';
                        if (row.is_bed_release == 0) {
                            if (row.is_admission_confirm == 0) {
                                html = "<span>Pending</span>";
                            } else if (row.is_admission_confirm == 1) {
                                html = "<span>Confirmed</span>";
                            } else if (row.is_admission_confirm == 2) {
                                html = "<span>Rejected</span>";
                            }
                        } else {
                            html = "<span>Release</span>";
                        }
                        return html;
                    }
                },
                {
                    data: 'fees_status', render: function (data, type, row) {
                        var html;
                        if (row.is_bed_release == 0) {
                            if (row.fees_status == 'Unpaid') {
                                html = "<span>" + row.fees_status + "</span>";
                            } else {
                                html = "<span>Paid (" + row.payment_term + ")</span>";
                            }
                        } else {
                            html = "<span>Student Left</span>";
                        }
                        return html;
                    }
                },
                {
                    data: 'is_admission_new', render: function (data, type, row) {
                        if (row.is_admission_new == 0) {
                            return "Old";
                        } else {
                            return "New";
                        }
                    }
                },
                {
                    data: 'is_fees_paid', render: function (data, type, row) {
                        var html = '';
                        /* if (row.is_fees_paid == 0 && row.is_admission_confirm == 1) {
                          html = '<a class="btn-room-allotment waves-effect waves-light btn modal-trigger mb-2 mr-1" onClick="AdmissionOverview.onFeesPaid(' + row.id + ');" href="#modal_fees">Pay</a>';
                        } else if (row.is_fees_paid == 0 && row.is_admission_confirm == 0) {
                          html = "<span>Admission not Confirm</span>";
                        } else {
                          html = "<span>Paid (" + row.payment_term + ")</span>";
                        } */
                        if (row.is_bed_release == 0) {
                            if (row.is_fees_paid == 0 && row.is_admission_confirm == 0) {
                                html = "<span>Admission not Confirm</span>";
                            } else {
                                if (row.is_admission_confirm == 1) {
                                    html = '<a class="btn-room-allotment waves-effect waves-light btn modal-trigger mb-2 mr-1" onClick="AdmissionOverview.onFeesPaid(' + row.id + ');" href="#modal_fees">Pay</a>';
                                } else if (row.is_admission_confirm == 3) {
                                    html = "<span>Admission Cancelled</span>";
                                } else {
                                    html = "<span>Admission Rejected</span>";
                                }
                            }
                        } else {
                            html = "<span>Student Left</span>";
                        }
                        return html;
                    }
                },
                {
                    data: 'student_id', render: function (data, type, row) {
                        var html;
                        if (row.is_bed_release == 0) {
                            if (row.is_admission_confirm == 0) {
                                html = "<span>Admission not Confirm</span>";
                            } else {
                                if (row.is_admission_confirm == 1) {
                                    html = '<a id="room_allotment_popup_' + row.student_id + '" data-json="' + window.btoa(unescape(encodeURIComponent((JSON.stringify(row))))) + '" class="btn-room-allotment waves-effect waves-light btn modal-trigger mb-2 mr-1" onClick="AdmissionOverview.onRoomAllotment(' + row.student_id + ',' + row.hostel_id + ',' + row.room_id + ',' + row.bed_id + ',' + row.id + ');" href="#reservation">Room Allot</a>';
                                } else if (row.is_admission_confirm == 3) {
                                    html = "<span>Admission Cancelled</span>";
                                } else {
                                    html = "<span>Admission Rejected</span>";
                                }
                            }
                        } else {
                            html = "<span>Release</span>";
                        }
                        return html;
                    }
                },
                {
                    data: 'action',
                    render: function (data, type, row) {
                        var button_html;
                        var x = 0;
                        x++;
                        if (row.is_admission_confirm == 1) {
                            button_html = '<button type="button" onClick="AdmissionOverview.onConfirm(' + row.id + ');" class="btn-reject mb-2 btn waves-effect waves-light amber darken-4"  data-tooltip="Confirm" data-position="top"><i class="material-icons dp48">clear</i></button>';
                        } else {
                            button_html = '<button type="button" onClick="AdmissionOverview.onConfirm(' + row.id + ');" class="btn-confirm mb-2 tooltipped btn waves-effect waves-light green darken-1"  data-tooltip="Confirm" data-position="top"><i class="material-icons dp48">done</i></button> ';
                        }

                        send_note_button_html = '';
                        var notes = "";
                        if (row.is_admission_confirm == 0) {

                            if (row.notes != null && row.notes != "") {
                                notes = row.notes;
                            }

                        }
                        send_note_button_html = '<button id="statusModal_' + row.id + '" data-json="' + notes + '"  onClick="AdmissionOverview.onModalOpen(' + row.id + ',' + row.is_admission_confirm + ');" type="button" class="mb-2 tooltipped btn waves-effect waves-light green darken-1" data-tooltip="View"><i class="material-icons">event_note</i></button> | ';

                        var editDeleteBtn;
                        if (row.role === "super_admin") {
                            editDeleteBtn = '<a class="mb-2 tooltipped btn waves-effect waves-light green darken-1" target="_blank" href="admission/show/' + row.id + '"><i class="material-icons">whatshot</i></a> | <a href="/admission/' + row.id + '/edit" class="mb-2 tooltipped btn waves-effect waves-light cyan" data-tooltip="Edit" data-position="top"><i class="material-icons dp48">edit</i></a> ' +
                                '<button type="button" onClick="AdmissionOverview.onDelete(' + row.id + ');" class="mb-2 tooltipped btn waves-effect waves-light red accent-2" data-tooltip="Delete" data-position="top"><i class="material-icons dp48">delete</i></button> ';

                            if (row.fees_status === 'Paid') {
                                /* editDeleteBtn += '| <a class="mb-2 tooltipped btn waves-effect waves-light green darken-1" target="_blank" href="donation/'+ row.id +'">View Receipt</a>'; */

                                editDeleteBtn += '<a class="mb-2 waves-effect waves-light btn modal-trigger" onClick="AdmissionOverview.viewReceipt(' + row.id + ')" href="#view_receipt">View Receipt</a>';
                            }

                        } else if (row.role === "staff_user") {
                            editDeleteBtn = '<a class="mb-2 tooltipped btn waves-effect waves-light green darken-1 modal-trigger view_button" href="admission/show/' + row.id + '"><i class="material-icons">whatshot</i></a> | ';
                        } else {
                            editDeleteBtn = '';
                        }

                        return send_note_button_html + editDeleteBtn;
                    }
                },
            ],
        });

    };

    var onConfirm = function ($id) {
        var data = {
            admissionId: $id
        };
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.post("confirm-admission", data, function (response) {
            variable.dataTable.ajax.reload();
            var toastHTML = response.message;
            M.toast({ html: toastHTML });
        });
    };

    var onView = function ($id) {
        $.get("/admin/front-admission/" + $id + "/edit", function (response) {
            var admission = response.data.admission;
            var document = response.data.documents;
            $('.active-tmp').addClass('active');
            $('#full_name').html(admission.first_name + " " + admission.middle_name + " " + admission.last_name);
            $('#admission_email_view').html(admission.email);
            $('#admission_phone_view').html(admission.phone);
            $('#dob_view').html(admission.dob);
            $('#age').html(admission.age);
            $('#gender_view').html(admission.gender == "boy" ? "Boy" : "Girl");
            $('#residence_number_view').html(admission.residence_number);
            $('#admission_telephone_view').html(admission.residence_number);
            $('#residence_address_view').html(admission.residence_address);
            $('#village_id_view').html(admission.village_name);
            $('#country_view').html(admission.country_name);
            $('#adhaar_number_view').html(admission.adhaar_number);
            $('#nationality_view').html(admission.nationality);
            $('#father_full_name_view').html(admission.father_full_name);
            $('#father_phone_view').html(admission.father_phone);
            $('#father_occupation_view').html(admission.father_occupation);
            $('#annual_income_view').html(admission.annual_income);
            $('#guardian_name_view').html(admission.guardian_name);
            $('#guardian_phone_view').html(admission.guardian_phone);
            $('#guardian_relation_view').html(admission.guardian_relation);
            $('#course_id_view').html(admission.course_name);
            $('#institute_name_view').html(admission.institute_name);
            $('#year_of_addmission_view').html(admission.year_of_addmission);
            $('#addmission_date_view').html(admission.addmission_date);
            $('#collage_timing_view').html(admission.college_start_time + " - " + admission.college_end_time);
            $('#college_fees_receipt_no_view').html(admission.college_fees_receipt_no);
            $('#college_fees_receipt_date_view').html(admission.college_fees_receipt_date);

            if (admission.student_photo_url == null) {
                $('.empty-student-photo').removeClass('hidden');
                $('#student_photo').addClass('hidden');
            }
            if (admission.student_photo_url == null) {
                $('.empty-parent-photo').removeClass('hidden');
                $('#parent_photo').addClass('hidden');
            }

            $('#student_id_view').val(admission.student_id);
            $('#student_photo').attr("src", admission.student_photo_url);
            $('#parent_photo').attr("src", admission.parent_photo_url);

            $('#studentPhotoUrl_view').attr("href", admission.student_photo_url);
            $('#studentPhotoUrl_view').html(admission.student_file_name);
            $('#parentPhotoUrl_view').attr("href", admission.parent_photo_url);
            $('#parentPhotoUrl_view').html(admission.parent_file_name);

            $('#hdnStudentPhotourl_view').val(admission.student_photo_url);
            $('#hdnParentPhotourl_view').val(admission.parent_photo_url);

            $('#student_photo_url_view').removeAttr('required', 'required');
            $('#parent_photo_url_view').removeAttr('required', 'required');


            $('#txtfileName').val("");
            $('#description').val("");
            $('#documentType').val("0").trigger('change');

            $('#submitted_doc').html("");
            var html = ";"
            $.each(document, function (key, value) {
                /*  var html = '<div class="row"><div class="col m2 s12"><div class="col m12 s12 file-field input-field"><label for="scc">'+value.doc_type+'</label></div></div>'+
                            '<div class="col m3 s12"><div class="col m12 s12 file-field input-field"><a href="'+value.doc_url+'" target="_blank">'+value.file_name+'</a></div></div>'+
                            '<div class="col m2 s12"><div class="col m12 s12 file-field input-field"><label for="scc">'+value.description+'</label></div></div><div class="col m1 s12">'+
                            '<div class="col m12 s12 file-field input-field"><label for="percentile">'+value.percentile+'</label></div></div><div class="col m2 s12">'+
                            '<div class="col m12 s12 file-field input-field"><label for="status">'+value.result_status+'</label></div></div></div>'; */
                html += '<tr><td>' + value.doc_type + '</td><td><a href="' + value.doc_url + '" target="_blank">' + value.file_name + '</a></td><td>' + value.description + '</td><td>' + value.percentile + '</td><td>' + value.result_status + '</td></tr>'
            });
            $('#submitted_doc').append(html);

        });
    };

    var onDelete = function ($id) {
        $.get("/admission-delete/" + $id, function (response) {
            variable.dataTable.ajax.reload();
            var toastHTML = response.message;
            M.toast({ html: toastHTML });
        });
    };

    var bindRoomDropdown = function () {
        $(selector.selHostel).on('change', function () {
            var hostelId = $(this).val();
            $.get("/get-room-list?hostel_id=" + hostelId, function (res) {
                $('#room_id').empty();
                $('#room_id').append('<option value=0>Select Room</option>');
                $.each(res, function (key, value) {
                    var selected = variable.varRoomId == key ? 'selected="selected"' : "";
                    $('#room_id').append('<option  value="' + key + '" ' + selected + ' >' + value + '</option>');
                });
            });
        });
    };

    var bindBedDropdown = function () {
        $(selector.selRoom).on('change', function () {
            var roomId = $(this).val();
            $.get("/get-bed-list?room_id=" + roomId, function (res) {
                $('#bed_id').empty();
                $('#bed_id').append('<option value=0>Select Bed</option>');
                $.each(res, function (key, value) {
                    var selected = variable.varBedId == key ? 'selected="selected"' : "";
                    $('#bed_id').append('<option  value="' + key + '" ' + selected + '>' + value + '</option>');
                });
            });
        });
    };

    var saveReservationData = function () {
        $(selector.frmResevation).submit(function (event) {
            event.preventDefault();
            $.post($(this).attr('action'), $(this).serialize(), function (response) {
                $(selector.reservationModal).modal('close');
                $(selector.frmResevation).trigger("reset");
                variable.dataTable.ajax.reload();
                var toastHTML = response.message;
                M.toast({ html: toastHTML });
            });
        });
    };

    var onRoomAllotment = function ($studentId, $hostelId, $roomId, $bedId, $admissionId) {
        variable.varHostelId = $hostelId;
        variable.varRoomId = $roomId;
        variable.varBedId = $bedId;
        $('#hostel_id').val($hostelId);
        $('#room_id').val($roomId);
        $('#allotment_student_id').val($studentId);
        $('#admission_id').val($admissionId);
        if ($hostelId > 0 && $roomId > 0) {
            $(selector.selHostel).trigger('change');
            $(selector.selRoom).trigger('change');
        }
        var data = JSON.parse(window.atob($("#room_allotment_popup_" + $studentId).attr('data-json')));
        if (data.hostel_name && data.room_number && data.bed_number) {
            $('#allotment_msg').html("<strong>Hostel Name : </strong>" + data.hostel_name + "<br> <strong>Room No : </strong>" + data.room_number + " <br><strong>Bed No : </strong>" + data.bed_number);
        } else {
            $('#allotment_msg').html("Your Allotment is pending.");
        }

    };

    var searchWrapHideShow = function () {
        /* $('#search_sel').on('change', function(){
            var seachKeyword = $(this).val();
            if(seachKeyword == "year"){
                $('#years_wrap').removeClass('hidden');
                $('#gender_wrap').addClass('hidden');
            }else if(seachKeyword == "gender"){
              $('#gender_wrap').removeClass('hidden');
              $('#years_wrap').addClass('hidden');
            } else{
              $('#gender_wrap').addClass('hidden');
              $('#years_wrap').addClass('hidden');
            }
        }); */
    };

    var searchData = function () {
        $(selector.btn_search).on('click', function () {
            bindTable();
            //});
        });
    };

    var addMoreField = function () {
        var x = 0;
        $(selector.btnAdd).on('click', function (e) {
            e.preventDefault();
            x++;
            $(selector.wrapper).append('<div class="row main-wapper">' +
                '<div class="col m2 s12 input-field doc-type-wrap"><select name="admission[' + x + '][documentType]" id="documentType_' + x + '" class="select2 browser-default documentType" required></select></div>' +
                '<div class="col m2 s12 input-field"><textarea id="description" name="admission[' + x + '][description]" class="materialize-textarea"></textarea><label for="description">Description</label></div>' +
                '<div class="col m2 s12 input-field"><select name="admission[' + x + '][resultStatus]" id="resultStatus" class="browser-default" required><option value="0">Please Result Status</option><option value="pass">Pass</option><option value="fail">Fail</option><option value="backlogs">Backlogs</option></select></div>' +
                '<div class="col m2 s12 input-field"><label for="percentile">Percentile <span class="red-text">*</span></label><input type="text" id="percentile" name="admission[' + x + '][percentile]" class="validate" required></div>' +
                '<div class="col m3 s12 file-field input-field"><div class="btn float-right"><span>Document</span><input name="admission[' + x + '][fileName]" type="file"></div><div class="file-path-wrapper"><input placeholder="Upload document" class="file-path validate" type="text"></div></div>' +
                // '<div class="col m3 s12 file-field input-field"><div class="btn float-right"><span>Document</span><span class="red-text">*</span><input name="admission[' + x + '][fileName]" type="file"accept="image/png, image/jpeg, image/jpg, .pdf"class="validate document_photo_url documentUpload" id="document_photo_url" required></div><div class="file-path-wrapper"><img id="dimage" class="rounded img-fluid dimage" src="" style=&quot;display: none;&quot; /><input placeholder="Upload document" class="file-path validate" type="text"></div><input name="admission[' + x + ']documentimage" id="documentimage" value="" type="hidden" class="form-control documentimage" /></div>' +
                '<a href="#" class="remove_field"><i class="material-icons">cancel</i></a></div>');
            bindDocumentType(x);
        });
    };

    var bindDocumentType = function (x) {
        var id = (x === undefined) ? 0 : x;
        $.get("/front-document-type", function (res) {
            $('#documentType_' + id).empty();
            $('#documentType_' + id).append('<option value=0>Please Select Document Type</option>');
            var html = "";
            $.each(res, function (key, value) {
                html += '<option  value="' + value.id + '">' + value.type + '</option>';
            });
            $('#documentType_' + id).append(html);
            $('#documentType_' + id).select2();
        });
    };

    var removeField = function () {
        $(selector.wrapper).on("click", ".remove_field", function (e) {
            e.preventDefault(); $(this).parent('div').remove(); x--;
        })
    };

    var onModalOpen = function ($id, $isAdmissionConfirm) {
        $("#student_id").val('');
        $("#student_id").val($id);

        //var data = JSON.parse(window.atob($("#statusModal_" + $id).attr('data-json')));
        var data = $("#statusModal_" + $id).attr('data-json');

        $("#admission_note").val('');
        $("#admission_note").val(data ? data.trim() : '');
        $('#admission_status').val($isAdmissionConfirm);
        $('#admission_status').formSelect();
        $("#modal1_send_note").modal('open');
    }

    var onFeesPaid = function ($admissionId) {
        $("#modal_fees").modal('open');
        $('#payment_admission_id').val($admissionId)
    }

    var viewReceipt = function ($admissionId) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "/all-receipt",
            type: "post",
            data: {
                admissionId: $admissionId
            },
            success: function (res) {
                $('body #student_name').text(res.student_name);
                receipt = '';
                $.each(res.fees, function (index, value) {
                    receipt += '<tr>';
                    receipt += '<td>' + value.paid_at + '</td><td>' + value.fees_amount + '</td><td>' + value.payment_type + '</td><td><a class="mb-2 tooltipped btn waves-effect waves-light green darken-1" target="_blank" href="donation/' + value.id + '"><i class="material-icons">visibility</i></a></td>';
                    receipt += '</tr>';
                });

                $('body #view_receipt_table').html(receipt);
            }
        });
    }

    var getStudentData = function () {
        $('#student_id').on('change', function () {
            var studentId = $(this).val();
            $.get("/admission/get-student-data/" + studentId, function (res) {
                $('#admission_first_name').val(res.first_name).trigger('click').closest('div').find('label').addClass('active');
                $('#admission_middle_name').val(res.middle_name).trigger('click').closest('div').find('label').addClass('active');
                $('#admission_last_name').val(res.last_name).trigger('click').closest('div').find('label').addClass('active');
                $('#admission_email').val(res.email).trigger('click').closest('div').find('label').addClass('active');
                $('#admission_phone').val(res.phone).trigger('click').closest('div').find('label').addClass('active');
                $('#admission_address').val(res.address).trigger('click').closest('div').find('label').addClass('active');
                $('#dob').val(res.dob);
                $('#gender').val(res.gender);
                $('#gender').formSelect();
                $('#residence_address').val(res.address).trigger('click').closest('div').find('label').addClass('active');
                $('#village_id').val(res.village_id);
                $('#village_id').formSelect();
                $('#country').val(res.country_id);
                $('#country').formSelect();
                /* if (res.is_any_illness == 1) {
                    $('#is_any_illness').prop('checked', true);
                    $('.student_illness_field').show();
                    $('#illness_description').val(res.illness_description);
                } else {
                    $('#is_any_illness').prop('checked', false);
                    $('.student_illness_field').hide();
                    $('#illness_description').val(res.illness_description);
                } */
            })
        });
    }

    var checkImageUploadSize = function () {
        $('input[type="file"]').on('change', function () {
            var file = $(this)[0].files[0];//get file
            var sizeKB = file.size / 1024;
            var imageFormate = ['jpg', 'jpeg', 'png'];
            var extension = $(this).val().split('.').pop();
            if (jQuery.inArray(extension, imageFormate) === -1) {
                alert('Valid image extension is jpg, jpeg, png');
                $(this).val('');
            }
            if (sizeKB > 3000) {
                alert('Image size should be less than 3 MB');
                $(this).val('');
            }
        });
    }

    return {

        init: function () {
            bindTable();
            bindRoomDropdown();
            bindBedDropdown();
            saveReservationData();
            searchWrapHideShow();
            searchData();
            addMoreField();
            removeField();
            bindDocumentType();
            getStudentData();
            checkImageUploadSize();
            $('.timepicker').timepicker();
            $('.dropdown-trigger').dropdown({});
            $("#btn_checkbox").on('click', function () {
                $("#modal_send_bulk_mail").modal('open');
                var rows_selected = variable.dataTable.column(0).checkboxes.selected();
                $.each(rows_selected, function (index, rowId) {
                    $("#send_bulk_mail_form").append(
                        $('<input>')
                            .attr('type', 'hidden')
                            .attr('name', 'id[]')
                            .val(rowId)
                    );
                });
            });

            $(document).on('change', '.cbId', function () {
                var rows_selected = variable.dataTable.column(0).checkboxes.selected();
                if (rows_selected.length > 0) {
                    $('#btn_checkbox').show();
                } else {
                    $('#btn_checkbox').hide();
                }
            });
        },
        onEdit: function ($id) {
            onEdit($id);
        },
        onView: function ($id) {
            onView($id);
        },
        onDelete: function ($id) {
            onDelete($id);
        },
        onConfirm: function ($id) {
            onConfirm($id);
        },
        viewReceipt: function ($id) {
            viewReceipt($id)
        },
        onRoomAllotment: function ($studentId, $hostelId, $roomId, $bedId, $admissionId) {
            onRoomAllotment($studentId, $hostelId, $roomId, $bedId, $admissionId);
        },
        onModalOpen: function ($id, $isAdmissionConfirm) {
            onModalOpen($id, $isAdmissionConfirm);
        },
        onFeesPaid: function ($admissionId) {
            onFeesPaid($admissionId);
        },
    };
}();

$(document).ready(function () {
    AdmissionOverview.init();
    $('.dropdown-trigger').dropdown({});
    $("#admission_table").on('click', '.view_button', function () {
        var id = $(this).data('id');
        location.href = "admission/show/" + id;
    });
});
