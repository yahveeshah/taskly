<x-layout title="Edit Profile">
<style>
.profile-grid{display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;max-width:840px}
.profile-card{padding:2rem}
.profile-card h2{font-family:'Cormorant Garamond',serif;font-size:1.5rem;font-weight:700;color:var(--navy);margin-bottom:1.5rem}
.error{color:#dc2626;font-size:0.75rem;margin-top:0.3rem}
@media (max-width:760px){.profile-grid{grid-template-columns:1fr}.profile-card{padding:1.5rem}}
</style>

<div class="profile-grid">
    <div class="profile-card ui-card">
        <h2>Profile Details</h2>
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf @method('PATCH')
            <div class="ui-field"><label>Name</label>
                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}">
                @error('name')<p class="error">{{ $message }}</p>@enderror
            </div>
            <div class="ui-field"><label>Email</label>
                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}">
                @error('email')<p class="error">{{ $message }}</p>@enderror
            </div>
            <button type="submit" class="ui-button ui-button-primary">Save Changes</button>
        </form>
    </div>
    <div class="profile-card ui-card">
        <h2>Change Password</h2>
        <form method="POST" action="{{ route('profile.password') }}">
            @csrf @method('PATCH')
            <div class="ui-field"><label>New Password</label>
                <input type="password" name="password" placeholder="Min 6 characters">
                @error('password')<p class="error">{{ $message }}</p>@enderror
            </div>
            <div class="ui-field"><label>Confirm Password</label>
                <input type="password" name="password_confirmation" placeholder="Repeat password">
            </div>
            <button type="submit" class="ui-button ui-button-primary">Update Password</button>
        </form>
    </div>
</div>
</x-layout>
