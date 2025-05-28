<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class AdminUserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('backend.admin_user.index');
    }

    public function adminUserData()
    {
        $adminUser = $this->userRepository->adminUserData();
        return datatables()->of($adminUser)->addIndexColumn()->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::where('id', '!=', 1)->get();
        return view('backend.admin_user.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $params = $request->all();

        $validator = Validator::make($params, [
            'name' => 'required|unique:users,name',
            'email' => 'required|email|unique:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }

        $params['role'] = 'staff_user';
        $params['role_id'] = 2;
        $params['created_by'] = Auth::user()->id;

        $this->userRepository->create($params);

        return response()->json([
            'status' => 'success',
            'message' => 'Admin User added successfully',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $admin_user = $this->userRepository->getById($id);
        return view('backend.admin_user.show', compact('admin_user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $admin_user = $this->userRepository->getById($id);
        $roles = Role::where('id', '!=', 1)->get();
        return view('backend.admin_user.update', compact('admin_user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $admin_user = $this->userRepository->getById($id);

        $payLoad = $request->all();

        $validator = Validator::make($payLoad, [
            'name' => 'required|unique:users,name,' . $admin_user->id,
            'email' => 'required|email|unique:users,email,' . $admin_user->id,
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput($payLoad);
        }

        $this->userRepository->update($payLoad, $id);

        return redirect('admin_user')->with([
            'status' => 'success',
            'message' => 'Admin User updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->userRepository->delete($id);
        return response()->json([
            'status' => true,
            'message' => 'Admin User deleted successfully!',
        ]);
    }
}
