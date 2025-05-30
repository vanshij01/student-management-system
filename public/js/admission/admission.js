$(document).ready(function () {

    setTimeout(function () {
        $('#overlay').css('display', 'none');
    }, 100);

    $(".pagination li:first-child .page-link").text("Previous");
    $(".pagination li:last-child .page-link").text("Next");

    var urlQueryParams = location.search.slice(1);
    var urlParams = new URLSearchParams(window.location.search);

    var year = urlParams.get('year') ?? "";
    var gender = urlParams.get('gender') ?? "";
    var status = urlParams.get('status') ?? "";
    var courseId = urlParams.get('courseId') ?? "";
    var roomAlloted = urlParams.get('roomAlloted') ?? "";
    var isAdmissionNew = urlParams.get('isAdmissionNew') ?? "";
    var search = urlParams.get('search') ?? "";

    var currentYear = (new Date).getFullYear();
    var nextYear = currentYear + 1;
    var defaultYear = currentYear + '-' + nextYear;

    $('.page-item a.page-link').each(function (key, value) {
        let page = value.href.split('?')[1];
        let pageLength = urlParams.get('page_length') ? urlParams.get('page_length') : 10;
        let year = urlParams.get('year') ?? "";
        let gender = urlParams.get('gender') ?? "";
        let status = urlParams.get('status') ?? "";
        let courseId = urlParams.get('courseId') ?? "";
        let roomAlloted = urlParams.get('roomAlloted') ?? "";
        let isAdmissionNew = urlParams.get('isAdmissionNew') ?? "";
        let baseUrl = value.href.split('?')[0];

        if (year == '') {
            $('#year').val(defaultYear).trigger('change');
        }

        if (page || pageLength) {
            $(this).attr('href', baseUrl + '?' + page + '&page_length=' + pageLength + '&year=' +
                year + '&gender=' + gender + '&status=' + status + '&courseId=' + courseId +
                '&roomAlloted=' + roomAlloted + '&isAdmissionNew=' + isAdmissionNew);
        } else {
            $(this).attr('href', value.href + '&year=' + defaultYear);
        }
    });

    $('.selPages').on('change', function () {
        var pageLength = $(this).val();
        $('#page_length').val(pageLength);
        $('#filter_form').submit().trigger('click');
    });

    $('#filter').click(function () {
        fill_datatable();
    });

    $('#reset').click(function () {
        $('#year').val(defaultYear).trigger('change');
        $('#gender').val('').trigger('change');
        $('#status').val('').trigger('change');
        $('#courseId').val('').trigger('change');
        $('#roomAlloted').val('').trigger('change');
        $('#isAdmissionNew').val('').trigger('change');
        $('#admission_table').DataTable().destroy();
        fill_datatable();
    });

    fill_datatable();

    $('.dataTables_filter input').on('input', function (e) {
        var inputValue = $(this).val();
        var currentPage = $('.dataTables_paginate .paginate_button.current')
            .text(); // Get current page number

        if (inputValue.length >= 5) {
            urlParams.set("search", inputValue);
            urlParams.set("page", currentPage); // Set current page number in URL
            window.location.search = urlParams.toString();
        } else if (inputValue.length === 0) {
            urlParams.set("search", inputValue);
            urlParams.set("page", currentPage); // Set current page number in URL
            window.location.search = urlParams.toString();
        }
    });

    /* $('.dataTables_filter input').keypress(function (e) {
        if (e.which == 13) { // Check if Enter key was pressed
            var inputValue = $(this).val();
            var currentPage = $('.dataTables_paginate .paginate_button.current')
                .text(); // Get current page number

            urlParams.set("search", inputValue);
            urlParams.set("page", currentPage); // Set current page number in URL
            window.location.search = urlParams.toString();
        }
    }); */
    $('.dataTables_filter input').focus();

    function fill_datatable() {
        if ($.fn.dataTable.isDataTable('#admission_table')) {
            dataTable.destroy();
        }
        dataTable = $('#admission_table').DataTable({
            fixedHeader: {
                header: true
            },
            "autoWidth": true,
            deferRender: true,
            bPaginate: false,
            "lengthChange": false,
            "info": false,
            searching: true,
            scrollX: false,
            processing: true,
            serverSide: false,
            sorting: false,
            ordering: false,
            lengthMenu: [10, 25, 50, 100, 200, 500],
            dom: '<"data-table-header d-flex justify-content-between align-items-center mb-3"lBf>tip',
            buttons: [{
                extend: 'csvHtml5',
                text: '<i class="las la-download"></i> Export Data',
                className: 'secondary_btn',
                exportOptions: {
                    columns: [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14]
                },
            }],
            columnDefs: [{
                // For Responsive
                className: 'control',
                orderable: false,
                searchable: false,
                responsivePriority: 1,
                targets: 0,
                render: function (data, type, full, meta) {
                    return '';
                }
            }, {
                targets: 1, // checkbox column
                orderable: false,
                searchable: false,
                className: 'dt-body-center',
                render: function () {
                    return '<input type="checkbox" class="dt-checkboxes form-check-input">';
                }
            }
            ],
            responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                        className: 'model',
                        header: function (row) {
                            var data = row.data();
                            return 'Details of ' + data[3];
                        }
                    }),
                    type: 'column',
                    renderer: function (api, rowIdx, columns) {
                        var data = $.map(columns, function (col, i) {
                            return col.title !==
                                '' // ? Do not show row in modal popup if title is blank (for check box)
                                ?
                                '<tr data-dt-row="' +
                                col.rowIndex +
                                '" data-dt-column="' +
                                col.columnIndex +
                                '">' +
                                '<td>' +
                                col.title +
                                ':' +
                                '</td> ' +
                                '<td>' +
                                col.data +
                                '</td>' +
                                '</tr>' :
                                '';
                        }).join('');

                        return data ? $('<table class="table"/><tbody />').append(data) : false;
                    },
                    className: 'custom-modal-class'
                }
            }
        });
        $('#selectAll').on('change', function () {
            const checked = $(this).is(':checked');
            $('#admission_table tbody input.dt-checkboxes').prop('checked', checked);
        });

        // When any row checkbox is toggled, update the header checkbox state
        $('#admission_table').on('change', 'input.dt-checkboxes', function () {
            const total = $('#admission_table tbody input.dt-checkboxes').length;
            const checked = $('#admission_table tbody input.dt-checkboxes:checked').length;
            $('#selectAll').prop('checked', total === checked);
        });

        $('.dataTables_filter input').val(search);
    }

    setTimeout(function () {
        $('.alert').fadeOut('fast');
    }, 3000);

    $('.select2').select2();
    // formatPagination();

});

