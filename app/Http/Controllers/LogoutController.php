<?php

namespace App\Http\Controllers;

class LogoutController extends Controller
{
    public function destroy()
    {
        auth()->logout();
        return redirect('/')->with('success', "You've been logged out. See you soon!");
    }
}
