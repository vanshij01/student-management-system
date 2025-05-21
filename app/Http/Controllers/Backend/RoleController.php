<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('backend.role.index');
    }

    public function roleData()
    {
        $roles = Role::where('id', '!=', 1)->orderBy('id', 'desc');
        return datatables()->of($roles)->addIndexColumn()->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.role.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $param = $request->all();
        $validator = Validator::make($param, [
            'name' => ['required', 'string', 'max:20', 'unique:roles,name'],
        ]);
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }
        $role = Role::create([
            'name' => $request->input('name'),
            'guard_name' => 'admin',
        ]);
        // $role->syncPermissions($request->input('permission'));

        return redirect('role/' . $role->id . '/edit')->with(['status' => 'success', 'message' => 'Role Created Successfully.!']);
        /* return redirect('role')->with(['status' => 'success', 'message' => 'Role Created Successfully.!']); */
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        $tablesArr = [];
        if ($id) {
            $role = Role::find($id);

            $tables = DB::select('SHOW TABLES');
            foreach ($tables as $table) {
                $host = $request->getHttpHost();
                if ($host == 'localhost') {
                    $tablesArr[$table->Tables_in_mineology_server] = $table->Tables_in_mineology_server;
                } else {
                    $tablesArr[$table->{'Tables_in_' . env('DB_DATABASE')}] = $table->{'Tables_in_' . env('DB_DATABASE')};
                }
            }

            $filterArr = [];

            if ($tablesArr['activity_log']) {
                $filterArr['Activity Log'] = 'Activity Log';
            }

            if ($tablesArr['users']) {
                $filterArr['Admin User'] = 'Admin User';
            }

            if ($tablesArr['admissions']) {
                $filterArr['Admission'] = 'Admission';
            }

            if ($tablesArr['apology_letters']) {
                $filterArr['Apology Letter'] = 'Apology Letter';
            }

            /* if ($tablesArr['attendances']) {
                $filterArr['Attendance'] = 'Attendance';
            } */

            if ($tablesArr['beds']) {
                $filterArr['Bed'] = 'Bed';
            }

            if ($tablesArr['complains']) {
                $filterArr['Complain'] = 'Complain';
            }

            if ($tablesArr['country']) {
                $filterArr['Country'] = 'Country';
            }

            if ($tablesArr['courses']) {
                $filterArr['Course'] = 'Course';
            }

            if ($tablesArr['document_type']) {
                $filterArr['Document Type'] = 'Document Type';
            }

            if ($tablesArr['fees']) {
                $filterArr['Fee'] = 'Fee';
            }

            if ($tablesArr['hostels']) {
                $filterArr['Hostel'] = 'Hostel';
            }

            if ($tablesArr['leaves']) {
                $filterArr['Leave'] = 'Leave';
            }

            if (route('report.dueFees') || route('report.availableBeds') || route('report.allotedStudents')) {
                $filterArr['Report'] = 'Report';
            }

            if ($tablesArr['rooms']) {
                $filterArr['Room'] = 'Room';
            }

            if ($tablesArr['settings']) {
                $filterArr['Setting'] = 'Setting';
            }

            /* if ($tablesArr['students']) {
                $filterArr['Student'] = 'Student';
            } */

            if ($tablesArr['villages']) {
                $filterArr['Village'] = 'Village';
            }

            if ($tablesArr['wardens']) {
                $filterArr['Warden'] = 'Warden';
            }

            $permissionData = new Permission();
            return view('backend.role.show', ['role' => $role, 'accessData' => $filterArr, 'permissionData' => $permissionData]);
        } else {
            return Redirect::back()->with('error', 'ID not selected or not found.!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        $tablesArr = [];
        if ($id) {
            $role = Role::find($id);

            $tables = DB::select('SHOW TABLES');
            foreach ($tables as $table) {
                $host = $request->getHttpHost();
                if ($host == 'localhost') {
                    $tablesArr[$table->Tables_in_mineology_server] = $table->Tables_in_mineology_server;
                } else {
                    $tablesArr[$table->{'Tables_in_' . env('DB_DATABASE')}] = $table->{'Tables_in_' . env('DB_DATABASE')};
                }
            }

            // dd($tablesArr);

            $filterArr = [];

            if ($tablesArr['activity_log']) {
                $filterArr['Activity Log'] = 'Activity Log';
            }

            if ($tablesArr['users']) {
                $filterArr['Admin User'] = 'Admin User';
            }

            if ($tablesArr['admissions']) {
                $filterArr['Admission'] = 'Admission';
            }

            if ($tablesArr['apology_letters']) {
                $filterArr['Apology Letter'] = 'Apology Letter';
            }

            /* if ($tablesArr['attendances']) {
                $filterArr['Attendance'] = 'Attendance';
            } */

            if ($tablesArr['beds']) {
                $filterArr['Bed'] = 'Bed';
            }

            if ($tablesArr['complains']) {
                $filterArr['Complain'] = 'Complain';
            }

            if ($tablesArr['country']) {
                $filterArr['Country'] = 'Country';
            }

            if ($tablesArr['courses']) {
                $filterArr['Course'] = 'Course';
            }

            if ($tablesArr['document_type']) {
                $filterArr['Document Type'] = 'Document Type';
            }

            if ($tablesArr['fees']) {
                $filterArr['Fee'] = 'Fee';
            }

            if ($tablesArr['hostels']) {
                $filterArr['Hostel'] = 'Hostel';
            }

            if ($tablesArr['leaves']) {
                $filterArr['Leave'] = 'Leave';
            }

            if (route('report.dueFees') || route('report.availableBeds') || route('report.allotedStudents')) {
                $filterArr['Report'] = 'Report';
            }

            if ($tablesArr['rooms']) {
                $filterArr['Room'] = 'Room';
            }

            /* if ($tablesArr['settings']) {
                $filterArr['Setting'] = 'Setting';
            } */

            if ($tablesArr['students']) {
                $filterArr['Student'] = 'Student';
            }

            if ($tablesArr['villages']) {
                $filterArr['Village'] = 'Village';
            }

            if ($tablesArr['wardens']) {
                $filterArr['Warden'] = 'Warden';
            }

            $permissionData = new Permission();
            return view('backend.role.update', ['role' => $role, 'accessData' => $filterArr, 'permissionData' => $permissionData]);
        } else {
            return Redirect::back()->with('error', 'ID not selected or not found.!');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $param = $request->all();
        $role = Role::find($param['id']);
        $validator = Validator::make($param, [
            'name' => ['required', 'string', 'max:20', 'unique:roles,name,' . $role->id],
        ]);
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }
        $role_id = $param['id'];

        if (!empty($param['permission'])) {
            Permission::where('role_id', $role_id)->delete();
            foreach ($param['permission'] as $key => $value) {
                $value['module'] = $key;
                $value['role_id'] = $role_id;
                Permission::create($value);
            }
            // dd($param['permission']);
        } else {
            Permission::where('role_id', $role_id)->delete();
        }
        if (!empty($param)) {

            $role = Role::find($param['id']);
            unset($param['id']);
            $isUpdated = $role->update($param);
            if ($isUpdated) {
                return redirect('role')->with('success', 'Updated Successfully.!');
            } else {
                return Redirect::back()->with('error', 'Something Wrong happend.!');
            }
        } else {
            return Redirect::back()->with('error', 'ID not selected or not found.!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role, $id)
    {
        $user = User::where('role_id', $id)->exists();

        if ($user) {
            return response()->json([
                'status' => false,
                'message' => 'This is already in Use'
            ]);
        }

        if (!empty($id)) {
            $data = Role::find($id);
            $isDeleted = $data->delete();

            if ($isDeleted) {
                return response()->json([
                    'status' => true,
                    'message' => 'Record deleted successfully.'
                ]);
            } else {
                return Redirect::back()->with('error', 'Something went wrong.');
            }
        } else {
            return Redirect::back()->with('error', 'Id not selected or found.');
        }
    }
}
