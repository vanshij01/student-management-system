<?php

namespace App\Http\Controllers\Auth;

use App\Events\TwoFactorEvent;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Student;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $user = $request->user();

        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false) . '?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        event(new TwoFactorEvent($user));

        $activity = activity('Student')
            ->causedBy($user)
            ->event('Login')
            ->performedOn($user)
            ->withProperties([
                'attributes' => [
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'two_factor_code' => $user->two_factor_code,
                    'two_factor_expires_at' => $user->two_factor_expires_at,
                ]
            ])
            ->log($user->name . ' received login code');

        $student = Student::where('user_id', $user->id)->first();
        ActivityLog::where('id', $activity->id)->update([
            'student_id' => $student->id ?? 0
        ]);

        return redirect()->intended(route('dashboard', absolute: false) . '?verified=1');
    }
}
