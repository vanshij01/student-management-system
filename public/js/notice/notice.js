$(document).ready(function () {
    dataTableData();
});

function dataTableData() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var dataTable = $('#notice_table').DataTable({
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
            url: 'notices/noticeData',
            data: {
                from: $('#from').val(),
                to: $('#to').val(),
                student_id: $('#student_id').val(),
                notice_status: $('#notice_status').val()
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
                    }
                    if (isSuperAdmin != 0) {
                        $html += '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect delete_button" href="javascript:void(0)" data-id="' + data + '" onclick="deleteNotice(' + data + ')">Delete</a></li>';
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
                data: 'title',
                name: 'title'
            },
            {
                data: 'content',
                name: 'content'
            },
            {
                data: 'created_by',
                render: function (data, type, row) {
                    return row.user.name;

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
                        return 'Details of ' + data['title'];
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

$(".notice_form").parsley();

$('.back').on('click', function () {
    location.href = "/notices";
});

$('.edit').on('click', function () {
    var id = $(this).data('id');
    location.href = "/notices/" + id + "/edit";
});

$("#notice_table").on('click', '.edit_button', function () {
    var id = $(this).data('id');
    location.href = "notices/" + id + "/edit";
});

$("#notice_table").on('click', '.view_button', function () {
    var id = $(this).data('id');
    location.href = "notices/" + id;
});

$("#notice_table").on('click', '.edit_button_notice', function () {
    var id = $(this).data('id');
    location.href = "notices/" + id;
});

$('#filter').on('click', function () {
    dataTableData();
});

$('#reset').click(function () {
    $('#from').val('');
    $('#to').val('');
    $('#student_id').val('').trigger('change');
    $('#notice_status').val('').trigger('change');
    dataTableData();
});

$("#notice_create_form").submit(function (event) {
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
                    window.location.href = '/notices';
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

function deleteNotice(id) {
    var url = "notices/delete/" + id;
    var tableId = 'notice_table';
    deleteData(url, tableId);
}
