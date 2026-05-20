<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>(function(){try{if(localStorage.getItem('taskly-theme')==='vintage')document.documentElement.setAttribute('data-theme','vintage');if(localStorage.getItem('taskly-sidebar')==='collapsed')document.documentElement.classList.add('sidebar-collapsed');}catch(e){}})();</script>
    <title>{{ $title ?? 'Taskly' }} - Taskly</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root{
            --lav:#C7A0CB;--navy:#000080;--yellow:#F6BE00;--ll:#f0e8f2;--lm:#dcc8de;--sidebar-w:220px;--navbar-h:56px;
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
        body{font-family:'DM Sans',sans-serif;color:var(--navy);min-height:100vh;display:flex;flex-direction:column;background-color:var(--ll);background-image:radial-gradient(circle,rgba(199,160,203,0.1) 1px,transparent 1px);background-size:28px 28px;background-attachment:fixed}

        .app-shell{display:flex;flex:1;min-height:0;width:100%;position:relative}
        html[data-theme="vintage"] body{background-color:var(--ll);background-image:radial-gradient(circle,rgba(196,180,154,0.22) 1px,transparent 1px);background-size:28px 28px;background-attachment:fixed}
        a,button,input,textarea,select{font-family:'DM Sans',sans-serif}
        html.sidebar-collapsed{--sidebar-w:64px}
        .sidebar{width:var(--sidebar-w);background:var(--sidebar);min-height:100vh;height:100vh;display:flex;flex-direction:column;padding:2rem 1rem;position:fixed;top:0;left:0;bottom:0;transition:width 0.25s ease;overflow:visible;z-index:150}
        .sidebar-head{display:flex;align-items:center;justify-content:space-between;gap:0.35rem;margin-bottom:2.5rem;min-height:2rem;position:relative}
        .sidebar-logo{font-family:'Cormorant Garamond',serif;font-size:1.6rem;color:var(--lav);font-weight:700;text-decoration:none;display:block;padding:0 0.25rem;flex:1;min-width:0;white-space:nowrap;overflow:hidden}
        .sidebar-logo .logo-accent{color:var(--highlight)}
        .sidebar-logo .logo-mini{display:none;font-size:1.55rem;color:var(--lav);text-align:center;width:100%}
        html.sidebar-collapsed .sidebar-logo .logo-full{display:none}
        html.sidebar-collapsed .sidebar-logo .logo-mini{display:block}
        html.sidebar-collapsed .sidebar{padding:2rem 0.65rem}
        html.sidebar-collapsed .sidebar-head{flex-direction:column;align-items:center;margin-bottom:1.5rem}
        html.sidebar-collapsed .sidebar-collapse-btn{position:static;margin-top:0.5rem}
        html.sidebar-collapsed .sidebar-logo{padding:0;text-align:center}
        .sidebar-collapse-btn{position:absolute;top:0;right:0;width:26px;height:26px;border-radius:8px;border:1px solid var(--sidebar-divider);background:rgba(255,255,255,0.08);color:var(--lav);cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:background 0.2s ease,color 0.2s ease,transform 0.25s ease}
        .sidebar-collapse-btn:hover{background:var(--nav-hover-bg);color:var(--highlight)}
        .sidebar-collapse-btn svg{transition:transform 0.25s ease}
        html.sidebar-collapsed .sidebar-collapse-btn svg{transform:rotate(180deg)}
        .sidebar nav{display:flex;flex-direction:column;gap:0.3rem;flex:1}
        .sidebar nav a{color:var(--nav-fade);text-decoration:none;padding:0.75rem 1rem;border-radius:10px;font-size:0.88rem;font-weight:500;display:flex;align-items:center;gap:0.7rem;transition:background 0.2s ease,color 0.2s ease,padding 0.25s ease}
        .sidebar nav a svg{flex-shrink:0}
        .sidebar nav a .nav-label{white-space:nowrap;overflow:hidden;transition:opacity 0.2s ease,width 0.25s ease}
        html.sidebar-collapsed .sidebar nav a{justify-content:center;padding:0.75rem;gap:0}
        html.sidebar-collapsed .sidebar nav a .nav-label{position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0}
        html.sidebar-collapsed .sidebar nav a{position:relative}
        html.sidebar-collapsed .sidebar nav a::after{content:attr(data-tooltip);position:absolute;left:calc(100% + 10px);top:50%;transform:translateY(-50%);background:var(--card);color:var(--navy);border:1px solid var(--lm);padding:0.35rem 0.65rem;border-radius:8px;font-size:0.75rem;font-weight:600;white-space:nowrap;opacity:0;visibility:hidden;pointer-events:none;transition:opacity 0.15s ease,visibility 0.15s ease;box-shadow:0 6px 18px rgba(0,0,0,0.12);z-index:400}
        html.sidebar-collapsed .sidebar nav a:hover::after,html.sidebar-collapsed .sidebar nav a:focus-visible::after{opacity:1;visibility:visible}
        [data-theme="vintage"] html.sidebar-collapsed .sidebar nav a::after{background:#FAF7F2;border-color:#C4B49A;color:#2C1810}
        .sidebar nav a:hover,.sidebar nav a.active{background:var(--nav-hover-bg);color:var(--highlight)}
        .sidebar-bottom{margin-top:auto;border-top:1px solid var(--sidebar-divider);padding-top:1.2rem}
        html.sidebar-collapsed .sidebar-bottom .avatar-row{display:none}
        html.sidebar-collapsed .sidebar-bottom .btn-label{display:none}
        html.sidebar-collapsed .sidebar-bottom .btn-theme{justify-content:center;padding:0.48rem}
        html.sidebar-collapsed .sidebar-bottom .btn-logout{padding:0.55rem;display:inline-flex;align-items:center;justify-content:center}
        .btn-logout .logout-icon{display:none}
        html.sidebar-collapsed .sidebar-bottom .btn-logout .logout-icon{display:block}
        .avatar-row{display:flex;align-items:center;gap:0.7rem;margin-bottom:0.8rem}
        .avatar{width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:0.9rem;flex-shrink:0}
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
        .btn-logout{width:100%;background:rgba(231,76,60,0.15);color:#e74c3c;border:1.5px solid rgba(231,76,60,0.3);padding:0.6rem;border-radius:8px;font-size:0.82rem;font-weight:600;cursor:pointer;transition:all 0.2s;margin-top:0;display:inline-flex;align-items:center;justify-content:center;gap:0.4rem}
        .btn-logout:hover{background:#e74c3c;color:#fff}
        .main-wrap{margin-left:var(--sidebar-w);flex:1;min-height:100vh;min-width:0;width:100%;display:flex;flex-direction:column;transition:margin-left 0.25s ease}
        .topbar{width:100%;height:56px;min-height:56px;flex-shrink:0;background:var(--topbar);border-bottom:1px solid var(--lm);padding:0 2.5rem;display:flex;align-items:center;justify-content:space-between;gap:1.5rem;position:sticky;top:0;z-index:200}
        [data-theme="vintage"] .topbar{background:#FAF7F2}
        .page-title{font-family:'Cormorant Garamond',serif;font-size:1.6rem;font-weight:700;color:var(--navy);line-height:1.2;margin:0;min-width:0;white-space:nowrap}
        .topbar-nav-links{display:flex;align-items:center;gap:0.2rem;flex:1;padding:0 1.5rem}
        .topbar-nav-links a{font-family:'DM Sans',sans-serif;font-size:0.85rem;font-weight:500;color:var(--muted-text);text-decoration:none;padding:0.3rem 0.6rem;border-radius:6px;transition:color 0.18s ease,background 0.18s ease;white-space:nowrap;position:relative}
        .topbar-nav-links a:hover{color:var(--navy);background:rgba(0,0,128,0.06)}
        [data-theme="vintage"] .topbar-nav-links a:hover{background:rgba(44,24,16,0.06)}
        .topbar-nav-links a.active{color:var(--navy);font-weight:600}
        .topbar-nav-links a.active::after{content:'';position:absolute;bottom:-2px;left:0.6rem;right:0.6rem;height:1.5px;background:var(--navy);border-radius:2px}
        [data-theme="vintage"] .topbar-nav-links a.active{color:var(--navy)}
        .nav-user-wrap{position:relative}
        .nav-profile-btn{width:42px;height:42px;border-radius:50%;border:1.5px solid var(--lm);cursor:pointer;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.9rem;color:#fff;font-family:'DM Sans',sans-serif;transition:transform 0.2s ease,box-shadow 0.2s ease}
        .nav-profile-btn:hover{transform:scale(1.04);box-shadow:0 4px 14px rgba(0,0,0,0.12)}
        .nav-profile-btn[aria-expanded="true"]{box-shadow:0 0 0 2px var(--lav)}
        .nav-dropdown{position:absolute;top:calc(100% + 10px);right:0;min-width:220px;background:var(--card);border-radius:14px;box-shadow:0 12px 40px rgba(0,0,0,0.12),0 2px 8px rgba(0,0,0,0.06);border:1px solid var(--lm);padding:0.65rem 0;opacity:0;visibility:hidden;transform:translateY(-6px);transition:opacity 0.2s ease,visibility 0.2s ease,transform 0.2s ease;z-index:300}
        .nav-dropdown.is-open{opacity:1;visibility:visible;transform:translateY(0)}
        .nav-dropdown-header{padding:0.5rem 1rem 0.65rem}
        .nav-dropdown-name{font-size:0.88rem;font-weight:700;color:var(--navy);line-height:1.3}
        .nav-dropdown-email{font-size:0.74rem;color:var(--muted-text);margin-top:0.2rem;word-break:break-word;opacity:0.92}
        .nav-dropdown-divider{height:1px;background:var(--lm);margin:0.45rem 0}
        [data-theme="vintage"] .nav-dropdown-divider{background:#C4B49A}
        .nav-dropdown a,.nav-dropdown button.nav-dropdown-link{display:flex;align-items:center;gap:0.45rem;width:100%;text-align:left;padding:0.55rem 1rem;font-size:0.84rem;font-weight:500;color:var(--navy);text-decoration:none;background:none;border:none;cursor:pointer;font-family:'DM Sans',sans-serif;transition:background 0.15s ease}
        .nav-dropdown a:hover,.nav-dropdown button.nav-dropdown-link:hover{background:rgba(199,160,203,0.12)}
        [data-theme="vintage"] .nav-dropdown a:hover,[data-theme="vintage"] .nav-dropdown button.nav-dropdown-link:hover{background:rgba(212,168,83,0.15)}
        .nav-dropdown-logout{padding:0.55rem 1rem}
        .nav-dropdown-logout button{width:100%;padding:0.55rem;border-radius:10px;border:1px solid rgba(231,76,60,0.35);background:rgba(231,76,60,0.08);color:#c0392b;font-size:0.84rem;font-weight:600;cursor:pointer;font-family:'DM Sans',sans-serif;transition:background 0.15s ease,color 0.15s ease}
        .nav-dropdown-logout button:hover{background:rgba(231,76,60,0.16);color:#a82315}
        .content{padding:2.5rem;padding-top:2.5rem;opacity:0;flex:1;width:100%}
        .content.page-enter-active{opacity:1;transition:opacity 0.3s ease}
        .content.page-leave{opacity:0!important;transition:opacity 0.15s ease!important}
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
        .flash-banner{background:#fff;border:1.5px solid #e0e0e0;border-radius:50px;box-shadow:0 4px 16px var(--shadow-flash);color:var(--navy);font-size:0.88rem;font-weight:700;margin:1rem auto;max-width:420px;padding:0.75rem 2rem;text-align:center;width:max-content;animation:flash-fade 0.35s ease-in 2.65s forwards}
        @keyframes flash-fade{to{opacity:0}}

        @media (max-width:900px){
            body{display:flex;flex-direction:column}
            .app-shell{flex-direction:column}
            html.sidebar-collapsed{--sidebar-w:100%}
            .sidebar{position:static;width:100%;min-height:auto;height:auto;top:auto;padding:1rem;transition:none}
            .sidebar-collapse-btn{display:none}
            .sidebar-head{margin-bottom:1rem}
            .sidebar-logo .logo-full{display:inline!important}
            .sidebar-logo .logo-mini{display:none!important}
            .sidebar nav{display:grid;grid-template-columns:repeat(5,1fr);gap:0.5rem}
            .sidebar nav a{justify-content:center;padding:0.65rem 0.5rem}
            .sidebar nav a .nav-label{position:static;width:auto;height:auto;margin:0;clip:auto;overflow:visible}
            .sidebar nav a::after{display:none!important}
            .sidebar-bottom .avatar-details,.sidebar-bottom .sidebar-edit,.sidebar-bottom .btn-label{display:block!important}
            .sidebar-bottom{display:grid;grid-template-columns:1fr auto;align-items:center;gap:1rem;margin-top:1rem}
            .main-wrap{margin-left:0;transition:none}
            .topbar{height:auto;min-height:56px;padding:0.75rem 1.25rem;flex-wrap:wrap;gap:0.75rem}
            .topbar-nav-links{flex-wrap:wrap;padding:0 0.25rem;gap:0.1rem}
            .page-title{font-size:1.35rem}
            .content{padding:1.25rem}
        }
        @media (max-width:640px){
            .sidebar nav{grid-template-columns:repeat(2,1fr)}
            .sidebar-bottom{grid-template-columns:1fr}
            .topbar{gap:0.5rem;padding:0.75rem 1rem;height:auto;min-height:56px}
            .page-title{font-size:1.25rem}
            .topbar-nav-links{display:none}
            .content{padding:1rem}
        }
    </style>
</head>
<body>
@php
    $layoutUser = auth()->user();
    $avatarLetter = strtoupper(substr(trim($layoutUser->name), 0, 1)) ?: '?';
    $avatarIndex = ctype_alpha($avatarLetter) ? (ord($avatarLetter) - 65) % 8 : 0;
    $avatarPalette = ['#5B4B8A', '#2E6B5E', '#8B4513', '#1E5F8C', '#7B3F61', '#4A6741', '#9B4D2E', '#3D4F7C'];
    $avatarBg = $avatarPalette[$avatarIndex];
@endphp

<div class="app-shell">
<div class="sidebar" id="appSidebar">
    <div class="sidebar-head">
        <a href="{{ route('dashboard') }}" class="sidebar-logo">
            <span class="logo-full">Task<span class="logo-accent">ly</span></span>
            <span class="logo-mini">T</span>
        </a>
        <button type="button" class="sidebar-collapse-btn" id="sidebarCollapseBtn" aria-label="Toggle sidebar" aria-expanded="true">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="15 18 9 12 15 6"/></svg>
        </button>
    </div>
    <nav>
        <a href="{{ route('dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}" data-tooltip="Dashboard">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
            <span class="nav-label">Dashboard</span>
        </a>
        <a href="/tasks" class="{{ request()->is('tasks') ? 'active' : '' }}" data-tooltip="Tasks">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="18" height="18" rx="3"/><path d="M9 12l2 2 4-4"/></svg>
            <span class="nav-label">Tasks</span>
        </a>
        <a href="/progress" class="{{ request()->is('progress') ? 'active' : '' }}" data-tooltip="Progress">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            <span class="nav-label">Progress</span>
        </a>
        <a href="/graph" class="{{ request()->is('graph') ? 'active' : '' }}" data-tooltip="Graph">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
            <span class="nav-label">Graph</span>
        </a>
        <a href="/track" class="{{ request()->is('track') ? 'active' : '' }}" data-tooltip="Track">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            <span class="nav-label">Track</span>
        </a>
        @if(auth()->user()->isManager())
        <a href="{{ route('team.index') }}" class="{{ request()->is('team*') ? 'active' : '' }}" data-tooltip="My Team">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            <span class="nav-label">My Team</span>
        </a>
        @endif
        <a href="/profile/edit" class="{{ request()->is('profile/edit') ? 'active' : '' }}" data-tooltip="Profile">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg>
            <span class="nav-label">Profile</span>
        </a>
    </nav>
    <div class="sidebar-bottom">
        <div class="avatar-row">
            <div class="avatar" style="background-color:{{ $avatarBg }}">{{ $avatarLetter }}</div>
            <div class="avatar-details">
                <div class="uname">{{ $layoutUser->name }}</div>
                <div class="uemail">{{ $layoutUser->email }}</div>
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
            <span class="btn-label" id="themeToggleLabel">Vintage theme</span>
        </button>
        <form method="POST" action="/logout">
            @csrf
            <button type="submit" class="btn-logout" aria-label="Logout">
                <svg class="logout-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                <span class="btn-label">Logout</span>
            </button>
        </form>
    </div>
</div>
<div class="main-wrap">
    <header class="topbar">
        <h1 class="page-title">{{ $title ?? 'Dashboard' }}</h1>
        <nav class="topbar-nav-links" aria-label="Main navigation">
            <a href="/" class="{{ request()->is('/') ? 'active' : '' }}">Home</a>
            <a href="{{ route('dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('tasks') }}" class="{{ request()->is('tasks') ? 'active' : '' }}">Tasks</a>
            <a href="/progress" class="{{ request()->is('progress') ? 'active' : '' }}">Progress</a>
            <a href="/graph" class="{{ request()->is('graph') ? 'active' : '' }}">Graph</a>
            <a href="/track" class="{{ request()->is('track') ? 'active' : '' }}">Track</a>
        </nav>
        <div class="nav-user-wrap" id="layoutNavUserWrap">
            <button type="button" class="nav-profile-btn" id="layoutProfileMenuBtn" aria-expanded="false" aria-haspopup="true" style="background-color: {{ $avatarBg }}">{{ $avatarLetter }}</button>
            <div class="nav-dropdown" id="layoutProfileMenu" role="menu">
                <div class="nav-dropdown-header">
                    <div class="nav-dropdown-name">{{ $layoutUser->name }}</div>
                    <div class="nav-dropdown-email">{{ $layoutUser->email }}</div>
                </div>
                <div class="nav-dropdown-divider" role="presentation"></div>
                <a href="{{ route('dashboard') }}" role="menuitem">Dashboard</a>
                <a href="{{ route('tasks') }}" role="menuitem">Tasks</a>
                <a href="{{ route('profile.edit') }}" role="menuitem">Profile</a>
                <div class="nav-dropdown-divider" role="presentation"></div>
                <button type="button" class="nav-dropdown-link" id="layoutDropdownThemeToggle" aria-label="Toggle color theme">
                    <svg id="layoutThemeIconSun" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/></svg>
                    <svg id="layoutThemeIconMoon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="display:none"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
                    <span id="layoutThemeMenuLabel">Lavender theme</span>
                </button>
                <div class="nav-dropdown-divider" role="presentation"></div>
                <div class="nav-dropdown-logout">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </header>
    @if(session('success'))
    <div class="flash-banner" id="flashBanner">{{ session('success') }}</div>
    @endif
    <div class="content" id="pageContent">{{ $slot }}</div>
</div>
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

    function syncDropdownThemeUi() {
        var vintage = currentTheme() === 'vintage';
        var menuLabel = document.getElementById('layoutThemeMenuLabel');
        var sun = document.getElementById('layoutThemeIconSun');
        var moon = document.getElementById('layoutThemeIconMoon');
        if (menuLabel) {
            menuLabel.textContent = vintage ? 'Vintage theme' : 'Lavender theme';
        }
        if (sun && moon) {
            sun.style.display = vintage ? 'none' : 'inline';
            moon.style.display = vintage ? 'inline' : 'none';
        }
    }

    function applyTheme(theme) {
        if (theme === 'vintage') {
            root.setAttribute('data-theme', 'vintage');
        } else {
            root.removeAttribute('data-theme');
        }
        try {
            localStorage.setItem(storageKey, theme === 'vintage' ? 'vintage' : 'light');
        } catch (e) {}
        if (label) {
            label.textContent = theme === 'vintage' ? 'Lavender theme' : 'Vintage theme';
        }
        syncDropdownThemeUi();
    }

    if (toggle) {
        toggle.addEventListener('click', function () {
            applyTheme(currentTheme() === 'vintage' ? 'light' : 'vintage');
        });
    }

    var dropTheme = document.getElementById('layoutDropdownThemeToggle');
    if (dropTheme) {
        dropTheme.addEventListener('click', function (e) {
            e.stopPropagation();
            applyTheme(currentTheme() === 'vintage' ? 'light' : 'vintage');
        });
    }

    applyTheme(currentTheme());

    var wrap = document.getElementById('layoutNavUserWrap');
    var btn = document.getElementById('layoutProfileMenuBtn');
    var menu = document.getElementById('layoutProfileMenu');
    if (wrap && btn && menu) {
        function closeProfileMenu() {
            menu.classList.remove('is-open');
            btn.setAttribute('aria-expanded', 'false');
        }
        function openProfileMenu() {
            menu.classList.add('is-open');
            btn.setAttribute('aria-expanded', 'true');
        }
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            if (menu.classList.contains('is-open')) {
                closeProfileMenu();
            } else {
                openProfileMenu();
            }
        });
        document.addEventListener('click', function () {
            closeProfileMenu();
        });
        menu.addEventListener('click', function (e) {
            e.stopPropagation();
        });
    }

    var sidebarKey = 'taskly-sidebar';
    var sidebar = document.getElementById('appSidebar');
    var collapseBtn = document.getElementById('sidebarCollapseBtn');

    function setSidebarCollapsed(collapsed) {
        if (collapsed) {
            root.classList.add('sidebar-collapsed');
            if (sidebar) sidebar.classList.add('is-collapsed');
            if (collapseBtn) collapseBtn.setAttribute('aria-expanded', 'false');
        } else {
            root.classList.remove('sidebar-collapsed');
            if (sidebar) sidebar.classList.remove('is-collapsed');
            if (collapseBtn) collapseBtn.setAttribute('aria-expanded', 'true');
        }
        try {
            localStorage.setItem(sidebarKey, collapsed ? 'collapsed' : 'expanded');
        } catch (err) {}
    }

    if (collapseBtn) {
        setSidebarCollapsed(root.classList.contains('sidebar-collapsed'));
        collapseBtn.addEventListener('click', function () {
            setSidebarCollapsed(!root.classList.contains('sidebar-collapsed'));
        });
    }

    var pageContent = document.getElementById('pageContent');
    if (pageContent) {
        requestAnimationFrame(function () {
            pageContent.classList.add('page-enter-active');
        });

        function isInternalNavLink(anchor) {
            var href = anchor.getAttribute('href');
            if (!href || href.charAt(0) === '#') return false;
            if (anchor.target === '_blank' || anchor.hasAttribute('download')) return false;
            if (anchor.closest('form')) return false;
            try {
                var url = new URL(href, window.location.href);
                return url.origin === window.location.origin;
            } catch (err) {
                return false;
            }
        }

        document.addEventListener('click', function (e) {
            var link = e.target.closest('a[href]');
            if (!link || !isInternalNavLink(link)) return;
            if (!link.closest('.sidebar nav') && !link.closest('.main-wrap')) return;
            if (e.defaultPrevented || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey || e.button !== 0) return;

            e.preventDefault();
            var destination = link.href;
            pageContent.classList.add('page-leave');
            setTimeout(function () {
                window.location.href = destination;
            }, 150);
        });
    }
})();
</script>
</body>
</html>
