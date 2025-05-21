<?php

namespace App\Repositories;

use App\Contract\ApologyLetterRepositoryInterface;
use App\Models\ApologyLetter;
use App\Models\StudentAdmissionMap;

class ApologyLetterRepository implements ApologyLetterRepositoryInterface
{
    public function getAll()
    {
        return ApologyLetter::select('h.hostel_name','r.room_number','b.bed_number','s.first_name','s.middle_name','s.last_name','apology_letters.*')
        ->leftJoin('student_admission_map as sam','sam.student_id','apology_letters.student_id')
        ->leftjoin('students as s','s.id','apology_letters.student_id')
        ->leftJoin('hostels as h','h.id','sam.hostel_id')
        ->leftJoin('rooms as r','r.id','sam.room_id')
        ->leftJoin('beds as b','b.id','sam.bed_id')
        ->orderBy("created_at", "desc")->get();
    }

    public function getByStudentId($studentId)
    {
        return ApologyLetter::where('apology_letters.student_id',$studentId)->orderBy("created_at", "desc")->get();
    }

    public function getById($id)
    {
        return ApologyLetter::where("id", $id)->first();
    }

    public function create($postData)
    {
        return ApologyLetter::create($postData);
    }

    public function update($postData, $id)
    {
        unset($postData['_token'], $postData['_method'], $postData['action']);
        $model = ApologyLetter::findOrFail($id);
        return $model->update($postData);
    }

    public function delete($id)
    {
        $model = ApologyLetter::findOrFail($id);
        return $model->delete();
    }
}
