<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', [
            'request' => $request,
            'token' => $request->route('token'),
            'email' => $request->input('email', ''),
        ]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed', Rules\Password::defaults()],
        ], [
            'token.required' => 'Токени барқарорсозӣ зарур аст.',
            'email.required' => 'Почтаи электронӣ ҳатмист.',
            'email.email' => 'Формати почтаи электронӣ нодуруст аст.',
            'password.required' => 'Парол ҳатмист.',
            'password.min' => 'Пароли нав бояд на камтар аз 8 аломат бошад.',
            'password.confirmed' => 'Тасдиқи парол мувофиқат намекунад.',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', 'Пароли Шумо бомуваффақият нав карда шуд! Акнун метавонед ворид шавед.');
        }

        throw ValidationException::withMessages([
            'email' => 'Токени барқароркунӣ нодуруст аст ё мӯҳлаташ гузаштааст.',
        ]);
    }
}
