<?php

namespace App\Contract;

interface RoleRepositoryInterface
{
    public function getAll();
    public function roleData();
    public function getById($id);
    public function create($params);
    public function update($payLoad, $id);
    public function delete($id);
}
