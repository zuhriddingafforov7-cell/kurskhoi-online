<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        return view('profile.edit', [
            'user' => auth()->user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
        ], [
            'name.required'  => 'Ному насаб ҳатмист.',
            'email.required' => 'Почтаи электронӣ ҳатмист.',
            'email.unique'   => 'Ин почтаи электронӣ аллакай истифода мешавад.',
        ]);

        $user->update([
            'name'  => $validated['name'],
            'email' => $validated['email'],
        ]);

        return back()->with('success', 'Маълумоти профил бо муваффақият навсозӣ шуд!');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'string', 'min:8', 'confirmed', Password::defaults()],
        ], [
            'current_password.current_password' => 'Пароли ҷорӣ нодуруст аст.',
            'password.min'                      => 'Пароли нав бояд на камтар аз 8 аломат бошад.',
            'password.confirmed'                => 'Тасдиқи парол мувофиқат намекунад.',
        ]);

        auth()->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Парол бо муваффақият иваз карда шуд!');
    }
}
