<x-layout title="My Tasks">
<style>
.toolbar{display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;gap:1rem;flex-wrap:wrap}
.task-total{font-size:0.83rem;color:rgba(0,0,128,0.55)}
.manager-filter{display:flex;align-items:center;gap:0.7rem;flex-wrap:wrap}
.manager-filter label{font-size:0.72rem;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:rgba(0,0,128,0.58)}
.manager-filter select{background:#fff;border:2px solid var(--lav);border-radius:50px;color:var(--navy);font-size:0.84rem;font-weight:700;padding:0.55rem 1rem;outline:none}
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
.sortable-column{min-height:4rem;border-radius:12px;padding:0.25rem;transition:outline 0.2s ease,background 0.2s ease}
.sortable-column.drag-over{outline:2px dashed var(--lav);outline-offset:4px;background:rgba(199,160,203,0.08)}
[data-theme="vintage"] .sortable-column.drag-over{background:rgba(212,168,83,0.12)}
@keyframes task-card-in{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:translateY(0)}}
.task-card{padding:1.1rem 1.2rem;margin-bottom:0.9rem;transition:border-color 0.2s,box-shadow 0.2s,transform 0.2s;position:relative;animation:task-card-in 0.45s ease both;cursor:grab}
.task-card:active{cursor:grabbing}
.task-card.sortable-ghost{opacity:0.45}
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
.actions .ui-button{transition:transform 0.15s ease,background 0.2s ease,color 0.2s ease,border-color 0.2s ease}
.actions .ui-button:hover{transform:scale(1.06)}
.form-inline{display:inline}
.sortable-column:has(.task-card) .col-empty{display:none}
.modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,0.4);backdrop-filter:blur(4px);z-index:200;display:flex;align-items:center;justify-content:center;padding:1rem;opacity:0;visibility:hidden;pointer-events:none;transition:opacity 0.28s ease,visibility 0.28s ease}
.modal-overlay.open{opacity:1;visibility:visible;pointer-events:auto}
.modal{background:var(--card);border-radius:20px;padding:2rem;width:100%;max-width:460px;box-shadow:0 20px 60px rgba(0,0,128,0.15);transform:scale(0.96);opacity:0;transition:transform 0.28s ease,opacity 0.28s ease}
.modal-overlay.open .modal{transform:scale(1);opacity:1}
.modal h2{font-family:'Cormorant Garamond',serif;font-size:1.7rem;color:var(--navy);font-weight:700;margin-bottom:1.3rem}
.row2{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
.modal-actions{display:flex;gap:0.8rem;justify-content:flex-end;margin-top:1.2rem;flex-wrap:wrap}
@media (max-width:1100px){.columns{grid-template-columns:repeat(2,minmax(0,1fr))}}
@media (max-width:720px){.columns,.row2{grid-template-columns:1fr}.modal-actions{justify-content:stretch}.modal-actions .ui-button{flex:1}}
</style>

<div class="toolbar">
    <div class="task-total">{{ $tasks->count() }} task{{ $tasks->count() !== 1 ? 's' : '' }} total</div>
    @if(auth()->user()->isManager())
    <form class="manager-filter" method="GET" action="{{ route('tasks') }}">
        <label for="memberFilter">Filter by member</label>
        <select id="memberFilter" name="member_id" onchange="this.form.submit()">
            <option value="">All team tasks</option>
            @foreach($teamUsers as $teamUser)
                <option value="{{ $teamUser->id }}" @selected($selectedMemberId === $teamUser->id)>{{ $teamUser->name }}</option>
            @endforeach
        </select>
    </form>
    @endif
    @unless(auth()->user()->isMember())
    <button class="ui-button ui-button-primary" type="button" onclick="document.getElementById('addModal').classList.add('open')">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Add Task
    </button>
    @endunless
</div>

@php $cardIndex = 0; @endphp
<div class="columns">
@foreach(['high'=>'High Priority','medium'=>'Medium Priority','low'=>'Low Priority'] as $p=>$label)
@php $filtered = $tasks->where('priority', $p); @endphp
<div class="col-{{ $p === 'medium' ? 'med' : ($p === 'high' ? 'high' : 'low') }}">
    <div class="col-header">
        <span class="dot"></span>{{ $label }}
        <span class="col-count" data-col-count="{{ $p }}">{{ $filtered->count() }}</span>
    </div>
    <div class="sortable-column" data-priority="{{ $p }}">
    @forelse($filtered as $task)
    @php
        $dl = $task->deadline;
        $near = $dl && $dl->diffInDays(now()) <= 2 && $dl->isFuture();
        $delay = $cardIndex * 0.06;
        $cardIndex++;
    @endphp
    <div class="task-card ui-card {{ $task->status }}"
        style="animation-delay:{{ $delay }}s"
        data-task-id="{{ $task->id }}"
        data-title="{{ e($task->title) }}"
        data-description="{{ e($task->description ?? '') }}"
        data-priority="{{ $task->priority }}"
        data-deadline="{{ optional($task->deadline)->format('Y-m-d') }}"
        data-assigned-id="{{ auth()->user()->isManager() && $task->user_id !== auth()->id() ? $task->user_id : '' }}">
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
            @if(auth()->user()->isManager())
            <span class="ui-tag ui-tag-priority-low">{{ $task->user->name }}</span>
            @endif
        </div>
        <div class="actions">
            @unless(auth()->user()->isMember())
            <button type="button" class="ui-button ui-button-sm ui-button-secondary" onclick='openEdit(@js($task->id), @js($task->title), @js($task->description), @js($task->priority), @js(optional($task->deadline)->format("Y-m-d")), @js($task->user_id === auth()->id() ? "" : $task->user_id))'>Edit</button>
            @endunless
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
            @unless(auth()->user()->isMember())
            <form class="form-inline" method="POST" action="{{ route('tasks.destroy', $task) }}">
                @csrf @method('DELETE')
                <button type="submit" class="ui-button ui-button-sm ui-button-danger" onclick="return confirm('Delete this task?')">Delete</button>
            </form>
            @endunless
        </div>
    </div>
    @empty
    <div class="ui-empty col-empty">No tasks here</div>
    @endforelse
    </div>
</div>
@endforeach
</div>

@unless(auth()->user()->isMember())
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
        @if(auth()->user()->isManager())
        <div class="ui-field">
            <label>Assign To</label>
            <select name="assigned_user_id">
                <option value="">Me</option>
                @foreach($assignableMembers as $member)
                    <option value="{{ $member->id }}">{{ $member->name }} - {{ $member->email }}</option>
                @endforeach
            </select>
        </div>
        @endif
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
        @if(auth()->user()->isManager())
        <div class="ui-field">
            <label>Assign To</label>
            <select name="assigned_user_id" id="eAssigned">
                <option value="">Me</option>
                @foreach($assignableMembers as $member)
                    <option value="{{ $member->id }}">{{ $member->name }} - {{ $member->email }}</option>
                @endforeach
            </select>
        </div>
        @endif
        <div class="modal-actions">
            <button type="button" class="ui-button ui-button-secondary" onclick="document.getElementById('editModal').classList.remove('open')">Cancel</button>
            <button type="submit" class="ui-button ui-button-primary">Update Task</button>
        </div>
    </form>
</div>
</div>
@endunless

<script>
function openEdit(id,title,desc,priority,deadline,assignedId){
    document.getElementById('editForm').action = '/tasks/' + id;
    document.getElementById('eTitle').value = title || '';
    document.getElementById('eDesc').value = desc || '';
    document.getElementById('ePriority').value = priority || 'medium';
    document.getElementById('eDeadline').value = deadline || '';
    if (document.getElementById('eAssigned')) document.getElementById('eAssigned').value = assignedId || '';
    document.getElementById('editModal').classList.add('open');
}
window.onclick = function(e){
    if(e.target.classList.contains('modal-overlay')) e.target.classList.remove('open');
}
</script>

@unless(auth()->user()->isMember())
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
(function () {
    var csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function updateCounts() {
        document.querySelectorAll('[data-col-count]').forEach(function (badge) {
            var priority = badge.getAttribute('data-col-count');
            var column = document.querySelector('.sortable-column[data-priority="' + priority + '"]');
            var count = column ? column.querySelectorAll('.task-card').length : 0;
            badge.textContent = count;
        });
    }

    function clearDragOver() {
        document.querySelectorAll('.sortable-column').forEach(function (col) {
            col.classList.remove('drag-over');
        });
    }

    document.querySelectorAll('.sortable-column').forEach(function (column) {
        new Sortable(column, {
            group: 'task-priorities',
            animation: 180,
            draggable: '.task-card',
            ghostClass: 'sortable-ghost',
            filter: '.col-empty',
            onMove: function (evt) {
                clearDragOver();
                if (evt.to) evt.to.classList.add('drag-over');
                return true;
            },
            onEnd: function (evt) {
                clearDragOver();
                var card = evt.item;
                if (!card.classList.contains('task-card')) return;

                var newPriority = evt.to.dataset.priority;
                var oldPriority = card.dataset.priority;
                if (newPriority === oldPriority) return;

                var fromEl = evt.from;
                var oldIndex = evt.oldIndex;
                card.dataset.priority = newPriority;

                var body = new FormData();
                body.append('_method', 'PATCH');
                body.append('_token', csrf);
                body.append('title', card.dataset.title);
                body.append('description', card.dataset.description || '');
                body.append('priority', newPriority);
                body.append('deadline', card.dataset.deadline || '');
                if (card.dataset.assignedId) {
                    body.append('assigned_user_id', card.dataset.assignedId);
                }

                fetch('/tasks/' + card.dataset.taskId, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: body
                }).then(function (res) {
                    if (!res.ok) throw new Error('Request failed');
                    updateCounts();
                }).catch(function () {
                    card.dataset.priority = oldPriority;
                    var ref = fromEl.children[oldIndex] || null;
                    fromEl.insertBefore(card, ref);
                    updateCounts();
                });
            }
        });
    });
})();
</script>
@endunless
</x-layout>
