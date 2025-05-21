<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repositories\AdmissionRepository;
use App\Repositories\BedRepository;
use App\Repositories\CourseRepository;
use App\Repositories\HostelRepository;
use App\Repositories\StudentRepository;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
{
    private $bedRepository;
    private $courseRepository;
    private $hostelRepository;
    private $admissionRepository;
    private $studentRepository;

    public function __construct(
        BedRepository $bedRepository,
        CourseRepository $courseRepository,
        HostelRepository $hostelRepository,
        StudentRepository $studentRepository,
        AdmissionRepository $admissionRepository
    ) {
        $this->bedRepository = $bedRepository;
        $this->courseRepository = $courseRepository;
        $this->hostelRepository = $hostelRepository;
        $this->admissionRepository = $admissionRepository;
        $this->studentRepository = $studentRepository;
    }

    public function dueFees()
    {
        $hostels = $this->hostelRepository->getAll();
        $yearList = array_merge($this->lastFiveYears(), $this->nextFiveYears());
        sort($yearList);
        $students = $this->studentRepository->getConfirmStudent();

        return view('backend.report.due_fees', compact('hostels', 'yearList', 'students'));
    }

    public function dueFeesReportData(Request $request)
    {
        $data = $request->all();
        $result = $this->admissionRepository->dueFeesReportData($data);
        return DataTables::of($result)->addIndexColumn()->make(true);
    }

    public function availableBedsReportData(Request $request)
    {
        $data = $request->all();
        $bed = $this->bedRepository->getAvailableBedReport($data);
        return Datatables::of($bed)->addIndexColumn()->make(true);
    }

    public function availableBeds()
    {
        $hostels = $this->hostelRepository->getAll();
        $yearList = array_merge($this->lastFiveYears(), $this->nextFiveYears());
        sort($yearList);
        return view('backend.report.available_beds', ['hostels' => $hostels, 'yearList' => $yearList]);
    }

    public function allotedStudents()
    {
        $hostels = $this->hostelRepository->getAll();
        $yearList = array_merge($this->lastFiveYears(),$this->nextFiveYears());
        sort($yearList);
        return view('backend.report.allocated_students', ['hostels' => $hostels,'yearList' => $yearList]);
    }

    public function allotedStudentsReportData(Request $request) {
        $data = $request->all();
        $bed = $this->admissionRepository->allotedStudentsRecord($data);
        return Datatables::of($bed)->addIndexColumn()->make(true);
    }

    private function nextFiveYears()
    {
        for ($i = 1; $i <= 3; $i++) {
            $nextFiveYears[] = date("Y", strtotime(" +$i year")) . '-' . (date("Y") + $i + 1);
            // $nextFiveYears[] = date("Y", strtotime(" +$i year"));
        }
        return $nextFiveYears;
    }

    private function lastFiveYears()
    {
        for ($i = 1; $i <= 3; $i++) {
            $lastFiveYears[] = date("Y", strtotime(" -$i year")) . '-' . (date("Y") - $i + 1);
            // $lastFiveYears[] = date("Y", strtotime(" -$i year"));
        }
        array_push($lastFiveYears, (date("Y") . '-' . date("Y", strtotime(" +1 year"))));
        return $lastFiveYears;
    }

    public function releaseBed(Request $request)
    {
        $postData = $request->all();

        $this->admissionRepository->releaseStudentData($postData['admission_id']);

        return response()->json([
            'status' => true,
            'message' => 'Bed Released Successfully.'
        ]);
    }

    public function idCardStudents()
    {
        $hostels = $this->hostelRepository->getAll();
        $courses = $this->courseRepository->getAll();
        $yearList = array_merge($this->lastFiveYears(), $this->nextFiveYears());
        sort($yearList);
        return view('backend.report.id_card_students', ['hostels' => $hostels,'yearList' => $yearList, 'courses' => $courses]);
    }

    public function idCardStudentsReportData(Request $request)
    {
        $data = $request->all();
        $bed = $this->admissionRepository->idCardStudentsRecord($data);
        return Datatables::of($bed)->addIndexColumn()->make(true);
    }
}
