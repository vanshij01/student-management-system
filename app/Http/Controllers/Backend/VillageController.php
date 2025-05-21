<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repositories\StudentRepository;
use App\Repositories\VillageRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class VillageController extends Controller
{
    private $villageRepository;
    private $studentRepository;

    public function __construct(
        VillageRepository $villageRepository,
        StudentRepository $studentRepository,
    ) {
        $this->villageRepository = $villageRepository;
        $this->studentRepository = $studentRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('backend.village.index');
    }

    public function villageData(Request $request)
    {
        $village = $this->villageRepository->getAll();
        return datatables()->of($village)->addIndexColumn()->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.village.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $params = $request->all();
        $params['created_by'] = Auth::user()->id;

        $this->villageRepository->create($params);
        return response()->json([
            'status' => 'success',
            'message' => 'Village added successfully',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $village = $this->villageRepository->getById($id);
        return view('backend.village.show', compact('village'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $village = $this->villageRepository->getById($id);
        return view('backend.village.update', compact('village'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $payLoad = $request->all();
        $this->villageRepository->update($payLoad, $id);
        return redirect('village')->with('success', 'Village updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        /* $isVillageExists = $this->studentRepository->isVillageExist($id);
        if ($isVillageExists) {
            return response()->json([
                "status" => false,
                "message" => "This is already in Use"
            ]);
        } */

        $this->villageRepository->delete($id);
        Session::flash('success', 'Village deleted successfully');
        return response()->json([
            'status' => true,
            'message' => 'Village deleted successfully!',
        ]);
    }
}
