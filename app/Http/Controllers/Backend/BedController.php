<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repositories\BedRepository;
use App\Repositories\HostelRepository;
use App\Repositories\RoomRepository;
use App\Repositories\StudentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class BedController extends Controller
{
    private $bedRepository;
    private $hostelRepository;
    private $roomRepository;
    private $studentRepository;

    public function __construct(
        BedRepository $bedRepository,
        HostelRepository $hostelRepository,
        RoomRepository $roomRepository,
        StudentRepository $studentRepository,
    ) {
        $this->bedRepository = $bedRepository;
        $this->hostelRepository = $hostelRepository;
        $this->roomRepository = $roomRepository;
        $this->studentRepository = $studentRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('backend.bed.index');
    }

    public function bedData()
    {
        $bed = $this->bedRepository->getAll();
        return datatables()->of($bed)->addIndexColumn()->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $hostels = $this->hostelRepository->getAll();
        $rooms = $this->roomRepository->getAll();
        return view('backend.bed.create', compact('hostels', 'rooms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $params = $request->all();
        $isBedExist = $this->bedRepository->isExist($params);
        if ($isBedExist) {
            return response()->json([
                'status' => 'error',
                'message' => 'The bed number has already been taken',
            ]);
        }
        $params['created_by'] = Auth::user()->id;

        $this->bedRepository->create($params);
        return response()->json([
            'status' => 'success',
            'message' => 'Bed added successfully',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bed = $this->bedRepository->getById($id);
        return view('backend.bed.show', compact('bed'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $bed = $this->bedRepository->getById($id);
        $hostels = $this->hostelRepository->getAll();
        $rooms = $this->roomRepository->getAll();
        return view('backend.bed.update', compact('bed', 'hostels', 'rooms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $payLoad = $request->all();
        $isBedExist = $this->bedRepository->isExist($payLoad, $id);
        if ($isBedExist) {
            return redirect()->back()
            ->withErrors(['bed_number' => 'Bed is already available for this hostel and room.'])
            ->withInput();
        }
        $this->bedRepository->update($payLoad, $id);
        return redirect('bed')->with([
            'status' => 'success',
            'message' => 'Bed updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $isStudentExists = $this->studentRepository->isBedExist($id);

        if ($isStudentExists) {
            return response()->json([
                "status" => false,
                "message" => "This is already in Use"
            ]);
        }

        $this->bedRepository->delete($id);
        return response()->json([
            'status' => true,
            'message' => 'Bed deleted successfully!',
        ]);
    }

    public function getBedList(Request $request, $id)
    {
        $beds = $this->bedRepository->getBedByRoomId($id);
        return response()->json($beds);
    }
}
