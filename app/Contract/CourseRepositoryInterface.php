<?php

namespace App\Contract;

interface CourseRepositoryInterface
{
    public function getAll();
    public function courseData();
    public function getById($id);
    public function create($params);
    public function update($payLoad, $id);
    public function delete($id);
    public function isExist($name, $email, $number);
}
