$('#from').datepicker({
    dateFormat: 'dd/mm/yy'
});

$('#to').datepicker({
    dateFormat: 'dd/mm/yy'
}).prop('disabled', true).css({
    'background-color': 'white',
    'color': '#000',
    'opacity': '1'
});

$('#from').on('change', function () {
    var fromDate = $(this).datepicker('getDate');
    $('#to').datepicker('option', 'minDate', fromDate).prop('disabled', false);
});

$(document).ready(function () {
    dataTableData();
});

function dataTableData() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var dataTable = $('#leave_table').DataTable({
        searching: true,
        processing: true,
        serverSide: true,
        scrollX: true,
        lengthMenu: [10, 25, 50, 100, 1000, 10000],
        dom: '<"data-table-header d-flex justify-content-between align-items-center mb-3"lBf>tip',
        buttons: [
            {
                extend: 'csvHtml5',
                text: '<i class="las la-download"></i> Export Data',
                className: 'secondary_btn',
                exportOptions: {
                    columns: [2, 3, 4, 5, 6, 7, 8, 9]
                },
            }
        ],
        "destroy": true,
        ajax: {
            url: 'leave/leaveData',
            data: {
                from: $('#from').val(),
                to: $('#to').val(),
                student_id: $('#student_id').val(),
                leave_status: $('#leave_status').val()
            },
        },
        columns: [
            {
                data: '',
            },
            {
                data: 'id',
                render: function (data, type, row) {
                    $html = "";

                    var isSuperAdmin = $('#isSuperAdmin').val();
                    var readCheck = $('#readCheck').val();
                    var updateCheck = $('#updateCheck').val();

                    if (updateCheck) {
                        $html += '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect edit_button" data-id="' + data + '">Edit</a></li>';
                        $html += '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect" href="javascript:void(0)" data-id="' + data + '" onclick="changeLeaveStatus(' + data + ')">Change Status</a></li>';
                    }
                    if (readCheck) {
                        $html += '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect view_button" data-id="' + data + '">View</a></li>';
                    }
                    if (isSuperAdmin != 0) {
                        $html += '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect" href="javascript:void(0)" data-id="' + data + '" onclick="deleteLeave(' + data + ')">Delete</a></li>';
                    }

                    return '<div class="dropdown">' +
                        '<button type="button" class="btn btn-sm btn-warning p-1 dropdown-toggle hide-arrow action-button" data-bs-toggle="dropdown">' +
                        'Action' +
                        '</button>' +
                        '<div class="dropdown-menu">' +
                        $html
                    '</div>' +
                        '</div>';
                }
            },
            {
                data: 'DT_RowIndex',
                name: 'id'
            },
            {
                data: 'first_name',
                render: function (data, type, row) {
                    return row.first_name + ' ' + row.last_name;
                }
            },
            {
                data: 'hostel_name',
                name: 'hostel_name'
            },
            {
                data: 'room_number',
                name: 'room_number'
            },
            {
                data: 'leave_from',
                name: 'leave_from'
            },
            {
                data: 'leave_to',
                name: 'leave_to'
            },
            {
                data: 'id',
                render: function (data, type, row) {
                    if (row.userName !== null) {
                        return row.userName;
                    } else {
                        return '';
                    }

                }
            },
            {
                data: 'leave_status',
                name: 'leave_status'
            },
        ],
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
        },],
        responsive: {
            details: {
                display: $.fn.dataTable.Responsive.display.modal({
                    header: function (row) {
                        var data = row.data();
                        return 'Details of ' + data['first_name'] + ' ' + data['last_name'];
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
                }
            }
        },
    });
}

$(".leave_form").parsley();

$('.back').on('click', function () {
    location.href = "/leave";
});

$('.edit').on('click', function () {
    var id = $(this).data('id');
    location.href = "/leave/" + id + "/edit";
});

