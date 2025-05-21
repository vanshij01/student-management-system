<?php

namespace App\Contract;

interface LeaveRepositoryInterface
{
    public function getAll();
    public function getById($id);
    public function create($params);
    public function update($payLoad, $id);
    public function delete($id);
    public function leaveData($data);
    public function isExist($name, $email, $number);
    public function getLeaveWithJoin($id);
}
