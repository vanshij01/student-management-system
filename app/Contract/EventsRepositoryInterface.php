<?php

namespace App\Contract;

interface EventsRepositoryInterface
{
    public function getAll();
    public function eventData();
    public function getById($id);
    public function create($params);
    public function update($payLoad, $id);
    public function delete($id);
}