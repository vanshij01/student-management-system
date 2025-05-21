$(document).ready(function () {
    setDefaultAcademicYear();

    $("#prevYear").on("click", function () {
        const currentStartYear = getYears($("#currentYear").text());
        $("#currentYear").text(formatAcademicYear(currentStartYear - 1));
    });

    $("#nextYear").on("click", function () {
        const currentStartYear = getYears($("#currentYear").text());
        $("#currentYear").text(formatAcademicYear(currentStartYear + 1));
    });

    $('#prevYear, #nextYear').on('click', function () {
        var year = $('#currentYear').text();
        allData(year);
    });

    var year = $('#currentYear').text();
    allData(year);

    function allData(year) {
        $.ajax({
            type: "get",
            url: "/yearInfo",
            data: {
                year: year
            },
            success: function (response) {
                $('#total_admission').text(response.admission);
                $('#total_complain').text(response.complains);
                $('#total_donors').text(response.fees);
                $('#total_hostel').text(response.hostel);
                $('#total_room').text(response.room);
                $('#total_bed').text(response.bed);
                $('#total_course').text(response.course);
                $('#hostelTable tbody').empty();
                $('#availableBedTable tbody').empty();
                $('#admissionStatusTable tbody').empty();
                $('#hostelTable tfoot').empty();
                $('#availableBedTable tfoot').empty();
                $('#admissionStatusTable tfoot').empty();
                var boystotal = 0;
                var girlsTotal = 0;
                var bedTotal = 0;
                var statusTotal = 0;
                $.each(response.hostel_gender, function (indexInArray, valueOfElement) {
                    var hostel = "";
                    boystotal += valueOfElement.boys;
                    girlsTotal += valueOfElement.girls;
                    hostel += '<tr>' +
                        '<td>' + (indexInArray + 1) + '</td>' +
                        '<td>' + valueOfElement.hostel_name + '</td>' +
                        '<td>' + valueOfElement.boys + '</td>' +
                        '<td>' + valueOfElement.girls + '</td>' +
                        '</tr>';
                    $('#hostelTable tbody').append(hostel);
                });
                $('#hostelTable tfoot').append('<tr><th>Total</th><th></th><th>' + boystotal + '</th><th>' + girlsTotal + '</th></tr>');
                $.each(response.available_bed, function (indexInArray, valueOfElement) {
                    var bed = "";
                    bedTotal += valueOfElement.beds;
                    bed += '<tr>' +
                        '<td>' + (indexInArray + 1) + '</td>' +
                        '<td>' + valueOfElement.hostel_name + '</td>' +
                        '<td>' + valueOfElement.beds + '</td>' +
                        '</tr>';
                    $('#availableBedTable tbody').append(bed);
                });
                $('#availableBedTable tfoot').append('<tr><th>Total</th><th></th><th>' + bedTotal + '</th></tr>');

                $('#pending_admission').text('0');
                $('#confirm_admission').text('0');
                $('#rejected_admission').text('0');

                $.each(response.admission_status, function (key, valueOfElement) {
                    if (key == 0) {
                        $('#pending_admission').text(valueOfElement);
                    } else if (key == 1) {
                        $('#confirm_admission').text(valueOfElement);
                    } else if (key == 2) {
                        $('#rejected_admission').text(valueOfElement);
                    }
                });

                $('#solved_complain').text('0');
                $('#unsolved_complain').text('0');

                var sum = 0
                $.each(response.complain_status, function (key, valueOfElement) {
                    if (key == 4) {
                        $('#solved_complain').text(valueOfElement);
                    } else {
                        sum += parseInt(valueOfElement)
                        $('#unsolved_complain').text(sum);
                    }
                });
            }
        });
    }
});

setTimeout(function () {
    $('.alert').fadeOut('fast');
}, 3000);


function getYears(label) {
    const parts = label.split("-");
    return parseInt(parts[0]);
}

function formatAcademicYear(startYear) {
    return `${startYear}-${startYear + 1}`;
}

function setDefaultAcademicYear() {
    const currentDate = new Date();
    let startYear = currentDate.getFullYear();
    const currentMonth = currentDate.getMonth() + 1;

    if (currentMonth < 4) {
        startYear -= 1;
    }

    $("#currentYear").text(formatAcademicYear(startYear));
}

