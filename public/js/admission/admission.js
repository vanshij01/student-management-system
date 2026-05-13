$(document).ready(function () {
    setTimeout(function () {
        $("#overlay").css("display", "none");
    }, 100);

    $(".pagination li:first-child .page-link").text("Previous");
    $(".pagination li:last-child .page-link").text("Next");

    var urlQueryParams = location.search.slice(1);
    var urlParams = new URLSearchParams(window.location.search);



    var currentYear = new Date().getFullYear();
    var nextYear = currentYear + 1;
    var defaultYear = currentYear + "-" + nextYear;

    var year = urlParams.get("year") ?? defaultYear;
    var gender = urlParams.get("gender") ?? "";
    var status = urlParams.get("status") ?? "";
    var courseId = urlParams.get("courseId") ?? "";
    var roomAlloted = urlParams.get("roomAlloted") ?? "";
    var isAdmissionNew = urlParams.get("isAdmissionNew") ?? "";
    var isUsedVehicle = urlParams.get("is_used_vehicle") ?? "";
    var hasBacklog = urlParams.get("has_backlog") ?? "";
    var commentedBy = urlParams.get("admin_id") ?? "";
    var fees_status = urlParams.get("fees_status") ?? "";
    var search = urlParams.get("search") ?? "";

    $("#year").val(year).trigger("change");
    $("#gender").val(gender).trigger("change");
    $("#status").val(status).trigger("change");
    $("#courseId").val(courseId).trigger("change");
    $("#roomAlloted").val(roomAlloted).trigger("change");
    $("#isAdmissionNew").val(isAdmissionNew).trigger("change");
    $("#is_used_vehicle").val(isUsedVehicle).trigger("change");
    $("#has_backlog").val(hasBacklog).trigger("change");
    $("#admin_id").val(commentedBy).trigger("change");
    $("#fees_status").val(fees_status).trigger("change");

    $(".page-item a.page-link").each(function (key, value) {
        let page = value.href.split("?")[1];
        let pageLength = urlParams.get("page_length")
            ? urlParams.get("page_length")
            : 10;
        let year = urlParams.get("year") ?? defaultYear;
        let gender = urlParams.get("gender") ?? "";
        let status = urlParams.get("status") ?? "";
        let courseId = urlParams.get("courseId") ?? "";
        let roomAlloted = urlParams.get("roomAlloted") ?? "";
        let isAdmissionNew = urlParams.get("isAdmissionNew") ?? "";
        var isUsedVehicle = urlParams.get("is_used_vehicle") ?? "";
        var hasBacklog = urlParams.get("has_backlog") ?? "";
        var commentedBy = urlParams.get("admin_id") ?? "";
        var fees_status = urlParams.get("fees_status") ?? "";
        let baseUrl = value.href.split("?")[0];

        if (year == '') {
            $('#year').val(defaultYear).trigger('change');
        }

        if (page || pageLength) {
            $(this).attr(
                "href",
                baseUrl +
                "?" +
                page +
                "&page_length=" +
                pageLength +
                "&year=" +
                year +
                "&gender=" +
                gender +
                "&status=" +
                status +
                "&courseId=" +
                courseId +
                "&roomAlloted=" +
                roomAlloted +
                "&isAdmissionNew=" +
                isAdmissionNew +
                "&is_used_vehicle=" +
                isUsedVehicle +
                "&hasBacklog=" +
                hasBacklog +
                "&commentedBy=" +
                commentedBy +
                "&fees_status=" +
                fees_status
            );
        } else {
            $(this).attr('href', value.href + '&year=' + defaultYear);
        }
    });

    $(".selPages").on("change", function () {
        var pageLength = $(this).val();
        $("#page_length").val(pageLength);
        $("#filter_form").submit().trigger("click");
    });

    $("#filter").click(function () {
        fill_datatable();
    });

    $("#reset").click(function () {
        $("#year").val(defaultYear).trigger("change");
        $("#gender").val("").trigger("change");
        $("#status").val("").trigger("change");
        $("#courseId").val("").trigger("change");
        $("#roomAlloted").val("").trigger("change");
        $("#isAdmissionNew").val("").trigger("change");
        $("#is_used_vehicle").val("").trigger("change");
        $("#has_backlog").val("").trigger("change");
        $("#admin_id").val("").trigger("change");
        $("#fees_status").val("").trigger("change");
        $("#admission_table").DataTable().destroy();
        fill_datatable();
    });

    fill_datatable();

    $(".dataTables_filter input")
        .off()
        .on("keypress", function (e) {
            if (e.which === 13) {
                let inputValue = $(this).val();
                urlParams.set("search", inputValue);
                urlParams.set("page", 1); // go to page 1 on new search
                urlParams.set("year", $("#year").val() || defaultYear);
                window.location.search = urlParams.toString();
            }
        });

    $(".dataTables_filter input").focus();

    function fill_datatable() {
        if ($.fn.dataTable.isDataTable("#admission_table")) {
            dataTable.destroy();
        }
        dataTable = $("#admission_table").DataTable({
            fixedHeader: {
                header: true,
            },
            autoWidth: true,
            deferRender: true,
            bPaginate: false,
            lengthChange: false,
            info: false,
            searching: true,
            scrollX: false,
            processing: true,
            serverSide: false,
            sorting: false,
            ordering: false,
            lengthMenu: [10, 25, 50, 100, 200, 500],
            dom: '<"data-table-header d-flex justify-content-between align-items-center mb-3"<"dt-buttons d-flex gap-2"B>f>tip',
            buttons: [
                {
                    extend: "csvHtml5",
                    text: '<i class="las la-download"></i> Export Data',
                    className: "secondary_btn",
                    exportOptions: {
                        columns: [3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13],
                    },
                },
            ],
            columnDefs: [
                {
                    // For Responsive
                    className: "control",
                    orderable: false,
                    searchable: false,
                    responsivePriority: 1,
                    targets: 0,
                    render: function (data, type, full, meta) {
                        return "";
                    },
                },
                {
                    targets: 1,
                    orderable: false,
                    searchable: false,
                    className: "dt-body-center",
                    render: function () {
                        return '<input type="checkbox" class="dt-checkboxes form-check-input">';
                    },
                },
                {
                    targets: 2,
                    visible: false,
                    searchable: false,
                },
            ],
            responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                        className: "model",
                        header: function (row) {
                            var data = row.data();
                            return "Details of " + data[3];
                        },
                    }),
                    type: "column",
                    renderer: function (api, rowIdx, columns) {
                        var data = $.map(columns, function (col, i) {
                            return col.title !== "" // ? Do not show row in modal popup if title is blank (for check box)
                                ? '<tr data-dt-row="' +
                                col.rowIndex +
                                '" data-dt-column="' +
                                col.columnIndex +
                                '">' +
                                "<td>" +
                                col.title +
                                ":" +
                                "</td> " +
                                "<td>" +
                                col.data +
                                "</td>" +
                                "</tr>"
                                : "";
                        }).join("");

                        return data
                            ? $('<table class="table"/><tbody />').append(data)
                            : false;
                    },
                    className: "custom-modal-class",
                },
            },
        });
        $("#selectAll").on("change", function () {
            const checked = $(this).is(":checked");
            $("#admission_table tbody input.dt-checkboxes").prop(
                "checked",
                checked
            );
        });

        // When any row checkbox is toggled, update the header checkbox state
        $("#admission_table").on("change", "input.dt-checkboxes", function () {
            const total = $(
                "#admission_table tbody input.dt-checkboxes"
            ).length;
            const checked = $(
                "#admission_table tbody input.dt-checkboxes:checked"
            ).length;
            $("#selectAll").prop("checked", total === checked);
        });
        // let search = '';
        $(".dataTables_filter input").val(search);
    }

    setTimeout(function () {
        $(".alert").fadeOut("fast");
    }, 3000);

    $(".select2").select2();
    // formatPagination();
});

