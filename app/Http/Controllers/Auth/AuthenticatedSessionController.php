<?php

namespace App\Http\Controllers\Auth;

use App\Events\TwoFactorEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\ActivityLog;
use App\Models\Student;
use App\Models\User;
use App\Notifications\SendTwoFactorCode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $loginUser = User::where('email', $request->email)->first();

        $request->user()->generateTwoFactorCode();

        $user = $request->user();

        // if ($user->hasVerifiedEmail()) {
        // $this->sendWhatsAppMessage($user);
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
        // }

        if ($user->role_id == 4) {
            return redirect()->intended(route('student.dashboard', absolute: false));
        } else {
            return redirect()->intended(route('dashboard', absolute: false));
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $activity = activity('User')
            ->causedBy($user)
            ->event('Logged out')
            ->performedOn($user)
            ->withProperties([
                'attributes' => [
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]
            ])
            ->log($user->name . ' logged out');

        $student = Student::where('user_id', $user->id)->first();
        ActivityLog::where('id', $activity->id)->update([
            'student_id' => $student->id ?? 0
        ]);

        return redirect('/');
    }

    /* public function sendWhatsAppMessage($user)
    {
        $studentId = Student::whereUserId($user->id)->value('id');
        $student = Student::find($studentId);
        // dd($student);

        if (!empty($student->phone)) {
            $twilioSid = ENV("TWILIO_SID");
            $twilioToken = ENV("TWILIO_AUTH_TOKEN");
            $twilioWhatsAppNumber = ENV("TWILIO_WHATSAPP_NUMBER");
            $recipientNumber = 'whatsapp:+91' . $student->phone . '';

            $twilio = new Client($twilioSid, $twilioToken);

            try {
                $twilio->messages->create(
                    $recipientNumber, // To
                    [
                        // "contentSid" => "HX67f045f241c3547514b99349b7fb23d1",
                        "from" => $twilioWhatsAppNumber,
                        "body" => "Your Message",
                        // "messagingServiceSid" => "MGa53eebcc61f193deb5d21aa3ea8183ca",
                    ]
                );

                return redirect()->back()->with([
                    'message' => 'WhatsApp message sent successfully.',
                    'status' => 'success'
                ]);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        } else {
            return redirect()->back()->with([
                'message' => 'Mobile number is not provided..!',
                'status' => 'danger'
            ]);
        }
    } */
}
