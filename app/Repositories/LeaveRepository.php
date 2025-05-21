<?php

namespace App\Repositories;

use App\Contract\LeaveRepositoryInterface;
use App\Models\Complain;
use App\Models\Leave;
use Illuminate\Support\Facades\Auth;

class LeaveRepository implements LeaveRepositoryInterface
{
    public function getAll($year = null)
    {
        if ($year != null && $year != '' && $year != 'all') {
            $year = explode('-', $year);
            return Leave::whereBetween('created_at', ["$year[0]-05-01", "$year[1]-04-30"])->get();
        }
        return Leave::orderBy("created_at", "desc")->get();
    }

    public function getById($id)
    {
        return Leave::where("id", $id)->first();
    }

    public function create($postData)
    {
        return Leave::create($postData);
    }

    public function update($postData, $id)
    {
        $model = Leave::findOrFail($id);
        return $model->update($postData);
    }

    public function delete($id)
    {
        $model = Leave::findOrFail($id);
        return $model->delete();
    }

    public function leaveData($data)
    {
        $leaves =  Leave::select('h.hostel_name', 'rm.room_number', 's.first_name', 's.last_name', 'leaves.*', 'users.name as userName')
            ->leftJoin('users', 'users.id', 'leaves.approve_by')
            ->leftJoin('students as s', 's.id', 'leaves.leave_apply_by')
            ->leftJoin('reservations', 's.id', 'reservations.student_id')
            ->leftJoin('hostels as h', 'h.id', 'reservations.hostel_id')
            ->leftJoin('rooms as rm', 'rm.id', 'reservations.room_id');
        // dd();

        if (!empty($data['student_id'])) {
            $leaves->where("leaves.leave_apply_by", $data['student_id']);
        }

        if (!empty($data['leave_status'])) {
            $leaves->where("leaves.leave_status", $data['leave_status']);
        }

        $from = ($data['from']) ? \DateTime::createFromFormat('d/m/Y', $data['from'])->format('Y-m-d') : null;
        $to = ($data['to']) ? \DateTime::createFromFormat('d/m/Y', $data['to'])->format('Y-m-d') : null;
        // dd($from);

        if ($from != null && $to != null) {
            $leaves->whereBetween('leaves.created_at', ["$from", "$to"]);
        } else {
            if ($from != null) {
                $leaves->where('leaves.created_at', '>=', "$from");
            }
        }

        return $leaves->orderBy("leaves.created_at", "desc")->get();
    }

    public function isExist($name, $email, $number)
    {
        return Leave::where("name", $name)
            ->orWhere("email", $email)
            ->orWhere("number", $number)
            ->first();
    }

    public function getLeaveWithJoin($id)
    {
        return Leave::with('student', 'user')->where("id", $id)->first();
    }

    public function getByStudentId($studentId)
    {
        return Leave::where('leave_apply_by', $studentId)->orderBy("created_at", "desc")->get();
    }
}
