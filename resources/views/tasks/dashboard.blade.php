<x-layout title="Dashboard">
@php
    $hour = (int) now()->format('G');
    $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
    $motivation = $weeklyCompletionPercent > 70
        ? "You're on fire this week — keep that momentum going!"
        : "Every task you finish moves you forward. You've got this!";
@endphp
<style>
.dash-greeting{font-family:'Cormorant Garamond',serif;font-size:2.75rem;font-weight:700;color:var(--navy);line-height:1.15;margin-bottom:0.85rem}
.streak-pill{display:inline-flex;align-items:center;gap:0.35rem;background:#fff;border:1.5px solid #e0e0e0;color:var(--navy);font-size:0.78rem;font-weight:700;padding:0.35rem 0.9rem;border-radius:50px;margin-bottom:2rem;letter-spacing:0.2px}
[data-theme="vintage"] .streak-pill{background:var(--card);border-color:var(--lm)}
.stats-row{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:1.2rem;margin-bottom:2rem}
.stat-card{padding:1.8rem;text-align:center}
.stat-num{font-family:'Cormorant Garamond',serif;font-size:3rem;font-weight:700;line-height:1}
.stat-num.t{color:var(--navy)}.stat-num.c{color:#27ae60}.stat-num.i{color:var(--yellow)}.stat-num.p{color:var(--lav)}
.stat-label{font-size:0.78rem;color:rgba(0,0,128,0.58);margin-top:0.3rem;text-transform:uppercase;letter-spacing:1px;font-weight:700}
[data-theme="vintage"] .stat-label{color:var(--muted-text)}
.weekly-card{padding:1.8rem 2rem;margin-bottom:2.5rem}
.weekly-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:1.5rem;margin:1.2rem 0 1rem}
.weekly-stat .weekly-num{font-family:'Cormorant Garamond',serif;font-size:2.2rem;font-weight:700;color:var(--navy);line-height:1}
.weekly-stat .weekly-label{font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:rgba(0,0,128,0.55);margin-top:0.25rem}
[data-theme="vintage"] .weekly-stat .weekly-label{color:var(--muted-text)}
.weekly-motivation{font-size:0.9rem;color:rgba(0,0,128,0.72);font-style:italic;line-height:1.6;border-top:1px solid var(--lm);padding-top:1rem}
[data-theme="vintage"] .weekly-motivation{color:var(--muted-text)}
.weekly-pct{font-size:0.82rem;font-weight:700;color:var(--lav);margin-top:0.5rem}
.section-title{font-family:'Cormorant Garamond',serif;font-size:1.4rem;font-weight:700;color:var(--navy);margin:0 0 1rem;padding-bottom:0.5rem;border-bottom:2px solid var(--lm)}
.due-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:1rem;margin-bottom:2.5rem}
.due-card{padding:1rem 1.2rem;transition:all 0.2s}
.due-card:hover{transform:translateY(-2px)}
.due-title{font-size:0.9rem;font-weight:700;color:var(--navy);margin-bottom:0.55rem;overflow-wrap:anywhere}
.due-meta{display:flex;flex-wrap:wrap;gap:0.4rem}
.coming-list{display:flex;flex-direction:column;gap:0.65rem;margin-bottom:2.5rem}
.coming-row{display:flex;align-items:center;gap:0.85rem;background:var(--card);border:1.5px solid var(--lm);border-radius:12px;padding:0.85rem 1.1rem;transition:all 0.2s}
.coming-row:hover{border-color:var(--lav);box-shadow:0 6px 18px var(--shadow-card)}
.priority-dot{width:10px;height:10px;border-radius:50%;flex-shrink:0}
.priority-dot.high{background:#e74c3c}
.priority-dot.medium{background:var(--yellow)}
.priority-dot.low{background:var(--lav)}
.coming-name{flex:1;font-size:0.88rem;font-weight:600;color:var(--navy);overflow-wrap:anywhere}
.coming-date{font-size:0.75rem;font-weight:700;color:rgba(0,0,128,0.55);white-space:nowrap}
[data-theme="vintage"] .coming-date{color:var(--muted-text)}
.dash-clear{font-size:0.84rem;color:var(--muted-text);margin-bottom:2.5rem}
.dash-actions{margin-top:0.5rem}
.btn-add{background:var(--sidebar);color:var(--btn-text);border:none;padding:0.85rem 2.2rem;border-radius:50px;font-size:0.92rem;font-weight:700;cursor:pointer;font-family:'DM Sans',sans-serif;display:inline-flex;align-items:center;gap:0.55rem;transition:all 0.2s;text-decoration:none}
.btn-add:hover{background:var(--lav);color:var(--navy)}
.btn-add svg{flex-shrink:0}
.modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,0.4);backdrop-filter:blur(4px);z-index:200;display:none;align-items:center;justify-content:center;padding:1rem}
.modal-overlay.open{display:flex}
.modal{background:var(--card);border-radius:20px;padding:2rem;width:100%;max-width:460px;box-shadow:0 20px 60px var(--shadow-card)}
.modal h2{font-family:'Cormorant Garamond',serif;font-size:1.7rem;color:var(--navy);font-weight:700;margin-bottom:1.3rem}
.row2{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
.modal-actions{display:flex;gap:0.8rem;justify-content:flex-end;margin-top:1.2rem;flex-wrap:wrap}
.dash-ai-widget{position:fixed;right:1.6rem;bottom:1.6rem;z-index:260}
.dash-ai-toggle{width:52px;height:52px;border-radius:50%;border:1.5px solid var(--lm);background:var(--navy);color:#fff;box-shadow:0 10px 28px rgba(0,0,128,0.18);cursor:pointer;display:flex;align-items:center;justify-content:center;transition:transform 0.18s ease,background 0.18s ease,color 0.18s ease}
.dash-ai-toggle:hover,.dash-ai-toggle[aria-expanded="true"]{background:var(--lav);color:var(--navy);transform:translateY(-2px)}
.dash-ai-panel{position:absolute;right:0;bottom:64px;width:min(340px,calc(100vw - 2rem));height:430px;display:none;flex-direction:column;overflow:hidden}
.dash-ai-panel.open{display:flex}
.dash-ai-head{padding:0.9rem 1rem;border-bottom:1px solid var(--lm);display:flex;align-items:center;justify-content:space-between;gap:0.75rem}
.dash-ai-title{font-size:0.9rem;font-weight:800;color:var(--navy);margin:0}
.dash-ai-close{width:28px;height:28px;border-radius:50%;border:1px solid var(--lm);background:transparent;color:var(--navy);cursor:pointer;display:flex;align-items:center;justify-content:center}
.dash-ai-messages{flex:1;overflow-y:auto;padding:1rem;display:flex;flex-direction:column;gap:0.7rem}
.dash-ai-msg{max-width:84%;border-radius:12px;padding:0.65rem 0.8rem;font-size:0.82rem;line-height:1.45;word-break:break-word}
.dash-ai-msg.user{align-self:flex-end;background:var(--lav);color:var(--navy);border-bottom-right-radius:4px}
.dash-ai-msg.assistant{align-self:flex-start;background:var(--surface-field);border:1px solid var(--lm);color:var(--navy);border-bottom-left-radius:4px}
.dash-ai-typing{align-self:flex-start;color:var(--muted-text);font-size:0.78rem;font-weight:700}
.dash-ai-form{border-top:1px solid var(--lm);padding:0.8rem;display:flex;gap:0.5rem;background:var(--card)}
.dash-ai-input{flex:1;min-width:0;border:1px solid var(--lm);border-radius:10px;background:var(--surface-field);color:var(--navy);font-size:0.82rem;padding:0.65rem 0.75rem;outline:none}
.dash-ai-input:focus{border-color:var(--lav)}
.dash-ai-send{padding:0.58rem 0.9rem;border-radius:10px}
@media (max-width:900px){.stats-row{grid-template-columns:repeat(2,minmax(0,1fr))}}
@media (max-width:640px){.stats-row,.weekly-grid,.row2{grid-template-columns:1fr}.dash-greeting{font-size:2.1rem}.dash-ai-widget{right:1rem;bottom:1rem}.dash-ai-panel{height:390px}}
</style>

<h1 class="dash-greeting">{{ $greeting }}, {{ explode(' ', $name)[0] }}</h1>
@if($streak > 0)
<span class="streak-pill">{{ $streak }} day streak</span>
@else
<span class="streak-pill">Start your streak today</span>
@endif

<div class="stats-row">
    <div class="stat-card ui-card">
        <div class="stat-num t">{{ $total }}</div>
        <div class="stat-label">Total Tasks</div>
    </div>
    <div class="stat-card ui-card">
        <div class="stat-num c">{{ $completed }}</div>
        <div class="stat-label">Completed</div>
    </div>
    <div class="stat-card ui-card">
        <div class="stat-num i">{{ $inProgress }}</div>
        <div class="stat-label">In Progress</div>
    </div>
    <div class="stat-card ui-card">
        <div class="stat-num p">{{ $pending }}</div>
        <div class="stat-label">Pending</div>
    </div>
</div>

<div class="weekly-card ui-card">
    <h2 class="section-title" style="margin-top:0;border:none;padding:0">Weekly Summary</h2>
    <div class="weekly-grid">
        <div class="weekly-stat">
            <div class="weekly-num">{{ $completedThisWeek }}</div>
            <div class="weekly-label">Completed this week</div>
        </div>
        <div class="weekly-stat">
            <div class="weekly-num">{{ $overdueCount }}</div>
            <div class="weekly-label">Tasks overdue</div>
        </div>
    </div>
    <p class="weekly-pct">{{ $weeklyCompletionPercent }}% completion rate this week</p>
    <p class="weekly-motivation">{{ $motivation }}</p>
</div>

<h2 class="section-title">Tasks Due Today</h2>
@if($dueToday->count())
<div class="due-grid">
    @foreach($dueToday as $task)
    <div class="due-card ui-card">
        <div class="due-title">{{ $task->title }}</div>
        <div class="due-meta">
            <span class="ui-tag ui-tag-priority-{{ $task->priority }}">{{ ucfirst($task->priority) }}</span>
            <span class="ui-tag ui-tag-status-{{ $task->status }}">{{ ucfirst(str_replace('_', ' ', $task->status)) }}</span>
        </div>
    </div>
    @endforeach
</div>
@else
<p class="dash-clear">You're all clear today!</p>
@endif

<h2 class="section-title">Coming Up</h2>
@if($comingUp->count())
<div class="coming-list">
    @foreach($comingUp as $task)
    <div class="coming-row">
        <span class="priority-dot {{ $task->priority }}"></span>
        <span class="coming-name">{{ $task->title }}</span>
        <span class="coming-date">{{ $task->deadline->format('d M Y') }}</span>
    </div>
    @endforeach
</div>
@else
<p class="dash-clear">No upcoming deadlines in the next 7 days.</p>
@endif

<div class="dash-actions">
    @if(auth()->user()->isMember())
    <a href="{{ route('tasks') }}" class="btn-add">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        View Tasks
    </a>
    @else
    <button type="button" class="btn-add" onclick="document.getElementById('addModal').classList.add('open')">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Add Task
    </button>
    @endif
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
<script>
window.onclick = function(e){
    if(e.target.classList.contains('modal-overlay')) e.target.classList.remove('open');
}
</script>
@endunless

<div class="dash-ai-widget" id="dashAiWidget">
    <div class="dash-ai-panel ui-card" id="dashAiPanel" aria-live="polite">
        <div class="dash-ai-head">
            <h2 class="dash-ai-title">Taskly AI</h2>
            <button type="button" class="dash-ai-close" id="dashAiClose" aria-label="Close AI chat">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="dash-ai-messages" id="dashAiMessages">
            <div class="dash-ai-msg assistant">Hi, I'm Taskly AI. Ask me about your tasks or progress.</div>
        </div>
        <form class="dash-ai-form" id="dashAiForm">
            <input class="dash-ai-input" id="dashAiInput" type="text" placeholder="Ask Taskly AI..." autocomplete="off" required>
            <button type="submit" class="ui-button ui-button-primary dash-ai-send" id="dashAiSend">Send</button>
        </form>
    </div>
    <button type="button" class="dash-ai-toggle" id="dashAiToggle" aria-label="Open AI chat" aria-expanded="false">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true"><path d="M12 3a6 6 0 0 0-6 6v2a6 6 0 0 0 12 0V9a6 6 0 0 0-6-6Z"/><path d="M8 15v2a4 4 0 0 0 8 0v-2"/><path d="M9 10h.01"/><path d="M15 10h.01"/><path d="M10 13h4"/></svg>
    </button>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var panel = document.getElementById('dashAiPanel');
    var toggle = document.getElementById('dashAiToggle');
    var close = document.getElementById('dashAiClose');
    var form = document.getElementById('dashAiForm');
    var input = document.getElementById('dashAiInput');
    var send = document.getElementById('dashAiSend');
    var messages = document.getElementById('dashAiMessages');
    var csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    function setOpen(open) {
        panel.classList.toggle('open', open);
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        toggle.setAttribute('aria-label', open ? 'Close AI chat' : 'Open AI chat');
        if (open) input.focus();
    }

    function appendMessage(role, text) {
        var div = document.createElement('div');
        div.className = 'dash-ai-msg ' + role;
        var body = document.createElement('span');
        body.dataset.messageText = 'true';
        body.textContent = text;
        div.appendChild(body);
        if (role === 'assistant') {
            var cursor = document.createElement('span');
            cursor.dataset.streamCursor = 'true';
            cursor.textContent = '|';
            div.appendChild(cursor);
        }
        messages.appendChild(div);
        messages.scrollTop = messages.scrollHeight;
        return div;
    }

    toggle.addEventListener('click', function () {
        setOpen(!panel.classList.contains('open'));
    });

    close.addEventListener('click', function () {
        setOpen(false);
    });

    form.addEventListener('submit', async function (event) {
        event.preventDefault();
        var text = input.value.trim();
        if (!text) return;

        appendMessage('user', text);
        input.value = '';
        input.disabled = true;
        send.disabled = true;

        var typing = document.createElement('div');
        typing.className = 'dash-ai-typing';
        typing.textContent = 'Taskly AI is typing...';
        messages.appendChild(typing);
        messages.scrollTop = messages.scrollHeight;
        var blink = null;
        var cursor = null;

        try {
            var response = await fetch('/ai/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ message: text })
            });

            if (!response.ok) {
                throw new Error('AI request failed');
            }

            if (!response.body) {
                throw new Error('Streaming is not supported');
            }

            var reader = response.body.getReader();
            var decoder = new TextDecoder();
            var assistant = appendMessage('assistant', '');
            var assistantText = assistant.querySelector('[data-message-text]');
            cursor = assistant.querySelector('[data-stream-cursor]');
            blink = setInterval(function () {
                cursor.style.visibility = cursor.style.visibility === 'hidden' ? 'visible' : 'hidden';
            }, 450);
            var started = false;

            while (true) {
                var result = await reader.read();
                if (result.done) break;

                var chunk = decoder.decode(result.value, { stream: true });
                if (!chunk) continue;

                if (!started) {
                    typing.remove();
                    started = true;
                }

                assistantText.textContent += chunk;
                messages.scrollTop = messages.scrollHeight;
            }

            var finalChunk = decoder.decode();
            if (finalChunk) {
                if (!started) {
                    typing.remove();
                    started = true;
                }
                assistantText.textContent += finalChunk;
            }

            typing.remove();
            clearInterval(blink);
            cursor.remove();
        } catch (error) {
            typing.remove();
            if (blink) clearInterval(blink);
            if (cursor) cursor.remove();
            var errorMessage = appendMessage('assistant', 'Failed to get response. Please try again.');
            errorMessage.querySelector('[data-stream-cursor]')?.remove();
        } finally {
            input.disabled = false;
            send.disabled = false;
            input.focus();
        }
    });
});
</script>
</x-layout>
