<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use App\Models\Setting;
use App\Models\Student;
use App\Models\StudentAdmissionMap;
use App\Models\StudentDocument;
use App\Repositories\AdmissionRepository;
use App\Repositories\BedRepository;
use App\Repositories\CommentRepository;
use App\Repositories\CountryRepository;
use App\Repositories\CourseRepository;
use App\Repositories\DocumentTypeRepository;
use App\Repositories\FeesRepository;
use App\Repositories\HostelRepository;
use App\Repositories\RoomRepository;
use App\Repositories\StudentDocumentRepository;
use App\Repositories\StudentRepository;
use App\Repositories\VillageRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdmissionController extends Controller
{
    private $bedRepository;
    private $hostelRepository;
    private $roomRepository;
    private $admissionRepository;
    private $studentRepository;
    private $studentDocumentRepository;
    private $countryRepository;
    private $villageRepository;
    private $courseRepository;
    private $feesRepository;
    private $documentTypeRepository;
    private $commentRepository;

    public function __construct(
        BedRepository $bedRepository,
        HostelRepository $hostelRepository,
        RoomRepository $roomRepository,
        AdmissionRepository $admissionRepository,
        StudentRepository $studentRepository,
        StudentDocumentRepository $studentDocumentRepository,
        CountryRepository $countryRepository,
        VillageRepository $villageRepository,
        CourseRepository $courseRepository,
        FeesRepository $feesRepository,
        DocumentTypeRepository $documentTypeRepository,
        CommentRepository $commentRepository
    ) {
        $this->bedRepository = $bedRepository;
        $this->hostelRepository = $hostelRepository;
        $this->roomRepository = $roomRepository;
        $this->admissionRepository = $admissionRepository;
        $this->studentRepository = $studentRepository;
        $this->studentDocumentRepository = $studentDocumentRepository;
        $this->countryRepository = $countryRepository;
        $this->villageRepository = $villageRepository;
        $this->courseRepository = $courseRepository;
        $this->feesRepository = $feesRepository;
        $this->documentTypeRepository = $documentTypeRepository;
        $this->commentRepository = $commentRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $admissions = $this->admissionRepository->getAdmissionByStudentId();
        $admissions->map(function ($admission) {
            $admission->room_allocation = $this->admissionRepository->isRoomAllocation($admission->student_id, $admission->id);
            // dd($admission);
            $admission->documents = $this->admissionRepository->getStudentDocumentsByAdmissionId($admission->id);
            $admission->documents->map(function ($document) {
                $document->file_name = basename($document->doc_url);
            });
        });

        $oldAdmissionDate = Setting::where('key', 'old_admission_date')->value('value');
        $newAdmissionDate = Setting::where('key', 'new_admission_date')->value('value');
        $studentId = Student::whereUserId(Auth::user()->id)->value('id');
        $oldLabel = Setting::where('key', 'old_admission_label')->value('value');
        $newLabel = Setting::where('key', 'new_admission_label')->value('value');

        if (count($admissions) > 0) {
            $admissionDate = $admissions[0]->is_admission_new == 1 ? $newAdmissionDate : $oldAdmissionDate;
            $admissionLabel = $admissions[0]->is_admission_new == 1 ? $newLabel : $oldLabel;
        } else {
            $admissionDate = $newAdmissionDate ? $newAdmissionDate : null;
            $admissionLabel = $newLabel ? $newLabel : "";
        }

        $startYear = date('Y') . '-05-01';
        $endYear = date('Y', strtotime('+1 Year')) . '-04-30';
        $isStudentAdmissionExist = StudentAdmissionMap::where('student_id', $studentId)->whereBetween('created_at', [$startYear, $endYear])->exists();

        $yearList = array_merge($this->lastFiveYears(), $this->nextFiveYears());
        sort($yearList);
        $hostels = $this->hostelRepository->getAll();
        $students = $this->studentRepository->getStudentNotAllotBed();
        $countries = $this->countryRepository->getAll();
        $courses = $this->courseRepository->getAll();
        $villages = $this->villageRepository->getAll();
        return view('frontend.admission.index', compact('admissions', 'hostels', 'students', 'admissionDate', 'admissionLabel', 'isStudentAdmissionExist', 'yearList', 'countries', 'courses', 'villages'));
    }

    private function nextFiveYears()
    {
        for ($i = 1; $i <= 3; $i++) {
            $nextFiveYears[] = date("Y", strtotime(" +$i year")) . '-' . (date("Y") + $i + 1);
        }
        return $nextFiveYears;
    }

    private function lastFiveYears()
    {
        for ($i = 1; $i <= 3; $i++) {
            $lastFiveYears[] = date("Y", strtotime(" -$i year")) . '-' . (date("Y") - $i + 1);
        }
        array_push($lastFiveYears, (date("Y") . '-' . date("Y", strtotime(" +1 year"))));
        return $lastFiveYears;
    }

    public function admissionData(Request $request)
    {
        $postData = $request->all();
        $admissions = $this->admissionRepository->getAll($postData);
        $admissions->map(function ($admission) {
            $admission->is_room_allocate = $this->admissionRepository->isRoomAllocation($admission->student_id);
            $admission->fees_status = $this->feesRepository->getByAdmissionId($admission->id) ? 'Paid' : 'Unpaid';
            return $admission;
        });
        /* dd($admissions); */
        return response()->json($admissions);
    }

    private function nextTenYears()
    {
        for ($i = 1; $i <= 10; $i++) {
            $nextFiveYears[] = date("Y", strtotime(" +$i year")) . '-' . (date("Y") + $i + 1);
            // $nextFiveYears[] = date("Y", strtotime(" +$i year"));
        }

        array_unshift($nextFiveYears, (date("Y") . '-' . date("Y", strtotime(" +1 year"))));
        //array_push($nextFiveYears,date("Y"));
        return $nextFiveYears;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $studentId = Student::whereUserId(Auth::user()->id)->value('id');
        $admission = $this->admissionRepository->getAllAdmissionByStudentId($studentId);

        $studentAdmissionCheck = $this->admissionRepository->checkStudent($studentId);

        $studentData = $this->studentRepository->getById($studentId);

        $documents = [];
        $oldAdmissionDetails = '';
        $oldAdmissionDocuments = '';
        if ($studentAdmissionCheck) {
            $studentDetail = StudentAdmissionMap::where('student_id', $studentId)->latest()->first();
            $oldAdmissionDetails = Admission::find($studentDetail->admission_id);
            $oldAdmissionDocuments = StudentDocument::where('student_id', $studentId)->get();

            $oldAdmissionDocuments->each(function ($doc) use (&$documents) {
                $documents[$doc->doc_type] = $doc->doc_url;
            });
            // dd($documents);
        }

        $admissionDetail = $this->admissionRepository->currrentAdmissionYear();
        $countries = $this->countryRepository->getAll();
        $villages = $this->villageRepository->getAll();
        $courses = $this->courseRepository->getAll();
        $addmission_years = $this->nextTenYears();
        $docTypes = $this->documentTypeRepository->getAll();
        $semesters = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10"];


        return view('frontend.admission.create', compact('admission', 'studentAdmissionCheck', 'studentData', 'oldAdmissionDetails', 'oldAdmissionDocuments', 'documents', 'admissionDetail', 'countries', 'villages', 'courses', 'addmission_years', 'docTypes', 'semesters'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        set_time_limit(300); // 5 minutes

        $params = $request->all();
        // dd($courseId);
        $studentId = Student::where('user_id', Auth::user()->id)->value('id');
        $student = Student::where('user_id', Auth::user()->id)->first();
        $defaultDocumentPath = "Uploads/Admission/" . date("Y") . "/" . $studentId . "/";

        // Process basic admission data
        $params['dob'] = \DateTime::createFromFormat('d/m/Y', $params['dob'])->format('Y-m-d');
        $params['addmission_date'] = \DateTime::createFromFormat('d/m/Y', $params['addmission_date'])->format('Y-m-d');
        $params['arriving_date'] = \DateTime::createFromFormat('d/m/Y', $params['arriving_date'])->format('Y-m-d');
        $params['is_indian_citizen'] = ($request->is_indian_citizen && $params['is_indian_citizen'] == 'true') ? true : false;
        $params['is_any_illness'] = ($request->is_any_illness && $params['is_any_illness'] == 'true') ? true : false;
        $params['is_used_vehicle'] = ($request->is_used_vehicle && $params['is_used_vehicle'] == 'true') ? true : false;
        $params['is_have_helmet'] = ($request->is_have_helmet && $params['is_have_helmet'] == 'true') ? true : false;
        $params['is_parent_indian_citizen'] = ($request->is_parent_indian_citizen && $params['is_parent_indian_citizen'] == 'true') ? true : false;
        $params['college_fees_receipt_date'] = $request->college_fees_receipt_date ? \DateTime::createFromFormat('d/m/Y', $params['college_fees_receipt_date'])->format('Y-m-d') : null;
        $params['chk_declaration'] = ($request->chk_declaration && $params['chk_declaration'] == 'on') ? true : false;
        $params['is_admission_new'] = $params['student'] ?? false;

        // Define document fields
        $documentFields = [
            'passport_photoimage' => ['param' => 'student_photo_url', 'folder' => 'student_photo', 'prefix' => 'student_photo_', 'middle_prefix' => 'Student'],
            'licenseimage' => ['param' => 'licence_doc_url', 'folder' => 'licence_photo', 'prefix' => 'licence_photo_', 'middle_prefix' => 'Licence'],
            'insuranceimage' => ['param' => 'insurance_doc_url', 'folder' => 'insurance_photo', 'prefix' => 'insurance_photo_', 'middle_prefix' => 'Insurance'],
            'rc_frontimage' => ['param' => 'rcbook_front_doc_url', 'folder' => 'rcbook_front_photo', 'prefix' => 'rcbook_front_photo_', 'middle_prefix' => 'RcbookFrontDoc'],
            'rc_backimage' => ['param' => 'rcbook_back_doc_url', 'folder' => 'rcbook_back_photo', 'prefix' => 'rcbook_back_photo_', 'middle_prefix' => 'RcbookBackDoc'],
            'father_photoimage' => ['param' => 'father_photo_url', 'folder' => 'father_photo', 'prefix' => 'father_photo_', 'middle_prefix' => 'Mother'],
            'mother_photoimage' => ['param' => 'mother_photo_url', 'folder' => 'mother_photo', 'prefix' => 'mother_photo_', 'middle_prefix' => 'Father'],
        ];

        // Process profile images and set URLs in params
        foreach ($documentFields as $requestField => $config) {
<<<<<<< Updated upstream
            $hdnField = 'hdn' . $config['middle_prefix'] . 'Photourl';
            $params[$config['param']] = isset($params[$hdnField]) ? $params[$hdnField] : null;
        }
=======
            // dd($config['param']);
            if (!empty($request->$requestField)) {
                $base64Image = $request->$requestField;
                $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Image));
                $fileName = $config['prefix'] . time() . '.jpg';
                $filePath = $defaultDocumentPath . $config['folder'] . '/';

                if (!Storage::disk('uploads')->exists($filePath)) {
                    Storage::disk('uploads')->makeDirectory($filePath, 0755, true);
                }

                $fullPath = $filePath . $fileName;
                Storage::disk('uploads')->put($fullPath, $imageData);
                $params[$config['param']] = $fullPath;
            } else {
                // dd($params['hdn'.$config['middle_prefix'].'Photourl']);
                $params[$config['param']] = isset($params['hdn' . $config['middle_prefix'] . 'Photourl']) ? $params['hdn' . $config['middle_prefix'] . 'Photourl'] : null;
            }
        }

        /* foreach ($documentFields as $config) {
            unset($params[$config['param']]);
        } */
>>>>>>> Stashed changes

        // Create admission record first without processing files
        $params['is_local_guardian_in_ahmedabad'] = ($request->is_local_guardian_in_ahmedabad && $params['is_local_guardian_in_ahmedabad'] == 'true') ? true : false;
        $params["is_fees_paid"] = 0;
        $params["is_admission_confirm"] = 0;
        $year = explode('-', $params['year_of_addmission']);
        $params['year_of_addmission'] = $year[0];
        $params['created_by'] = Auth::user()->id;

        try {
            $admission = $this->admissionRepository->create($params);

            $student->update([
                'dob' => $admission['dob'],
                'address' => $admission['residence_address'],
                'country_id' => $admission['country'],
            ]);

            $admission_data["admission_id"] = $admission->id;
            $admission_data["student_id"] = $studentId;
            $admission_data["admission_year"] = date("Y");
            $this->admissionRepository->studentAdmissionMapInsert($admission_data);

            if ($params['note'] != null) {
                $data['student_id'] = $studentId;
                $data['admission_id'] = $admission->id;
                $data['student_comment'] = $params['note'];
                $data['comment_type'] = 'admission_create';
                $data['commented_by'] = Auth::user()->id;
                $this->commentRepository->create($data);
            }

            session()->flash('message', 'Admission Added Successfully! Document processing is in progress.');
            session()->flash('status', 'success');
            if($params['old_course_id'] <= 0){
                $courseId =  $params['course_id'];
            } else{
                $courseId = $params['course_id'] != $params['old_course_id'] ? $params['old_course_id'] : $params['course_id'];
            }
            $newcourseId = $params['course_id'];

            $this->processAdmissionFiles($request, $studentId, $admission->id, $courseId, $defaultDocumentPath, $documentFields ,$newcourseId);

            return redirect()->route('student.dashboard');
        } catch (\Exception $e) {
            // If there was an error creating the admission, don't process files
            \Log::error("Error creating admission: " . $e->getMessage());
            session()->flash('message', 'Error creating admission: ' . $e->getMessage());
            session()->flash('status', 'error');
            return back()->withInput();
        }
    }

    /**
     * Process admission files in the background
     */
    private function processAdmissionFiles($request, $studentId, $admissionId, $courseId, $defaultDocumentPath, $documentFields, $newcourseId)
    {
        foreach ($documentFields as $requestField => $config) {
            if (!empty($request->$requestField)) {
                try {
                    $base64Image = $request->$requestField;
                    $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Image));
                    $fileName = $config['prefix'] . time() . '.jpg';
                    $filePath = $defaultDocumentPath . $config['folder'] . '/';

                    if (!Storage::disk('uploads')->exists($filePath)) {
                        Storage::disk('uploads')->makeDirectory($filePath, 0755, true);
                    }

                    $fullPath = $filePath . $fileName;
                    Storage::disk('uploads')->put($fullPath, $imageData);

                    Admission::where('id', $admissionId)
                        ->update([$config['param'] => $fullPath]);
                } catch (\Exception $e) {
                    \Log::error("Error processing {$requestField}: " . $e->getMessage());
                }
            }
        }

        $fields = [
            'hsc_resultimage' => 'HSC',
            'ssc_resultimage' => 'SSC',
            'last_qualificationimage' => 'Qualification Result',
            'leaving_certificateimage' => 'Leaving',
            'degree_certificateimage' => 'Degree Certificate',
            'parents_passport_frontimage' => 'Parent Passport Front',
            'parents_passport_backimage' => 'Parent Passport Back',
            'parents_aadhar_frontimage' => 'Parent Aadhar Card Front',
            'parents_aadhar_backimage' => 'Parent Aadhar Card Back',
            'aadhar_frontimage' => 'Aadhar Card Front',
            'aadhar_backimage' => 'Aadhar Card Back',
            'passport_frontimage' => 'Passport Front',
            'passport_backimage' => 'Passport Back',
            'ipcc_resultimage' => 'IPCC',
            'cpt_resultimage' => 'CPT',
            'ca_final_resultimage' => 'CA Final',
        ];



        foreach ($fields as $fieldName => $docType) {
            if (!empty($request->$fieldName)) {
                try {
                    $base64Image = $request->$fieldName;
                    $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Image));
                    $fileName = $docType . '_' . $courseId . '.jpg';
                    $filePath = $defaultDocumentPath . $docType . '/';

                    if (!Storage::disk('uploads')->exists($filePath)) {
                        Storage::disk('uploads')->makeDirectory($filePath, 0755, true);
                    }

                    $fullPath = $filePath . $fileName;
                    Storage::disk('uploads')->put($fullPath, $imageData);

                    $existingDoc = StudentDocument::where('student_id', $studentId)
                        ->where('doc_type', $docType)
                        ->first();

                    if ($existingDoc) {
                        $existingDoc->doc_url = $fullPath;
                        $existingDoc->save();
                    } else {
                        StudentDocument::create([
                            'doc_url' => $fullPath,
                            'student_id' => $studentId,
                            'course_id' => $courseId,
                            'doc_type' => $docType
                        ]);
                    }
                } catch (\Exception $e) {
                    \Log::error("Error processing {$fieldName}: " . $e->getMessage());
                }
            }
        }

        if (!empty($request->fee_receiptimage)) {
            try {
                $base64Image = $request->fee_receiptimage;
                $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Image));
                $semester = $request->semester ?? '';
                $fileName = 'Sem_' . $semester . '_Fees_Receipt_' . time() . '.jpg';
                $filePath = $defaultDocumentPath . 'Sem_' . $semester . '_Fees_Receipt/';

                if (!Storage::disk('uploads')->exists($filePath)) {
                    Storage::disk('uploads')->makeDirectory($filePath, 0755, true);
                }

                $fullPath = $filePath . $fileName;
                Storage::disk('uploads')->put($fullPath, $imageData);

                $documentData = [
                    'doc_url' => $fullPath,
                    'student_id' => $studentId,
                    'course_id' => $newcourseId,
                    'doc_type' => 'Semester ' . $semester . ' Fees Receipt'
                ];

                $existingDoc = StudentDocument::where('student_id', $documentData['student_id'])
                    ->where('doc_type', $documentData['doc_type'])
                    ->where('course_id', $newcourseId)
                    ->first();

                if ($existingDoc) {
                    $existingDoc->doc_url = $fullPath;
                    $existingDoc->save();
                } else {
                    StudentDocument::create($documentData);
                }
            } catch (\Exception $e) {
                \Log::error("Error processing fee receipt: " . $e->getMessage());
            }
        }
