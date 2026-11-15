<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function show(User $user): View
    {
        $user->load(['enrollments.course', 'courses']);
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'role' => ['required', 'in:student,author,admin'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Маълумоти корбар навсозӣ шуд!');
    }
}
