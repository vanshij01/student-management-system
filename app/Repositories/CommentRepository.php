<?php

namespace App\Repositories;

use App\Contract\CommentRepositoryInterface;
use App\Models\Comment;

class CommentRepository implements CommentRepositoryInterface
{
    public function getAll()
    {
        return Comment::orderBy("id", "desc")->get();
    }

    public function create($params)
    {
        return Comment::create($params);
    }
}
