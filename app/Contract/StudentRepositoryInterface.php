<?php

namespace App\Contract;

interface StudentRepositoryInterface
{
    public function getAll();
    public function getById($id);
    public function delete($id);
    public function create($params);
    public function update($payLoad, $id);
    public function studentData($genderId, $countryId);
    public function isExist($name, $email, $number);
    public function studentInfo($year = null);
    public function checkUserEmailExists($postData, $id);
    public function checkStudentEmailExists($postData, $id);
    public function getStudentUserId($id);
    public function updateStudentUser($user, $userId);
    public function getStudentNotAllotBed();
    public function updateProfile($postData, $id);
    public function studentInfoYearWise();
    public function updateStudentEmail($data, $userId);
    public function getStudentIdAdmissionMap($admission_id);
    public function getStudentByUserId();
    public function getStudentByPassedUserId($userId);
    public function userFullDetail($userId);
    public function getConfirmStudent();
    public function getNoAdmissionStudent();
    public function getByStudentId($id);
    public function isHostelExist($id);
    public function isRoomExist($id);
    public function isBedExist($id);
    public function isCountryExist($id);
    public function isVillageExist($id);
}
