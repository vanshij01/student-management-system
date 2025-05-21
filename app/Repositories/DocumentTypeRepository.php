<?php

namespace App\Repositories;

use App\Contract\DocumentTypeRepositoryInterface;
use App\Models\DocumentType;

class DocumentTypeRepository implements DocumentTypeRepositoryInterface
{
    public function getAll()
    {
        return DocumentType::orderBy("id", "desc")->get();
    }

    public function documentTypeData()
    {
        return DocumentType::orderBy("id", "desc")->get();
    }

    public function getById($id)
    {
        return DocumentType::findOrFail($id);
    }

    public function create($params)
    {
        return DocumentType::create($params);
    }

    public function update($payLoad, $id)
    {
        $course = DocumentType::findOrFail($id);
        return $course->update($payLoad);
    }

    public function delete($id)
    {
        $user = DocumentType::findOrFail($id);
        return $user->delete();
    }
}
