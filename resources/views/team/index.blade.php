<x-layout title="My Team">
<style>
.team-head{display:flex;justify-content:space-between;align-items:center;gap:1rem;margin-bottom:1.5rem;flex-wrap:wrap}
.team-code{background:var(--yellow);color:var(--navy);border-radius:50px;font-size:0.88rem;font-weight:800;padding:0.65rem 1.2rem;letter-spacing:1px}
.member-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:1rem}
.member-card{padding:1.3rem 1.4rem;text-decoration:none;color:var(--navy);display:block;transition:all 0.2s}
.member-card:hover{transform:translateY(-2px);border-color:var(--lav)}
.member-name{font-size:1rem;font-weight:800;margin-bottom:0.25rem}
.member-email{font-size:0.78rem;color:rgba(0,0,128,0.55);margin-bottom:1rem;overflow-wrap:anywhere}
.stat-line{display:grid;grid-template-columns:repeat(2,1fr);gap:0.45rem;margin-bottom:1rem}
.mini-stat{background:var(--ll);border-radius:10px;padding:0.65rem;text-align:center}
.mini-num{font-family:'Cormorant Garamond',serif;font-size:1.55rem;font-weight:700;line-height:1;color:var(--navy)}
.mini-label{font-size:0.64rem;font-weight:700;color:rgba(0,0,128,0.55);letter-spacing:0.7px;text-transform:uppercase;margin-top:0.2rem}
.bar-track{height:10px;background:var(--ll);border-radius:50px;overflow:hidden;margin-top:0.55rem}
.bar-fill{height:100%;background:var(--navy);border-radius:50px}
.completion{font-size:0.78rem;font-weight:800;color:var(--navy);margin-top:0.6rem}
</style>

<div class="team-head">
    <div>
        <div style="font-size:0.84rem;color:rgba(0,0,128,0.58);font-weight:700">{{ $team->name }}</div>
        <div style="font-size:0.78rem;color:rgba(0,0,128,0.45);margin-top:0.2rem">Share this code with members so they can join your team.</div>
    </div>
    <div class="team-code">{{ $team->code }}</div>
</div>

@if($members->count())
<div class="member-grid">
@foreach($members as $member)
@php
    $total = $member->tasks->count();
    $completed = $member->tasks->where('status', 'completed')->count();
    $inProgress = $member->tasks->where('status', 'in_progress')->count();
    $pending = $member->tasks->where('status', 'pending')->count();
    $percent = $total ? round(($completed / $total) * 100) : 0;
@endphp
    <a class="member-card ui-card" href="{{ route('team.member', $member) }}">
        <div class="member-name">{{ $member->name }}</div>
        <div class="member-email">{{ $member->email }}</div>
        <div class="stat-line">
            <div class="mini-stat"><div class="mini-num">{{ $total }}</div><div class="mini-label">Assigned</div></div>
            <div class="mini-stat"><div class="mini-num">{{ $completed }}</div><div class="mini-label">Completed</div></div>
            <div class="mini-stat"><div class="mini-num">{{ $inProgress }}</div><div class="mini-label">In Progress</div></div>
            <div class="mini-stat"><div class="mini-num">{{ $pending }}</div><div class="mini-label">Pending</div></div>
        </div>
        <div class="bar-track"><div class="bar-fill" style="width:{{ $percent }}%"></div></div>
        <div class="completion">{{ $percent }}% complete</div>
    </a>
@endforeach
</div>
@else
<div class="ui-empty">No members have joined your team yet.</div>
@endif
</x-layout>
