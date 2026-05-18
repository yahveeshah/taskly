<x-layout title="{{ $user->name }} Progress">
<style>
.member-summary{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:1rem;margin-bottom:2rem}
.summary-card{padding:1.4rem;text-align:center}
.summary-num{font-family:'Cormorant Garamond',serif;font-size:2.5rem;font-weight:700;line-height:1}
.summary-num.c{color:#27ae60}.summary-num.i{color:var(--yellow)}.summary-num.p{color:var(--lav)}
.summary-label{font-size:0.72rem;font-weight:800;letter-spacing:1px;color:rgba(0,0,128,0.55);text-transform:uppercase;margin-top:0.35rem}
.section-title{font-family:'Cormorant Garamond',serif;font-size:1.4rem;font-weight:700;color:var(--navy);margin:2rem 0 1rem;padding-bottom:0.5rem;border-bottom:2px solid var(--lm)}
.prog-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:1rem}
.prog-card{padding:1.2rem 1.4rem;transition:all 0.2s}
.prog-card.completed{border-left:4px solid #27ae60}
.prog-card.in_progress{border-left:4px solid var(--yellow)}
.prog-card.pending{border-left:4px solid var(--lav)}
.prog-name{font-size:0.9rem;font-weight:700;color:var(--navy);margin-bottom:0.3rem;overflow-wrap:anywhere}
.prog-dl{font-size:0.72rem;color:rgba(0,0,128,0.55);margin-top:0.3rem}
.graph-card{padding:2rem;max-width:680px;margin-top:2rem}
.graph-card h2{font-family:'Cormorant Garamond',serif;font-size:1.6rem;font-weight:700;color:var(--navy);margin-bottom:2rem}
.bar-wrap{display:flex;align-items:flex-end;gap:2rem;min-height:230px;padding:0 1rem;border-bottom:2px solid var(--lm);margin-bottom:1.2rem}
.bar-col{display:flex;flex-direction:column;align-items:center;gap:0.5rem;flex:1;min-width:0}
.bar-stage{flex:1;width:100%;display:flex;align-items:flex-end}
.bar{width:100%;border-radius:10px 10px 0 0;min-height:8px;position:relative}
.bar-val{position:absolute;top:-28px;left:50%;transform:translateX(-50%);font-size:0.9rem;font-weight:700;color:var(--navy)}
.bar-label{font-size:0.78rem;font-weight:700;color:rgba(0,0,128,0.62);text-align:center;margin-top:0.6rem}
@media (max-width:720px){.member-summary{grid-template-columns:1fr}.bar-wrap{gap:1rem;padding:0}}
</style>

@php
    $max = max($completed, $in_progress, $pending, 1);
    $ch = round(($completed / $max) * 200);
    $ih = round(($in_progress / $max) * 200);
    $ph = round(($pending / $max) * 200);
    $encouragements = [
        'completed' => ['Nailed it!', 'Crushed it!', 'Task complete!'],
        'in_progress' => ['Keep pushing!', 'Almost there!', 'Momentum is building!'],
        'pending' => ['Ready when they are!', 'Next up.', 'Ready to start.'],
    ];
@endphp

<div style="margin-bottom:1.2rem">
    <a class="ui-button ui-button-secondary ui-button-sm" href="{{ route('team.index') }}">Back to My Team</a>
</div>

<div class="member-summary">
    <div class="summary-card ui-card"><div class="summary-num c">{{ $completed }}</div><div class="summary-label">Completed</div></div>
    <div class="summary-card ui-card"><div class="summary-num i">{{ $in_progress }}</div><div class="summary-label">In Progress</div></div>
    <div class="summary-card ui-card"><div class="summary-num p">{{ $pending }}</div><div class="summary-label">Pending</div></div>
</div>

@foreach(['completed'=>'Completed','in_progress'=>'In Progress','pending'=>'Pending'] as $status=>$label)
@php $group = $tasks->where('status', $status); @endphp
@if($group->count())
<div class="section-title">{{ $label }}</div>
<div class="prog-grid">
    @foreach($group as $i=>$task)
    <div class="prog-card ui-card {{ $task->status }}">
        <div class="prog-name">{{ $task->title }}</div>
        @if($task->deadline)
        <div class="prog-dl">Due {{ $task->deadline->format('d M Y') }}</div>
        @endif
        <div style="font-size:0.75rem;color:#7a5c00;margin-top:0.5rem;font-style:italic">{{ $encouragements[$status][$i % count($encouragements[$status])] }}</div>
    </div>
    @endforeach
</div>
@endif
@endforeach

@if($tasks->count() === 0)
<div class="ui-empty">No tasks assigned to this member yet.</div>
@endif

<div class="graph-card ui-card">
    <h2>{{ $user->name }}'s Progress Graph</h2>
    <div class="bar-wrap">
        <div class="bar-col"><div class="bar-stage"><div class="bar" style="background:var(--navy);height:{{ $ch }}px"><span class="bar-val">{{ $completed }}</span></div></div><div class="bar-label">Completed</div></div>
        <div class="bar-col"><div class="bar-stage"><div class="bar" style="background:var(--yellow);height:{{ $ih }}px"><span class="bar-val">{{ $in_progress }}</span></div></div><div class="bar-label">In Progress</div></div>
        <div class="bar-col"><div class="bar-stage"><div class="bar" style="background:var(--lav);height:{{ $ph }}px"><span class="bar-val">{{ $pending }}</span></div></div><div class="bar-label">Pending</div></div>
    </div>
</div>
</x-layout>
