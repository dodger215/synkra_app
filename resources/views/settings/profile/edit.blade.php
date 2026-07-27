<x-layouts.app title="Profile Settings">
    <x-slot:head>
        <meta name="description" content="Update your personal account settings.">
    </x-slot:head>

    <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 800px; margin: 0 auto;">
        <div style="margin-bottom: 2rem;">
            <h1 style="color: var(--headings); margin: 0 0 0.5rem 0;">Profile Settings</h1>
            <p style="color: var(--text-secondary); margin: 0;">Update your personal account details and security settings.</p>
        </div>

        @if(session('status'))
            <x-ui.alert type="success" title="Success" message="{{ session('status') }}" style="margin-bottom: 2rem;" />
        @endif

        @if($errors->any())
            <x-ui.alert type="danger" title="Error" message="Please fix the errors below." style="margin-bottom: 2rem;" />
        @endif

        <div class="flowexa-card" style="padding: 2rem; background: var(--surface); border-radius: 20px; box-shadow: 0 10px 40px -10px rgba(0,0,0,0.06); border: 1px solid var(--border);">
            <form action="{{ route('settings.profile.update') }}" method="POST">
                @csrf

                <h3 style="color: var(--headings); margin: 0 0 1.5rem 0; border-bottom: 1px solid var(--border); padding-bottom: 1rem;">Account Details</h3>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <x-ui.input name="name" label="Full Name" placeholder="John Doe" value="{{ old('name', $user->name) }}" required />
                    <x-ui.input name="email" label="Email Address" type="email" placeholder="john@example.com" value="{{ old('email', $user->email) }}" required />
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <x-ui.input type="tel" name="phone_number" label="Phone Number" placeholder="e.g. 0241234567" value="{{ old('phone_number', $user->phone_number) }}" minlength="10" maxlength="15" pattern="\+?[0-9]{10,15}" title="Please enter a valid phone number (10-15 digits)" />

                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <label style="font-size: 0.85rem; font-weight: 600; color: var(--headings);">Two-Factor Authentication Method</label>
                        <select name="mfa_type" style="width: 100%; padding: 0.75rem 1rem; border-radius: 10px; border: 1px solid var(--border); background: var(--surface); color: var(--text-primary); outline: none; font-family: inherit; transition: all 0.2s;">
                            <option value="none" {{ old('mfa_type', $user->mfa_type) === 'none' ? 'selected' : '' }}>None (Not Recommended)</option>
                            <option value="email" {{ old('mfa_type', $user->mfa_type) === 'email' ? 'selected' : '' }}>Email Verification</option>
                            <option value="sms" {{ old('mfa_type', $user->mfa_type) === 'sms' ? 'selected' : '' }}>SMS Text Message</option>
                        </select>
                        @error('mfa_type') <span style="color: var(--danger); font-size: 0.8rem;">{{ $message }}</span> @enderror
                    </div>
                </div>

                <h3 style="color: var(--headings); margin: 2rem 0 1.5rem 0; border-bottom: 1px solid var(--border); padding-bottom: 1rem;">Security</h3>
                <p style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 1.5rem;">Leave the password fields blank if you do not wish to change your password.</p>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                    <x-ui.input name="password" label="New Password" type="password" placeholder="Enter new password" />
                    <x-ui.input name="password_confirmation" label="Confirm New Password" type="password" placeholder="Confirm new password" />
                </div>

                <div style="display: flex; justify-content: flex-end; padding-top: 1.5rem; border-top: 1px solid var(--border);">
                    <button type="submit" class="flowexa-btn flowexa-btn-primary" style="background: var(--primary); border: none; color: white; cursor: pointer; padding: 0.75rem 2rem; border-radius: 10px; font-weight: 600;">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
