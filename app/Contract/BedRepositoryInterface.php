<?php

namespace App\Contract;

interface BedRepositoryInterface
{
    public function getAll();
    public function getById($id);
    public function create($params);
    public function update($payLoad, $id);
    public function delete($id);
    public function bedData();
    public function getAvailableBed($year = null);
    public function isExist($data, $id = null);
    public function getBedByRoomId($id);
    public function getAvailableBedReport($data);
    public function getBedByReservation($id);
    public function isHostelExist($id);
    public function isRoomExist($id);
}
