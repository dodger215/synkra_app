<x-layouts.shop>
    <x-slot:title>Customer Register</x-slot:title>

    <div class="max-w-md mx-auto my-20 p-8 bg-surface-container rounded-2xl shadow-2xl border border-outline">
        <h2 class="font-display-lg text-headline-md font-bold text-primary mb-6 text-center">CREATE ACCOUNT</h2>

        <form action="{{ route('home.customer.register') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="first_name" class="block text-sm font-bold text-on-surface-variant mb-2">FIRST NAME</label>
                    <input type="text" name="first_name" id="first_name" required
                        class="w-full bg-surface border-outline rounded-xl py-3 px-4 focus:ring-2 focus:ring-primary/20 text-on-surface"
                        placeholder="First name">
                    @error('first_name') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="last_name" class="block text-sm font-bold text-on-surface-variant mb-2">LAST NAME</label>
                    <input type="text" name="last_name" id="last_name" required
                        class="w-full bg-surface border-outline rounded-xl py-3 px-4 focus:ring-2 focus:ring-primary/20 text-on-surface"
                        placeholder="Last name">
                    @error('last_name') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

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
                    placeholder="Create password">
                @error('password') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-bold text-on-surface-variant mb-2">CONFIRM PASSWORD</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                    class="w-full bg-surface border-outline rounded-xl py-3 px-4 focus:ring-2 focus:ring-primary/20 text-on-surface"
                    placeholder="Repeat password">
            </div>

            <button type="submit" class="w-full py-4 bg-primary text-on-primary rounded-xl font-bold hover:opacity-90 active:scale-95 transition-all">
                SIGN UP
            </button>
        </form>

        <div class="mt-8 text-center text-sm text-on-surface-variant">
            By creating an account, you agree to our
            <a href="{{ route('terms') }}" class="text-primary font-bold hover:underline">Terms</a> &
            <a href="{{ route('privacy') }}" class="text-primary font-bold hover:underline">Privacy Policy</a>.
        </div>

        <p class="mt-8 text-center text-sm text-on-surface-variant">
            Already have an account?
            <a href="{{ route('home.customer.login') }}" class="text-primary font-bold hover:underline">LOGIN HERE</a>
        </p>
    </div>
</x-layouts.shop>
