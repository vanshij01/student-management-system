<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repositories\ApologyLetterRepository;
use App\Repositories\StudentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class ApologyLetterController extends Controller
{
    private $apologyLetterRepository;
    private $studentRepository;

    public function __construct(ApologyLetterRepository $apologyLetterRepository, StudentRepository $studentRepository)
    {
        $this->apologyLetterRepository = $apologyLetterRepository;
        $this->studentRepository = $studentRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('backend.apology_letter.index');
    }

    public function apologyLetterData()
    {
        $letters = $this->apologyLetterRepository->getAll();
        return datatables()->of($letters)->addIndexColumn()->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $students = $this->studentRepository->getAll();
        return view('backend.apology_letter.create', compact('students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $post_data = $request->all();
        $defaultDocumentPath = "ApologyLetter/" . date("Y") . "/" . $post_data['student_id'] . "/";

        if ($request->hasFile('document')) {
            $document = $request->file('document');
            $fileName = $request->file('document')->getClientOriginalName();
            $filePath = $defaultDocumentPath . $fileName;
            Storage::disk('uploads')->put($filePath, file_get_contents($document));
            $post_data['document'] = $filePath;
        } else {
            $filePath =  NULL;
        }
        $post_data['document'] = $filePath;
        $params['created_by'] = Auth::user()->id;

        $this->apologyLetterRepository->create($post_data);

        return response()->json([
            'status' => 'success',
            'message' => 'Apology Letter added successfully'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $apology_letter = $this->apologyLetterRepository->getById($id);
        return view('backend.apology_letter.show', compact('apology_letter'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $apology_letter = $this->apologyLetterRepository->getById($id);
        $students = $this->studentRepository->getAll();
        return view('backend.apology_letter.update', compact('apology_letter', 'students'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $payLoad = $request->all();
        /* $defaultDocumentPath = "ApologyLetter/" . date("Y") . "/" . $payLoad['student_id'] . "/";

        if ($request->hasFile('document')) {
            $document = $request->file('document');
            $fileName = $request->file('document')->getClientOriginalName();
            $filePath = $defaultDocumentPath . $fileName;
            Storage::disk('uploads')->put($filePath, file_get_contents($document));
        } else {
            $filePath =  NULL;
        } */

        $defaultDocumentPath = "Uploads/ApologyLetter/" . date("Y") . "/" . $payLoad['student_id'] . "/";
        if ($request->hasFile('document')) {
            $document = $request->file('document');
            $fileName = $request->file('document')->getClientOriginalName();
            $docPath = $defaultDocumentPath . $fileName;
            if ($payLoad['old_apology'] && Storage::disk('uploads')->exists($payLoad['old_apology'])) {
                    Storage::disk('uploads')->delete($payLoad['old_apology']);
                }
            Storage::disk('uploads')->put($docPath, file_get_contents($document));
            $payLoad['document'] = $docPath;
        }

        $payLoad['document'] = $docPath ?? $payLoad['old_apology'];

        $this->apologyLetterRepository->update($payLoad, $id);
        return redirect('apology_letter')->with([
            'status' => 'success',
            'message' => 'Apology Letter updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->apologyLetterRepository->delete($id);
        return response()->json([
            'status' => true,
            'message' => 'Apology Letter deleted successfully!',
        ]);
    }
}
