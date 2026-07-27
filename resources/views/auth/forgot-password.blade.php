<x-layouts.auth>
    <x-ui.auth.card>
        <x-slot:header>
            <h2 class="flowexa-auth-card-title">Forgot Password</h2>
            <p class="flowexa-auth-card-subtitle">Enter your email to receive a reset link</p>
        </x-slot:header>

        @if (session('status'))
            <x-ui.alert type="success" title="Email Sent" message="{{ session('status') }}"/>
        @endif

        @if (session('error'))
            <x-ui.alert type="danger" title="Error" message="{{ session('error') }}"/>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="flowexa-form-group">
                <x-ui.input
                    type="email"
                    name="email"
                    placeholder="Email Address"
                    value="{{ old('email') }}"
                    label="Email Address"
                    icon="fa-solid fa-envelope"
                    required
                    autofocus
                />
                @error('email')
                    <span class="flowexa-form-error">{{ $message }}</span>
                @enderror
            </div>

            <x-ui.button type="submit" icon="fa-solid fa-paper-plane" fullWidth=true>Send Reset Link</x-ui.button>

            <div class="flowexa-auth-links">
                <a href="{{ route('login') }}" class="flowexa-auth-link"><i class="fa-solid fa-arrow-left"></i> Back to Login</a>
            </div>
        </form>

<style>
    .flowexa-form-group { width: 100%; margin-bottom: 1.25rem; display: flex; justify-content: center; align-items: center;}
    .flowexa-auth-links { text-align: center; margin-top: 1.5rem; }
    .flowexa-auth-link { color: var(--primary); text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: opacity 0.2s; }
    .flowexa-auth-link:hover { opacity: 0.8; }
</style>
    </x-ui.auth.card>
</x-layouts.auth>
