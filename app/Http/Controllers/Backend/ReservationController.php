<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Repositories\AdmissionRepository;
use App\Repositories\BedRepository;
use App\Repositories\HostelRepository;
use App\Repositories\ReservationRepository;
use App\Repositories\RoomRepository;
use App\Repositories\StudentRepository;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    private $bedRepository;
    private $hostelRepository;
    private $roomRepository;
    private $admissionRepository;
    private $studentRepository;
    private $reservationRepository;

    public function __construct(
        BedRepository $bedRepository,
        HostelRepository $hostelRepository,
        RoomRepository $roomRepository,
        AdmissionRepository $admissionRepository,
        StudentRepository $studentRepository,
        ReservationRepository $reservationRepository,
    ) {
        $this->bedRepository = $bedRepository;
        $this->hostelRepository = $hostelRepository;
        $this->roomRepository = $roomRepository;
        $this->admissionRepository = $admissionRepository;
        $this->studentRepository = $studentRepository;
        $this->reservationRepository = $reservationRepository;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $params = $request->all();
        $this->reservationRepository->bedAllocation($params);

        return response()->json([
            'status' => 'success',
            'message' => 'Bed allot successfully.',
            'url' => '/admission'
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
