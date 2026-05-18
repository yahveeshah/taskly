<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Taskly</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        :root{--lav:#C7A0CB;--navy:#000080;--yellow:#F6BE00;--ll:#f0e8f2}
        body{font-family:'DM Sans',sans-serif;background:var(--ll);color:var(--navy);min-height:100vh;display:grid;grid-template-columns:1fr 1fr}
        .left{background:var(--navy);padding:4rem;display:flex;flex-direction:column;justify-content:space-between}
        .logo{font-family:'Cormorant Garamond',serif;font-size:1.8rem;color:var(--lav);font-weight:700;text-decoration:none}
        .logo span{color:#fff}
        .left-content{margin:auto 0}
        .left h2{font-family:'Cormorant Garamond',serif;font-size:3rem;font-weight:700;line-height:1.1;margin-bottom:1.2rem;color:#fff}
        .left h2 em{color:var(--yellow);font-style:italic}
        .left p{color:var(--lav);line-height:1.9;font-size:0.92rem;opacity:0.85}
        .footer-note{color:rgba(199,160,203,0.45);font-size:0.78rem;letter-spacing:1.5px}
        .right{padding:4rem;display:flex;align-items:center;justify-content:center;background:var(--ll)}
        .form-box{width:100%;max-width:420px}
        .home-link{color:var(--navy);text-decoration:none;font-size:0.82rem;display:inline-flex;align-items:center;gap:0.4rem;margin-bottom:2.5rem;font-weight:500;opacity:0.65;transition:opacity 0.2s}
        .home-link:hover{opacity:1}
        .form-box h1{font-family:'Cormorant Garamond',serif;font-size:2.4rem;font-weight:700;margin-bottom:0.3rem;color:var(--navy)}
        .sub{color:var(--navy);font-size:0.84rem;margin-bottom:2rem;opacity:0.6}
        .form-group{margin-bottom:1.1rem}
        .form-group label,.choice-title{display:block;font-size:0.72rem;font-weight:700;color:var(--navy);letter-spacing:1.5px;text-transform:uppercase;margin-bottom:0.5rem}
        .form-group input{width:100%;padding:0.78rem 1rem;background:#fff;border:2px solid var(--lav);border-radius:10px;color:var(--navy);font-family:'DM Sans',sans-serif;font-size:0.92rem;outline:none;transition:border-color 0.2s}
        .form-group input:focus{border-color:var(--navy)}
        .choice-section{border-top:1px solid rgba(199,160,203,0.65);margin:1.4rem 0 1.1rem;padding-top:1.2rem}
        .choice-section h2{font-family:'Cormorant Garamond',serif;font-size:1.5rem;font-weight:700;color:var(--navy);margin-bottom:0.8rem}
        .choice-grid{display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;margin-bottom:1rem}
        .choice{cursor:pointer;display:block}
        .choice input{position:absolute;opacity:0;pointer-events:none}
        .choice span{display:flex;align-items:center;justify-content:center;min-height:46px;background:#fff;border:2px solid var(--lav);border-radius:50px;color:var(--navy);font-size:0.86rem;font-weight:700;text-align:center;transition:all 0.2s}
        .choice input:checked + span{background:var(--navy);border-color:var(--navy);color:#fff}
        .team-role,.team-code{display:none}
        .team-role.show,.team-code.show{display:block}
        .btn-submit{width:100%;background:var(--navy);color:#fff;border:none;padding:0.88rem;border-radius:50px;font-weight:700;font-size:0.92rem;cursor:pointer;font-family:'DM Sans',sans-serif;transition:all 0.2s;margin-top:0.5rem}
        .btn-submit:hover{background:var(--lav);color:var(--navy)}
        .link{text-align:center;margin-top:1.2rem;font-size:0.82rem;color:rgba(0,0,128,0.68)}
        .link a{color:var(--navy);text-decoration:none;font-weight:700}
        .error{color:#c0392b;font-size:0.74rem;margin-top:0.4rem}
        .flash-banner{background:var(--yellow);border:none;border-radius:50px;box-shadow:0 4px 16px rgba(0,0,128,0.15);color:var(--navy);font-size:0.88rem;font-weight:700;margin:0 auto 1.2rem;max-width:420px;padding:0.75rem 2rem;text-align:center;width:max-content;animation:flash-fade 0.35s ease-in 2.65s forwards}
        @keyframes flash-fade{to{opacity:0}}
        @media (max-width:760px){body{grid-template-columns:1fr}.left{padding:2rem;min-height:320px}.right{padding:2rem}.choice-grid{grid-template-columns:1fr}}
    </style>
</head>
<body>
    <div class="left">
        <a href="/" class="logo">Task<span>ly</span></a>
        <div class="left-content">
            <h2>Take control of your <em>time.</em></h2>
            <p>Join Taskly and manage everything from one clean, powerful dashboard.</p>
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

                <div class="choice-section">
                    <h2>How would you like to use Taskly?</h2>
                    <div class="choice-grid">
                        <label class="choice"><input type="radio" name="use_type" value="personal" @checked(old('use_type', 'personal') === 'personal')><span>Personal Use</span></label>
                        <label class="choice"><input type="radio" name="use_type" value="group" @checked(old('use_type') === 'group')><span>Group / Team Use</span></label>
                    </div>
                    @error('use_type')<p class="error">{{ $message }}</p>@enderror

                    <div class="team-role" id="teamRole">
                        <div class="choice-title">What is your role?</div>
                        <div class="choice-grid">
                            <label class="choice"><input type="radio" name="role" value="manager" @checked(old('role') === 'manager')><span>Manager</span></label>
                            <label class="choice"><input type="radio" name="role" value="member" @checked(old('role') === 'member')><span>Member</span></label>
                        </div>
                        @error('role')<p class="error">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group team-code" id="teamCode">
                        <label>Enter your Team Code</label>
                        <input type="text" name="team_code" value="{{ old('team_code') }}" placeholder="TEAM-4821">
                        @error('team_code')<p class="error">{{ $message }}</p>@enderror
                    </div>
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
    <script>
        function syncTeamFields() {
            var useType = document.querySelector('input[name="use_type"]:checked')?.value;
            var role = document.querySelector('input[name="role"]:checked')?.value;
            document.getElementById('teamRole').classList.toggle('show', useType === 'group');
            document.getElementById('teamCode').classList.toggle('show', useType === 'group' && role === 'member');
        }
        document.querySelectorAll('input[name="use_type"],input[name="role"]').forEach(function (input) {
            input.addEventListener('change', syncTeamFields);
        });
        syncTeamFields();
    </script>
</body>
</html>
