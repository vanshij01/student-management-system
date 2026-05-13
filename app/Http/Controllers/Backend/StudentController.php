<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Repositories\AdmissionRepository;
use App\Repositories\CountryRepository;
use App\Repositories\FeesRepository;
use App\Repositories\StudentRepository;
use App\Repositories\UserRepository;
use App\Repositories\VillageRepository;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class StudentController extends Controller
{
    private $studentRepository;
    protected $userRepository;
    private $countryRepository;
    private $villageRepository;
    private $feesRepository;
    private $admissionRepository;

    public function __construct(
        StudentRepository $studentRepository,
        CountryRepository $countryRepository,
        UserRepository $userRepository,
        VillageRepository $villageRepository,
        FeesRepository $feesRepository,
        AdmissionRepository $admissionRepository,
    ) {
        $this->studentRepository = $studentRepository;
        $this->userRepository = $userRepository;
        $this->countryRepository = $countryRepository;
        $this->villageRepository = $villageRepository;
        $this->feesRepository = $feesRepository;
        $this->admissionRepository = $admissionRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $countries = $this->countryRepository->getAll();
        return view('backend.student.index', compact('countries'));
    }

    public function studentData(Request $request)
    {
        $genderId = $request->gender;
        $countryId = $request->country_id;
        $student = $this->studentRepository->studentData($genderId, $countryId);
        // dd($student);
        return datatables()->of($student)->filter(function ($query) use ($request) {
                if ($request->has('search') && $search = $request->input('search')['value']) {
                    $query->where(DB::raw("CONCAT_WS(' ', first_name, middle_name, last_name)"), 'like', "%{$search}%");
                }
            })->addIndexColumn()->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = $this->countryRepository->getAll();
        $villages = $this->villageRepository->getAll();
        return view('backend.student.create', compact('countries', 'villages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $params = $request->all();

            $validator = Validator::make($params, [
                'email' => 'required|email|unique:users',
                'password' => ['required', 'min:8'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors(),
                ]);
            }

            $params['role'] = 'student';
            $params['role_id'] = 4;
            $params['name'] = $params['first_name'] . ' ' . $params['middle_name'] . ' ' . $params['last_name'];

            if (isset($params['is_any_illness']) && $params['is_any_illness'] == 'on') {
                $params['is_any_illness'] = true;
            } else {
                $params['is_any_illness'] = false;
            }

            $user = $this->userRepository->create($params);

            $params['dob'] = ($request->dob) ? \DateTime::createFromFormat('d/m/Y', $params['dob'])->format('Y-m-d') : null;
            $params['user_id'] = $user->id;
            event(new Registered($user));
            $params['created_by'] = Auth::user()->id;

            $this->studentRepository->create($params);

            return response()->json([
                'status' => 'success',
                'message' => 'Student registered successfully',
            ]);
        } catch (\Throwable $e) {
            //throw $th;

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $student = $this->studentRepository->getById($id);
        $countries = $this->countryRepository->getAll();
        $villages = $this->villageRepository->getAll();
        $donations = $this->feesRepository->getAllFeesByStudentId($id);
        $admissions = $this->admissionRepository->getAllAdmissionByStudentId($id);
        $activities = ActivityLog::where('student_id', $id)->orderBy('id', 'desc')->get();
        // dd($activities);
        return view('backend.student.show', compact('student', 'countries', 'villages', 'donations', 'admissions', 'activities'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $student = $this->studentRepository->getById($id);
        $countries = $this->countryRepository->getAll();
        $villages = $this->villageRepository->getAll();
        return view('backend.student.update', compact('student', 'countries', 'villages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $payLoad = $request->all();
        $isUserEmailExists = $this->studentRepository->checkUserEmailExists($payLoad, $id);
        $isStudentEmailExists = $this->studentRepository->checkStudentEmailExists($payLoad, $id);
        if ($isUserEmailExists || $isStudentEmailExists) {
            return redirect()->back()->with('emailExists', 'The email has already been taken');
        }
        if (isset($payLoad['is_any_illness']) && $payLoad['is_any_illness'] == 'on') {
            $payLoad['is_any_illness'] = true;
        } else {
            $payLoad['is_any_illness'] = false;
            $payLoad['illness_description'] = null;
        }
        $payLoad['dob'] = ($request->dob) ? \DateTime::createFromFormat('d/m/Y', $payLoad['dob'])->format('Y-m-d') : null;
        unset($payLoad['_token'], $payLoad['_method'], $payLoad['action']);
        $this->studentRepository->update($payLoad, $id);
        return redirect('student')->with('success', 'Student detail updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->studentRepository->delete($id);
        Session::flash('success', 'Student deleted successfully');
        return response()->json([
            'status' => true,
            'message' => 'Student deleted successfully',
        ]);
    }

    public function getStudentDataById($id)
    {
        $student = $this->studentRepository->getById($id);
        return response()->json([
            'status' => 'success',
            'student' => $student
        ]);
    }
}
