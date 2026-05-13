<?php

namespace App\Repositories;

use App\Contract\StudentRepositoryInterface;
use App\Models\Admission;
use App\Models\Reservation;
use App\Models\Student;
use App\Models\StudentAdmissionMap;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class StudentRepository implements StudentRepositoryInterface
{
    public function getAll()
    {
        return Student::orderBy("created_at", "desc")->get();
    }

    public function getById($id)
    {
        return Student::with(['user'])->findOrFail($id);
    }

    public function create($params)
    {
        unset($params['_token'], $params['role'], $params['role_id'], $params['name'], $params['created_by']);
        // $isCreste = Student::create($params);
        // dd($isCreste);
        return Student::create($params);
    }

    public function update($payLoad, $id)
    {
        $wardenUserId = $this->getStudentUserId($id);
        $user['email'] = $payLoad['email'];
        $user['name'] = $payLoad['first_name'] . ' ' . $payLoad['last_name'];
        $this->updateStudentUser($user, $wardenUserId);

        $user = Student::findOrFail($id);
        return $user->update($payLoad);
    }

    public function delete($id)
    {
        $user = Student::findOrFail($id);
        $user->delete();
    }

    public function studentData($genderId, $countryId)
    {
        return Student::when($genderId, function ($query) use ($genderId) {
            return $query->where('gender', $genderId);
        })
            ->when($countryId, function ($query) use ($countryId) {
                return $query->where('country_id', $countryId);
            })
            ->orderBy("created_at", "desc");
    }

    public function isExist($name, $email, $number)
    {
        return Student::where("name", $name)
            ->orWhere("email", $email)
            ->orWhere("number", $number)
            ->first();
    }

    public function studentInfo($year = null)
    {
        if ($year != null && $year != '' && $year != 'all') {
            $year = explode('-', $year);
            return Student::whereBetween('created_at', ["$year[0]-05-01", "$year[1]-04-30"])->get();
        }
        return Student::all();
    }

    public function checkUserEmailExists($postData, $id)
    {
        $userId = $this->getById($id);
        $user = User::where('email', $postData['email'])->where('id', '!=', $userId->user_id)->exists();
        return $user;
    }

    public function checkStudentEmailExists($postData, $id)
    {
        $user = Student::where('email', $postData['email'])->where('id', '!=', $id)->exists();
        return $user;
    }

    public function getStudentUserId($id)
    {
        $studentData = $this->getById($id);
        $userId = $studentData->user_id;
        return $userId;
    }

    public function updateStudentUser($user, $userId)
    {
        return User::where('id', $userId)->update([
            'email' => $user['email'],
            'name' => $user['name'],
        ]);
    }

    public function getStudentNotAllotBed()
    {
        $reservations = Reservation::pluck('student_id');
        return Student::whereNotIn('id', $reservations)->get();
    }

    public function updateProfile($postData, $id)
    {
        $studentUserId = $this->getStudentUserId($id);
        unset($postData['_token'], $postData['_method'], $postData['action'], $postData['email']);
        $model = Student::findOrFail($id);
        return $model->update($postData);
    }

    public function studentInfoYearWise()
    {
        $student = Student::whereYear('created_at', date('Y'));
        return $student;
    }

    public function updateStudentEmail($data, $userId)
    {
        return User::where('id', $userId)->update(['email' => $data]);
    }

    public function getStudentIdAdmissionMap($admission_id)
    {
        return StudentAdmissionMap::where('admission_id', $admission_id)->value("student_id");
    }

    public function getStudentByUserId()
    {
        /* return User::join('students as stud', 'stud.user_id', 'users.id')
            ->select('users.*', 'stud.*', 'c.name as countryName', 'stud.id as studet_id', 'v.name as villageName')
            ->leftJoin('country as c', 'c.id', 'stud.country_id')
            ->leftJoin('villages as v', 'v.id', 'stud.village_id')
            ->where('users.id', Auth::user()->id)
            ->first(); */

            $student = Student::whereUserId(Auth::user()->id)->first();

            return $student;
    }

    public function getStudentByPassedUserId($userId)
    {
        return User::join('students as stud', 'stud.user_id', 'users.id')
            ->select('users.*', 'stud.*', 'c.name as countryName', 'stud.id as studet_id')
            ->leftJoin('country as c', 'c.id', 'stud.country_id')
            ->where('users.id', $userId)
            ->first();
    }

    public function userFullDetail($userId)
    {
        return User::leftJoin('students as s', 's.user_id', 'users.id')
            ->leftJoin('reservations', 's.id', 'reservations.student_id')
            ->leftJoin('hostels as h', 'h.id', 'reservations.hostel_id')
            ->leftJoin('rooms as rm', 'rm.id', 'reservations.room_id')
            ->leftJoin('beds as bd', 'bd.id', 'reservations.bed_id')
            ->select('h.hostel_name', 'rm.room_number', 'bd.bed_number', 's.first_name', 's.last_name', 's.email')
            ->where(function ($query) use ($userId) {
                return $query->where('s.user_id', $userId);
            })
            ->orderBy('reservations.id', 'DESC')
            ->first();
    }

    public function getConfirmStudent()
    {
        $admissionIds = Admission::where('is_admission_confirm', '1')->pluck('id');
        return Student::select('students.*')->join('student_admission_map as sam', 'sam.student_id', 'students.id')->whereIn('sam.admission_id', $admissionIds)->get();
    }

    public function getNoAdmissionStudent()
    {
        $currentYear  = date('Y');
        $nextYear = date('Y', strtotime('+1 year'));
        $studentIds = StudentAdmissionMap::whereBetween('created_at', ["$currentYear-05-01", "$nextYear-04-30"])->pluck('student_id');
        return Student::whereNotIn('id', $studentIds)->get();
    }

    public function getByStudentId($id)
    {
        return Student::whereId($id)->first();
    }

    public function isHostelExist($id)
    {
        return StudentAdmissionMap::where('hostel_id', $id)->exists();
    }

    public function isRoomExist($id)
    {
        return StudentAdmissionMap::where('room_id', $id)->exists();
    }

    public function isBedExist($id)
    {
        return StudentAdmissionMap::where('bed_id', $id)->exists();
    }

    public function isCountryExist($id)
    {
        return Student::where('country_id', $id)->exists();
    }

    public function isVillageExist($id)
    {
        return Student::where('village_id', $id)->exists();
    }
}
