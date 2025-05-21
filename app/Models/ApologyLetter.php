<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class ApologyLetter extends Model
{
    use HasFactory/* , LogsActivity */;

    protected $table = 'apology_letters';

    protected $fillable = [
        'student_id',
        'subject',
        'letter_content',
        'document',
        'note',
        'created_by'
    ];

    protected static $logAttributes = ['*'];
    protected static $logFillable = true;
    protected static $recordEvents = ['created', 'updated', 'deleted'];
    protected static $logOnlyDirty = true;
    protected static $logUnguarded = true;
    protected static $logName = 'apology_letters';

    /**
     * Get the user that owns the ApologyLetter
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    /* public function getActivitylogOptions(): LogOptions
    {
        $userName = Auth::user()->name;

        return LogOptions::defaults()
            ->logOnly(['*'])
            ->useLogName('Apology Letter')
            ->setDescriptionForEvent(function (string $eventName) use ($userName) {
                return "{$userName} has {$eventName} Apology Letter";
            });
    } */

    protected static function booted()
    {
        static::created(function ($ApologyLetter) {
            $userName = Auth::check() ? Auth::user()->name : 'Super Admin';
            Activity::create([
                'log_name' => 'Apology Letter',
                'description' => "{$userName} has created Apology Letter",
                'subject_id' => $ApologyLetter->id,
                'subject_type' => get_class($ApologyLetter),
                'event' => 'created',
                'causer_id' => Auth::id(),
                'causer_type' => User::class,
                'created_at' => now(),
                'updated_at' => now(),
                'student_id' => $ApologyLetter->student_id,
                'properties' => [
                    'attributes' => $ApologyLetter->getAttributes(),
                ],
            ]);
        });

        static::updated(function ($ApologyLetter) {
            $userName = Auth::check() ? Auth::user()->name : 'Super Admin';
            Activity::create([
                'log_name' => 'Apology Letter',
                'description' => "{$userName} has updated Apology Letter",
                'subject_id' => $ApologyLetter->id,
                'subject_type' => get_class($ApologyLetter),
                'event' => 'updated',
                'causer_id' => Auth::id(),
                'causer_type' => User::class,
                'created_at' => now(),
                'updated_at' => now(),
                'student_id' => $ApologyLetter->student_id,
                'properties' => [
                    'attributes' => $ApologyLetter->getAttributes(),
                    'old' => $ApologyLetter->getOriginal(),
                ],
            ]);
        });

        static::deleted(function ($ApologyLetter) {
            $userName = Auth::check() ? Auth::user()->name : 'Super Admin';
            Activity::create([
                'log_name' => 'Apology Letter',
                'description' => "{$userName} has deleted Apology Letter",
                'subject_id' => $ApologyLetter->id,
                'subject_type' => get_class($ApologyLetter),
                'event' => 'deleted',
                'causer_id' => Auth::id(),
                'causer_type' => User::class,
                'created_at' => now(),
                'updated_at' => now(),
                'student_id' => $ApologyLetter->student_id,
                'properties' => [
                    'old' => $ApologyLetter->getOriginal(),
                ],
            ]);
        });
    }
}
