<x-layouts.shop>
    <x-ui.grid>
    <x-slot:title>Customer Login</x-slot:title>

    <div class="max-w-md mx-auto my-20 p-8 bg-surface-container rounded-2xl shadow-2xl border border-outline">
        <h2 class="font-display-lg text-headline-md font-bold text-primary mb-6 text-center">WELCOME BACK</h2>

        <form action="{{ route('home.customer.login') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label for="email" class="block text-sm font-bold text-on-surface-variant mb-2">EMAIL ADDRESS</label>
                <input type="email" name="email" id="email" required
                    class="w-full bg-surface border-outline rounded-xl py-3 px-4 focus:ring-2 focus:ring-primary/20 text-on-surface"
                    placeholder="Enter your email">
                @error('email') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-bold text-on-surface-variant mb-2">PASSWORD</label>
                <input type="password" name="password" id="password" required
                    class="w-full bg-surface border-outline rounded-xl py-3 px-4 focus:ring-2 focus:ring-primary/20 text-on-surface"
                    placeholder="Enter your password">
            </div>

            <button type="submit" class="w-full py-4 bg-primary text-on-primary rounded-xl font-bold hover:opacity-90 active:scale-95 transition-all">
                SIGN IN
            </button>
        </form>

        <div class="mt-8">
            <div class="relative flex items-center justify-center">
                <div class="w-full border-t border-outline"></div>
                <span class="absolute bg-surface-container px-4 text-xs font-bold text-on-surface-variant">OR CONTINUE WITH</span>
            </div>

            <a href="{{ route('home.customer.auth.google') }}" class="mt-6 w-full flex items-center justify-center gap-3 py-3 border border-outline rounded-xl hover:bg-surface transition-colors active:scale-95">
                <img src="https://www.google.com/favicon.ico" class="w-5 h-5" alt="Google">
                <span class="font-bold text-sm text-on-surface">GOOGLE</span>
            </a>
        </div>

        <p class="mt-8 text-center text-sm text-on-surface-variant">
            Don't have an account?
            <a href="{{ route('home.customer.register') }}" class="text-primary font-bold hover:underline">REGISTER NOW</a>
        </p>
    </div>
    </x-ui.grid>
</x-layouts.shop>
