<?php

namespace App\Models;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable/*  implements MustVerifyEmail */
{
    use HasFactory, Notifiable, LogsActivity;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'role_id',
        'name',
        'phone',
        'email',
        'password',
        'role',
        'two_factor_verified',
        'status'
    ];

    protected static $logAttributes = ['*'];
    protected static $logFillable = true;
    protected static $recordEvents = ['created', 'updated', 'deleted'];
    protected static $logOnlyDirty = true;
    protected static $logUnguarded = true;
    protected static $logName = 'users';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /* public function sendPasswordResetNotification($token)
    {
        // $this->notify(new ResetPassword($token));

        $data = [
            'email' => $this->email,
            'name' => $this->name,
            'reset_url'     => url('/password/reset/'.$token.'?email='.$this->email),
        ];
        Mail::send('reset-mail', $data, function($message) use($data){
            $message->subject('Reset Password Request');
            $message->to($data['email']);
        });
    } */

    /**
     * Get the student that owns the Student
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'user_id', 'id');
    }

    public function getRole(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function generateTwoFactorCode(): void
    {
        $this->timestamps = false;  // Prevent updating the 'updated_at' column
        $this->two_factor_code = rand(100000, 999999);  // Generate a random code
        $this->two_factor_expires_at = now()->addMinutes(1);  // Set expiration time
        $this->save();
    }

    public function resetTwoFactorCode(): void
    {
        $this->timestamps = false;
        $this->two_factor_code = null;
        $this->two_factor_expires_at = null;
        $this->save();
    }

    public function getActivitylogOptions(): LogOptions
    {
        if (Auth::user()) {
            $userName = Auth::user()->name;
        } else {
            $userName = 'Super Admin';
        }

        return LogOptions::defaults()
            ->logOnly(['*'])
            ->useLogName('User')
            ->setDescriptionForEvent(function (string $eventName) use ($userName) {
                return "{$userName} has {$eventName} User";
            });
    }

    /**
     * Get all of the permissions for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function permissions(): HasMany
    {
        return $this->hasMany(Permission::class, 'role_id', 'role_id');
    }

    public function hasRolePermission($module)
    {
        if ($this->permissions()->where('module', $module)->first()) {
            return true;
        }
        return false;
    }

    public function hasRoleCRUDPermission($module, $permission)
    {
        if ($this->permissions()->where([['module', $module], [$permission, 'on']])->first()) {
            return true;
        }
        return false;
    }
}
