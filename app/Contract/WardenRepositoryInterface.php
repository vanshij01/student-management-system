<?php

namespace App\Contract;

interface WardenRepositoryInterface
{
    public function getAll();
    public function getById($id);
    public function create($params);
    public function update($payLoad, $id);
    public function delete($id);
    public function wardenData();
    public function getWardenWithUser($id);
    public function isExist($name, $email, $number);
    public function checkEmailExists($params, $id);
    public function checkWardenEmailExists($params, $id);
    public function getWardenUserId($id);
    public function updateWardenEmail($user, $userId);
    public function getHostelWarden($id);
}
