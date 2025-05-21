<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repositories\HostelRepository;
use App\Repositories\UserRepository;
use App\Repositories\WardenRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class WardenController extends Controller
{
    private $wardenRepository;
    protected $userRepository;
    protected $hostelRepository;

    public function __construct(UserRepository $userRepository, WardenRepository $wardenRepository, HostelRepository $hostelRepository)
    {
        $this->wardenRepository = $wardenRepository;
        $this->userRepository = $userRepository;
        $this->hostelRepository = $hostelRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('backend.warden.index');
    }

    public function wardenData()
    {
        $warden = $this->wardenRepository->getAll();
        return datatables()->of($warden)->addIndexColumn()->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.warden.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $params = $request->all();

        $validator = Validator::make($params, [
            'email' => 'required|email|unique:users',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ]);
        }

        $params['role'] = 'warden';
        $params['role_id'] = 3;
        $params['name'] = $params['first_name'] . ' ' . $params['last_name'];
        unset($params['first_name'], $params['last_name'], $params['phone']);
        /* activity()->disableLogging(); */
        $id = $this->userRepository->create($params);
        /* activity()->enableLogging(); */
        $wardenData = $request->all();
        /* $wardenData['dob'] = date('Y-m-d', strtotime($wardenData['dob'])); */
        $wardenData['dob'] = ($request->dob) ? \DateTime::createFromFormat('d/m/Y', $wardenData['dob'])->format('Y-m-d') : null;

        $wardenData['user_id'] = $id['id'];
        $params['created_by'] = Auth::user()->id;

        $this->wardenRepository->create($wardenData);
        return response()->json([
            'status' => 'success',
            'message' => 'Warden registered successfully',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $warden = $this->wardenRepository->getById($id);
        $hostels = $this->wardenRepository->getHostelWarden($id);
        return view('backend.warden.show', compact('warden', 'hostels'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $warden = $this->wardenRepository->getById($id);
        return view('backend.warden.update', compact('warden'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $payLoad = $request->all();
        $payLoad['dob'] = ($request->dob) ? \DateTime::createFromFormat('d/m/Y', $payLoad['dob'])->format('Y-m-d') : null;
        $isUserEmailExists = $this->wardenRepository->checkEmailExists($payLoad, $id);
        $isWardenEmailExists = $this->wardenRepository->checkWardenEmailExists($payLoad, $id);
        if ($isUserEmailExists || $isWardenEmailExists) {
            return redirect('warden/' . $id . '/edit')->with('emailExists', 'The email has already been taken');
        }
        $this->wardenRepository->update($payLoad, $id);
        return redirect('warden')->with([
            'status' => 'success',
            'message' => 'Warden updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $isHostelExists = $this->hostelRepository->isWardenExist($id);
        if ($isHostelExists) {
            return response()->json([
                "status" => false,
                "message" => "This is already in Use"
            ]);
        }
        $this->wardenRepository->delete($id);
        Session::flash('success', 'Warden deleted successfully');
        return response()->json([
            'status' => true,
            'message' => 'Warden deleted successfully!',
        ]);
    }
}
