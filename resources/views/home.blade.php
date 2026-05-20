<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>(function(){try{if(localStorage.getItem('taskly-theme')==='vintage')document.documentElement.setAttribute('data-theme','vintage');}catch(e){}})();</script>
    <title>Taskly - Stay Organized</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,600;0,700;1,600&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root{--lavender:#C7A0CB;--navy:#000080;--yellow:#F6BE00;--lavender-light:#f0e8f2;--lavender-mid:#dcc8de;--card:#fff;--muted:rgba(0,0,128,0.65);--muted-soft:rgba(0,0,128,0.58);--muted-faint:rgba(0,0,128,0.38);--muted-mid:rgba(0,0,128,0.6)}
        [data-theme="vintage"]{--lavender:#D4A853;--navy:#2C1810;--yellow:#D4A853;--lavender-light:#FAF8F5;--lavender-mid:#C4B49A;--card:#FAF7F2;--muted:rgba(44,24,16,0.65);--muted-soft:rgba(44,24,16,0.58);--muted-faint:rgba(44,24,16,0.38);--muted-mid:rgba(44,24,16,0.6)}
        [data-theme="vintage"] .stat-card:nth-child(1),[data-theme="vintage"] .stat-card:nth-child(3){background:#2C1810;border:1px solid #C4B49A;color:#FAF7F2}
        [data-theme="vintage"] .stat-card:nth-child(1) .stat-num,[data-theme="vintage"] .stat-card:nth-child(1) .stat-label,[data-theme="vintage"] .stat-card:nth-child(3) .stat-num,[data-theme="vintage"] .stat-card:nth-child(3) .stat-label{color:#FAF7F2;opacity:1}
        [data-theme="vintage"] .stat-card:nth-child(2){background:#FAF7F2;border:1px solid #C4B49A;color:#2C1810}
        [data-theme="vintage"] .stat-card:nth-child(2) .stat-num,[data-theme="vintage"] .stat-card:nth-child(2) .stat-label{color:#2C1810;opacity:1}
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'DM Sans',sans-serif;color:var(--navy);min-height:100vh;background-color:var(--lavender-light);background-image:radial-gradient(circle,rgba(199,160,203,0.1) 1px,transparent 1px);background-size:28px 28px;background-attachment:fixed}
        html[data-theme="vintage"] body{background-color:var(--lavender-light);background-image:radial-gradient(circle,rgba(196,180,154,0.22) 1px,transparent 1px);background-size:28px 28px;background-attachment:fixed}
        nav{display:flex;justify-content:space-between;align-items:center;padding:1.2rem 4rem;background:rgba(240,232,242,0.96);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border-bottom:1.5px solid #dcc8de;box-shadow:0 2px 20px rgba(0,0,0,0.06);position:sticky;top:0;z-index:100;gap:1rem}
        html[data-theme="vintage"] nav{background:rgba(250,248,245,0.96);border-bottom-color:#C4B49A}
        .logo{font-family:'Cormorant Garamond',serif;font-size:1.9rem;color:var(--navy);font-weight:700;letter-spacing:1px;text-decoration:none}
        .logo span{color:var(--lavender)}
        .nav-links{display:flex;gap:0.75rem;align-items:center}
        .nav-auth-link{border:1.5px solid #1A0F0A;color:#ffffff;padding:0.55rem 1.6rem;border-radius:8px;font-size:0.85rem;font-weight:600;text-decoration:none;transition:background 0.2s ease;background:#1A0F0A;box-shadow:none;display:inline-flex;align-items:center;justify-content:center}
        .nav-auth-link:hover{background:#2C1810;border-color:#2C1810;color:#ffffff}
        .nav-user-wrap{position:relative}
        .nav-profile-btn{width:42px;height:42px;border-radius:50%;border:1.5px solid var(--lavender-mid);cursor:pointer;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.9rem;color:#fff;font-family:'DM Sans',sans-serif;transition:transform 0.2s ease,box-shadow 0.2s ease}
        .nav-profile-btn:hover{transform:scale(1.04);box-shadow:0 4px 14px rgba(0,0,0,0.12)}
        .nav-profile-btn[aria-expanded="true"]{box-shadow:0 0 0 2px var(--lavender)}
        .nav-dropdown{position:absolute;top:calc(100% + 10px);right:0;min-width:220px;background:#fff;border-radius:14px;box-shadow:0 12px 40px rgba(0,0,0,0.12),0 2px 8px rgba(0,0,0,0.06);border:1px solid var(--lavender-mid);padding:0.65rem 0;opacity:0;visibility:hidden;transform:translateY(-6px);transition:opacity 0.2s ease,visibility 0.2s ease,transform 0.2s ease;z-index:200}
        html[data-theme="vintage"] .nav-dropdown{background:#FAF7F2;border-color:#C4B49A}
        .nav-dropdown.is-open{opacity:1;visibility:visible;transform:translateY(0)}
        .nav-dropdown-header{padding:0.5rem 1rem 0.65rem}
        .nav-dropdown-name{font-size:0.88rem;font-weight:700;color:var(--navy);line-height:1.3}
        .nav-dropdown-email{font-size:0.74rem;color:var(--muted-soft);margin-top:0.2rem;word-break:break-word}
        .nav-dropdown-divider{height:1px;background:var(--lavender-mid);margin:0.45rem 0}
        html[data-theme="vintage"] .nav-dropdown-divider{background:#C4B49A}
        .nav-dropdown a,.nav-dropdown button.nav-dropdown-link{display:block;width:100%;text-align:left;padding:0.55rem 1rem;font-size:0.84rem;font-weight:500;color:var(--navy);text-decoration:none;background:none;border:none;cursor:pointer;font-family:'DM Sans',sans-serif;transition:background 0.15s ease}
        .nav-dropdown a:hover,.nav-dropdown button.nav-dropdown-link:hover{background:rgba(199,160,203,0.12)}
        html[data-theme="vintage"] .nav-dropdown a:hover,html[data-theme="vintage"] .nav-dropdown button.nav-dropdown-link:hover{background:rgba(212,168,83,0.15)}
        .nav-dropdown-theme-row{display:flex;align-items:center;gap:0.5rem;padding:0.55rem 1rem}
        .nav-dropdown-theme-row button.nav-dropdown-link{padding:0.45rem 0.65rem;border-radius:10px;border:1px solid var(--lavender-mid);display:inline-flex;align-items:center;gap:0.45rem;font-size:0.8rem;font-weight:600}
        html[data-theme="vintage"] .nav-dropdown-theme-row button.nav-dropdown-link{border-color:#C4B49A}
        .nav-dropdown-logout{padding:0.55rem 1rem}
        .nav-dropdown-logout button{width:100%;padding:0.55rem;border-radius:10px;border:1px solid rgba(231,76,60,0.35);background:rgba(231,76,60,0.08);color:#c0392b;font-size:0.84rem;font-weight:600;cursor:pointer;font-family:'DM Sans',sans-serif;transition:background 0.15s ease,color 0.15s ease}
        .nav-dropdown-logout button:hover{background:rgba(231,76,60,0.16);color:#a82315}
        .flash-banner{background:#fff;border:1.5px solid #e0e0e0;border-radius:12px;color:var(--navy);box-shadow:0 10px 24px color-mix(in srgb,var(--navy) 8%,transparent);font-size:0.88rem;font-weight:700;margin:1.25rem auto 0;max-width:1100px;padding:0.85rem 1rem}
        html[data-theme="vintage"] .flash-banner{background:var(--card);border-color:#C4B49A}
        .hero{padding:10rem 4rem 8rem;max-width:1100px;margin:0 auto;display:grid;grid-template-columns:1fr 1fr;gap:6rem;align-items:center}
        .hero-tag{display:inline-block;background:var(--navy);color:var(--lavender);font-size:0.72rem;letter-spacing:3px;text-transform:uppercase;padding:0.45rem 1.2rem;border-radius:50px;margin-bottom:1.8rem}
        .hero h1{font-family:'Cormorant Garamond',serif;font-size:5.5rem;line-height:0.95;font-weight:700;color:var(--navy);margin-bottom:1.6rem}
        .hero h1 em{color:var(--lavender);font-style:italic}
        .hero p{color:var(--muted);font-size:1rem;line-height:1.9;margin-bottom:2rem;max-width:420px}
        .hero-actions{display:flex;gap:1rem;align-items:center;flex-wrap:wrap}
        .btn-hero{background:var(--navy);color:#fff;padding:1.1rem 3rem;border-radius:50px;font-size:1rem;font-weight:700;text-decoration:none;transition:all 0.25s ease}
        .btn-hero:hover{background:var(--lavender);color:var(--navy);transform:translateY(-3px);box-shadow:0 8px 24px rgba(0,0,0,0.15)}
        .btn-hero-ghost{color:var(--navy);opacity:0.65;font-size:0.92rem;font-weight:500;text-decoration:none;transition:opacity 0.2s}
        .btn-hero-ghost:hover{opacity:1}
        .hero-right{display:flex;flex-direction:column;gap:1rem}
        .stat-card{background:var(--card);border:1px solid var(--lavender-mid);border-radius:16px;padding:1.8rem 2rem;transition:transform 0.2s ease,box-shadow 0.2s ease}
        .stat-card:hover{transform:translateY(-4px);box-shadow:0 12px 32px rgba(0,0,0,0.1)}
        .stat-card:nth-child(2){margin-left:2rem;background:var(--navy)}
        .stat-card:nth-child(2) .stat-num{color:var(--yellow)}
        .stat-card:nth-child(2) .stat-label{color:var(--lavender);opacity:0.78}
        .stat-num{font-family:'Cormorant Garamond',serif;font-size:3rem;font-weight:700;color:var(--navy);line-height:1}
        .stat-label{font-size:0.82rem;color:var(--muted-soft);margin-top:0.3rem;letter-spacing:0.5px}
        .features{padding:6rem 4rem;max-width:1100px;margin:0 auto}
        .section-label{font-size:0.72rem;letter-spacing:3px;text-transform:uppercase;color:var(--lavender);margin-bottom:0.8rem;font-weight:600}
        .section-title{font-family:'Cormorant Garamond',serif;font-size:3rem;color:var(--navy);font-weight:700;line-height:1.1;margin-bottom:3rem}
        .features-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem}
        .f-card{background:var(--card);border:1px solid var(--lavender-mid);border-radius:16px;padding:2.2rem;transition:transform 0.2s ease,box-shadow 0.2s ease;position:relative}
        .f-card:hover{box-shadow:0 12px 32px rgba(0,0,0,0.1);transform:translateY(-3px)}
        .f-badge{display:inline-block;background:#C7A0CB;color:#fff;font-size:0.7rem;font-weight:700;padding:0.2rem 0.6rem;border-radius:50px;margin-bottom:0.75rem}
        html[data-theme="vintage"] .f-badge{background:#D4A853}
        .f-card h3{font-size:0.95rem;font-weight:700;color:var(--navy);margin-bottom:0.6rem;letter-spacing:0.3px}
        .f-card p{color:var(--muted-mid);font-size:0.85rem;line-height:1.8}
        .cta{background:var(--navy);margin:0;width:100%;border-radius:0;padding:7rem 6rem;display:grid;grid-template-columns:1fr auto;gap:4rem;align-items:center;box-sizing:border-box}
        html[data-theme="vintage"] .cta{background:#2C1810}
        .cta h2{font-family:'Cormorant Garamond',serif;font-size:3.8rem;color:#fff;font-weight:700;line-height:1.1}
        .cta h2 em{color:var(--yellow);font-style:italic}
        .cta p{color:var(--lavender);opacity:0.78;margin-top:0.8rem;font-size:0.92rem}
        .btn-cta{background:var(--yellow);color:var(--navy);padding:1.2rem 4rem;border-radius:50px;font-size:1rem;font-weight:800;text-decoration:none;transition:all 0.25s ease;white-space:nowrap;display:inline-block}
        .btn-cta:hover{background:#fff;transform:translateY(-3px);box-shadow:0 10px 28px rgba(0,0,0,0.18)}
        .site-footer{background:var(--lavender-light);border-top:1px solid var(--lavender-mid);padding:3rem 4rem;margin-top:0}
        .footer-inner{max-width:1100px;margin:0 auto;display:grid;grid-template-columns:1fr auto 1fr;gap:2rem;align-items:start}
        .footer-brand .footer-logo{font-family:'Cormorant Garamond',serif;font-size:1.65rem;font-weight:700;color:var(--navy);text-decoration:none;display:inline-block}
        .footer-brand .footer-logo span{color:var(--lavender)}
        .footer-tagline{font-size:0.82rem;color:var(--muted-soft);margin-top:0.45rem;line-height:1.5;max-width:240px}
        .footer-nav{display:flex;flex-direction:column;gap:0.5rem;align-items:center;text-align:center}
        .footer-nav a{color:var(--navy);text-decoration:none;font-size:0.82rem;font-weight:500;opacity:0.75;transition:opacity 0.2s ease}
        .footer-nav a:hover{opacity:1}
        .footer-copy{text-align:right;font-size:0.78rem;color:var(--muted-soft);line-height:1.5;justify-self:end;max-width:200px}
        @media (max-width:900px){nav{padding:1rem 1.5rem}.hero{grid-template-columns:1fr;padding:7rem 1.5rem 5.5rem;gap:3rem}.features{padding:4rem 1.5rem}.features-grid{grid-template-columns:1fr 1fr}.cta{padding:4rem 2rem;grid-template-columns:1fr}.stat-card:nth-child(2){margin-left:0}.cta h2{font-size:2.8rem}.footer-inner{grid-template-columns:1fr;text-align:center}.footer-nav{align-items:center}.footer-copy{text-align:center;justify-self:center}}
        @media (max-width:620px){nav{align-items:flex-start;flex-direction:column}.hero h1{font-size:4rem}.features-grid{grid-template-columns:1fr}.cta h2{font-size:2.35rem}.cta{padding:3rem 1.5rem}}
    </style>
</head>
<body>
<nav>
    <a href="/" class="logo">Task<span>ly</span></a>
    <div class="nav-links">
        @auth
            @php
                $homeUser = auth()->user();
                $homeAvatarLetter = strtoupper(substr(trim($homeUser->name), 0, 1)) ?: '?';
                $homeAvatarIdx = ctype_alpha($homeAvatarLetter) ? (ord($homeAvatarLetter) - 65) % 8 : 0;
                $homeAvatarPalette = ['#5B4B8A', '#2E6B5E', '#8B4513', '#1E5F8C', '#7B3F61', '#4A6741', '#9B4D2E', '#3D4F7C'];
                $homeProfileBg = $homeAvatarPalette[$homeAvatarIdx];
            @endphp
            <div class="nav-user-wrap" id="navUserWrap">
                <button type="button" class="nav-profile-btn" id="profileMenuBtn" aria-expanded="false" aria-haspopup="true" style="background-color: {{ $homeProfileBg }}">{{ $homeAvatarLetter }}</button>
                <div class="nav-dropdown" id="profileMenu" role="menu">
                    <div class="nav-dropdown-header">
                        <div class="nav-dropdown-name">{{ $homeUser->name }}</div>
                        <div class="nav-dropdown-email">{{ $homeUser->email }}</div>
                    </div>
                    <div class="nav-dropdown-divider" role="presentation"></div>
                    <a href="{{ route('dashboard') }}" role="menuitem">Dashboard</a>
                    <a href="{{ route('tasks') }}" role="menuitem">Tasks</a>
                    <a href="{{ route('profile.edit') }}" role="menuitem">Profile</a>
                    <div class="nav-dropdown-divider" role="presentation"></div>
                    <div class="nav-dropdown-theme-row">
                        <button type="button" class="nav-dropdown-link" id="homeThemeMenuToggle" aria-label="Toggle color theme">
                            <svg id="themeIconSun" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/></svg>
                            <svg id="themeIconMoon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="display:none"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
                            <span id="homeThemeMenuLabel">Lavender theme</span>
                        </button>
                    </div>
                    <div class="nav-dropdown-divider" role="presentation"></div>
                    <div class="nav-dropdown-logout">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <a href="{{ route('login') }}" class="nav-auth-link">Login</a>
            <a href="{{ route('register') }}" class="nav-auth-link">Register</a>
        @endauth
    </div>
</nav>

@if(session('success'))
<div class="flash-banner">{{ session('success') }}</div>
@endif

<section class="hero">
    <div>
        <div class="hero-tag">Your Productivity Hub</div>
        <h1>Work.<br><em>Focus.</em><br>Achieve.</h1>
        <p>Taskly gives you a clean, powerful space to manage your tasks, track your progress and hit every deadline.</p>
        <div class="hero-actions">
            <a href="/register" class="btn-hero">Get Started</a>
            <a href="/login" class="btn-hero-ghost">Sign In</a>
        </div>
    </div>
    <div class="hero-right">
        <div class="stat-card">
            <div class="stat-num">3</div>
            <div class="stat-label">Priority Levels - High, Medium, Low</div>
        </div>
        <div class="stat-card">
            <div class="stat-num">100%</div>
            <div class="stat-label">End-to-end privacy. Always.</div>
        </div>
        <div class="stat-card">
            <div class="stat-num">Live</div>
            <div class="stat-label">Real-time progress tracking and graphs</div>
        </div>
    </div>
</section>

<section class="features">
    <div class="section-label">What You Get</div>
    <div class="section-title">Everything you need<br>to stay on top.</div>
    <div class="features-grid">
        <div class="f-card"><span class="f-badge">01</span><h3>Task Management</h3><p>Create, edit and delete tasks with priorities, deadlines and descriptions all in one place.</p></div>
        <div class="f-card"><span class="f-badge">02</span><h3>Progress Cards</h3><p>See completed, ongoing and pending tasks laid out in clean cards with encouraging context.</p></div>
        <div class="f-card"><span class="f-badge">03</span><h3>Visual Graph</h3><p>A vertical bar graph gives you an instant snapshot of your productivity at any point.</p></div>
        <div class="f-card"><span class="f-badge">04</span><h3>Deadline Alerts</h3><p>Tasks nearing their deadline are automatically flagged so you always know what needs attention.</p></div>
        <div class="f-card"><span class="f-badge">05</span><h3>Time Tracking</h3><p>Track how long each task took from start to finish and understand where your time goes.</p></div>
        <div class="f-card"><span class="f-badge">06</span><h3>Private &amp; Secure</h3><p>Every task is tied to your account only. Full authentication and authorization built in.</p></div>
    </div>
</section>

<section class="cta">
    <div>
        <h2>Ready to get<br><em>organized?</em></h2>
        <p>Create your free account and take control of your tasks today.</p>
    </div>
    <a href="/register" class="btn-cta">Start Now</a>
</section>

<footer class="site-footer">
    <div class="footer-inner">
        <div class="footer-brand">
            <a href="/" class="footer-logo">Task<span>ly</span></a>
            <p class="footer-tagline">Stay organized. Stay ahead.</p>
        </div>
        <nav class="footer-nav" aria-label="Footer">
            <a href="/">Home</a>
            <a href="{{ route('login') }}">Login</a>
            <a href="{{ route('register') }}">Register</a>
        </nav>
        <p class="footer-copy">Taskly 2026. All rights reserved.</p>
    </div>
</footer>

<script>
(function () {
    var root = document.documentElement;
    var storageKey = 'taskly-theme';

    function isVintage() {
        return root.getAttribute('data-theme') === 'vintage';
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
        syncThemeUi();
    }

    function syncThemeUi() {
        var vintage = isVintage();
        var label = document.getElementById('homeThemeMenuLabel');
        var sun = document.getElementById('themeIconSun');
        var moon = document.getElementById('themeIconMoon');
        if (label) {
            label.textContent = vintage ? 'Vintage theme' : 'Lavender theme';
        }
        if (sun && moon) {
            sun.style.display = vintage ? 'none' : 'inline';
            moon.style.display = vintage ? 'inline' : 'none';
        }
    }

    var themeBtn = document.getElementById('homeThemeMenuToggle');
    if (themeBtn) {
        themeBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            applyTheme(isVintage() ? 'light' : 'vintage');
        });
    }
    syncThemeUi();

    var wrap = document.getElementById('navUserWrap');
    var btn = document.getElementById('profileMenuBtn');
    var menu = document.getElementById('profileMenu');
    if (wrap && btn && menu) {
        function closeMenu() {
            menu.classList.remove('is-open');
            btn.setAttribute('aria-expanded', 'false');
        }
        function openMenu() {
            menu.classList.add('is-open');
            btn.setAttribute('aria-expanded', 'true');
        }
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            if (menu.classList.contains('is-open')) {
                closeMenu();
            } else {
                openMenu();
            }
        });
        document.addEventListener('click', function () {
            closeMenu();
        });
        menu.addEventListener('click', function (e) {
            e.stopPropagation();
        });
    }
})();
</script>
</body>
</html>
