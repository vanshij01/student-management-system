<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Fees extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'fees';

    protected $fillable = [
        'admission_id',
        'fees_amount',
        'payment_type',
        'payment_method',
        'paid_at',
        'status',
        'transaction_number',
        'bank_name',
        'cheque_number',
        'receipt_number',
        'remarks',
        'serial_number',
        'donation_type',
        'financial_year',
        'student_name',
        'father_name',
        'address',
        'created_by'
    ];

    protected static $logAttributes = ['*'];
    protected static $logFillable = true;
    protected static $recordEvents = ['created', 'updated', 'deleted'];
    protected static $logOnlyDirty = true;
    protected static $logUnguarded = true;
    protected static $logName = 'fees';

    public function admission()
    {
        return $this->belongsTo(Admission::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        $userName = Auth::user()->name;

        return LogOptions::defaults()
            ->logOnly(['*'])
            ->useLogName('Fees')
            ->setDescriptionForEvent(function (string $eventName) use ($userName) {
                return "{$userName} has {$eventName} Fees";
            });
    }
}
