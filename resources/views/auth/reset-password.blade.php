<x-layouts.auth>
    <x-ui.auth.card>
        <x-slot:header>
            <h2 class="flowexa-auth-card-title">Reset Password</h2>
            <p class="flowexa-auth-card-subtitle">Enter your new password below</p>
        </x-slot:header>

        @if (session('status'))
            <x-ui.alert type="success" title="Success" message="{{ session('status') }}"/>
        @endif

        @if (session('error'))
            <x-ui.alert type="danger" title="Error" message="{{ session('error') }}"/>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token ?? request()->route('token') }}">

            <div class="flowexa-form-group">
                <x-ui.input
                    type="email"
                    name="email"
                    placeholder="Email Address"
                    value="{{ $email ?? old('email') }}"
                    icon="fa-solid fa-envelope"
                    required
                    autofocus
                />
                @error('email')
                    <span class="flowexa-form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="flowexa-form-group">
                <x-ui.input
                    type="password"
                    name="password"
                    placeholder="New Password"
                    icon="fa-solid fa-lock"
                    required
                />
                @error('password')
                    <span class="flowexa-form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="flowexa-form-group">
                <x-ui.input
                    type="password"
                    name="password_confirmation"
                    placeholder="Confirm New Password"
                    icon="fa-solid fa-lock"
                    required
                />
            </div>

            <x-ui.button type="submit" icon="fa-solid fa-check" fullWidth=true>Reset Password</x-ui.button>
        </form>

<style>
    .flowexa-form-group { width: 100%; margin-bottom: 1.25rem; display: flex; justify-content: center; align-items: center;}
</style>
    </x-ui.auth.card>
</x-layouts.auth>
