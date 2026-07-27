<x-layouts.landing title="About Us - {{ config('app.name', 'flowexa') }}">
    <main class="pt-32 pb-24 bg-background">
        <div class="max-w-4xl mx-auto px-4">
            <h1 class="text-5xl font-extrabold mb-8 text-headings" data-aos="fade-up">Our <span class="text-brand-primary">Story</span></h1>

            <div class="prose prose-lg dark:prose-invert max-w-none space-y-8 text-body-text" data-aos="fade-up" data-aos-delay="100">
                <p class="text-xl text-text-secondary leading-relaxed">
                    flowexa was born out of a simple observation: businesses are drowning in disconnected tools.
                </p>

                <p>
                    Our founders spent years working in supply chain management and retail technology. They saw first-hand how much efficiency is lost when your inventory doesn't talk to your CRM, or when your marketing campaigns have no visibility into your current stock levels.
                </p>

                <h2 class="text-3xl font-bold text-brand-primary pt-8">The Mission</h2>
                <p>
                    Our mission is to synchronize every aspect of your business operations. We believe that by providing a unified data layer, we empower business owners to make better decisions, faster.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 py-12">
                    <div class="modern-value-card">
                        <div class="value-icon">
                            <i class="fa-solid fa-lightbulb text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-4 text-headings">Innovation First</h3>
                        <p class="text-text-secondary">We constantly push the boundaries of what's possible with automation and real-time analytics.</p>
                    </div>
                    <div class="modern-value-card">
                        <div class="value-icon">
                            <i class="fa-solid fa-heart text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-4 text-headings">Customer Centric</h3>
                        <p class="text-text-secondary">Your success is our metric. We build features based on the real-world needs of our growing community.</p>
                    </div>
                </div>

                <h2 class="text-3xl font-bold text-brand-primary pt-8">The Future</h2>
                <p>
                    As we grow, flowexa continues to evolve. From AI-driven forecasting to advanced ecommerce builders, we are building the foundation for the next generation of global commerce.
                </p>
            </div>
        </div>
    </main>
</x-layouts.landing>
