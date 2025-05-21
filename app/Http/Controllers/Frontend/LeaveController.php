<?php

namespace App\Http\Controllers\Frontend;

use App\Events\LeaveCreateEvent;
use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Repositories\LeaveRepository;
use App\Repositories\StudentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class LeaveController extends Controller
{
    private $leaveRepository;
    private $studentRepository;

    public function __construct(LeaveRepository $leaveRepository, StudentRepository $studentRepository)
    {
        $this->leaveRepository = $leaveRepository;
        $this->studentRepository = $studentRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $studentId = Student::whereUserId(Auth::user()->id)->value('id');
        $leaves = $this->leaveRepository->getByStudentId($studentId);
        return view('frontend.leave.index', compact('leaves'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('frontend.leave.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $postData = $request->all();
        $postData['leave_from'] = ($request->leave_from) ? \DateTime::createFromFormat('d/m/Y', $postData['leave_from'])->format('Y-m-d') : null;
        $postData['leave_to'] = ($request->leave_to) ? \DateTime::createFromFormat('d/m/Y', $postData['leave_to'])->format('Y-m-d') : null;
        $postData['leave_apply_by'] = Student::whereUserId(Auth::user()->id)->value('id');
        $studentData = Student::find($postData['leave_apply_by']);

        $defaultDocumentPath = "Uploads/Leave/" . date("Y") . "/" . $postData['leave_apply_by'] . "/";
        if ($request->hasFile('ticket')) {
            $ticket = $request->file('ticket');
            $fileName = $request->file('ticket')->getClientOriginalName();
            $docPath = $defaultDocumentPath . $fileName;
            Storage::disk('uploads')->put($docPath, file_get_contents($ticket));
            $postData['ticket'] = $docPath;
        } else {
            $docPath =  NULL;
        }
        $postData['ticket'] = $docPath;

        /* $mailArr = [
            "email" => $studentData->email,
            "title" => "Leave create Mail",
            'studentData' => $studentData,
            'leave' => $postData,
        ];

        Mail::send('mail/create_leave_mail', $mailArr, function ($message) use ($mailArr) {
            $message->to($mailArr['email'], 'John Doe');
            $message->subject($mailArr['title']);
        }); */

        $params['created_by'] = Auth::user()->id;

        $leave = $this->leaveRepository->create($postData);

        event(new LeaveCreateEvent($leave, $studentData));
        /* return redirect()->route('student.leave.index')->with([
            'message' => 'Leave Added Successfully!',
            'status' => 'success'
        ]); */
        return redirect()->route('student.dashboard')->with([
            'message' => 'Leave Added Successfully!',
            'status' => 'success'
        ]);
    }
}
