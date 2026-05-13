<?php

namespace App\Http\Controllers\Backend;

use App\Events\ComplainStatusChangeEvent;
use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Repositories\StudentRepository;
use App\Models\User;
use App\Repositories\ComplainRepository;
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
        $students = $this->studentRepository->getAll();
        return view('backend.complain.index', compact('students'));
    }

    public function complainData(Request $request)
    {
        $data = $request->all();
        $complain = $this->complainRepository->complainData($data);
        $complain->map(function ($data) {
            $data->first_name = ($data->first_name == null) ? User::whereId($data->complain_by)->value('name') : $data->first_name;
            $data->last_name = ($data->last_name == null) ? "" : $data->last_name;
            return $data;
        });
        return datatables()->of($complain)->addIndexColumn()->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $students = $this->studentRepository->getAll();
        return view('backend.complain.create', compact('students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $post_data = $request->all();
        /* $post_data['complain_by'] = Auth::user()->id; */
        $defaultDocumentPath = "Complain/" . date("Y") . "/" . $post_data['complain_by'] . "/";
        if ($request->hasFile('document')) {
            $document = $request->file('document');
            $fileName = $request->file('document')->getClientOriginalName();
            $filePath = $defaultDocumentPath . $fileName;
            Storage::disk('uploads')->put($filePath, file_get_contents($document));
        } else {
            $filePath = NULL;
        }
        $post_data['document'] = $filePath;

        $student = Student::find($post_data['complain_by']);
        $studentData = User::find($student->user_id);
        $post_data['user_id'] = $studentData->id;
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
        // $post_data['created_by'] = Auth::user()->id;
        $this->complainRepository->create($post_data);
        return response()->json([
            'status' => 'success',
            'message' => 'Complain added successfully',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $complain = $this->complainRepository->getById($id);
        return view('backend.complain.show', compact('complain'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $complain = $this->complainRepository->getById($id);
        $students = $this->studentRepository->getAll();
        return view('backend.complain.update', compact('complain', 'students'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $post_data = $request->all();
        // dd($post_data);
        $defaultDocumentPath = "Uploads/Complain/" . date("Y") . "/" . $post_data['complain_by'] . "/";
        if ($request->hasFile('document')) {
            $document = $request->file('document');
            $fileName = $request->file('document')->getClientOriginalName();
            $filePath = $defaultDocumentPath . $fileName;

            if ($post_data['old_document'] && Storage::disk('uploads')->exists($post_data['old_document'])) {
                Storage::disk('uploads')->delete($post_data['old_document']);
            }
            
            Storage::disk('uploads')->put($filePath, file_get_contents($document));
        }
        $post_data['document'] = $filePath ?? $post_data['old_document'];

        // if ($post_data['status'] != 1) {

        //     $student = Student::find($post_data['complain_by']);
        //     $studentData = User::find($student->user_id);
        //     $mailArr = [
        //         "email" => $studentData->email,
        //         "title" => "Complain Status Change Mail",
        //         'studentData' => $studentData,
        //         'complain' => $post_data,
        //     ];

        //     Mail::send('mail/complain_status_change', $mailArr, function ($message) use ($mailArr) {
        //         $message->to($mailArr['email'], 'John Doe');
        //         $message->subject($mailArr['title']);
        //     });
        //     /* event(new ComplainStatusChangeEvent($studentData, $post_data)); */
        // }
        $this->complainRepository->update($post_data, $id);
        return redirect('complain')->with([
            'status' => 'success',
            'message' => 'Complain updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->complainRepository->delete($id);
        Session::flash('success', 'Complain deleted successfully');
        return response()->json([
            'status' => true,
            'message' => 'Complain deleted successfully!',
        ]);
    }

    public function getComplainDataById($id)
    {
        $complain = $this->complainRepository->getById($id);
        $student = $this->studentRepository->getById($complain->complain_by);

        return response()->json([
            'status' => 'success',
            'complain' => $complain,
            'student' => $student,
        ]);
    }

    public function changeComplainStatus(Request $request)
    {
        $params = $request->all();
        // dd($params);
        $this->complainRepository->update($params, $params['complain_id']);

        $complain = $this->complainRepository->getById($params['complain_id']);
        $studentData = Student::find($params['student_id']);

        event(new ComplainStatusChangeEvent($studentData,$complain));

        return response()->json([
            'status' => 'success',
            'message' => 'Note send to user successfully'
        ]);
    }
}
