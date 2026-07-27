<x-layouts.shop>
    <x-slot:title>Account Settings</x-slot:title>

    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop py-stack-lg">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-6">
            <div>
                <h1 class="font-display-lg text-headline-md font-bold text-primary uppercase tracking-tighter">ACCOUNT SETTINGS</h1>
                <p class="text-on-surface-variant">Update your personal information and security settings.</p>
            </div>
            <form action="{{ route('home.customer.logout') }}" method="POST">
                @csrf
                <button type="submit" class="px-6 py-2 border border-outline rounded-lg font-bold text-sm hover:bg-surface-container transition-colors">
                    LOGOUT
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-gutter">
            <!-- Sidebar -->
            <aside class="md:col-span-1">
                <nav class="flex flex-col gap-2">
                    <a href="{{ route('home.customer.dashboard') }}" class="px-4 py-3 {{ request()->routeIs('home.customer.dashboard') ? 'bg-primary text-on-primary' : 'text-on-surface-variant hover:bg-surface-container' }} rounded-xl font-bold text-sm transition-colors">DASHBOARD</a>
                    <a href="{{ route('home.customer.orders') }}" class="px-4 py-3 {{ request()->routeIs('home.customer.orders') ? 'bg-primary text-on-primary' : 'text-on-surface-variant hover:bg-surface-container' }} rounded-xl font-bold text-sm transition-colors">ORDER HISTORY</a>
                    <a href="{{ route('home.customer.saved_items') }}" class="px-4 py-3 {{ request()->routeIs('home.customer.saved_items') ? 'bg-primary text-on-primary' : 'text-on-surface-variant hover:bg-surface-container' }} rounded-xl font-bold text-sm transition-colors">SAVED ITEMS</a>
                    <a href="{{ route('home.customer.settings') }}" class="px-4 py-3 {{ request()->routeIs('home.customer.settings') ? 'bg-primary text-on-primary' : 'text-on-surface-variant hover:bg-surface-container' }} rounded-xl font-bold text-sm transition-colors">ACCOUNT SETTINGS</a>
                </nav>
            </aside>

            <!-- Main Content -->
            <div class="md:col-span-3 space-y-8">
                <!-- Profile Settings -->
                <div class="bg-surface-container-lowest border border-surface-container rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-surface-container">
                        <h2 class="font-bold text-sm uppercase tracking-widest">Personal Information</h2>
                    </div>
                    <div class="p-6">
                        @if (session('status') === 'profile-updated')
                            <div class="mb-4 text-sm font-medium text-success">Profile updated successfully.</div>
                        @endif

                        <form action="{{ route('home.customer.settings.update') }}" method="POST" class="space-y-6">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-2">First Name</label>
                                    <input type="text" name="first_name" value="{{ old('first_name', $customer->first_name) }}" class="w-full bg-surface-container border-outline rounded-xl text-sm focus:ring-primary focus:border-primary text-on-surface" required>
                                    @error('first_name') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-2">Last Name</label>
                                    <input type="text" name="last_name" value="{{ old('last_name', $customer->last_name) }}" class="w-full bg-surface-container border-outline rounded-xl text-sm focus:ring-primary focus:border-primary text-on-surface" required>
                                    @error('last_name') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-2">Email Address</label>
                                    <input type="email" name="email" value="{{ old('email', $customer->email) }}" class="w-full bg-surface-container border-outline rounded-xl text-sm focus:ring-primary focus:border-primary text-on-surface" required>
                                    @error('email') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-2">Phone Number</label>
                                    <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}" class="w-full bg-surface-container border-outline rounded-xl text-sm focus:ring-primary focus:border-primary text-on-surface">
                                    @error('phone') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="bg-primary text-on-primary px-8 py-3 rounded-xl font-bold hover:opacity-90 transition-all">SAVE CHANGES</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Password Settings -->
                <div class="bg-surface-container-lowest border border-surface-container rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-surface-container">
                        <h2 class="font-bold text-sm uppercase tracking-widest">Security</h2>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('home.customer.settings.password') }}" method="POST" class="space-y-6">
                            @csrf
                            <div class="max-w-md space-y-6">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-2">Current Password</label>
                                    <input type="password" name="current_password" class="w-full bg-surface-container border-outline rounded-xl text-sm focus:ring-primary focus:border-primary text-on-surface" required>
                                    @error('current_password') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-2">New Password</label>
                                    <input type="password" name="password" class="w-full bg-surface-container border-outline rounded-xl text-sm focus:ring-primary focus:border-primary text-on-surface" required>
                                    @error('password') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-2">Confirm New Password</label>
                                    <input type="password" name="password_confirmation" class="w-full bg-surface-container border-outline rounded-xl text-sm focus:ring-primary focus:border-primary text-on-surface" required>
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="bg-primary text-on-primary px-8 py-3 rounded-xl font-bold hover:opacity-90 transition-all">UPDATE PASSWORD</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Display Preferences -->
                <div class="bg-surface-container-lowest border border-surface-container rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-surface-container">
                        <h2 class="font-bold text-sm uppercase tracking-widest">Display Preferences</h2>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-bold text-on-surface">Appearance</h3>
                                <p class="text-xs text-on-surface-variant">Customize how the store looks to you.</p>
                            </div>
                            <div class="flex bg-surface-container p-1 rounded-xl">
                                <button onclick="setTheme('light')" id="theme-light" class="px-4 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm">light_mode</span>
                                    LIGHT
                                </button>
                                <button onclick="setTheme('dark')" id="theme-dark" class="px-4 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm">dark_mode</span>
                                    DARK
                                </button>
                                <button onclick="setTheme('system')" id="theme-system" class="px-4 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm">settings_brightness</span>
                                    SYSTEM
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    function setTheme(theme) {
                        if (theme === 'system') {
                            localStorage.removeItem('theme');
                            if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                                document.documentElement.classList.add('dark');
                            } else {
                                document.documentElement.classList.remove('dark');
                            }
                        } else {
                            localStorage.setItem('theme', theme);
                            if (theme === 'dark') {
                                document.documentElement.classList.add('dark');
                            } else {
                                document.documentElement.classList.remove('dark');
                            }
                        }
                        updateThemeUI();
                    }

                    function updateThemeUI() {
                        const theme = localStorage.getItem('theme') || 'system';
                        const buttons = {
                            light: document.getElementById('theme-light'),
                            dark: document.getElementById('theme-dark'),
                            system: document.getElementById('theme-system')
                        };

                        Object.keys(buttons).forEach(key => {
                            if (buttons[key]) {
                                if (key === theme) {
                                    buttons[key].classList.add('bg-primary', 'text-on-primary');
                                    buttons[key].classList.remove('text-on-surface-variant');
                                } else {
                                    buttons[key].classList.remove('bg-primary', 'text-on-primary');
                                    buttons[key].classList.add('text-on-surface-variant');
                                }
                            }
                        });
                    }

                    document.addEventListener('DOMContentLoaded', updateThemeUI);
                </script>
            </div>
        </div>
    </div>
</x-layouts.shop>
