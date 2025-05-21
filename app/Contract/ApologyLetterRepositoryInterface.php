<?php

namespace App\Contract;

interface ApologyLetterRepositoryInterface
{
    public function getAll();
    public function getByStudentId($studentId);
    public function getById($id);
    public function create($params);
    public function update($payLoad, $id);
    public function delete($id);
}
