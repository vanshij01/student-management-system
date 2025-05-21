<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class Student extends Model
{
    use HasFactory/* , LogsActivity */;

    protected $table = 'students';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'course_id',
        'country_id',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'password',
        'phone',
        'dob',
        'gender', 'address',
        'status',
        'is_any_illness',
        'illness_description',
        'user_id',
        'village_id',
        'created_by'
    ];

    protected static $logAttributes = ['*'];
    protected static $logFillable = true;
    protected static $recordEvents = ['created', 'updated', 'deleted'];
    protected static $logOnlyDirty = true;
    protected static $logUnguarded = true;
    protected static $logName = 'student';

    /* public function getActivitylogOptions(): LogOptions
    {
        if (Auth::user()) {
            $userName = Auth::user()->name;
        } else {
            $userName = 'Super Admin';
        }

        return LogOptions::defaults()
            ->logOnly(['*'])
            ->useLogName('Student')
            ->setDescriptionForEvent(function (string $eventName) use ($userName) {
                return "{$userName} has {$eventName} Student";
            });
    } */

    public function admission()
    {
        return $this->belongsToMany(Admission::class, 'student_admission_map')->select('addmission_year');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function studentadmissionmap()
    {
        return $this->belongsTo('App\StudentAdmissionMap', 'student_id', 'id');
    }

	public function getFullNameAttribute()
	{
		return $this->first_name. " " . $this->middle_name . " " . $this->last_name;
	}

    /**
     * Get the user that owns the Student
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    /**
     * Get the user that owns the Student
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class, 'village_id', 'id');
    }

    protected static function booted()
    {
        static::created(function ($student) {
            $userName = Auth::check() ? Auth::user()->full_name : 'Super Admin';
            Activity::create([
                'log_name' => 'Student',
                'description' => "{$userName} has created Student",
                'subject_id' => $student->id,
                'subject_type' => get_class($student),
                'event' => 'created',
                'causer_id' => Auth::id(),
                'causer_type' => User::class,
                'created_at' => now(),
                'updated_at' => now(),
                'student_id' => $student->id,
                'properties' => [
                    'attributes' => $student->getAttributes(),
                ],
            ]);
        });

        static::updated(function ($student) {
            $userName = Auth::check() ? Auth::user()->full_name : 'Super Admin';
            Activity::create([
                'log_name' => 'Student',
                'description' => "{$userName} has updated Student",
                'subject_id' => $student->id,
                'subject_type' => get_class($student),
                'event' => 'updated',
                'causer_id' => Auth::id(),
                'causer_type' => User::class,
                'created_at' => now(),
                'updated_at' => now(),
                'student_id' => $student->id,
                'properties' => [
                    'attributes' => $student->getAttributes(),
                    'old' => $student->getOriginal(),
                ],
            ]);
        });

        static::deleted(function ($student) {
            $userName = Auth::check() ? Auth::user()->full_name : 'Super Admin';
            Activity::create([
                'log_name' => 'Student',
                'description' => "{$userName} has deleted Student",
                'subject_id' => $student->id,
                'subject_type' => get_class($student),
                'event' => 'deleted',
                'causer_id' => Auth::id(),
                'causer_type' => User::class,
                'created_at' => now(),
                'updated_at' => now(),
                'student_id' => $student->id,
                'properties' => [
                    'old' => $student->getOriginal(),
                ],
            ]);
        });
    }
}
