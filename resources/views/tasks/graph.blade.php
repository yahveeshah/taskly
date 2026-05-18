<x-layout title="Progress Graph">
<style>
.graph-card{padding:2.5rem;max-width:680px}
.graph-card h2{font-family:'Cormorant Garamond',serif;font-size:1.8rem;font-weight:700;color:var(--navy);margin-bottom:2rem}
.bar-wrap{display:flex;align-items:flex-end;gap:2rem;min-height:260px;padding:0 1rem;border-bottom:2px solid var(--lm);margin-bottom:1.5rem}
.bar-col{display:flex;flex-direction:column;align-items:center;gap:0.5rem;flex:1;min-width:0}
.bar-stage{flex:1;width:100%;display:flex;align-items:flex-end}
.bar{width:100%;border-radius:10px 10px 0 0;transition:all 0.3s;min-height:12px;position:relative}
.bar-val{position:absolute;top:-28px;left:50%;transform:translateX(-50%);font-size:0.9rem;font-weight:700;color:var(--navy);white-space:nowrap}
.bar-label{font-size:0.78rem;font-weight:700;color:var(--muted-text);text-align:center;margin-top:0.6rem;opacity:0.85}
.legend{display:flex;gap:1.5rem;flex-wrap:wrap;margin-top:1rem}
.leg{display:flex;align-items:center;gap:0.5rem;font-size:0.8rem;color:var(--muted-text)}
.leg-dot{width:12px;height:12px;border-radius:4px;flex-shrink:0}
.total{font-size:0.82rem;color:var(--muted-text);margin-bottom:1rem;opacity:0.85}
.graph-page-grid{display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;align-items:stretch}
.graph-card,.cal-card{height:100%}
@media (max-width:560px){.graph-card{padding:1.4rem}.bar-wrap{gap:1rem;padding:0;min-height:220px}.bar-label{font-size:0.7rem}.legend{gap:0.8rem}}
.cal-card{background:var(--card);border:1.5px solid var(--lm);border-radius:20px;padding:2.5rem;max-width:380px;box-shadow:0 8px 24px var(--shadow-card)}
.cal-card h2{font-family:'Cormorant Garamond',serif;font-size:1.8rem;font-weight:700;color:var(--navy);margin-bottom:1.5rem}
.cal-month{font-size:0.88rem;font-weight:700;color:var(--navy);margin-bottom:1rem;text-align:center}
.cal-weekdays{display:grid;grid-template-columns:repeat(7,1fr);gap:2px;margin-bottom:0.4rem}
.cal-weekdays span{font-size:0.7rem;font-weight:700;color:var(--muted-text);text-align:center;text-transform:uppercase;letter-spacing:0.5px}
.cal-grid{display:grid;grid-template-columns:repeat(7,1fr);gap:2px}
.cal-day{position:relative;display:flex;align-items:center;justify-content:center;min-height:34px;font-size:0.82rem;font-weight:600;color:var(--muted-text)}
.cal-day.empty{pointer-events:none}
.cal-day .cal-num{display:flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:50%;line-height:1}
.cal-day.has-task .cal-num{color:#fff}
.cal-day.has-task.priority-high .cal-num{background:#e74c3c}
.cal-day.has-task.priority-medium .cal-num{background:#F6BE00;color:var(--navy)}
.cal-day.has-task.priority-low .cal-num{background:#C7A0CB;color:var(--navy)}
.cal-day.has-task{cursor:default}
.cal-tooltip{position:absolute;bottom:calc(100% + 6px);left:50%;transform:translateX(-50%);background:var(--card);border:1px solid var(--lm);border-radius:8px;padding:0.4rem 0.6rem;font-size:0.72rem;font-weight:600;color:var(--navy);white-space:nowrap;max-width:200px;white-space:normal;text-align:center;box-shadow:0 4px 12px var(--shadow-card);opacity:0;visibility:hidden;transition:opacity 0.15s,visibility 0.15s;z-index:10;pointer-events:none;line-height:1.35}
.cal-day.has-task:hover .cal-tooltip{opacity:1;visibility:visible}
@media (max-width:560px){.cal-card{padding:1.4rem;max-width:100%}}
</style>

@php
$total = $completed + $in_progress + $pending;
$max = max($completed, $in_progress, $pending, 1);
$ch = round(($completed / $max) * 220);
$ih = round(($in_progress / $max) * 220);
$ph = round(($pending / $max) * 220);
@endphp

<div class="graph-page-grid">
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

<div class="cal-card">
    <h2>Deadline Calendar</h2>
    <div class="cal-month" id="calMonth"></div>
    <div class="cal-weekdays">
        <span>Sun</span><span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span>
    </div>
    <div class="cal-grid" id="calGrid"></div>
</div>
</div>

<script>
const taskDates = {!! $taskDates !!};

(function () {
    const priorityRank = { high: 3, medium: 2, low: 1 };
    const priorityClass = { high: 'priority-high', medium: 'priority-medium', low: 'priority-low' };

    const byDate = {};
    taskDates.forEach(function (item) {
        if (!byDate[item.date]) byDate[item.date] = [];
        byDate[item.date].push(item);
    });

    function highestPriority(tasks) {
        return tasks.reduce(function (best, t) {
            return (priorityRank[t.priority] || 0) > (priorityRank[best] || 0) ? t.priority : best;
        }, 'low');
    }

    const now = new Date();
    const year = now.getFullYear();
    const month = now.getMonth();
    const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

    document.getElementById('calMonth').textContent = monthNames[month] + ' ' + year;

    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const grid = document.getElementById('calGrid');
    grid.innerHTML = '';

    for (let i = 0; i < firstDay; i++) {
        const empty = document.createElement('div');
        empty.className = 'cal-day empty';
        grid.appendChild(empty);
    }

    for (let d = 1; d <= daysInMonth; d++) {
        const dateStr = year + '-' + String(month + 1).padStart(2, '0') + '-' + String(d).padStart(2, '0');
        const cell = document.createElement('div');
        cell.className = 'cal-day';
        const num = document.createElement('span');
        num.className = 'cal-num';
        num.textContent = d;
        cell.appendChild(num);

        const tasks = byDate[dateStr];
        if (tasks && tasks.length) {
            const pri = highestPriority(tasks);
            cell.classList.add('has-task', priorityClass[pri] || 'priority-low');
            const tip = document.createElement('span');
            tip.className = 'cal-tooltip';
            tip.textContent = tasks.map(function (t) { return t.title; }).join(' • ');
            cell.appendChild(tip);
        }

        grid.appendChild(cell);
    }
})();
</script>
</x-layout>