$(".amount_error").addClass("d-none");

$(".admission_form").parsley();

$(".back").on("click", function () {
    location.href = "/admission";
});

$(".edit").on("click", function () {
    var id = $(this).data("id");
    location.href = "/admission/" + id + "/edit";
});

function deleteAdmission(id) {
    var url = "admission/delete/" + id;
    var tableId = "admission_table";
    deleteData(url, tableId);
}

function loadCommentModal(admissionId) {
    $('#commentModalBody').html('<div class="text-center py-5">Loading comments...</div>');
    $('#viewCommentModal').modal('show');

    $.ajax({
        url: "/admission/get-comments/" + admissionId,
        type: 'GET',
        success: function (comments) {
            console.log("comments", comments);

            let html = '';
            if (comments.length > 0) {
                html += '<table class="table table-bordered">';
                html += '<thead><tr><th>SR No.</th><th>Admin Comment</th><th>Student Comment</th><th>Commented By</th><th>Comment Type</th><th>Date</th></tr></thead><tbody>';

                comments.forEach(function (comment, index) {
                    html += '<tr>';
                    html += '<td>' + (index + 1) + '</td>';
                    html += '<td>' + (comment.admin_comment ?? '-') + '</td>';
                    html += '<td>' + (comment.student_comment ?? '-') + '</td>';
                    html += '<td>' + (comment.user?.name ?? '-') + '</td>';
                    html += '<td>' + (comment.comment_type?.replaceAll('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) ?? '-') + '</td>';
                    html += '<td>' + new Date(comment.created_at).toLocaleString('en-GB') + '</td>';
                    html += '</tr>';
                });

                html += '</tbody></table>';
            } else {
                html = '<div class="text-center py-4">No comments found.</div>';
            }

            $('#commentModalBody').html(html);
        },
        error: function () {
            $('#commentModalBody').html('<div class="text-danger text-center py-4">Error loading comments.</div>');
        }
    });
}

function sendComment(id) {
    $.ajax({
        type: "get",
        url: "/admission/getAdmissionDataById/" + id,
        success: function (response) {
            var studentAdmissionMap = response.studentAdmissionMap;
            var comment = response.comment;

            $(".dtr-bs-modal").modal("hide");
            $(".createCommentModal").modal("show");
            $(".createCommentForm").parsley();
            $(".admission_id").val(studentAdmissionMap.admission_id);
            $(".student_id").val(studentAdmissionMap.student_id);
            $(".student_name").val(
                studentAdmissionMap.first_name +
                " " +
                studentAdmissionMap.middle_name +
                " " +
                studentAdmissionMap.last_name
            );
            $(".student_email").val(studentAdmissionMap.email);

            $(".createCommentForm").submit(function (e) {
                e.preventDefault();
                var $submitBtn = $(".submitCommentBtn");
                $submitBtn.prop("disabled", true).html("Saving...");
                var formData = $(this).serialize(); // Serialize form data

                $.ajax({
                    url: "/admission/sendComment",
                    type: "POST",
                    data: formData,
                    success: function (response) {
                        if (response.status === "success") {
                            $(".createCommentModal").modal("hide");
                            $("#alertMessage").text(response.message);
                            $("#alertBox").removeClass("d-none");
                            setTimeout(function () {
                                window.location.reload();
                            }, 3000);
                        } else {
                            $submitBtn.prop("disabled", false).html("Save");
                        }
                    },
                });
            });
        },
    });
}

function sendBacklogStatus(id) {
    $.ajax({
        type: "get",
        url: "/admission/getAdmissionDataById/" + id,
        success: function (response) {
            var studentAdmissionMap = response.studentAdmissionMap;

            $(".dtr-bs-modal").modal("hide");
            $(".createBacklogStatusModal").modal("show");
            $(".createBacklogStatusForm").parsley();

            $(".admission_id").val(studentAdmissionMap.admission_id);
            $(".student_id").val(studentAdmissionMap.student_id);
            $(".student_name").val(
                studentAdmissionMap.first_name + " " +
                studentAdmissionMap.middle_name + " " +
                studentAdmissionMap.last_name
            );
            $(".student_email").val(studentAdmissionMap.email);

            $(".has_backlog").val(studentAdmissionMap.has_backlog ? 'true' : 'false').trigger('change');

            $(".createBacklogStatusForm").off("submit").on("submit", function (e) {
                e.preventDefault();
                var $submitBtn = $(".submitBacklogStatusBtn");
                $submitBtn.prop("disabled", true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
                var formData = $(this).serialize();

                $.ajax({
                    url: "/admission/sendBacklogStatus",
                    type: "POST",
                    data: formData,
                    success: function (response) {
                        if (response.status === "success") {
                            $(".createBacklogStatusModal").modal("hide");
                            $("#alertMessage").text(response.message);
                            $("#alertBox").removeClass("d-none");
                            setTimeout(function () {
                                window.location.reload();
                            }, 3000);
                        } else {
                            $submitBtn.prop("disabled", false).html("Save");
                        }
                    },
                });
            });
        },
    });
}

function sendStatusRemark(id) {
    $.ajax({
        type: "get",
        url: "/admission/getAdmissionDataById/" + id,
        success: function (response) {
            var studentAdmissionMap = response.studentAdmissionMap;
            var comment = response.comment;

            $(".dtr-bs-modal").modal("hide");
            $(".createStatusRemarkModal").modal("show");
            $(".createStatusRemarkForm").parsley();
            $(".admission_id").val(studentAdmissionMap.admission_id);
            $(".student_id").val(studentAdmissionMap.student_id);
            $(".student_name").val(
                studentAdmissionMap.first_name +
                " " +
                studentAdmissionMap.middle_name +
                " " +
                studentAdmissionMap.last_name
            );
            $(".student_email").val(studentAdmissionMap.email);
            $(".admission_status")
                .val(studentAdmissionMap.is_admission_confirm)
                .trigger("change");
            // $(".admin_comment").val(comment ? comment.admin_comment : "");

            $(".createStatusRemarkForm").submit(function (e) {
                e.preventDefault();
                var $submitBtn = $(".submitStatusRemarkBtn");
                $submitBtn
                    .prop("disabled", true)
                    .html('<i class="fa fa-spinner fa-spin"></i> Saving...'); // Prevent form submission
                var formData = $(this).serialize(); // Serialize form data

                $.ajax({
                    url: "/admission/sendStatusRemark",
                    type: "POST",
                    data: formData,
                    success: function (response) {
                        if (response.status === "success") {
                            $(".createStatusRemarkModal").modal("hide");
                            $("#alertMessage").text(response.message);
                            $("#alertBox").removeClass("d-none");
                            setTimeout(function () {
                                window.location.reload();
                            }, 3000);
                        } else {
                            $submitBtn.prop("disabled", false).html("Save");
                        }
                    },
                });
            });
        },
    });
}

function addFees(id) {
    $.ajax({
        type: "get",
        url: "/admission/getAdmissionDataById/" + id,
        success: function (response) {
            var studentAdmissionMap = response.studentAdmissionMap;
            $(".dtr-bs-modal").modal("hide");
            $(".createFeesModal").modal("show");
            $(".fees_form").parsley();
            $(".admission_id").val(studentAdmissionMap.admission_id);
            $(".student_id").val(studentAdmissionMap.student_id);
            $(".student_name").val(
                studentAdmissionMap.first_name +
                " " +
                studentAdmissionMap.middle_name +
                " " +
                studentAdmissionMap.last_name
            );
            $(".student_email").val(studentAdmissionMap.email);

            $("#createFeesForm").submit(function (e) {
                e.preventDefault(); // Prevent form submission
                $('#submitFees').prop('disabled', true);
                $(".amount_error").addClass("d-none");
                var formData = $(this).serialize(); // Serialize form data

                $.ajax({
                    url: $(this).attr("action"),
                    type: "POST",
                    data: formData,
                    success: function (response) {
                        $(".amount_error").addClass("d-none");
                        if (
                            response.data.fees_amount > 20000 &&
                            response.data.payment_type == "Cash"
                        ) {
                            $(".amount_error").removeClass("d-none");
                            $('#submitFees').prop('disabled', false);
                        } else {
                            console.log(response);

                            $(".amount_error").addClass("d-none");
                            $(".createFeesModal").modal("hide");
                            window.location.href = "/fees/" + response.id.id;
                            // $("#fees_table").DataTable().ajax.reload();
                        }
                    },
                });
            });
        },
        error: function (xhr) {
            $('.submitFees').prop('disabled', false);
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

            $(".dtr-bs-modal").modal("hide");
            $(".viewFeesReceiptModal").modal("show");
            $(".student_name").text(
                admission.first_name +
                " " +
                admission.middle_name +
                " " +
                admission.last_name
            );

            $.each(fees, function (key, value) {
                var appendData = "";

                appendData +=
                    "<tr>" +
                    "<td>" +
                    (key + 1) +
                    "</td>" +
                    "<td>" +
                    value.serial_number +
                    "</td>" +
                    "<td>" +
                    value.payment_type +
                    "</td>" +
                    "<td>" +
                    value.fees_amount +
                    "</td>" +
                    "<td>" +
                    value.paid_at +
                    "</td>" +
                    '<td><a href="fees/' +
                    value.id +
                    '" class="btn btn-primary" target="_blank"><i class="las la-eye"></i></a></td>' +
                    "</tr>";
                $("#viewFeesReceiptTable").append(appendData);
            });
        },
    });
}

function roomAllocate(id) {
    $.ajax({
        type: "get",
        url: "/admission/getAdmissionDataById/" + id,
        success: function (response) {
            $(".dtr-bs-modal").modal("hide");
            $(".roomAllocateModal").modal("show");
            var studentAdmissionMap = response.studentAdmissionMap;
            $(".roomAllocateForm").parsley();
            $(".admission_id").val(studentAdmissionMap.admission_id);
            $(".student_id").val(studentAdmissionMap.student_id);
            $(".student_name").val(
                studentAdmissionMap.first_name +
                " " +
                studentAdmissionMap.middle_name +
                " " +
                studentAdmissionMap.last_name
            );
            $(".student_email").val(studentAdmissionMap.email);
            $("#hostel_id")
                .val(studentAdmissionMap.hostel_id)
                .trigger("change");

            if (
                studentAdmissionMap.hostel_name &&
                studentAdmissionMap.room_number &&
                studentAdmissionMap.bed_number
            ) {
                $("#hostel_name_text").text(studentAdmissionMap.hostel_name);
                $("#room_name_text").text(studentAdmissionMap.room_number);
                $("#bed_name_text").text(studentAdmissionMap.bed_number);
                $("#room_allotment_section").removeClass("d-none");
                $("#no_allotment_section").addClass("d-none");
            } else {
                $("#no_allotment_section").removeClass("d-none");
                $("#room_allotment_section").addClass("d-none");
            }

            var hostelId = $("#hostel_id").find(":selected").val();
            bindRoomData(hostelId, studentAdmissionMap.room_id);

            var roomId = studentAdmissionMap.room_id;
            var bedId = studentAdmissionMap.bed_id;
            var bedNumber = studentAdmissionMap.bed_number;

            setTimeout(() => {
                bindBedData(roomId, bedId, bedNumber);
            }, 3000);

            $("#roomAllocateForm").submit(function (e) {
                e.preventDefault(); // Prevent form submission
                var formData = $(this).serialize(); // Serialize form data

                $.ajax({
                    url: $(this).attr("action"),
                    type: "POST",
                    data: formData,
                    success: function (response) {
                        if (response.status == "success") {
                            window.location.href = "/admission";
                        }
                    },
                });
            });
        },
    });
}

$("#payment_type").on("change", function () {
    if ($(this).val() == "Bank" || $(this).val() == "Card" || $(this).val() == "E-Wallet") {
        $(".transaction_number_div").removeClass("d-none");
        $(".transaction_date_div").removeClass("d-none");
        $(".bank_name_div").removeClass("d-none");
        $("#transaction_number").attr("required", true);
        $("#transaction_date").attr("required", true);
        $("#bank_name").attr("required", true);
        $(".cheque_number_div").addClass("d-none");
        $("#cheque_number").attr("required", false);
        $("#submit").on("click", function () {
            var transaction_number = $("#transaction_number").val();
            var transaction_date = $("#transaction_date").val();
            $(".error").remove();
            if (transaction_number.length < 1) {
                $("#transaction_number").after(
                    '<span class="error">This field is required</span>'
                );
            }
            if (transaction_date.length < 1) {
                $("#transaction_date").after(
                    '<span class="error">This field is required</span>'
                );
            }
        });
        $("#transaction_date").flatpickr({
            dateFormat: "d/m/Y",
        });
    } else if ($(this).val() == "Cheque") {
        $(".transaction_number_div").addClass("d-none");
        $(".transaction_date_div").addClass("d-none");
        $("#transaction_number").attr("required", false);
        $("#transaction_date").attr("required", false);
        $(".bank_name_div").removeClass("d-none");
        $(".cheque_number_div").removeClass("d-none");
        $("#bank_name").attr("required", true);
        $("#cheque_number").attr("required", true);
        $("#submit").on("click", function () {
            var bank_name = $("#bank_name").val();
            var cheque_number = $("#cheque_number").val();
            $(".error").remove();
            if (bank_name.length < 1) {
                $("#bank_name").after(
                    '<span class="error">This field is required</span>'
                );
            }
            if (cheque_number.length < 1) {
                $("#cheque_number").after(
                    '<span class="error">This field is required</span>'
                );
            }
        });
    } else {
        $(".transaction_number_div").addClass("d-none");
        $(".transaction_date_div").addClass("d-none");
        $(".bank_name_div").addClass("d-none");
        $(".cheque_number_div").addClass("d-none");
        $("#transaction_number").attr("required", false);
        $("#transaction_date").attr("required", false);
        $("#bank_name").attr("required", false);
        $("#cheque_number").attr("required", false);
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
                html +=
                    '<option value="' +
                    indexInArray +
                    '">' +
                    valueOfElement +
                    "</option>";
            });
            $("#room_id").html("");
            if (html != "") {
                $("#room_id").append(html);
                if (roomId) {
                    $("#room_id").val(roomId).trigger("change");
                }
            } else {
                $("#room_id").html("");
            }
        },
        error: function (xhr) { },
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
                html +=
                    '<option value="' +
                    indexInArray +
                    '">' +
                    valueOfElement +
                    "</option>";
            });
            if (bedId) {
                html +=
                    '<option value="' + bedId + '">' + bedNumber + "</option>";
            }
            $("#bed_id").html("");
            if (html != "") {
                $("#bed_id").append(html);
                if (bedId) {
                    $("#bed_id").val(bedId).trigger("change");
                }
            } else {
                $("#bed_id").html("");
            }
        },
        error: function (xhr) { },
    });
}

