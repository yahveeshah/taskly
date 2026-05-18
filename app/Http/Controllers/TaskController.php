<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $user = auth()->user();
        $selectedMemberId = null;

        $tasksQuery = $this->visibleTasksQuery($user)->with('user')->orderBy('deadline');

        if ($user->isManager() && $request->filled('member_id')) {
            $selectedMemberId = (int) $request->member_id;
            $memberIds = $this->teamUserIds($user);

            if ($memberIds->contains($selectedMemberId)) {
                $tasksQuery->where('user_id', $selectedMemberId);
            } else {
                $selectedMemberId = null;
            }
        }

        $tasks = $tasksQuery->get();
        $assignableMembers = $this->assignableMembers($user);
        $teamUsers = $user->isManager()
            ? User::whereIn('id', $this->teamUserIds($user))->orderBy('name')->get()
            : collect();

        return view('tasks.index', compact('tasks', 'assignableMembers', 'teamUsers', 'selectedMemberId'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        abort_if($user->isMember(), 403);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'priority' => ['required', Rule::in(['high', 'medium', 'low'])],
            'deadline' => ['required', 'date'],
            'assigned_user_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        $assigneeId = $this->resolveAssigneeId($user, $validated['assigned_user_id'] ?? null);

        Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'priority' => $validated['priority'],
            'deadline' => $validated['deadline'],
            'user_id' => $assigneeId,
        ]);

        return back()->with('success', "Task added successfully! Let's get it done. ✅");
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);
        abort_if(auth()->user()->isMember(), 403);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'priority' => ['required', Rule::in(['high', 'medium', 'low'])],
            'deadline' => ['nullable', 'date'],
            'assigned_user_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        $validated['user_id'] = $this->resolveAssigneeId(auth()->user(), $validated['assigned_user_id'] ?? $task->user_id);
        unset($validated['assigned_user_id']);

        $task->update($validated);

        return back()->with('success', 'Task updated successfully! 📝');
    }

    public function updateStatus(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'status' => ['required', Rule::in(['pending', 'in_progress', 'completed'])],
        ]);

        $task->update(['status' => $validated['status']]);

        $message = match ($validated['status']) {
            'in_progress' => 'Task marked as In Progress! Keep going 🔥',
            'completed' => 'Task completed! Nicely done 🏆',
            default => 'Status updated.',
        };

        return back()->with('success', $message);
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        abort_if(auth()->user()->isMember(), 403);

        $task->delete();

        return back()->with('success', 'Task deleted. 🗑️');
    }

    public function progress()
    {
        $tasks = $this->visibleTasksQuery(auth()->user())->orderBy('created_at', 'desc')->get();

        return view('tasks.progress', compact('tasks'));
    }

    public function graph()
    {
        $tasks = $this->visibleTasksQuery(auth()->user())->get();
        $completed = $tasks->where('status', 'completed')->count();
        $in_progress = $tasks->where('status', 'in_progress')->count();
        $pending = $tasks->where('status', 'pending')->count();

        $taskDates = json_encode(
            $tasks
                ->filter(fn (Task $task) => $task->deadline !== null)
                ->map(fn (Task $task) => [
                    'date' => $task->deadline->format('Y-m-d'),
                    'priority' => $task->priority,
                    'title' => $task->title,
                ])
                ->values()
                ->all()
        );

        return view('tasks.graph', compact('completed', 'in_progress', 'pending', 'taskDates'));
    }

    public function track()
    {
        $tasks = $this->visibleTasksQuery(auth()->user())->orderBy('deadline')->get();

        return view('tasks.track', compact('tasks'));
    }

    private function visibleTasksQuery(User $user)
    {
        if ($user->isManager() && $user->team_id) {
            return Task::whereIn('user_id', $this->teamUserIds($user));
        }

        return $user->tasks();
    }

    private function assignableMembers(User $user)
    {
        if (! $user->isManager() || ! $user->team_id) {
            return collect();
        }

        return User::where('team_id', $user->team_id)
            ->where('role', 'member')
            ->orderBy('name')
            ->get();
    }

    private function teamUserIds(User $user)
    {
        if (! $user->team_id) {
            return collect([$user->id]);
        }

        return User::where('team_id', $user->team_id)->pluck('id');
    }

    private function resolveAssigneeId(User $user, ?int $assigneeId): int
    {
        if (! $user->isManager()) {
            return $user->id;
        }

        if (! $assigneeId) {
            return $user->id;
        }

        $isTeamMember = User::where('id', $assigneeId)
            ->where('team_id', $user->team_id)
            ->exists();

        abort_unless($isTeamMember, 403);

        return $assigneeId;
    }
}
