<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class Admission extends Model
{
    use HasFactory/* , LogsActivity */;

    protected $table = 'admissions';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'is_admission_new',
        'email',
        'phone',
        'dob',
        'gender',
        'residence_number',
        'residence_address',
        'country',
        'adhaar_number',
        'passport_number',
        'nationality',
        'is_indian_citizen',
        'father_full_name',
        'father_occupation',
        'father_phone',
        'annual_income',
        'is_parent_indian_citizen',
        'is_local_guardian_in_ahmedabad',
        'guardian_name',
        'guardian_relation',
        'guardian_phone',
        'course_id',
        'board_type',
        'board_name',
        'semester',
        'institute_name',
        'year_of_addmission',
        'addmission_date',
        'college_start_time',
        'college_end_time',
        'college_fees_receipt_no',
        'college_fees_receipt_date',
        'college_fees_receipt_url',
        'arriving_date',
        'notes',
        'admin_comment',
        'student_photo_url',
        'father_photo_url',
        'mother_photo_url',
        'parent_photo_url',
        'is_fees_paid',
        'is_admission_confirm',
        'status',
        'mother_full_name',
        'mother_phone',
        'mother_occupation',
        'is_used_vehicle',
        'vehicle_number',
        'is_have_helmet',
        'licence_doc_url',
        'education_type',
        'rcbook_front_doc_url',
        'rcbook_back_doc_url',
        'insurance_doc_url',
        'is_any_illness',
        'illness_description',
        'created_by',
        'student_photoimage',
        'parent_photoimage',
        'passport_back',
        'aadhar_front',
        'aadhar_back',
        'passport_front',
        'passport_back',
        'local_guardian',
        'parent_citizen',
        'parents_aadhar_front',
        'parents_aadhar_back',
        'parents_passport_front',
        'has_backlog'
    ];

    protected static $logAttributes = ['*'];
    protected static $logFillable = true;
    protected static $recordEvents = ['created', 'updated', 'deleted'];
    protected static $logOnlyDirty = true;
    protected static $logUnguarded = true;
    protected static $logName = 'admissions';

    /* public function getActivitylogOptions(): LogOptions
    {
        $userName = Auth::user()->name;

        return LogOptions::defaults()
            ->logOnly(['*'])
            ->useLogName('Admission')
            ->setDescriptionForEvent(function (string $eventName) use ($userName) {
                return "{$userName} has {$eventName} Admission";
            });
    } */

    public function getFullNameAttribute()
    {
        return $this->first_name . " " . $this->middle_name . " " . $this->last_name;
    }

    /**
     * Get the user that owns the Admission
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    protected static function booted()
    {
        static::created(function ($admission) {
            $userName = Auth::check() ? Auth::user()->full_name : 'Super Admin';
            Activity::create([
                'log_name' => 'Admission',
                'description' => "{$userName} has created Admission",
                'subject_id' => $admission->id,
                'subject_type' => get_class($admission),
                'event' => 'created',
                'causer_id' => Auth::id(),
                'causer_type' => User::class,
                'created_at' => now(),
                'updated_at' => now(),
                'student_id' => StudentAdmissionMap::where('admission_id', $admission->id)->value('student_id'),
                'admission_id' => $admission->id,
                'properties' => [
                    'attributes' => $admission->getAttributes(),
                ],
            ]);
        });

        static::updated(function ($admission) {
            $userName = Auth::check() ? Auth::user()->full_name : 'Super Admin';
            Activity::create([
                'log_name' => 'Admission',
                'description' => "{$userName} has updated Admission",
                'subject_id' => $admission->id,
                'subject_type' => get_class($admission),
                'event' => 'updated',
                'causer_id' => Auth::id(),
                'causer_type' => User::class,
                'created_at' => now(),
                'updated_at' => now(),
                'student_id' => StudentAdmissionMap::where('admission_id', $admission->id)->value('student_id'),
                'admission_id' => $admission->id,
                'properties' => [
                    'attributes' => $admission->getAttributes(),
                    'old' => $admission->getOriginal(),
                ],
            ]);
        });

        static::deleted(function ($admission) {
            $userName = Auth::check() ? Auth::user()->full_name : 'Super Admin';
            Activity::create([
                'log_name' => 'Admission',
                'description' => "{$userName} has deleted Admission",
                'subject_id' => $admission->id,
                'subject_type' => get_class($admission),
                'event' => 'deleted',
                'causer_id' => Auth::id(),
                'causer_type' => User::class,
                'created_at' => now(),
                'updated_at' => now(),
                'student_id' => StudentAdmissionMap::where('admission_id', $admission->id)->value('student_id'),
                'admission_id' => $admission->id,
                'properties' => [
                    'old' => $admission->getOriginal(),
                ],
            ]);
        });
    }
}
