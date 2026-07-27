<x-layouts.landing title="flowexa - Smart Business Management">
    <!-- Hero Section -->
    <section id="home" class="pt-32 pb-20 px-4 relative overflow-hidden bg-surface-secondary">
        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row items-center justify-between">
            <div class="lg:w-1/2 mb-12 lg:mb-0" data-aos="fade-right">
                <h1 class="text-5xl lg:text-7xl font-extrabold mb-6 leading-tight text-headings">
                    Streamline Your <span class="text-brand-primary">Business</span> Operations
                </h1>
                <p class="text-lg text-text-secondary mb-8 max-w-lg leading-relaxed">
                    flowexa is an all-in-one ecosystem for supply chain, inventory, CRM, and marketing. Scale your business with data-driven insights.
                </p>
                <div class="flex space-x-4">
                    <a href="{{ route('register') }}" class="bg-brand-primary text-white px-8 py-4 rounded-full font-bold shadow-xl shadow-primary/30 hover:scale-105 transition transform">Start Free Trial</a>
                    <a href="#services" class="border-2 border-brand-primary text-brand-primary px-8 py-4 rounded-full font-bold hover:bg-primary/10 transition">Explore Features</a>
                </div>
            </div>
            <div class="lg:w-1/2 relative" data-aos="fade-left">
                <div class="w-full h-[400px] lg:h-[500px] bg-gradient-to-br from-orange-400/20 to-teal-400/20 rounded-3xl flex items-center justify-center relative overflow-hidden">
                    <div class="absolute inset-0 flex items-center justify-center opacity-30">
                            <svg class="w-64 h-64 text-brand-primary" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path></svg>
                    </div>
                    <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Dashboard Preview" class="rounded-xl shadow-2xl relative z-10 w-4/5 border border-white/20">
                </div>
            </div>
        </div>

        <!-- Wave Separator -->
        <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-[0]">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="relative block w-[calc(100%+1.3px)] h-[60px] fill-background">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z"></path>
            </svg>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-24 px-4 bg-background">
        <div class="max-w-7xl mx-auto text-center mb-16" data-aos="fade-up">
            <h2 class="text-4xl font-bold mb-4 text-headings">Our <span class="text-brand-primary">Services</span></h2>
            <p class="text-text-secondary max-w-2xl mx-auto">Everything you need to manage and grow your enterprise in one unified platform.</p>
        </div>

        <div class="max-w-7xl mx-auto flex flex-wrap justify-center gap-8">
            <!-- Service Card 1 -->
            <div class="modern-card" data-aos="zoom-in" data-aos-delay="100">
                <p class="card-title">
                    <i class="fa-solid fa-boxes-stacked mr-3"></i>Inventory & POS
                </p>
                <p class="small-desc">
                    Real-time tracking of stock levels and seamless point-of-sale integration for physical stores.
                </p>
                <div class="go-corner">
                    <div class="go-arrow">→</div>
                </div>
            </div>

            <!-- Service Card 2 -->
            <div class="modern-card" data-aos="zoom-in" data-aos-delay="200">
                <p class="card-title">
                    <i class="fa-solid fa-truck-fast mr-3"></i>Supply Chain
                </p>
                <p class="small-desc">
                    Automate purchase orders, manage suppliers, and track shipments across the globe.
                </p>
                <div class="go-corner">
                    <div class="go-arrow">→</div>
                </div>
            </div>

            <!-- Service Card 3 -->
            <div class="modern-card" data-aos="zoom-in" data-aos-delay="300">
                <p class="card-title">
                    <i class="fa-solid fa-users-gear mr-3"></i>CRM & Loyalty
                </p>
                <p class="small-desc">
                    Deepen customer relationships with loyalty programs and personalized interaction history.
                </p>
                <div class="go-corner">
                    <div class="go-arrow">→</div>
                </div>
            </div>

            <!-- Service Card 4 -->
            <div class="modern-card" data-aos="zoom-in" data-aos-delay="400">
                <p class="card-title">
                    <i class="fa-solid fa-chart-line mr-3"></i>Marketing
                </p>
                <p class="small-desc">
                    Launch multi-channel ad campaigns and track ROI with advanced analytics dashboards.
                </p>
                <div class="go-corner">
                    <div class="go-arrow">→</div>
                </div>
            </div>
        </div>

        <!-- Wave Separator -->
        <div class="relative w-full h-[60px] mt-12 overflow-hidden leading-[0]">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="relative block w-[calc(100%+1.3px)] h-[60px] fill-surface-secondary">
                <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5,73.84-4.36,147.54,16.88,218.2,35.26,69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113,0,1200,0V0H0Z"></path>
            </svg>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-24 px-4 bg-surface-secondary relative overflow-hidden">
        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row items-center gap-16">
            <div class="lg:w-1/2 order-2 lg:order-1" data-aos="fade-right">
                <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="About Team" class="rounded-3xl shadow-2xl">
            </div>
            <div class="lg:w-1/2 order-1 lg:order-2" data-aos="fade-left">
                <span class="text-brand-primary font-bold tracking-widest uppercase text-sm">About flowexa</span>
                <h2 class="text-4xl font-bold mt-4 mb-6 text-headings">Empowering Modern Enterprises with <span class="text-brand-primary">Innovation</span></h2>
                <p class="text-text-secondary mb-6 leading-relaxed">
                    Founded with a mission to simplify complex business workflows, flowexa provides a robust architecture that bridges the gap between digital stores and physical operations.
                </p>
                <ul class="space-y-4 mb-8 text-text-secondary">
                    <li class="flex items-center space-x-3">
                        <i class="fa-solid fa-check-circle text-success"></i>
                        <span>Cloud-native infrastructure for 100% uptime.</span>
                    </li>
                    <li class="flex items-center space-x-3">
                        <i class="fa-solid fa-check-circle text-success"></i>
                        <span>Enterprise-grade security and MFA.</span>
                    </li>
                    <li class="flex items-center space-x-3">
                        <i class="fa-solid fa-check-circle text-success"></i>
                        <span>API-first approach for easy integrations.</span>
                    </li>
                </ul>
                <a href="{{ route('about') }}" class="text-brand-primary font-bold hover:underline">Learn more about our journey &rarr;</a>
            </div>
        </div>
    </section>
</x-layouts.landing>