$("#room_id").on("change", function () {
    var roomId = $(this).val();
    bindBedData(roomId);
});

function formatPagination() {
    $(".pagination li:first-child .page-link").text("Previous");
    $(".pagination li:last-child .page-link").text("Next");

    const totalPages = Math.max(
        ...$(".pagination li")
            .map(function () {
                const num = parseInt($(this).text());
                return isNaN(num) ? 0 : num;
            })
            .get()
    );

    const activePage =
        parseInt($(".pagination li.active .page-link").text()) || 1;
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
                    if (
                        visiblePages[i] === 5 &&
                        visiblePages[i + 1] === totalPages &&
                        !ellipsisAdded.has(gapKey)
                    ) {
                        let insertAfterElement = null;
                        $(".pagination li").each(function () {
                            const pageNum = parseInt($(this).text());
                            if (pageNum === visiblePages[i]) {
                                insertAfterElement = $(this);
                                return false; // Break the loop
                            }
                        });

                        if (insertAfterElement) {
                            $(
                                '<li class="page-item disabled ellipsis"><span class="page-link">...</span></li>'
                            ).insertAfter(insertAfterElement);
                            ellipsisAdded.add(gapKey);
                        }
                    }
                } else {
                    const isGapBeforeLastPage =
                        visiblePages[i + 1] === totalPages &&
                        ellipsisAdded.size > 0;

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
                            $(
                                '<li class="page-item disabled ellipsis"><span class="page-link">...</span></li>'
                            ).insertAfter(insertAfterElement);
                            ellipsisAdded.add(gapKey);
                        }
                    }
                }
            }
        }
    }
}

$("#addmission_date, #college_fees_receipt_date, #arriving_date").flatpickr({
    dateFormat: "d/m/Y",
});

$("#addmission_date, #college_fees_receipt_date, #arriving_date").datepicker({
    dateFormat: "dd/mm/yy",
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

$("#college_start_time").flatpickr({
    enableTime: true,
    noCalendar: true,
});

$("#college_start_time").on("change", function () {
    var fromDate = $(this).val();
    $("#college_end_time").flatpickr({
        enableTime: true,
        noCalendar: true,
        minDate: fromDate,
    });
});

setTimeout(function () {
    $(".alert").fadeOut("fast");
}, 3000);