<<<<<<< Updated upstream

=======

        /* foreach ($fields as $key => $label) {
            unset($fields[$key]);
        } */

        if (!empty($request->fee_receiptimage)) {
            $base64Image = $request->fee_receiptimage;
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Image));
            $semester = $request->semester ?? '';
            $fileName = 'Sem_' . $semester . '_Fees_Receipt_' . time() . '.jpg';
            $filePath = $defaultDocumentPath . 'Sem_' . $semester . '_Fees_Receipt/';
            if (!Storage::disk('uploads')->exists($filePath)) {
                Storage::disk('uploads')->makeDirectory($filePath, 0755, true);
            }
            $fullPath = $filePath . $fileName;
            Storage::disk('uploads')->put($fullPath, $imageData);

            $documentData = [
                'doc_url' => $fullPath,
                'student_id' => $studentId,
                'course_id' => $params['course_id'],
                'doc_type' => 'Semester ' . $semester . ' Fees Receipt'
            ];

            $existingDoc = StudentDocument::where('student_id', $documentData['student_id'])
                ->where('doc_type', $documentData['doc_type'])
                ->first();
            if ($existingDoc) {
                $existingDoc->doc_url = $fullPath;
                $existingDoc->save();
            } else {
                StudentDocument::create($documentData);
            }
        }

