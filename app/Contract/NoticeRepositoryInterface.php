<?php

namespace App\Contract;

interface NoticeRepositoryInterface
{
    public function getAll();
    public function getById($id);
    public function create($params);
    public function update($payLoad, $id);
    public function delete($id);
    public function getByFirst();
}
