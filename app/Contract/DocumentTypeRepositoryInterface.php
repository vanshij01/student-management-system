<?php

namespace App\Contract;

interface DocumentTypeRepositoryInterface
{
    public function getAll();
    public function documentTypeData();
    public function getById($id);
    public function create($params);
    public function update($payLoad, $id);
    public function delete($id);
}
