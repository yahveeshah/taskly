<x-layout title="My Tasks">
    <style>
        :root{--lav:#C7A0CB;--navy:#000080;--yellow:#F6BE00;--lav-light:#f0e8f2}
        .task-toolbar{display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;flex-wrap:wrap;gap:1rem}
        .btn-add{background:var(--navy);color:#fff;border:none;padding:0.72rem 1.8rem;border-radius:50px;font-size:0.88rem;font-weight:700;cursor:pointer;font-family:'DM Sans',sans-serif;display:inline-flex;align-items:center;gap:0.5rem;transition:all 0.2s;text-decoration:none}
        .btn-add:hover{background:var(--lav);color:var(--navy)}
        .btn-add svg{flex-shrink:0}
        .columns{display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem}
        .col-header{display:flex;align-items:center;gap:0.6rem;padding:0.9rem 1rem;border-radius:12px;margin-bottom:1rem;font-size:0.78rem;font-weight:700;letter-spacing:1.5px;text-transform:uppercase}
        .col-high .col-header{background:#fce8e8;color:#900}
        .col-high .col-dot{background:#e74c3c}
        .col-med .col-header{background:#fff8e1;color:#7a5c00}
        .col-med .col-dot{background:var(--yellow)}
        .col-low .col-header{background:#e8f0fe;color:var(--navy)}
        .col-low .col-dot{background:var(--lav)}
        .col-dot{width:8px;height:8px;border-radius:50%}
        .col-count{margin-left:auto;background:rgba(0,0,0,0.08);border-radius:50px;padding:0.1rem 0.55rem;font-size:0.72rem}
        .task-card{background:#fff;border:1.5px solid #e8d8eb;border-radius:14px;padding:1.1rem 1.2rem;margin-bottom:0.9rem;transition:all 0.2s;position:relative}
        .task-card:hover{box-shadow:0 6px 22px rgba(0,0,128,0.08);transform:translateY(-2px);border-color:var(--lav)}
        .task-card.status-completed{opacity:0.6;border-left:4px solid #27ae60}
        .task-card.status-in_progress{border-left:4px solid var(--yellow)}
        .task-card.status-pending{border-left:4px solid var(--lav)}
        .task-name{font-size:0.93rem;font-weight:700;color:var(--navy);margin-bottom:0.45rem}
        .task-desc{font-size:0.8rem;color:var(--navy);opacity:0.55;margin-bottom:0.75rem;line-height:1.6}
        .task-meta{display:flex;flex-wrap:wrap;gap:0.5rem;align-items:center;margin-bottom:0.9rem}
        .tag{font-size:0.68rem;font-weight:600;letter-spacing:0.8px;padding:0.22rem 0.7rem;border-radius:50px;text-transform:uppercase}
        .tag-deadline{background:#fce8e8;color:#900}
        .tag-deadline.near{background:#e74c3c;color:#fff}
        .tag-status-pending{background:#f0e8f2;color:var(--navy)}
        .tag-status-in_progress{background:#fff8e1;color:#7a5c00}
        .tag-status-completed{background:#e8f5e9;color:#1a6b2a}
        .task-actions{display:flex;gap:0.5rem;flex-wrap:wrap}
        .form-inline{display:inline}
        .btn-sm{font-size:0.74rem;font-weight:600;padding:0.3rem 0.8rem;border-radius:50px;border:1.5px solid;cursor:pointer;font-family:'DM Sans',sans-serif;transition:all 0.18s;background:transparent;text-decoration:none;display:inline-block}
        .btn-edit{border-color:var(--navy);color:var(--navy)}
        .btn-edit:hover{background:var(--navy);color:#fff}
        .btn-progress{border-color:var(--yellow);color:#7a5c00}
        .btn-progress:hover{background:var(--yellow);color:var(--navy)}
        .btn-done{border-color:#27ae60;color:#1a6b2a}
        .btn-done:hover{background:#27ae60;color:#fff}
        .btn-delete{border-color:#e74c3c;color:#900}
        .btn-delete:hover{background:#e74c3c;color:#fff}
        .empty-col{text-align:center;padding:2.5rem 1rem;color:var(--navy);opacity:0.3;font-size:0.84rem}
        .empty-col svg{margin-bottom:0.6rem;opacity:0.4}

        /* Modal */
        .modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,0.35);backdrop-filter:blur(4px);z-index:200;display:none;align-items:center;justify-content:center}
        .modal-overlay.open{display:flex}
        .modal{background:#fff;border-radius:20px;padding:2.2rem;width:100%;max-width:480px;box-shadow:0 20px 60px rgba(0,0,128,0.18)}
        .modal h2{font-family:'Cormorant Garamond',serif;font-size:1.8rem;color:var(--navy);font-weight:700;margin-bottom:1.4rem}
        .fgrp{margin-bottom:1rem}
        .fgrp label{display:block;font-size:0.71rem;font-weight:700;color:var(--navy);letter-spacing:1.5px;text-transform:uppercase;margin-bottom:0.45rem}
        .fgrp input,.fgrp textarea,.fgrp select{width:100%;padding:0.72rem 0.9rem;border:2px solid var(--lav);border-radius:10px;font-family:'DM Sans',sans-serif;font-size:0.88rem;color:var(--navy);outline:none;transition:border-color 0.2s;background:#faf6fb}
        .fgrp input:focus,.fgrp textarea:focus,.fgrp select:focus{border-color:var(--navy)}
        .fgrp textarea{resize:vertical;min-height:80px}
        .modal-actions{display:flex;gap:0.8rem;justify-content:flex-end;margin-top:1.4rem}
        .btn-cancel{background:none;border:2px solid var(--lav);color:var(--navy);padding:0.65rem 1.5rem;border-radius:50px;font-size:0.85rem;font-weight:600;cursor:pointer;font-family:'DM Sans',sans-serif;transition:all 0.2s}
        .btn-cancel:hover{background:var(--lav)}
        .btn-save{background:var(--navy);color:#fff;border:none;padding:0.65rem 1.8rem;border-radius:50px;font-size:0.85rem;font-weight:700;cursor:pointer;font-family:'DM Sans',sans-serif;transition:all 0.2s}
        .btn-save:hover{background:var(--lav);color:var(--navy)}

        .alert-success{background:#e8f5e9;border:1.5px solid #a5d6a7;border-radius:10px;padding:0.8rem 1.2rem;margin-bottom:1.5rem;font-size:0.85rem;color:#1a6b2a}
    </style>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="task-toolbar">
        <div style="font-size:0.85rem;color:var(--navy);opacity:0.55">{{ $tasks->count() }} task{{ $tasks->count() !== 1 ? 's' : '' }} total</div>
        <button class="btn-add" onclick="document.getElementById('addModal').classList.add('open')">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Add Task
        </button>
    </div>

    <div class="columns">
        @foreach(['high' => 'High Priority', 'medium' => 'Medium Priority', 'low' => 'Low Priority'] as $priority => $label)
        @php $filtered = $tasks->where('priority', $priority); @endphp
        <div class="col-{{ $priority == 'medium' ? 'med' : ($priority == 'high' ? 'high' : 'low') }}">
            <div class="col-header">
                <span class="col-dot"></span>
                {{ $label }}
                <span class="col-count">{{ $filtered->count() }}</span>
            </div>

            @forelse($filtered as $task)
            @php $deadline = \Carbon\Carbon::parse($task->deadline); $near = $deadline->diffInDays(now()) <= 2 && $deadline->isFuture(); @endphp
            <div class="task-card status-{{ $task->status }}">
                <div class="task-name">{{ $task->title }}</div>
                @if($task->description)
                    <div class="task-desc">{{ Str::limit($task->description, 80) }}</div>
                @endif
                <div class="task-meta">
                    <span class="tag tag-deadline {{ $near ? 'near' : '' }}">
                        <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="vertical-align:middle;margin-right:2px"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        {{ $deadline->format('d M Y') }}{{ $near ? ' — Soon!' : '' }}
                    </span>
                    <span class="tag tag-status-{{ $task->status }}">{{ ucfirst(str_replace('_', ' ', $task->status)) }}</span>
                </div>
                <div class="task-actions">
                    <a href="/tasks/{{ $task->id }}/edit" class="btn-sm btn-edit">Edit</a>
                    @if($task->status === 'pending')
                        <form class="form-inline" method="POST" action="/tasks/{{ $task->id }}/progress">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn-sm btn-progress">In Progress</button>
                        </form>
                    @endif
                    @if($task->status !== 'completed')
                        <form class="form-inline" method="POST" action="/tasks/{{ $task->id }}/complete">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn-sm btn-done">Done</button>
                        </form>
                    @endif
                    <form class="form-inline" method="POST" action="/tasks/{{ $task->id }}">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-sm btn-delete" onclick="return confirm('Delete this task?')">Delete</button>
                    </form>
                </div>
            </div>
            @empty
            <div class="empty-col">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="3"/><path d="M9 12l2 2 4-4"/></svg>
                <div>No tasks here</div>
            </div>
            @endforelse
        </div>
        @endforeach
    </div>

    <!-- Add Task Modal -->
    <div class="modal-overlay" id="addModal">
        <div class="modal">
            <h2>New Task</h2>
            <form method="POST" action="/tasks">
                @csrf
                <div class="fgrp">
                    <label>Task Title</label>
                    <input type="text" name="title" placeholder="What needs to be done?" required>
                </div>
                <div class="fgrp">
                    <label>Description</label>
                    <textarea name="description" placeholder="Optional details..."></textarea>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                    <div class="fgrp">
                        <label>Priority</label>
                        <select name="priority">
                            <option value="high">High</option>
                            <option value="medium" selected>Medium</option>
                            <option value="low">Low</option>
                        </select>
                    </div>
                    <div class="fgrp">
                        <label>Deadline</label>
                        <input type="date" name="deadline" required>
                    </div>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="document.getElementById('addModal').classList.remove('open')">Cancel</button>
                    <button type="submit" class="btn-save">Save Task</button>
                </div>
            </form>
        </div>
    </div>
</x-layout>