$('.amount_error').addClass('d-none');

$(".admission_form").parsley();

$('.back').on('click', function () {
    location.href = "/admission";
});

$('.edit').on('click', function () {
    var id = $(this).data('id');
    location.href = "/admission/" + id + "/edit";
});

function deleteAdmission(id) {
    var url = "admission/delete/" + id;
    var tableId = 'admission_table';
    deleteData(url, tableId);
}

function sendComment(id) {
    $.ajax({
        type: "get",
        url: "/admission/getAdmissionDataById/" + id,
        success: function (response) {
            var studentAdmissionMap = response.studentAdmissionMap;
            var comment = response.comment;

            $('.dtr-bs-modal').modal('hide');
            $('.createCommentModal').modal('show');
            $(".createCommentForm").parsley();
            $('.admission_id').val(studentAdmissionMap.admission_id);
            $('.student_id').val(studentAdmissionMap.student_id);
            $('.student_name').val(studentAdmissionMap.first_name + ' ' + studentAdmissionMap.middle_name + ' ' + studentAdmissionMap.last_name);
            $('.student_email').val(studentAdmissionMap.email);

            $('.createCommentForm').submit(function (e) {
                e.preventDefault(); // Prevent form submission
                var formData = $(this).serialize(); // Serialize form data

                $.ajax({
                    url: '/admission/sendComment',
                    type: 'POST',
                    data: formData,
                    success: function (response) {
                        if (response.status == 'success') {
                            $('.createCommentModal').modal('hide');
                            window.location.reload();
                        }
                    }
                });
            });
        }
    });
}

function sendStatusRemark(id) {
    $.ajax({
        type: "get",
        url: "/admission/getAdmissionDataById/" + id,
        success: function (response) {
            var studentAdmissionMap = response.studentAdmissionMap;
            var comment = response.comment;

            $('.dtr-bs-modal').modal('hide');
            $('.createStatusRemarkModal').modal('show');
            $(".createStatusRemarkForm").parsley();
            $('.admission_id').val(studentAdmissionMap.admission_id);
            $('.student_id').val(studentAdmissionMap.student_id);
            $('.student_name').val(studentAdmissionMap.first_name + ' ' + studentAdmissionMap.middle_name + ' ' + studentAdmissionMap.last_name);
            $('.student_email').val(studentAdmissionMap.email);
            $('.admission_status').val(studentAdmissionMap.is_admission_confirm).trigger('change');
            $('.admin_comment').val(comment ? comment.admin_comment : '');

            $('.createStatusRemarkForm').submit(function (e) {
                e.preventDefault(); // Prevent form submission
                var formData = $(this).serialize(); // Serialize form data

                $.ajax({
                    url: '/admission/sendStatusRemark',
                    type: 'POST',
                    data: formData,
                    success: function (response) {
                        if (response.status == 'success') {
                            $('.createStatusRemarkModal').modal('hide');
                            window.location.reload();
                        }
                    }
                });
            });
        }
    });
}

