$(document).ready(function () {
    dataTableData();
});

function dataTableData() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var dataTable = $('#bed_table').DataTable({
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
                    columns: [2, 3, 4, 5]
                },
            }
        ],
        "destroy": true,
        ajax: {
            url: 'bed/bedData',
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
                        $html += '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect" href="bed/' + data + '/edit">Edit</a></li>';
                    }
                    if (readCheck) {
                        $html += '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect" href="bed/' + data + '">View</a></li>';
                    }
                    if (isSuperAdmin != 0) {
                        $html += '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect" href="javascript:void(0)" data-id="' + data + '" onclick="deleteBed(' + data + ')">Delete</a></li>';
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
                data: 'bed_number',
                name: 'bed_number'
            },
            {

                data: 'hostel_name',
                name: 'hostel_name', render: function (data, type, row) {
                    return row ? row.hostel.hostel_name : '';
                }
            },
            {
                data: 'room_number',
                name: 'room_number', render: function (data, type, row) {
                    return row ? row.room.room_number : '';
                }, searchable: true
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
                        return 'Details of ' + data['bed_number'];
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
}

$(".bed_form").parsley();

$('.back').on('click', function () {
    location.href = "/bed";
});

$('.edit').on('click', function () {
    var id = $(this).data('id');
    location.href = "/bed/" + id + "/edit";
});

$("#bed_create_form").submit(function (event) {
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
            if (response.status == 'error') {
                $('#bed_error').text(response.message);
            }
            if (response.status == 'success') {
                $('#bed_error').text('');
                window.location.href = '/bed';
            }
        },
        error: function (xhr) {
        }
    });

});

$("#hostel_id").on("change", function () {
    var hostelId = $(this).val();

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
            } else {
                $("#room_id").html('');
            }
        },
        error: function (xhr) {
        },
    });
});

function deleteBed(id) {
    var url = "bed/delete/" + id;
    var tableId = 'bed_table';
    deleteData(url, tableId);
}

setTimeout(function () {
    $('.alert').fadeOut('fast');
}, 3000);
