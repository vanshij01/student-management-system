$(document).ready(function () {
    dataTableData();

    $('#event_form').parsley();
    $('#start_datetime').flatpickr({
        enableTime: true,
        dateFormat: 'd/m/Y H:i'
    });

    $('#start_datetime').on('change', function () {
        var fromDate = $(this).val();
        changeEndDate(fromDate);
    });

    function changeEndDate(fromDate) {
        if (fromDate != '') {
            $('#end_datetime').flatpickr({
                enableTime: true,
                dateFormat: 'd/m/Y H:i',
                minDate: fromDate
            });
        } else {
            $('#end_datetime').each(function () {
                $(this).flatpickr().destroy(); // Destroy the flatpickr instance for each element
            });
            $('#end_datetime').val('');
        }
    }
});
function dataTableData() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var dataTable = $('#event_table').DataTable({
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
            url: 'event/eventData',
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
                        $html += '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect" href="event/' + data + '/edit">Edit</a></li>';
                    }
                    if (readCheck) {
                        $html += '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect view_button" data-id="' + data + '">View</a></li>';
                    }
                    if (isSuperAdmin != 0) {
                        $html += '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect delete_button" href="javascript:void(0)" data-id="' + data + '" onclick="deleteEvent(' + data + ')">Delete</a></li>';
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
                name: 'name'
            },
            {
                data: 'location',
                name: 'location'
            },
            {
                data: 'start_datetime',
                name: 'start_datetime'
            },
            {
                data: 'end_datetime',
                name: 'end_datetime'
            },
            {
                data: 'note',
                name: 'note'
            },
            {
                data: 'user.name',
                name: 'created_by'
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

$('.back').on('click', function () {
    location.href = "/event";
});

$('.edit').on('click', function () {
    var id = $(this).data('id');
    location.href = "/event/" + id + "/edit";
});

$("#event_table").on('click', '.view_button', function () {
    var id = $(this).data('id');
    location.href = "event/" + id;
});

function deleteEvent(id) {
    var url = "event/delete/" + id;
    var tableId = 'event_table';
    deleteData(url, tableId);
}

setTimeout(function () {
    $('.alert').fadeOut('fast');
}, 3000);
