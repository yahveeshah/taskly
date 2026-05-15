<div class="task-card">
    @if($task->isNearDeadline())
        <div class="deadline-warn">Deadline nearing — {{ $task->title }}</div>
    @endif
    <div class="task-title">
        @if($task->status === 'completed')
            <span class="tick">&#10003;</span>
        @endif
        {{ $task->title }}
    </div>
    @if($task->description)
        <div class="task-desc">{{ $task->description }}</div>
    @endif
    @if($task->deadline)
        <div class="task-date">Due: {{ $task->deadline->format('d M Y') }}</div>
    @endif
    <span class="status-badge s-{{ $task->status }}">
        {{ $task->status === 'pending' ? 'Pending' : ($task->status === 'in_progress' ? 'In Progress' : 'Completed') }}
    </span>
    <div class="task-actions">
        <button class="btn-edit" onclick="document.getElementById('editModal{{ $task->id }}').classList.add('open')">Edit</button>
        @if($task->status !== 'completed')
        <form method="POST" action="/tasks/{{ $task->id }}/status" style="display:inline">
            @csrf @method('PATCH')
            <input type="hidden" name="status" value="completed">
            <button type="submit" class="btn-complete">Mark Complete</button>
        </form>
        @endif
        <form method="POST" action="/tasks/{{ $task->id }}" style="display:inline">
            @csrf @method('DELETE')
            <button type="submit" class="btn-delete" onclick="return confirm('Delete this task?')">Delete</button>
        </form>
    </div>
</div>