$("#leave_table").on('click', '.edit_button', function () {
    var id = $(this).data('id');
    location.href = "leave/" + id + "/edit";
});

$("#leave_table").on('click', '.view_button', function () {
    var id = $(this).data('id');
    location.href = "leave/" + id;
});

$("#leave_table").on('click', '.edit_button_leave', function () {
    var id = $(this).data('id');
    location.href = "leave/" + id;
});

$('#filter').on('click', function () {
    dataTableData();
});

$('#reset').click(function () {
    $('#from').val('');
    $('#to').val('');
    $('#student_id').val('').trigger('change');
    $('#leave_status').val('').trigger('change');
    dataTableData();
});

$("#leave_create_form").submit(function (event) {
    event.preventDefault();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var formData = new FormData(this);
    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: formData,
        contentType: false,   // Don't set contentType
        processData: false,   // Don't process the data
        success: function (response) {
            if (response.status == 'success') {
                $('#email_error').text('');
                $('#name_error').text('');
                $('.alert-success').removeClass('hide');
                $('.alert-success').addClass('show');
                $('.alert-success-message').text(response.message);
                setTimeout(function () {
                    $('.alert-success').removeClass('show');
                    $('.alert-success').addClass('hide');
                    window.location.href = '/leave';
                }, 2000);
            }
        },
        error: function (xhr) {
            $('#email_error').text('');
            $('#name_error').text('');
            var res = JSON.parse(xhr.responseText);
            if (res.errors) {
                if (res.errors.email) {
                    $('#email_error').text(res.errors.email[0]);
                }
                if (res.errors.name) {
                    $('#name_error').text(res.errors.name[0]);
                }
            }
        }
    });
});

function deleteLeave(id) {
    var url = "leave/delete/" + id;
    var tableId = 'leave_table';
    deleteData(url, tableId);
}

$('#leave_from').datepicker({
    dateFormat: 'dd/mm/yy',
});

$('#leave_to').datepicker({
    dateFormat: 'dd/mm/yy',
});

$('#leave_from').on('change', function () {
    var fromDate = $(this).datepicker('getDate');
    if (fromDate) {
        $('#leave_to').datepicker('option', 'minDate', fromDate);
    }
});

function updateFileNameSwap(input, targetId) {
    const label = document.getElementById(targetId);
    const defaultText = label.getAttribute('data-default') || 'Upload'; // Default text stored here
    const fileName = input.files.length > 0 ? input.files[0].name : defaultText;
    label.textContent = fileName;
}

function changeLeaveStatus(id) {
    $.ajax({
        type: "get",
        url: "/leave/getLeaveDataById/" + id,
        success: function (response) {
            var leave = response.leave;
            var student = response.student;
            console.log('leave', leave);
            console.log('student', student);

            $('.dtr-bs-modal').modal('hide');
            $('.changeLeaveStatusModal').modal('show');
            $(".changeLeaveStatusForm").parsley();
            $('.leave_id').val(leave.id);
            $('.student_id').val(student.id);
            $('.student_name').val(student.first_name + ' ' + student.middle_name + ' ' + student.last_name);
            $('.student_email').val(student.email);
            $('.from_date').text(moment(leave.leave_from).format("DD/MM/YYYY"));
            $('.to_date').text(moment(leave.leave_to).format("DD/MM/YYYY"));
            $('.ticket').attr("href", "http://www.google.com/")

            $('.leave_status').val(leave.leave_status).trigger('change');

            $('.changeLeaveStatusForm').submit(function (e) {
                e.preventDefault(); // Prevent form submission
                var formData = $(this).serialize(); // Serialize form data

                $.ajax({
                    url: '/leave/changeLeaveStatus',
                    type: 'POST',
                    data: formData,
                    success: function (response) {
                        if (response.status == 'success') {
                            $('.changeLeaveStatusModal').modal('hide');
                            window.location.reload();
                        }
                    }
                });
            });
        }
    });
}
