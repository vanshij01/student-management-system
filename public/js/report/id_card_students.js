$(document).ready(function () {
    dataTableData();
});

function dataTableData() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var dataTable = $('#due_fees_table').DataTable({
        searching: true,
        processing: true,
        serverSide: true,
        scrollX: true,
        responsive: true,
        lengthMenu: [10, 25, 50, 100, 1000, 10000],
        dom: '<"data-table-header d-flex justify-content-between align-items-center mb-3"lBf>tip',
        buttons: [{
            extend: 'csvHtml5',
            text: '<i class="las la-download"></i> Export Data',
            className: 'secondary_btn',
                exportOptions: {
                    columns: [2, 3, 4, 5]
                },
        }],
        destroy: true,
        ajax: {
            url: '/report/idCardStudentsReportData',
            data: {
                year: $('#year').val(),
                gender: $('#gender').val(),
                hostel_id: $('#hostel_id').val(),
                course_id: $('#course_id').val(),
            },
            type: "get",
        },
        columns: [
            {
                data: '',
            },
            {
                data: 'DT_RowIndex'
            },
            {
                data: 'name',
                render: function (data, type, row) {
                    return row.first_name + ' ' + row.middle_name + ' ' + row.last_name;
                }
            },
            { data: 'course_name', name: 'course_name' },
            { data: 'bed_number', name: 'bed_number' },
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

$('#filter').on('click', function () {
    dueFeesData();
});

$('#reset').click(function () {
    $('#year').val('').trigger('change');
    $('#gender').val('').trigger('change');
    $('#hostel_id').val('').trigger('change');
    $('#course_id').val('').trigger('change');
    dueFeesData();
});
