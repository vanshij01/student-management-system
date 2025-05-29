<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StudentDocument extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'student_documents';

    protected $fillable = [
        'student_id',
        'course_id',
        'doc_type',
        'doc_url',
        'description',
        'percentile',
        'result_status'
    ];

    protected static $logAttributes = ['*'];
    protected static $logFillable = true;
    protected static $recordEvents = ['created', 'updated', 'deleted'];
    protected static $logOnlyDirty = true;
    protected static $logUnguarded = true;
    protected static $logName = 'student_documents';

    public function getActivitylogOptions(): LogOptions
    {
        $userName = Auth::user()->name;

        return LogOptions::defaults()
            ->logOnly(['*'])
            ->useLogName('Student Document')
            ->setDescriptionForEvent(function (string $eventName) use ($userName) {
                return "{$userName} has {$eventName} StudentDocument";
            });
    }

    /**
     * Get the student that owns the StudentAdmissionMap
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }
}
