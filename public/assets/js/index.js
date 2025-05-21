$(document).ready(function () {
    // Sidebar Toggle Functionality
    const sidebarToggle = document.getElementById('sidebarToggle');
    const adminSidebar = document.getElementById('admin_sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const closeSidebarBtn = document.getElementById('closeSidebar');
    const body = document.body;

    function openSidebar() {
        adminSidebar.classList.add('sidebar-collapsed');
        if (sidebarOverlay) sidebarOverlay.style.display = 'block';
        body.classList.add('no-scroll');
    }

    function closeSidebar() {
        adminSidebar.classList.remove('sidebar-collapsed');
        if (sidebarOverlay) sidebarOverlay.style.display = 'none';
        body.classList.remove('no-scroll');
    }

    if (sidebarToggle && adminSidebar) {
        sidebarToggle.addEventListener('click', function () {
            if (adminSidebar.classList.contains('sidebar-collapsed')) {
                closeSidebar();
            } else {
                openSidebar();
            }
        });
    }

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', function () {
            closeSidebar();
        });
    }

    // Handle close cross icon
    if (closeSidebarBtn) {
        closeSidebarBtn.addEventListener('click', function () {
            closeSidebar();
        });
    }

    $('.select2').select2();

    setTimeout(function () {
        const $filter = $('.dt-search');
        const $input = $filter.find('input');

        if ($input.length) {
            $input.wrap('<div id="search-wrapper" class="d-flex align-items-center" style="gap: 10px;"></div>');
            $('#search-wrapper').append('<button id="searchBtn" class="primary_btn">Search</button>');

            // Disable live typing search
            $input.off('input');

            // Search on button click
            $('#searchBtn').on('click', function () {
                table.search($input.val()).draw();
            });

            // Optional: Search on Enter key
            $input.on('keypress', function (e) {
                if (e.which === 13) {
                    $('#searchBtn').click();
                }
            });
        }
    }, 100);

    setTimeout(function () {
        $('.alert').fadeOut('fast');
    }, 3000);
});

function deleteData(url, tableId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You want to Delete.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, Please!',
        customClass: {
            confirmButton: 'btn primary_btn me-3 waves-effect waves-light',
            cancelButton: 'btn secondary_btn waves-effect'
        },
        buttonsStyling: false
    }).then(function (result) {
        if (result.value) {
            $.ajax({
                url: url,
                type: "get"
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
                    if (tableId == 'admission_table') {
                        location.href = "/admission";
                    } else {
                        $('#' + tableId).DataTable().ajax.reload();
                    }
                }
            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                Swal.fire({
                    icon: 'error',
                    title: 'Cancelled!',
                    text: 'Something wrong.',
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
