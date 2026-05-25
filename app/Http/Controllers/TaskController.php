<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    use AuthorizesRequests;

    public function dashboard()
    {
        $user = auth()->user();
        $tasks = $this->visibleTasksQuery($user)->with('user')->get();
        $today = now()->startOfDay();
        $weekStart = now()->startOfWeek();

        // Cache the dashboard stats for 5 minutes
        $stats = Cache::remember("dashboard_stats_{$user->id}", now()->addMinutes(5), function() use ($tasks, $today, $weekStart) {
            $total = $tasks->count();
            $completed = $tasks->where('status', 'completed')->count();
            $inProgress = $tasks->where('status', 'in_progress')->count();
            $pending = $tasks->where('status', 'pending')->count();

            $dueToday = $tasks
                ->filter(fn (Task $task) => $task->deadline?->isSameDay($today) && $task->status !== 'completed')
                ->pluck('id')
                ->toArray();

            $comingUp = $tasks
                ->filter(function (Task $task) use ($today) {
                    if (! $task->deadline || $task->status === 'completed') {
                        return false;
                    }

                    $deadline = $task->deadline->startOfDay();

                    return $deadline->gt($today) && $deadline->lte($today->copy()->addDays(7));
                })
                ->sortBy('deadline')
                ->pluck('id')
                ->toArray();

            $createdThisWeek = $tasks->filter(
                fn (Task $task) => $task->created_at && $task->created_at->gte($weekStart)
            )->count();

            $completedThisWeek = $tasks->filter(
                fn (Task $task) => $task->status === 'completed'
                    && $task->updated_at
                    && $task->updated_at->gte($weekStart)
            )->count();

            $weeklyCompletionPercent = $createdThisWeek > 0
                ? (int) round(($completedThisWeek / $createdThisWeek) * 100)
                : ($completedThisWeek > 0 ? 100 : 0);

            $overdueCount = $tasks->filter(
                fn (Task $task) => $task->deadline
                    && $task->deadline->lt($today)
                    && $task->status !== 'completed'
            )->count();

            return compact(
                'total', 'completed', 'inProgress', 'pending', 'dueToday', 
                'comingUp', 'weeklyCompletionPercent', 'completedThisWeek', 'overdueCount'
            );
        });

        // Reconstruct Collections from cached IDs to keep the view working
        $stats['dueToday'] = $tasks->whereIn('id', $stats['dueToday'])->values();
        
        // Ensure comingUp retains its sorted order
        $stats['comingUp'] = collect($stats['comingUp'])
            ->map(fn($id) => $tasks->firstWhere('id', $id))
            ->filter()
            ->values();

        $streak = $this->syncUserStreak($user);
        $assignableMembers = $this->assignableMembers($user);

        return view('tasks.dashboard', array_merge([
            'name' => $user->name,
            'streak' => $streak,
            'assignableMembers' => $assignableMembers,
        ], $stats));
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $selectedMemberId = null;

        $tasksQuery = $this->visibleTasksQuery($user)
            ->where('status', '!=', 'completed')
            ->with('user')
            ->orderBy('deadline');

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

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'priority' => ['required', Rule::in(['high', 'medium', 'low'])],
            'deadline' => ['required', 'date'],
            'assigned_user_id' => ['nullable', 'integer', 'exists:users,id'],
            'is_personal' => ['nullable', 'boolean'],
        ]);

        $isPersonal = $user->isMember() && $request->boolean('is_personal');

        if ($user->isMember()) {
            abort_unless($isPersonal, 403);
            $assigneeId = $user->id;
        } else {
            $assigneeId = $this->resolveAssigneeId($user, $validated['assigned_user_id'] ?? null);
        }

        Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'priority' => $validated['priority'],
            'deadline' => $validated['deadline'],
            'user_id' => $assigneeId,
            'is_personal' => $isPersonal,
        ]);

        // Invalidate dashboard stats cache when a new task is created
        Cache::forget("dashboard_stats_{$user->id}");

        return back()->with('success', "Task added successfully! Let's get it done.");
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);
        $user = auth()->user();

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'priority' => ['required', Rule::in(['high', 'medium', 'low'])],
            'deadline' => ['nullable', 'date'],
            'assigned_user_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        if ($user->isMember()) {
            abort_unless($task->is_personal && $task->user_id === $user->id, 403);
            unset($validated['assigned_user_id']);
            $task->update($validated);

            return back()->with('success', 'Task updated successfully!');
        }

        $validated['user_id'] = $this->resolveAssigneeId($user, $validated['assigned_user_id'] ?? $task->user_id);
        unset($validated['assigned_user_id']);

        $task->update($validated);

        // Invalidate dashboard stats cache when a task is updated
        Cache::forget("dashboard_stats_{$user->id}");
        // Invalidate history cache in case status or other details changed for completed task
        Cache::forget("completed_tasks_{$user->id}");

        return back()->with('success', 'Task updated successfully!');
    }

    public function updateStatus(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'status' => ['required', Rule::in(['pending', 'in_progress', 'completed'])],
        ]);

        $task->update(['status' => $validated['status']]);

        $user = auth()->user();
        Cache::forget("dashboard_stats_{$user->id}");
        Cache::forget("completed_tasks_{$user->id}");

        if ($validated['status'] === 'completed') {
            $this->syncUserStreak($task->user);
        }

        $message = match ($validated['status']) {
            'in_progress' => 'Task marked as In Progress! Keep going.',
            'completed' => 'Task completed! Nicely done.',
            default => 'Status updated.',
        };

        return back()->with('success', $message);
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $user = auth()->user();

        if ($user->isMember()) {
            abort_unless($task->is_personal && $task->user_id === $user->id, 403);
        }

        $task->delete();

        Cache::forget("dashboard_stats_{$user->id}");
        Cache::forget("completed_tasks_{$user->id}");

        return back()->with('success', 'Task deleted.');
    }

    public function history()
    {
        $user = auth()->user();
        
        // Cache history tasks for 10 minutes
        $tasks = Cache::remember("completed_tasks_{$user->id}", now()->addMinutes(10), function() use ($user) {
            return $this->visibleTasksQuery($user)
                ->where('status', 'completed')
                ->with('user')
                ->orderByDesc('updated_at')
                ->get()
                ->toArray();
        });

        return view('tasks.history', compact('tasks'));
    }

    public function destroySelected(Request $request)
    {
        $user = auth()->user();
        $validated = $request->validate([
            'task_ids' => ['required', 'array'],
            'task_ids.*' => ['integer'],
        ]);

        $tasksToDelete = Task::whereIn('id', $validated['task_ids'])->get();

        foreach ($tasksToDelete as $task) {
            $this->authorize('delete', $task);
            if ($user->isMember()) {
                abort_unless($task->is_personal && $task->user_id === $user->id, 403);
            }
            $task->delete();
        }

        Cache::forget("dashboard_stats_{$user->id}");
        Cache::forget("completed_tasks_{$user->id}");

        return back()->with('success', 'Selected tasks have been deleted.');
    }

    public function destroyAll()
    {
        $user = auth()->user();
        
        $tasksToDelete = $this->visibleTasksQuery($user)
            ->where('status', 'completed')
            ->get();

        foreach ($tasksToDelete as $task) {
            $this->authorize('delete', $task);
            if ($user->isMember()) {
                abort_unless($task->is_personal && $task->user_id === $user->id, 403);
            }
            $task->delete();
        }

        Cache::forget("dashboard_stats_{$user->id}");
        Cache::forget("completed_tasks_{$user->id}");

        return back()->with('success', 'All completed tasks have been deleted.');
    }

    public function progress()
    {
        $user = auth()->user();
        $tasks = $this->visibleTasksQuery($user)->with('user')->orderBy('created_at', 'desc')->get();
        $teamMembers = $this->filterableMembers($user);

        return view('tasks.progress', compact('tasks', 'teamMembers'));
    }

    public function graph()
    {
        $user = auth()->user();
        $tasks = $this->visibleTasksQuery($user)->with('user')->get();
        $completed = $tasks->where('status', 'completed')->count();
        $in_progress = $tasks->where('status', 'in_progress')->count();
        $pending = $tasks->where('status', 'pending')->count();
        $teamMembers = $this->filterableMembers($user);

        $taskDates = json_encode(
            $tasks
                ->filter(fn (Task $task) => $task->deadline !== null)
                ->map(fn (Task $task) => [
                    'date' => $task->deadline->format('Y-m-d'),
                    'priority' => $task->priority,
                    'title' => $task->title,
                    'status' => $task->status,
                    'user_id' => $task->user_id,
                ])
                ->values()
                ->all()
        );

        return view('tasks.graph', compact('completed', 'in_progress', 'pending', 'taskDates', 'teamMembers'));
    }

    public function track()
    {
        $user = auth()->user();
        $tasks = $this->visibleTasksQuery($user)->with('user')->orderByDesc('created_at')->get();
        $teamMembers = $this->filterableMembers($user);

        return view('tasks.track', compact('tasks', 'teamMembers'));
    }

    private function syncUserStreak(User $user): int
    {
        $dates = Task::query()
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->select(DB::raw('DATE(updated_at) as completion_date'))
            ->distinct()
            ->orderByDesc('completion_date')
            ->pluck('completion_date')
            ->map(fn ($date) => Carbon::parse($date)->startOfDay());

        if ($dates->isEmpty()) {
            $user->update(['streak' => 0, 'last_streak_date' => null]);

            return 0;
        }

        $today = now()->startOfDay();
        $yesterday = $today->copy()->subDay();
        $latest = $dates->first();

        if (! $latest->equalTo($today) && ! $latest->equalTo($yesterday)) {
            $user->update([
                'streak' => 0,
                'last_streak_date' => $latest->toDateString(),
            ]);

            return 0;
        }

        $dateSet = $dates->mapWithKeys(fn (Carbon $date) => [$date->toDateString() => true]);
        $cursor = $latest->equalTo($today) ? $today->copy() : $yesterday->copy();
        $streak = 0;

        while ($dateSet->has($cursor->toDateString())) {
            $streak++;
            $cursor->subDay();
        }

        $user->update([
            'streak' => $streak,
            'last_streak_date' => $latest->toDateString(),
        ]);

        return $streak;
    }

    private function visibleTasksQuery(User $user)
    {
        if ($user->isManager() && $user->team_id) {
            return Task::whereIn('user_id', $this->teamUserIds($user))
                ->where('is_personal', false);
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

    private function filterableMembers(User $user)
    {
        if (! $user->isManager() || ! $user->team_id) {
            return collect();
        }

        return User::where('team_id', $user->team_id)
            ->where('role', 'member')
            ->orderBy('name')
            ->get(['id', 'name']);
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
