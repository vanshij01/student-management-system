<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Student;
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
        $studentId = Student::whereUserId(Auth::user()->id)->value('id');
        $letters = $this->apologyLetterRepository->getByStudentId($studentId);
        return view('frontend.apology_letter.index', compact('letters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $students = $this->studentRepository->getAll();
        return view('frontend.apology_letter.create', compact('students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $post_data = $request->all();
        $post_data['student_id'] = Student::whereUserId(Auth::user()->id)->value('id');
        $defaultDocumentPath = "Uploads/ApologyLetter/" . date("Y") . "/" . $post_data['student_id'] . "/";

        if ($request->hasFile('document')) {
            $document = $request->file('document');
            $fileName = $request->file('document')->getClientOriginalName();
            $docPath = $defaultDocumentPath . $fileName;
            Storage::disk('uploads')->put($docPath, file_get_contents($document));
            $post_data['document'] = $docPath;
        } else {
            $docPath =  NULL;
        }
        $post_data['document'] = $docPath;
        $params['created_by'] = Auth::user()->id;

        $this->apologyLetterRepository->create($post_data);

        /* return redirect()->route('student.apology_letter.index')->with([
            'message' => 'Apology Letter Added Successfully!',
            'status' => 'success'
        ]); */
        return redirect()->route('student.dashboard')->with([
            'message' => 'Apology Letter Added Successfully!',
            'status' => 'success'
        ]);
    }
}
