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
</x-layout>
