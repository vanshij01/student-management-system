<?php

namespace App\Http\Controllers\Backend;

use App\Events\SendNoteEvent;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Repositories\BedRepository;
use App\Repositories\CountryRepository;
use App\Repositories\CourseRepository;
use App\Repositories\HostelRepository;
use App\Repositories\RoomRepository;
use App\Repositories\StudentRepository;
use App\Repositories\VillageRepository;
use App\Models\StudentAdmissionMap;
use App\Models\Student;
use App\Models\Admission;
use App\Models\StudentDocument;
use App\Models\TempAdmissionUpload;
use App\Repositories\AdmissionRepository;
use App\Repositories\CommentRepository;
use App\Repositories\DocumentTypeRepository;
use App\Repositories\FeesRepository;
use App\Repositories\StudentDocumentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

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
        $yearList = array_merge($this->lastFiveYears(), $this->nextFiveYears());
        sort($yearList);
        $hostels = $this->hostelRepository->getAll();
        $students = $this->studentRepository->getStudentNotAllotBed();
        $countries = $this->countryRepository->getAll();
        $courses = $this->courseRepository->getAll();
        $villages = $this->villageRepository->getAll();

        $postData = $request->all();

        $data = $this->admissionRepository->getAll($postData);

        if ($request->search) {
            $data->where(function ($w) use ($request) {
                $search = $request->get('search');
                $w->orWhere(DB::raw("CONCAT(admissions.first_name, ' ', admissions.middle_name, ' ', admissions.last_name)"), 'LIKE', "%$search%")
                    ->orWhere(DB::raw("CONCAT(admissions.first_name, ' ', admissions.last_name)"), 'LIKE', "%$search%")
                    ->orWhere(DB::raw("CONCAT(admissions.first_name, ' ', admissions.middle_name)"), 'LIKE', "%$search%")
                    ->orWhere('admissions.first_name', 'LIKE', "%$search%")
                    ->orWhere('admissions.middle_name', 'LIKE', "%$search%")
                    ->orWhere('admissions.last_name', 'LIKE', "%$search%")
                    ->orWhere('admissions.gender', 'LIKE', "%$search%")
                    ->orWhere('admissions.phone', 'LIKE', "%$search%")
                    ->orWhere('admissions.email', 'LIKE', "%$search%")
                    ->orWhere('v.name', 'LIKE', "%$search%")
                    ->orWhere('admissions.father_full_name', 'LIKE', "%$search%")
                    ->orWhere('admissions.father_phone', 'LIKE', "%$search%")
                    ->orWhere('c.course_name', 'LIKE', "%$search%")
                    ->orWhere('admissions.semester', 'LIKE', "%$search%")
                    ->orWhere('admissions.institute_name', 'LIKE', "%$search%")
                    ->orWhereRaw("DATE_FORMAT(admissions.addmission_date, '%d/%m/%Y') LIKE ?", ["%$search%"])
                    ->orWhere('admissions.status', 'LIKE', "%$search%")
                    ->orWhere('admissions.is_fees_paid', 'LIKE', "%$search%")
                    ->orWhere('admissions.is_admission_new', 'LIKE', "%$search%");
            });
        }

        $allData = $data->orderBy('id', 'DESC')->paginate($request->page_length ?? 10);

        $allData->map(function ($admission) {
            $admission->is_room_allocate = $this->admissionRepository->isRoomAllocation($admission->student_id);
            $admission->fees_status = $this->feesRepository->getByAdmissionId($admission->id) ? 'Paid' : 'Unpaid';
        });

        return view('backend.admission.index', compact('hostels', 'students', 'yearList', 'countries', 'courses', 'villages', 'allData', 'request'));
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
        dd($postData);
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

    private function nextOneYear()
    {
        $nextOneYear = date('Y') . '-' . date('Y', strtotime(' +1 year'));

        return $nextOneYear;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {

        $students = $this->studentRepository->getAll();

        $admission = collect();
        $studentAdmissionCheck = false;
        $studentData = new Student(); // empty student object
        $documents = [];
        $oldAdmissionDetails = '';
        $oldAdmissionDocuments = collect();

        if ($request->student_id) {
            $admission = $this->admissionRepository->getAllAdmissionByStudentId($request->student_id);
            $studentAdmissionCheck = $this->admissionRepository->checkStudent($request->student_id);
            $studentData = $this->studentRepository->getById($request->student_id);

            $documents = [];
            $oldAdmissionDetails = '';
            $oldAdmissionDocuments = collect();

            if ($studentAdmissionCheck) {
                $studentDetail = StudentAdmissionMap::where('student_id', $request->student_id)->latest()->first();

                if ($studentDetail) {
                    $oldAdmissionDetails = Admission::find($studentDetail->admission_id);

                    $oldAdmissionDocuments = StudentDocument::where('student_id', $request->student_id)->get();

                    if ($oldAdmissionDocuments->isNotEmpty()) {
                        $oldAdmissionDocuments->each(function ($doc) use (&$documents) {
                            $documents[$doc->doc_type] = $doc->doc_url;
                        });
                    }
                }
            }
        }
        $admissionDetail = $this->admissionRepository->currrentAdmissionYear();
        $countries = $this->countryRepository->getAll();
        $villages = $this->villageRepository->getAll();
        $courses = $this->courseRepository->getAll();
        $addmission_years = $this->nextOneYear();
        $docTypes = $this->documentTypeRepository->getAll();
        $semesters = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10"];
        // dd($oldAdmissionDetails);

        return view('backend.admission.create', compact('admission', 'studentAdmissionCheck', 'studentData', 'oldAdmissionDetails', 'oldAdmissionDocuments', 'documents', 'admissionDetail', 'countries', 'villages', 'courses', 'addmission_years', 'docTypes', 'semesters', 'students'));
    }

    public function fetchStudentAdmissionData($studentId)
    {
        $studentData = $this->studentRepository->getById($studentId);
        if (!$studentData) {
            return response()->json(['error' => 'Student not found'], 404);
        }

        $studentAdmissionCheck = $this->admissionRepository->checkStudent($studentId);
        $oldAdmissionDetails = null;
        $documents = [];
        $course_id = null;
        $course_name = null;

        if ($studentAdmissionCheck) {
            $studentDetail = StudentAdmissionMap::where('student_id', $studentId)->latest()->first();

            // Eager load course relationship
            $oldAdmissionDetails = Admission::with('course')->find($studentDetail->admission_id);

            if ($oldAdmissionDetails && $oldAdmissionDetails->course) {
                $course_id = $oldAdmissionDetails->course->id;
                $course_name = $oldAdmissionDetails->course->course_name;
            }

            $oldAdmissionDocuments = StudentDocument::where('student_id', $studentId)->get();
            foreach ($oldAdmissionDocuments as $doc) {
                $documents[$doc->doc_type] = [
                    'id' => $doc->id,
                    'url' => asset($doc->doc_url),
                ];
            }
        }

        return response()->json([
            'success' => true,
            'studentData' => $studentData,
            'oldAdmissionDetails' => $oldAdmissionDetails,
            'course_id' => $course_id,
            'course_name' => $course_name,
            'documents' => $documents,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        set_time_limit(300); // 5 minutes

        $params = $request->all();
        // dd($params);
        unset($params['passport_photoimage'], $params['father_photoimage'], $params['mother_photoimage']);
        $studentId = $request->student_id;
        $student = Student::where('id', $request->student_id)->first();
        $defaultDocumentPath = "Uploads/Admission/" . date("Y") . "/" . $studentId . "/";

        // Process basic admission data
        $params['is_admission_new'] = ($request->is_admission_new && $params['is_admission_new'] == 'true') ? true : false;
        $params['dob'] = \DateTime::createFromFormat('d/m/Y', $params['dob'])->format('Y-m-d');
        $params['is_indian_citizen'] = ($request->is_indian_citizen && $params['is_indian_citizen'] == 'true') ? true : false;
        $params['is_any_illness'] = ($request->is_any_illness && $params['is_any_illness'] == 'true') ? true : false;
        $params['is_used_vehicle'] = ($request->is_used_vehicle && $params['is_used_vehicle'] == 'true') ? true : false;
        $params['is_have_helmet'] = ($request->is_have_helmet && $params['is_have_helmet'] == 'true') ? true : false;
        $params['is_local_guardian_in_ahmedabad'] = ($request->is_local_guardian_in_ahmedabad && $params['is_local_guardian_in_ahmedabad'] == 'true') ? true : false;
        $params['is_parent_indian_citizen'] = ($request->is_parent_indian_citizen && $params['is_parent_indian_citizen'] == 'true') ? true : false;
        $params['addmission_date'] = $request->addmission_date ? \DateTime::createFromFormat('d/m/Y', $params['addmission_date'])->format('Y-m-d') : null;
        $params['arriving_date'] = \DateTime::createFromFormat('d/m/Y', $params['arriving_date'])->format('Y-m-d');
        $params['college_fees_receipt_date'] = $request->college_fees_receipt_date ? \DateTime::createFromFormat('d/m/Y', $params['college_fees_receipt_date'])->format('Y-m-d') : null;
        $params['chk_declaration'] = ($request->chk_declaration && $params['chk_declaration'] == 'on') ? true : false;
        // $params['is_admission_new'] = $params['student'] ?? false;
        $params['course_id'] = isset($request->course_id) ? $params['course_id'] : $params['old_course_id'];

        // Define document fields
        $documentFields = [
            'passport_photoimage' => ['param' => 'student_photo_url', 'folder' => 'student_photo', 'prefix' => 'student_photo_', 'middle_prefix' => 'Student', 'old_value' => 'hdnStudentPhotourl'],
            'licenseimage' => ['param' => 'licence_doc_url', 'folder' => 'licence_photo', 'prefix' => 'licence_photo_', 'middle_prefix' => 'Licence', 'old_value' => 'hdnLicencePhotourl'],
            'insuranceimage' => ['param' => 'insurance_doc_url', 'folder' => 'insurance_photo', 'prefix' => 'insurance_photo_', 'middle_prefix' => 'Insurance', 'old_value' => 'hdnInsurancePhotourl'],
            'rc_frontimage' => ['param' => 'rcbook_front_doc_url', 'folder' => 'rcbook_front_photo', 'prefix' => 'rcbook_front_photo_', 'middle_prefix' => 'RcbookFrontDoc', 'old_value' => 'hdnRcbookFrontDocPhotourl'],
            'rc_backimage' => ['param' => 'rcbook_back_doc_url', 'folder' => 'rcbook_back_photo', 'prefix' => 'rcbook_back_photo_', 'middle_prefix' => 'RcbookBackDoc', 'old_value' => 'hdnRcbookBackDocPhotourl'],
            'father_photoimage' => ['param' => 'father_photo_url', 'folder' => 'father_photo', 'prefix' => 'father_photo_', 'middle_prefix' => 'Mother', 'old_value' => 'hdnMotherPhotourl'],
            'mother_photoimage' => ['param' => 'mother_photo_url', 'folder' => 'mother_photo', 'prefix' => 'mother_photo_', 'middle_prefix' => 'Father', 'old_value' => 'hdnFatherPhotourl'],
        ];

        // Process profile images and set URLs in params
        /* foreach ($documentFields as $requestField => $config) {
            $hdnField = 'hdn' . $config['middle_prefix'] . 'Photourl';
            $params[$config['param']] = isset($params[$hdnField]) ? $params[$hdnField] : null;
        } */

        // Create admission record first without processing files
        $params["is_fees_paid"] = 0;
        $params["is_admission_confirm"] = 0;
        $year = explode('-', $params['year_of_addmission']);
        $params['year_of_addmission'] = $year[0];
        $params['created_by'] = Auth::user()->id;

        try {
            $admission = $this->admissionRepository->create($params);

            $updates = [];

            foreach ($documentFields as $requestField => $config) {
                if (Schema::hasColumn('admissions', $config['param'])) {
                    $temp = TempAdmissionUpload::where('student_id', $studentId)
                        ->where('doc_type', $config['folder'])
                        ->first();

                    if ($temp) {
                        $updates[$config['param']] = $temp->file_path;
                    } else {
                        $updates[$config['param']] = $params[$config['old_value']];
                    }
                }
            }

            if (!empty($updates)) {
                $admission->update($updates);
                TempAdmissionUpload::where('student_id', $studentId)->delete(); // optional cleanup
            }

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

            // Process other documents
            if (isset($params['otherDoc']) && is_array($params['otherDoc'])) {
                foreach ($params['otherDoc'] as $index => $docData) {
                    if (!empty($docData['type'])) {
                        $docType = $docData['type'];
                        $percentage = $docData['percentage'] ?? null;

                        // Check if there's a base64 image in the request
                        $imageKey = "otherDoc.{$index}.image";
                        if ($request->has($imageKey) && !empty($request->input($imageKey))) {
                            $base64Image = $request->input($imageKey);
                            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Image));
                            $fileName = "other_doc_" . time() . '_' . $index . '.jpg';
                            $filePath = $defaultDocumentPath . "Other_Document/{$docType}/";

                            if (!Storage::disk('uploads')->exists($filePath)) {
                                Storage::disk('uploads')->makeDirectory($filePath, 0755, true);
                            }

                            $fullPath = $filePath . $fileName;
                            Storage::disk('uploads')->put($fullPath, $imageData);

                            // Save to StudentDocument
                            $existingDoc = StudentDocument::where('student_id', $studentId)
                                ->where('doc_type', $docType)
                                ->first();

                            if ($existingDoc) {
                                $existingDoc->doc_url = $fullPath;
                                if ($percentage !== null) {
                                    $existingDoc->percentile = $percentage;
                                }
                                $existingDoc->save();
                            } else {
                                StudentDocument::create([
                                    'doc_url' => $fullPath,
                                    'student_id' => $studentId,
                                    'course_id' => $params['course_id'] ?? 0,
                                    'doc_type' => $docType,
                                    'percentile' => $percentage,
                                ]);
                            }
                        }
                    }
                }
            }

            session()->flash('message', 'Admission Added Successfully!');
            session()->flash('status', 'success');
            /* if ($params['old_course_id'] <= 0) {
                $courseId =  $params['course_id'];
            } else {
                $courseId = $params['course_id'] != $params['old_course_id'] ? $params['old_course_id'] : $params['course_id'];
            } */
            $courseId = isset($request->course_id) ? $params['course_id'] : $params['old_course_id'];

            $newcourseId = $params['course_id'];

            $semesters = range(1, 12);
            // dd($params);
            foreach ($semesters as $sem) {
                if (!empty($params["semester_{$sem}_percentage"])) {
                    try {
                        $percentageKey = "semester_{$sem}_percentage";
                        $percentage = $request->$percentageKey ?? null;

                        if ($params['is_admission_new']) {
                            $newStudent = StudentDocument::where('student_id', $studentId)
                                ->where('doc_type', "Semester $sem")
                                ->where('course_id', $courseId)
                                ->first();
                            if ($newStudent) {
                                if ($percentage !== null) {
                                    $newStudent->update([
                                        'percentile' => $percentage,
                                        'course_id' => $courseId,
                                    ]);
                                }
                            }
                        } elseif (($params['course_id'] != $params['old_course_id']) && ($params['course_id'] != 39) && ($params['course_id'] != 73)) {
                            $newStudent = StudentDocument::where('student_id', $studentId)
                                ->where('doc_type', "Semester $sem")
                                ->where('course_id', $params['old_course_id'])
                                ->first();
                            if ($newStudent) {
                                if ($percentage !== null) {
                                    $newStudent->update([
                                        'percentile' => $percentage,
                                        'course_id' => $params['old_course_id'],
                                    ]);
                                }
                            }
                        } else {
                            $existingDoc = StudentDocument::where('student_id', $studentId)
                                ->where('doc_type', "Semester $sem")
                                ->where('course_id', $courseId)
                                ->first();

                            if ($existingDoc) {
                                if ($percentage !== null) {
                                    $existingDoc->update([
                                        'percentile' => $percentage,
                                        'course_id' => $courseId,
                                    ]);
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        Log::error("Error processing semester $sem result: " . $e->getMessage());
                    }
                }
                if (!empty($params["semester_{$sem}_backlog_percentage"])) {
                    try {
                        $percentageKey = "semester_{$sem}_backlog_percentage";

                        $percentage = $request->$percentageKey ?? null;

                        if ($params['is_admission_new']) {
                            $newStudent = StudentDocument::where('student_id', $studentId)
                                ->where('doc_type', "Semester $sem Backlog")
                                ->where('course_id', $courseId)
                                ->first();
                            if ($newStudent) {
                                if ($percentage !== null) {
                                    $newStudent->update([
                                        'percentile' => $percentage,
                                        'course_id' => $courseId,
                                    ]);
                                }
                            }
                        } elseif (($params['course_id'] != $params['old_course_id']) && ($params['course_id'] != 39) && ($params['course_id'] != 73)) {
                            $newStudent = StudentDocument::where('student_id', $studentId)
                                ->where('doc_type', "Semester $sem Backlog")
                                ->where('course_id', $params['old_course_id'])
                                ->first();
                            if ($newStudent) {
                                if ($percentage !== null) {
                                    $newStudent->update([
                                        'percentile' => $percentage,
                                        'course_id' => $params['old_course_id'],
                                    ]);
                                }
                            }
                        } else {
                            $existingDoc = StudentDocument::where('student_id', $studentId)
                                ->where('doc_type', "Semester $sem Backlog")
                                ->where('course_id', $courseId)
                                ->first();

                            if ($existingDoc) {
                                if ($percentage !== null) {
                                    $existingDoc->update([
                                        'percentile' => $percentage,
                                        'course_id' => $courseId,
                                    ]);
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        Log::error("Error processing semester $sem result: " . $e->getMessage());
                    }
                }
            }

            if ($request->education_type === 'Other') {
                try {
                    $percentage = $request->otherDoc_percentage ?? null;
                    $docType = $request->otherDoc_type;

                    $existingDoc = StudentDocument::where('student_id', $studentId)
                        ->where('doc_type', $docType)
                        ->where('course_id', $courseId)
                        ->first();

                    if ($existingDoc) {
                        if ($percentage !== null) {
                            $existingDoc->percentile = $percentage;
                        }
                        $existingDoc->save();
                    }
                } catch (\Exception $e) {
                    Log::error("Error processing other document: " . $e->getMessage());
                }
            }

            $percentageFields = [
                'HSC' => 'hsc_percentage',
                'SSC' => 'ssc_percentage',
                'IPCC' => 'ipcc_percentage',
                'CPT' => 'cpt_percentage',
                'CA Final' => 'ca_final_percentage',
                'IPCC Backlog' => 'ipcc_backlog_percentage',
                'CPT Backlog' => 'cpt_backlog_percentage',
                'CA Final Backlog' => 'ca_final_backlog_percentage',
                'Degree Certificate' => 'degree_percentage',
                'Qualification Result' => 'last_qualification_percentage',
            ];

            foreach ($percentageFields as $key => $percentageField) {
                if (!empty($params[$percentageField])) {
                    try {
                        $percentageKey = $percentageField;
                        $percentage = $request->$percentageKey ?? null;

                        $existingDoc = StudentDocument::where('student_id', $studentId)
                            ->where('doc_type', $key)
                            ->where('course_id', $courseId)
                            ->first();

                        if ($existingDoc) {
                            if ($percentage !== null) {
                                $existingDoc->percentile = $percentage;
                            }
                            $existingDoc->save();
                        }
                    } catch (\Exception $e) {
                        Log::error("Error processing semester $sem result: " . $e->getMessage());
                    }
                }
            }
            //$this->processAdmissionFiles($request, $studentId, $admission->id, $courseId, $defaultDocumentPath, $documentFields, $newcourseId);

            return redirect()->route('admission.index');
        } catch (\Exception $e) {
            // dd($e->getMessage());
            // If there was an error creating the admission, don't process files
            Log::error("Error creating admission: " . $e->getMessage());
            session()->flash('message', 'Error creating admission: ' . $e->getMessage());
            session()->flash('status', 'error');
            return redirect()->back()->with([
                'message' => 'Error creating admission: ' . $e->getMessage(),
                'status' => 'error'
            ]);
            return back()->withInput();
        }
    }

    public function processAdmissionFileUpload(Request $request)
    {
        if (!empty($request->base64File)) {
            try {
                $config = json_decode($request->config);

                if (isset($config->folder) && strtolower($config->folder) === 'other document') {
                    return response()->json([
                        "status" => false,
                        "msg" => "Skipping otherDoc processing here."
                    ], 200);
                }

                $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->base64File));
                $fileName = $config->prefix . time() . '.jpg';
                $filePath = $request->defaultDocumentPath . date("Y") . "/" . $request->studentId . "/" . $config->folder . '/';

                if (!Storage::disk('uploads')->exists($filePath)) {
                    Storage::disk('uploads')->makeDirectory($filePath, 0755, true);
                }

                $fullPath = $filePath . $fileName;
                Storage::disk('uploads')->put($fullPath, $imageData);

                $isColumnExist = Schema::hasColumn('admissions', $config->param);
                if ($isColumnExist) {
                    TempAdmissionUpload::updateOrCreate(
                        [
                            'student_id' => $request->studentId,
                            'doc_type' => $config->folder,
                        ],
                        [
                            'file_path' => $fullPath,
                            'uploaded_at' => now(),
                        ]
                    );

                    /* Admission::where('id', $request->admissionId)
                        ->update([$config->param => $fullPath]); */
                } else {
                    $educationFolders = array_merge(
                        array_map(fn($n) => "Fees Receipt Semester $n", range(1, 12)),
                        array_map(fn($n) => "Semester $n", range(1, 12)),
                        array_map(fn($n) => "Semester $n Backlog", range(1, 12)),
                        [
                            'Other Document',
                            'SSC',
                            'HSC',
                            'Qualification Result',
                            'Degree Certificate',
                            'Leaving Certificate',
                            'Job Offer Letter',
                            'Internship Offer Letter',
                        ]
                    );
                    // dd($educationFolders);
                    $config->folder = $config->folder == 'Fee Receipt' ? 'Fees Receipt Semester ' . $config->semester : $config->folder;
                    if (in_array($config->folder, $educationFolders)) {
                        $existingDoc = StudentDocument::where('student_id', $request->studentId)
                            ->where('doc_type', $config->folder)
                            ->where('course_id', $request->courseId ?? 0)
                            ->first();
                    } else {
                        $existingDoc = StudentDocument::where('student_id', $request->studentId)
                            ->where('doc_type', $config->folder)
                            ->first();
                    }
                    // dd($existingDoc);

                    if ($existingDoc) {
                        $existingDoc->doc_url = $fullPath;
                        /* if ($percentage !== null) {
                            $existingDoc->percentile = $percentage;
                        } */
                        $existingDoc->save();
                    } else {
                        StudentDocument::create([
                            'doc_url' => $fullPath,
                            'student_id' => $request->studentId,
                            'course_id' => $request->courseId ?? 0,
                            'doc_type' => $config->folder,
                            // 'percentile' => $percentage,
                        ]);
                    }
                }

                return response()->json(["status" => true, "msg" => "File Uploaded successfully", "file_path" => $fullPath], 200);
            } catch (\Exception $e) {
                Log::error("Error processing {$config->folder}: " . $e->getMessage());
                return response()->json(["status" => false, "msg" => "Error uploading file"], 500);
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
        $student_id = $this->studentRepository->getStudentIdAdmissionMap($id);
        $admission->student_file_name = ($admission->student_photo_url != "") ? basename($admission->student_photo_url) : "";
        $admission->parent_file_name = ($admission->parent_photo_url != "") ? basename($admission->parent_photo_url) : "";

        $admission->age = $this->caculateAge($admission->dob);
        $reservation = $this->admissionRepository->getReservationByAdmissionId($id);

        $documents = $this->admissionRepository->getStudentDocumentsByAdmissionId($id);
        $documents->map(function ($document) {
            $document->file_name = basename($document->doc_url);
        });

        $courses = $this->courseRepository->getAll();
        $villages = $this->villageRepository->getAll();
        $docTypes = $this->admissionRepository->getDocumentTypes();
        $countries = $this->countryRepository->getAll();
        $comments = $this->admissionRepository->getCommentsByAdmissionId($id);
        $activities = ActivityLog::where('admission_id', $id)
            ->orWhere('student_id', $student_id)
            ->get();
        // dd($activities);
        return view('backend.admission.show', compact('admission', 'documents', 'reservation', 'courses', 'villages', 'docTypes', 'countries', 'comments', 'activities'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // $admissionDetail = $this->admissionRepository->getById($id);
        $admissionDetail = $this->admissionRepository->view($id);

        $loginUser = $studentDetail = StudentAdmissionMap::where('admission_id', $admissionDetail->id)->pluck('student_id');
        $userId = $loginUser;
        $studentData = $userId ? $this->studentRepository->getById($userId)->first() : null;
        $documents = $this->admissionRepository->getStudentDocumentsByAdmissionId($id);

        $documents->map(function ($document) {
            $document->file_name = basename($document->doc_url);
        });

        $countries = $this->countryRepository->getAll();
        $villages = $this->villageRepository->getAll();
        $students = $this->studentRepository->getAll();
        $courses = $this->courseRepository->getAll();
        $addmission_years = $this->nextTenYears();
        $docTypes = $this->documentTypeRepository->getAll();
        $comment = $this->admissionRepository->getLatestComment($id, $userId);
        $studentComments = $this->admissionRepository->getCommentsByAdmissionStudentId($id, $userId);
        $semesters = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10"];
        return view('backend.admission.update', compact(
            'admissionDetail',
            'semesters',
            'students',
            'studentData',
            'documents',
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
        $payLoad = $request->all();
        $admission = Admission::findOrFail($id);
        $studentId = $request->student_id;
        $student = Student::where('id', $request->student_id)->first();
        $defaultDocumentPath = "Uploads/Admission/" . date("Y") . "/" . $studentId . "/";
        $payLoad['dob'] = \DateTime::createFromFormat('d/m/Y', $payLoad['dob'])->format('Y-m-d');
        $payLoad['is_indian_citizen'] = ($request->is_indian_citizen && $payLoad['is_indian_citizen'] == 'true') ? true : false;
        $payLoad['is_any_illness'] = ($request->is_any_illness && $payLoad['is_any_illness'] == 'true') ? true : false;
        $payLoad['is_used_vehicle'] = ($request->is_used_vehicle && $payLoad['is_used_vehicle'] == 'true') ? true : false;
        $payLoad['is_have_helmet'] = ($request->is_have_helmet && $payLoad['is_have_helmet'] == 'true') ? true : false;
        $payLoad['is_local_guardian_in_ahmedabad'] = ($request->is_local_guardian_in_ahmedabad && $payLoad['is_local_guardian_in_ahmedabad'] == 'true') ? true : false;
        $payLoad['is_parent_indian_citizen'] = ($request->is_parent_indian_citizen && $payLoad['is_parent_indian_citizen'] == 'true') ? true : false;

        unset($payLoad['student_id'], $payLoad['village_id'], $payLoad['licenceimage'], $payLoad['rcbook_frontimage'], $payLoad['rcbook_backimage'], $payLoad['insuranceimage'], $payLoad['duration'], $payLoad['studentDocument']);

        $payLoad['addmission_date'] = $request->addmission_date ? \DateTime::createFromFormat('d/m/Y', $payLoad['addmission_date'])->format('Y-m-d') : null;
        $payLoad['arriving_date'] = \DateTime::createFromFormat('d/m/Y', $payLoad['arriving_date'])->format('Y-m-d');
        $payLoad['college_fees_receipt_date'] = $request->college_fees_receipt_date ? \DateTime::createFromFormat('d/m/Y', $payLoad['college_fees_receipt_date'])->format('Y-m-d') : null;
        $payLoad['chk_declaration'] = ($request->chk_declaration && $payLoad['chk_declaration'] == 'on') ? true : false;

        // Define document fields
        $documentFields = [
            'passport_photoimage' => ['param' => 'student_photo_url', 'folder' => 'student_photo', 'prefix' => 'student_photo_', 'middle_prefix' => 'Student', 'old_value' => 'hdnStudentPhotourl'],
            'licenseimage' => ['param' => 'licence_doc_url', 'folder' => 'licence_photo', 'prefix' => 'licence_photo_', 'middle_prefix' => 'Licence', 'old_value' => 'hdnLicencePhotourl'],
            'insuranceimage' => ['param' => 'insurance_doc_url', 'folder' => 'insurance_photo', 'prefix' => 'insurance_photo_', 'middle_prefix' => 'Insurance', 'old_value' => 'hdnInsurancePhotourl'],
            'rc_frontimage' => ['param' => 'rcbook_front_doc_url', 'folder' => 'rcbook_front_photo', 'prefix' => 'rcbook_front_photo_', 'middle_prefix' => 'RcbookFrontDoc', 'old_value' => 'hdnRcbookFrontDocPhotourl'],
            'rc_backimage' => ['param' => 'rcbook_back_doc_url', 'folder' => 'rcbook_back_photo', 'prefix' => 'rcbook_back_photo_', 'middle_prefix' => 'RcbookBackDoc', 'old_value' => 'hdnRcbookBackDocPhotourl'],
            'father_photoimage' => ['param' => 'father_photo_url', 'folder' => 'father_photo', 'prefix' => 'father_photo_', 'middle_prefix' => 'Mother', 'old_value' => 'hdnMotherPhotourl'],
            'mother_photoimage' => ['param' => 'mother_photo_url', 'folder' => 'mother_photo', 'prefix' => 'mother_photo_', 'middle_prefix' => 'Father', 'old_value' => 'hdnFatherPhotourl'],
        ];

        // Create admission record first without processing files
        try {
            $admission->update($payLoad);

            $updates = [];

            foreach ($documentFields as $requestField => $config) {
                if (Schema::hasColumn('admissions', $config['param'])) {
                    $temp = TempAdmissionUpload::where('student_id', $studentId)
                        ->where('doc_type', $config['folder'])
                        ->first();

                    if ($temp) {
                        $updates[$config['param']] = $temp->file_path;
                    } else {
                        $updates[$config['param']] = $payLoad[$config['old_value']];
                    }
                }
            }

            if (!empty($updates)) {
                $admission->update($updates);
                TempAdmissionUpload::where('student_id', $studentId)->delete(); // optional cleanup
            }

            $student->update([
                'dob' => $admission['dob'],
                'address' => $admission['residence_address'],
                'country_id' => $admission['country'],
            ]);

            if ($payLoad['note'] != null) {
                $data['student_id'] = $studentId;
                $data['admission_id'] = $admission->id;
                $data['student_comment'] = $payLoad['note'];
                $data['comment_type'] = 'admission_create';
                $data['commented_by'] = Auth::user()->id;
                $this->commentRepository->create($data);
            }

            // Process other documents
            if (isset($payLoad['otherDoc']) && is_array($payLoad['otherDoc'])) {
                foreach ($payLoad['otherDoc'] as $index => $docData) {
                    if (!empty($docData['type'])) {
                        $docType = $docData['type'];
                        $percentage = $docData['percentage'] ?? null;

                        // Check if there's a base64 image in the request
                        $imageKey = "otherDoc.{$index}.image";
                        if ($request->has($imageKey) && !empty($request->input($imageKey))) {
                            $base64Image = $request->input($imageKey);
                            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Image));
                            $fileName = "other_doc_" . time() . '_' . $index . '.jpg';
                            $filePath = $defaultDocumentPath . "Other_Document/{$docType}/";

                            if (!Storage::disk('uploads')->exists($filePath)) {
                                Storage::disk('uploads')->makeDirectory($filePath, 0755, true);
                            }

                            $fullPath = $filePath . $fileName;
                            Storage::disk('uploads')->put($fullPath, $imageData);

                            // Save to StudentDocument
                            $existingDoc = StudentDocument::where('student_id', $studentId)
                                ->where('doc_type', $docType)
                                ->first();

                            if ($existingDoc) {
                                $existingDoc->doc_url = $fullPath;
                                if ($percentage !== null) {
                                    $existingDoc->percentile = $percentage;
                                }
                                $existingDoc->save();
                            } else {
                                StudentDocument::create([
                                    'doc_url' => $fullPath,
                                    'student_id' => $studentId,
                                    'course_id' => $payLoad['course_id'] ?? 0,
                                    'doc_type' => $docType,
                                    'percentile' => $percentage,
                                ]);
                            }
                        }
                    }
                }
            }

            session()->flash('message', 'Admission Updated Successfully!');
            session()->flash('status', 'success');
            $courseId = $payLoad['course_id'];

            $semesters = range(1, 10);

            foreach ($semesters as $sem) {
                if (!empty($payLoad["semester_{$sem}_percentage"])) {
                    try {
                        $percentageKey = "semester_{$sem}_percentage";
                        $percentage = $request->$percentageKey ?? null;

                        if ($payLoad['is_admission_new']) {
                            $newStudent = StudentDocument::where('student_id', $studentId)
                                ->where('doc_type', "Semester $sem")
                                ->where('course_id', $courseId)
                                ->first();
                            if ($newStudent) {
                                if ($percentage !== null) {
                                    $newStudent->update([
                                        'percentile' => $percentage,
                                        'course_id' => $courseId,
                                    ]);
                                }
                            }
                        } elseif (($payLoad['course_id'] != $payLoad['old_course_id']) && ($payLoad['course_id'] != 39) && ($payLoad['course_id'] != 73)) {
                            $newStudent = StudentDocument::where('student_id', $studentId)
                                ->where('doc_type', "Semester $sem")
                                ->where('course_id', $payLoad['old_course_id'])
                                ->first();
                            if ($newStudent) {
                                if ($percentage !== null) {
                                    $newStudent->update([
                                        'percentile' => $percentage,
                                        'course_id' => $payLoad['old_course_id'],
                                    ]);
                                }
                            }
                        } else {
                            $existingDoc = StudentDocument::where('student_id', $studentId)
                                ->where('doc_type', "Semester $sem")
                                ->where('course_id', $courseId)
                                ->first();

                            if ($existingDoc) {
                                if ($percentage !== null) {
                                    $existingDoc->update([
                                        'percentile' => $percentage,
                                        'course_id' => $courseId,
                                    ]);
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        Log::error("Error processing semester $sem result: " . $e->getMessage());
                    }
                }
                if (!empty($payLoad["semester_{$sem}_backlog_percentage"])) {
                    try {
                        $percentageKey = "semester_{$sem}_backlog_percentage";

                        $percentage = $request->$percentageKey ?? null;

                        if ($payLoad['is_admission_new']) {
                            $newStudent = StudentDocument::where('student_id', $studentId)
                                ->where('doc_type', "Semester $sem Backlog")
                                ->where('course_id', $courseId)
                                ->first();
                            if ($newStudent) {
                                if ($percentage !== null) {
                                    $newStudent->update([
                                        'percentile' => $percentage,
                                        'course_id' => $courseId,
                                    ]);
                                }
                            }
                        } elseif (($payLoad['course_id'] != $payLoad['old_course_id']) && ($payLoad['course_id'] != 39) && ($payLoad['course_id'] != 73)) {
                            $newStudent = StudentDocument::where('student_id', $studentId)
                                ->where('doc_type', "Semester $sem Backlog")
                                ->where('course_id', $payLoad['old_course_id'])
                                ->first();
                            if ($newStudent) {
                                if ($percentage !== null) {
                                    $newStudent->update([
                                        'percentile' => $percentage,
                                        'course_id' => $payLoad['old_course_id'],
                                    ]);
                                }
                            }
                        } else {
                            $existingDoc = StudentDocument::where('student_id', $studentId)
                                ->where('doc_type', "Semester $sem Backlog")
                                ->where('course_id', $courseId)
                                ->first();

                            if ($existingDoc) {
                                if ($percentage !== null) {
                                    $existingDoc->update([
                                        'percentile' => $percentage,
                                        'course_id' => $courseId,
                                    ]);
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        Log::error("Error processing semester $sem result: " . $e->getMessage());
                    }
                }
            }
            $percentageFields = [
                'HSC' => 'hsc_percentage',
                'SSC' => 'ssc_percentage',
                'IPCC' => 'ipcc_percentage',
                'CPT' => 'cpt_percentage',
                'CA Final' => 'ca_final_percentage',
                'IPCC Backlog' => 'ipcc_backlog_percentage',
                'CPT Backlog' => 'cpt_backlog_percentage',
                'CA Final Backlog' => 'ca_final_backlog_percentage',
                'Degree Certificate' => 'degree_percentage',
                'Qualification Result' => 'last_qualification_percentage',
            ];

            foreach ($percentageFields as $key => $percentageField) {
                if (!empty($payLoad[$percentageField])) {
                    try {
                        $percentageKey = $percentageField;
                        $percentage = $request->$percentageKey ?? null;

                        $existingDoc = StudentDocument::where('student_id', $studentId)
                            ->where('doc_type', $key)
                            ->where('course_id', $courseId)
                            ->first();

                        if ($existingDoc) {
                            if ($percentage !== null) {
                                $existingDoc->percentile = $percentage;
                            }
                            $existingDoc->save();
                        }
                    } catch (\Exception $e) {
                        Log::error("Error processing semester $sem result: " . $e->getMessage());
                    }
                }
            }
            return redirect()->route('admission.index')->with([
                'message' => 'Admission Updated Successfully!',
                'status' => 'success'
            ]);
        } catch (\Exception $e) {
            // If there was an error creating the admission, don't process files
            Log::error("Error creating admission: " . $e->getMessage());
            session()->flash('message', 'Error updating admission: ' . $e->getMessage());
            session()->flash('status', 'error');
            return redirect()->back()->with([
                'message' => 'Error updating admission: ' . $e->getMessage(),
                'status' => 'error'
            ]);
            return back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->admissionRepository->delete($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Admission Deleted successfully',
        ]);
    }

    public function getStudentData($id)
    {
        try {
            $student = $this->studentRepository->getById($id);

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student not found'
                ], 404);
            }

            $data = [
                'personal' => [
                    'student' => $student
                ],
            ];

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching student data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getAdmissionDataById($id)
    {
        $studentAdmissionMap = $this->admissionRepository->getById($id);
        $comment = $this->admissionRepository->getLatestComment($id, Auth::user()->id);

        return response()->json([
            'status' => 'success',
            'studentAdmissionMap' => $studentAdmissionMap,
            'comment' => $comment,
        ]);
    }

    public function sendComment(Request $request)
    {
        $params = $request->all();
        $params['created_by'] = Auth::user()->id;
        $params['send_email'] = ($request->send_email && $params['send_email'] == 'on') ? true : false;

        if ($params['admin_comment'] != null) {
            $data['student_id'] = $params['student_id'];
            $data['admission_id'] = $params['admission_id'];
            $data['admin_comment'] = $params['admin_comment'];
            $data['comment_type'] = 'admission';
            $data['commented_by'] = Auth::user()->id;
            $this->commentRepository->create($data);
        }

        $id = $params['admission_id'];

        $admission = $this->admissionRepository->getById($id);
        $admin_comment = $this->admissionRepository->getLatestComment($id, Auth::user()->id);

        $activity = activity('Admission Status Change')
            ->causedBy($admin_comment)
            ->event('Email')
            ->performedOn($admission)
            ->withProperties([
                'attributes' => [
                    'id' => $admission->id,
                    'name' => $admission->full_name,
                    'email' => $admission->email,
                ]
            ])
            ->log(Auth::user()->name . ' has sent email to ' . $admission->full_name);

        ActivityLog::where('id', $activity->id)->update([
            'student_id' => $params['student_id'] ?? 0,
            'admission_id' => $params['admission_id'] ?? 0
        ]);

        if ($params['send_email']) {
            event(new SendNoteEvent($admission, $admin_comment));
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Note send to user successfully'
        ]);
    }

    public function sendStatusRemark(Request $request)
    {
        $params = $request->all();
        $params['created_by'] = Auth::user()->id;
        $params['is_admission_confirm'] = $params['admission_status'];
        unset($params['admission_status']);

        if ($params['admin_comment'] != null) {
            $data['student_id'] = $params['student_id'];
            $data['admission_id'] = $params['admission_id'];
            $data['admin_comment'] = $params['admin_comment'];
            $data['comment_type'] = 'admission_status';
            $data['commented_by'] = Auth::user()->id;
            $this->commentRepository->create($data);
        }

        $id = $params['admission_id'];
        unset($params['student_id'], $params['admission_id'], $params['created_by'], $params['admin_comment']);
        $this->admissionRepository->update($params, $id);

        $admission = $this->admissionRepository->getById($id);
        $admin_comment = $this->admissionRepository->getLatestComment($id, Auth::user()->id);
        // $AdminComment = $this->admissionRepository->getById($admin_comment->id);

        $activity = activity('Admission Status Change')
            ->causedBy($admin_comment)
            ->event('Email')
            ->performedOn($admission)
            ->withProperties([
                'attributes' => [
                    'id' => $admission->id,
                    'name' => $admission->full_name,
                    'email' => $admission->email,
                ]
            ])
            ->log(Auth::user()->name . ' has sent email to ' . $admission->full_name);

        ActivityLog::where('id', $activity->id)->update([
            'student_id' => $params['student_id'] ?? 0,
            'admission_id' => $params['admission_id'] ?? 0
        ]);

        event(new SendNoteEvent($admission, $admin_comment));

        return response()->json([
            'status' => 'success',
            'message' => 'Note send to user successfully'
        ]);
    }

    public function getCoursesByEducation(Request $request)
    {
        $courses = $this->courseRepository->getCoursesByEducation($request->education_type);
        // $courses = $this->courseRepository->getAll();
        return response()->json([
            'status' => 'success',
            'courses' => $courses
        ]);
    }

    public function getCoursesById(Request $request)
    {
        $course = $this->courseRepository->getById($request->course_id);
        return response()->json([
            'status' => 'success',
            'course' => $course
        ]);
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

    public function getStudentAdmissionData($course_id, $student_id)
    {
        // $studentId = Student::where('user_id', Auth::user()->id)->value('id');
        $course = $this->courseRepository->getById($course_id);
        $admissions = $this->admissionRepository->getAllAdmissionByStudentId($student_id);
        return response()->json([
            'course' => $course,
            'admissions' => $admissions,
        ]);
    }

    public function feesReceipt($id)
    {
        $fees = $this->feesRepository->getDataByAdmissionId($id);
        $admission = $this->admissionRepository->getById($id);

        return response()->json([
            'fees' => $fees,
            'admission' => $admission,
        ]);
    }
}
