<x-layout title="My Tasks">
<style>
.toolbar{display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;gap:1rem;flex-wrap:wrap}
.task-total{font-size:0.83rem;color:rgba(0,0,128,0.55)}
.columns{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:1.5rem}
.col-header{display:flex;align-items:center;gap:0.6rem;padding:0.8rem 1rem;border-radius:10px;margin-bottom:1rem;font-size:0.75rem;font-weight:700;letter-spacing:1.4px;text-transform:uppercase}
.col-high .col-header{background:#fce8e8;color:#900}
.col-high .dot{background:#e74c3c}
.col-med .col-header{background:#fff8e1;color:#7a5c00}
.col-med .dot{background:var(--yellow)}
.col-low .col-header{background:#e8f0fe;color:var(--navy)}
.col-low .dot{background:var(--lav)}
.dot{width:8px;height:8px;border-radius:50%;flex-shrink:0}
.col-count{margin-left:auto;background:rgba(0,0,0,0.08);border-radius:50px;padding:0.1rem 0.5rem;font-size:0.7rem}
.task-card{padding:1.1rem 1.2rem;margin-bottom:0.9rem;transition:all 0.2s;position:relative}
.task-card:hover{transform:translateY(-2px);border-color:var(--lav)}
.task-card.completed{border-left:4px solid #27ae60;background:#fbfffc}
.task-card.in_progress{border-left:4px solid var(--yellow)}
.task-card.pending{border-left:4px solid var(--lav)}
.task-name{font-size:0.93rem;font-weight:700;color:var(--navy);margin-bottom:0.4rem;display:flex;align-items:center;gap:0.5rem;overflow-wrap:anywhere}
.task-name.done-text{text-decoration:line-through;color:rgba(0,0,128,0.58)}
.check-icon{color:#27ae60;flex-shrink:0}
.task-desc{font-size:0.8rem;color:rgba(0,0,128,0.68);margin-bottom:0.7rem;line-height:1.6;overflow-wrap:anywhere}
.meta{display:flex;flex-wrap:wrap;gap:0.45rem;margin-bottom:0.8rem}
.actions{display:flex;gap:0.45rem;flex-wrap:wrap}
.form-inline{display:inline}
.modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,0.4);backdrop-filter:blur(4px);z-index:200;display:none;align-items:center;justify-content:center;padding:1rem}
.modal-overlay.open{display:flex}
.modal{background:#fff;border-radius:20px;padding:2rem;width:100%;max-width:460px;box-shadow:0 20px 60px rgba(0,0,128,0.15)}
.modal h2{font-family:'Cormorant Garamond',serif;font-size:1.7rem;color:var(--navy);font-weight:700;margin-bottom:1.3rem}
.row2{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
.modal-actions{display:flex;gap:0.8rem;justify-content:flex-end;margin-top:1.2rem;flex-wrap:wrap}
@media (max-width:1100px){.columns{grid-template-columns:repeat(2,minmax(0,1fr))}}
@media (max-width:720px){.columns,.row2{grid-template-columns:1fr}.modal-actions{justify-content:stretch}.modal-actions .ui-button{flex:1}}
</style>

<div class="toolbar">
    <div class="task-total">{{ $tasks->count() }} task{{ $tasks->count() !== 1 ? 's' : '' }} total</div>
    <button class="ui-button ui-button-primary" type="button" onclick="document.getElementById('addModal').classList.add('open')">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Add Task
    </button>
</div>

<div class="columns">
@foreach(['high'=>'High Priority','medium'=>'Medium Priority','low'=>'Low Priority'] as $p=>$label)
@php $filtered = $tasks->where('priority', $p); @endphp
<div class="col-{{ $p === 'medium' ? 'med' : ($p === 'high' ? 'high' : 'low') }}">
    <div class="col-header">
        <span class="dot"></span>{{ $label }}
        <span class="col-count">{{ $filtered->count() }}</span>
    </div>
    @forelse($filtered as $task)
    @php
        $dl = $task->deadline;
        $near = $dl && $dl->diffInDays(now()) <= 2 && $dl->isFuture();
    @endphp
    <div class="task-card ui-card {{ $task->status }}">
        <div class="task-name {{ $task->status === 'completed' ? 'done-text' : '' }}">
            @if($task->status === 'completed')
            <svg class="check-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
            @endif
            {{ $task->title }}
        </div>
        @if($task->description)<div class="task-desc">{{ Str::limit($task->description, 80) }}</div>@endif
        <div class="meta">
            @if($dl)
            <span class="ui-tag ui-tag-deadline {{ $near ? 'is-near' : '' }}">Due {{ $dl->format('d M Y') }}{{ $near ? ' - Soon' : '' }}</span>
            @endif
            <span class="ui-tag ui-tag-status-{{ $task->status }}">{{ ucfirst(str_replace('_',' ',$task->status)) }}</span>
        </div>
        <div class="actions">
            <button type="button" class="ui-button ui-button-sm ui-button-secondary" onclick='openEdit(@js($task->id), @js($task->title), @js($task->description), @js($task->priority), @js(optional($task->deadline)->format("Y-m-d")))'>Edit</button>
            @if($task->status === 'pending')
            <form class="form-inline" method="POST" action="{{ route('tasks.status', $task) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="in_progress">
                <button type="submit" class="ui-button ui-button-sm ui-button-warning">In Progress</button>
            </form>
            @endif
            @if($task->status !== 'completed')
            <form class="form-inline" method="POST" action="{{ route('tasks.status', $task) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="completed">
                <button type="submit" class="ui-button ui-button-sm ui-button-success">Done</button>
            </form>
            @endif
            <form class="form-inline" method="POST" action="{{ route('tasks.destroy', $task) }}">
                @csrf @method('DELETE')
                <button type="submit" class="ui-button ui-button-sm ui-button-danger" onclick="return confirm('Delete this task?')">Delete</button>
            </form>
        </div>
    </div>
    @empty
    <div class="ui-empty">No tasks here</div>
    @endforelse
</div>
@endforeach
</div>

<div class="modal-overlay" id="addModal">
<div class="modal">
    <h2>New Task</h2>
    <form method="POST" action="{{ route('tasks.store') }}">
        @csrf
        <div class="ui-field"><label>Task Title</label><input type="text" name="title" placeholder="What needs to be done?" required></div>
        <div class="ui-field"><label>Description</label><textarea name="description" placeholder="Optional details..."></textarea></div>
        <div class="row2">
            <div class="ui-field"><label>Priority</label><select name="priority"><option value="high">High</option><option value="medium" selected>Medium</option><option value="low">Low</option></select></div>
            <div class="ui-field"><label>Deadline</label><input type="date" name="deadline" required></div>
        </div>
        <div class="modal-actions">
            <button type="button" class="ui-button ui-button-secondary" onclick="document.getElementById('addModal').classList.remove('open')">Cancel</button>
            <button type="submit" class="ui-button ui-button-primary">Save Task</button>
        </div>
    </form>
</div>
</div>

<div class="modal-overlay" id="editModal">
<div class="modal">
    <h2>Edit Task</h2>
    <form method="POST" id="editForm">
        @csrf @method('PATCH')
        <div class="ui-field"><label>Task Title</label><input type="text" name="title" id="eTitle" required></div>
        <div class="ui-field"><label>Description</label><textarea name="description" id="eDesc"></textarea></div>
        <div class="row2">
            <div class="ui-field"><label>Priority</label><select name="priority" id="ePriority"><option value="high">High</option><option value="medium">Medium</option><option value="low">Low</option></select></div>
            <div class="ui-field"><label>Deadline</label><input type="date" name="deadline" id="eDeadline"></div>
        </div>
        <div class="modal-actions">
            <button type="button" class="ui-button ui-button-secondary" onclick="document.getElementById('editModal').classList.remove('open')">Cancel</button>
            <button type="submit" class="ui-button ui-button-primary">Update Task</button>
        </div>
    </form>
</div>
</div>

<script>
function openEdit(id,title,desc,priority,deadline){
    document.getElementById('editForm').action = '/tasks/' + id;
    document.getElementById('eTitle').value = title || '';
    document.getElementById('eDesc').value = desc || '';
    document.getElementById('ePriority').value = priority || 'medium';
    document.getElementById('eDeadline').value = deadline || '';
    document.getElementById('editModal').classList.add('open');
}
window.onclick = function(e){
    if(e.target.classList.contains('modal-overlay')) e.target.classList.remove('open');
}
</script>
</x-layout>
