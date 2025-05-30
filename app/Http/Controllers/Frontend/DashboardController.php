<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ApologyLetter;
use App\Models\Comment;
use App\Models\Setting;
use App\Models\Student;
use App\Models\StudentAdmissionMap;
use App\Repositories\AdmissionRepository;
use App\Repositories\BedRepository;
use App\Repositories\CourseRepository;
use App\Repositories\EventsRepository;
use App\Repositories\HostelRepository;
use App\Repositories\LeaveRepository;
use App\Repositories\NoticeRepository;
use App\Repositories\RoomRepository;
use App\Repositories\StudentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    private $studentRepository;
    private $bedRepository;
    private $roomRepository;
    private $hostelRepository;
    private $courseRepository;
    private $leaveRepository;
    private $admissionRepository;
    private $eventsRepository;
    private $noticeRepository;

    public function __construct(
        StudentRepository $studentRepository,
        BedRepository $bedRepository,
        RoomRepository $roomRepository,
        HostelRepository $hostelRepository,
        LeaveRepository $leaveRepository,
        CourseRepository $courseRepository,
        AdmissionRepository $admissionRepository,
        EventsRepository $eventsRepository,
        NoticeRepository $noticeRepository,
    ) {
        $this->studentRepository = $studentRepository;
        $this->bedRepository = $bedRepository;
        $this->roomRepository = $roomRepository;
        $this->hostelRepository = $hostelRepository;
        $this->courseRepository = $courseRepository;
        $this->leaveRepository = $leaveRepository;
        $this->admissionRepository = $admissionRepository;
        $this->eventsRepository = $eventsRepository;
        $this->noticeRepository = $noticeRepository;
    }

    public function index(Request $request)
    {
        $admissions = $this->admissionRepository->getAdmissionByStudentId();
        /* $admissions->map(function ($admission) {
            $admission->room_allocation = $this->admissionRepository->isRoomAllocation($admission->student_id, $admission->id);
            $admission->documents = $this->admissionRepository->getStudentDocumentsByAdmissionId($admission->id);
            $admission->documents->map(function ($document) {
                $document->file_name = basename($document->doc_url);
            });
            return $admission;
        }); */
        $admission = $this->admissionRepository->getLatestAdmissionByStudentId();
        // dd($admission);

        if ($admission) {
            $admission->room_allocation = $this->admissionRepository->isRoomAllocation($admission->student_id, $admission->id);
            $admission->documents = $this->admissionRepository->getStudentDocumentsByAdmissionId($admission->id);
            $admission->documents->map(function ($document) {
                $document->file_name = basename($document->doc_url);
            });
        }

        $oldAdmissionDate = Setting::where('key', 'old_admission_date')->value('value');
        $newAdmissionDate = Setting::where('key', 'new_admission_date')->value('value');
        $studentId = Student::whereUserId(Auth::user()->id)->value('id');
        $oldLabel = Setting::where('key', 'old_admission_label')->value('value');
        $newLabel = Setting::where('key', 'new_admission_label')->value('value');

        $apologyLetterCount = ApologyLetter::whereStudentId($studentId)->count();
        // if (count($admissions) > 0) {
        if ($admission) {
            $admissionDate = $admission->is_admission_new == 1 ? $newAdmissionDate : $oldAdmissionDate;
            $admissionLabel = $admission->is_admission_new == 1 ? $newLabel : $oldLabel;
        } else {
            $admissionDate = $newAdmissionDate ? $newAdmissionDate : null;
            $admissionLabel = $newLabel ? $newLabel : "";
        }

        $startYear = date('Y') . '-05-01';
        $endYear = date('Y', strtotime('+1 Year')) . '-04-30';
        $isStudentAdmissionExist = StudentAdmissionMap::where('student_id', $studentId)->whereBetween('created_at', [$startYear, $endYear])->exists();
        // dd($isStudentAdmissionExist);

        $events = $this->eventsRepository->getUpcoming();
        $notice = $this->noticeRepository->getByFirst();

        // $comments = $this->admissionRepository->getCommentsByAdmissionId();

        return view('frontend.dashboard', compact('admissions', 'admission',  'admissionDate', 'admissionLabel', 'isStudentAdmissionExist', 'apologyLetterCount', 'events', 'notice'));
    }

    public function yearInfo(Request $request)
    {
        $year = $request->year;
        // dd($year);
        $studentId = Student::where('user_id', Auth::user()->id)->value('id');
        $admission = $this->admissionRepository->getAdmissionByStudentId($year);
        if (!empty($admission)) {
            $admission->room_allocation = $this->admissionRepository->isRoomAllocation($admission->student_id, $admission->id);
            $admission->documents = $this->admissionRepository->getStudentDocumentsByAdmissionId($admission->id);
            $admission->documents->map(function ($document) {
                $document->file_name = basename($document->doc_url);
            });
            $admission->comments = $this->admissionRepository->getCommentsByAdmissionId($admission->id);
        }

        return response()->json([
            'year' => $year,
            'admission' => $admission,
        ]);
    }
}
