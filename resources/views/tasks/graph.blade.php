<x-layout title="Progress Graph">
<style>
.graph-card{padding:2.5rem;max-width:680px}
.graph-card h2{font-family:'Cormorant Garamond',serif;font-size:1.8rem;font-weight:700;color:var(--navy);margin-bottom:2rem}
.bar-wrap{display:flex;align-items:flex-end;gap:2rem;min-height:260px;padding:0 1rem;border-bottom:2px solid var(--lm);margin-bottom:1.5rem}
.bar-col{display:flex;flex-direction:column;align-items:center;gap:0.5rem;flex:1;min-width:0}
.bar-stage{flex:1;width:100%;display:flex;align-items:flex-end}
.bar{width:100%;border-radius:10px 10px 0 0;transition:all 0.3s;min-height:8px;position:relative}
.bar-val{position:absolute;top:-28px;left:50%;transform:translateX(-50%);font-size:0.9rem;font-weight:700;color:var(--navy);white-space:nowrap}
.bar-label{font-size:0.78rem;font-weight:700;color:rgba(0,0,128,0.62);text-align:center;margin-top:0.6rem}
.legend{display:flex;gap:1.5rem;flex-wrap:wrap;margin-top:1rem}
.leg{display:flex;align-items:center;gap:0.5rem;font-size:0.8rem;color:rgba(0,0,128,0.72)}
.leg-dot{width:12px;height:12px;border-radius:4px;flex-shrink:0}
.total{font-size:0.82rem;color:rgba(0,0,128,0.58);margin-bottom:1rem}
@media (max-width:560px){.graph-card{padding:1.4rem}.bar-wrap{gap:1rem;padding:0;min-height:220px}.bar-label{font-size:0.7rem}.legend{gap:0.8rem}}
</style>

@php
$total = $completed + $in_progress + $pending;
$max = max($completed, $in_progress, $pending, 1);
$ch = round(($completed / $max) * 220);
$ih = round(($in_progress / $max) * 220);
$ph = round(($pending / $max) * 220);
@endphp

<div class="graph-card ui-card">
    <h2>Your Progress at a Glance</h2>
    <div class="bar-wrap">
        <div class="bar-col">
            <div class="bar-stage">
                <div class="bar" style="background:var(--navy);height:{{ $ch }}px">
                    <span class="bar-val">{{ $completed }}</span>
                </div>
            </div>
            <div class="bar-label">Completed</div>
        </div>
        <div class="bar-col">
            <div class="bar-stage">
                <div class="bar" style="background:var(--yellow);height:{{ $ih }}px">
                    <span class="bar-val">{{ $in_progress }}</span>
                </div>
            </div>
            <div class="bar-label">In Progress</div>
        </div>
        <div class="bar-col">
            <div class="bar-stage">
                <div class="bar" style="background:var(--lav);height:{{ $ph }}px">
                    <span class="bar-val">{{ $pending }}</span>
                </div>
            </div>
            <div class="bar-label">Pending</div>
        </div>
    </div>
    <div class="total">Total: {{ $total }} task{{ $total !== 1 ? 's' : '' }}</div>
    <div class="legend">
        <div class="leg"><div class="leg-dot" style="background:var(--navy)"></div>Completed</div>
        <div class="leg"><div class="leg-dot" style="background:var(--yellow)"></div>In Progress</div>
        <div class="leg"><div class="leg-dot" style="background:var(--lav)"></div>Pending</div>
    </div>
</div>
</x-layout>
