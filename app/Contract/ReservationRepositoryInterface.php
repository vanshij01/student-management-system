<?php

namespace App\Contract;

interface ReservationRepositoryInterface
{
    public function getAll();
    public function getById($id);
    public function isExist($name, $email, $number);
    public function create($params);
    public function update($payLoad, $id);
    public function delete($id);
    public function bedAllocation($data);
}
