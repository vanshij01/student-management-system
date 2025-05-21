<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repositories\HostelRepository;
use App\Repositories\StudentRepository;
use App\Models\Admission;
use App\Models\Fees;
use App\Models\StudentAdmissionMap;
use App\Repositories\AdmissionRepository;
use App\Repositories\FeesRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class FeesController extends Controller
{
    private $feesRepository;
    private $hostelRepository;
    private $studentRepository;
    private $admissionRepository;

    public function __construct(
        FeesRepository $feesRepository,
        HostelRepository $hostelRepository,
        StudentRepository $studentRepository,
        AdmissionRepository $admissionRepository
    ) {
        $this->feesRepository = $feesRepository;
        $this->hostelRepository = $hostelRepository;
        $this->studentRepository = $studentRepository;
        $this->admissionRepository = $admissionRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $year = date('Y') . '-' . date('Y', strtotime('+1 year'));

        $hostels = $this->hostelRepository->getAll();
        $students = $this->studentRepository->getConfirmStudent();
        $yearList = array_merge($this->lastFiveYears(), $this->nextFiveYears());
        $admissions = $this->admissionRepository->getAll(null, $year)->get();
        sort($yearList);
        return view('backend.fees.index', compact('hostels', 'students', 'yearList', 'admissions'));
    }

    private function nextFiveYears()
    {
        for ($i = 1; $i <= 3; $i++) {
            $nextFiveYears[] = date("Y", strtotime(" +$i year")) . '-' . (date("Y") + $i + 1);
            // $nextFiveYears[] = date("Y", strtotime(" +$i year"));
        }
        return $nextFiveYears;
    }

    private function lastFiveYears()
    {
        for ($i = 1; $i <= 3; $i++) {
            $lastFiveYears[] = date("Y", strtotime(" -$i year")) . '-' . (date("Y") - $i + 1);
            // $lastFiveYears[] = date("Y", strtotime(" -$i year"));
        }
        array_push($lastFiveYears, (date("Y") . '-' . date("Y", strtotime(" +1 year"))));
        return $lastFiveYears;
    }

    public function feesData(Request $request)
    {
        $data = $request->all();
        if (!empty($data['student_id']) || $data['from'] != null || $data['to'] != null || !empty($data['hostel_id']) || !empty($data['gender'])) {
            $fees = $this->feesRepository->feesData($data);
        } else {
            $fees = $this->feesRepository->getAll();
        }
        return DataTables::of($fees)->addIndexColumn()->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $params = $request->all();
        $params['paid_at'] = date('Y-m-d');
        $serialNumber = Fees::orderBy('id', 'desc')->limit(1)->value('serial_number');
        // dd($serialNumber);
        $admission = Admission::find($request->admission_id);

        if (($params['fees_amount'] > 20000) && ($params['payment_type'] == 'Cash')) {
            return response()->json([
                'status' => 'error',
                'message' => 'fees amount is more than 20,000',
                'data' => $params,
            ]);
        }

        if (date('d-m') == '01-04') {
            $serialNumber = 1;
        } else {
            $serialNumber = $serialNumber + 1;
        }

        $params['serial_number'] = $serialNumber;
        $params['address'] = $admission->residence_address;
        $params['status'] = 1;

        $start_year = date('Y') . '-04-01';

        if (now() > $start_year) {
            $financialYear = date('y') . '-' . date('y', strtotime('+1 year', strtotime($start_year)));
        } else {
            $financialYear = date('y', strtotime('-1 year', strtotime($start_year))) . '-' . date('y');
        }

        $params['financial_year'] = $financialYear;

        if ($request->fees_amount > 10000 && $request->payment_type == 'Cash') {
            $feesArray = [10000, ($request->fees_amount - 10000)];

            foreach ($feesArray as $key => $fees) {
                if ($key == 0) {
                    $params['student_name'] = $admission->full_name;
                } else {
                    unset($params['student_name']);
                    $params['father_name'] = $admission['father_full_name'];
                }

                $params['fees_amount'] = $fees;
                $params['created_by'] = Auth::user()->id;

                $this->feesRepository->create($params);
            }
        } else {
            $params['student_name'] = $admission->full_name;
            $params['created_by'] = Auth::user()->id;

            $this->feesRepository->create($params);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Fees added successfully.',
            'data' => $params,
        ]);
    }

    public function getFeesData($id)
    {
        if ($id) {
            $fees = Fees::where('id', $id)->first();
            return response()->json([
                'status' => 'success',
                'fees' => $fees
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Data not find.'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $fees = Fees::with('admission')->find($id);
        $admissionMap = StudentAdmissionMap::where('admission_id', $fees->admission_id)->first();
        $amountInWords = $this->getIndianCurrency($fees->fees_amount);
        $year = date('Y') . '-' . date('Y', strtotime('+1 year'));

        $hostels = $this->hostelRepository->getAll();
        $students = $this->studentRepository->getConfirmStudent();
        $yearList = array_merge($this->lastFiveYears(), $this->nextFiveYears());
        $admissions = $this->admissionRepository->getAll(null, $year)->get();
        // dd($admissions);

        sort($yearList);
        return view('backend.fees.show', compact('fees', 'admissionMap', 'amountInWords', 'hostels', 'students', 'yearList', 'admissions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $payLoad = $request->all();
        unset($payLoad['_token'], $payLoad['_method']);
        $this->feesRepository->update($payLoad);
        return response()->json([
            'message' => 'Fees updated successfully',
            'status' => 'success',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function emailPdf($id)
    {
        $fees = Fees::find($id);
        $amountInWords = $this->getIndianCurrency($fees->fees_amount);

        $pdf = Pdf::loadView('backend.fees.email-export-pdf', ['fees' => $fees, 'amountInWords' => $amountInWords])->setPaper('a4', 'portrait')->setOptions(['defaultFont' => 'sans-serif']);
        $fileName = 'fees_receipt_' . $fees->id . '.pdf';
        // return $pdf->stream();
        Storage::disk('uploads')->put($fileName, $pdf->output());

        if (!empty($fees->admission->email)) {
            $mailArr = [
                "email" => $fees->admission->email,
                "title" => "Fees Receipt",
                "body" => "Thank you",
                'fees' => $fees,
            ];

            $files = [
                public_path('uploads/fees_receipt_' . $fees->id . '.pdf'),
            ];

            Mail::send([], $mailArr, function ($message) use ($mailArr, $files) {
                $message->to($mailArr["email"], $mailArr["email"])
                    ->subject($mailArr["title"]);

                foreach ($files as $file) {
                    $message->attach($file);
                }
            });

            return redirect()->back()->with([
                'message' => 'Email sent successfully!',
                'status' => 'success'
            ]);
        } else {
            return redirect()->back()->with([
                'message' => 'Email id is not provided..!',
                'status' => 'danger'
            ]);
        }
    }

    function getIndianCurrency(float $number)
    {
        $decimal = round($number - ($no = floor($number)), 2) * 100;
        $hundred = null;
        $digits_length = strlen($no);
        $i = 0;
        $str = array();
        $words = array(
            0 => '',
            1 => 'One',
            2 => 'Two',
            3 => 'Three',
            4 => 'Four',
            5 => 'Five',
            6 => 'Six',
            7 => 'Seven',
            8 => 'Eight',
            9 => 'Nine',
            10 => 'Ten',
            11 => 'Eleven',
            12 => 'Twelve',
            13 => 'Thirteen',
            14 => 'Fourteen',
            15 => 'Fifteen',
            16 => 'Sixteen',
            17 => 'Seventeen',
            18 => 'Eighteen',
            19 => 'Nineteen',
            20 => 'Twenty',
            30 => 'Thirty',
            40 => 'Forty',
            50 => 'Fifty',
            60 => 'Sixty',
            70 => 'Seventy',
            80 => 'Eighty',
            90 => 'Ninety'
        );
        $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
        while ($i < $digits_length) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += $divider == 10 ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str[] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural . ' ' . $hundred : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural . ' ' . $hundred;
            } else $str[] = null;
        }
        $Rupees = implode('', array_reverse($str));
        $paise = ($decimal > 0) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
        return ($Rupees ? $Rupees . 'Only ' : '') . $paise;
    }
}
