<?php

namespace App\Http\Controllers;

class LoginController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store()
    {
        $validated = request()->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (auth()->attempt($validated)) {
            session()->regenerate();
            return redirect()->route('dashboard')->with('success', "Welcome back! You're now logged in.");
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.'
        ]);
    }
}
