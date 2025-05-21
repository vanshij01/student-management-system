<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Repositories\ComplainRepository;
use App\Repositories\StudentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class ComplainController extends Controller
{
    private $complainRepository;
    private $studentRepository;

    public function __construct(ComplainRepository $complainRepository, StudentRepository $studentRepository)
    {
        $this->complainRepository = $complainRepository;
        $this->studentRepository = $studentRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $studentId = Student::whereUserId(Auth::user()->id)->value('id');
        $complains = $this->complainRepository->getByStudentId($studentId);
        return view('frontend.complain.index', compact('complains'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $students = $this->studentRepository->getAll();
        return view('frontend.complain.create', compact('students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $post_data = $request->all();

        $post_data['student_id'] = Student::whereUserId(Auth::user()->id)->value('id');
        $post_data['complain_by'] = $post_data['student_id'];
        $post_data['user_id'] = Auth::user()->id;

        $defaultDocumentPath = "Uploads/Complain/" . date("Y") . "/" . $post_data['student_id'] . "/";

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

        $studentData = User::find($post_data['complain_by']);

        $mailArr = [
            "email" => $studentData->email,
            "title" => "Complain crate Mail",
            'studentData' => $studentData,
            'complain' => $post_data,
        ];

        Mail::send('mail/create_complain_mail', $mailArr, function ($message) use ($mailArr) {
            $message->to($mailArr['email'], 'John Doe');
            $message->subject($mailArr['title']);
        });
        /* event(new ComplainCreateEvent($studentData, $post_data)); */
        $params['created_by'] = Auth::user()->id;

        $this->complainRepository->create($post_data);

        /* return redirect()->route('student.complain.index')->with([
            'message' => 'Complain Added Successfully!',
            'status' => 'success'
        ]); */
        return redirect()->route('student.dashboard')->with([
            'message' => 'Complain Added Successfully!',
            'status' => 'success'
        ]);
    }
}
