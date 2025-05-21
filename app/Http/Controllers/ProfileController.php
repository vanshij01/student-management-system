<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\StudentProfileUpdateRequest;
use App\Models\Student;
use App\Models\User;
use App\Repositories\CountryRepository;
use App\Repositories\StudentRepository;
use App\Repositories\VillageRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    private $studentRepository;
    private $countryRepository;
    private $villageRepository;

    public function __construct(
        StudentRepository $studentRepository,
        CountryRepository $countryRepository,
        VillageRepository $villageRepository,
    ) {
        $this->studentRepository = $studentRepository;
        $this->countryRepository = $countryRepository;
        $this->villageRepository = $villageRepository;
    }
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user  = $request->user();
        $student  = $this->studentRepository->getStudentByUserId();
        $countries  = $this->countryRepository->getAll();
        $villages  = $this->villageRepository->getAll();

        if (Auth::user()->role_id == 4) {
            return view('frontend.profile.edit', compact('user', 'student', 'countries', 'villages'));
        } else {
            return view('backend.profile.edit', [
                'user' => $user,
            ]);
        }
    }

    public function updatePassword(Request $request): View
    {
        return view('backend.profile.partials.update-password-form', [
            'user' => $request->user(),
        ]);
    }

    public function updatePasswordStudent(Request $request): View
    {
        return view('frontend.profile.partials.update-password-form', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit', Auth::user()->id)->with('status', 'profile-updated');
    }

    public function updateProfileStudent(StudentProfileUpdateRequest $request): RedirectResponse
    {
        $authUser = Auth::user();
        $user = User::find($authUser->id);

        $validated = $request->validated();

        $userData = [
            'name' => $validated['first_name'] . ' ' . ($validated['middle_name'] ?? '') . ' ' . $validated['last_name'],
            'email' => $validated['email'],
        ];

        $user->fill($userData);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $student = Student::where('user_id', $user->id)->firstOrFail();

        if (isset($validated['dob'])) {
            $validated['dob'] = \Carbon\Carbon::createFromFormat('d/m/Y', $validated['dob'])->format('Y-m-d');
        }

        $student->fill($validated);
        $student->save();
        return Redirect::route('student.profile.edit', $user->id)->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
