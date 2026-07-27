<!DOCTYPE html>
<html class="scroll-smooth" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Store' }} | {{ isset($tenant) ? $tenant->name : 'Flowexa' }}</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400..700&family=Geist:wght@100..900&family=Inter:wght@100..900&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: 'var(--primary)',
                        'on-primary': 'var(--on-primary)',
                        secondary: 'var(--secondary)',
                        surface: 'var(--surface)',
                        'surface-container': 'var(--surface-container)',
                        'surface-container-lowest': 'var(--surface-container-lowest)',
                        'surface-container-highest': 'var(--surface-container-highest)',
                        'on-surface': 'var(--on-surface)',
                        'on-surface-variant': 'var(--on-surface-variant)',
                        outline: 'var(--outline)',
                        error: 'var(--error)',
                        'on-error': 'var(--on-error)',
                        success: 'var(--success)',
                        'on-success': 'var(--on-success)',
                    },
                    spacing: {
                        'margin-desktop': '40px',
                        'stack-lg': '48px',
                        'container-max': '1280px',
                        'stack-sm': '12px',
                        'gutter': '24px',
                        'base': '4px',
                        'stack-md': '24px',
                        'margin-mobile': '16px'
                    },
                    fontFamily: {
                        'display-lg': ['Geist'],
                        'body-md': ['Inter']
                    }
                }
            }
        }
    </script>

    <style>
        :root {
            --primary: #F97316;
            --on-primary: #FFFFFF;
            --secondary: #E4E4E7;
            --surface: #FAFAFA;
            --surface-container: #F4F4F5;
            --surface-container-lowest: #FFFFFF;
            --surface-container-highest: #E4E4E7;
            --on-surface: #09090B;
            --on-surface-variant: #52525B;
            --outline: #E4E4E7;
            --error: #EF4444;
            --on-error: #FFFFFF;
            --success: #22C55E;
            --on-success: #FFFFFF;
        }

        .dark {
            --primary: #F97316;
            --on-primary: #FFFFFF;
            --secondary: #27272A;
            --surface: #09090B;
            --surface-container: #121214;
            --surface-container-lowest: #18181B;
            --surface-container-highest: #27272A;
            --on-surface: #FAFAFA;
            --on-surface-variant: #A1A1AA;
            --outline: #27272A;
            --error: #F87171;
            --on-error: #FFFFFF;
            --success: #4ADE80;
            --on-success: #FFFFFF;
        }

        .material-symbols-outlined {
            font-family: 'Material Symbols Outlined';
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            display: inline-block;
            line-height: 1;
        }
        .glass-effect {
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

    @stack('styles')
</head>
<body class="bg-surface text-on-surface font-body-md overflow-x-hidden">
    <!-- TopAppBar -->
    <header class="fixed top-0 w-full z-50 bg-surface/80 backdrop-blur-xl border-b border-outline">
        <div class="flex justify-between items-center px-margin-mobile md:px-margin-desktop py-4 max-w-container-max mx-auto">
            <div class="flex items-center gap-6">
                <a href="{{ route('home.index') }}" class="font-display-lg text-xl tracking-tighter font-bold text-on-surface uppercase">
                    {{ isset($tenant) ? $tenant->name : 'Flowexa' }}
                </a>
            </div>
            <div class="flex items-center gap-8">
                <button class="hidden md:flex items-center text-on-surface-variant hover:text-primary transition-colors gap-2 text-sm">
                    <span class="material-symbols-outlined text-lg">search</span>
                    <span>Search Market</span>
                </button>

                @auth('customer')
                    <a href="{{ route('home.customer.dashboard') }}" class="text-on-surface-variant hover:text-primary transition-colors">
                        <span class="material-symbols-outlined">person</span>
                    </a>
                @else
                    <a href="{{ route('home.customer.login') }}" class="text-on-surface hover:text-primary transition-colors text-xs font-bold tracking-widest uppercase">LOGIN</a>
                @endauth

                <a href="{{ isset($tenant) ? route('home.checkout.show', $tenant) : route('home.cart.index') }}" class="text-on-surface hover:text-primary transition-colors relative">
                    <span class="material-symbols-outlined">shopping_cart</span>
                    <span class="absolute -top-2 -right-2 bg-primary text-on-primary text-[10px] w-4 h-4 rounded-full flex items-center justify-center cart-count">0</span>
                </a>
            </div>
        </div>
    </header>

    <main class="pt-16 min-h-screen">
        {{ $slot }}
    </main>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed bottom-8 right-8 z-[100] flex flex-col gap-3 pointer-events-none"></div>

    <script>
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = `flowexa-alert flowexa-alert-${type} shadow-2xl pointer-events-auto cursor-pointer transition-all duration-300 transform translate-y-10 opacity-0`;

            const iconMap = {
                success: 'check_circle',
                info: 'info',
                warning: 'warning',
                danger: 'error'
            };

            const icon = iconMap[type] || 'info';

            toast.innerHTML = `
                <div class="flex items-center gap-3 pr-4">
                    <span class="material-symbols-outlined text-xl">${icon}</span>
                    <p class="text-sm font-bold whitespace-nowrap">${message}</p>
                </div>
            `;

            toast.onclick = () => {
                toast.classList.add('opacity-0', 'translate-x-full');
                setTimeout(() => toast.remove(), 300);
            };

            container.appendChild(toast);

            // Animate in
            requestAnimationFrame(() => {
                toast.classList.remove('translate-y-10', 'opacity-0');
            });

            // Auto remove
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.classList.add('opacity-0', 'translate-x-full');
                    setTimeout(() => toast.remove(), 300);
                }
            }, 5000);
        }
    </script>

    <!-- Footer -->
    <footer class="w-full bg-surface border-t border-outline py-stack-lg mt-20">
        <div class="flex flex-col md:flex-row justify-between items-start gap-stack-lg px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto">
            <div class="max-w-sm">
                <span class="font-display-lg text-2xl font-bold text-on-surface uppercase block mb-4">{{ isset($tenant) ? $tenant->name : 'Flowexa' }}</span>
                <p class="text-on-surface-variant text-sm mb-6">The world's premier destination for curated direct-to-consumer luxury brands.</p>

                <div class="flex gap-4">
                    <a href="#" class="w-10 h-10 rounded-full border border-outline flex items-center justify-center text-on-surface-variant hover:text-primary hover:border-primary transition-all">
                        <i class="fa-brands fa-instagram text-lg"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full border border-outline flex items-center justify-center text-on-surface-variant hover:text-primary hover:border-primary transition-all">
                        <i class="fa-brands fa-x-twitter text-lg"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full border border-outline flex items-center justify-center text-on-surface-variant hover:text-primary hover:border-primary transition-all">
                        <i class="fa-brands fa-linkedin-in text-lg"></i>
                    </a>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-12">
                <div class="flex flex-col gap-3">
                    <h4 class="font-bold text-xs uppercase tracking-widest text-primary">Market</h4>
                    <a class="text-on-surface-variant hover:text-primary transition-colors text-sm" href="{{ route('about') }}">About Us</a>
                    <a class="text-on-surface-variant hover:text-primary transition-colors text-sm" href="{{ route('contact') }}">Contact</a>
                </div>
                <div class="flex flex-col gap-3">
                    <h4 class="font-bold text-xs uppercase tracking-widest text-primary">Legal</h4>
                    <a class="text-on-surface-variant hover:text-primary transition-colors text-sm" href="{{ route('privacy') }}">Privacy</a>
                    <a class="text-on-surface-variant hover:text-primary transition-colors text-sm" href="{{ route('terms') }}">Terms</a>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
