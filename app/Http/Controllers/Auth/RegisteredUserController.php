<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
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
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'min:8', 'confirmed', Rules\Password::defaults()],
        ], [
            'name.required' => 'Номи истифодабаранда ҳатмист.',
            'email.required' => 'Почтаи электронӣ (email) ҳатмист.',
            'email.email' => 'Формати почтаи электронӣ нодуруст аст.',
            'email.unique' => 'Ин почтаи электронӣ аллакай сабт шудааст.',
            'password.required' => 'Парол ҳатмист.',
            'password.min' => 'Парол бояд на камтар аз 8 аломат бошад.',
            'password.confirmed' => 'Тасдиқи парол мувофиқат намекунад.',
        ]);

        // Нақши корбари нав ҳамеша 'student' аст ва аз форма қабул намешавад
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'student',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('student.dashboard');
    }
}
