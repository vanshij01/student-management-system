$(document).ready(function () {
    setTimeout(function () {
        $('#overlay').css('display', 'none');
    }, 100);

    $('#from').flatpickr({
        dateFormat: 'd/m/Y'
    });
    $('#to').flatpickr({
        dateFormat: 'd/m/Y'
    });

    $(".pagination li:first-child .page-link").text("Previous");
    $(".pagination li:last-child .page-link").text("Next");

    var urlParams = new URLSearchParams(window.location.search);

    var search = urlParams.get('search') ?? "";
    $('.page-item a.page-link').each(function (key, value) {
        let page = value.href.split('?')[1];
        let pageLength = urlParams.get('page_length') ? urlParams.get('page_length') : 10;
        let from = urlParams.get('from') ?? "";
        let to = urlParams.get('to') ?? "";
        let actionId = urlParams.get('actionId') ?? "";
        let moduleId = urlParams.get('moduleId') ?? "";
        let paymentType = urlParams.get('paymentType') ?? "";
        let baseUrl = value.href.split('?')[0];
        if (page || pageLength) {
            $(this).attr('href', baseUrl + '?' + page + '&page_length=' + pageLength + '&from=' +
                from + '&to=' + to + '&actionId=' + actionId + '&moduleId=' +
                moduleId + '&paymentType=' + paymentType);
        } else {
            $(this).attr('href', value.href);
        }
    });

    $('.selPages').on('change', function () {
        var pageLength = $(this).val();
        $('#page_length').val(pageLength);
        $('#filter_form').submit().trigger('click');
    });

    $('#filter').click(function () {
        fill_datatable();
    });

    $('#reset').click(function () {
        $('#from').val('');
        $('#to').val('');
        $('#actionId').val('').trigger('change');
        $('#moduleId').val('').trigger('change');
        $('#paymentType').val('').trigger('change');
        $('#donation_table').DataTable().destroy();
        fill_datatable();
    });

    fill_datatable();

    $('.dataTables_filter input').on('input', function (e) {
        var inputValue = $(this).val();
        var currentPage = $('.dataTables_paginate .paginate_button.current')
            .text(); // Get current page number

        if (inputValue.length >= 5) {
            urlParams.set("search", inputValue);
            urlParams.set("page", currentPage); // Set current page number in URL
            window.location.search = urlParams.toString();
        } else if (inputValue.length === 0) {
            urlParams.set("search", inputValue);
            urlParams.set("page", currentPage); // Set current page number in URL
            window.location.search = urlParams.toString();
        }
    });

    $('.dataTables_filter input').keypress(function (e) {
        if (e.which == 13) { // Check if Enter key was pressed
            var inputValue = $(this).val();
            var currentPage = $('.dataTables_paginate .paginate_button.current')
                .text(); // Get current page number

            urlParams.set("search", inputValue);
            urlParams.set("page", currentPage); // Set current page number in URL
            window.location.search = urlParams.toString();
        }
    });
    $('.dataTables_filter input').focus();

    function fill_datatable() {
        if ($.fn.dataTable.isDataTable('#donation_table')) {
            dataTable.destroy();
        }
        dataTable = $('#donation_table').DataTable({
            fixedHeader: {
                header: true
            },
            "autoWidth": true,
            deferRender: true,
            bPaginate: false,
            "lengthChange": false,
            "info": false,
            searching: true,
            scrollX: false,
            processing: true,
            serverSide: false,
            sorting: false,
            ordering: false,
            lengthMenu: [10, 25, 50, 100, 200, 500],
            dom: '<"data-table-header d-flex justify-content-between align-items-center mb-3"lBf>tip',
            buttons: [{
                extend: 'csvHtml5',
                text: '<i class="las la-download"></i> Export Data',
                className: 'secondary_btn',
                exportOptions: {
                    columns: [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14]
                },
            }],
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
                        className: 'model',
                        header: function (row) {
                            var data = row.data();
                            return 'Details of ' + data[4] + ' ' + data[3];
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
                    },
                    className: 'custom-modal-class'
                }
            }
        });
        $('.dataTables_filter input').val(search);
    }
});

setTimeout(function () {
    $('.alert').fadeOut('fast');
}, 3000);


