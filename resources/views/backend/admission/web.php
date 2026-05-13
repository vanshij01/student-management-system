<?php

use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\Backend\ActivityLogController;
use App\Http\Controllers\Backend\AdminUserController;
use App\Http\Controllers\Backend\AdmissionController;
use App\Http\Controllers\Backend\ApologyLetterController;
use App\Http\Controllers\Backend\BedController;
use App\Http\Controllers\Backend\ComplainController;
use App\Http\Controllers\Backend\CountryController;
use App\Http\Controllers\Backend\CourseController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\DocumentTypeController;
use App\Http\Controllers\Backend\EventsController;
use App\Http\Controllers\Backend\FeesController;
use App\Http\Controllers\Backend\HostelController;
use App\Http\Controllers\Backend\LeaveController;
use App\Http\Controllers\Backend\ReportController;
use App\Http\Controllers\Backend\ReservationController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\RoomController;
use App\Http\Controllers\Backend\SettingController;
use App\Http\Controllers\Backend\StudentController;
use App\Http\Controllers\Backend\VillageController;
use App\Http\Controllers\Backend\WardenController;
use App\Http\Controllers\Frontend\AdmissionController as FrontendAdmissionController;
use App\Http\Controllers\Frontend\ApologyLetterController as FrontendApologyLetterController;
use App\Http\Controllers\Frontend\ComplainController as FrontendComplainController;
use App\Http\Controllers\Frontend\DashboardController as FrontendDashboardController;
use App\Http\Controllers\Frontend\EventController as FrontendEventController;
use App\Http\Controllers\Frontend\LeaveController as FrontendLeaveController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/* Route::get('/', function () {
    return view('welcome');
}); */

/* Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard'); */


Route::get('verify/resend', [TwoFactorController::class, 'resend'])->name('verify.resend');
Route::resource('verify', TwoFactorController::class)->only(['index', 'store']);
Route::get('documentTypes', [FrontendAdmissionController::class, 'documentTypes'])->name('student.admission.documentTypes');