function addFees(id) {
    $.ajax({
        type: "get",
        url: "/admission/getAdmissionDataById/" + id,
        success: function (response) {
            var studentAdmissionMap = response.studentAdmissionMap;
            $('.dtr-bs-modal').modal('hide');
            $('.createFeesModal').modal('show');
            $(".fees_form").parsley();
            $('.admission_id').val(studentAdmissionMap.admission_id);
            $('.student_id').val(studentAdmissionMap.student_id);
            $('.student_name').val(studentAdmissionMap.first_name + ' ' + studentAdmissionMap.middle_name + ' ' + studentAdmissionMap.last_name);
            $('.student_email').val(studentAdmissionMap.email);

            $('#createFeesForm').submit(function (e) {
                e.preventDefault(); // Prevent form submission
                $('.amount_error').addClass('d-none');
                var formData = $(this).serialize(); // Serialize form data

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    success: function (response) {
                        $('.amount_error').addClass('d-none');
                        if (response.data.fees_amount > 20000 && response.data.payment_type == 'Cash') {
                            $('.amount_error').removeClass('d-none');
                        } else {
                            $('.amount_error').addClass('d-none');
                            $('.createFeesModal').modal('hide');
                            location.reload();
                            $('#fees_table').DataTable().ajax.reload();
                        }
                    }
                });
            });
        }
    });
}

function feesReceipt(id) {
    $.ajax({
        type: "get",
        url: "/admission/feesReceipt/" + id,
        success: function (response) {
            var admission = response.admission;
            var fees = response.fees;

            $('.dtr-bs-modal').modal('hide');
            $('.viewFeesReceiptModal').modal('show');
            $('.student_name').text(admission.first_name + ' ' + admission.middle_name + ' ' + admission.last_name);

            $.each(fees, function (key, value) {
                var appendData = '';

                appendData += '<tr>' +
                    '<td>' + (key + 1) + '</td>' +
                    '<td>' + value.serial_number + '</td>' +
                    '<td>' + value.payment_type + '</td>' +
                    '<td>' + value.fees_amount + '</td>' +
                    '<td>' + value.paid_at + '</td>' +
                    '<td><a href="fees/' + value.id + '" class="btn btn-primary" target="_blank"><i class="las la-eye"></i></a></td>' +
                    '</tr>';
                $('#viewFeesReceiptTable').append(appendData);
            });

        }
    });
}

function roomAllocate(id) {
    $.ajax({
        type: "get",
        url: "/admission/getAdmissionDataById/" + id,
        success: function (response) {
            $('.dtr-bs-modal').modal('hide');
            $('.roomAllocateModal').modal('show');
            var studentAdmissionMap = response.studentAdmissionMap;
            $(".roomAllocateForm").parsley();
            $('.admission_id').val(studentAdmissionMap.admission_id);
            $('.student_id').val(studentAdmissionMap.student_id);
            $('.student_name').val(studentAdmissionMap.first_name + ' ' + studentAdmissionMap.middle_name + ' ' + studentAdmissionMap.last_name);
            $('.student_email').val(studentAdmissionMap.email);
            $('#hostel_id').val(studentAdmissionMap.hostel_id).trigger('change');

            if (studentAdmissionMap.hostel_name && studentAdmissionMap.room_number && studentAdmissionMap.bed_number) {
                $('#hostel_name_text').text(studentAdmissionMap.hostel_name);
                $('#room_name_text').text(studentAdmissionMap.room_number);
                $('#bed_name_text').text(studentAdmissionMap.bed_number);
                $('#room_allotment_section').removeClass('d-none');
                $('#no_allotment_section').addClass('d-none');
            } else {
                $('#no_allotment_section').removeClass('d-none');
                $('#room_allotment_section').addClass('d-none');
            }

            var hostelId = $('#hostel_id').find(':selected').val();
            bindRoomData(hostelId, studentAdmissionMap.room_id);

            var roomId = studentAdmissionMap.room_id;
            var bedId = studentAdmissionMap.bed_id;
            var bedNumber = studentAdmissionMap.bed_number;

            setTimeout(() => {
                bindBedData(roomId, bedId, bedNumber);
            }, 3000);

            $('#roomAllocateForm').submit(function (e) {
                e.preventDefault(); // Prevent form submission
                var formData = $(this).serialize(); // Serialize form data

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    success: function (response) {
                        if (response.status == 'success') {
                            window.location.href = '/admission';
                        }
                    }
                });
            });
        }
    });
}

