<?php

namespace App\Repositories;

use App\Contract\ReservationRepositoryInterface;
use App\Models\Reservation;
use App\Models\StudentAdmissionMap;

class ReservationRepository implements ReservationRepositoryInterface
{
    public function getAll()
    {
        return Reservation::join('students as s', 's.id', 'reservations.student_id')
            ->join('hostels as h', 'h.id', 'reservations.hostel_id')
            ->join('rooms as rm', 'rm.id', 'reservations.room_id')
            ->join('beds as b', 'b.id', 'reservations.bed_id')
            ->select('h.hostel_name', 'rm.room_number', 'b.bed_number', 's.first_name', 's.last_name', 'reservations.id')
            ->get();
    }

    public function getById($id)
    {
        return Reservation::where("id", $id)->first();
    }

    public function isExist($name, $email, $number)
    {
        return Reservation::where("name", $name)
            ->orWhere("email", $email)
            ->orWhere("number", $number)
            ->first();
    }

    public function create($postData)
    {
        return Reservation::create($postData);
    }

    public function update($postData, $id)
    {
        unset($postData['_token'], $postData['_method'], $postData['action']);
        return Reservation::where("id", $id)->update($postData);
    }

    public function delete($id)
    {
        return Reservation::where("id", $id)->delete();
    }

    public function bedAllocation($data)
    {
        return StudentAdmissionMap::where([
            ['student_id', $data['student_id']],
            ['admission_id', $data['admission_id']]
        ])->update(['hostel_id' => $data['hostel_id'], 'room_id' => $data['room_id'], 'bed_id' => $data['bed_id']]);
    }
}
