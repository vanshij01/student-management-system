<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repositories\BedRepository;
use App\Repositories\HostelRepository;
use App\Repositories\RoomRepository;
use App\Repositories\StudentRepository;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class RoomController extends Controller
{
    private $hostelRepository;
    private $roomRepository;
    private $bedRepository;
    private $studentRepository;

    public function __construct(
        HostelRepository $hostelRepository,
        RoomRepository $roomRepository,
        BedRepository $bedRepository,
        StudentRepository $studentRepository,
    ) {
        $this->hostelRepository = $hostelRepository;
        $this->roomRepository = $roomRepository;
        $this->bedRepository = $bedRepository;
        $this->studentRepository = $studentRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('backend.room.index');
    }

    public function roomData(Request $request)
    {
        $room = $this->roomRepository->roomData();
        return datatables()->of($room)->addIndexColumn()->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $hostels = $this->hostelRepository->getAll();
        return view('backend.room.create', compact('hostels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $post_data = $request->all();
        $isRoomExists = $this->roomRepository->isExist($post_data);
        if ($isRoomExists) {
            return response()->json([
                'status' => 'error',
                'message' => 'The room number has already been taken',
            ]);
        }
        $params['created_by'] = Auth::user()->id;

        $this->roomRepository->create($post_data);
        return response()->json([
            'status' => 'success',
            'message' => 'Room added successfully',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $room = $this->roomRepository->getById($id);
        $hostels = $this->hostelRepository->getAll();
        return view('backend.room.show', ['hostels' => $hostels, 'room' => $room]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $room = $this->roomRepository->getById($id);
        $hostels = $this->hostelRepository->getAll();
        return view('backend.room.update', compact('hostels', 'room'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $post_data = $request->all();

        $isRoomExists = $this->roomRepository->isExist($post_data, $id);
        if ($isRoomExists) {
            return redirect()->back()
                ->withErrors(['room_number' => 'The room number has already been taken'])
                ->withInput();
        }

        $this->roomRepository->update($post_data, $id);

        return redirect('room')->with([
            'status' => 'success',
            'message' => 'Room updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $isBedExists = $this->bedRepository->isRoomExist($id);
        /* $isStudentExists = $this->studentRepository->isRoomExist($id); */

        if ($isBedExists /* || $isStudentExists */) {
            return response()->json([
                "status" => false,
                "message" => "This is already in Use"
            ]);
        }

        $this->roomRepository->delete($id);
        Session::flash('success', 'Room deleted successfully');
        return response()->json([
            'status' => 'success',
            'message' => 'Room deleted successfully',
        ]);
    }

    public function getRoomList(Request $request, $id)
    {
        $rooms = $this->roomRepository->getRoomByHostelId($id);
        return response()->json($rooms);
    }
}
