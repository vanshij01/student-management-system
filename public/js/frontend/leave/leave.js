$(document).ready(function () {
    leaveData();
});

$('#from').flatpickr({
    dateFormat: 'd/m/Y',
});

$('#from').on('change', function () {
    var fromDate = $(this).val();
    $('#to').flatpickr({
        dateFormat: 'd/m/Y',
        minDate: fromDate
    });
});

function leaveData() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var dataTable = $('#leave_table').DataTable({
        "responsive": true,
        "destroy": true,
        "processing": true,
        "serverSide": true,
        "ordering": true,
        "autoWidth": false,
        "aaSorting": [],
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        ajax: {
            url: 'leave/leaveData',
            type: "get",
            data: {
                student_id: $('#student_id').val()
            },
        },
        columns: [
            {
                data: '',
            },
            {
                data: 'id',
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
                data: 'leave_from',
                name: 'leave_from'
            },
            {
                data: 'leave_to',
                name: 'leave_to'
            },
            {
                data: 'id',
                render: function (data, type, row) {
                    if (row.userName !== null) {
                        return row.userName;
                    } else {
                        return '';
                    }

                }
            },
            { data: 'leave_status', name: 'leave_status' },
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
                        return 'Details of ';
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

$(".leave_form").parsley();

$("#leave_create_form").submit(function (event) {
    event.preventDefault();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var frm = $('#leave_form').serialize();
    $.ajax({
        url: $(this).attr('action'),
        type: 'POST', // replaced from put
        cache: false,
        data: $(this).serialize(),
        processData: true,
        dataType: "JSON",
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
                    window.location.href = '/leave';
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

setTimeout(function() {
    $('.alert').fadeOut('fast');
}, 3000);
