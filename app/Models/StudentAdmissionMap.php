<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentAdmissionMap extends Model
{
    use HasFactory;

    protected $table = 'student_admission_map';

    protected $fillable = [
        'student_id',
        'admission_id',
        'admission_year',
        'hostel_id',
        'room_id',
        'bed_id',
        'is_bed_release',
    ];

    /**
     * Get the student that owns the StudentAdmissionMap
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    /**
     * Get the admission that owns the StudentAdmissionMap
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function admission(): BelongsTo
    {
        return $this->belongsTo(Admission::class, 'admission_id', 'id');
    }

    /**
     * Get the user that owns the StudentAdmissionMap
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class, 'hostel_id', 'id');
    }

    /**
     * Get the user that owns the StudentAdmissionMap
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id', 'id');
    }

    /**
     * Get the user that owns the StudentAdmissionMap
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bed(): BelongsTo
    {
        return $this->belongsTo(Bed::class, 'bed_id', 'id');
    }
}
