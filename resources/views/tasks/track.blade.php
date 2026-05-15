<x-layout title="Track Tasks">
<style>
.track-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1.2rem}
.track-card{padding:1.3rem 1.5rem;transition:all 0.2s}
.track-card:hover{transform:translateY(-2px)}
.track-card.completed{border-left:4px solid #27ae60}
.track-card.in_progress{border-left:4px solid var(--yellow)}
.track-card.pending{border-left:4px solid var(--lav)}
.tc-name{font-size:0.9rem;font-weight:700;color:var(--navy);display:flex;align-items:center;gap:0.4rem;margin-bottom:0.4rem;overflow-wrap:anywhere}
.tc-meta{display:flex;flex-wrap:wrap;gap:0.4rem;margin:0.5rem 0}
.tc-dl{font-size:0.74rem;color:rgba(0,0,128,0.55)}
.tc-dl.overdue{color:#c0392b;font-weight:700}
</style>

@if($tasks->count())
<div class="track-grid">
@foreach($tasks as $task)
@php
$dl = $task->deadline;
$overdue = $dl && $dl->isPast() && $task->status !== 'completed';
@endphp
<div class="track-card ui-card {{ $task->status }}">
    <div class="tc-name">
        @if($task->status === 'completed')
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#27ae60" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
        @endif
        {{ $task->title }}
    </div>
    <div class="tc-meta">
        <span class="ui-tag ui-tag-priority-{{ $task->priority }}">{{ ucfirst($task->priority) }}</span>
        <span class="ui-tag ui-tag-status-{{ $task->status }}">{{ ucfirst(str_replace('_',' ',$task->status)) }}</span>
    </div>
    @if($dl)
    <div class="tc-dl {{ $overdue ? 'overdue' : '' }}">
        {{ $overdue ? 'Overdue - ' : 'Due ' }}{{ $dl->format('d M Y') }}
    </div>
    @endif
</div>
@endforeach
</div>
@else
<div class="ui-empty">No tasks to track yet.</div>
@endif
</x-layout>
