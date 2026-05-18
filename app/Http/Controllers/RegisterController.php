<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store()
    {
        request()->merge([
            'use_type' => request('use_type', 'personal'),
        ]);

        $validated = request()->validate([
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:6', 'confirmed'],
            'use_type' => ['required', Rule::in(['personal', 'group'])],
            'role' => ['nullable', 'required_if:use_type,group', Rule::in(['manager', 'member'])],
            'team_code' => ['nullable', 'required_if:role,member', 'exists:teams,code'],
        ]);

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'use_type' => $validated['use_type'],
            'role' => $validated['use_type'] === 'group' ? $validated['role'] : null,
        ];

        if (($validated['role'] ?? null) === 'member') {
            $userData['team_id'] = Team::where('code', $validated['team_code'])->value('id');
        }

        $user = User::create($userData);

        if (($validated['role'] ?? null) === 'manager') {
            $code = $this->generateTeamCode();
            $team = Team::create([
                'name' => $user->name."'s Team",
                'code' => $code,
                'manager_id' => $user->id,
            ]);

            $user->update(['team_id' => $team->id]);

            return redirect('/')->with('success', "Welcome to Taskly! Your account has been created. 🎉 Share your Team Code: {$code}");
        }

        return redirect('/')->with('success', 'Welcome to Taskly! Your account has been created. 🎉');
    }

    private function generateTeamCode(): string
    {
        do {
            $code = 'TEAM-'.random_int(1000, 9999);
        } while (Team::where('code', $code)->exists());

        return $code;
    }
}
