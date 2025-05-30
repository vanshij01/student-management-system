<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Notice extends Model
{
    use LogsActivity;

    protected $fillable = [
        'title',
        'content',
        'is_visible_for_student',
        'created_by'
    ];

    protected static $logAttributes = ['*'];
    protected static $logFillable = true;
    protected static $recordEvents = ['created', 'updated', 'deleted'];
    protected static $logOnlyDirty = true;
    protected static $logUnguarded = true;
    protected static $logName = 'notices';

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function getActivitylogOptions(): LogOptions
    {
        $userName = Auth::user()->name;

        return LogOptions::defaults()
            ->logOnly(['*'])
            ->useLogName('Notice')
            ->setDescriptionForEvent(function (string $eventName) use ($userName) {
                return "{$userName} has {$eventName} Notice";
            });
    }
}
