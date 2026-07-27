<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <script>
        (function () {
            var saved = localStorage.getItem('appearance') || 'system';
            var theme = saved;
            if (saved === 'system') {
                theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name', 'flowexa') }}</title>

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- AOS Animations -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <style>
        .hero-gradient {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        }
        .text-brand-primary { color: var(--primary); }
        .bg-brand-primary { background-color: var(--primary); }
        .border-brand-primary { border-color: var(--primary); }
        .bg-surface { background-color: var(--surface); }
        .bg-surface-secondary { background-color: var(--surface-secondary); }

        /* Modern Card Styles */
        .modern-card {
            display: block;
            position: relative;
            max-width: 300px;
            max-height: 320px;
            background: linear-gradient(to bottom, #e3f2fd, #bbdefb);
            border-radius: 10px;
            padding: 2em 1.2em;
            margin: 12px;
            text-decoration: none;
            z-index: 0;
            overflow: hidden;
            font-family: Arial, Helvetica, sans-serif;
            transition: transform 0.3s ease-out;
        }

        .modern-card:hover {
            transform: translateY(-8px);
        }

        .modern-card:before {
            content: '';
            position: absolute;
            z-index: -1;
            top: -16px;
            right: -16px;
            background: linear-gradient(135deg, var(--primary) 0%, #364a60 100%);
            height: 32px;
            width: 32px;
            border-radius: 32px;
            transform: scale(1);
            transform-origin: 50% 50%;
            transition: transform 0.35s ease-out;
        }

        .modern-card:hover:before {
            transform: scale(28);
        }

        .card-title {
            color: #262626;
            font-size: 1.5em;
            line-height: normal;
            font-weight: 700;
            margin-bottom: 0.5em;
            transition: all 0.5s ease-out;
            display: flex;
            align-items: center;
        }

        .small-desc {
            font-size: 1em;
            font-weight: 400;
            line-height: 1.5em;
            color: #452c2c;
            transition: all 0.5s ease-out;
        }

        .modern-card:hover .small-desc {
            color: rgba(255, 255, 255, 0.8);
        }

        .modern-card:hover .card-title {
            color: #ffffff;
        }

        .go-corner {
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            width: 2em;
            height: 2em;
            overflow: hidden;
            top: 0;
            right: 0;
            background: linear-gradient(135deg, var(--primary) 0%, #364a60 100%);
            border-radius: 0 4px 0 32px;
            transition: all 0.35s ease-out;
        }

        .go-arrow {
            margin-top: -4px;
            margin-right: -4px;
            color: white;
            font-family: courier, sans;
            font-weight: bold;
        }

        /* Modern Value Cards */
        .modern-value-card {
            background: linear-gradient(135deg, rgba(66, 165, 245, 0.1), rgba(156, 39, 176, 0.1));
            border: 2px solid var(--primary);
            border-radius: 15px;
            padding: 2rem;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease-out;
        }

        .modern-value-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(66, 165, 245, 0.2);
            border-color: var(--primary);
        }

        .modern-value-card:before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, var(--primary), transparent);
            border-radius: 50%;
            opacity: 0.1;
            transform: translate(30%, -30%);
        }

        .value-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-bottom: 1rem;
            transition: all 0.3s ease-out;
        }

        .modern-value-card:hover .value-icon {
            transform: scale(1.1) rotate(5deg);
        }

        /* Modern Form Card */
        .modern-form-card {
            background: linear-gradient(135deg, #f0f7ff 0%, #f8e6ff 100%);
            border-radius: 15px;
            padding: 2.5rem;
            box-shadow: 0 10px 40px rgba(66, 165, 245, 0.15);
            border: 1px solid rgba(66, 165, 245, 0.2);
            position: relative;
            overflow: hidden;
        }

        .modern-form-card:before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, var(--primary) 0%, transparent 70%);
            opacity: 0.05;
            border-radius: 50%;
        }

        .modern-form-card > form {
            position: relative;
            z-index: 1;
        }

        .modern-input {
            width: 100%;
            background: rgba(255, 255, 255, 0.7);
            border: 2px solid rgba(66, 165, 245, 0.3);
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            color: #333;
            transition: all 0.3s ease-out;
            font-family: Arial, Helvetica, sans-serif;
        }

        .modern-input:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.95);
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(66, 165, 245, 0.1);
            transform: translateY(-2px);
        }

        .modern-input::placeholder {
            color: rgba(69, 44, 44, 0.5);
        }

        .modern-button {
            background: linear-gradient(135deg, var(--primary) 0%, #1976d2 100%);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 10px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease-out;
            box-shadow: 0 10px 25px rgba(66, 165, 245, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            position: relative;
            overflow: hidden;
        }

        .modern-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(66, 165, 245, 0.4);
        }

        .modern-button:active {
            transform: translateY(-1px);
        }

        .button-arrow {
            transition: transform 0.3s ease-out;
        }

        .modern-button:hover .button-arrow {
            transform: translateX(4px);
        }

        /* Policy Sections */
        .policy-section {
            background: linear-gradient(135deg, rgba(66, 165, 245, 0.05), rgba(156, 39, 176, 0.05));
            border-left: 4px solid var(--primary);
            padding: 2rem;
            border-radius: 8px;
            transition: all 0.3s ease-out;
        }

        .policy-section:hover {
            background: linear-gradient(135deg, rgba(66, 165, 245, 0.1), rgba(156, 39, 176, 0.1));
            padding-left: 2.5rem;
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .section-number {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary), #1976d2);
            color: white;
            border-radius: 50%;
            font-weight: bold;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .policy-section h2 {
            margin: 0;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .modern-card {
                max-width: 280px;
                max-height: 300px;
                padding: 1.5em 1em;
            }

            .modern-value-card {
                padding: 1.5rem;
            }

            .modern-form-card {
                padding: 1.5rem;
            }

            .policy-section {
                padding: 1.5rem;
            }

            .section-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .section-number {
                align-self: flex-start;
            }
        }
    </style>
</head>
<body class="bg-background text-body-text antialiased scroll-smooth transition-colors duration-300">
    <!-- Modern Navbar -->
    <nav class="fixed top-0 w-full z-50 bg-surface/80 backdrop-blur-md border-b border-border transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center">
                    <a href="/" class="text-2xl font-bold hero-gradient bg-clip-text text-transparent hover:opacity-80 transition">flowexa</a>
                </div>
                <div class="hidden md:flex space-x-8 items-center font-medium">
                    <a href="/" class="hover:text-primary transition">Home</a>
                    <a href="/#services" class="hover:text-primary transition">Services</a>
                    <a href="{{ route('about') }}" class="hover:text-primary transition">About</a>
                    <a href="{{ route('contact') }}" class="hover:text-primary transition">Contact</a>

                    <button onclick="toggleTheme()" class="text-text-secondary hover:text-primary transition p-2 rounded-full bg-surface-secondary focus:outline-none">
                        <i class="fa-solid fa-moon dark:hidden"></i>
                        <i class="fa-solid fa-sun hidden dark:inline-block text-warning"></i>
                    </button>

                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="bg-primary text-white px-5 py-2 rounded-full hover:bg-primary-hover transition shadow-lg shadow-primary/30">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="hover:text-primary transition">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-primary text-white px-5 py-2 rounded-full hover:bg-primary-hover transition shadow-lg shadow-primary/30">Get Started</a>
                            @endif
                        @endauth
                    @endif
                </div>
                <!-- Mobile menu toggle (optional, simple visible icon for now) -->
                <div class="md:hidden flex items-center">
                    <button onclick="toggleTheme()" class="text-text-secondary hover:text-primary transition p-2 rounded-full bg-surface-secondary focus:outline-none mr-4">
                        <i class="fa-solid fa-moon dark:hidden"></i>
                        <i class="fa-solid fa-sun hidden dark:inline-block text-warning"></i>
                    </button>
                    <i class="fa-solid fa-bars text-xl text-headings cursor-pointer"></i>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    {{ $slot }}

    <!-- Modern Footer -->
    <footer class="bg-surface-secondary pt-20 pb-10 relative border-t border-border mt-10">
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
            <div>
                <span class="text-3xl font-bold hero-gradient bg-clip-text text-transparent">flowexa</span>
                <p class="mt-4 text-text-secondary leading-relaxed">The future of intelligent business orchestration. Built for developers, trusted by enterprises.</p>
            </div>
            <div>
                <h4 class="font-bold mb-6 text-headings">Product</h4>
                <ul class="space-y-4 text-text-secondary">
                    <li><a href="/#services" class="hover:text-primary transition">Features</a></li>
                    <li><a href="#" class="hover:text-primary transition">Pricing</a></li>
                    <li><a href="#" class="hover:text-primary transition">Integrations</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold mb-6 text-headings">Company</h4>
                <ul class="space-y-4 text-text-secondary">
                    <li><a href="{{ route('about') }}" class="hover:text-primary transition">About Us</a></li>
                    <li><a href="#" class="hover:text-primary transition">Careers</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-primary transition">Contact</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold mb-6 text-headings">Legal</h4>
                <ul class="space-y-4 text-text-secondary">
                    <li><a href="{{ route('privacy') }}" class="hover:text-primary transition">Privacy Policy</a></li>
                    <li><a href="{{ route('terms') }}" class="hover:text-primary transition">Terms of Service</a></li>
                    <li><a href="#" class="hover:text-primary transition">Cookie Policy</a></li>
                </ul>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 pt-10 border-t border-border flex flex-col md:flex-row items-center justify-between text-text-secondary text-sm">
            <p>&copy; {{ date('Y') }} flowexa Inc. All rights reserved.</p>
            <div class="flex space-x-6 mt-4 md:mt-0">
                <a href="#" class="hover:text-primary transition"><i class="fa-brands fa-twitter text-lg"></i></a>
                <a href="#" class="hover:text-primary transition"><i class="fa-brands fa-github text-lg"></i></a>
                <a href="#" class="hover:text-primary transition"><i class="fa-brands fa-linkedin text-lg"></i></a>
            </div>
        </div>
    </footer>

    <script>
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100,
        });

        function toggleTheme() {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('appearance', newTheme);
        }
    </script>
</body>
</html>
