<?php

namespace App\Contract;

interface HostelRepositoryInterface
{
    public function getAll();
    public function getById($id);
    public function create($params);
    public function update($payLoad, $id);
    public function delete($id);
    public function hostelData($year = null);
    public function genderStatistics($year = null);
    public function getChartDataAvailableBed($year = null);
    public function getChartDataAdmissionYearwise();
    public function getHostels();
    public function isWardenExist($id);
}