Route::middleware('auth', 'twofactor', 'adminRole')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/yearInfo', [DashboardController::class, 'yearInfo'])->name('dashboard.yearInfo');

    Route::prefix('profile')->group(function () {
        Route::get('/{profile}/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::get('/', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
    });

    Route::group(['middleware' => 'role:Activity Log'], function () {
        Route::get('activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');
    });

    Route::prefix('admin_user')->group(function () {
        Route::group(['middleware' => 'role:Admin User'], function () {
            Route::get('', [AdminUserController::class, 'index'])->name('admin_user.index');
            Route::get('adminUserData', [AdminUserController::class, 'adminUserData'])->name('admin_user.adminUserData');
        });
        Route::group(['middleware' => 'permission:Admin User,create'], function () {
            Route::get('/create', [AdminUserController::class, 'create'])->name('admin_user.create');
            Route::post('/store', [AdminUserController::class, 'store'])->name('admin_user.store');
        });
        Route::group(['middleware' => 'permission:Admin User,update'], function () {
            Route::get('/{id}/edit', [AdminUserController::class, 'edit'])->name('admin_user.edit');
            Route::put('/{id}', [AdminUserController::class, 'update'])->name('admin_user.update');
        });
        Route::group(['middleware' => 'permission:Admin User,read'], function () {
            Route::get('/{id}', [AdminUserController::class, 'show'])->name('admin_user.show');
        });
        Route::group(['middleware' => 'permission:Admin User,delete'], function () {
            Route::get('delete/{id}', [AdminUserController::class, 'destroy'])->name('admin_user.destroy');
        });
    });

    Route::prefix('admission')->group(function () {
        Route::group(['middleware' => 'role:Admission'], function () {
            Route::get('', [AdmissionController::class, 'index'])->name('admission.index');
            Route::get('admissionData', [AdmissionController::class, 'admissionData'])->name('admission.admissionData');
        });
        Route::group(['middleware' => 'permission:Admission,create'], function () {
            Route::get('/create', [AdmissionController::class, 'create'])->name('admission.create');
            Route::post('/store', [AdmissionController::class, 'store'])->name('admission.store');
        });
        Route::group(['middleware' => 'permission:Admission,update'], function () {
            Route::get('/{id}/edit', [AdmissionController::class, 'edit'])->name('admission.edit');
            Route::put('/{id}', [AdmissionController::class, 'update'])->name('admission.update');
        });
        Route::group(['middleware' => 'permission:Admission,read'], function () {
            Route::get('/{id}', [AdmissionController::class, 'show'])->name('admission.show');
        });
        Route::group(['middleware' => 'permission:Admission,delete'], function () {
            Route::get('delete/{id}', [AdmissionController::class, 'destroy'])->name('admission.destroy');
        });
        Route::post('/sendComment', [AdmissionController::class, 'sendComment'])->name('admission.sendComment');
        Route::post('/sendStatusRemark', [AdmissionController::class, 'sendStatusRemark'])->name('admission.sendStatusRemark');
        Route::post('/sendBacklogStatus', [AdmissionController::class, 'sendBacklogStatus'])->name('admission.sendBacklogStatus');
        Route::get('getAdmissionDataById/{id}', [AdmissionController::class, 'getAdmissionDataById'])->name('admission.getAdmissionDataById');
        Route::get('feesReceipt/{id}', [AdmissionController::class, 'feesReceipt'])->name('admission.feesReceipt');
        Route::post('getCoursesByEducation', [AdmissionController::class, 'getCoursesByEducation'])->name('admission.getCoursesByEducation');
        Route::post('/admission-file-upload', [AdmissionController::class, 'processAdmissionFileUpload'])->name('backend.admission.file-upload');
        Route::get('/get-student-data/{id}', [AdmissionController::class, 'getStudentData'])->name('students.get-student-data');
        Route::get('/getCoursesById', [AdmissionController::class, 'getCoursesById'])->name('admission.getCoursesById');
        Route::get('getStudentAdmissionDataBackend/{id}/{studentid}', [AdmissionController::class, 'getStudentAdmissionData'])->name('admission.getStudentAdmissionData');
        Route::get('fetch-student-data/{studentId}', [AdmissionController::class, 'fetchStudentAdmissionData'])->name('admission.fetch');
        Route::get('document/{path}/download', [AdmissionController::class, 'download'])->name('admission.document.download');
        Route::get('images/{id}/download', [AdmissionController::class, 'downloadImage'])->name('admission.images.download');
        Route::get('/get-comments/{id}', [AdmissionController::class, 'getComments'])->name('admission.getComments');
    });

    Route::prefix('apology_letter')->group(function () {
        Route::group(['middleware' => 'role:Apology Letter'], function () {
            Route::get('', [ApologyLetterController::class, 'index'])->name('apology_letter.index');
            Route::get('apologyLetterData', [ApologyLetterController::class, 'apologyLetterData'])->name('apology_letter.apologyLetterData');
        });
        Route::group(['middleware' => 'permission:Apology Letter,create'], function () {
            Route::get('/create', [ApologyLetterController::class, 'create'])->name('apology_letter.create');
            Route::post('/store', [ApologyLetterController::class, 'store'])->name('apology_letter.store');
        });
        Route::group(['middleware' => 'permission:Apology Letter,update'], function () {
            Route::get('/{id}/edit', [ApologyLetterController::class, 'edit'])->name('apology_letter.edit');
            Route::put('/{id}', [ApologyLetterController::class, 'update'])->name('apology_letter.update');
        });
        Route::group(['middleware' => 'permission:Apology Letter,read'], function () {
            Route::get('/{id}', [ApologyLetterController::class, 'show'])->name('apology_letter.show');
        });
        Route::group(['middleware' => 'permission:Apology Letter,delete'], function () {
            Route::get('delete/{id}', [ApologyLetterController::class, 'destroy'])->name('apology_letter.destroy');
        });
    });

    Route::prefix('bed')->group(function () {
        Route::group(['middleware' => 'role:Bed'], function () {
            Route::get('', [BedController::class, 'index'])->name('bed.index');
            Route::get('bedData', [BedController::class, 'bedData'])->name('bed.bedData');
        });
        Route::group(['middleware' => 'permission:Bed,create'], function () {
            Route::get('/create', [BedController::class, 'create'])->name('bed.create');
            Route::post('/store', [BedController::class, 'store'])->name('bed.store');
        });
        Route::group(['middleware' => 'permission:Bed,update'], function () {
            Route::get('/{id}/edit', [BedController::class, 'edit'])->name('bed.edit');
            Route::put('/{id}', [BedController::class, 'update'])->name('bed.update');
        });
        Route::group(['middleware' => 'permission:Bed,read'], function () {
            Route::get('/{id}', [BedController::class, 'show'])->name('bed.show');
        });
        Route::group(['middleware' => 'permission:Bed,delete'], function () {
            Route::get('delete/{id}', [BedController::class, 'destroy'])->name('bed.destroy');
        });
        Route::get('getBedList/{id}', [BedController::class, 'getBedList'])->name('bed.getBedList');
    });

    Route::prefix('complain')->group(function () {
        Route::get('', [ComplainController::class, 'index'])->name('complain.index');
        Route::group(['middleware' => 'role:Complain'], function () {
            Route::get('complainData', [ComplainController::class, 'complainData'])->name('complain.complainData');
        });
        Route::group(['middleware' => 'permission:Complain,create'], function () {
            Route::get('/create', [ComplainController::class, 'create'])->name('complain.create');
            Route::post('/store', [ComplainController::class, 'store'])->name('complain.store');
        });
        Route::group(['middleware' => 'permission:Complain,update'], function () {
            Route::get('/{id}/edit', [ComplainController::class, 'edit'])->name('complain.edit');
            Route::put('/{id}', [ComplainController::class, 'update'])->name('complain.update');
        });
        Route::group(['middleware' => 'permission:Complain,read'], function () {
            Route::get('/{id}', [ComplainController::class, 'show'])->name('complain.show');
        });
        Route::group(['middleware' => 'permission:Complain,delete'], function () {
            Route::get('delete/{id}', [ComplainController::class, 'destroy'])->name('complain.destroy');
        });
    });

    Route::prefix('country')->group(function () {
        Route::group(['middleware' => 'role:Country'], function () {
            Route::get('', [CountryController::class, 'index'])->name('country.index');
            Route::get('countryData', [CountryController::class, 'countryData'])->name('country.countryData');
        });
        Route::group(['middleware' => 'permission:Country,create'], function () {
            Route::get('/create', [CountryController::class, 'create'])->name('country.create');
            Route::post('/store', [CountryController::class, 'store'])->name('country.store');
        });
        Route::group(['middleware' => 'permission:Country,update'], function () {
            Route::get('/{id}/edit', [CountryController::class, 'edit'])->name('country.edit');
            Route::put('/{id}', [CountryController::class, 'update'])->name('country.update');
        });
        Route::group(['middleware' => 'permission:Country,read'], function () {
            Route::get('/{id}', [CountryController::class, 'show'])->name('country.show');
        });
        Route::group(['middleware' => 'permission:Country,delete'], function () {
            Route::get('delete/{id}', [CountryController::class, 'destroy'])->name('country.destroy');
        });
    });

    Route::prefix('course')->group(function () {
        Route::group(['middleware' => 'role:Course'], function () {
            Route::get('', [CourseController::class, 'index'])->name('course.index');
            Route::get('courseData', [CourseController::class, 'courseData'])->name('course.courseData');
        });
        Route::group(['middleware' => 'permission:Course,create'], function () {
            Route::get('/create', [CourseController::class, 'create'])->name('course.create');
            Route::post('/store', [CourseController::class, 'store'])->name('course.store');
        });
        Route::group(['middleware' => 'permission:Course,update'], function () {
            Route::get('/{id}/edit', [CourseController::class, 'edit'])->name('course.edit');
            Route::put('/{id}', [CourseController::class, 'update'])->name('course.update');
        });
        Route::group(['middleware' => 'permission:Course,read'], function () {
            Route::get('/{id}', [CourseController::class, 'show'])->name('course.show');
        });
        Route::group(['middleware' => 'permission:Course,delete'], function () {
            Route::get('delete/{id}', [CourseController::class, 'destroy'])->name('course.destroy');
        });
    });

    Route::prefix('document_type')->group(function () {
        Route::group(['middleware' => 'role:Document Type'], function () {
            Route::get('', [DocumentTypeController::class, 'index'])->name('document_type.index');
            Route::get('documentTypeData', [DocumentTypeController::class, 'documentTypeData'])->name('document_type.documentTypeData');
        });
        Route::group(['middleware' => 'permission:Document Type,create'], function () {
            Route::get('/create', [DocumentTypeController::class, 'create'])->name('document_type.create');
            Route::post('/store', [DocumentTypeController::class, 'store'])->name('document_type.store');
        });
        Route::group(['middleware' => 'permission:Document Type,update'], function () {
            Route::get('/{id}/edit', [DocumentTypeController::class, 'edit'])->name('document_type.edit');
            Route::put('/{id}', [DocumentTypeController::class, 'update'])->name('document_type.update');
        });
        Route::group(['middleware' => 'permission:Document Type,read'], function () {
            Route::get('/{id}', [DocumentTypeController::class, 'show'])->name('document_type.show');
        });
        Route::group(['middleware' => 'permission:Document Type,delete'], function () {
            Route::get('delete/{id}', [DocumentTypeController::class, 'destroy'])->name('document_type.destroy');
        });
    });

    Route::prefix('fees')->group(function () {
        Route::group(['middleware' => 'role:Fee'], function () {
            Route::get('', [FeesController::class, 'index'])->name('fees.index');
            Route::get('feesData', [FeesController::class, 'feesData'])->name('fees.feesData');
        });
        Route::group(['middleware' => 'permission:Fee,create'], function () {
            Route::get('/create', [FeesController::class, 'create'])->name('fees.create');
            Route::post('/store', [FeesController::class, 'store'])->name('fees.store');
        });
        Route::group(['middleware' => 'permission:Fee,update'], function () {
            Route::get('/{id}/edit', [FeesController::class, 'edit'])->name('fees.edit');
            Route::put('/{id}', [FeesController::class, 'update'])->name('fees.update');
        });
        Route::group(['middleware' => 'permission:Fee,read'], function () {
            Route::get('/{id}', [FeesController::class, 'show'])->name('fees.show');
        });
        Route::group(['middleware' => 'permission:Fee,delete'], function () {
            Route::get('delete/{id}', [FeesController::class, 'destroy'])->name('fees.destroy');
        });
        Route::get('getFeesData/{id}', [FeesController::class, 'getFeesData'])->name('fees.getFeesData');
        Route::get('/email-pdf/{id}', [FeesController::class, 'emailPdf'])->name('fees.email-pdf');
    });

    Route::prefix('hostel')->group(function () {
        Route::group(['middleware' => 'role:Hostel'], function () {
            Route::get('', [HostelController::class, 'index'])->name('hostel.index');
            Route::get('hostelData', [HostelController::class, 'hostelData'])->name('hostel.hostelData');
        });
        Route::group(['middleware' => 'permission:Hostel,create'], function () {
            Route::get('/create', [HostelController::class, 'create'])->name('hostel.create');
            Route::post('/store', [HostelController::class, 'store'])->name('hostel.store');
        });
        Route::group(['middleware' => 'permission:Hostel,update'], function () {
            Route::get('/{id}/edit', [HostelController::class, 'edit'])->name('hostel.edit');
            Route::put('/{id}', [HostelController::class, 'update'])->name('hostel.update');
        });
        Route::group(['middleware' => 'permission:Hostel,read'], function () {
            Route::get('/{id}', [HostelController::class, 'show'])->name('hostel.show');
        });
        Route::group(['middleware' => 'permission:Hostel,delete'], function () {
            Route::get('delete/{id}', [HostelController::class, 'destroy'])->name('hostel.destroy');
        });
    });

    Route::prefix('leave')->group(function () {
        Route::get('', [LeaveController::class, 'index'])->name('leave.index');
        Route::get('leaveData', [LeaveController::class, 'leaveData'])->name('leave.leaveData');
        Route::group(['middleware' => 'permission:Leave,create'], function () {
            Route::get('/create', [LeaveController::class, 'create'])->name('leave.create');
            Route::post('/store', [LeaveController::class, 'store'])->name('leave.store');
        });
        Route::group(['middleware' => 'permission:Leave,update'], function () {
            Route::get('/{id}/edit', [LeaveController::class, 'edit'])->name('leave.edit');
            Route::put('/{id}', [LeaveController::class, 'update'])->name('leave.update');
        });
        Route::group(['middleware' => 'permission:Leave,read'], function () {
            Route::get('/{id}', [LeaveController::class, 'show'])->name('leave.show');
        });
        Route::group(['middleware' => 'permission:Leave,delete'], function () {
            Route::get('delete/{id}', [LeaveController::class, 'destroy'])->name('leave.destroy');
        });
    });

    Route::prefix('reservation')->group(function () {
        Route::group(['middleware' => 'role:Reservation'], function () {
            Route::get('', [ReservationController::class, 'index'])->name('reservation.index');
            Route::get('reservationData', [ReservationController::class, 'reservationData'])->name('reservation.reservationData');
        });
        Route::group(['middleware' => 'permission:Reservation,create'], function () {
            Route::get('/create', [ReservationController::class, 'create'])->name('reservation.create');
            Route::post('/store', [ReservationController::class, 'store'])->name('reservation.store');
        });
        Route::group(['middleware' => 'permission:Reservation,update'], function () {
            Route::get('/{id}/edit', [ReservationController::class, 'edit'])->name('reservation.edit');
            Route::put('/{id}', [ReservationController::class, 'update'])->name('reservation.update');
        });
        Route::group(['middleware' => 'permission:Reservation,read'], function () {
            Route::get('/{id}', [ReservationController::class, 'show'])->name('reservation.show');
        });
        Route::group(['middleware' => 'permission:Reservation,delete'], function () {
            Route::get('delete/{id}', [ReservationController::class, 'destroy'])->name('reservation.destroy');
        });
        Route::get('getReservationList/{id}', [ReservationController::class, 'getReservationList'])->name('reservation.getReservationList');
    });

    Route::prefix('report')->group(function () {
        Route::group(['middleware' => 'role:Report'], function () {
            Route::get('dueFees', [ReportController::class, 'dueFees'])->name('report.dueFees');
            Route::get('dueFeesReportData', [ReportController::class, 'dueFeesReportData'])->name('report.dueFeesReportData');
            Route::get('availableBeds', [ReportController::class, 'availableBeds'])->name('report.availableBeds');
            Route::get('availableBedsReportData', [ReportController::class, 'availableBedsReportData'])->name('report.availableBedsReportData');
            Route::get('allotedStudents', [ReportController::class, 'allotedStudents'])->name('report.allotedStudents');
            Route::get('allotedStudentsReportData', [ReportController::class, 'allotedStudentsReportData'])->name('report.allotedStudentsReportData');
            Route::get('releaseBed/{id}', [ReportController::class, 'releaseBed'])->name('report.releaseBed');
            Route::get('idCardStudents', [ReportController::class, 'idCardStudents'])->name('report.idCardStudents');
            Route::get('idCardStudentsReportData', [ReportController::class, 'idCardStudentsReportData'])->name('report.idCardStudentsReportData');
        });
    });

    Route::prefix('role')->group(function () {
        Route::get('', [RoleController::class, 'index'])->name('role.index');
        Route::get('roleData', [RoleController::class, 'roleData'])->name('role.roleData');
        /* Route::group(['middleware' => 'permission:Warden,create'], function () {
        });
        Route::group(['middleware' => 'permission:Warden,update'], function () {
        });
        Route::group(['middleware' => 'permission:Warden,read'], function () {
        });
        Route::group(['middleware' => 'permission:Warden,delete'], function () {
        }); */
        Route::get('/create', [RoleController::class, 'create'])->name('role.create');
        Route::post('/store', [RoleController::class, 'store'])->name('role.store');
        Route::get('/{id}', [RoleController::class, 'show'])->name('role.show');
        Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('role.edit');
        Route::put('/{id}', [RoleController::class, 'update'])->name('role.update');
        Route::get('delete/{id}', [RoleController::class, 'destroy'])->name('role.destroy');
    });

    Route::prefix('room')->group(function () {
        Route::group(['middleware' => 'role:Room'], function () {
            Route::get('', [RoomController::class, 'index'])->name('room.index');
            Route::get('roomData', [RoomController::class, 'roomData'])->name('room.roomData');
        });
        Route::group(['middleware' => 'permission:Room,create'], function () {
            Route::get('/create', [RoomController::class, 'create'])->name('room.create');
            Route::post('/store', [RoomController::class, 'store'])->name('room.store');
        });
        Route::group(['middleware' => 'permission:Room,update'], function () {
            Route::get('/{id}/edit', [RoomController::class, 'edit'])->name('room.edit');
            Route::put('/{id}', [RoomController::class, 'update'])->name('room.update');
        });
        Route::group(['middleware' => 'permission:Room,read'], function () {
            Route::get('/{id}', [RoomController::class, 'show'])->name('room.show');
        });
        Route::group(['middleware' => 'permission:Room,delete'], function () {
            Route::get('delete/{id}', [RoomController::class, 'destroy'])->name('room.destroy');
        });
        Route::get('getRoomList/{id}', [RoomController::class, 'getRoomList'])->name('room.getRoomList');
    });

    Route::prefix('setting')->group(function () {
        Route::group(['middleware' => 'role:Setting'], function () {
            Route::get('', [SettingController::class, 'index'])->name('setting.index');
        });
        Route::group(['middleware' => 'permission:Setting,create'], function () {
            Route::get('/update', [SettingController::class, 'create'])->name('setting.create');
            Route::post('/store', [SettingController::class, 'store'])->name('setting.store');
        });
    });

    Route::prefix('student')->group(function () {
        Route::group(['middleware' => 'role:Student'], function () {
            Route::get('', [StudentController::class, 'index'])->name('student.index');
            Route::get('studentData', [StudentController::class, 'studentData'])->name('student.studentData');
        });
        Route::group(['middleware' => 'permission:Student,create'], function () {
            Route::get('/create', [StudentController::class, 'create'])->name('student.create');
            Route::post('/store', [StudentController::class, 'store'])->name('student.store');
        });
        Route::group(['middleware' => 'permission:Student,update'], function () {
            Route::get('/{id}/edit', [StudentController::class, 'edit'])->name('student.edit');
            Route::put('/{id}', [StudentController::class, 'update'])->name('student.update');
        });
        Route::group(['middleware' => 'permission:Student,read'], function () {
            Route::get('/{id}', [StudentController::class, 'show'])->name('student.show');
        });
        Route::group(['middleware' => 'permission:Student,delete'], function () {
            Route::get('delete/{id}', [StudentController::class, 'destroy'])->name('student.destroy');
        });
        Route::get('getStudentDataById/{id}', [StudentController::class, 'getStudentDataById'])->name('student.getStudentDataById');
    });

    Route::prefix('village')->group(function () {
        Route::group(['middleware' => 'role:Village'], function () {
            Route::get('', [VillageController::class, 'index'])->name('village.index');
            Route::get('villageData', [VillageController::class, 'villageData'])->name('village.villageData');
        });
        Route::group(['middleware' => 'permission:Village,create'], function () {
            Route::get('/create', [VillageController::class, 'create'])->name('village.create');
            Route::post('/store', [VillageController::class, 'store'])->name('village.store');
        });
        Route::group(['middleware' => 'permission:Village,update'], function () {
            Route::get('/{id}/edit', [VillageController::class, 'edit'])->name('village.edit');
            Route::put('/{id}', [VillageController::class, 'update'])->name('village.update');
        });
        Route::group(['middleware' => 'permission:Village,read'], function () {
            Route::get('/{id}', [VillageController::class, 'show'])->name('village.show');
        });
        Route::group(['middleware' => 'permission:Village,delete'], function () {
            Route::get('delete/{id}', [VillageController::class, 'destroy'])->name('village.destroy');
        });
    });

    Route::prefix('warden')->group(function () {
        Route::group(['middleware' => 'role:Warden'], function () {
            Route::get('', [WardenController::class, 'index'])->name('warden.index');
            Route::get('wardenData', [WardenController::class, 'wardenData'])->name('warden.wardenData');
        });
        Route::group(['middleware' => 'permission:Warden,create'], function () {
            Route::get('/create', [WardenController::class, 'create'])->name('warden.create');
            Route::post('/store', [WardenController::class, 'store'])->name('warden.store');
        });
        Route::group(['middleware' => 'permission:Warden,update'], function () {
            Route::get('/{id}/edit', [WardenController::class, 'edit'])->name('warden.edit');
            Route::put('/{id}', [WardenController::class, 'update'])->name('warden.update');
        });
        Route::group(['middleware' => 'permission:Warden,read'], function () {
            Route::get('/{id}', [WardenController::class, 'show'])->name('warden.show');
        });
        Route::group(['middleware' => 'permission:Warden,delete'], function () {
            Route::get('delete/{id}', [WardenController::class, 'destroy'])->name('warden.destroy');
        });
    });

    Route::prefix('event')->group(function () {
        Route::group(['middleware' => 'role:Event'], function () {
            Route::get('', [EventsController::class, 'index'])->name('event.index');
            Route::get('/eventData', [EventsController::class, 'eventData'])->name('event.eventData');
        });
        Route::group(['middleware' => 'permission:Event,create'], function () {
            Route::get('/create', [EventsController::class, 'create'])->name('event.create');
            Route::post('/store', [EventsController::class, 'store'])->name('event.store');
        });
        Route::group(['middleware' => 'permission:Event,update'], function () {
            Route::get('/{id}/edit', [EventsController::class, 'edit'])->name('event.edit');
            Route::put('/{id}', [EventsController::class, 'update'])->name('event.update');
        });
        Route::group(['middleware' => 'permission:Event,read'], function () {
            Route::get('/{id}', [EventsController::class, 'show'])->name('event.show');
        });
        Route::group(['middleware' => 'permission:Event,delete'], function () {
            Route::get('delete/{id}', [EventsController::class, 'destroy'])->name('event.destroy');
        });
    });
});

