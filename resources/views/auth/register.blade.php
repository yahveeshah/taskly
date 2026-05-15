<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Taskly</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        :root{--lav:#C7A0CB;--navy:#000080;--yellow:#F6BE00;--lav-light:#f0e8f2}
        body{font-family:'DM Sans',sans-serif;background:var(--lav-light);color:var(--navy);min-height:100vh;display:grid;grid-template-columns:1fr 1fr}
        .left{background:var(--navy);padding:4rem;display:flex;flex-direction:column;justify-content:space-between}
        .logo{font-family:'Cormorant Garamond',serif;font-size:1.8rem;color:var(--lav);font-weight:700;text-decoration:none;display:block}
        .logo span{color:#fff}
        .left-content{margin:auto 0}
        .left h2{font-family:'Cormorant Garamond',serif;font-size:3rem;font-weight:700;line-height:1.1;margin-bottom:1.2rem;color:#fff}
        .left h2 em{color:var(--yellow);font-style:italic}
        .left p{color:var(--lav);line-height:1.9;font-size:0.92rem;opacity:0.85}
        .points{margin-top:2rem;display:flex;flex-direction:column;gap:0.75rem}
        .point{display:flex;align-items:center;gap:0.8rem;color:var(--lav);font-size:0.84rem}
        .point-dot{width:5px;height:5px;border-radius:50%;background:var(--yellow);flex-shrink:0}
        .footer-note{color:rgba(199,160,203,0.45);font-size:0.78rem;letter-spacing:1.5px}
        .right{padding:4rem;display:flex;align-items:center;justify-content:center;background:var(--lav-light)}
        .form-box{width:100%;max-width:400px}
        .home-link{color:var(--navy);text-decoration:none;font-size:0.82rem;display:inline-flex;align-items:center;gap:0.4rem;margin-bottom:2.5rem;transition:opacity 0.2s;font-weight:500;opacity:0.65}
        .home-link:hover{opacity:1}
        .form-box h1{font-family:'Cormorant Garamond',serif;font-size:2.4rem;font-weight:700;margin-bottom:0.3rem;color:var(--navy)}
        .sub{color:var(--navy);font-size:0.84rem;margin-bottom:2rem;opacity:0.6}
        .form-group{margin-bottom:1.1rem}
        .form-group label{display:block;font-size:0.72rem;font-weight:700;color:var(--navy);letter-spacing:1.5px;text-transform:uppercase;margin-bottom:0.5rem}
        .form-group input{width:100%;padding:0.78rem 1rem;background:#fff;border:2px solid var(--lav);border-radius:10px;color:var(--navy);font-family:'DM Sans',sans-serif;font-size:0.92rem;outline:none;transition:border-color 0.2s}
        .form-group input:focus{border-color:var(--navy)}
        .btn-submit{width:100%;background:var(--navy);color:#fff;border:none;padding:0.88rem;border-radius:50px;font-weight:700;font-size:0.92rem;cursor:pointer;font-family:'DM Sans',sans-serif;transition:all 0.2s;margin-top:0.5rem;letter-spacing:0.5px}
        .btn-submit:hover{background:var(--lav);color:var(--navy)}
        .link{text-align:center;margin-top:1.2rem;font-size:0.82rem;color:rgba(0,0,128,0.68)}
        .link a{color:var(--navy);text-decoration:none;font-weight:700}
        .error{color:#c0392b;font-size:0.74rem;margin-top:0.4rem}
        .flash-banner{background:var(--yellow);border:none;border-radius:50px;box-shadow:0 4px 16px rgba(0,0,128,0.15);color:var(--navy);font-size:0.88rem;font-weight:700;margin:0 auto 1.2rem;max-width:420px;padding:0.75rem 2rem;text-align:center;width:max-content;animation:flash-fade 0.35s ease-in 2.65s forwards}
        @keyframes flash-fade{to{opacity:0}}
        @media (max-width:760px){body{grid-template-columns:1fr}.left{padding:2rem;min-height:320px}.right{padding:2rem}}
    </style>
</head>
<body>
    <div class="left">
        <a href="/" class="logo">Task<span>ly</span></a>
        <div class="left-content">
            <h2>Take control of your <em>time.</em></h2>
            <p>Join Taskly and manage everything from one clean, powerful dashboard.</p>
            <div class="points">
                <div class="point"><div class="point-dot"></div>Set priorities and deadlines</div>
                <div class="point"><div class="point-dot"></div>Track progress in real time</div>
                <div class="point"><div class="point-dot"></div>Your data stays private</div>
            </div>
        </div>
        <div class="footer-note">TASKLY &copy; 2026</div>
    </div>
    <div class="right">
        <div class="form-box">
            <a href="/" class="home-link">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                Back to Home
            </a>
            <h1>Create Account</h1>
            <p class="sub">Fill in your details to get started.</p>
            @if(session('success'))
                <div class="flash-banner">{{ session('success') }}</div>
            @endif
            <form method="POST" action="/register">
                @csrf
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Your full name">
                    @error('name')<p class="error">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com">
                    @error('email')<p class="error">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Min 6 characters">
                    @error('password')<p class="error">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation" placeholder="Repeat password">
                </div>
                <button type="submit" class="btn-submit">Create Account</button>
            </form>
            @if(session('success'))
            <script>
                setTimeout(function () {
                    var banner = document.querySelector('.flash-banner');
                    if (banner) banner.remove();
                }, 3000);
            </script>
            @endif
            <p class="link">Already have an account? <a href="/login">Sign in</a></p>
        </div>
    </div>
</body>
</html>
