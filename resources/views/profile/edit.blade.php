<x-layout title="Edit Profile">
<style>
.profile-grid{display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;max-width:840px}
.profile-card{padding:2rem}
.profile-card h2{font-family:'Cormorant Garamond',serif;font-size:1.5rem;font-weight:700;color:var(--navy);margin-bottom:1.5rem}
.error{color:#dc2626;font-size:0.75rem;margin-top:0.3rem}
.password-wrapper {
    position: relative;
    display: flex;
    align-items: center;
    width: 100%;
}
.password-wrapper input {
    padding-right: 2.75rem;
    width: 100%;
}
.toggle-password {
    position: absolute;
    right: 12px;
    background: none;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    color: var(--navy);
    opacity: 0.6;
    transition: opacity 0.2s;
    padding: 0;
}
.toggle-password:hover {
    opacity: 1;
}
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
                <div class="password-wrapper">
                    <input type="password" name="password" id="password" placeholder="Min 6 characters">
                    <button type="button" class="toggle-password" data-target="password">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
                @error('password')<p class="error">{{ $message }}</p>@enderror
            </div>
            <div class="ui-field"><label>Confirm Password</label>
                <div class="password-wrapper">
                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Repeat password">
                    <button type="button" class="toggle-password" data-target="password_confirmation">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
            </div>
            <button type="submit" class="ui-button ui-button-primary">Update Password</button>
        </form>
    </div>
    <div class="profile-card ui-card" style="grid-column: 1 / -1; margin-top: 1.5rem; border-color: #e74c3c;">
        <h2 style="color: #900;">Danger Zone</h2>
        <p style="margin-bottom: 1rem; font-size: 0.88rem; color: var(--navy);">Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.</p>
        <button type="button" class="ui-button ui-button-danger" onclick="document.getElementById('deleteAccountModal').style.display='flex'">Delete Account</button>
    </div>

    <!-- Delete Account Modal -->
    <div id="deleteAccountModal" style="display: {{ $errors->userDeletion->isNotEmpty() ? 'flex' : 'none' }}; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
        <div class="ui-card" style="width: 100%; max-width: 450px; padding: 2rem;">
            <h2 style="color: #900; font-size: 1.4rem; margin-bottom: 1rem;">Delete Account</h2>
            <p style="margin-bottom: 1.5rem; font-size: 0.9rem;">Are you sure you want to delete your account? This action cannot be undone.</p>
            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf
                @method('DELETE')
                <div class="ui-field">
                    <label>Password</label>
                    <input type="password" name="password" required placeholder="Enter your password to confirm">
                    @error('password', 'userDeletion')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>
                <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem;">
                    <button type="button" class="ui-button ui-button-secondary" onclick="document.getElementById('deleteAccountModal').style.display='none'">Cancel</button>
                    <button type="submit" class="ui-button ui-button-danger">Delete Account</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';

            if (isPassword) {
                this.innerHTML = `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>`;
            } else {
                this.innerHTML = `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>`;
            }
        });
    });
</script>
</x-layout>
