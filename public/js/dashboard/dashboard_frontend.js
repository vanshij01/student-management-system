$(document).ready(function () {
    $('#prevYear, #nextYear').on('click', function () {
        var year = $('#currentYear').text();
        allData(year);
    });

    var year = $('#currentYear').text();
    allData(year);

    function allData(year) {
        $.ajax({
            type: "get",
            url: "/students/yearInfo",
            data: {
                year: year
            },
            success: function (response) {
                var admission = response.admission;
                var APP_URL = $('#APP_URL').val();

                if (admission != null) {
                    $('.admission-edit').attr('href', '/students/admission/' + admission.id + '/edit')

                    $('.admission-exist').removeClass('d-none');
                    $('.admission-not-exist').addClass('d-none');
                    $('.comment-section').removeClass('d-none');
                    var documents = admission.documents;
                    var comments = admission.comments;
                    var room_allocation = admission.room_allocation;

                    switch (admission.is_admission_confirm) {
                        case 0:
                            is_admission_confirm = 'Pending';
                            break;
                        case 1:
                            is_admission_confirm = 'Confirm';
                            break;
                        case 2:
                            is_admission_confirm = 'Reject';
                            break;
                        case 3:
                            is_admission_confirm = 'Cancelled';
                            break;
                        case 4:
                            is_admission_confirm = 'Release';
                            break;

                        default:
                            break;
                    }

                    $('.admission-status-box').text(is_admission_confirm);
                    $('#admission_id').val(admission.id);
                    $('.admission-date-box').text(moment(admission.addmission_date).format("DD/MM/YYYY"));
                    $('.admission-arriving-date-box').text(admission.arriving_date ? moment(admission.arriving_date).format("DD/MM/YYYY") : '');

                    if (room_allocation) {
                        var roomData = '<p>Hostel Name : ' + room_allocation.hostel_name + '</p><p>Room Numbe : ' + room_allocation.room_number + '</p><p>Bed Number : ' + room_allocation.bed_number + '</p>';
                    }
                    $('.room-allot').html(admission.room_allocation == null ? 'Not Alloted' : roomData);

                    var documentList = '';
                    $.each(documents, function (index, document) {
                        documentList += '<a href="' + APP_URL + '/' + document.doc_url + '" target="_blank" rel="noopener noreferrer">' + document.doc_type + '</a>';
                        if (index < documents.length - 1) {
                            documentList += ', ';
                        }
                    });
                    $('.uploaded-documents').html(documentList);
                    $('.display-comment').html('');

                    var display_comments = '';
                    $.each(comments, function (indexInArray, comment) {
                    console.log('comment.student_comment != null', comment.student_comment != null);
                        if (comment.student_comment != null) {
                            display_comments += '<div class="d-flex justify-content-between">' +
                                '<p class="upload-date-text" id="latest_comment_date">You</p>' +
                                '<p class="upload-date-text" id="latest_comment_date">' + moment(comment.created_at).format('DD-MM-YYYY') + ' | ' + moment(comment.created_at).format('hh:mma') + '</p>' +
                                '</div>' +
                                '<p class="upload-desc-text" id="latest_admin_comment">' + comment.student_comment + '</p>' +
                                '<hr class="my-1">';
                        } else {
                            display_comments += '<div class="d-flex justify-content-between">' +
                                '<p class="upload-date-text" id="latest_comment_date">Admin</p>' +
                                '<p class="upload-date-text" id="latest_comment_date">' + moment(comment.created_at).format('DD-MM-YYYY') + ' | ' + moment(comment.created_at).format('hh:mma') + '</p>' +
                                '</div>' +
                                '<p class="upload-desc-text" id="latest_admin_comment">' + comment.admin_comment + '</p>' +
                                '<hr class="my-1">';
                        }
                    });

                    $('.display-comment').append(display_comments);
                } else {
                    $('.admission-exist').addClass('d-none');
                    $('.comment-section').addClass('d-none');
                    $('.admission-not-exist').removeClass('d-none');
                }
            }
        });
    }
    $('.chat-reply').on('click', function () {
        var student_comment = $('#student_comment').val();
        var admission_id = $('#admission_id').val();
        console.log('student_comment', student_comment);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "post",
            url: "/students/admission/storeComment",
            data: {
                admission_id: admission_id,
                student_comment: student_comment,
            },
            success: function (response) {
                $('.display-comment').html('');

                var comments = response.comments;
                var display_comments = '';
                $.each(comments, function (indexInArray, comment) {
                    if (comment.student_comment != null) {
                        display_comments += '<div class="d-flex justify-content-between">' +
                            '<p class="upload-date-text" id="latest_comment_date">You</p>' +
                            '<p class="upload-date-text" id="latest_comment_date">' + moment(comment.created_at).format('DD-MM-YYYY') + ' | ' + moment(comment.created_at).format('hh:mma') + '</p>' +
                            '</div>' +
                            '<p class="upload-desc-text" id="latest_admin_comment">' + comment.student_comment + '</p>' +
                            '<hr class="my-1">';
                    } else {
                        display_comments += '<div class="d-flex justify-content-between">' +
                            '<p class="upload-date-text" id="latest_comment_date">Admin</p>' +
                            '<p class="upload-date-text" id="latest_comment_date">' + moment(comment.created_at).format('DD-MM-YYYY') + ' | ' + moment(comment.created_at).format('hh:mma') + '</p>' +
                            '</div>' +
                            '<p class="upload-desc-text" id="latest_admin_comment">' + comment.admin_comment + '</p>' +
                            '<hr class="my-1">';
                    }
                });

                $('.display-comment').append(display_comments);
                $('#student_comment').val('');
            }
        });

    });
});

setTimeout(function () {
    $('.alert').fadeOut('fast');
}, 3000);


