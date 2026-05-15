<x-layout title="My Progress">
<style>
.stats-row{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:1.2rem;margin-bottom:2.5rem}
.stat-card{padding:1.8rem;text-align:center}
.stat-num{font-family:'Cormorant Garamond',serif;font-size:3rem;font-weight:700;line-height:1}
.stat-num.c{color:#27ae60}.stat-num.i{color:var(--yellow)}.stat-num.p{color:var(--lav)}
.stat-label{font-size:0.78rem;color:rgba(0,0,128,0.58);margin-top:0.3rem;text-transform:uppercase;letter-spacing:1px;font-weight:700}
.section-title{font-family:'Cormorant Garamond',serif;font-size:1.4rem;font-weight:700;color:var(--navy);margin:2rem 0 1rem;padding-bottom:0.5rem;border-bottom:2px solid var(--lm)}
.prog-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:1rem}
.prog-card{padding:1.2rem 1.4rem;transition:all 0.2s}
.prog-card:hover{transform:translateY(-2px)}
.prog-card.completed{border-left:4px solid #27ae60}
.prog-card.in_progress{border-left:4px solid var(--yellow)}
.prog-card.pending{border-left:4px solid var(--lav)}
.prog-name{font-size:0.9rem;font-weight:700;color:var(--navy);margin-bottom:0.3rem;display:flex;align-items:center;gap:0.4rem;overflow-wrap:anywhere}
.prog-enc{font-size:0.75rem;color:#7a5c00;margin-top:0.5rem;font-style:italic}
.prog-dl{font-size:0.72rem;color:rgba(0,0,128,0.55);margin-top:0.3rem}
.graph-link{margin-top:2rem}
@media (max-width:720px){.stats-row{grid-template-columns:1fr}}
</style>

@php
$completed = $tasks->where('status','completed');
$in_progress = $tasks->where('status','in_progress');
$pending = $tasks->where('status','pending');
$encouragements = [
    'completed' => ['Nailed it!', 'Crushed it!', 'One down, greatness up!', 'Champion move!', 'Absolutely smashing!', 'Task complete!'],
    'in_progress' => ['Keep pushing!', 'Almost there!', 'You have got this!', 'Momentum is building!', 'Stay focused!', 'In the zone!'],
    'pending' => ['Ready when you are!', 'Next up.', 'Waiting to shine.', 'Queued for action.', 'Your moment soon.', 'Ready to start.'],
];
@endphp

<div class="stats-row">
    <div class="stat-card ui-card">
        <div class="stat-num c">{{ $completed->count() }}</div>
        <div class="stat-label">Completed</div>
    </div>
    <div class="stat-card ui-card">
        <div class="stat-num i">{{ $in_progress->count() }}</div>
        <div class="stat-label">In Progress</div>
    </div>
    <div class="stat-card ui-card">
        <div class="stat-num p">{{ $pending->count() }}</div>
        <div class="stat-label">Pending</div>
    </div>
</div>

@foreach(['completed'=>'Completed','in_progress'=>'In Progress','pending'=>'Pending'] as $status=>$label)
@php $group = $tasks->where('status', $status); @endphp
@if($group->count())
<div class="section-title">{{ $label }}</div>
<div class="prog-grid">
    @foreach($group as $i=>$task)
    <div class="prog-card ui-card {{ $task->status }}">
        <div class="prog-name">
            @if($task->status === 'completed')
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#27ae60" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
            @endif
            {{ $task->title }}
        </div>
        @if($task->deadline)
        <div class="prog-dl">Due {{ $task->deadline->format('d M Y') }}</div>
        @endif
        <div class="prog-enc">{{ $encouragements[$status][$i % count($encouragements[$status])] }}</div>
    </div>
    @endforeach
</div>
@endif
@endforeach

@if($tasks->count() === 0)
<div class="ui-empty">No tasks yet. Add some from the Tasks page.</div>
@endif

<a href="{{ route('graph') }}" class="ui-button ui-button-primary graph-link">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
    Your Progress Graph
</a>
</x-layout>
