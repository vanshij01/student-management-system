$(document).ready(function () {
    dataTableData();
});

function dataTableData() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var dataTable = $('#apology_letter_table').DataTable({
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
                    columns: [2, 3, 4, 5, 6, 7, 8]
                },
            }
        ],
        "destroy": true,
        ajax: {
            url: 'apology_letter/apologyLetterData',
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
                        $html += '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect delete_button" href="javascript:void(0)" data-id="' + data + '" onclick="deleteApologyLetter(' + data + ')">Delete</a></li>';
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
                data: 'subject',
                name: 'subject'
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
                data: 'bed_number',
                name: 'bed_number'

            },
            {
                data: 'created_at', render: function (data, type, row) {
                    return moment(data).format('DD/MM/YYYY')
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
                        return 'Details of ' + data['first_name'] + ' ' + data['middle_name'] + ' ' + data['last_name'];
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

$(".apology_letter_form").parsley();

$('.back').on('click', function () {
    location.href = "/apology_letter";
});

$('.edit').on('click', function () {
    var id = $(this).data('id');
    location.href = "/apology_letter/" + id + "/edit";
});

$("#apology_letter_table").on('click', '.edit_button', function () {
    var id = $(this).data('id');
    location.href = "apology_letter/" + id + "/edit";
});

$("#apology_letter_table").on('click', '.view_button', function () {
    var id = $(this).data('id');
    location.href = "apology_letter/" + id;
});

$("#apology_letter_create_form").submit(function (event) {
    event.preventDefault();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        cache: false,
        data: $(this).serialize(),
        processData: true,
        dataType: "JSON",
        success: function (response) {
            if (response.status == 'success') {
                window.location.href = '/apology_letter';
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
        error: function (xhr) {
            $('#complain_name_error').text('');
            var res = JSON.parse(xhr.responseText);
            if (res.errors) {
                $('#complain_name_error').text(res.errors.complain_name[0]);
            }
        }
    });

});

function deleteApologyLetter(id) {
    var url = "apology_letter/delete/" + id;
    var tableId = 'apology_letter_table';
    deleteData(url, tableId);
}

function updateFileNameSwap(input, targetId) {
    const label = document.getElementById(targetId);
    const defaultText = label.getAttribute('data-default') || 'Upload'; // Default text stored here
    const fileName = input.files.length > 0 ? input.files[0].name : defaultText;
    label.textContent = fileName;
}

setTimeout(function () {
    $('.alert').fadeOut('fast');
}, 3000);
