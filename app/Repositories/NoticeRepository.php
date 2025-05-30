<?php

namespace App\Repositories;

use App\Contract\NoticeRepositoryInterface;
use App\Models\Notice;

/**
 * Interface NoticeRepository.
 *
 * @package namespace App\Repositories;
 */
class NoticeRepository implements NoticeRepositoryInterface
{
    public function getAll()
    {
        return Notice::with('user')->orderBy("id", "desc")->get();
    }

    public function getById($id)
    {
        return Notice::where("id", $id)->first();
    }

    public function create($postData)
    {
        return Notice::create($postData);
    }

    public function update($postData, $id)
    {
        $model = Notice::findOrFail($id);
        return $model->update($postData);
    }

    public function delete($id)
    {
        $model = Notice::findOrFail($id);
        return $model->delete();
    }

    public function getByFirst()
    {
        return Notice::first();
    }
}

