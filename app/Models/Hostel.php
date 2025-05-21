<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Hostel extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'hostels';

    protected $fillable = [
        'hostel_name',
        'location',
        'contact_number',
        'mobile_number',
        'status',
        'warden_id',
        'created_by'
    ];

    protected static $logAttributes = ['*'];
    protected static $logFillable = true;
    protected static $recordEvents = ['created', 'updated', 'deleted'];
    protected static $logOnlyDirty = true;
    protected static $logUnguarded = true;
    protected static $logName = 'hostels';

    public function warden()
    {
        return $this->hasOne(Warden::class, 'id', 'warden_id');
    }

    public function student()
    {
        return $this->belongsToMany(Student::class, 'reservations');
    }

    public function bed()
    {
        return $this->hasMany(Bed::class, 'hostel_id', 'id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        $userName = Auth::user()->name;

        return LogOptions::defaults()
            ->logOnly(['*'])
            ->useLogName('Hostel')
            ->setDescriptionForEvent(function (string $eventName) use ($userName) {
                return "{$userName} has {$eventName} Hostel";
            });
    }
}
