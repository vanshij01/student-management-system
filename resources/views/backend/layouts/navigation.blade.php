@php
    use App\Models\Role;
    use App\Models\Permission;
    use App\Models\User;

    /* $role = Role::pluck('name', 'id'); */
    $loggedInUser = Auth::user();
    /* $role = []; */

    $permissions = [];
    $roleId = User::where('id', Auth::user()->id)->value('role_id');
    $data = Permission::where('role_id', $roleId)->pluck('module', 'id')->toArray();
    $permissions = array_unique($data);

    $isSuperAdmin = 0;
    if ($loggedInUser->role_id == 1) {
        $isSuperAdmin = 1;
    }
    // dd($permissions);
@endphp


<div class="col-md-2  admin-sidebar" id="admin_sidebar">

    <div class="text-center mb-4">
        <img src="/assets/images/sklps-logo.png" alt="Logo" class="img-fluid">
        <button type="button" id="closeSidebar" class="close-sidebar-btn">
            &times;
        </button>
    </div>
    <nav class="nav flex-column">
        <a class="nav-link {{ in_array(Route::current()->getName(), ['dashboard']) ? 'active' : '' }}"
            href="{{ route('dashboard') }}"><i class="las la-home"></i>Dashboard</a>

        @if ($isSuperAdmin == 1 || in_array('Admission', $permissions))
            <a class="nav-link {{ in_array(Route::current()->getName(), ['admission.index', 'admission.create', 'admission.edit', 'admission.show']) ? 'active' : '' }}"
                href="{{ route('admission.index') }}"><i class="las la-id-badge"></i> Admission</a>
        @endif
        @if ($isSuperAdmin == 1 || in_array('Fee', $permissions))
            <a class="nav-link {{ in_array(Route::current()->getName(), ['fees.index', 'fees.create', 'fees.edit', 'fees.show']) ? 'active' : '' }}"
                href="{{ route('fees.index') }}"><i class="las la-wallet"></i> Donation</a>
        @endif
        @if ($isSuperAdmin == 1 || in_array('Leave', $permissions))
            <a class="nav-link {{ in_array(Route::current()->getName(), ['leave.index', 'leave.create', 'leave.edit', 'leave.show']) ? 'active' : '' }}"
                href="{{ route('leave.index') }}"><i class="las la-calendar-day"></i> Leaves</a>
        @endif
        @if ($isSuperAdmin == 1 || in_array('Complain', $permissions))
            <a class="nav-link {{ in_array(Route::current()->getName(), ['complain.index', 'complain.create', 'complain.edit', 'complain.show']) ? 'active' : '' }}"
                href="{{ route('complain.index') }}"><i class="bi bi-exclamation-circle"></i> Complains</a>
        @endif
        @if ($isSuperAdmin == 1 || in_array('Student', $permissions))
            <a class="nav-link {{ in_array(Route::current()->getName(), ['student.index', 'student.create', 'student.edit', 'student.show']) ? 'active' : '' }}"
                href="{{ route('student.index') }}"><i class="lar la-user"></i> Students</a>
        @endif
        @if ($isSuperAdmin == 1 || in_array('Report', $permissions))
            <li class="menu {{ in_array(Route::current()->getName(), [
                'report.allotedStudents',
                'report.availableBeds',
                'report.dueFees',
                'report.idCardStudents'
                ]) ? 'open' : '' }}">
                <a class="nav-link" href="#"><i class="las la-chart-bar"></i> Reports</a>
                <ul class="menu-item">
                    <li><a class="nav-link {{ in_array(Route::current()->getName(), ['report.allotedStudents']) ? 'active' : '' }}"
                            href="{{ route('report.allotedStudents') }}">Allocated Student List</a></li>
                    <li><a class="nav-link {{ in_array(Route::current()->getName(), ['report.availableBeds']) ? 'active' : '' }}"
                            href="{{ route('report.availableBeds') }}">Available Beds</a></li>
                    <li><a class="nav-link {{ in_array(Route::current()->getName(), ['report.dueFees']) ? 'active' : '' }}"
                            href="{{ route('report.dueFees') }}">Due Fees</a></li>
                    <li><a class="nav-link {{ in_array(Route::current()->getName(), ['report.idCardStudents']) ? 'active' : '' }}"
                            href="{{ route('report.idCardStudents') }}">ID Card Student</a></li>
                </ul>
            </li>
        @endif
        {{-- <li class="menu">
            <a class="nav-link" href="#"><i class="las la-chart-bar"></i> Reports</a>
            <ul class="menu-item">
                <li><a class="nav-link {{ in_array(Route::current()->getName(), ['report.allotedStudents']) ? 'active' : '' }}"
                        href="{{ route('report.allotedStudents') }}">Allocated Student List</a></li>
                <li><a class="nav-link {{ in_array(Route::current()->getName(), ['report.availableBeds']) ? 'active' : '' }}"
                        href="{{ route('report.availableBeds') }}">Available Beds</a></li>
                <li><a class="nav-link {{ in_array(Route::current()->getName(), ['report.dueFees']) ? 'active' : '' }}"
                        href="{{ route('report.dueFees') }}">Due Fees</a></li>
                <li><a class="nav-link {{ in_array(Route::current()->getName(), ['report.idCardStudents']) ? 'active' : '' }}"
                        href="{{ route('report.idCardStudents') }}">ID Card Student</a></li>
            </ul>
        </li> --}}
        @if ($isSuperAdmin == 1 || in_array('Admin User', $permissions))
            <a class="nav-link {{ in_array(Route::current()->getName(), ['admin_user.index', 'admin_user.create', 'admin_user.edit', 'admin_user.show']) ? 'active' : '' }}"
                href="{{ route('admin_user.index') }}"><i class="lar la-user"></i> Admin Users</a>
        @endif
        @if ($isSuperAdmin == 1 || in_array('Warden', $permissions))
            <a class="nav-link {{ in_array(Route::current()->getName(), ['warden.index', 'warden.create', 'warden.edit', 'warden.show']) ? 'active' : '' }}"
                href="{{ route('warden.index') }}"><i class="las la-user-cog"></i> Wardens</a>
        @endif
        @if ($isSuperAdmin == 1 || in_array('Activity Log', $permissions))
            <a class="nav-link {{ in_array(Route::current()->getName(), ['activity-log.index']) ? 'active' : '' }}"
                href="{{ route('activity-log.index') }}"><i class="las la-chart-pie"></i> Activity</a>
        @endif
        @if ($isSuperAdmin == 1 || in_array('Apology Letter', $permissions))
            <a class="nav-link {{ in_array(Route::current()->getName(), ['apology_letter.index', 'apology_letter.create', 'apology_letter.edit', 'apology_letter.show']) ? 'active' : '' }}"
                href="{{ route('apology_letter.index') }}"><i class="las la-envelope-open-text"></i> Apology
                Letters</a>
        @endif
        @if ($isSuperAdmin == 1 || in_array('Event', $permissions))
            <a class="nav-link {{ in_array(Route::current()->getName(), ['event.index', 'event.create', 'event.edit', 'event.show']) ? 'active' : '' }}"
                href="{{ route('event.index') }}"><i class="las la-microphone-alt"></i> Events</a>
        @endif
        @if ($isSuperAdmin == 1 || 
            in_array('Role', $permissions) || 
            in_array('Hostel', $permissions) || 
            in_array('Room', $permissions) || 
            in_array('Bed', $permissions) || 
            in_array('Course', $permissions) || 
            in_array('Setting', $permissions))
            <li class="menu {{ in_array(Route::current()->getName(), [
                'role.index', 'role.create', 'role.edit', 'role.show',
                'hostel.index', 'hostel.create', 'hostel.edit', 'hostel.show',
                'room.index', 'room.create', 'room.edit', 'room.show',
                'bed.index', 'bed.create', 'bed.edit', 'bed.show',
                'course.index', 'course.create', 'course.edit', 'course.show',
                'setting.index', 'setting.create'
                ]) ? 'open' : '' }}">
                <a class="nav-link" href="#"><i class="las la-cog"></i> Configuration</a>
                <ul class="menu-item">
                    @if ($isSuperAdmin == 1 || in_array('Role', $permissions))
                        <li><a class="nav-link {{ in_array(Route::current()->getName(), ['role.index', 'role.create', 'role.edit', 'role.show']) ? 'active' : '' }}"
                                href="{{ route('role.index') }}">Role</a></li>
                    @endif
                    @if ($isSuperAdmin == 1 || in_array('Hostel', $permissions))
                        <li><a class="nav-link {{ in_array(Route::current()->getName(), ['hostel.index', 'hostel.create', 'hostel.edit', 'hostel.show']) ? 'active' : '' }}"
                                href="{{ route('hostel.index') }}">Hostel</a></li>
                    @endif
                    @if ($isSuperAdmin == 1 || in_array('Room', $permissions))
                        <li><a class="nav-link {{ in_array(Route::current()->getName(), ['room.index', 'room.create', 'room.edit', 'room.show']) ? 'active' : '' }}"
                                href="{{ route('room.index') }}">Room</a></li>
                    @endif
                    @if ($isSuperAdmin == 1 || in_array('Bed', $permissions))
                        <li><a class="nav-link {{ in_array(Route::current()->getName(), ['bed.index', 'bed.create', 'bed.edit', 'bed.show']) ? 'active' : '' }}"
                                href="{{ route('bed.index') }}">Bed</a></li>
                    @endif
                    @if ($isSuperAdmin == 1 || in_array('Course', $permissions))
                        <li><a class="nav-link {{ in_array(Route::current()->getName(), ['course.index', 'course.create', 'course.edit', 'course.show']) ? 'active' : '' }}"
                                href="{{ route('course.index') }}">Courses</a></li>
                    @endif
                    @if ($isSuperAdmin == 1 || in_array('Setting', $permissions))
                        <li><a class="nav-link {{ in_array(Route::current()->getName(), ['setting.index', 'setting.create']) ? 'active' : '' }}"
                                href="{{ route('setting.index') }}">Setting</a></li>
                    @endif
                </ul>
            </li>
        @endif
        {{-- <li class="menu">
            <a class="nav-link" href="#"><i class="las la-cog"></i> Configuration</a>
            <ul class="menu-item">
                @if ($isSuperAdmin == 1 || in_array('Role', $permissions))
                    <li><a class="nav-link {{ in_array(Route::current()->getName(), ['role.index', 'role.create', 'role.edit', 'role.show']) ? 'active' : '' }}"
                            href="{{ route('role.index') }}">Role</a></li>
                @endif
                @if ($isSuperAdmin == 1 || in_array('Hostel', $permissions))
                    <li><a class="nav-link {{ in_array(Route::current()->getName(), ['hostel.index', 'hostel.create', 'hostel.edit', 'hostel.show']) ? 'active' : '' }}"
                            href="{{ route('hostel.index') }}">Hostel</a></li>
                @endif
                @if ($isSuperAdmin == 1 || in_array('Room', $permissions))
                    <li><a class="nav-link {{ in_array(Route::current()->getName(), ['room.index', 'room.create', 'room.edit', 'room.show']) ? 'active' : '' }}"
                            href="{{ route('room.index') }}">Room</a></li>
                @endif
                @if ($isSuperAdmin == 1 || in_array('Bed', $permissions))
                    <li><a class="nav-link {{ in_array(Route::current()->getName(), ['bed.index', 'bed.create', 'bed.edit', 'bed.show']) ? 'active' : '' }}"
                            href="{{ route('bed.index') }}">Bed</a></li>
                @endif
                @if ($isSuperAdmin == 1 || in_array('Course', $permissions))
                    <li><a class="nav-link {{ in_array(Route::current()->getName(), ['course.index', 'course.create', 'course.edit', 'course.show']) ? 'active' : '' }}"
                            href="{{ route('course.index') }}">Courses</a></li>
                @endif
                @if ($isSuperAdmin == 1 || in_array('Setting', $permissions))
                    <li><a class="nav-link {{ in_array(Route::current()->getName(), ['setting.index', 'setting.create']) ? 'active' : '' }}"
                            href="{{ route('setting.index') }}">Setting</a></li>
                @endif
            </ul>
        </li> --}}
        <a class="nav-link" href="{{ route('logout') }}"
            onclick="event.preventDefault();
            document.getElementById('logout-form').submit();">
            <i class="las la-sign-out-alt"></i>
            <div data-i18n="Logout">Logout
            </div>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </a>
    </nav>
</div>
