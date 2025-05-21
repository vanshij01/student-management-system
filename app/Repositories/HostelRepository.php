<?php

namespace App\Repositories;

use App\Contract\HostelRepositoryInterface;
use App\Models\Admission;
use App\Models\Bed;
use App\Models\Hostel;
use App\Models\StudentAdmissionMap;

class HostelRepository implements HostelRepositoryInterface
{
    public function getAll()
    {
        return Hostel::orderBy("id", "asc")->get();
    }

    public function getById($id)
    {
        return Hostel::findOrFail($id);
    }

    public function create($params)
    {
        return Hostel::create($params);
    }

    public function update($payLoad, $id)
    {
        $course = Hostel::findOrFail($id);
        return $course->update($payLoad);
    }

    public function delete($id)
    {
        $user = Hostel::findOrFail($id);
        return $user->delete();
    }

    public function hostelData($year = null)
    {
        if ($year != null && $year != '' && $year != 'all') {
            $year = explode('-', $year);
            $hostelIds = StudentAdmissionMap::whereBetween('created_at', ["$year[0]-05-01", "$year[1]-04-30"])->pluck('hostel_id');
            $hostels = Hostel::whereNotIn('id', $hostelIds)->get();
        } else {
            $hostels = Hostel::with('warden')->orderBy("id", "desc")->whereStatus(1)->get();
        }
        return $hostels;
    }

    public function genderStatistics($year = null)
    {
        $hostels = Hostel::all();
        $mArr = [];
        foreach ($hostels as $hostel) {
            if ($year != null && $year != '' && $year != 'all') {
                $singleYear = explode('-', $year);
                $admissionIds = StudentAdmissionMap::whereBetween('created_at', ["$singleYear[0]-05-01", "$singleYear[1]-04-30"])->where('hostel_id', $hostel->id)->pluck('admission_id');
            } else {
                $admissionIds = StudentAdmissionMap::where('hostel_id', $hostel->id)->pluck('admission_id');
            }
            $cArr = [];
            $cArr['hostel_name'] = $hostel->hostel_name;
            $cArr['boys'] = Admission::whereIn('id', $admissionIds)->where('gender', 'boy')->count();
            $cArr['girls'] = Admission::whereIn('id', $admissionIds)->where('gender', 'girl')->count();
            array_push($mArr, $cArr);
        }

        return $mArr;
    }

    public function getChartDataAvailableBed($year = null)
    {
        $hostels = Hostel::all();
        $bedArr = [];
        foreach ($hostels as $hostel) {
            if ($year != null && $year != '' && $year != 'all') {
                $singleYear = explode('-', $year);
                $bedIds = StudentAdmissionMap::whereBetween('created_at', ["$singleYear[0]-05-01", "$singleYear[1]-04-30"])->where('hostel_id', $hostel->id)->pluck('bed_id');
            } else {
                $bedIds = StudentAdmissionMap::where('hostel_id', $hostel->id)->pluck('bed_id');
            }
            $cArr = [];
            $cArr['hostel_name'] = $hostel->hostel_name;
            $cArr['beds'] = Bed::join('rooms as r', 'r.id', 'beds.room_id')->where('r.hostel_id', $hostel->id)->whereNotIn('beds.id', $bedIds)->count();
            array_push($bedArr, $cArr);
        }

        return $bedArr;
    }

    public function getChartDataAdmissionYearwise()
    {
        $hostels = Hostel::with('student')->get();
        $main_male = [];
        $mainYearCount = [];
        foreach ($hostels as $hostel) {
            foreach ($hostel->student as $student) {
                $mainYear[] = $student->admission;
                $mainYearCount[] = $student->admission->count();
            }
        }
        $final = [
            'mainYear' => $mainYear,
            'mainYearCount' => $mainYearCount,
        ];
        return $final;
    }

    public function getHostels()
    {
        return Hostel::all()->pluck('hostel_name', 'id');
    }

    public function isWardenExist($id)
    {
        return Hostel::where('warden_id', $id)->exists();
    }
}