$('#payment_type').on('change', function () {
    if (($(this).val() == 'Bank') || ($(this).val() == 'Card')) {
        $('.transaction_number_div').removeClass("d-none");
        $('.transaction_date_div').removeClass("d-none");
        $('#transaction_number').attr("required", true);
        $('#transaction_date').attr("required", true);
        $('.bank_name_div').addClass("d-none");
        $('.cheque_number_div').addClass("d-none");
        $('#bank_name').attr("required", false);
        $('#cheque_number').attr("required", false);
        $('#submit').on('click', function () {
            var transaction_number = $('#transaction_number').val();
            var transaction_date = $('#transaction_date').val();
            $(".error").remove();
            if (transaction_number.length < 1) {
                $('#transaction_number').after('<span class="error">This field is required</span>');
            }
            if (transaction_date.length < 1) {
                $('#transaction_date').after('<span class="error">This field is required</span>');
            }
        });
        $('#transaction_date').flatpickr({
            dateFormat: 'd/m/Y'
        });
    }
    else if ($(this).val() == 'Cheque') {
        $('.transaction_number_div').addClass("d-none");
        $('.transaction_date_div').addClass("d-none");
        $('#transaction_number').attr("required", false);
        $('#transaction_date').attr("required", false);
        $('.bank_name_div').removeClass("d-none");
        $('.cheque_number_div').removeClass("d-none");
        $('#bank_name').attr("required", true);
        $('#cheque_number').attr("required", true);
        $('#submit').on('click', function () {
            var bank_name = $('#bank_name').val();
            var cheque_number = $('#cheque_number').val();
            $(".error").remove();
            if (bank_name.length < 1) {
                $('#bank_name').after('<span class="error">This field is required</span>');
            }
            if (cheque_number.length < 1) {
                $('#cheque_number').after('<span class="error">This field is required</span>');
            }
        });
    }
    else {
        $('.transaction_number_div').addClass("d-none");
        $('.transaction_date_div').addClass("d-none");
        $('.bank_name_div').addClass("d-none");
        $('.cheque_number_div').addClass("d-none");
        $('#transaction_number').attr("required", false);
        $('#transaction_date').attr("required", false);
        $('#bank_name').attr("required", false);
        $('#cheque_number').attr("required", false);
    }
});

function bindRoomData(hostelId, roomId) {
    $.ajax({
        url: "/room/getRoomList/" + hostelId,
        type: "get", // replaced from put
        cache: false,
        success: function (response) {
            var html = "";
            $.each(response, function (indexInArray, valueOfElement) {
                html += '<option value="' + indexInArray + '">' + valueOfElement + '</option>'
            });
            $("#room_id").html('');
            if (html != "") {
                $("#room_id").append(html);
                if (roomId) {
                    $('#room_id').val(roomId).trigger('change');
                }
            } else {
                $("#room_id").html('');
            }
        },
        error: function (xhr) {
        },
    });
}

$("#hostel_id").on("change", function () {
    var hostelId = $(this).val();
    bindRoomData(hostelId);
});

function bindBedData(roomId, bedId, bedNumber) {
    $.ajax({
        url: "/bed/getBedList/" + roomId,
        type: "get", // replaced from put
        cache: false,
        success: function (response) {
            var html = "";
            $.each(response, function (indexInArray, valueOfElement) {
                html += '<option value="' + indexInArray + '">' + valueOfElement + '</option>'
            });
            if (bedId) {
                html += '<option value="' + bedId + '">' + bedNumber + '</option>'
            }
            $("#bed_id").html('');
            if (html != "") {
                $("#bed_id").append(html);
                if (bedId) {
                    $('#bed_id').val(bedId).trigger('change');
                }
            } else {
                $("#bed_id").html('');
            }
        },
        error: function (xhr) {
        },
    });
}

$("#room_id").on("change", function () {
    var roomId = $(this).val();
    bindBedData(roomId);
});

