<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Role extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'roles';

    protected $fillable = ['name', 'guard_name'];

    protected static $logAttributes = ['*'];
    protected static $logFillable = true;
    protected static $recordEvents = ['created', 'updated', 'deleted'];
    protected static $logOnlyDirty = true;
    protected static $logUnguarded = true;
    protected static $logName = 'roles';

    public function getActivitylogOptions(): LogOptions
    {
        if (Auth::user()) {
            $userName = Auth::user()->name;
        } else {
            $userName = 'Super Admin';
        }

        return LogOptions::defaults()
            ->logOnly(['*'])
            ->useLogName('Role')
            ->setDescriptionForEvent(function (string $eventName) use ($userName) {
                return "{$userName} has {$eventName} Role";
            });
    }
}
