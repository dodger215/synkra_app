<x-layouts.shop>
    <x-slot:title>Unified Commerce & Marketplace Ecosystem</x-slot:title>

    @push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        :root {
            --swiper-theme-color: var(--primary);
        }

        /* 3D Floating Animations */
        @keyframes float {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(2deg); }
            100% { transform: translateY(0px) rotate(0deg); }
        }

        @keyframes float-slow {
            0% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(10px, -15px) rotate(1deg); }
            66% { transform: translate(-15px, 10px) rotate(-1deg); }
            100% { transform: translate(0, 0) rotate(0deg); }
        }

        .animate-float { animation: float 6s ease-in-out infinite; }
        .animate-float-slow { animation: float-slow 10s ease-in-out infinite; }

        /* Swiper Hero Customization */
        .hero-swiper {
            width: 100%;
            height: 85vh;
            min-height: 600px;
        }

        .hero-slide {
            display: flex;
            align-items: center;
            overflow: hidden;
            position: relative;
        }

        .hero-content {
            z-index: 10;
            position: relative;
        }

        .hero-3d-wrapper {
            position: absolute;
            right: 5%;
            top: 50%;
            transform: translateY(-50%);
            width: 45%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
        }

        .hero-3d-item {
            position: relative;
            filter: drop-shadow(0 25px 50px rgba(0,0,0,0.15));
        }

        /* Glassmorphism */
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .dark .glass-card {
            background: rgba(9, 9, 11, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        /* Stat Counter Gradient */
        .stat-gradient {
            background: linear-gradient(135deg, var(--primary) 0%, #fb923c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Platform Accordion */
        .platform-accordion {
            width: 100%;
            height: 450px;
            display: flex;
            gap: 12px;
            padding: 0.5rem;
        }

        .platform-segment {
            height: 100%;
            flex: 1.5;
            overflow: hidden;
            cursor: pointer;
            border-radius: 2rem;
            transition: all 0.7s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .platform-segment:hover {
            flex: 8;
        }

        .platform-segment span {
            width: 100%;
            padding: .5em;
            text-align: center;
            transform: rotate(-90deg);
            transition: all 0.5s;
            text-transform: uppercase;
            color: white;
            letter-spacing: .1em;
            font-weight: 900;
            font-size: 0.85rem;
            pointer-events: none;
            position: absolute;
            top: 55%;
            left: 0;
            white-space: nowrap;
            opacity: 1;
            z-index: 5;
            display: block;
        }

        .platform-segment:hover span {
            transform: rotate(0);
            position: relative;
            top: 0;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            letter-spacing: .2em;
        }

        .platform-segment .segment-desc {
            max-width: 400px;
            opacity: 0;
            transition: opacity 0.4s ease-out 0.3s;
            color: white;
            text-align: center;
            font-weight: 500;
            padding: 0 2rem;
            display: none;
        }

        .platform-segment:hover .segment-desc {
            opacity: 1;
            display: block;
        }

        .platform-segment .segment-icon {
            font-size: 2rem;
            color: white;
            margin-bottom: 0;
            opacity: 1;
            transition: all 0.5s;
            position: absolute;
            top: 2rem;
            left: 50%;
            transform: translateX(-50%);
            z-index: 5;
        }

        .platform-segment:hover .segment-icon {
            position: relative;
            top: 0;
            left: 0;
            transform: scale(1.5);
            margin-bottom: 1rem;
        }

        @media (max-width: 1024px) {
            .platform-accordion {
                flex-direction: column;
                height: auto;
                gap: 12px;
            }
            .platform-segment {
                height: 120px;
                width: 100%;
                border-radius: 1.5rem;
            }
            .platform-segment:hover {
                flex: none;
                height: 300px;
            }
            .platform-segment span {
                transform: rotate(0);
                position: relative;
                min-width: auto;
            }
            .platform-segment .segment-icon {
                position: relative;
                top: 0;
                margin-bottom: 0;
                margin-right: 1rem;
            }
            .platform-segment:hover .segment-icon {
                margin-bottom: 1rem;
                margin-right: 0;
            }
            .platform-segment .segment-content {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 100%;
            }
            .platform-segment:hover .segment-content {
                flex-direction: column;
            }
        }

        .parallax-3d {
            transition: transform 0.2s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            will-change: transform;
        }

        /* Custom scrollbar for horizontal sections */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
    @endpush

    <!-- HERO CAROUSEL -->
    <div class="swiper hero-swiper">
        <div class="swiper-wrapper">
            <!-- Slide 1: Marketplace -->
            <div class="swiper-slide hero-slide bg-[#F8F9FA] dark:bg-[#0B0B0E]">
                <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop w-full grid grid-cols-1 lg:grid-cols-2 items-center gap-12">
                    <div class="hero-content space-y-8" data-aos="fade-right">
                        <span class="inline-block px-4 py-2 rounded-full bg-primary/10 text-primary text-xs font-black tracking-widest uppercase">Multi-Vendor Marketplace</span>
                        <h1 class="text-6xl md:text-8xl font-black text-on-surface leading-[0.9] tracking-tighter uppercase">
                            Global <br/><span class="text-primary">Market</span> <br/>Discovery
                        </h1>
                        <p class="text-xl text-on-surface-variant max-w-lg leading-relaxed font-medium">
                            Join a premium ecosystem connecting thousands of unique brands with conscious customers worldwide.
                        </p>
                        <div class="flex flex-wrap gap-4 pt-4">
                            <a href="#shops" class="bg-on-surface text-surface px-10 py-5 rounded-2xl font-black uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-2xl shadow-on-surface/20">
                                Explore Shops
                            </a>
                            <a href="{{ route('home.shops') }}" class="bg-surface-container border border-outline text-on-surface px-10 py-5 rounded-2xl font-black uppercase tracking-widest hover:bg-surface-container-highest transition-all">
                                Trending Products
                            </a>
                        </div>
                    </div>
                    <div class="relative hidden lg:flex justify-center items-center">
                        <div class="hero-3d-item animate-float">
                            <img src="https://raw.githubusercontent.com/microsoft/fluentui-emoji/main/assets/Convenience%20store/3D/convenience_store_3d.png" class="w-[450px] parallax-3d" data-speed="2" alt="Marketplace 3D">
                        </div>
                        <!-- Floating Decorative 3D -->
                        <img src="https://raw.githubusercontent.com/microsoft/fluentui-emoji/main/assets/Star/3D/star_3d.png" class="absolute top-20 -left-10 w-24 h-24 animate-float-slow parallax-3d" data-speed="5" style="animation-delay: -1s">
                        <img src="https://raw.githubusercontent.com/microsoft/fluentui-emoji/main/assets/Coin/3D/coin_3d.png" class="absolute bottom-20 right-0 w-32 h-32 animate-float parallax-3d" data-speed="4" style="animation-delay: -3s">

                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-primary/20 blur-3xl rounded-full"></div>
                    </div>
                </div>
            </div>

            <!-- Slide 2: Inventory -->
            <div class="swiper-slide hero-slide bg-[#FFF7ED] dark:bg-[#120F0C]">
                <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop w-full grid grid-cols-1 lg:grid-cols-2 items-center gap-12">
                    <div class="hero-content space-y-8">
                        <span class="inline-block px-4 py-2 rounded-full bg-primary/10 text-primary text-xs font-black tracking-widest uppercase">Smart Inventory</span>
                        <h1 class="text-6xl md:text-8xl font-black text-on-surface leading-[0.9] tracking-tighter uppercase">
                            Intelligent <br/><span class="text-primary">Stock</span> <br/>Control
                        </h1>
                        <p class="text-xl text-on-surface-variant max-w-lg leading-relaxed font-medium">
                            Manage multiple warehouses, track real-time stock movements, and never miss a sale with automated reordering.
                        </p>
                        <div class="flex flex-wrap gap-4 pt-4">
                            <a href="{{ route('register') }}" class="bg-primary text-on-primary px-10 py-5 rounded-2xl font-black uppercase tracking-widest hover:scale-105 transition-all shadow-xl shadow-primary/30">
                                Start Free Trial
                            </a>
                        </div>
                    </div>
                    <div class="relative hidden lg:flex justify-center items-center">
                        <div class="hero-3d-item animate-float" style="animation-delay: -2s">
                            <img src="https://raw.githubusercontent.com/microsoft/fluentui-emoji/main/assets/Package/3D/package_3d.png" class="w-[450px] parallax-3d" data-speed="2" alt="Inventory 3D">
                        </div>
                    </div>
                </div>
            </div>

             <!-- Slide 3: POS -->
             <div class="swiper-slide hero-slide bg-[#F0FDFA] dark:bg-[#0C1211]">
                <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop w-full grid grid-cols-1 lg:grid-cols-2 items-center gap-12">
                    <div class="hero-content space-y-8">
                        <span class="inline-block px-4 py-2 rounded-full bg-teal-500/10 text-teal-600 text-xs font-black tracking-widest uppercase text-teal-500">Universal POS</span>
                        <h1 class="text-6xl md:text-8xl font-black text-on-surface leading-[0.9] tracking-tighter uppercase">
                            Sell <br/><span class="text-teal-500">Every-</span> <br/>where
                        </h1>
                        <p class="text-xl text-on-surface-variant max-w-lg leading-relaxed font-medium">
                            A high-performance Point of Sale for retail, restaurants, and services. Cloud-synced and lightning fast.
                        </p>
                        <div class="flex flex-wrap gap-4 pt-4">
                            <a href="{{ route('register') }}" class="bg-on-surface text-surface px-10 py-5 rounded-2xl font-black uppercase tracking-widest hover:scale-105 transition-all">
                                Get Started
                            </a>
                        </div>
                    </div>
                    <div class="relative hidden lg:flex justify-center items-center">
                        <div class="hero-3d-item animate-float" style="animation-delay: -4s">
                            <img src="https://raw.githubusercontent.com/microsoft/fluentui-emoji/main/assets/Credit%20card/3D/credit_card_3d.png" class="w-[450px] parallax-3d" data-speed="3" alt="POS 3D">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 4: SCM -->
            <div class="swiper-slide hero-slide bg-[#EFF6FF] dark:bg-[#0C0F12]">
                <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop w-full grid grid-cols-1 lg:grid-cols-2 items-center gap-12">
                    <div class="hero-content space-y-8">
                        <span class="inline-block px-4 py-2 rounded-full bg-blue-500/10 text-blue-600 text-xs font-black tracking-widest uppercase">Supply Chain</span>
                        <h1 class="text-6xl md:text-8xl font-black text-on-surface leading-[0.9] tracking-tighter uppercase">
                            Unified <br/><span class="text-blue-600">Network</span> <br/>Flow
                        </h1>
                        <p class="text-xl text-on-surface-variant max-w-lg leading-relaxed font-medium">
                            Connect directly with suppliers, manage procurement, and streamline your distribution network.
                        </p>
                        <div class="flex flex-wrap gap-4 pt-4">
                            <a href="{{ route('register') }}" class="bg-blue-600 text-white px-10 py-5 rounded-2xl font-black uppercase tracking-widest hover:scale-105 transition-all">
                                Join Network
                            </a>
                        </div>
                    </div>
                    <div class="relative hidden lg:flex justify-center items-center">
                        <div class="hero-3d-item animate-float">
                            <img src="https://raw.githubusercontent.com/microsoft/fluentui-emoji/main/assets/Handshake/3D/handshake_3d.png" class="w-[450px] parallax-3d" data-speed="2" alt="SCM 3D">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="swiper-pagination"></div>
        <div class="swiper-button-next !text-on-surface/20 hover:!text-primary transition-colors"></div>
        <div class="swiper-button-prev !text-on-surface/20 hover:!text-primary transition-colors"></div>
    </div>

    <!-- CALL TO ACTION: START BUSINESS -->
    <section class="relative py-12 z-20 px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto">
        <div class="bg-primary p-12 md:p-20 rounded-[3rem] shadow-2xl shadow-primary/20 flex flex-col md:flex-row items-center justify-between gap-12 overflow-hidden relative group" data-aos="zoom-in-up">
            <div class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full -mr-20 -mt-20 blur-3xl group-hover:scale-125 transition-transform duration-1000"></div>
            <div class="relative z-10 text-center md:text-left space-y-4">
                <h2 class="text-4xl md:text-6xl font-black text-white uppercase tracking-tighter leading-none">Ready to start <br/>your legacy?</h2>
                <p class="text-white/80 text-lg font-medium max-w-md">Join 500+ businesses growing on Flowexa. Setup your shop in under 2 minutes.</p>
            </div>
            <div class="relative z-10 flex flex-col sm:flex-row gap-4 w-full md:w-auto">
                <a href="{{ route('register') }}" class="bg-white text-primary px-12 py-6 rounded-[2rem] font-black uppercase tracking-widest text-center hover:scale-105 transition-all shadow-xl">
                    Create Your Shop
                </a>
                <a href="{{ route('home.shops') }}" class="bg-on-surface/20 text-white border border-white/30 backdrop-blur-md px-12 py-6 rounded-[2rem] font-black uppercase tracking-widest text-center hover:bg-on-surface/30 transition-all">
                    Explore Market
                </a>
            </div>
        </div>
    </section>

    <!-- FEATURED SHOPS -->
    <section id="shops" class="py-32 px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto overflow-hidden relative">
        <div class="absolute top-0 right-0 -mr-20 -mt-20 opacity-20 pointer-events-none hidden lg:block">
            <img src="https://raw.githubusercontent.com/microsoft/fluentui-emoji/main/assets/Department%20store/3D/department_store_3d.png" class="w-96 parallax-3d" data-speed="-2">
        </div>
        <div class="flex flex-col md:flex-row items-end justify-between mb-20 gap-8">
            <div class="max-w-2xl" data-aos="fade-up">
                <span class="text-primary font-black uppercase tracking-[0.3em] text-xs block mb-4">Discovery</span>
                <h2 class="text-5xl md:text-7xl font-black text-on-surface uppercase tracking-tighter leading-none mb-6">Featured <br/>Vendors</h2>
                <p class="text-on-surface-variant text-xl font-medium leading-relaxed">Curated selection of high-performance brands operating on the Flowexa ecosystem.</p>
            </div>
            <a href="{{ route('home.shops') }}" class="inline-flex items-center gap-4 text-on-surface font-black uppercase tracking-widest text-sm hover:text-primary transition-all border-b-2 border-outline hover:border-primary pb-2" data-aos="fade-left">
                View All Vendors <span class="material-symbols-outlined">arrow_outward</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            @foreach($tenants as $tenant)
                <div class="group relative bg-surface-container-lowest border border-outline rounded-[3rem] overflow-hidden hover:border-primary/40 transition-all duration-700 hover:shadow-[0_40px_80px_-20px_rgba(0,0,0,0.1)]" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <!-- Shop Banner -->
                    <div class="h-60 overflow-hidden relative">
                        <img src="{{ $tenant->settings['banner_url'] ?? 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?q=80&w=2070&auto=format&fit=crop' }}"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                        <div class="absolute inset-0 bg-gradient-to-t from-surface-container-lowest via-transparent to-transparent"></div>
                    </div>

                    <!-- Shop Details -->
                    <div class="p-10 pt-0 relative">
                        <!-- Logo (Floats over banner) -->
                        <div class="w-24 h-24 rounded-3xl bg-surface border-4 border-surface-container-lowest shadow-2xl -mt-12 mb-6 overflow-hidden transform transition-transform group-hover:rotate-3 group-hover:scale-105 duration-500">
                            <img src="{{ $tenant->settings['logo_url'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($tenant->name) . '&background=random' }}" class="w-full h-full object-cover">
                        </div>

                        <div class="space-y-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-2xl font-black text-on-surface uppercase tracking-tighter">{{ $tenant->name }}</h3>
                                    <p class="text-xs font-bold text-primary uppercase tracking-widest">{{ $tenant->country ?? 'Ghana' }}</p>
                                </div>
                                <div class="flex flex-col items-end">
                                    <div class="flex text-primary">
                                        @for($i=0; $i<5; $i++) <span class="material-symbols-outlined text-xs {{ $i < 4 ? 'fill-1' : '' }}" style="font-variation-settings: 'FILL' {{ $i < 4 ? '1' : '0' }};">star</span> @endfor
                                    </div>
                                    <span class="text-[10px] font-black text-on-surface-variant uppercase tracking-widest mt-1">4.8 Rating</span>
                                </div>
                            </div>

                            <p class="text-sm text-on-surface-variant font-medium line-clamp-2 leading-relaxed h-10 italic">
                                "{{ $tenant->settings['description'] ?? 'Premium direct-to-consumer collections and unique boutique experiences.' }}"
                            </p>

                            <div class="grid grid-cols-2 gap-4 py-4 border-y border-outline/50">
                                <div class="text-center border-r border-outline/50">
                                    <p class="text-lg font-black text-on-surface leading-none">{{ $tenant->products_count }}</p>
                                    <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest mt-1">Products</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-lg font-black text-on-surface leading-none">1.2k</p>
                                    <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest mt-1">Followers</p>
                                </div>
                            </div>

                            <a href="{{ route('home.shop', $tenant) }}" class="w-full flex items-center justify-center gap-3 bg-on-surface text-surface py-5 rounded-[1.5rem] font-black uppercase tracking-widest text-xs hover:bg-primary hover:text-white transition-all transform group-hover:translate-y-[-5px]">
                                Visit Store <span class="material-symbols-outlined text-sm">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- TRENDING PRODUCTS -->
    <section class="py-32 bg-surface-container/30 border-y border-outline/40">
        <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop">
            <div class="flex items-center justify-between mb-20">
                <div data-aos="fade-up">
                    <h2 class="text-5xl md:text-6xl font-black text-on-surface uppercase tracking-tighter leading-none mb-4">Trending <br/>Drops</h2>
                    <p class="text-on-surface-variant text-lg font-medium">The most wanted items across our network this week.</p>
                </div>
                <div class="flex gap-4">
                    <button class="w-16 h-16 rounded-full border border-outline flex items-center justify-center hover:bg-primary hover:border-primary hover:text-white transition-all">
                        <span class="material-symbols-outlined">west</span>
                    </button>
                    <button class="w-16 h-16 rounded-full border border-outline flex items-center justify-center hover:bg-primary hover:border-primary hover:text-white transition-all">
                        <span class="material-symbols-outlined">east</span>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($trendingProducts as $product)
                    <div class="group bg-surface rounded-[2.5rem] overflow-hidden border border-outline/60 hover:border-primary/40 transition-all duration-500 hover:shadow-2xl shadow-primary/5" data-aos="zoom-in" data-aos-delay="{{ $loop->index * 50 }}">
                        <div class="relative aspect-[3/4] overflow-hidden">
                            <img src="{{ $product->imageUrl() }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            <div class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>

                            <!-- Action Overlays -->
                            <div class="absolute inset-0 flex flex-col items-center justify-center gap-3 opacity-0 group-hover:opacity-100 translate-y-10 group-hover:translate-y-0 transition-all duration-500">
                                <button class="w-14 h-14 rounded-full bg-white text-on-surface flex items-center justify-center shadow-xl hover:bg-primary hover:text-white transition-colors" title="Quick View">
                                    <span class="material-symbols-outlined">visibility</span>
                                </button>
                                <button class="w-14 h-14 rounded-full bg-white text-on-surface flex items-center justify-center shadow-xl hover:bg-primary hover:text-white transition-colors" title="Add to Cart">
                                    <span class="material-symbols-outlined">add_shopping_cart</span>
                                </button>
                            </div>

                            <button class="absolute top-6 right-6 w-12 h-12 rounded-full glass-card flex items-center justify-center text-on-surface hover:bg-primary hover:text-white transition-all">
                                <span class="material-symbols-outlined text-xl">favorite</span>
                            </button>
                        </div>
                        <div class="p-8 space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-black text-primary uppercase tracking-[0.2em]">{{ $product->tenant->name }}</span>
                                <div class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[10px] fill-1 text-primary">star</span>
                                    <span class="text-[10px] font-black text-on-surface-variant">4.9</span>
                                </div>
                            </div>
                            <h4 class="font-black text-on-surface text-xl uppercase tracking-tighter truncate">{{ $product->name }}</h4>
                            <div class="flex items-baseline gap-2">
                                <p class="font-black text-on-surface text-2xl">{{ number_format($product->unit_price, 2) }}</p>
                                <span class="text-xs font-black text-on-surface-variant uppercase tracking-widest">GHS</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- PLATFORM FEATURES -->
    <section class="py-32 bg-surface px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto overflow-hidden">
        <div class="text-center max-w-3xl mx-auto mb-24" data-aos="fade-up">
            <span class="text-primary font-black uppercase tracking-[0.3em] text-xs block mb-4">Core Platform</span>
            <h2 class="text-5xl md:text-7xl font-black text-on-surface uppercase tracking-tighter leading-none mb-8">One Workspace. <br/>Infinite <span class="text-primary">Power.</span></h2>
            <p class="text-on-surface-variant text-xl font-medium">Flowexa consolidates your entire business operation into a high-performance digital environment.</p>
        </div>

        <div class="platform-accordion" data-aos="zoom-in">
            @php
                $features = [
                    ['icon' => 'hub', 'title' => 'Multi-Vendor', 'desc' => 'Host multiple independent shops on a single high-traffic domain.', 'color' => '#6366f1'],
                    ['icon' => 'inventory_2', 'title' => 'Inventory', 'desc' => 'Multi-warehouse stock tracking with real-time sync and reorder alerts.', 'color' => '#10b981'],
                    ['icon' => 'point_of_sale', 'title' => 'Unified POS', 'desc' => 'Lightning fast checkout for retail, restaurants, and mobile sellers.', 'color' => '#f97316'],
                    ['icon' => 'local_shipping', 'title' => 'Supply Chain', 'desc' => 'Integrated procurement and distribution management for wholesalers.', 'color' => '#3b82f6'],
                    ['icon' => 'query_stats', 'title' => 'BI Analytics', 'desc' => 'Advanced sales reports, customer heatmaps, and financial metrics.', 'color' => '#f43f5e'],
                    ['icon' => 'groups', 'title' => 'CRM & Loyalty', 'desc' => 'Manage customer segments and reward systems to drive repeat sales.', 'color' => '#8b5cf6'],
                    ['icon' => 'payments', 'title' => 'Secure Pay', 'desc' => 'Integrated payment gateways supporting Cards, MoMo, and Transfers.', 'color' => '#06b6d4'],
                    ['icon' => 'notifications_active', 'title' => 'Real-Time Alerts', 'desc' => 'Instant notifications for orders, stock levels, and arrival tracking.', 'color' => '#ef4444'],
                    ['icon' => 'smartphone', 'title' => 'Mobile Optimized', 'desc' => 'Full control of your business from any device, anywhere in the world.', 'color' => '#71717a'],
                ];
            @endphp

            @foreach($features as $f)
                <div class="platform-segment" style="background-color: {{ $f['color'] }};">
                    <div class="segment-content">
                        <span class="material-symbols-outlined segment-icon">{{ $f['icon'] }}</span>
                        <span>{{ $f['title'] }}</span>
                        <p class="segment-desc">{{ $f['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- STATISTICS SECTION -->
    <section class="py-32 relative overflow-hidden bg-on-surface text-surface">
        <div class="absolute inset-0 z-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
        <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop relative z-10">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-12 md:gap-24 text-center">
                <div data-aos="zoom-in">
                    <p class="text-6xl md:text-8xl font-black mb-2 stat-gradient">{{ $stats['shops'] }}</p>
                    <p class="text-xs md:text-sm font-black uppercase tracking-[0.4em] text-white/50">Active Shops</p>
                </div>
                <div data-aos="zoom-in" data-aos-delay="100">
                    <p class="text-6xl md:text-8xl font-black mb-2 stat-gradient">{{ number_format($stats['products'] / 1000, 1) }}k</p>
                    <p class="text-xs md:text-sm font-black uppercase tracking-[0.4em] text-white/50">Products</p>
                </div>
                <div data-aos="zoom-in" data-aos-delay="200">
                    <p class="text-6xl md:text-8xl font-black mb-2 stat-gradient">{{ number_format($stats['orders']) }}</p>
                    <p class="text-xs md:text-sm font-black uppercase tracking-[0.4em] text-white/50">Orders Done</p>
                </div>
                <div data-aos="zoom-in" data-aos-delay="300">
                    <p class="text-6xl md:text-8xl font-black mb-2 stat-gradient">99%</p>
                    <p class="text-xs md:text-sm font-black uppercase tracking-[0.4em] text-white/50">Satisfaction</p>
                </div>
            </div>
        </div>
    </section>

    <!-- HOW IT WORKS -->
    <section class="py-32 px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto overflow-hidden">
        <div class="text-center max-w-3xl mx-auto mb-24" data-aos="fade-up">
            <span class="text-primary font-black uppercase tracking-[0.3em] text-xs block mb-4">Operations</span>
            <h2 class="text-5xl md:text-7xl font-black text-on-surface uppercase tracking-tighter leading-none mb-8">Launch to Profit <br/>in <span class="text-primary">4 Steps</span></h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 relative">
            <!-- Connecting Line -->
            <div class="hidden md:block absolute top-1/4 left-0 w-full h-[2px] bg-outline/40 z-0"></div>

            @php
                $steps = [
                    ['icon' => 'https://raw.githubusercontent.com/microsoft/fluentui-emoji/main/assets/Memo/3D/memo_3d.png', 'title' => 'Register', 'desc' => 'Sign up as a tenant and set your unique business identity.'],
                    ['icon' => 'https://raw.githubusercontent.com/microsoft/fluentui-emoji/main/assets/Artist%20palette/3D/artist_palette_3d.png', 'title' => 'Customise', 'desc' => 'Set your theme, colors, and physical shop location.'],
                    ['icon' => 'https://raw.githubusercontent.com/microsoft/fluentui-emoji/main/assets/Package/3D/package_3d.png', 'title' => 'Import', 'desc' => 'Upload your products and configure your stock levels.'],
                    ['icon' => 'https://raw.githubusercontent.com/microsoft/fluentui-emoji/main/assets/Rocket/3D/rocket_3d.png', 'title' => 'Sell', 'desc' => 'Go live and start accepting orders across the world.'],
                ];
            @endphp

            @foreach($steps as $index => $step)
                <div class="relative z-10 flex flex-col items-center text-center p-8 bg-surface border border-outline/60 rounded-[3rem] hover:border-primary/40 transition-all group" data-aos="fade-up" data-aos-delay="{{ $index * 150 }}">
                    <div class="w-32 h-32 rounded-full flex items-center justify-center mb-8 transform transition-transform group-hover:scale-110 duration-500">
                        <img src="{{ $step['icon'] }}" class="w-full h-full object-contain">
                    </div>
                    <div class="absolute -top-4 -right-4 w-12 h-12 rounded-full bg-on-surface text-surface flex items-center justify-center font-black text-xl shadow-2xl">
                        {{ $index + 1 }}
                    </div>
                    <h3 class="text-2xl font-black text-on-surface uppercase tracking-tighter mb-4">{{ $step['title'] }}</h3>
                    <p class="text-on-surface-variant font-medium">{{ $step['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <!-- MOBILE PROMOTION -->
    <section class="py-32 px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto">
        <div class="bg-surface-container-highest rounded-[4rem] p-12 md:p-24 grid grid-cols-1 lg:grid-cols-2 items-center gap-16 overflow-hidden relative">
            <div class="space-y-10" data-aos="fade-right">
                <span class="text-primary font-black uppercase tracking-[0.3em] text-xs">Omnichannel</span>
                <h2 class="text-5xl md:text-7xl font-black text-on-surface uppercase tracking-tighter leading-none">Your Business <br/>in Your <span class="text-primary">Pocket.</span></h2>
                <p class="text-on-surface-variant text-xl font-medium leading-relaxed">Download the Flowexa Partner app to manage orders, scan barcodes, and track sales on the go.</p>

                <div class="flex flex-wrap gap-6">
                    <a href="#" class="hover:scale-105 transition-all">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/3/3c/Download_on_the_App_Store_Badge.svg" class="h-14" alt="Download on the App Store">
                    </a>
                    <a href="#" class="hover:scale-105 transition-all">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg" class="h-14" alt="Get it on Google Play">
                    </a>
                </div>
            </div>

            <div class="relative flex justify-center lg:justify-end" data-aos="fade-left">
                <!-- CSS Phone Mockup (Front View) -->
                <div class="relative w-[320px] h-[640px] bg-on-surface rounded-[3.5rem] border-[12px] border-[#1f1f23] shadow-[0_50px_100px_-20px_rgba(0,0,0,0.5)] overflow-hidden animate-float parallax-3d" data-speed="4">
                    <!-- High-Fidelity Screen Content -->
                    <div class="absolute inset-0 bg-white flex flex-col pt-12">
                        <!-- App Header -->
                        <div class="px-6 py-8 flex flex-col items-center text-center space-y-6">
                            <div class="w-24 h-24 bg-primary rounded-[2rem] flex items-center justify-center shadow-2xl transform rotate-3">
                                <img src="https://raw.githubusercontent.com/microsoft/fluentui-emoji/main/assets/Rocket/3D/rocket_3d.png" class="w-14 h-14" alt="Flowexa Logo">
                            </div>
                            <div class="space-y-1">
                                <h3 class="text-3xl font-black text-on-surface uppercase tracking-tighter">Flowexa</h3>
                                <p class="text-sm font-black text-primary uppercase tracking-[0.2em]">Partner Dashboard</p>
                            </div>
                        </div>

                        <!-- App Stats Grid -->
                        <div class="px-6 grid grid-cols-2 gap-4">
                            <div class="bg-surface-container p-4 rounded-3xl border border-outline/50 flex flex-col items-center justify-center space-y-1">
                                <p class="text-[10px] font-black uppercase text-on-surface-variant">Today's Sales</p>
                                <p class="text-xl font-black text-on-surface">GH₵ 4.2k</p>
                            </div>
                            <div class="bg-surface-container p-4 rounded-3xl border border-outline/50 flex flex-col items-center justify-center space-y-1">
                                <p class="text-[10px] font-black uppercase text-on-surface-variant">New Orders</p>
                                <p class="text-xl font-black text-on-surface">12</p>
                            </div>
                        </div>

                        <!-- QR / Sync Section -->
                        <div class="mt-8 px-6">
                            <div class="w-full bg-on-surface p-6 rounded-[2.5rem] flex flex-col items-center text-center space-y-4 shadow-xl">
                                <span class="material-symbols-outlined text-5xl text-primary animate-pulse">qr_code_scanner</span>
                                <p class="text-xs font-black uppercase text-white/90">Sync Shop Inventory</p>
                                <button class="w-full py-3 bg-white/10 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl border border-white/20">
                                    Open Scanner
                                </button>
                            </div>
                        </div>

                        <!-- Footer Navigation Placeholder -->
                        <div class="mt-auto border-t border-outline/30 px-8 py-6 flex justify-between items-center text-on-surface-variant">
                            <span class="material-symbols-outlined fill-1 text-primary">dashboard</span>
                            <span class="material-symbols-outlined">inventory_2</span>
                            <span class="material-symbols-outlined">analytics</span>
                            <span class="material-symbols-outlined">settings</span>
                        </div>
                    </div>

                    <!-- Dynamic Island / Notch -->
                    <div class="absolute top-4 left-1/2 -translate-x-1/2 w-32 h-8 bg-on-surface rounded-full z-20 flex items-center justify-center">
                         <div class="w-1.5 h-1.5 rounded-full bg-white/10 ml-auto mr-4"></div>
                    </div>

                    <!-- Glass Reflection -->
                    <div class="absolute inset-0 pointer-events-none bg-gradient-to-tr from-white/10 via-transparent to-transparent opacity-30 z-30"></div>
                </div>

                <!-- Floating App Badges -->
                <div class="absolute -bottom-10 -left-24 flex flex-col gap-4 animate-float-slow parallax-3d z-40" data-speed="6">
                    <div class="group cursor-pointer">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/3/3c/Download_on_the_App_Store_Badge.svg" class="h-14 shadow-2xl transition-transform group-hover:scale-105" alt="App Store">
                    </div>
                    <div class="group cursor-pointer">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg" class="h-14 shadow-2xl transition-transform group-hover:scale-105" alt="Google Play">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ SECTION -->
    <section class="py-32 px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-20" data-aos="fade-up">
                <h2 class="text-5xl md:text-7xl font-black text-on-surface uppercase tracking-tighter leading-none mb-8">FAQ</h2>
                <p class="text-on-surface-variant text-xl font-medium">Common questions about the ecosystem.</p>
            </div>

            <div class="space-y-4">
                @php
                    $faqs = [
                        ['q' => 'How much does it cost to start a shop?', 'a' => 'Creating a basic shop is free. We offer premium tiers for advanced inventory and supply chain features.'],
                        ['q' => 'Can I use my own domain?', 'a' => 'Yes, our premium plans allow you to connect a custom domain to your storefront.'],
                        ['q' => 'Does Flowexa handle delivery?', 'a' => 'We have integrated Bolt and Yango delivery services (coming soon) and provide native support for shop pickup.'],
                        ['q' => 'Is my data secure?', 'a' => 'We use enterprise-grade encryption and isolated tenant databases to ensure your business data is 100% private and secure.'],
                    ];
                @endphp

                @foreach($faqs as $i => $faq)
                    <div class="bg-surface-container rounded-[2rem] border border-outline/40 overflow-hidden" data-aos="fade-up" data-aos-delay="{{ $i * 100 }}">
                        <button class="w-full px-10 py-8 flex items-center justify-between text-left group" onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('.arrow').classList.toggle('rotate-180')">
                            <span class="text-xl font-black text-on-surface uppercase tracking-tight group-hover:text-primary transition-colors">{{ $faq['q'] }}</span>
                            <span class="material-symbols-outlined transition-transform duration-300 arrow">expand_more</span>
                        </button>
                        <div class="hidden px-10 pb-8">
                            <p class="text-on-surface-variant text-lg font-medium border-t border-outline/40 pt-6 leading-relaxed">{{ $faq['a'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- NEWSLETTER -->
    <section class="py-32 px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto">
        <div class="bg-on-surface text-surface rounded-[4rem] p-12 md:p-24 flex flex-col lg:flex-row items-center justify-between gap-16 overflow-hidden relative">
            <div class="max-w-xl space-y-6 text-center lg:text-left" data-aos="fade-right">
                <h2 class="text-4xl md:text-6xl font-black uppercase tracking-tighter leading-tight">Stay ahead of <br/>the <span class="text-primary">curve.</span></h2>
                <p class="text-white/60 text-lg font-medium">Join 10,000+ entrepreneurs receiving our weekly commerce insights.</p>
            </div>
            <form class="w-full max-w-md relative" data-aos="fade-left">
                <input type="email" placeholder="Your Email Address" class="w-full bg-white/10 border-white/20 rounded-[2rem] py-6 px-10 text-white placeholder:text-white/40 focus:ring-2 focus:ring-primary/50 transition-all outline-none">
                <button type="submit" class="absolute right-3 top-3 bottom-3 bg-primary text-on-primary px-10 rounded-[1.5rem] font-black uppercase tracking-widest text-xs hover:scale-95 transition-all">Subscribe</button>
            </form>
        </div>
    </section>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize AOS
            AOS.init({
                duration: 1000,
                once: true,
                offset: 100
            });

            // Initialize Swiper
            const swiper = new Swiper('.hero-swiper', {
                loop: true,
                autoplay: {
                    delay: 6000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                effect: 'fade',
                fadeEffect: {
                    crossFade: true
                }
            });

            // Mouse Parallax Effect
            document.addEventListener('mousemove', (e) => {
                const elements = document.querySelectorAll('.parallax-3d');
                const mouseX = e.clientX;
                const mouseY = e.clientY;

                elements.forEach(el => {
                    const speed = el.getAttribute('data-speed') || 2;
                    const x = (window.innerWidth - mouseX * speed) / 100;
                    const y = (window.innerHeight - mouseY * speed) / 100;

                    el.style.transform = `translateX(${x}px) translateY(${y}px)`;
                });
            });
        });
    </script>
    @endpush
</x-layouts.shop>
