<?php

namespace App\Http\Controllers;

use App\Models\User;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store()
    {
        $validated = request()->validate([
            'name'     => ['required', 'min:3'],
            'email'    => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        User::create($validated);

        return redirect('/')->with('success', 'Welcome to Taskly! Your account has been created. 🎉');
    }
}
