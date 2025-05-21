<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repositories\AdmissionRepository;
use App\Repositories\CourseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class CourseController extends Controller
{
    private $courseRepository;
    private $admissionRepository;

    public function __construct(
        CourseRepository $courseRepository,
        AdmissionRepository $admissionRepository
    ) {
        $this->courseRepository = $courseRepository;
        $this->admissionRepository = $admissionRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('backend.course.index');
    }

    public function courseData()
    {
        $course = $this->courseRepository->getAll();
        return datatables()->of($course)->addIndexColumn()->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.course.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $params = $request->all();
        $params['created_by'] = Auth::user()->id;
        $this->courseRepository->create($params);
        return response()->json([
            'status' => 'success',
            'message' => 'Course added successfully',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $course = $this->courseRepository->getById($id);
        return view('backend.course.show', compact('course'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $course = $this->courseRepository->getById($id);
        return view('backend.course.update', compact('course'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $payLoad = $request->all();
        $this->courseRepository->update($payLoad, $id);
        return redirect('course')->with([
            'status' => 'success',
            'message' => 'Course updated successfully!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $isCourseExists = $this->admissionRepository->isCourseExist($id);

        if ($isCourseExists) {
            return response()->json([
                "status" => false,
                "message" => "This is already in Use"
            ]);
        }

        $this->courseRepository->delete($id);
        return response()->json([
            'status' => true,
            'message' => 'Course deleted successfully!',
        ]);
    }
}
