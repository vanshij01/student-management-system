<?php

namespace App\Http\Controllers\Auth;

use App\Events\TwoFactorEvent;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class TwoFactorController extends Controller
{
    public function index()
    {
        return view('auth.twoFactor');
    }

    public function store(Request $request): ValidationException|RedirectResponse
    {
        $params = $request->all();

        $validation = Validator::make($params, [
            'two_factor_code.5' => 'required|digits:1',
        ], [
            'two_factor_code.5' => 'Please enter the code',
        ]);

        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput($params);
        }

        $user = User::find(Auth::id());
        $inputCode = implode('', $request->input('two_factor_code'));

        // Check if the code is expired
        if ($user->two_factor_expires_at < now()) {
            $user->resetTwoFactorCode();
            throw ValidationException::withMessages([
                'two_factor_code' => __("Your verification code has expired. Please resend."),
            ]);
        }

        // Check if the code matches
        if ($inputCode !== $user->two_factor_code) {
            throw ValidationException::withMessages([
                'two_factor_code' => __("The code you entered doesn't match our records"),
            ]);
        }

        $user->resetTwoFactorCode();

        if ($user->role_id == 4) {
            $activity = activity('Student')
                ->causedBy($user)
                ->event('Logged in')
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
                ->log($user->name . ' logged in');

                $student = Student::where('user_id', $user->id)->first();
                ActivityLog::where('id',$activity->id)->update([
                    'student_id' => $student->id ?? 0
                ]);
            return redirect()->to('/students/dashboard');
        }

        return redirect()->to('/dashboard');
    }

    public function resend(): RedirectResponse
    {
        $authUser = Auth::user();
        $user = User::find($authUser->id);
        $user->generateTwoFactorCode();
        // $user->notify(new SendTwoFactorCode());
        /* Mail::to($user->email)->send(new TwoFactorMail([
            'user' => $user,
       ])); */
        // $this->sendWhatsAppMessage($user);
        event(new TwoFactorEvent($user));

        return redirect()->back()->withStatus(__('Code has been sent again'));
    }

    /* public function sendWhatsAppMessage($user)
    {
        $studentId = Student::whereUserId($user->id)->value('id');
        $student = Student::find($studentId);

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
                        "body" => $user->two_factor_code . ' is your verification code. For your security, do not share this code.',
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
