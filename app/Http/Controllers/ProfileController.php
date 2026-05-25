<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit() { return view('profile.edit'); }

    public function update(Request $request) {
        $request->validate(['name'=>'required','email'=>'required|email|unique:users,email,'.auth()->id()]);
        auth()->user()->update($request->only('name','email'));
        return back()->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request) {
        $request->validate(['password'=>'required|min:6|confirmed']);
        auth()->user()->update(['password'=>bcrypt($request->password)]);
        return back()->with('success', 'Password changed successfully!');
    }

    public function destroy(Request $request) {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = auth()->user();

        if ($user->isManager()) {
            $team = $user->managedTeam;
            if ($team) {
                $team->members()->update([
                    'team_id' => null,
                    'role' => null,
                    'use_type' => 'personal'
                ]);
                $team->delete();
            }
        }

        auth()->logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Your account has been successfully deleted.');
    }
}
