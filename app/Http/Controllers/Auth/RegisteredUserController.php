<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\StudentRepository;
use App\Models\Country;
use App\Models\User;
use App\Models\Village;
use App\Repositories\UserRepository;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $villages = Village::all();
        $countries  = Country::all();
        return view('auth.register', compact('villages', 'countries'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $params = $request->all();

        $request->validate([
            // 'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class, 'unique:students'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $params['role'] = 'student';
        $params['role_id'] = 4;
        $params['name'] = $params['first_name'] . ' ' . $params['last_name'];
        $params['dob'] = ($request->dob) ? \DateTime::createFromFormat('d/m/Y', $params['dob'])->format('Y-m-d') : null;

        $userRepository = new UserRepository();
        $user = $userRepository->create($params);

        $params['user_id'] = $user->id;

        $studentRepository = new StudentRepository();
        $student =  $studentRepository->create($params);

        event(new Registered($user));

        // Auth::login($user);

        // return redirect(route('dashboard', absolute: false));

        return redirect()->route('login')->with([
            'message' => 'Registration completed successfully!',
            'status' => 'success'
        ]);
    }
}
