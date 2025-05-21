<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempAdmissionUpload extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'course_id',
        'doc_type',
        'file_path',
        'uploaded_at',
    ];
}
