$('#from').datepicker({
    dateFormat: 'dd/mm/yy'
});

$('#to').datepicker({
    dateFormat: 'dd/mm/yy'
}).prop('disabled', true).css({
    'background-color': 'white',
    'color': '#000',
    'opacity': '1'
});

$('#from').on('change', function () {
    var fromDate = $(this).datepicker('getDate');
    $('#to').datepicker('option', 'minDate', fromDate).prop('disabled', false);
});

$(document).ready(function () {
    dataTableData();

    $('#admission_id, #payment_type, #donation_type, #payment_method').select2({
        dropdownParent: $('#createFeesModal'), // replace with your popup's container
        minimumResultsForSearch: 0 // optional: force search box to always show
    });

    $('#update_admission_id, #update_payment_type, #update_donation_type, #update_payment_method').select2({
        dropdownParent: $('#updateFeesModal'), // replace with your popup's container
        minimumResultsForSearch: 0 // optional: force search box to always show
    });
});

function dataTableData() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var dataTable = $('#fees_table').DataTable({
        searching: true,
        processing: true,
        serverSide: true,
        scrollX: true,
        lengthMenu: [10, 25, 50, 100, 1000, 10000],
        dom: '<"data-table-header d-flex justify-content-between align-items-center mb-3"lBf>tip',
        buttons: [{
            extend: 'csvHtml5',
            text: '<i class="las la-download"></i> Export Data',
            className: 'secondary_btn',
            exportOptions: {
                columns: [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14]
            },
        }],
        "destroy": true,
        ajax: {
            url: 'fees/feesData',
            data: {
                /* year: $('#year_sel').val(), */
                from: $('#from').val(),
                to: $('#to').val(),
                gender: $('#gender').val(),
                student_id: $('#student_id').val(),
                hostel_id: $('#hostel_id').val()
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
                        $html += '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect delete_button" href="javascript:void(0)" data-id="' + data + '" onclick="updateFees(' + data + ')">Update</a></li>';
                    }
                    if (readCheck) {
                        $html += '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect view_button" data-id="' + data + '">View</a></li>';
                    }
                    if (isSuperAdmin != 0) {
                        $html += '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect email_button" data-id="' + data + '">Send Receipt Via Email</a></li>';
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
                name: 'DT_RowIndex'
            },
            {
                data: 'serial_number',
                render: function (data, type, row) {
                    return row.serial_number + ' / ' + row.financial_year;
                }
            },
            {
                data: 'name',
                render: function (data, type, row) {
                    return row.first_name + ' ' + row.middle_name + ' ' + row.last_name;
                }
            },
            {
                data: 'gender', render: function (data, type, row) {
                    return row.gender == 'boy' ? 'Boy' : 'Girl';
                }
            },
            {
                data: 'hostel_name',
                name: 'hostel_name'
            },
            {
                data: 'payment_type',
                name: 'payment_type'
            },
            {
                data: 'payment_method',
                name: 'payment_method'
            },
            {
                data: 'fees_amount',
                name: 'fees_amount'
            },
            {
                data: 'paid_at', name: 'paid_at', render: function (data, type, row) {
                    return moment(row.paid_at).format("DD/MM/YYYY");
                }
            },
            { data: 'admission_year', name: 'admission_year' },
            {
                data: 'created_at', name: 'created_at', render: function (data, type, row) {
                    return moment(row.created_at).format("DD/MM/YYYY");
                }
            },
            { data: 'transaction_number', name: 'transaction_number' },
            {
                data: 'donation_type', name: 'donation_type'
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

$("#fees_table").on('click', '.view_button', function () {
    var id = $(this).data('id');
    location.href = "fees/" + id;
});

$("#fees_table").on('click', '.email_button', function () {
    var id = $(this).data('id');
    location.href = "fees/email-pdf/" + id;
});

$('#filter').on('click', function () {
    dataTableData();
});

$('#reset').click(function () {
    $('#from').val('');
    $('#to').val('');
    $('#gender').val('').trigger('change');
    $('#student_id').val('').trigger('change');
    $('#hostel_id').val('').trigger('change');
    dataTableData();
});

$(".fees_form").parsley();
$('.amount_error').addClass('d-none');

$('#payment_type').on('change', function () {
    if (($(this).val() == 'Bank') || ($(this).val() == 'Card')) {
        $('.transaction_number_div').removeClass("d-none");
        $('.transaction_date_div').removeClass("d-none");
        $('#transaction_number').attr("required", true);
        $('#transaction_date').attr("required", true);
        $('.bank_name_div').addClass("d-none");
        $('.cheque_number_div').addClass("d-none");
        $('#bank_name').attr("required", false);
        $('#cheque_number').attr("required", false);
        $('#submit').on('click', function () {
            var transaction_number = $('#transaction_number').val();
            var transaction_date = $('#transaction_date').val();
            $(".error").remove();
        });
        $('#transaction_date').flatpickr({
            dateFormat: 'd/m/Y'
        });
    }
    else if ($(this).val() == 'Cheque') {
        $('.transaction_number_div').addClass("d-none");
        $('.transaction_date_div').addClass("d-none");
        $('#transaction_number').attr("required", false);
        $('#transaction_date').attr("required", false);
        $('.bank_name_div').removeClass("d-none");
        $('.cheque_number_div').removeClass("d-none");
        $('#bank_name').attr("required", true);
        $('#cheque_number').attr("required", true);
        $('#submit').on('click', function () {
            var bank_name = $('#bank_name').val();
            var cheque_number = $('#cheque_number').val();
            $(".error").remove();
        });
    }
    else {
        $('.transaction_number_div').addClass("d-none");
        $('.transaction_date_div').addClass("d-none");
        $('.bank_name_div').addClass("d-none");
        $('.cheque_number_div').addClass("d-none");
        $('#transaction_number').attr("required", false);
        $('#transaction_date').attr("required", false);
        $('#bank_name').attr("required", false);
        $('#cheque_number').attr("required", false);
    }
});

$('#addButton').on('click', function () {
    $('#createFeesModal').modal('show');
});

$('#createFeesForm').submit(function (e) {
    e.preventDefault(); // Prevent form submission
    $('.amount_error').addClass('d-none');
    var formData = $(this).serialize(); // Serialize form data

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: formData,
        success: function (response) {
            $('.amount_error').addClass('d-none');
            if (response.data.fees_amount > 20000 && response.data.payment_type == 'Cash') {
                $('.amount_error').removeClass('d-none');
            } else {
                $('.amount_error').addClass('d-none');
                $('#createFeesModal').modal('hide');
                location.reload();
                $('#fees_table').DataTable().ajax.reload();
            }
        }
    });
});

$('#print_update_fees').on('click', function () {
    var id = $(this).attr('data-id');
    updateFees(id);
});

function updateFees(id) {
    $.ajax({
        type: "get",
        url: "/fees/getFeesData/" + id,
        success: function (response) {
            $('#updateFeesModal').modal('show');
            $(".fees_form").parsley();

            var fees = response.fees;
            $('#update_fees_id').val(fees.id);
            $('#update_admission_id option[value="' + fees.admission_id + '"]').prop('selected', true).trigger('change');
            $('#update_fees_amount').val(fees.fees_amount);
            $('#update_payment_type option[value="' + fees.payment_type + '"]').prop('selected', true).trigger('change');
            $('#update_transaction_number').val(fees.transaction_number);
            $('#update_bank_name').val(fees.bank_name);
            $('#update_cheque_number').val(fees.cheque_number);
            $('#update_donation_type option[value="' + fees.donation_type + '"]').prop('selected', true).trigger('change');
            $('#update_payment_method option[value="' + fees.payment_method + '"]').prop('selected', true).trigger('change');
            $('#update_remarks').val(fees.remarks);
            paymentDetails();
            function paymentDetails() {
                if (($('#update_payment_type').val() == 'Bank') || ($('#update_payment_type').val() == 'Card')) {
                    $('.transaction_number_div').removeClass("d-none");
                    $('.transaction_date_div').removeClass("d-none");
                    $('#update_transaction_number').attr("required", true);
                    $('#transaction_date').attr("required", true);
                    $('.bank_name_div').addClass("d-none");
                    $('.cheque_number_div').addClass("d-none");
                    $('#update_bank_name').attr("required", false);
                    $('#update_cheque_number').attr("required", false);
                    $('#transaction_date').flatpickr({
                        dateFormat: 'd/m/Y'
                    });
                }
                else if ($('#update_payment_type').val() == 'Cheque') {
                    $('.transaction_number_div').addClass("d-none");
                    $('.transaction_date_div').addClass("d-none");
                    $('#update_transaction_number').attr("required", false);
                    $('#transaction_date').attr("required", false);
                    $('.bank_name_div').removeClass("d-none");
                    $('.cheque_number_div').removeClass("d-none");
                    $('#update_bank_name').attr("required", true);
                    $('#update_cheque_number').attr("required", true);
                }
                else {
                    $('.transaction_number_div').addClass("d-none");
                    $('.transaction_date_div').addClass("d-none");
                    $('.bank_name_div').addClass("d-none");
                    $('.cheque_number_div').addClass("d-none");
                    $('#update_transaction_number').attr("required", false);
                    $('#transaction_date').attr("required", false);
                    $('#update_bank_name').attr("required", false);
                    $('#update_cheque_number').attr("required", false);
                }
            }

            $('#update_payment_type').on('change', function () {
                paymentDetails();
            });
            $('#updateFeesForm').submit(function (e) {
                e.preventDefault(); // Prevent form submission
                var formData = $(this).serialize(); // Serialize form data

                $.ajax({
                    url: '/fees/' + id,
                    type: 'POST',
                    data: formData,
                    success: function (response) {
                        if (response.status == 'success') {
                            $('#updateFeesModal').modal('hide');
                            window.location.reload();
                        }
                    }
                });
            });
        }
    });
}

setTimeout(function () {
    $('.alert').fadeOut('fast');
}, 3000);


