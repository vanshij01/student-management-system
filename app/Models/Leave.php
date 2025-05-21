<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Leave extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'leaves';

    protected $fillable = [
        'leave_apply_by',
        'subject',
        'reason',
        'approve_by',
        'note',
        'leave_status',
        'leave_from',
        'leave_to',
        'ticket',
        'created_by'
    ];

    protected static $logAttributes = ['*'];
    protected static $logFillable = true;
    protected static $recordEvents = ['created', 'updated', 'deleted'];
    protected static $logOnlyDirty = true;
    protected static $logUnguarded = true;
    protected static $logName = 'leaves';

    public function student()
    {
        return $this->hasOne(Student::class, 'id', 'leave_apply_by');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'approve_by');
    }

    public function getActivitylogOptions(): LogOptions
    {
        $userName = Auth::user()->name;

        return LogOptions::defaults()
            ->logOnly(['*'])
            ->useLogName('Leave')
            ->setDescriptionForEvent(function (string $eventName) use ($userName) {
                return "{$userName} has {$eventName} Leave";
            });
    }
}
