<?php

namespace App\Contract;

interface RoomRepositoryInterface
{
    public function getAll();
    public function getById($id);
    public function create($params);
    public function update($payLoad, $id);
    public function delete($id);
    public function roomData($year = null);
    public function isExist($data, $id = null);
    public function getRoomByHostelId($id);
    public function isHostelExist($id);
}
