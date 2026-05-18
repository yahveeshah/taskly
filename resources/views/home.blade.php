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
        body{font-family:'DM Sans',sans-serif;background:var(--lavender-light);color:var(--navy);min-height:100vh}
        nav{display:flex;justify-content:space-between;align-items:center;padding:1.2rem 4rem;background:color-mix(in srgb,var(--lavender-light) 92%,transparent);backdrop-filter:blur(12px);border-bottom:1px solid var(--lavender-mid);position:sticky;top:0;z-index:100;gap:1rem}
        .logo{font-family:'Cormorant Garamond',serif;font-size:1.9rem;color:var(--navy);font-weight:700;letter-spacing:1px;text-decoration:none}
        .logo span{color:var(--lavender)}
        .nav-links{display:flex;gap:2rem;align-items:center}
        .nav-links a{color:var(--navy);opacity:0.68;text-decoration:none;font-size:0.88rem;font-weight:500;transition:opacity 0.2s}
        .nav-links a:hover{opacity:1}
        .btn-login{border:1.5px solid var(--navy);color:var(--navy);padding:0.5rem 1.4rem;border-radius:50px;font-size:0.85rem;font-weight:600;text-decoration:none;transition:all 0.2s}
        .btn-login:hover{background:var(--navy);color:#fff}
        .nav-links a.btn-register{background:var(--navy);border:1.5px solid var(--navy);box-shadow:0 6px 16px color-mix(in srgb,var(--navy) 18%,transparent);color:#fff;opacity:1;padding:0.5rem 1.4rem;border-radius:50px;font-size:0.85rem;font-weight:700;text-decoration:none;transition:all 0.2s}
        .nav-links a.btn-register:hover{background:var(--lavender);border-color:var(--lavender);color:var(--navy);opacity:1;transform:translateY(-1px)}
        .logout-btn{background:var(--navy);color:#fff;border:none;padding:0.5rem 1.4rem;border-radius:50px;font-size:0.85rem;font-weight:600;cursor:pointer;font-family:'DM Sans',sans-serif}
        .logout-btn:hover{background:var(--lavender);color:var(--navy)}
        .flash-banner{background:var(--card);border:1px solid var(--lavender-mid);border-left:4px solid var(--yellow);border-radius:12px;color:var(--navy);box-shadow:0 10px 24px color-mix(in srgb,var(--navy) 8%,transparent);font-size:0.88rem;font-weight:700;margin:1.25rem auto 0;max-width:1100px;padding:0.85rem 1rem}
        .hero{padding:9rem 4rem 7rem;max-width:1100px;margin:0 auto;display:grid;grid-template-columns:1fr 1fr;gap:6rem;align-items:center}
        .hero-tag{display:inline-block;background:var(--navy);color:var(--lavender);font-size:0.72rem;letter-spacing:3px;text-transform:uppercase;padding:0.45rem 1.2rem;border-radius:50px;margin-bottom:2rem}
        .hero h1{font-family:'Cormorant Garamond',serif;font-size:5.5rem;line-height:0.95;font-weight:700;color:var(--navy);margin-bottom:2rem}
        .hero h1 em{color:var(--lavender);font-style:italic}
        .hero p{color:var(--muted);font-size:1rem;line-height:1.9;margin-bottom:2.5rem;max-width:420px}
        .hero-actions{display:flex;gap:1rem;align-items:center;flex-wrap:wrap}
        .btn-hero{background:var(--navy);color:#fff;padding:0.9rem 2.5rem;border-radius:50px;font-size:0.92rem;font-weight:600;text-decoration:none;transition:all 0.2s}
        .btn-hero:hover{background:var(--lavender);color:var(--navy)}
        .btn-hero-ghost{color:var(--navy);opacity:0.65;font-size:0.92rem;font-weight:500;text-decoration:none;transition:opacity 0.2s}
        .btn-hero-ghost:hover{opacity:1}
        .hero-right{display:flex;flex-direction:column;gap:1rem}
        .stat-card{background:var(--card);border:1px solid var(--lavender-mid);border-radius:16px;padding:1.8rem 2rem;transition:transform 0.2s}
        .stat-card:hover{transform:translateY(-3px)}
        .stat-card:nth-child(2){margin-left:2rem;background:var(--navy)}
        .stat-card:nth-child(2) .stat-num{color:var(--yellow)}
        .stat-card:nth-child(2) .stat-label{color:var(--lavender);opacity:0.78}
        .stat-num{font-family:'Cormorant Garamond',serif;font-size:3rem;font-weight:700;color:var(--navy);line-height:1}
        .stat-label{font-size:0.82rem;color:var(--muted-soft);margin-top:0.3rem;letter-spacing:0.5px}
        .features{padding:6rem 4rem;max-width:1100px;margin:0 auto}
        .section-label{font-size:0.72rem;letter-spacing:3px;text-transform:uppercase;color:var(--lavender);margin-bottom:0.8rem;font-weight:600}
        .section-title{font-family:'Cormorant Garamond',serif;font-size:3rem;color:var(--navy);font-weight:700;line-height:1.1;margin-bottom:3rem}
        .features-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem}
        .f-card{background:var(--card);border:1px solid var(--lavender-mid);border-radius:16px;padding:2.2rem;transition:all 0.2s;position:relative;overflow:hidden}
        .f-card::before{content:'';position:absolute;top:0;left:0;width:4px;height:100%;background:var(--lavender)}
        .f-card:hover{box-shadow:0 10px 40px rgba(199,160,203,0.2);transform:translateY(-3px)}
        .f-num{font-family:'Cormorant Garamond',serif;font-size:2.5rem;color:var(--lavender-mid);font-weight:700;line-height:1;margin-bottom:1.2rem}
        .f-card h3{font-size:0.95rem;font-weight:700;color:var(--navy);margin-bottom:0.6rem;letter-spacing:0.3px}
        .f-card p{color:var(--muted-mid);font-size:0.85rem;line-height:1.8}
        .cta{background:var(--navy);margin:0 4rem 5rem;border-radius:24px;padding:5rem 4rem;display:grid;grid-template-columns:1fr auto;gap:4rem;align-items:center}
        .cta h2{font-family:'Cormorant Garamond',serif;font-size:3rem;color:#fff;font-weight:700;line-height:1.1}
        .cta h2 em{color:var(--yellow);font-style:italic}
        .cta p{color:var(--lavender);opacity:0.78;margin-top:0.8rem;font-size:0.92rem}
        .btn-cta{background:var(--yellow);color:var(--navy);padding:1rem 3rem;border-radius:50px;font-size:0.95rem;font-weight:700;text-decoration:none;transition:all 0.2s;white-space:nowrap;display:inline-block}
        .btn-cta:hover{background:#fff}
        footer{text-align:center;padding:2rem;color:var(--muted-faint);font-size:0.78rem;letter-spacing:2px;text-transform:uppercase}
        @media (max-width:900px){nav{padding:1rem 1.5rem}.nav-links{gap:1rem;flex-wrap:wrap;justify-content:flex-end}.hero{grid-template-columns:1fr;padding:5rem 1.5rem;gap:3rem}.features{padding:4rem 1.5rem}.features-grid{grid-template-columns:1fr 1fr}.cta{margin:0 1.5rem 4rem;padding:3rem 2rem;grid-template-columns:1fr}.stat-card:nth-child(2){margin-left:0}}
        @media (max-width:620px){nav{align-items:flex-start;flex-direction:column}.hero h1{font-size:4rem}.features-grid{grid-template-columns:1fr}.cta h2{font-size:2.4rem}}
    </style>
</head>
<body>
<nav>
    <a href="/" class="logo">Task<span>ly</span></a>
    <div class="nav-links">
        @auth
            <a href="/tasks">Tasks</a>
            <a href="/progress">Progress</a>
            <a href="/graph">Graph</a>
            <a href="/track">Track</a>
            <form method="POST" action="/logout" style="display:inline">
                @csrf
                <button class="logout-btn">Logout</button>
            </form>
        @else
            <a href="/login" class="btn-login">Login</a>
            <a href="/register" class="btn-register">Register</a>
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
        <div class="f-card"><div class="f-num">01</div><h3>Task Management</h3><p>Create, edit and delete tasks with priorities, deadlines and descriptions all in one place.</p></div>
        <div class="f-card"><div class="f-num">02</div><h3>Progress Cards</h3><p>See completed, ongoing and pending tasks laid out in clean cards with encouraging context.</p></div>
        <div class="f-card"><div class="f-num">03</div><h3>Visual Graph</h3><p>A vertical bar graph gives you an instant snapshot of your productivity at any point.</p></div>
        <div class="f-card"><div class="f-num">04</div><h3>Deadline Alerts</h3><p>Tasks nearing their deadline are automatically flagged so you always know what needs attention.</p></div>
        <div class="f-card"><div class="f-num">05</div><h3>Time Tracking</h3><p>Track how long each task took from start to finish and understand where your time goes.</p></div>
        <div class="f-card"><div class="f-num">06</div><h3>Private &amp; Secure</h3><p>Every task is tied to your account only. Full authentication and authorization built in.</p></div>
    </div>
</section>

<section class="cta">
    <div>
        <h2>Ready to get<br><em>organized?</em></h2>
        <p>Create your free account and take control of your tasks today.</p>
    </div>
    <a href="/register" class="btn-cta">Start Now</a>
</section>

<footer>Taskly &copy; 2026</footer>
</body>
</html>
