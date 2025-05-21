<?php

namespace App\Contract;

interface ComplainRepositoryInterface
{
    public function getAll();
    public function getById($id);
    public function create($params);
    public function update($payLoad, $id);
    public function delete($id);
    public function complainData($data);
    public function isExist($name, $email, $number);
}
