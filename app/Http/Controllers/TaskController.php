<?php
namespace App\Http\Controllers;
use App\Models\Task;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    use AuthorizesRequests;

    public function index() {
        $tasks = auth()->user()->tasks()->orderBy('deadline')->get();
        return view('tasks.index', compact('tasks'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'priority' => ['required', Rule::in(['high', 'medium', 'low'])],
            'deadline' => ['required', 'date'],
        ]);

        auth()->user()->tasks()->create($validated);
        return back()->with('success', "Task added successfully! Let's get it done. ✅");
    }

    public function update(Request $request, Task $task) {
        $this->authorize('update', $task);
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'priority' => ['required', Rule::in(['high', 'medium', 'low'])],
            'deadline' => ['nullable', 'date'],
        ]);

        $task->update($validated);
        return back()->with('success', 'Task updated successfully! 📝');
    }

    public function updateStatus(Request $request, Task $task) {
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

    public function destroy(Task $task) {
        $this->authorize('delete', $task);
        $task->delete();
        return back()->with('success', 'Task deleted. 🗑️');
    }

    public function progress() {
        $tasks = auth()->user()->tasks()->orderBy('created_at','desc')->get();
        return view('tasks.progress', compact('tasks'));
    }

    public function graph() {
        $tasks = auth()->user()->tasks()->get();
        $completed = $tasks->where('status','completed')->count();
        $in_progress = $tasks->where('status','in_progress')->count();
        $pending = $tasks->where('status','pending')->count();
        return view('tasks.graph', compact('completed','in_progress','pending'));
    }

    public function track() {
        $tasks = auth()->user()->tasks()->orderBy('deadline')->get();
        return view('tasks.track', compact('tasks'));
    }
}
