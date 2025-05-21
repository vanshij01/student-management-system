$(document).ready(function () {
    dataTableData();
});

function dataTableData() {
    var dataTable = $('#country_table').DataTable({
        searching: true,
        processing: true,
        serverSide: true,
        scrollX: true,
        lengthMenu: [10, 25, 50, 100, 1000, 10000],
        ajax: {
            url: 'country/countryData',
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
                        $html += '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect" href="country/' + data + '/edit">Edit</a></li>';
                    }
                    if (readCheck) {
                        $html += '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect" href="country/' + data + '">View</a></li>';
                    }
                    if (isSuperAdmin!=0) {
                        $html += '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect delete_button" href="javascript:void(0)" data-id="' + data + '" onclick="deleteCountry(' + data + ')">Delete</a></li>';
                    }

                    return '<div class="dropdown">' +
                        '<button type="button" class="btn btn-primary p-1 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">' +
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

$(".country_form").parsley();

$("#country_create_form").submit(function (event) {
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
                window.location.href = '/country';
            }
        },
        error: function (xhr) {
        }
    });

});

function deleteCountry(id) {
    var url = "country/delete/" + id;
    var tableId = 'country_table';
    deleteData(url, tableId);
}

setTimeout(function() {
    $('.alert').fadeOut('fast');
}, 3000);
