<?php

namespace App\Repositories;

use App\Contract\ComplainRepositoryInterface;
use App\Models\Complain;
use Illuminate\Support\Facades\Auth;

class ComplainRepository implements ComplainRepositoryInterface
{
    public function getAll($year = null)
    {
        $complain = Complain::orderBy("created_at", "desc");

        if (!empty($year)) {
            $yearParts = explode('-', $year);
            $start = "{$yearParts[0]}-05-01";
            $end = "{$yearParts[1]}-04-30";
            $complain->whereBetween('created_at', [$start, $end]);
        }
        return $complain;
    }

    public function getById($id)
    {
        return Complain::where("id", $id)->first();
    }

    public function create($postData)
    {
        return Complain::create($postData);
    }

    public function update($postData, $id)
    {
        unset($postData['_token'], $postData['_method'], $postData['action']);
        $model = Complain::findOrFail($id);
        return $model->update($postData);
    }

    public function delete($id)
    {
        $model = Complain::findOrFail($id);
        return $model->delete();
    }

    public function complainData($data)
    {
        $loginUser = Auth::user();
        $userId = $loginUser->id;

        $leaves = Complain::leftJoin('students as s', 's.id', 'complains.complain_by')
            ->leftJoin('reservations', 's.id', 'reservations.student_id')
            ->leftJoin('hostels as h', 'h.id', 'reservations.hostel_id')
            ->leftJoin('rooms as rm', 'rm.id', 'reservations.room_id')
            ->select('h.hostel_name', 'rm.room_number', 's.*', 'complains.*')
            ->where(function ($query) use ($userId, $loginUser) {
                if ($loginUser->role_id == 4) {
                    return $query->where('complain_by', $userId);
                }
            });

        if (!empty($data['complain_by'])) {
            $leaves->where("complains.complain_by", $data['complain_by']);
        }

        if (!empty($data['type'])) {
            $leaves->where("complains.type", $data['type']);
        }

        if (!empty($data['status'])) {
            $leaves->where("complains.status", $data['status']);
        }

        return $leaves->orderBy("complains.created_at", "desc")->get();
    }

    public function isExist($name, $email, $number)
    {
        return Complain::where("name", $name)
            ->orWhere("email", $email)
            ->orWhere("number", $number)
            ->first();
    }

    public function getByStudentId($studentId)
    {
        return Complain::where('complain_by', $studentId)->orderBy("created_at", "desc")->get();
    }

    public function complainStatusData($year = null)
    {
        if (!empty($year)) {
            $year = explode('-', $year);
            $complain_status = Complain::whereBetween('created_at', ["$year[0]-05-01", "$year[1]-04-30"])
                ->pluck('status');
            $complain_status = $complain_status->toArray();
            $complain_status = array_count_values($complain_status);
        }
        return $complain_status;
    }
}
