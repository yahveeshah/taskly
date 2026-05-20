<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>(function(){try{if(localStorage.getItem('taskly-theme')==='vintage')document.documentElement.setAttribute('data-theme','vintage');}catch(e){}})();</script>
    <title>Login - Taskly</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        html,body{margin:0;padding:0}
        :root{--lav:#C7A0CB;--navy:#000080;--yellow:#F6BE00;--ll:#f0e8f2;--lm:#dcc8de;--card:#fff;--sidebar:#000080;--muted:rgba(0,0,128,0.68)}
        [data-theme="vintage"]{--lav:#D4A853;--navy:#2C1810;--yellow:#D4A853;--ll:#E8D9C0;--lm:#C4B49A;--card:#FAF7F2;--sidebar:#2C1810;--muted:rgba(44,24,16,0.68)}
        body{font-family:'DM Sans',sans-serif;color:var(--navy);min-height:100vh;display:grid;grid-template-columns:1fr 1fr;background-color:var(--ll);background-image:radial-gradient(circle,rgba(199,160,203,0.1) 1px,transparent 1px);background-size:28px 28px;background-attachment:fixed}
        html[data-theme="vintage"] body{background-color:var(--ll);background-image:radial-gradient(circle,rgba(196,180,154,0.22) 1px,transparent 1px);background-size:28px 28px;background-attachment:fixed}
        .left{background:var(--sidebar);padding:4rem;display:flex;flex-direction:column;justify-content:space-between;min-height:100vh}
        .logo{font-family:'Cormorant Garamond',serif;font-size:1.8rem;color:var(--lav);font-weight:700;text-decoration:none}
        .logo span{color:#fff}
        .left-content{margin:auto 0}
        .left h2{font-family:'Cormorant Garamond',serif;font-size:3rem;font-weight:700;line-height:1.1;margin-bottom:1.2rem;color:#fff}
        .left h2 em{color:var(--yellow);font-style:italic}
        .left p{color:var(--lav);line-height:1.9;font-size:0.92rem;opacity:0.85}
        .footer-note{color:rgba(199,160,203,0.45);font-size:0.78rem;letter-spacing:1.5px}
        .right{padding:4rem;display:flex;align-items:center;justify-content:center;background:transparent}
        .form-box{width:100%;max-width:420px}
        .home-link{color:var(--navy);text-decoration:none;font-size:0.82rem;display:inline-flex;align-items:center;gap:0.4rem;margin-bottom:2.5rem;font-weight:500;opacity:0.65;transition:opacity 0.2s}
        .home-link:hover{opacity:1}
        .form-box h1{font-family:'Cormorant Garamond',serif;font-size:2.4rem;font-weight:700;margin-bottom:0.3rem;color:var(--navy)}
        .sub{color:var(--navy);font-size:0.84rem;margin-bottom:2rem;opacity:0.6}
        .form-group{margin-bottom:1.1rem}
        .form-group label{display:block;font-size:0.72rem;font-weight:700;color:var(--navy);letter-spacing:1.5px;text-transform:uppercase;margin-bottom:0.5rem}
        .form-group input{width:100%;padding:0.78rem 1rem;background:var(--card);border:2px solid var(--lav);border-radius:10px;color:var(--navy);font-family:'DM Sans',sans-serif;font-size:0.92rem;outline:none;transition:border-color 0.2s}
        .form-group input:focus{border-color:var(--navy)}
        .btn-submit{width:100%;background:var(--sidebar);color:#fff;border:none;padding:0.88rem;border-radius:50px;font-weight:700;font-size:0.92rem;cursor:pointer;font-family:'DM Sans',sans-serif;transition:all 0.2s;margin-top:0.5rem}
        .btn-submit:hover{background:var(--lav);color:var(--navy)}
        .link{text-align:center;margin-top:1.2rem;font-size:0.82rem;color:var(--muted)}
        .link a{color:var(--navy);text-decoration:none;font-weight:700}
        .error{color:#c0392b;font-size:0.74rem;margin-top:0.4rem}
        .alert-error{background:#fce8e8;border:1.5px solid #e0a0a0;border-radius:10px;padding:0.8rem 1rem;margin-bottom:1.2rem;font-size:0.84rem;color:#a00}
        .flash-banner{background:#fff;border:1.5px solid #e0e0e0;border-radius:50px;box-shadow:0 4px 16px rgba(0,0,128,0.08);color:var(--navy);font-size:0.88rem;font-weight:700;margin:0 auto 1.2rem;max-width:420px;padding:0.75rem 2rem;text-align:center;width:max-content;animation:flash-fade 0.35s ease-in 2.65s forwards}
        @keyframes flash-fade{to{opacity:0}}
        @media (max-width:760px){body{grid-template-columns:1fr}.left{padding:2rem;min-height:100vh}.right{padding:2rem}}
    </style>
</head>
<body>
    <div class="left">
        <a href="/" class="logo">Task<span>ly</span></a>
        <div class="left-content">
            <h2>Welcome <em>back.</em></h2>
            <p>Sign in and pick up right where you left off. Your tasks are waiting.</p>
        </div>
        <div class="footer-note">TASKLY &copy; 2026</div>
    </div>
    <div class="right">
        <div class="form-box">
            <a href="/" class="home-link">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                Back to Home
            </a>
            <h1>Sign In</h1>
            <p class="sub">Enter your credentials to continue.</p>
            @if(session('success'))
                <div class="flash-banner">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert-error">{{ session('error') }}</div>
            @endif
            <form method="POST" action="/login">
                @csrf
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com">
                    @error('email')<p class="error">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Your password">
                    @error('password')<p class="error">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="btn-submit">Sign In</button>
            </form>
            @if(session('success'))
            <script>
                setTimeout(function () {
                    var banner = document.querySelector('.flash-banner');
                    if (banner) banner.remove();
                }, 3000);
            </script>
            @endif
            <p class="link">Don't have an account? <a href="/register">Register</a></p>
        </div>
    </div>
</body>
</html>
