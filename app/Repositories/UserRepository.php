<?php

namespace App\Repositories;

use App\Contract\UserRepositoryInterface;
use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function getAll()
    {
        return User::orderBy("created_at", "desc")->get();
    }

    public function getById($id)
    {
        return User::findOrFail($id);
    }

    public function create($params)
    {
        unset($params['first_name'], $params['middle_name'], $params['last_name'], $params['phone'], $params['is_any_illness']);
        return User::create($params);
    }

    public function update($payLoad, $id)
    {
        $user = User::findOrFail($id);
        return $user->update($payLoad);
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
    }

    public function adminUserData()
    {
        return User::where('role', 'staff_user')->orderBy('id', 'desc')->get();
    }
}
