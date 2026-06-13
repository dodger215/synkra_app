<x-layouts.auth>
    <x-ui.auth.card>
        <x-slot:header>
            <h2 class="synkra-auth-card-title">Accept Invitation</h2>
            <p class="synkra-auth-card-subtitle">Complete your account setup to join the workspace</p>
        </x-slot:header>

        @if (session('status'))
            <x-ui.alert type="success" title="Success" message="{{ session('status') }}"/>
        @endif

        @if (session('error'))
            <x-ui.alert type="danger" title="Error" message="{{ session('error') }}"/>
        @endif

        <form method="POST" action="{{ url('/accept-invite/' . request()->route('token')) }}">
            @csrf

            <div class="synkra-form-group">
                <x-ui.input 
                    type="text" 
                    name="name" 
                    placeholder="Full Name" 
                    value="{{ old('name') }}" 
                    icon="fa-solid fa-user"
                    required 
                    autofocus
                />
                @error('name')
                    <span class="synkra-form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="synkra-form-group">
                <x-ui.input 
                    type="password" 
                    name="password" 
                    placeholder="Create Password"
                    icon="fa-solid fa-lock"
                    required
                />
                @error('password')
                    <span class="synkra-form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="synkra-form-group">
                <x-ui.input 
                    type="password" 
                    name="password_confirmation" 
                    placeholder="Confirm Password"
                    icon="fa-solid fa-lock"
                    required
                />
            </div>

            <x-ui.button type="submit" icon="fa-solid fa-user-check" fullWidth=true>Join Workspace</x-ui.button>
        </form>

<style>
    .synkra-form-group { width: 100%; margin-bottom: 1.25rem; display: flex; justify-content: center; align-items: center;}
</style>
    </x-ui.auth.card>
</x-layouts.auth>
