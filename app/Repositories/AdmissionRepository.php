<?php

namespace App\Repositories;

use App\Contract\AdmissionRepositoryInterface;
use App\Models\Admission;
use App\Models\Comment;
use App\Models\DocumentType;
use App\Models\Fees;
use App\Models\Student;
use App\Models\StudentAdmissionMap;
use App\Models\StudentDocument;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdmissionRepository implements AdmissionRepositoryInterface
{
    public function getAll($data = [], $year = null)
    {
        $currentYear =  date('Y') . "-05-01";
        $nextYear =  (date('Y') + 1) . "-04-30";

        $admissions = Admission::select('admissions.*', 'c.course_name', 'sam.student_id as student_id', 'sam.hostel_id', 'sam.room_id', 'sam.bed_id', 'h.hostel_name', 'r.room_number', 'b.bed_number', 'v.name as village_name', 'sam.is_bed_release')
            ->leftjoin('courses as c', 'c.id', 'admissions.course_id')
            ->join('student_admission_map as sam', 'sam.admission_id', 'admissions.id')
            ->leftJoin('students as stud', 'stud.id', 'sam.student_id')
            ->leftjoin('hostels as h', 'h.id', 'sam.hostel_id')
            ->leftjoin('rooms as r', 'r.id', 'sam.room_id')
            ->leftjoin('beds as b', 'b.id', 'sam.bed_id')
            ->Join('villages as v', 'v.id', 'stud.village_id');

        /* if ($year != null && $year != '') {
            $year = explode('-', $year);
            $admissions->whereBetween('sam.created_at', ["$year[0]-05-01", "$year[1]-04-30"]);
        } else {
            // dd($currentYear);
            $admissions->whereBetween('sam.created_at', ["$currentYear", "$nextYear"]);
        } */

        if ($data) {
            if (!empty($data['year']) && $data['year'] != 'all') {
                $year = explode('-', $data['year']);
                // dd("$year[0]-05-01");
                $admissions->whereBetween('sam.created_at', ["$year[0]-05-01", "$year[1]-04-30"]);
                // dd($admissions->get());
            }

            if (!empty($data['gender']) && $data['gender'] != 'all') {
                $admissions->where('admissions.gender', $data['gender']);
            }

            if (!empty($data['courseId']) && $data['courseId'] != 'all') {
                $admissions->where('admissions.course_id', $data['courseId']);
            }

            if (isset($data['isAdmissionNew'])) {
                if (!empty($data['isAdmissionNew']) && $data['isAdmissionNew'] != 'all') {
                    $admissions->where('admissions.is_admission_new', $data['isAdmissionNew']);
                } else {
                    if ($data['isAdmissionNew'] == '0') {
                        $admissions->where('admissions.is_admission_new', '0');
                    }
                }
            }

            if (isset($data['status'])) {
                if (!empty($data['status']) && $data['status'] != 'all') {
                    $admissions->where('admissions.is_admission_confirm', $data['status']);
                } else {
                    if ($data['status'] == '0') {
                        $admissions->where('admissions.is_admission_confirm', '0');
                    }
                }
            }

            if (!empty($data['roomAlloted']) && $data['roomAlloted'] != 'all') {
                if ($data['roomAlloted'] === 'no') {
                    $admissions->where([['sam.hostel_id', '0'], ['sam.room_id', '0'], ['sam.bed_id', '0']]);
                } else {
                    $admissions->where([['sam.hostel_id', '<>', '0'], ['sam.room_id', '<>', '0'], ['sam.bed_id', '<>', '0']]);
                }
            }
        } else {
            $admissions->whereBetween('sam.created_at', ["$currentYear", "$nextYear"]);
        }

        $admissions =  $admissions->orderBy("created_at", "desc");

        return $admissions;
    }

    public function getAdmissionByStudentId($year = null)
    {
        $currentYear =  date('Y') . "-05-01";
        $nextYear =  (date('Y') + 1) . "-05-30";

        $admission = Admission::select('admissions.*', 'c.course_name', 'sam.student_id as student_id', 'stud.village_id')
            ->leftJoin('courses as c', 'c.id', 'admissions.course_id')
            ->leftJoin('student_admission_map as sam', 'sam.admission_id', 'admissions.id')
            ->leftJoin('students as stud', 'stud.id', 'sam.student_id')
            ->leftJoin('comments as cmt', 'cmt.student_id', 'sam.student_id')
            ->where('stud.user_id', Auth::user()->id)
            ->orderBy("year_of_addmission", "desc");

        if ($year != null && $year != '') {
            $year = explode('-', $year);
            $admission->whereBetween('sam.created_at', ["$year[0]-05-01", "$year[1]-04-30"]);
        } else {
            $admission->whereBetween('sam.created_at', ["$currentYear", "$nextYear"]);
        }

        return $admission->first();
    }

    public function getAllAdmissionByStudentId($id)
    {
        return Admission::select('admissions.*', 'c.course_name', 'sam.student_id as student_id', 'stud.village_id')
            ->leftJoin('courses as c', 'c.id', 'admissions.course_id')
            ->leftJoin('student_admission_map as sam', 'sam.admission_id', 'admissions.id')
            ->leftJoin('students as stud', 'stud.id', 'sam.student_id')
            ->where('sam.student_id', $id)
            ->orderBy("year_of_addmission", "desc")
            ->get();
    }

    public function getById($id)
    {
        return Admission::select('admissions.*', 'sam.*', 'stud.*', 'admissions.course_id as cId', 'v.name as village_name', 'c.course_name', 'cu.name as country_name', 'h.hostel_name', 'r.room_number', 'b.bed_number', 'admissions.is_any_illness', 'admissions.illness_description')
            ->leftJoin('student_admission_map as sam', 'sam.admission_id', 'admissions.id')
            ->leftJoin('students as stud', 'stud.id', 'sam.student_id')
            ->Join('villages as v', 'v.id', 'stud.village_id')
            ->leftJoin('courses as c', 'c.id', 'admissions.course_id')
            ->Join('country as cu', 'cu.id', 'admissions.country')
            ->leftjoin('hostels as h', 'h.id', 'sam.hostel_id')
            ->leftjoin('rooms as r', 'r.id', 'sam.room_id')
            ->leftjoin('beds as b', 'b.id', 'sam.bed_id')
            ->where("admissions.id", $id)->first();
    }

    public function isExist($name, $email, $number)
    {
        return Admission::where("name", $name)
            ->orWhere("email", $email)
            ->orWhere("number", $number)
            ->first();
    }

    public function create($postData)
    {
        return Admission::create($postData);
    }

    public function update($postData, $id)
    {
        unset($postData['_token'], $postData['_method'], $postData['action']);
        return Admission::where("id", $id)->update($postData);
    }

    public function delete($id)
    {
        StudentAdmissionMap::where("admission_id", $id)->delete();
        Admission::where("id", $id)->delete();

        return true;
    }

    public function studentAdmissionMapInsert($studentAdmissionMapData)
    {
        return StudentAdmissionMap::create($studentAdmissionMapData);
    }

    public function dueFeesReportData($data)
    {
        // dd($data);
        $admissionIds = Fees::pluck('admission_id');
        $dueFeesReport = StudentAdmissionMap::select('f.payment_method', 'f.status', 's.first_name', 's.middle_name', 's.last_name', 'r.room_number', 'b.bed_number', 'a.gender', 'student_admission_map.admission_year', 's.phone', 'a.father_phone')
            ->leftjoin('admissions as a', 'a.id', 'student_admission_map.admission_id')
            ->leftjoin('fees as f', 'f.admission_id', 'student_admission_map.admission_id')
            ->leftjoin('students as s', 's.id', 'student_admission_map.student_id')
            ->leftjoin('rooms as r', 'r.id', 'student_admission_map.room_id')
            ->leftjoin('beds as b', 'b.id', 'student_admission_map.bed_id')
            // ->where('admission_year', $data['year'])
            ->whereNotIn('a.is_admission_confirm', ['2', '3'])
            ->whereNotIn('student_admission_map.admission_id', $admissionIds);
        if (!empty($data['year'])) {
            $year = explode('-', $data['year']);
            $dueFeesReport->whereBetween('a.created_at', ["$year[0]-04-01", "$year[1]-03-31"]);
        }
        if (isset($data['hostel_id']) && $data['hostel_id'] > 0) {
            $dueFeesReport->where('student_admission_map.hostel_id', $data['hostel_id']);
        }
        if (isset($data['hostelstudent_id_id']) && $data['student_id'] > 0) {
            $dueFeesReport->where('student_admission_map.student_id', $data['student_id']);
        }
        if (isset($data['gender']) && !empty($data['gender'])) {
            $dueFeesReport->where('a.gender', $data['gender']);
        }
        return $dueFeesReport->orderBy("r.room_number", "asc")->orderBy("b.bed_number", "asc")->get();
    }

    public function isConfirmAdmission($data)
    {
        return Admission::where("id", $data['admissionId'])->update(['is_admission_confirm' => $data['status']]);
    }

    public function admissionStatus($data)
    {
        return Admission::where("id", $data['admissionId'])->value('is_admission_confirm');
    }

    public function getStudentDocumentsByAdmissionId($admissionId)
    {
        $admission = StudentAdmissionMap::with('admission')->where('admission_id', $admissionId)->first();
        $documents = StudentDocument::where('student_id', $admission->student_id)->orderBy('id', 'desc')->get();
        return $documents;
    }

    public function isRoomAllocation($student_id, $admission_id = null)
    {
        // dd($student_id, $admission_id);
        return StudentAdmissionMap::select('r.room_number', 'b.bed_number', 'h.hostel_name')
            ->join('hostels as h', 'h.id', 'student_admission_map.hostel_id')
            ->join('rooms as r', 'r.id', 'student_admission_map.room_id')
            ->join('beds as b', 'b.id', 'student_admission_map.bed_id')
            ->where([['student_id', $student_id], ['bed_id', '<>', '0'], ['admission_id', $admission_id]])->first();
    }

    public function getReservationByAdmissionId($admissionId)
    {
        return StudentAdmissionMap::join('reservations as res', 'res.student_id', 'student_admission_map.student_id')
            ->where('student_admission_map.admission_id', $admissionId)->first();
    }

    public function isStudentHasNewAdmission()
    {
        return Student::join('student_admission_map as sam', 'sam.student_id', 'students.id')
            ->where([['sam.admission_year', date('Y')], ['students.user_id', Auth::user()->id]])
            ->exists();
    }

    public function getStudentVillageId()
    {
        return Student::where('user_id', Auth::user()->id)->value('village_id');
    }

    public function studentDetails()
    {
        $studentDetail =  Student::join('student_admission_map as sam', 'sam.student_id', 'students.id')
            ->join('admissions as add', 'add.id', 'sam.admission_id')
            ->join('student_documents as sd', 'sd.student_id', 'students.id')
            ->where('students.user_id', Auth::user()->id)->first();
        $studentAddDetail = ($studentDetail != null) ? $studentDetail :  Student::where('user_id', Auth::user()->id)->first();
        return $studentAddDetail;
    }

    public function getDataBySearchKeyword($data, $keyword)
    {
        $searchResult = Admission::select('admissions.*', 'c.course_name', 'sam.student_id as student_id', 'sam.hostel_id', 'sam.room_id', 'sam.bed_id', 'h.hostel_name', 'r.room_number', 'b.bed_number', 'v.name as village_name')
            ->join('courses as c', 'c.id', 'admissions.course_id')
            ->join('student_admission_map as sam', 'sam.admission_id', 'admissions.id')
            ->leftJoin('students as stud', 'stud.id', 'sam.student_id')
            ->leftjoin('hostels as h', 'h.id', 'sam.hostel_id')
            ->leftjoin('rooms as r', 'r.id', 'sam.room_id')
            ->leftjoin('beds as b', 'b.id', 'sam.bed_id')
            ->Join('villages as v', 'v.id', 'stud.village_id');

        if (!empty($data['year'])) {
            $searchResult->where('sam.admission_year', $data['year']);
        }

        if (!empty($data['gender']) && $data['gender'] != 'all') {
            $searchResult->where('admissions.gender', $data['gender']);
        }

        if (!empty($data['course']) && $data['course'] != 'all') {
            $searchResult->where('admissions.course_id', $data['course']);
        }

        if (!empty($data['status']) && $data['status'] != 'all') {
            $searchResult->where('admissions.is_admission_confirm', $data['status']);
        } else {
            if ($data['status'] == '0') {
                $searchResult->where('admissions.is_admission_confirm', '0');
            }
        }
        $searchResult =  $searchResult->orderBy("created_at", "desc")->get();
        $searchResult->map(function ($data) {
            $data->role = Auth::user()->role;
        });

        return $searchResult;
    }

    public function currrentAdmissionYear()
    {
        return Admission::select('admissions.*', 'c.course_name', 'sam.student_id as student_id', 'stud.village_id')
            ->join('courses as c', 'c.id', 'admissions.course_id')
            ->join('student_admission_map as sam', 'sam.admission_id', 'admissions.id')
            ->join('students as stud', 'stud.id', 'sam.student_id')
            // ->whereYear('admissions.created_at', Carbon::now())
            ->where('stud.user_id', Auth::user()->id)
            ->orderBy("created_at", "desc")->first();
    }

    public function getDocumentTypes()
    {
        return DocumentType::all();
    }

    public function getDocumentTypeById($typeId)
    {
        return DocumentType::find($typeId)->type;
    }

    public function view($id)
    {
        return Admission::select('admissions.*', 'c.course_name', 'stud.village_id')
            ->leftJoin('courses as c', 'c.id', 'admissions.course_id')
            ->leftJoin('student_admission_map as sam', 'sam.admission_id', 'admissions.id')
            ->leftJoin('students as stud', 'stud.id', 'sam.student_id')
            ->where('admissions.id', $id)->first();
    }

    public function year()
    {
        return Admission::select('admissions.*', 'c.course_name', 'sam.student_id as student_id', 'stud.village_id')
            ->leftJoin('courses as c', 'c.id', 'admissions.course_id')
            ->leftJoin('student_admission_map as sam', 'sam.admission_id', 'admissions.id')
            ->leftJoin('students as stud', 'stud.id', 'sam.student_id')
            ->where('stud.user_id', Auth::user()->id)
            ->orderBy("year_of_addmission", "desc")
            ->pluck('year_of_addmission');
    }

    public function allotedStudentsRecord($data)
    {
        $allotedList = StudentAdmissionMap::select('s.first_name', 's.middle_name', 's.last_name', 'b.bed_number', 'r.room_number', 'a.gender', 's.phone', 'a.father_phone', 'a.mother_phone', 'a.guardian_phone', 'a.id as admission_id')
            ->join('students as s', 's.id', 'student_admission_map.student_id')
            ->join('admissions as a', 'a.id', 'student_admission_map.admission_id')
            ->join('rooms as r', 'r.id', 'student_admission_map.room_id')
            ->join('beds as b', 'b.id', 'student_admission_map.bed_id')
            // ->where([['admission_year', $data['year']],['student_admission_map.is_bed_release','0']]);
            ->where('student_admission_map.is_bed_release', '0');

        if (!empty($data['year'])) {
            $year = explode('-', $data['year']);
            $allotedList->whereBetween('a.created_at', ["$year[0]-05-01", "$year[1]-04-30"]);
        }

        if (!empty($data['hostel_id'])) {
            $allotedList->where('student_admission_map.hostel_id', $data['hostel_id']);
        }
        if (!empty($data['gender'])) {
            $allotedList->where('a.gender', $data['gender']);
        }

        return $allotedList->orderBy("r.room_number", "asc")->orderBy("b.bed_number", "asc")->get();
    }

    public function releaseStudentData($admissionId)
    {
        StudentAdmissionMap::where([['admission_id', $admissionId], ['admission_year', date('Y')]])->update([
            'hostel_id' => 0,
            'room_id' => 0,
            'bed_id' => 0,
            'is_bed_release' => 1
        ]);
        Admission::where('id', $admissionId)->update(['is_admission_confirm' => 4]);
        return true;
    }

    public function checkStudent($studentId)
    {
        $studentDetail = StudentAdmissionMap::where('student_id', $studentId)->latest()->first();

        if ($studentDetail) {
            return true;
        } else {
            return false;
        }
    }

    public function admissionStatusData($year = null)
    {
        if (!empty($year)) {
            $year = explode('-', $year);
            $admission_status = Admission::join('student_admission_map as sam', 'sam.admission_id', 'admissions.id')
                ->whereBetween('sam.created_at', ["$year[0]-05-01", "$year[1]-04-30"])
                ->pluck('is_admission_confirm');
            $admission_status = $admission_status->toArray();
            $admission_status = array_count_values($admission_status);
        }
        return $admission_status;
    }

    public function isCourseExist($id)
    {
        return Admission::where('course_id', $id)->exists();
    }

    public function getCommentsByAdmissionId($admission_id)
    {
        $comments = Comment::where('admission_id', $admission_id)->get();
        return $comments;
    }

    public function getCommentsByAdmissionStudentId($admission_id, $user_id)
    {
        $comments = Comment::where([['admission_id', $admission_id], ['student_comment', '!=', null], ['student_comment', '!=', '']])->get();
        return $comments;
    }

    public function getLatestComment($admission_id, $user_id)
    {
        $comment = Comment::where([['admission_id', $admission_id], ['commented_by', $user_id]])->latest()->first();
        return $comment;
    }

    public function getLatestAdmissionByStudentId()
    {
        return Admission::select('admissions.*', 'c.course_name', 'sam.student_id as student_id', 'stud.village_id')
            ->leftJoin('courses as c', 'c.id', 'admissions.course_id')
            ->leftJoin('student_admission_map as sam', 'sam.admission_id', 'admissions.id')
            ->leftJoin('students as stud', 'stud.id', 'sam.student_id')
            ->where('stud.user_id', Auth::user()->id)
            ->orderBy("year_of_addmission", "desc")
            ->latest()->first();
    }

    public function idCardStudentsRecord($data)
    {
        $currentYear =  date('Y') . "-05-01";
        $nextYear =  (date('Y') + 1) . "-04-30";
        $allotedList = StudentAdmissionMap::select('s.first_name', 's.middle_name', 's.last_name', 'b.bed_number', 'r.room_number', 'a.gender', 's.phone', 'a.father_phone', 'a.mother_phone', 'a.guardian_phone', 'a.id as admission_id', 'c.course_name')
            ->join('students as s', 's.id', 'student_admission_map.student_id')
            ->join('admissions as a', 'a.id', 'student_admission_map.admission_id')
            ->join('rooms as r', 'r.id', 'student_admission_map.room_id')
            ->join('beds as b', 'b.id', 'student_admission_map.bed_id')
            // ->where([['admission_year', $data['year']],['student_admission_map.is_bed_release','0']]);
            ->leftJoin('courses as c', 'c.id', 'a.course_id')
            ->where('student_admission_map.is_bed_release', '0');

        if ($data) {
            if (!empty($data['year']) && $data['year'] != 'all') {
                $year = explode('-', $data['year']);
                $allotedList->whereBetween('student_admission_map.created_at', ["$year[0]-05-01", "$year[1]-04-30"]);
            }

            if ($data['hostel_id'] > 0) {
                $allotedList->where('student_admission_map.hostel_id', $data['hostel_id']);
            }

            if (!empty($data['gender']) && $data['gender'] != 'all') {
                $allotedList->where('a.gender', $data['gender']);
            }

            if ($data['course_id'] > 0) {
                $allotedList->where('c.id', $data['course_id']);
            }
        } else {
            $allotedList->whereBetween('student_admission_map.created_at', ["$currentYear", "$nextYear"]);
        }

        return $allotedList->orderBy("r.room_number", "asc")->orderBy("b.bed_number", "asc")->get();
    }

    public function firstHalfReportData($year = null)
    {
        $admissionIds = Fees::pluck('admission_id');
        // dd($admissionIds);
        $dueFeesReport = StudentAdmissionMap::select('f.payment_method', 'f.status', 's.first_name', 's.middle_name', 's.last_name', 'r.room_number', 'b.bed_number', 'a.gender', 'student_admission_map.admission_year', 's.phone', 'a.father_phone')
            ->leftjoin('admissions as a', 'a.id', 'student_admission_map.admission_id')
            ->leftjoin('fees as f', 'f.admission_id', 'student_admission_map.admission_id')
            ->leftjoin('students as s', 's.id', 'student_admission_map.student_id')
            ->leftjoin('rooms as r', 'r.id', 'student_admission_map.room_id')
            ->leftjoin('beds as b', 'b.id', 'student_admission_map.bed_id')
            ->whereIn('f.payment_method', ['Half-Yearly', 'Yearly'])
            ->whereNotIn('a.is_admission_confirm', ['2', '3']);
        if (!empty($year)) {
            $year = explode('-', $year);
            $dueFeesReport
                ->whereBetween('a.created_at', ["$year[0]-05-01", "$year[1]-04-30"])
                ->whereBetween('f.created_at', ["$year[0]-06-01", "$year[0]-11-30"]);
        }
        return $dueFeesReport->orderBy("r.room_number", "asc")->orderBy("b.bed_number", "asc")->get();
    }

    public function secondHalfReportData($year = null)
    {
        $admissionIds = Fees::pluck('admission_id');

        $dueFeesReport = StudentAdmissionMap::select(
            'f.payment_method',
            'f.status',
            's.first_name',
            's.middle_name',
            's.last_name',
            'r.room_number',
            'b.bed_number',
            'a.gender',
            'student_admission_map.admission_year',
            's.phone',
            'a.father_phone'
        )
            ->leftJoin('admissions as a', 'a.id', 'student_admission_map.admission_id')
            ->leftJoin('fees as f', 'f.admission_id', 'student_admission_map.admission_id')
            ->leftJoin('students as s', 's.id', 'student_admission_map.student_id')
            ->leftJoin('rooms as r', 'r.id', 'student_admission_map.room_id')
            ->leftJoin('beds as b', 'b.id', 'student_admission_map.bed_id')
            ->whereIn('f.payment_method', ['Half-Yearly', 'Yearly'])
            ->whereNotIn('a.is_admission_confirm', ['2', '3']);

        if (!empty($year)) {
            $year = explode('-', $year);
            $admissionStart = "{$year[0]}-05-01";
            $admissionEnd = "{$year[1]}-04-30";

            $dueFeesReport->whereBetween('a.created_at', [$admissionStart, $admissionEnd]);

            // Conditional filter for fee creation dates
            $dueFeesReport->where(function ($query) use ($year) {
                $query->where(function ($q) use ($year) {
                    $q->where('f.payment_method', 'Half-Yearly')
                        ->whereBetween('f.created_at', ["{$year[0]}-12-01", "{$year[1]}-05-30"]);
                })->orWhere(function ($q) use ($year) {
                    $q->where('f.payment_method', 'Yearly')
                        ->whereBetween('f.created_at', ["{$year[0]}-06-01", "{$year[1]}-05-30"]);
                });
            });
        }

        return $dueFeesReport->orderBy("r.room_number", "asc")
            ->orderBy("b.bed_number", "asc")
            ->get();
    }

    public function totalFeesReportData($data)
    {
        $dueFeesReport = DB::table('student_admission_map')
            ->selectRaw('
        MAX(f.payment_method) as payment_method,
        MAX(f.status) as status,
        MAX(s.first_name) as first_name,
        MAX(s.middle_name) as middle_name,
        MAX(s.last_name) as last_name,
        MAX(r.room_number) as room_number,
        MAX(b.bed_number) as bed_number,
        MAX(a.gender) as gender,
        MAX(s.phone) as phone,
        MAX(a.father_phone) as father_phone
    ')
            ->leftJoin('admissions as a', 'a.id', '=', 'student_admission_map.admission_id')
            ->leftJoin('fees as f', 'f.admission_id', '=', 'student_admission_map.admission_id')
            ->leftJoin('students as s', 's.id', '=', 'student_admission_map.student_id')
            ->leftJoin('rooms as r', 'r.id', '=', 'student_admission_map.room_id')
            ->leftJoin('beds as b', 'b.id', '=', 'student_admission_map.bed_id')
            ->whereNotIn('a.is_admission_confirm', ['2', '3']);
        if (!empty($data['year'])) {
            $year = explode('-', $data['year']);
            $dueFeesReport->whereBetween('a.created_at', ["$year[0]-05-01", "$year[1]-04-30"]);
        }

        return $dueFeesReport->groupBy('student_admission_map.admission_id')
            ->orderByRaw('MAX(r.room_number)')
            ->orderByRaw('MAX(b.bed_number)')
            ->get();
    }
}
