{{-- <div class="row">
    <div class="col-sm-12 col-lg-12 mb-4">
        <div class="card card-border-shadow-primary h-100">
            <div class="card-header py-2 bg-none">
                <a href="{{ route('logout') }}" class="menu-link justify-content-end"
                    onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    <h5 class="card-title m-0 text-right"><i class="mdi mdi-power mdi-power-font"></i></h5>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </a>
            </div>
            <div class="card-body">
                <div class="align-items-center mb-2 pb-1 justify-content-between header">
                    <img src="{{ asset('assets/images/sklps-icon.png') }}" class="main-logo" alt="">
                    <h1 class="mb-0 header-text">Shree Kutchi Leva Patel Samaj – Ahmedabad</h1>
                    <div class="student-img d-flex align-items-center gap-3">
                        @php
                            $student = App\Models\Student::whereUserId(\Auth::user()->id)->first();
                            $data = $student
                                ? App\Models\StudentAdmissionMap::with('admission')
                                    ->whereStudentId($student->id)
                                    ->latest()
                                    ->first()
                                : '';
                            $course = $data ? App\Models\Course::whereId($data->admission->course_id)->first() : '';
                            $admission = $student
                                ? App\Models\StudentAdmissionMap::whereStudentId($student->id)
                                    ->where('admission_year', now()->year)
                                    ->pluck('admission_id')
                                    ->last()
                                : '';
                            $image = $admission
                                ? App\Models\Admission::select('student_photo_url')->where('id', $admission)->first()
                                : '';
                        @endphp
                        <div>
                            @if (empty($image['student_photo_url']))
                                @if ($student->gender == 'girl')
                                    <img src="{{ asset('assets/images/girl.png') }}" alt="student Photo"
                                        class="rounded-circle" width="120px" height="120px">
                                @else
                                    <img src="{{ asset('assets/images/boy.png') }}" alt="student Photo"
                                        class="rounded-circle" width="120px" height="120px">
                                @endif
                            @else
                                <img src="{{ asset($image['student_photo_url']) }}" alt="student Photo"
                                    class="rounded-circle" width="120px" height="120px">
                            @endif
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $student->first_name }}</h5>
                            <span>{{ $course->course_name ?? ' ' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}


<header class="bg-white top-header-section">
    <div class="container">
        <div class="top-header-inr d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <img class="site_logo" src="/assets/images/sklps-logo.png" alt="Logo" style="margin-right: 24px" />
                <div class="top-header-text">
                    <p class="text-small-top">Welcome to</p>
                    <h5 class="mb-0 top-header-name desktop">Shree Kutchi Leva Patel Samaj Ahmedabad</h5>
                    <h5 class="mb-0  top-header-name mobile">SKLPS - Ahmedabad</h5>
                </div>

            </div>
            <div class="user-box">
                @php
                    $student = App\Models\Student::whereUserId(\Auth::user()->id)->first();
                    $admission = $student
                        ? App\Models\StudentAdmissionMap::whereStudentId($student->id)
                            ->where('admission_year', now()->year)
                            ->pluck('admission_id')
                            ->last()
                        : '';
                    $image = $admission
                        ? App\Models\Admission::select('student_photo_url')->where('id', $admission)->first()
                        : '';
                @endphp
                {{-- <img src="https://i.pravatar.cc/40" class="rounded-circle" style="cursor: pointer" /> --}}
                @if (empty($image['student_photo_url']))
                    @if ($student->gender == 'girl')
                        <img src="{{ asset('assets/images/girl.png') }}" alt="student Photo" class="rounded-circle"
                            style="cursor: pointer">
                    @else
                        <img src="{{ asset('assets/images/boy.png') }}" alt="student Photo" class="rounded-circle"
                            style="cursor: pointer">
                    @endif
                @else
                    <img src="{{ asset($image['student_photo_url']) }}" alt="student Photo" class="rounded-circle"
                        style="cursor: pointer">
                @endif
                <span class="ms-2 user-name">{{ $student->first_name }}</span>


                <svg width="18" height="10" viewBox="0 0 18 10" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M1.63672 0.238281L9 7.60156L16.3633 0.238281L17.2617 1.13672L9.44922 8.94922L9 9.37891L8.55078 8.94922L0.738281 1.13672L1.63672 0.238281Z"
                        fill="#1D1D1B" />
                </svg>

                <div class="user-dropdown p-2">
                    <a href="{{ route('student.profile.edit', auth()->user()->id) }}"
                        class="d-block px-3 py-2 text-dark">Profile</a>
                    <a href="{{ route('student.profile.updatePasswordStudent') }}"
                        class="d-block px-3 py-2 text-dark">Change Password</a>
                    <a href="{{ route('logout') }}" class="d-block px-3 py-2 text-dark"
                        onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                        Log Out
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>
