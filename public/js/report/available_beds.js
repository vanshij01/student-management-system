$(document).ready(function () {
    dataTableData();
});

function dataTableData() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var dataTable = $('#available_beds_table').DataTable({
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
                    columns: [2, 3, 4]
                },
        }],
        destroy: true,
        ajax: {
            url: '/report/availableBedsReportData',
            data: {
                'hostel_id': $("#hostel_id").val(),
                'year': $('#year').val(),
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
                data: 'room_number',
                name: 'room_number'
            },
            {
                data: 'bed_number',
                name: 'bed_number'
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
                        return 'Details of Room number ' + data['room_number'] + ' and Bed number ' + data['bed_number'];
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
    availableBedsData();
});

$('#reset').click(function () {
    $('#year').val('').trigger('change');
    $('#hostel_id').val('').trigger('change');
    availableBedsData();
});
