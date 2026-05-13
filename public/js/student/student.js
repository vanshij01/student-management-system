$(document).ready(function () {
    dataTableData();
});

function dataTableData() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var dataTable = $('#student_table').DataTable({
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
                    columns: [2, 3, 4, 5, 6]
                },
            }
        ],
        "destroy": true,
        ajax: {
            url: 'student/studentData',
            data: {
                gender: $('#gender').val(),
                country_id: $('#country_id').val()
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
                        $html += '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect edit_button" href="student/' + data + '/edit">Edit</a></li>';
                    }
                    if (readCheck) {
                        $html += '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect view_button" href="student/' + data + '">View</a></li>';
                    }
                    if (isSuperAdmin != 0) {
                        $html += '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect delete_button" href="javascript:void(0)" data-id="' + data + '" onclick="deleteStudent(' + data + ')">Delete</a></li>';
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
                data: 'name',
                render: function (data, type, row) {
                    return row.first_name + ' ' + row.middle_name + ' ' + row.last_name;
                }
            },
            {
                data: 'gender',
                name: 'gender'
            },
            {
                data: 'email',
                name: 'email'
            },
            {
                data: 'phone',
                name: 'phone'
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
                        return 'Details of ' + data.first_name + ' ' + data.middle_name + ' ' + data.last_name;
                    }
                }),
                type: 'column',
                renderer: function (api, rowIdx, columns) {
                    var data = $.map(columns, function (col, i) {
                        return col.title !==
                            '' // ? Do not show row in modal popup if title is blank (for check box)
                            ?
                            '<tr data-dt-row="' + col.rowIndex + '" data-dt-column="' + col.columnIndex + '">' +
                            '<td>' + col.title + ':' + '</td> ' +
                            '<td>' + col.data + '</td>' +
                            '</tr>' :
                            '';
                    }).join('');

                    return data ? $('<table class="table"/><tbody />').append(data) : false;
                }
            }
        },
    });
    var urlParams = new URLSearchParams(window.location.search);
    var search = urlParams.get('search') ?? '';

    $('.dataTables_filter input').val(search);

    $('.dataTables_filter input')
        .off()
        .on('keypress', function (e) {
            if (e.which === 13) {
                let inputValue = $(this).val();

                $('#student_table').DataTable().search(inputValue).draw();

                urlParams.set('search', inputValue);
                urlParams.set('page', 1); 
                history.replaceState(null, '', '?' + urlParams.toString());
            }
        });
}

$(".student_form").parsley();

$('.back').on('click', function () {
    location.href = "/student";
});

$('.edit').on('click', function () {
    var id = $(this).data('id');
    location.href = "/student/" + id + "/edit";
});

$('#filter').on('click', function () {
    studentData();
});

$('#reset').click(function () {
    $('#country_id').val('').trigger('change');
    $('#gender').val('').trigger('change');
    studentData();
});

$("#student_create_form").submit(function (event) {
    event.preventDefault();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: $(this).attr('action'),
        type: 'POST', // replaced from put
        cache: false,
        data: $(this).serialize(),
        processData: true,
        dataType: "JSON",
        success: function (response) {
            if (response.status == 'error') {
                if (response.errors.email) {
                    $('#email_error').text(response.errors.email[0]);
                }
            }

            if (response.status == 'success') {
                $('#email_error').text('');
                window.location.href = '/student';
            }
        },
        error: function (xhr) {
        }
    });
});

function deleteStudent(id) {
    var url = "student/delete/" + id;
    var tableId = 'student_table';
    deleteData(url, tableId);
}

setTimeout(function () {
    $('.alert').fadeOut('fast');
}, 3000);