>>>>>>> Stashed changes
        $semesters = range(1, 8);

        foreach ($semesters as $sem) {
            $key = "sem{$sem}_resultimage";
            if (!empty($request->$key)) {
                try {
                    $base64Image = $request->$key;
                    $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Image));
                    $fileName = "Sem{$sem}_Result_photo_" . time() . '.jpg';
                    $filePath = $defaultDocumentPath . "Sem{$sem}_Result_photo/";

                    if (!Storage::disk('uploads')->exists($filePath)) {
                        Storage::disk('uploads')->makeDirectory($filePath, 0755, true);
                    }

                    $fullPath = $filePath . $fileName;
                    Storage::disk('uploads')->put($fullPath, $imageData);

                    $existingDoc = StudentDocument::where('student_id', $studentId)
                        ->where('doc_type', "Semester $sem")
                        ->where('course_id', $courseId)
                        ->first();

                    if ($existingDoc) {
                        $existingDoc->doc_url = $fullPath;
                        $existingDoc->save();
                    } else {
                        StudentDocument::create([
                            'doc_url' => $fullPath,
                            'student_id' => $studentId,
                            'course_id' => $courseId,
                            'doc_type' => "Semester $sem"
                        ]);
                    }
                } catch (\Exception $e) {
                    \Log::error("Error processing semester $sem result: " . $e->getMessage());
                }
            }
        }

        foreach ($semesters as $sem) {
            $key = "sem{$sem}_backlog_resultimage";
            if (!empty($request->$key)) {
                try {
                    $base64Image = $request->$key;
                    $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Image));
                    $fileName = "Sem{$sem}_backlog_Result_photo_" . time() . '.jpg';
                    $filePath = $defaultDocumentPath . "Sem{$sem}_backlog_Result_photo/";

                    if (!Storage::disk('uploads')->exists($filePath)) {
                        Storage::disk('uploads')->makeDirectory($filePath, 0755, true);
                    }

                    $fullPath = $filePath . $fileName;
                    Storage::disk('uploads')->put($fullPath, $imageData);

                    $existingDoc = StudentDocument::where('student_id', $studentId)
                        ->where('doc_type', "Semester $sem (Backlog)")
                        ->where('course_id', $courseId)
                        ->first();

                    if ($existingDoc) {
                        $existingDoc->doc_url = $fullPath;
                        $existingDoc->save();
                    } else {
                        StudentDocument::create([
                            'doc_url' => $fullPath,
                            'student_id' => $studentId,
                            'course_id' => $courseId,
                            'doc_type' => "Semester $sem (Backlog)"
                        ]);
                    }
                } catch (\Exception $e) {
                    \Log::error("Error processing semester $sem backlog: " . $e->getMessage());
                }
            }
        }
    }

    private function caculateAge($dob)
    {
        $getyear = explode("-", $dob);
        return date('Y') - $getyear[0];
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $admission = $this->admissionRepository->getById($id);
        // dd($admission);
        $admission->student_file_name = ($admission->student_photo_url != "") ? basename($admission->student_photo_url) : "";
        $admission->parent_file_name = ($admission->parent_photo_url != "") ? basename($admission->parent_photo_url) : "";

        $admission->age = $this->caculateAge($admission->admission->dob);
        $reservation = $this->admissionRepository->getReservationByAdmissionId( $id);

        $documents = $this->admissionRepository->getStudentDocumentsByAdmissionId($id);
        $documents->map(function ($document) {
            $document->file_name = basename($document->doc_url);
        });

        $courses = $this->courseRepository->getAll();
        $villages = $this->villageRepository->getAll();
        $docTypes = $this->admissionRepository->getDocumentTypes();
        $countries = $this->countryRepository->getAll();
        $comments = $this->admissionRepository->getCommentsByAdmissionId($id);

        return view('frontend.admission.show', compact('admission', 'documents', 'reservation', 'courses', 'villages', 'docTypes', 'countries', 'comments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $admissionDetail = $this->admissionRepository->getById($id);

        // Get studentId from admissionDetail, handle null
        $studentId = $admissionDetail ? $admissionDetail->student_id : null;

        // Fetch student data
        $studentData = $studentId ? $this->studentRepository->getById($studentId) : null;

        // Check if the student has previous admissions
        $studentAdmissionCheck = $studentId ? $this->admissionRepository->checkStudent($studentId) : false;

        // Initialize as empty
        $oldAdmissionDetails = '';
        $oldAdmissionDocuments = '';

        if ($studentAdmissionCheck) {
            $studentDetail = StudentAdmissionMap::where('student_id', $studentId)->latest()->first();
            $oldAdmissionDetails = $studentDetail ? Admission::find($studentDetail->admission_id) : '';
            $oldAdmissionDocuments = StudentDocument::where('student_id', $studentId)->get();
        }

        $countries = $this->countryRepository->getAll();
        $villages = $this->villageRepository->getAll();
        $students = $this->studentRepository->getAll();
        $courses = $this->courseRepository->getAll();
        $addmission_years = $this->nextTenYears();
        $docTypes = $this->documentTypeRepository->getAll();
        $comment = $this->admissionRepository->getLatestComment($id, Auth::user()->id);
        $studentComments = $this->admissionRepository->getCommentsByAdmissionStudentId($id, Auth::user()->id);
        $semesters = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10"];

        return view('frontend.admission.update', compact(
            'admissionDetail',
            'studentAdmissionCheck',
            'semesters',
            'students',
            'studentData',
            'oldAdmissionDetails',
            'oldAdmissionDocuments',
            'countries',
            'villages',
            'courses',
            'addmission_years',
            'docTypes',
            'comment',
            'studentComments'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        set_time_limit(300); // 5 minutes

        $payLoad = $request->all();
        $admission = Admission::findOrFail($id);
        $student_id = StudentAdmissionMap::where('admission_id', $id)->value('student_id');
        $defaultDocumentPath = "Uploads/Admission/" . date("Y") . "/" . $student_id . "/";

        $payLoad['dob'] = \DateTime::createFromFormat('d/m/Y', $payLoad['dob'])->format('Y-m-d');
        $payLoad['addmission_date'] = \DateTime::createFromFormat('d/m/Y', $payLoad['addmission_date'])->format('Y-m-d');
        $payLoad['arriving_date'] = \DateTime::createFromFormat('d/m/Y', $payLoad['arriving_date'])->format('Y-m-d');
        $payLoad['is_indian_citizen'] = ($request->is_indian_citizen && $payLoad['is_indian_citizen'] == 'true') ? true : false;
        $payLoad['is_any_illness'] = ($request->is_any_illness && $payLoad['is_any_illness'] == 'true') ? true : false;
        $payLoad['is_used_vehicle'] = ($request->is_used_vehicle && $payLoad['is_used_vehicle'] == 'true') ? true : false;
        $payLoad['is_have_helmet'] = ($request->is_have_helmet && $payLoad['is_have_helmet'] == 'true') ? true : false;
        $payLoad['is_parent_indian_citizen'] = ($request->is_parent_indian_citizen && $payLoad['is_parent_indian_citizen'] == 'true') ? true : false;
        $payLoad['college_fees_receipt_date'] = $request->college_fees_receipt_date ? \DateTime::createFromFormat('d/m/Y', $payLoad['college_fees_receipt_date'])->format('Y-m-d') : null;
        $payLoad['chk_declaration'] = ($request->chk_declaration && $payLoad['chk_declaration'] == 'on') ? true : false;
        $payLoad['is_admission_new'] = $payLoad['student'] ?? false;
        $payLoad['is_local_guardian_in_ahmedabad'] = ($request->is_local_guardian_in_ahmedabad && $payLoad['is_local_guardian_in_ahmedabad'] == 'true') ? true : false;

        $documentFields = [
            'passport_photoimage' => ['param' => 'student_photo_url', 'folder' => 'student_photo', 'prefix' => 'student_photo_', 'middle_prefix' => 'Student'],
            'licenseimage' => ['param' => 'licence_doc_url', 'folder' => 'licence_photo', 'prefix' => 'licence_photo_', 'middle_prefix' => 'Licence'],
            'insuranceimage' => ['param' => 'insurance_doc_url', 'folder' => 'insurance_photo', 'prefix' => 'insurance_photo_', 'middle_prefix' => 'Insurance'],
            'rc_frontimage' => ['param' => 'rcbook_front_doc_url', 'folder' => 'rcbook_front_photo', 'prefix' => 'rcbook_front_photo_', 'middle_prefix' => 'RcbookFrontDoc'],
            'rc_backimage' => ['param' => 'rcbook_back_doc_url', 'folder' => 'rcbook_back_photo', 'prefix' => 'rcbook_back_photo_', 'middle_prefix' => 'RcbookBackDoc'],
            'father_photoimage' => ['param' => 'father_photo_url', 'folder' => 'father_photo', 'prefix' => 'father_photo_', 'middle_prefix' => 'Mother'],
            'mother_photoimage' => ['param' => 'mother_photo_url', 'folder' => 'mother_photo', 'prefix' => 'mother_photo_', 'middle_prefix' => 'Father'],
        ];

        foreach ($documentFields as $requestField => $config) {
            $hdnField = 'hdn' . $config['middle_prefix'] . 'Photourl';
            $payLoad[$config['param']] = isset($payLoad[$hdnField]) ? $payLoad[$hdnField] : null;
        }

        unset(
            $payLoad['licenseimage'],
            $payLoad['rc_frontimage'],
            $payLoad['rc_backimage'],
            $payLoad['insuranceimage'],
            $payLoad['passport_photoimage'],
            $payLoad['hsc_resultimage'],
            $payLoad['ssc_resultimage'],
            $payLoad['last_qualificationimage'],
            $payLoad['leaving_certificateimage'],
            $payLoad['degree_certificateimage'],
            $payLoad['parents_passport_frontimage'],
            $payLoad['parents_passport_backimage'],
            $payLoad['parents_aadhar_frontimage'],
            $payLoad['parents_aadhar_backimage'],
            $payLoad['aadhar_frontimage'],
            $payLoad['aadhar_backimage'],
            $payLoad['passport_frontimage'],
            $payLoad['passport_backimage'],
            $payLoad['fee_receiptimage'],
            $payLoad['ipcc_resultimage'],
            $payLoad['cpt_resultimage'],
            $payLoad['ca_final_resultimage'],
            $payLoad['father_photoimage'],
            $payLoad['mother_photoimage']
        );

        // for ($i = 1; $i <= 8; $i++) {
        //     unset($payLoad["sem{$i}_resultimage"], $payLoad["sem{$i}_backlog_resultimage"]);
        // }

        try {
            $admission->update($payLoad);
            if($payLoad['old_course_id'] <= 0){
                $courseId =  $payLoad['course_id'];
            } else{
                $courseId = $payLoad['course_id'] != $payLoad['old_course_id'] ? $payLoad['old_course_id'] : $payLoad['course_id'];
            }
            $newcourseId = $payLoad['course_id'];
            $this->processAdmissionFiles($request, $student_id, $id, $courseId, $defaultDocumentPath, $documentFields, $newcourseId);

            return redirect()->route('student.dashboard')->with([
                'message' => 'Admission Updated Successfully!',
                'status' => 'success'
            ]);
        } catch (\Exception $e) {
            \Log::error("Error updating admission: " . $e->getMessage());
            return redirect()->back()->with([
                'message' => 'Error updating admission: ' . $e->getMessage(),
                'status' => 'error'
            ])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getAdmissionDataById($id)
    {
        $studentAdmissionMap = $this->admissionRepository->getById($id);

        return response()->json([
            'status' => 'success',
            'studentAdmissionMap' => $studentAdmissionMap
        ]);
    }

    public function getStudentAdmissionData($course_id)
    {
        $studentId = Student::where('user_id', Auth::user()->id)->value('id');
        $course = $this->courseRepository->getById($course_id);
        $admissions = $this->admissionRepository->getAllAdmissionByStudentId($studentId);
        return response()->json([
            'course' => $course,
            'admissions' => $admissions,
        ]);
    }


    public function documentTypes()
    {
        return $this->admissionRepository->getDocumentTypes();
    }

    public function download($id)
    {
        $document = StudentDocument::where('id', $id)->firstOrFail();
        $pathToFile = public_path($document->doc_url);
        if (file_exists($pathToFile)) {
            return response()->download($pathToFile);
        } else {
            return redirect()->back()->with('message', 'File is not exists.');
        }
    }

    public function downloadImage(Request $request, $id)
    {
        $admission = $this->admissionRepository->getById($id);
        $pathToFile = public_path($admission[$request->fieldName]);
        if (file_exists($pathToFile)) {
            return response()->download($pathToFile);
        } else {
            return redirect()->back()->with('message', 'File is not exists.');
        }
    }
}
