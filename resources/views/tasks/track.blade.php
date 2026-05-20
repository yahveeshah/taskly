<x-layout title="Track Tasks">
<style>
.track-page{max-width:100%}
.track-heading{font-family:'Cormorant Garamond',serif;font-size:1.85rem;font-weight:700;color:var(--navy);margin-bottom:1.5rem;line-height:1.2}
.track-card{background:var(--card);border:1.5px solid var(--lm);border-radius:16px;box-shadow:0 8px 24px var(--shadow-card);overflow:hidden}
.track-table-wrap{overflow-x:auto;width:100%}
.track-table{width:100%;border-collapse:collapse;font-family:'DM Sans',sans-serif;font-size:0.88rem;color:var(--navy)}
.track-table thead th{background:rgba(199,160,203,0.14);color:var(--navy);font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;text-align:left;padding:0.9rem 1.15rem;border-bottom:1px solid var(--lm);white-space:nowrap}
[data-theme="vintage"] .track-table thead th{background:rgba(196,180,154,0.35)}
.track-table tbody td{padding:0.95rem 1.15rem;border-bottom:1px solid var(--lm);vertical-align:middle}
.track-table tbody tr:nth-child(even){background:rgba(199,160,203,0.06)}
[data-theme="vintage"] .track-table tbody tr:nth-child(even){background:rgba(196,180,154,0.12)}
.track-table tbody tr{transition:background 0.15s ease}
.track-table tbody tr:hover{background:rgba(199,160,203,0.14)}
[data-theme="vintage"] .track-table tbody tr:hover{background:rgba(212,168,83,0.18)}
.track-table tbody tr:last-child td{border-bottom:none}
.track-name{font-weight:600;color:var(--navy)}
.track-muted{color:var(--muted-text)}
.track-priority{display:inline-flex;align-items:center;gap:0.45rem;font-weight:600;font-size:0.82rem;text-transform:capitalize}
.track-priority-dot{width:8px;height:8px;border-radius:50%;flex-shrink:0}
.track-priority-high .track-priority-dot{background:#c0392b}
.track-priority-high{color:#900}
.track-priority-medium .track-priority-dot{background:#F6BE00}
[data-theme="vintage"] .track-priority-medium .track-priority-dot{background:#D4A853}
.track-priority-medium{color:var(--warning-text)}
.track-priority-low .track-priority-dot{background:#C7A0CB}
[data-theme="vintage"] .track-priority-low .track-priority-dot{background:#C7A0CB}
.track-priority-low{color:var(--navy)}
.track-pill{border-radius:50px;display:inline-flex;align-items:center;font-size:0.67rem;font-weight:700;letter-spacing:0.6px;line-height:1;padding:0.3rem 0.7rem;text-transform:uppercase}
.track-pill-completed{background:#e8f5e9;color:#1a6b2a}
.track-pill-in_progress{background:#fff8e1;color:var(--warning-text)}
.track-pill-pending{background:var(--ll);color:var(--navy)}
.track-empty{text-align:center;padding:3rem 1.5rem;color:var(--muted-text);font-size:0.9rem}
</style>

<div class="track-page">
    <h2 class="track-heading">Task Timeline</h2>

    @if($tasks->count())
    <div class="track-card">
        <div class="track-table-wrap">
            <table class="track-table">
                <thead>
                    <tr>
                        <th>Task Name</th>
                        <th>Priority</th>
                        <th>Started At</th>
                        <th>Finished At</th>
                        <th>Time Taken</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tasks as $task)
                    @php
                        $startedAt = $task->created_at;
                        $finishedAt = $task->status === 'completed' ? $task->updated_at : null;
                        $timeTaken = '—';
                        if ($task->status === 'completed' && $startedAt && $finishedAt) {
                            $diff = $startedAt->diff($finishedAt);
                            $parts = [];
                            if ($diff->d > 0) {
                                $parts[] = $diff->d . ' day' . ($diff->d === 1 ? '' : 's');
                            }
                            if ($diff->h > 0) {
                                $parts[] = $diff->h . ' hr' . ($diff->h === 1 ? '' : 's');
                            }
                            if ($diff->i > 0 && $diff->d === 0) {
                                $parts[] = $diff->i . ' mins';
                            }
                            $timeTaken = $parts !== [] ? implode(' ', $parts) : 'Less than 1 min';
                        } elseif ($task->status === 'in_progress') {
                            $timeTaken = 'In progress';
                        } elseif ($task->status === 'pending') {
                            $timeTaken = 'Pending';
                        }
                    @endphp
                    <tr>
                        <td class="track-name">{{ $task->title }}</td>
                        <td>
                            <span class="track-priority track-priority-{{ $task->priority }}">
                                <span class="track-priority-dot" aria-hidden="true"></span>
                                {{ ucfirst($task->priority) }}
                            </span>
                        </td>
                        <td>{{ $startedAt ? $startedAt->format('d M Y, h:i A') : '—' }}</td>
                        <td>{{ $finishedAt ? $finishedAt->format('d M Y, h:i A') : '—' }}</td>
                        <td>{{ $timeTaken }}</td>
                        <td>
                            <span class="track-pill track-pill-{{ $task->status }}">
                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="track-card">
        <p class="track-empty">No tasks to show in your timeline yet.</p>
    </div>
    @endif
</div>
</x-layout>
