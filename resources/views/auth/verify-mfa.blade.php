<x-layouts.auth title="Verify Account">
    <x-ui.auth.card>
        <x-slot:header>
            <div style="display: flex; justify-content: flex-start; margin-bottom: 1rem;">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" style="background: none; border: none; cursor: pointer; color: var(--text-secondary); display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; font-weight: 500; padding: 0;">
                        <i class="fa-solid fa-arrow-left"></i> Back to Login
                    </button>
                </form>
            </div>
            <div style="font-size: 3rem; color: var(--primary); text-align: center; margin-bottom: 1rem;">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <h2 class="synkra-auth-card-title">Verify Your Account</h2>
            <p class="synkra-auth-card-subtitle" style="font-size: 0.95rem; line-height: 1.5; padding: 0 1rem;">
                We've sent a secure 6-digit verification code to your 
                <strong>{{ $user->mfa_type === 'sms' ? 'Phone Number' : 'Email Address' }}</strong>. 
                Please enter it below to complete your registration.
            </p>
        </x-slot:header>

        @if (session('status'))
            <x-ui.alert type="success" title="Success" message="{{ session('status') }}" style="margin-bottom: 1.5rem;" />
        @endif

        @if (session('error'))
            <x-ui.alert type="danger" title="Error" message="{{ session('error') }}" style="margin-bottom: 1.5rem;" />
        @endif

        <form method="POST" action="{{ route('mfa.verify.submit') }}">
            @csrf

            <div class="synkra-form-group" style="width: 100%; margin-bottom: 1.5rem;">
                <x-ui.input type="text" name="mfa_code" placeholder="000000" icon="fa-solid fa-key" label="Verification Code" required autofocus maxlength="6" style="text-align: center; font-size: 1.25rem; letter-spacing: 0.25rem; font-weight: 700;" />
            </div>
            
            <div class="synkra-form-actions" style="margin-top: 2rem;">
                <x-ui.button type="submit" icon="fa-solid fa-check-circle" style="width: 100%;">Verify Account</x-ui.button>
            </div>
        </form>

        <div class="synkra-auth-links" style="margin-top: 1.5rem; text-align: center;">
            <p style="color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 0.75rem;">Didn't receive the code?</p>
            <div style="display: flex; gap: 1rem; justify-content: center; align-items: center; flex-wrap: wrap;">
                <form method="POST" action="{{ route('mfa.resend') }}">
                    @csrf
                    <button type="submit" class="synkra-auth-link" style="background: none; border: none; cursor: pointer; padding: 0; font-family: inherit;">Resend Code</button>
                </form>
                
                @if($user->mfa_type === 'sms')
                <span style="color: var(--border);">|</span>
                <form method="POST" action="{{ route('mfa.resend') }}">
                    @csrf
                    <input type="hidden" name="method" value="email">
                    <button type="submit" class="synkra-auth-link" style="background: none; border: none; cursor: pointer; padding: 0; font-family: inherit;">Send via Email instead</button>
                </form>
                @elseif($user->mfa_type === 'email' && $user->phone_number)
                <span style="color: var(--border);">|</span>
                <form method="POST" action="{{ route('mfa.resend') }}">
                    @csrf
                    <input type="hidden" name="method" value="sms">
                    <button type="submit" class="synkra-auth-link" style="background: none; border: none; cursor: pointer; padding: 0; font-family: inherit;">Send via SMS instead</button>
                </form>
                @endif
            </div>
        </div>
    </x-ui.auth.card>
</x-layouts.auth>
