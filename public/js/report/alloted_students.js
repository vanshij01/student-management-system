$(document).ready(function () {
    dataTableData();
});

function dataTableData() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var dataTable = $('#allocated_students_table').DataTable({
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
                    columns: [2, 3, 4, 5, 6, 7, 8]
                },
        }],
        destroy: true,
        ajax: {
            url: '/report/allotedStudentsReportData',
            data: {
                'hostel_id': $("#hostel_id").val(),
                'year': $('#year').val(),
                'gender': $('#gender_sel').val(),
            },
            type: "get",
        },
        columns: [
            {
                data: ''
            },
            {
                data: 'first_name',
                render: function (data, type, row) {
                    $html = "";
                    $html += '<button type="button" class="btn primary_btn"data-id="' + row.admission_id + '" onclick="releaseBed(' + row.admission_id + ')">Release Bed</button>';
                    return $html;
                }
            },
            {
                data: 'DT_RowIndex'
            },
            {
                data: 'first_name',
                render: function (data, type, row) {
                    return row.first_name + ' ' + row.middle_name + ' ' + row.last_name;
                }
            },
            {
                data: 'phone'
            },
            {
                data: 'father_phone'
            },
            {
                data: 'mother_phone'
            },
            {
                data: 'guardian_phone'
            },
            { data: 'bed_number', name: 'bed_number' },
            /* {
                data: 'action', render: function (data, xhr, row) {
                    return '<button class="btn btn-sm btn-primary btn-release-bed" data-id="' + row.admission_id + '">Release Bed</button>';
                }
            } */
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

var admissionId;

$(document).on('click', '.btn-release-bed', function () {
    admissionId = $(this).data('id');
    $('#modal_release_bed').modal('open');
});

$('#filter').on('click', function () {
    allotedStudentsData();
});

$('#reset').click(function () {
    $('#student_id').val('').trigger('change');
    $('#year').val('').trigger('change');
    $('#gender').val('').trigger('change');
    allotedStudentsData();
});

function releaseBed(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You want to release this bed.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Release it!',
        cancelButtonText: 'No, Please!',
        customClass: {
            confirmButton: 'btn primary_btn me-3 waves-effect waves-light',
            cancelButton: 'btn secondary_btn waves-effect'
        },
        buttonsStyling: false
    }).then(function (result) {
        if (result.value) {
            $.ajax({
                url: "/report/releaseBed/" + id,
                type: "get",
                data: {
                    admission_id: id
                },
            }).done(function (data) {
                if (!data.status) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Cancelled!',
                        text: data.message,
                        customClass: {
                            confirmButton: 'btn secondary_btn waves-effect'
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: data.message,
                        customClass: {
                            confirmButton: 'btn secondary_btn waves-effect'
                        }
                    });
                    $('#allocated_students_table').DataTable().ajax.reload();
                }
            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                Swal.fire({
                    icon: 'error',
                    title: 'Cancelled!',
                    text: 'Something wrong.' + thrownError,
                    customClass: {
                        confirmButton: 'btn secondary_btn waves-effect'
                    }
                });
            })
        } else {
            Swal.fire({
                title: 'Cancelled!',
                text: 'Record is safe',
                icon: 'error',
                customClass: {
                    confirmButton: 'btn secondary_btn'
                }
            });
        }
    });
}
