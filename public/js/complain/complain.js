$(document).ready(function () {
    dataTableData();
});

function dataTableData() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var dataTable = $('#complain_table').DataTable({
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
                    columns: [2, 3, 4, 5, 6, 7]
                },
            }
        ],
        "destroy": true,
        ajax: {
            url: 'complains/complainData',
            data: {
                complain_by: $('#complain_by').val(),
                type: $('#type').val(),
                status: $('#status').val(),
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
                    }
                    if (readCheck) {
                        $html += '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect view_button" data-id="' + data + '">View</a></li>';
                        $html += '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect" href="javascript:void(0)" data-id="' + data + '" onclick="changeComplainStatus(' + data + ')">Change Status</a></li>';
                    }
                    if (isSuperAdmin != 0) {
                        $html += '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect delete_button" href="javascript:void(0)" data-id="' + data + '" onclick="deleteComplain(' + data + ')">Delete</a></li>';
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
                data: 'hostel_name',
                name: 'hostel_name'
            },
            {
                data: 'room_number',
                name: 'room_number'
            },
            {
                data: 'first_name',
                render: function (data, type, row) {
                    return row.first_name + ' ' + row.last_name;
                }
            },
            {
                data: 'type',
                render: function (data, type, row) {
                    //
                    if (data == 1) {
                        return 'Technical';
                    }
                    if (data == 2) {
                        return 'System';
                    } if (data == 3)
                        return 'Management';
                    else {
                        return '';
                    }
                }
            },
            {
                data: 'status',
                render: function (data, type, row) {
                    //
                    if (data == 1) {
                        return 'Pending';
                    }
                    if (data == 2) {
                        return 'Open';
                    } if (data == 3)
                        return 'In Progress';
                    if (data == 4)
                        return 'Completed';
                    else {
                        return '';
                    }
                }
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
                        return 'Details of ' + data['name'];
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

$(".complain_form").parsley();

$("#complain_table").on('click', '.edit_button', function () {
    var id = $(this).data('id');
    location.href = "complains/" + id + "/edit";
});

$("#complain_table").on('click', '.view_button', function () {
    var id = $(this).data('id');
    location.href = "complains/" + id;
});

$('.back').on('click', function () {
    location.href = "/complains";
});

$('.edit').on('click', function () {
    var id = $(this).data('id');
    location.href = "/complains/" + id + "/edit";
});

$('#filter').on('click', function () {
    dataTableData();
});

$('#reset').click(function () {
    $('#complain_by').val('').trigger('change');
    $('#type').val('').trigger('change');
    $('#status').val('').trigger('change');
    dataTableData();
});

$("#complain_create_form").submit(function (event) {
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
        dataType: "JSON",
        success: function (response) {
            if (response.status == 'success') {
                window.location.href = '/complains';

                $('#complain_name_error').text('');
                $('.alert-success').removeClass('hide');
                $('.alert-success').addClass('show');
                $('.alert-success-message').text(response.message);
                setTimeout(function () {
                    $('.alert-success').removeClass('show');
                    $('.alert-success').addClass('hide');
                }, 3000);
            }
        },
        cache: false,
        processData: false,
        contentType: false,
        error: function (xhr) {
            $('#complain_name_error').text('');
            var res = JSON.parse(xhr.responseText);
            if (res.errors) {
                $('#complain_name_error').text(res.errors.complain_name[0]);
            }
        }
    });

});

function deleteComplain(id) {
    var url = "complains/delete/" + id;
    var tableId = 'complain_table';
    deleteData(url, tableId);
}

setTimeout(function () {
    $('.alert').fadeOut('fast');
}, 3000);

function updateFileNameSwap(input, targetId) {
    const label = document.getElementById(targetId);
    const defaultText = label.getAttribute('data-default') || 'Upload'; // Default text stored here
    const fileName = input.files.length > 0 ? input.files[0].name : defaultText;
    label.textContent = fileName;
}

function changeComplainStatus(id) {
    $.ajax({
        type: "get",
        url: "/complains/getComplainDataById/" + id,
        success: function (response) {
            var complain = response.complain;
            var student = response.student;
            console.log(complain);

            var filePath = complain.document;
            $('.dtr-bs-modal').modal('hide');
            $('.changeLeaveStatusModal').modal('show');
            $(".changeLeaveStatusForm").parsley();
            $('.complain_id').val(complain.id);
            $('.student_id').val(student.id);
            $('.student_name').val(student.first_name + ' ' + student.middle_name + ' ' + student.last_name);
            $('.student_email').val(student.email);
            $('#admin_comment').val(complain.admin_comment);
            $('.statusChange').val(complain.status).trigger('change');
            if (complain.document) {
                var filePath = assetBaseUrl + encodeURI(complain.document);
                $('.ticket').attr("href", filePath).removeClass('d-none').addClass('d-block');
            } else {
                $('.ticket').removeClass('d-block').addClass('d-none');
            }

            $('.changeLeaveStatusForm').submit(function (e) {
                e.preventDefault(); // Prevent form submission
                var formData = $(this).serialize(); // Serialize form data

                $.ajax({
                    url: '/complains/changeComplainStatus',
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
