<?php

namespace App\Repositories;

use App\Contract\StudentDocumentRepositoryInterface;
use App\Models\StudentDocument;

class StudentDocumentRepository implements StudentDocumentRepositoryInterface
{
    public function getAll()
    {
        return StudentDocument::orderBy("id", "asc")->get();
    }

    public function getById($id)
    {
        return StudentDocument::findOrFail($id);
    }

    public function create($params)
    {
        return StudentDocument::create($params);
    }

    public function update($payLoad, $id)
    {
        $student_document = StudentDocument::findOrFail($id);
        return $student_document->update($payLoad);
    }

    public function delete($id)
    {
        $student_document = StudentDocument::findOrFail($id);
        return $student_document->delete();
    }

    public function isStudentDocumentExist($type)
    {
        return StudentDocument::where('doc_type', $type)->exists();
    }
}
