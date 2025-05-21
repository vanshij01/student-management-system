<?php

namespace App\Repositories;

use App\Contract\RoleRepositoryInterface;
use App\Models\Role;

class RoleRepository implements RoleRepositoryInterface
{
    public function getAll()
    {
        return Role::orderBy("name", "asc")->get();
    }

    public function roleData()
    {
        return Role::orderBy("id", "desc")->get();
    }

    public function getById($id)
    {
        return Role::where("id", $id)->first();
    }

    public function create($postData)
    {
        unset($postData['_token']);
        return Role::create($postData);
    }

    public function update($postData, $id)
    {
        unset($postData['_token'], $postData['_method'], $postData['action']);
        $model = Role::findOrFail($id);
        return $model->update($postData);
    }

    public function delete($id)
    {
        $model = Role::findOrFail($id);
        return $model->delete();
    }
}
