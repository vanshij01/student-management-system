<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    private $userRepository;

    public function __construct(
        UserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $oldDate = Setting::where('key', 'old_admission_date')->value('value');
        $newDate = Setting::where('key', 'new_admission_date')->value('value');
        $oldLabel = Setting::where('key', 'old_admission_label')->value('value');
        $newLabel = Setting::where('key', 'new_admission_label')->value('value');
        $startAttendance = Setting::where('key', 'start_attendance')->value('value');
        $endAttendance = Setting::where('key', 'end_attendance')->value('value');
        $lastUpdatedBy = Setting::where('key', 'last_updated_by')->value('value');
        $user = $this->userRepository->getById($lastUpdatedBy);
        return view('backend.setting.index', compact('oldDate', 'newDate', 'oldLabel', 'newLabel', 'startAttendance', 'endAttendance', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $oldDate = Setting::where('key', 'old_admission_date')->value('value');
        $newDate = Setting::where('key', 'new_admission_date')->value('value');
        $oldLabel = Setting::where('key', 'old_admission_label')->value('value');
        $newLabel = Setting::where('key', 'new_admission_label')->value('value');
        $startAttendance = Setting::where('key', 'start_attendance')->value('value');
        $endAttendance = Setting::where('key', 'end_attendance')->value('value');
        return view('backend.setting.create', compact('oldDate', 'newDate', 'oldLabel', 'newLabel', 'startAttendance', 'endAttendance'));
    }

    public function store(Request $request)
    {
        $params = $request->all();
        $params['setting'][0]['value'] = ($request->setting[0]['value']) ? \DateTime::createFromFormat('d/m/Y', $params['setting'][0]['value'])->format('Y-m-d') : null;
        $params['setting'][1]['value'] = ($request->setting[1]['value']) ? \DateTime::createFromFormat('d/m/Y', $params['setting'][1]['value'])->format('Y-m-d') : null;
        $params['setting'][6]['value'] = Auth::user()->id;

        foreach ($params['setting'] as $item) {
            Setting::updateOrCreate(['key' => $item['key']], ["key" => $item['key'], "value" => $item['value']]);
        }

        return redirect()->route('setting.index')->with(['status' => 'success', 'message' => 'Configuration Updated successfully']);
    }
}
