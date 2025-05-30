@extends('frontend.layouts.app')
@section('title', 'Dashboard')
@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .student-year-switcher {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .student-year-switcher button {
            border: none;
        }

        .student-year-switcher button i {
            font-size: 24px;
        }

        .student-year-switcher #currentYear {
            font-weight: 600;
            font-size: 24px;
            line-height: 30px;
            margin: 0px;
            color: #1D1D1B;
        }

        .upload-doc-wrapper {
            height: 400px;
            overflow-y: scroll;
        }
    </style>
@endsection
@section('content')
    <section class="quick-links-section">
        <div class="container">
            @if (session('message'))
                <div class="alert alert-{{ session('status') }} alert-dismissible fade show" role="alert">
                    <strong>{{ session('message') }}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="quick-links-inr">
                @if ($admissionDate)
                    @if (date('Y-m-d') <= $admissionDate)
                    @else
                        <a href="#" class="quick-links-box" rel="noopener noreferrer">
                            <div class="quick-links-icon">
                                <img src="/assets/images/apply-admissions.png" alt="" class="hover-image">
                            </div>
                            <div class="quick-links-content">
                                <h3 class="quick-links-title">{{ $admissionLabel }}</h3>
                            </div>
                    @endif
                @endif
                @if (!$isStudentAdmissionExist)
                    @if ($admissionDate)
                        @if (date('Y-m-d') <= $admissionDate)
                            @if ($apologyLetterCount <= 3)
                                <a href="{{ route('student.admission.create') }}" class="quick-links-box"
                                    rel="noopener noreferrer">
                                    <div class="quick-links-icon">
                                        <img src="/assets/images/apply-admissions.png" alt="" class="hover-image">
                                    </div>
                                    <div class="quick-links-content">
                                        <h3 class="quick-links-title">Apply for an Admissions</h3>
                                    </div>
                                </a>
                            @endif
                        @endif
                    @elseif(!$admissionDate && count($admissions) == 0)
                        <a href="{{ route('student.admission.create') }}" class="quick-links-box" rel="noopener noreferrer">
                            <div class="quick-links-icon">
                                <img src="/assets/images/apply-admissions.png" alt="" class="hover-image">
                            </div>
                            <div class="quick-links-content">
                                <h3 class="quick-links-title">Apply for an Admissions</h3>
                            </div>
                        </a>
                    @endif
                @endif
                {{-- <a href="{{ route('student.admission.create') }}" class="quick-links-box" rel="noopener noreferrer">
                    <div class="quick-links-icon">
                        <img src="/assets/images/apply-admissions.png" alt="" class="hover-image">
                    </div>
                    <div class="quick-links-content">
                        <h3 class="quick-links-title">Apply for an Admissions</h3>
                    </div>
                </a> --}}
                <a href="{{ route('student.complain.create') }}" class="quick-links-box" rel="noopener noreferrer">
                    <div class="quick-links-icon">
                        <img src="/assets/images/file-complain.png" alt="" class="hover-image">
                    </div>
                    <div class="quick-links-content">
                        <h3 class="quick-links-title">File a Complain</h3>
                    </div>
                </a>
                <a href="{{ route('student.leave.create') }}" class="quick-links-box" rel="noopener noreferrer">
                    <div class="quick-links-icon">
                        <img src="/assets/images/apply-leave.png" alt="" class="hover-image">
                    </div>
                    <div class="quick-links-content">
                        <h3 class="quick-links-title">Apply for a Leave</h3>
                    </div>
                </a>
                <a href="{{ route('student.apology_letter.create') }}" class="quick-links-box" rel="noopener noreferrer">
                    <div class="quick-links-icon">
                        <img src="/assets/images/apology-letter.png" alt="" class="hover-image">
                    </div>
                    <div class="quick-links-content">
                        <h3 class="quick-links-title">Apology Letter</h3>
                    </div>
                </a>
                <a href="{{ route('student.event.index') }}" class="quick-links-box" rel="noopener noreferrer">
                    <div class="quick-links-icon">
                        <img src="/assets/images/list-events.png" alt="" class="hover-image">
                    </div>
                    <div class="quick-links-content">
                        <h3 class="quick-links-title">List of Events</h3>
                    </div>
                </a>
                <a href="{{ route('student.contact-page.index') }}" class="quick-links-box" rel="noopener noreferrer">
                    <div class="quick-links-icon">
                        <img src="/assets/images/contact-management.png" alt="" class="hover-image">
                    </div>
                    <div class="quick-links-content">
                        <h3 class="quick-links-title">Contact Management</h3>
                    </div>
                </a>

            </div>
        </div>
    </section>
    {{-- {{ $admissions }} --}}
    <section class="my-admission-section">
        <div class="container">
            <div class="row ">
                <div class="col-md-8 admission-main-block">
                    <h3 class="admission-card-title">My Admissions</h3>
                    <div class="admission-card ">
                        <div class="my-admission-edit d-flex justify-content-between align-items-center">
                            <div class="student-year-switcher">
                                <button class="nav-btn" id="prevYear"><i class="bi bi-chevron-left"></i></button>
                                <h6 class="year-label" id="currentYear"></h6>
                                <button class="nav-btn" id="nextYear"><i class="bi bi-chevron-right"></i></button>
                            </div>
                            @if ($admission)
                                <a href="" class="admission-edit">Edit</a>
                            @endif
                        </div>
                        <div class="admission-card-info row admission-exist">
                            <div class="col-md-6 admission-card-left admission-exist">
                                <input type="hidden" id="APP_URL" value="{{ config('APP_URL') }}">
                                <input type="hidden" id="admission_id">
                                <p>
                                    <strong>Admission Status:</strong>
                                    <span class="admission-status-box"></span>
                                </p>
                                <p><strong>Admission Date:</strong> <span class="admission-date-box"></span> </p>
                                <p><strong>Arrival Date at Hostel:</strong> <span
                                        class="admission-arriving-date-box"></span></p>
                                <p>
                                    <strong>Room Allotment:</strong>
                                    <span class="room-allot"></span>
                                </p>
                                <p class="uploaded-documents-text">
                                    <strong>Uploaded Documents:</strong>
                                    <span class="uploaded-documents"></span>
                                </p>
                            </div>
                            <div class="col-md-6 admission-card-right comment-section">
                                <div class=" upload-doc-header d-flex justify-content-between">
                                    <h3 class="upload-doc-title">Comments:</h3>
                                </div>
                                <div class="upload-doc-wrapper">
                                    <div class="display-comment"></div>
                                    <div class="chat-input-wrapper">
                                        <input type="text" class="chat-input" id="student_comment"
                                            name="student_comment" placeholder="Type your message here..." />
                                        <button class="chat-reply">Reply</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="admission-card-info row admission-not-exist d-none">
                            <div class="col-md-12 admission-card-left">
                                <p>Admission Not Found</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 imp-notice-main-block">
                    <h3 class="admission-card-title"> Important Notice</h3>
                    <div class="imp-notice-wrapper ">
                        <div class="my-admission-edit my-admission-notice">
                            <p class="mb-0">{{ $notice->title }}</p>
                        </div>
                        <div class="imp-notice-desc">
                            <p class="mb-0">{{ $notice->content }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>

    <section class="upcoming-events-section">
        <div class="container">
            <h3 class="admission-card-title">Upcoming Events</h3>
            <div class="upcoming-events-wrapper">
                @foreach ($events as $event)
                    <div class="upcoming-events-box">
                        <div class="event-box">
                            <span class="event-name">{{ $event->name }}</span>
                            <div class="event-box-info">
                                <p><strong>Event Date:</strong>
                                    @if (date('Y-m-d', strtotime($event->start_datetime)) == date('Y-m-d', strtotime($event->end_datetime)))
                                        {{ date('Y-m-d', strtotime($event->start_datetime)) }}
                                    @else
                                        {{ date('Y-m-d', strtotime($event->start_datetime)) }} -
                                        {{ date('Y-m-d', strtotime($event->end_datetime)) }}
                                    @endif
                                </p>
                                <p><strong>Event Time:</strong>
                                    {{ date('h:iA', strtotime($event->start_datetime)) }} -
                                    {{ date('h:iA', strtotime($event->end_datetime)) }}
                                </p>
                                <p><strong>Location:</strong> {{ $event->location }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script src="{{ asset('js/dashboard/dashboard_frontend.js') }}"></script>
    <script>
        const currentYear = new Date().getFullYear() + '-' + (new Date().getFullYear() + 1).toString().slice(-2);
        $('#currentYear').text(currentYear);

        const yearLabel = document.getElementById("currentYear");

        function updateNextYearVisibility() {
            const currentDisplayedYear = $('#currentYear').text().trim();
            if (currentDisplayedYear === currentYear) {
                $('#nextYear').prop('disabled', true);
                $('.admission-edit').removeClass('d-none');
            } else {
                $('#nextYear').prop('disabled', false);
                $('.admission-edit').addClass('d-none');
            }
        }

        updateNextYearVisibility(); // Initial check

        function getYears(label) {
            const parts = label.split("-");
            return parseInt(parts[0]);
        }

        function formatAcademicYear(startYear) {
            const endYear = (startYear + 1).toString().slice(-2); // Get last two digits
            return `${startYear}-${endYear}`;
        }

        document.getElementById("prevYear").addEventListener("click", () => {
            const currentStartYear = getYears(yearLabel.textContent);
            yearLabel.textContent = formatAcademicYear(currentStartYear - 1);
            updateNextYearVisibility();
        });

        document.getElementById("nextYear").addEventListener("click", () => {
            const currentStartYear = getYears(yearLabel.textContent);
            yearLabel.textContent = formatAcademicYear(currentStartYear + 1);
            updateNextYearVisibility();
        });
    </script>

@endsection
