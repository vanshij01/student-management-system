<?php

namespace App\Contract;

interface VillageRepositoryInterface
{
    public function getAll();
    public function villageData();
    public function getById($id);
    public function create($params);
    public function update($payLoad, $id);
    public function delete($id);
}
