<x-layouts.auth title="Login">
    <x-ui.auth.card>
        <x-slot:header>
            <h2 class="synkra-auth-card-title">Welcome to Synkra</h2>
            <p class="synkra-auth-card-subtitle">Sign in to your account to continue</p>
        </x-slot:header>

        @if (session('status'))
            <x-ui.alert type="success" title="Success" message="{{ session('status') }}"/>
        @endif

        @if (session('error'))
            <x-ui.alert type="danger" title="Error" message="{{ session('error') }}"/>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="synkra-form-group">
                <x-ui.input 
                    type="email" 
                    name="email" 
                    label="Email Address"
                    placeholder="Email Address" 
                    value="{{ old('email') }}" 
                    icon="fa-solid fa-envelope"
                    required 
                    autofocus
                />
                
                @error('email')
                    <span class="synkra-form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="synkra-form-group">

                <x-ui.input 
                    type="password" 
                    name="password" 
                    label="Password"
                    placeholder="Password"
                    icon="fa-solid fa-lock"
                    required
                />
               
                @error('password')
                    <span class="synkra-form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="synkra-form-group">
               <div class="synkra-form-checkbox" style="width: 100%; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <x-ui.checkbox label="Remember Me" checked="false" />
                    </div>
                    <a href="{{ route('password.request') }}" class="synkra-auth-link" style="font-size: 0.85rem;">Forgot password?</a>
                </div>
            </div>
            <x-ui.button type="submit" icon="fa-solid fa-sign-in" fullWidth=true>Login</x-ui.button>
            
            <x-ui.auth.social_auth />

            <div class="synkra-auth-links" style="text-align: center; margin-top: 1.5rem;">
                <span style="font-size: 0.9rem; color: var(--text-secondary);">Don't have an account?</span>
                <a href="{{ route('register') }}" class="synkra-auth-link">Sign Up</a>
            </div>
        </form>

<style>
    .synkra-form-group { width: 100%; margin-bottom: 1.25rem; display: flex; justify-content: center; align-items: center;}
    .synkra-auth-link { color: var(--primary); text-decoration: none; font-weight: 500; transition: opacity 0.2s; }
    .synkra-auth-link:hover { opacity: 0.8; }
</style>
    </x-ui.auth.card>
</x-layouts.auth>


