<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>(function(){try{if(localStorage.getItem('taskly-theme')==='vintage')document.documentElement.setAttribute('data-theme','vintage');}catch(e){}})();</script>
    <title>{{ $title ?? 'Taskly' }} - Taskly</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root{
            --lav:#C7A0CB;--navy:#000080;--yellow:#F6BE00;--ll:#f0e8f2;--lm:#dcc8de;
            --sidebar:#000080;--card:#fff;--topbar:#fff;--btn-text:#fff;--highlight:#fff;--surface-field:#faf6fb;
            --nav-fade:rgba(255,255,255,0.68);--nav-hover-bg:rgba(199,160,203,0.22);--sidebar-divider:rgba(199,160,203,0.2);
            --shadow-card:rgba(0,0,128,0.04);--shadow-card-hover:rgba(0,0,128,0.08);--shadow-flash:rgba(0,0,128,0.15);
            --muted-text:rgba(0,0,128,0.38);--warning-text:#7a5c00;
        }
        [data-theme="vintage"]{
            --lav:#D4A853;--navy:#2C1810;--yellow:#D4A853;--ll:#E8D9C0;--lm:#C4B49A;
            --sidebar:#2C1810;--card:#FAF7F2;--topbar:#FAF7F2;--btn-text:#FAF7F2;--highlight:#FFFFFF;--surface-field:#FAF7F2;
            --nav-fade:rgba(250,247,242,0.68);--nav-hover-bg:rgba(212,168,83,0.22);--sidebar-divider:rgba(212,168,83,0.2);
            --shadow-card:rgba(44,24,16,0.08);--shadow-card-hover:rgba(44,24,16,0.14);--shadow-flash:rgba(44,24,16,0.15);
            --muted-text:rgba(44,24,16,0.45);--warning-text:#5c3d0a;
        }
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'DM Sans',sans-serif;background:var(--ll);color:var(--navy);min-height:100vh;display:flex}
        a,button,input,textarea,select{font-family:'DM Sans',sans-serif}
        .sidebar{width:220px;background:var(--sidebar);min-height:100vh;display:flex;flex-direction:column;padding:2rem 1rem;position:fixed;top:0;left:0;bottom:0}
        .sidebar-logo{font-family:'Cormorant Garamond',serif;font-size:1.6rem;color:var(--lav);font-weight:700;text-decoration:none;display:block;margin-bottom:2.5rem;padding:0 0.5rem}
        .sidebar-logo span{color:var(--highlight)}
        .sidebar nav{display:flex;flex-direction:column;gap:0.3rem;flex:1}
        .sidebar nav a{color:var(--nav-fade);text-decoration:none;padding:0.75rem 1rem;border-radius:10px;font-size:0.88rem;font-weight:500;display:flex;align-items:center;gap:0.7rem;transition:all 0.2s}
        .sidebar nav a:hover,.sidebar nav a.active{background:var(--nav-hover-bg);color:var(--highlight)}
        .sidebar-bottom{margin-top:auto;border-top:1px solid var(--sidebar-divider);padding-top:1.2rem}
        .avatar-row{display:flex;align-items:center;gap:0.7rem;margin-bottom:0.8rem}
        .avatar{width:36px;height:36px;background:var(--lav);border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;color:var(--navy);font-size:0.9rem;flex-shrink:0}
        .uname{font-size:0.82rem;font-weight:600;color:var(--highlight);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:120px}
        .uemail{font-size:0.7rem;color:var(--lav);opacity:0.75;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:120px}
        .sidebar-edit{font-size:0.7rem;color:var(--lav);text-decoration:none;display:block;margin-top:0.2rem;opacity:0.82}
        .sidebar-edit:hover{opacity:1}
        .btn-theme{width:100%;background:transparent;color:var(--lav);border:1px solid var(--sidebar-divider);padding:0.48rem 0.65rem;border-radius:8px;font-size:0.72rem;font-weight:500;cursor:pointer;transition:all 0.2s;margin-bottom:0.5rem;display:inline-flex;align-items:center;justify-content:center;gap:0.4rem;letter-spacing:0.2px}
        .btn-theme:hover{background:var(--nav-hover-bg);color:var(--highlight);border-color:var(--lav)}
        .btn-theme svg{flex-shrink:0}
        .btn-theme .icon-vintage{display:none}
        [data-theme="vintage"] .btn-theme .icon-light{display:none}
        [data-theme="vintage"] .btn-theme .icon-vintage{display:block}
        .btn-logout{width:100%;background:rgba(231,76,60,0.15);color:#e74c3c;border:1.5px solid rgba(231,76,60,0.3);padding:0.6rem;border-radius:8px;font-size:0.82rem;font-weight:600;cursor:pointer;transition:all 0.2s;margin-top:0}
        .btn-logout:hover{background:#e74c3c;color:#fff}
        .main-wrap{margin-left:220px;flex:1;min-height:100vh;min-width:0}
        .topbar{background:var(--topbar);border-bottom:1px solid var(--lm);padding:1.2rem 2.5rem;display:flex;align-items:center;justify-content:space-between;gap:1rem}
        .page-title{font-family:'Cormorant Garamond',serif;font-size:1.9rem;font-weight:700;color:var(--navy)}
        .topbar-right{font-size:0.82rem;color:var(--navy);opacity:0.55;white-space:nowrap}
        .content{padding:2.5rem}
        .content .ui-card h2,.content .profile-card h2,.content .graph-card h2,.content .cal-card h2,.content .section-title{font-family:'Cormorant Garamond',serif;font-size:1.6rem;font-weight:700;color:var(--navy)}

        .ui-card{background:var(--card);border:1.5px solid var(--lm);border-radius:14px;box-shadow:0 8px 24px var(--shadow-card)}
        .ui-card:hover{box-shadow:0 10px 28px var(--shadow-card-hover)}
        .ui-button{border:1.5px solid transparent;border-radius:50px;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;gap:0.45rem;font-size:0.84rem;font-weight:700;padding:0.6rem 1.35rem;text-decoration:none;transition:all 0.2s;line-height:1.2}
        .ui-button svg{flex-shrink:0}
        .ui-button-primary{background:var(--sidebar);color:var(--btn-text)}
        .ui-button-primary:hover{background:var(--lav);color:var(--navy);border-color:var(--lav)}
        .ui-button-secondary{background:transparent;border-color:var(--navy);color:var(--navy)}
        .ui-button-secondary:hover{background:var(--sidebar);color:var(--btn-text)}
        .ui-button-warning{background:transparent;border-color:var(--yellow);color:var(--warning-text)}
        .ui-button-warning:hover{background:var(--yellow);color:var(--navy)}
        .ui-button-success{background:transparent;border-color:#27ae60;color:#1a6b2a}
        .ui-button-success:hover{background:#27ae60;color:#fff}
        .ui-button-danger{background:transparent;border-color:#e74c3c;color:#900}
        .ui-button-danger:hover{background:#e74c3c;color:#fff}
        .ui-button-sm{font-size:0.73rem;padding:0.3rem 0.78rem;font-weight:650}
        .ui-tag{border-radius:50px;display:inline-flex;align-items:center;gap:0.25rem;font-size:0.67rem;font-weight:700;letter-spacing:0.7px;line-height:1;padding:0.27rem 0.65rem;text-transform:uppercase}
        .ui-tag-deadline{background:#fce8e8;color:#900}
        .ui-tag-deadline.is-near,.ui-tag-overdue{background:#e74c3c;color:#fff}
        .ui-tag-priority-high{background:#fce8e8;color:#900}
        .ui-tag-priority-medium{background:#fff8e1;color:var(--warning-text)}
        .ui-tag-priority-low{background:#e8f0fe;color:var(--navy)}
        [data-theme="vintage"] .ui-tag-priority-low{background:#EDE4D6;color:var(--navy)}
        .ui-tag-status-pending{background:var(--ll);color:var(--navy)}
        .ui-tag-status-in_progress{background:#fff8e1;color:var(--warning-text)}
        .ui-tag-status-completed{background:#e8f5e9;color:#1a6b2a}
        .ui-alert{border-radius:10px;font-size:0.85rem;font-weight:600;margin-bottom:1.5rem;padding:0.78rem 1.1rem}
        .ui-alert-success{background:#e8f5e9;border:1.5px solid #a5d6a7;color:#1a6b2a}
        .ui-field{margin-bottom:1rem}
        .ui-field label{color:var(--navy);display:block;font-size:0.72rem;font-weight:700;letter-spacing:1.4px;margin-bottom:0.45rem;text-transform:uppercase}
        .ui-field input,.ui-field textarea,.ui-field select{background:var(--surface-field);border:2px solid var(--lav);border-radius:10px;color:var(--navy);font-size:0.88rem;outline:none;padding:0.72rem 0.9rem;transition:border-color 0.2s;width:100%}
        .ui-field input:focus,.ui-field textarea:focus,.ui-field select:focus{border-color:var(--navy)}
        .ui-field textarea{min-height:80px;resize:vertical}
        .ui-empty{text-align:center;padding:2.5rem 1rem;color:var(--muted-text);font-size:0.84rem}
        .flash-banner{background:var(--yellow);border:none;border-radius:50px;box-shadow:0 4px 16px var(--shadow-flash);color:var(--navy);font-size:0.88rem;font-weight:700;margin:1rem auto;max-width:420px;padding:0.75rem 2rem;text-align:center;width:max-content;animation:flash-fade 0.35s ease-in 2.65s forwards}
        @keyframes flash-fade{to{opacity:0}}

        @media (max-width:900px){
            body{display:block}
            .sidebar{position:static;width:100%;min-height:auto;padding:1rem}
            .sidebar-logo{margin-bottom:1rem}
            .sidebar nav{display:grid;grid-template-columns:repeat(5,1fr);gap:0.5rem}
            .sidebar nav a{justify-content:center;padding:0.65rem 0.5rem}
            .sidebar-bottom{display:grid;grid-template-columns:1fr auto;align-items:center;gap:1rem;margin-top:1rem}
            .main-wrap{margin-left:0}
            .topbar{padding:1rem 1.25rem}
            .content{padding:1.25rem}
        }
        @media (max-width:640px){
            .sidebar nav{grid-template-columns:repeat(2,1fr)}
            .sidebar-bottom{grid-template-columns:1fr}
            .topbar{align-items:flex-start;gap:0.3rem;flex-direction:column}
            .page-title{font-size:1.4rem}
            .content{padding:1rem}
        }
    </style>
</head>
<body>
<div class="sidebar">
    <a href="/" class="sidebar-logo">Task<span>ly</span></a>
    <nav>
        <a href="{{ route('dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
            Dashboard
        </a>
        <a href="/tasks" class="{{ request()->is('tasks') ? 'active' : '' }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="18" height="18" rx="3"/><path d="M9 12l2 2 4-4"/></svg>
            Tasks
        </a>
        <a href="/progress" class="{{ request()->is('progress') ? 'active' : '' }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            Progress
        </a>
        <a href="/graph" class="{{ request()->is('graph') ? 'active' : '' }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
            Graph
        </a>
        <a href="/track" class="{{ request()->is('track') ? 'active' : '' }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            Track
        </a>
        @if(auth()->user()->isManager())
        <a href="{{ route('team.index') }}" class="{{ request()->is('team*') ? 'active' : '' }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            My Team
        </a>
        @endif
        <a href="/profile/edit" class="{{ request()->is('profile/edit') ? 'active' : '' }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg>
            Profile
        </a>
    </nav>
    <div class="sidebar-bottom">
        <div class="avatar-row">
            <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div>
                <div class="uname">{{ auth()->user()->name }}</div>
                <div class="uemail">{{ auth()->user()->email }}</div>
                <a href="/profile/edit" class="sidebar-edit">Edit Profile</a>
            </div>
        </div>
        <button type="button" class="btn-theme" id="themeToggle" aria-label="Toggle theme">
            <span class="icon-light" aria-hidden="true">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/></svg>
            </span>
            <span class="icon-vintage" aria-hidden="true">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
            </span>
            <span id="themeToggleLabel">Vintage theme</span>
        </button>
        <form method="POST" action="/logout">
            @csrf
            <button class="btn-logout">Logout</button>
        </form>
    </div>
</div>
<div class="main-wrap">
    <div class="topbar">
        <div class="page-title">{{ $title ?? 'Dashboard' }}</div>
        <div class="topbar-right">{{ now()->format('D, d M Y') }}</div>
    </div>
    @if(session('success'))
    <div class="flash-banner" id="flashBanner">{{ session('success') }}</div>
    @endif
    <div class="content">{{ $slot }}</div>
</div>
@if(session('success'))
<script>
    setTimeout(function () {
        var banner = document.getElementById('flashBanner');
        if (banner) banner.remove();
    }, 3000);
</script>
@endif
<script>
(function () {
    var storageKey = 'taskly-theme';
    var root = document.documentElement;
    var toggle = document.getElementById('themeToggle');
    var label = document.getElementById('themeToggleLabel');

    function currentTheme() {
        return root.getAttribute('data-theme') === 'vintage' ? 'vintage' : 'light';
    }

    function applyTheme(theme) {
        if (theme === 'vintage') {
            root.setAttribute('data-theme', 'vintage');
        } else {
            root.removeAttribute('data-theme');
        }
        try {
            localStorage.setItem(storageKey, theme);
        } catch (e) {}
        if (label) {
            label.textContent = theme === 'vintage' ? 'Lavender theme' : 'Vintage theme';
        }
    }

    if (toggle) {
        toggle.addEventListener('click', function () {
            applyTheme(currentTheme() === 'vintage' ? 'light' : 'vintage');
        });
    }

    applyTheme(currentTheme());
})();
</script>
</body>
</html>