function formatPagination() {
    $(".pagination li:first-child .page-link").text("Previous");
    $(".pagination li:last-child .page-link").text("Next");

    const totalPages = Math.max(...$(".pagination li").map(function () {
        const num = parseInt($(this).text());
        return isNaN(num) ? 0 : num;
    }).get());

    const activePage = parseInt($(".pagination li.active .page-link").text()) || 1;
    if (!$(".pagination li.active").length) {
        $(".pagination li").eq(1).addClass("active");
        activePage = 1;
    }
    if (totalPages > 5) {
        $(".pagination li").show();

        $(".pagination li.ellipsis").remove();

        let visiblePages = [];

        if (activePage < 5) {
            for (let i = 1; i <= 5 && i <= totalPages; i++) {
                visiblePages.push(i);
            }

            if (!visiblePages.includes(totalPages)) {
                visiblePages.push(totalPages);
            }
        } else {
            visiblePages.push(1);
            if (activePage > 2) visiblePages.push(activePage - 1);
            visiblePages.push(activePage);
            if (activePage < totalPages - 1) visiblePages.push(activePage + 1);

            if (!visiblePages.includes(totalPages)) {
                visiblePages.push(totalPages);
            }
        }

        visiblePages = [...new Set(visiblePages)].sort((a, b) => a - b);

        $(".pagination li").each(function (index) {
            if (index === 0 || index === $(".pagination li").length - 1) return;

            const pageNum = parseInt($(this).text());
            if (isNaN(pageNum)) return; // Skip non-numeric items

            if (!visiblePages.includes(pageNum)) {
                $(this).hide();
            }
        });

        const ellipsisAdded = new Set();

        for (let i = 0; i < visiblePages.length - 2; i++) {
            if (visiblePages[i + 1] - visiblePages[i] > 1) {
                const gapKey = `${visiblePages[i]}-${visiblePages[i + 1]}`;

                if (activePage < 5) {
                    if (visiblePages[i] === 5 && visiblePages[i + 1] === totalPages && !ellipsisAdded.has(gapKey)) {
                        let insertAfterElement = null;
                        $(".pagination li").each(function () {
                            const pageNum = parseInt($(this).text());
                            if (pageNum === visiblePages[i]) {
                                insertAfterElement = $(this);
                                return false; // Break the loop
                            }
                        });

                        if (insertAfterElement) {
                            $('<li class="page-item disabled ellipsis"><span class="page-link">...</span></li>')
                                .insertAfter(insertAfterElement);
                            ellipsisAdded.add(gapKey);
                        }
                    }
                } else {
                    const isGapBeforeLastPage = visiblePages[i + 1] === totalPages && ellipsisAdded.size > 0;

                    if (!ellipsisAdded.has(gapKey) && !isGapBeforeLastPage) {
                        let insertAfterElement = null;
                        $(".pagination li").each(function () {
                            const pageNum = parseInt($(this).text());
                            if (pageNum === visiblePages[i]) {
                                insertAfterElement = $(this);
                                return false; // Break the loop
                            }
                        });

                        if (insertAfterElement) {
                            $('<li class="page-item disabled ellipsis"><span class="page-link">...</span></li>')
                                .insertAfter(insertAfterElement);
                            ellipsisAdded.add(gapKey);
                        }
                    }
                }
            }
        }
    }
}

$('#addmission_date, #college_fees_receipt_date, #arriving_date').flatpickr({
    dateFormat: 'd/m/Y',
});


$('#addmission_date, #college_fees_receipt_date, #arriving_date').datepicker({
    dateFormat: 'dd/mm/yy',
});

/* $('#leave_to').datepicker({
    dateFormat: 'm/d/yy'
});

$('#leave_from').on('change', function() {
    var fromDate = $(this).datepicker('getDate');
    if (fromDate) {
        $('#leave_to').datepicker('option', 'minDate', fromDate);
    }
}); */

/* $('#college_start_time, #college_end_time').timepicker({
    minTime: '5:00am',
    maxTime: '9:00pm',
    orientation: isRtl ? 'r' : 'l'
}); */

$('#college_start_time').flatpickr({
    enableTime: true,
    noCalendar: true,
});

$('#college_start_time').on('change', function () {
    var fromDate = $(this).val();
    $('#college_end_time').flatpickr({
        enableTime: true,
        noCalendar: true,
        minDate: fromDate
    });
});

setTimeout(function () {
    $('.alert').fadeOut('fast');
}, 3000);


