<?php

namespace App\Repositories;

use App\Contract\FeesRepositoryInterface;
use App\Models\Fees;

class FeesRepository implements FeesRepositoryInterface
{
    public function getAll($year = null)
    {
        $fees = Fees::select('fees.*', 'stud.first_name', 'stud.middle_name', 'stud.last_name', 'c.course_name', 'sam.student_id as student_id', 'sam.hostel_id', 'sam.room_id', 'sam.bed_id', 'h.hostel_name', 'r.room_number', 'b.bed_number', 'v.name as village_name', 'a.gender', 'sam.admission_year')
            ->join('student_admission_map as sam', 'sam.admission_id', 'fees.admission_id')
            ->join('admissions as a', 'a.id', 'sam.admission_id')
            ->leftjoin('courses as c', 'c.id', 'a.course_id')
            ->leftJoin('students as stud', 'stud.id', 'sam.student_id')
            ->leftjoin('hostels as h', 'h.id', 'sam.hostel_id')
            ->leftjoin('rooms as r', 'r.id', 'sam.room_id')
            ->leftjoin('beds as b', 'b.id', 'sam.bed_id')
            ->Join('villages as v', 'v.id', 'stud.village_id');

        if($year != 'all' && $year != null) {
            $year = explode('-', $year);
            $fees->whereBetween('a.created_at', ["$year[0]-04-01", "$year[1]-03-31"]);
        }
        return $fees->where('financial_year',date('y') . '-' . date('y', strtotime('+1 year', strtotime('y'))))->orderBy("fees.serial_number", "desc")->get();
    }

    public function feesData($data)
    {
        $fees =  Fees::select('fees.*', 'stud.first_name', 'stud.middle_name', 'stud.last_name', 'c.course_name', 'sam.student_id as student_id', 'sam.hostel_id', 'sam.room_id', 'sam.bed_id', 'h.hostel_name', 'r.room_number', 'b.bed_number', 'v.name as village_name', 'a.gender', 'sam.admission_year')
            ->join('student_admission_map as sam', 'sam.admission_id', 'fees.admission_id')
            ->join('admissions as a', 'a.id', 'sam.admission_id')
            ->leftjoin('courses as c', 'c.id', 'a.course_id')
            ->leftJoin('students as stud', 'stud.id', 'sam.student_id')
            ->leftjoin('hostels as h', 'h.id', 'sam.hostel_id')
            ->leftjoin('rooms as r', 'r.id', 'sam.room_id')
            ->leftjoin('beds as b', 'b.id', 'sam.bed_id')
            ->Join('villages as v', 'v.id', 'stud.village_id');

        if (!empty($data['student_id'])) {
            // dd($fees->where("sam.student_id", $data['student_id'])->first());
            $fees->where("sam.student_id", $data['student_id']);
        }

        if (!empty($data['hostel_id'])) {
            $fees->where("sam.hostel_id", $data['hostel_id']);
        }

        /* if(!empty($data['year']) && $data['year'] != 'all'){
            $year = explode('-', $data['year']);
            $fees->whereBetween('a.created_at', ["$year[0]-04-01", "$year[1]-03-31"]);
            // $fees->where('sam.admission_year', $data['year']);
        } */

        $from = $data['from'];
        $to = $data['to'];

        $from = ($data['from']) ? \DateTime::createFromFormat('d/m/Y', $data['from'])->format('Y-m-d') : null;
        $to = ($data['to']) ? \DateTime::createFromFormat('d/m/Y', $data['to'])->format('Y-m-d') : null;

        if ($from != null && $to != null) {
            $fees->whereBetween('fees.paid_at', ["$from", "$to"]);
        } else {
            if ($from != null) {
                $fees->where('fees.paid_at', '>=', "$from");
            }
        }

        if (!empty($data['gender'])) {
            $fees->where('a.gender', $data['gender']);
        }

        return $fees->where('financial_year',date('y') . '-' . date('y', strtotime('+1 year', strtotime('y'))))->orderBy("fees.serial_number", "desc")->get();
    }

    public function getById($id)
    {
        $fees =  Fees::select('fees.*', 'stud.first_name', 'stud.middle_name', 'stud.last_name', 'c.course_name', 'sam.student_id as student_id', 'sam.hostel_id', 'sam.room_id', 'sam.bed_id', 'h.hostel_name', 'r.room_number', 'b.bed_number', 'v.name as village_name', 'a.gender', 'sam.admission_year')
            ->join('student_admission_map as sam', 'sam.admission_id', 'fees.admission_id')
            ->join('admissions as a', 'a.id', 'sam.admission_id')
            ->leftjoin('courses as c', 'c.id', 'a.course_id')
            ->leftJoin('students as stud', 'stud.id', 'sam.student_id')
            ->leftjoin('hostels as h', 'h.id', 'sam.hostel_id')
            ->leftjoin('rooms as r', 'r.id', 'sam.room_id')
            ->leftjoin('beds as b', 'b.id', 'sam.bed_id')
            ->Join('villages as v', 'v.id', 'stud.village_id');

        return $fees->where('financial_year',date('y') . '-' . date('y', strtotime('+1 year', strtotime('y'))))->orderBy("fees.serial_number", "desc")->get();
    }

    public function getAllFeesByStudentId($id)
    {
        return Fees::select('fees.*', 'c.course_name', 'sam.student_id as student_id', 'stud.village_id')
            ->leftJoin('admissions', 'fees.admission_id', 'admissions.id')
            ->leftJoin('courses as c', 'c.id', 'admissions.course_id')
            ->leftJoin('student_admission_map as sam', 'sam.admission_id', 'admissions.id')
            ->leftJoin('students as stud', 'stud.id', 'sam.student_id')
            ->where('sam.student_id', $id)
            ->orderBy("year_of_addmission", "desc")
            ->get();
    }

    public function getByAdmissionId($admissionId)
    {
        return Fees::where('admission_id', $admissionId)->exists();
    }

    public function getDataByAdmissionId($admissionId)
    {
        return Fees::where('admission_id', $admissionId)->get();
    }

    public function isExist($name, $email, $number)
    {
        return Fees::where("name", $name)
            ->orWhere("email", $email)
            ->orWhere("number", $number)
            ->first();
    }

    public function create($postData)
    {
        return Fees::create($postData);
    }

    public function update($postData)
    {
        return Fees::where("id", $postData["id"])->update($postData);
    }

    public function delete($id)
    {
        return Fees::where("id", $id)->delete();
    }
}
