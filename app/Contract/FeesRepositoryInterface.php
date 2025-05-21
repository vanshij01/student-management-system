<?php

namespace App\Contract;

interface FeesRepositoryInterface
{
    public function getAll($year = null);
    public function feesData($data);
    public function getById($id);
    public function getAllFeesByStudentId($id);
    public function getByAdmissionId($admissionId);
    public function getDataByAdmissionId($admissionId);
    public function isExist($name, $email, $number);
    public function create($postData);
    public function update($postData);
    public function delete($id);
}
