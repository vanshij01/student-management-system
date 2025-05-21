<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repositories\BedRepository;
use App\Repositories\HostelRepository;
use App\Repositories\RoomRepository;
use App\Repositories\StudentRepository;
use App\Repositories\WardenRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class HostelController extends Controller
{
    private $hostelRepository;
    private $wardenRepository;
    private $roomRepository;
    private $bedRepository;
    private $studentRepository;

    public function __construct(
        HostelRepository $hostelRepository,
        WardenRepository $wardenRepository,
        RoomRepository $roomRepository,
        BedRepository $bedRepository,
        StudentRepository $studentRepository,
    ) {
        $this->hostelRepository = $hostelRepository;
        $this->wardenRepository = $wardenRepository;
        $this->roomRepository = $roomRepository;
        $this->bedRepository = $bedRepository;
        $this->studentRepository = $studentRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.hostel.index');
    }

    public function hostelData(Request $request)
    {
        $hostel = $this->hostelRepository->hostelData();
        return datatables()->of($hostel)->addIndexColumn()->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $wardens = $this->wardenRepository->getAll();
        return view('backend.hostel.create', compact('wardens'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'hostel_name' => 'required|unique:hostels,hostel_name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ]);
        }

        $params['created_by'] = Auth::user()->id;

        $this->hostelRepository->create($params);
        return response()->json([
            'status' => 'success',
            'message' => 'Hostel added successfully',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $hostel = $this->hostelRepository->getById($id);
        $wardens = $this->wardenRepository->getAll();
        return view('backend.hostel.show', compact('hostel', 'wardens'));
        // return response()->json($hostel, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $hostel = $this->hostelRepository->getById($id);
        $wardens = $this->wardenRepository->getAll();
        return view('backend.hostel.update', compact('hostel', 'wardens'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $payLoad = $request->all();
        $validator = Validator::make($payLoad, [
            'hostel_name' => 'required|unique:hostels,hostel_name,'. $id,
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput($payLoad);
        }

        $this->hostelRepository->update($payLoad, $id);
        return redirect('hostel')->with([
            'status' => 'success',
            'message' => 'Hostel updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $isRoomExists = $this->roomRepository->isHostelExist($id);
        $isBedExists = $this->bedRepository->isHostelExist($id);
        /* $isStudentExists = $this->studentRepository->isRoomExist($id); */

        if ($isRoomExists || $isBedExists /* || $isStudentExists */) {
            return response()->json([
                "status" => false,
                "message" => "This is already in Use"
            ]);
        }
        $this->hostelRepository->delete($id);
        Session::flash('success', 'Hostel Deleted successfully');
        return response()->json([
            'status' => true,
            'message' => 'Hostel Deleted successfully',
        ]);
    }
}
