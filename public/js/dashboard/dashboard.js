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
                $('#total_donors').text(response.totalFeesReportData);
                $('#first_half_pending').text(response.totalFeesReportData - response.firstHalfReportData);
                $('#second_half_pending').text(response.totalFeesReportData - response.secondHalfReportData);
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
                var available_beds = response.available_bed;
                var allocated_beds = response.allocated_bed;
                var all_beds = response.all_bed;

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

                $.each(available_beds, function (indexInArray, available_bed) {
                    if (indexInArray == 0) {
                        $('.women_available_beds').text(available_bed.beds);
                    }
                    if (indexInArray == 2) {
                        $('.men_available_beds').text(available_bed.beds);
                    }
                    if (indexInArray == 1) {
                        $('.job_available_beds').text(available_bed.beds);
                    }
                });

                $.each(allocated_beds, function (indexInArray, allocated_bed) {
                    if (indexInArray == 0) {
                        $('.women_allocated_beds').text(allocated_bed.beds);
                    }
                    if (indexInArray == 2) {
                        $('.men_allocated_beds').text(allocated_bed.beds);
                    }
                    if (indexInArray == 1) {
                        $('.job_allocated_beds').text(allocated_bed.beds);
                    }
                });

                $.each(all_beds, function (indexInArray, all_bed) {
                    if (indexInArray == 0) {
                        $('.women_all_beds').text(all_bed.beds);
                    }
                    if (indexInArray == 2) {
                        $('.men_all_beds').text(all_bed.beds);
                    }
                    if (indexInArray == 1) {
                        $('.job_all_beds').text(all_bed.beds);
                    }
                });

                var women_available_beds = parseInt($('.women_available_beds').text());
                var women_allocated_beds = parseInt($('.women_allocated_beds').text());
                womenHostelGraph(women_available_beds, women_allocated_beds);
                var men_available_beds = parseInt($('.men_available_beds').text());
                var men_allocated_beds = parseInt($('.men_allocated_beds').text());
                menHostelGraph(men_available_beds, men_allocated_beds);
                var job_available_beds = parseInt($('.job_available_beds').text());
                var job_allocated_beds = parseInt($('.job_allocated_beds').text());
                jobHostelGraph(job_available_beds, job_allocated_beds);
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

function womenHostelGraph(women_available_beds, women_allocated_beds) {
    Highcharts.chart('women-chart', {
        chart: {
            type: 'pie',
            height: '100%'
        },
        title: {
            text: ''
        },
        subtitle: {
            text: ''
        },
        colors: ['#FFB42D', '#18A8B0'],
        plotOptions: {
            pie: {
                shadow: false,
                center: ['50%', '50%'],
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '{point.name}: {point.y}',
                    distance: 30
                },
                size: '100%' // Makes the chart fill the container
            }
        },
        series: [{
            name: 'Beds',
            colorByPoint: true,
            data: [
                {
                    name: 'Available Beds',
                    y: women_available_beds
                },
                {
                    name: 'Allocated Beds',
                    y: women_allocated_beds
                }
            ]
        }]
    });
}

function menHostelGraph(men_available_beds, men_allocated_beds) {
    Highcharts.chart('men-chart', {
        chart: {
            type: 'pie',
            height: '100%'
        },
        title: {
            text: ''
        },
        subtitle: {
            text: ''
        },
        colors: ['#FFB42D', '#18A8B0'],
        plotOptions: {
            pie: {
                shadow: false,
                center: ['50%', '50%'],
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '{point.name}: {point.y}',
                    distance: 30
                },
                size: '100%' // Makes the chart fill the container
            }
        },
        series: [{
            name: 'Beds',
            colorByPoint: true,
            data: [
                {
                    name: 'Available Beds',
                    y: men_available_beds
                },
                {
                    name: 'Allocated Beds',
                    y: men_allocated_beds
                }
            ]
        }]
    });
}

function jobHostelGraph(job_available_beds, job_allocated_beds) {
    Highcharts.chart('job-chart', {
        chart: {
            type: 'pie',
            height: '100%'
        },
        title: {
            text: ''
        },
        subtitle: {
            text: ''
        },
        colors: ['#FFB42D', '#18A8B0'],
        plotOptions: {
            pie: {
                shadow: false,
                center: ['50%', '50%'],
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '{point.name}: {point.y}',
                    distance: 30
                },
                size: '100%' // Makes the chart fill the container
            }
        },
        series: [{
            name: 'Beds',
            colorByPoint: true,
            data: [
                {
                    name: 'Available Beds',
                    y: job_available_beds
                },
                {
                    name: 'Allocated Beds',
                    y: job_allocated_beds
                }
            ]
        }]
    });
}

