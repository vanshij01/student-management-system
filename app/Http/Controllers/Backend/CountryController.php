<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repositories\StudentRepository;
use App\Repositories\CountryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class CountryController extends Controller
{
    private $countryRepository;
    private $studentRepository;

    public function __construct(
        CountryRepository $countryRepository,
        StudentRepository $studentRepository,
    ) {
        $this->countryRepository = $countryRepository;
        $this->studentRepository = $studentRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('backend.country.index');
    }

    public function countryData()
    {
        $country = $this->countryRepository->getAll();
        return datatables()->of($country)->addIndexColumn()->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.country.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $params = $request->all();
        $params['created_by'] = Auth::user()->id;

        $this->countryRepository->create($params);
        return response()->json([
            'status' => 'success',
            'message' => 'Country added successfully',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $country = $this->countryRepository->getById($id);
        return view('backend.country.show', compact('country'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $country = $this->countryRepository->getById($id);
        return view('backend.country.update', compact('country'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $payLoad = $request->all();
        $this->countryRepository->update($payLoad, $id);
        return redirect('country')->with([
            'status' => 'success',
            'message' => 'Country updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        /* $isCountryExists = $this->studentRepository->isCountryExist($id);
        if ($isCountryExists) {
            return response()->json([
                "status" => false,
                "message" => "This is already in Use"
            ]);
        } */

        $this->countryRepository->delete($id);
        Session::flash('success', 'Country deleted successfully');
        return response()->json([
            'status' => true,
            'message' => 'Country deleted successfully!',
        ]);
    }
}
