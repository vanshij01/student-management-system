<?php

namespace App\Contract;

interface AdmissionRepositoryInterface
{
    public function getAll($data = [], $year = null);
    public function getAdmissionByStudentId();
    public function getAllAdmissionByStudentId($id);
    public function getById($id);
    public function isExist($name, $email, $number);
    public function create($postData);
    public function update($postData, $id);
    public function delete($id);
    public function studentAdmissionMapInsert($studentAdmissionMapData);
    public function dueFeesReportData($data);
    public function isConfirmAdmission($data);
    public function admissionStatus($data);
    public function getStudentDocumentsByAdmissionId($admissionId);
    public function isRoomAllocation($student_id, $admission_id = null);
    public function getReservationByAdmissionId($admissionId);
    public function isStudentHasNewAdmission();
    public function getStudentVillageId();
    public function studentDetails();
    public function getDataBySearchKeyword($data, $keyword);
    public function currrentAdmissionYear();
    public function getDocumentTypes();
    public function getDocumentTypeById($typeId);
    public function view($id);
    public function year();
    public function allotedStudentsRecord($data);
    public function releaseStudentData($admissionId);
    public function checkStudent($studentId);
    public function admissionStatusData($year = null);
    public function isCourseExist($id);
    public function getLatestComment($admission_id, $user_id);
}
