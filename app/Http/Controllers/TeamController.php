<?php

namespace App\Http\Controllers;

use App\Models\User;

class TeamController extends Controller
{
    public function index()
    {
        $manager = auth()->user();
        abort_unless($manager->isManager() && $manager->team_id, 403);

        $members = User::with(['tasks' => fn ($query) => $query->where('is_personal', false)])
            ->where('team_id', $manager->team_id)
            ->where('role', 'member')
            ->orderBy('name')
            ->get();

        return view('team.index', [
            'team' => $manager->team,
            'members' => $members,
        ]);
    }

    public function member(User $user)
    {
        $manager = auth()->user();
        abort_unless(
            $manager->isManager()
            && $manager->team_id
            && $user->team_id === $manager->team_id
            && $user->role === 'member',
            403
        );

        $tasks = $user->tasks()->where('is_personal', false)->orderBy('created_at', 'desc')->get();
        $completed = $tasks->where('status', 'completed')->count();
        $in_progress = $tasks->where('status', 'in_progress')->count();
        $pending = $tasks->where('status', 'pending')->count();

        return view('team.member', compact('user', 'tasks', 'completed', 'in_progress', 'pending'));
    }
}