Route::middleware('auth', 'twofactor', 'studentRole')->prefix('students')->group(function () {
    Route::get('/dashboard', [FrontendDashboardController::class, 'index'])->name('student.dashboard');
    Route::get('/', [FrontendDashboardController::class, 'index'])->name('student.dashboard');
    Route::get('/yearInfo', [FrontendDashboardController::class, 'yearInfo'])->name('student.dashboard.yearInfo');
    /* Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('student.profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('student.profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('student.profile.destroy');
        Route::get('/', [ProfileController::class, 'updatePasswordStudent'])->name('student.profile.updatePasswordStudent');
    }); */

    Route::prefix('profile')->group(function () {
        Route::get('/{profile}/edit', [ProfileController::class, 'edit'])->name('student.profile.edit');
        Route::patch('/', [ProfileController::class, 'updateProfileStudent'])->name('student.profile.updateProfileStudent');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('student.profile.destroy');
        Route::get('/', [ProfileController::class, 'updatePasswordStudent'])->name('student.profile.updatePasswordStudent');
    });

    Route::prefix('admission')->group(function () {
        Route::get('', [FrontendAdmissionController::class, 'index'])->name('student.admission.index');
        Route::get('admissionData', [FrontendAdmissionController::class, 'admissionData'])->name('student.admission.admissionData');
        Route::get('/create', [FrontendAdmissionController::class, 'create'])->name('student.admission.create');
        Route::post('/store', [FrontendAdmissionController::class, 'store'])->name('student.admission.store');
        Route::get('/{id}/edit', [FrontendAdmissionController::class, 'edit'])->name('student.admission.edit');
        Route::put('/{id}', [FrontendAdmissionController::class, 'update'])->name('student.admission.update');
        Route::group(['middleware' => 'permission:Admission,read'], function () {
            Route::get('/{id}', [FrontendAdmissionController::class, 'show'])->name('student.admission.show');
        });
        Route::group(['middleware' => 'permission:Admission,delete'], function () {
            Route::get('delete/{id}', [FrontendAdmissionController::class, 'destroy'])->name('student.admission.destroy');
        });
        Route::get('getAdmissionDataById/{id}', [FrontendAdmissionController::class, 'getAdmissionDataById'])->name('student.admission.getAdmissionDataById');
        Route::get('getStudentAdmissionData/{id}', [FrontendAdmissionController::class, 'getStudentAdmissionData'])->name('student.admission.getStudentAdmissionData');
        Route::get('document/{path}/download', [FrontendAdmissionController::class, 'download'])->name('student.document.download');
        Route::get('images/{id}/download', [FrontendAdmissionController::class, 'downloadImage'])->name('student.images.download');
        Route::post('/storeComment', [FrontendAdmissionController::class, 'storeComment'])->name('student.admission.storeComment');
        // Route::post('/upload-cropped-image', [FrontendAdmissionController::class, 'store'])->name('student.admission.upload');
        Route::post('admission-file-upload', [FrontendAdmissionController::class, 'processAdmissionFileUpload'])->name('admission.file-upload');
        Route::post('removeOtherDoc', [FrontendAdmissionController::class, 'removeOtherDoc'])->name('admission.removeOtherDoc');
    });
    Route::get('/getCoursesByEducation', [FrontendAdmissionController::class, 'getCoursesByEducation'])->name('student.admission.getCoursesByEducation');
    Route::get('/getCoursesById', [FrontendAdmissionController::class, 'getCoursesById'])->name('student.admission.getCoursesById');

    Route::prefix('apology_letter')->group(function () {
        Route::get('', [FrontendApologyLetterController::class, 'index'])->name('student.apology_letter.index');
        Route::get('/create', [FrontendApologyLetterController::class, 'create'])->name('student.apology_letter.create');
        Route::post('/store', [FrontendApologyLetterController::class, 'store'])->name('student.apology_letter.store');
    });

    Route::prefix('complain')->group(function () {
        Route::get('', [FrontendComplainController::class, 'index'])->name('student.complain.index');
        Route::get('/create', [FrontendComplainController::class, 'create'])->name('student.complain.create');
        Route::post('/store', [FrontendComplainController::class, 'store'])->name('student.complain.store');
    });

    Route::prefix('event')->group(function () {
        Route::get('', [FrontendEventController::class, 'index'])->name('student.event.index');
    });

    Route::prefix('leave')->group(function () {
        Route::get('', [FrontendLeaveController::class, 'index'])->name('student.leave.index');
        Route::get('/create', [FrontendLeaveController::class, 'create'])->name('student.leave.create');
        Route::post('/store', [FrontendLeaveController::class, 'store'])->name('student.leave.store');
    });

    Route::get('/contact-page', function () {
        return view('frontend.contact-page.index');
    })->name('student.contact-page.index');
});

require __DIR__ . '/auth.php';
