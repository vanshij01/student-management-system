<?php

namespace App\Repositories;

use App\Contract\RoomRepositoryInterface;
use App\Models\Room;
use App\Models\StudentAdmissionMap;

class RoomRepository implements RoomRepositoryInterface
{
    public function getAll()
    {
        return Room::orderBy("id", "asc")->get();
    }

    public function getById($id)
    {
        return Room::findOrFail($id);
    }

    public function create($params)
    {
        return Room::create($params);
    }

    public function update($payLoad, $id)
    {
        $room = Room::findOrFail($id);
        return $room->update($payLoad);
    }

    public function delete($id)
    {
        $user = Room::findOrFail($id);
        return $user->delete();
    }

    public function roomData($year = null)
    {
        if ($year != null && $year != '' && $year != 'all') {
            $year = explode('-', $year);
            $roomIds = StudentAdmissionMap::whereBetween('created_at', ["$year[0]-05-01", "$year[1]-04-30"])->pluck('room_id');
            $rooms = Room::whereNotIn('id', $roomIds)->get();
        } else {
            $rooms = Room::with('hostel')->orderBy("id", "desc")->get();
        }
        return $rooms;
    }

    public function isExist($data, $id = null)
    {
        return Room::where([["hostel_id", $data['hostel_id']], ['room_number', $data['room_number']], ['id', '!=', $id]])->exists();
    }

    public function getRoomByHostelId($id)
    {
        return Room::whereHostelId($id)->where('status', '1')->pluck('room_number', 'id');
    }

    public function isHostelExist($id)
    {
        return Room::where('hostel_id', $id)->exists();
    }
}
