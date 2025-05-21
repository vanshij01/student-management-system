<?php

namespace App\Contract;

interface CommentRepositoryInterface
{
    public function getAll();
    public function create($params);
}
