<?php

namespace App\Http\Controllers\Backend;

use App\Events\LeaveCreateEvent;
use App\Events\LeaveStatusChangeEvent;
use App\Http\Controllers\Controller;
use App\Repositories\StudentRepository;
use App\Models\Student;
use App\Repositories\LeaveRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
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
        $leaves = $this->leaveRepository->getAll();
        $students = $this->studentRepository->getAll();
        return view('backend.leave.index', compact('leaves', 'students'));
    }

    public function leaveData(Request $request)
    {
        $data = $request->all();
        // dd($data);
        /* if (!empty($data['student_id']) || $data['from'] != null || $data['to'] != null || !empty($data['leave_status'])) {
        } else {
            $leaves = $this->leaveRepository->leaveData();
        } */
        $leaves = $this->leaveRepository->leaveData($data);
        return datatables()->of($leaves)->addIndexColumn()->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $students = $this->studentRepository->getAll();
        return view('backend.leave.create', compact('students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $postData = $request->all();
        $postData['leave_from'] = ($request->leave_from) ? \DateTime::createFromFormat('d/m/Y', $postData['leave_from'])->format('Y-m-d') : null;
        $postData['leave_to'] = ($request->leave_to) ? \DateTime::createFromFormat('d/m/Y', $postData['leave_to'])->format('Y-m-d') : null;
        $studentData = Student::find($postData['leave_apply_by']);

        $defaultDocumentPath = "Uploads/Leave/" . date("Y") . "/" . $postData['leave_apply_by'] . "/";
        if ($request->hasFile('ticket')) {
            $ticket = $request->file('ticket');
            $fileName = $request->file('ticket')->getClientOriginalName();
            $docPath = $defaultDocumentPath . $fileName;
            Storage::disk('uploads')->put($docPath, file_get_contents($ticket));
            $postData['ticket'] = $docPath;
        } else {
            $docPath = NULL;
        }

        // $postData['ticket'] = $docPath;
        $postData['created_by'] = Auth::user()->id;
        // dd($postData);
        $leave = $this->leaveRepository->create($postData);

        event(new LeaveCreateEvent($leave, $studentData));

        return response()->json([
            'status' => 'success',
            'message' => 'Leave added successfully',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $leave = $this->leaveRepository->getLeaveWithJoin($id);
        return view('backend.leave.show', compact('leave'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $leave = $this->leaveRepository->getLeaveWithJoin($id);
        $students = $this->studentRepository->getAll();
        return view('backend.leave.update', compact('leave', 'students'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $postData = $request->all();
        $postData['note'] = isset($postData['note']) ? $postData['note'] : '';
        unset($postData['_token'], $postData['_method'], $postData['action']);
        // dd($postData);
        $postData['leave_from'] = ($request->leave_from) ? \DateTime::createFromFormat('d/m/Y', $postData['leave_from'])->format('Y-m-d') : null;
        $postData['leave_to'] = ($request->leave_to) ? \DateTime::createFromFormat('d/m/Y', $postData['leave_to'])->format('Y-m-d') : null;
        $studentData = Student::find($postData['leave_apply_by']);
        $defaultDocumentPath = "Uploads/Leave/" . date("Y") . "/" . $postData['leave_apply_by'] . "/";
        if ($request->hasFile('ticket')) {
            // dd($postData['old_ticket'] && Storage::disk('uploads')->exists($postData['old_ticket']));
            $ticket = $request->file('ticket');
            $fileName = $request->file('ticket')->getClientOriginalName();
            $docPath = $defaultDocumentPath . $fileName;
            if ($postData['old_ticket'] && Storage::disk('uploads')->exists($postData['old_ticket'])) {
                Storage::disk('uploads')->delete($postData['old_ticket']);
            }
            Storage::disk('uploads')->put($docPath, file_get_contents($ticket));
            $postData['ticket'] = $docPath;
        }

        $postData['ticket'] = $docPath ?? $postData['old_ticket'];
        $postData['approve_by'] = Auth::user()->id ?? null;

        if ($postData['old_ticket'] && Storage::disk('uploads')->exists($postData['old_ticket'])) {
            Storage::disk('uploads')->delete($postData['old_ticket']);
            $postData['ticket'] = null;
        }

        $this->leaveRepository->update($postData, $id);
        $leave = $this->leaveRepository->getById($id);

        event(new LeaveStatusChangeEvent($leave, $studentData));
        return redirect('leave')->with('success', 'Leave updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->leaveRepository->delete($id);
        Session::flash('success', 'Leave deleted successfully');
        return response()->json([
            'status' => 'success',
            'message' => 'Leave deleted successfully',
        ]);
    }

    public function getLeaveDataById($id)
    {
        $leave = $this->leaveRepository->getById($id);
        $student = $this->studentRepository->getById($leave->leave_apply_by);

        return response()->json([
            'status' => 'success',
            'leave' => $leave,
            'student' => $student,
        ]);
    }

    public function changeLeaveStatus(Request $request)
    {
        $params = $request->all();

        $this->leaveRepository->update($params, $params['leave_id']);

        $leave = $this->leaveRepository->getById($params['leave_id']);
        $studentData = Student::find($params['student_id']);

        event(new LeaveStatusChangeEvent($leave, $studentData));

        return response()->json([
            'status' => 'success',
            'message' => 'Note send to user successfully'
        ]);
    }
}
