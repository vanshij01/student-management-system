<?php

namespace App\Repositories;

use App\Contract\BedRepositoryInterface;
use App\Models\Bed;
use App\Models\StudentAdmissionMap;

class BedRepository implements BedRepositoryInterface
{
    public function getAll()
    {
        return Bed::with('hostel', 'room')->orderBy("id", "desc")->get();
    }

    public function getById($id)
    {
        return Bed::findOrFail($id);
    }

    public function create($params)
    {
        return Bed::create($params);
    }

    public function update($payLoad, $id)
    {
        $course = Bed::findOrFail($id);
        return $course->update($payLoad);
    }

    public function delete($id)
    {
        $user = Bed::findOrFail($id);
        return $user->delete();
    }

    public function bedData()
    {
        return Bed::with('hostel', 'room')->orderBy("id", "desc")->get();
    }

    public function getAvailableBed($year = null)
    {
        if ($year != null && $year != '' && $year != 'all') {
            $year = explode('-', $year);
            $bedIds = StudentAdmissionMap::whereBetween('created_at', ["$year[0]-05-01", "$year[1]-04-30"])->pluck('bed_id');
            $availabelBeds = Bed::whereNotIn('id', $bedIds)->get();
        } else {
            $bedIds = StudentAdmissionMap::pluck('bed_id');
            $availabelBeds = Bed::whereNotIn('id', $bedIds)->get();
        }
        return $availabelBeds;
    }

    public function isExist($data, $id = null)
    {
        return Bed::where([["hostel_id", $data['hostel_id']], ["room_id", $data['room_id']], ["bed_number", $data['bed_number']], ['id', '!=', $id]])
            ->exists();
    }

    public function getBedByRoomId($id)
    {
        $bedIds = StudentAdmissionMap::where([['admission_year', date('Y')], ['room_id', $id]])->pluck('bed_id');
        return Bed::whereRoomId($id)->whereNotIn('id', $bedIds)->pluck('bed_number', 'id');
    }
    
    public function getAvailableBedReport($data)
    {
        $year = explode('-', $data['year']);
        $bedIds = StudentAdmissionMap::whereBetween('created_at', ["$year[0]-05-01", "$year[1]-04-30"]);

        if (!empty($data['hostel_id'])) {
            $bedIds->where('hostel_id', $data['hostel_id']);
        }
        $bedIds = $bedIds->pluck('bed_id');
        $availabelBeds = Bed::select('beds.bed_number', 'r.room_number')->join('rooms as r', 'r.id', 'beds.room_id')->whereNotIn('beds.id', $bedIds);
        if (!empty($data['hostel_id'])) {
            $availabelBeds->where('r.hostel_id', $data['hostel_id']);
        }
        return $availabelBeds->get();
    }

    public function getBedByReservation($id)
    {
        return Bed::where('room_id', $id)->get()->pluck('bed_number', 'id');
    }

    public function isHostelExist($id)
    {
        return Bed::where('hostel_id', $id)->exists();
    }

    public function isRoomExist($id)
    {
        return Bed::where('room_id', $id)->exists();
    }
}
