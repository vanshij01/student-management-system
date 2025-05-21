<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repositories\AdmissionRepository;
use App\Repositories\BedRepository;
use App\Repositories\ComplainRepository;
use App\Repositories\CourseRepository;
use App\Repositories\FeesRepository;
use App\Repositories\HostelRepository;
use App\Repositories\LeaveRepository;
use App\Repositories\RoomRepository;
use App\Repositories\StudentRepository;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private $studentRepository;
    private $bedRepository;
    private $roomRepository;
    private $hostelRepository;
    private $courseRepository;
    private $leaveRepository;
    private $admissionRepository;
    private $complainRepository;
    private $feesRepository;

    public function __construct(
        StudentRepository $studentRepository,
        BedRepository $bedRepository,
        RoomRepository $roomRepository,
        HostelRepository $hostelRepository,
        LeaveRepository $leaveRepository,
        CourseRepository $courseRepository,
        AdmissionRepository $admissionRepository,
        ComplainRepository $complainRepository,
        FeesRepository $feesRepository,
    ) {
        $this->studentRepository = $studentRepository;
        $this->bedRepository = $bedRepository;
        $this->roomRepository = $roomRepository;
        $this->hostelRepository = $hostelRepository;
        $this->courseRepository = $courseRepository;
        $this->leaveRepository = $leaveRepository;
        $this->admissionRepository = $admissionRepository;
        $this->complainRepository = $complainRepository;
        $this->feesRepository = $feesRepository;
    }

    public function index(Request $request) {
        $admission = $this->admissionRepository->getAll();
        $yearList = array_merge($this->lastFiveYears(), $this->nextFiveYears());
        sort($yearList);
        return view('backend.dashboard', compact('admission', 'yearList', 'request'));
    }

    private function nextFiveYears()
    {
        for ($i = 1; $i <= 3; $i++) {
            $nextFiveYears[] = date("Y", strtotime(" +$i year")) . '-' . (date("Y") + $i + 1);
        }
        return $nextFiveYears;
    }

    private function lastFiveYears()
    {
        for ($i = 1; $i <= 3; $i++) {
            $lastFiveYears[] = date("Y", strtotime(" -$i year")) . '-' . (date("Y") - $i + 1);
        }
        array_push($lastFiveYears, (date("Y") . '-' . date("Y", strtotime(" +1 year"))));
        return $lastFiveYears;
    }

    public function yearInfo(Request $request){
        $year = $request->year;
        $admission = $this->admissionRepository->getAll(null, $request->year)->count();
        $student = $this->studentRepository->studentInfo($request->year)->count();
        $hostel = $this->hostelRepository->hostelData($year = null)->count();
        $room = $this->roomRepository->roomData($request->year)->count();
        $bed = $this->bedRepository->getAvailableBed($request->year)->count();
        $course = $this->courseRepository->getAll()->count();
        $hostel_gender = $this->hostelRepository->genderStatistics($request->year);
        $available_bed = $this->hostelRepository->getChartDataAvailableBed($request->year);
        $admission_status = $this->admissionRepository->admissionStatusData($request->year);
        $complains = $this->complainRepository->getAll($request->year)->count();
        $fees = $this->feesRepository->getAll($request->year)->count();
        $complain_status = $this->complainRepository->complainStatusData($request->year);

        return response()->json([
            'year' => $year,
            'admission' => $admission,
            'student' => $student,
            'hostel' => $hostel,
            'room' => $room,
            'bed' => $bed,
            'course' => $course,
            'hostel_gender' => $hostel_gender,
            'available_bed' => $available_bed,
            'admission_status' => $admission_status,
            'complains' => $complains,
            'complain_status' => $complain_status,
            'fees' => $fees,
        ]);
    }
}
