<x-layouts.landing title="Privacy Policy - {{ config('app.name', 'flowexa') }}">
    <main class="max-w-4xl mx-auto px-4 py-32 bg-background">
        <div class="mb-12">
            <h1 class="text-5xl font-extrabold mb-4 text-headings">Privacy <span class="text-brand-primary">Policy</span></h1>
            <p class="text-lg text-text-secondary">Last updated: {{ date('F d, Y') }}</p>
        </div>

        <div class="space-y-8 text-body-text">
            <section class="policy-section">
                <div class="section-header">
                    <i class="fa-solid fa-database text-brand-primary text-2xl"></i>
                    <h2 class="text-2xl font-bold text-headings">Information We Collect</h2>
                </div>
                <p class="text-text-secondary leading-relaxed">We collect information you provide directly to us when you create an account, use our services, or communicate with us. This may include your name, email address, phone number, and business details.</p>
            </section>

            <section class="policy-section">
                <div class="section-header">
                    <i class="fa-solid fa-cogs text-brand-primary text-2xl"></i>
                    <h2 class="text-2xl font-bold text-headings">How We Use Your Information</h2>
                </div>
                <p class="text-text-secondary leading-relaxed">We use the information we collect to provide, maintain, and improve our services, to develop new ones, and to protect flowexa and our users.</p>
            </section>

            <section class="policy-section">
                <div class="section-header">
                    <i class="fa-solid fa-shield text-brand-primary text-2xl"></i>
                    <h2 class="text-2xl font-bold text-headings">Data Security</h2>
                </div>
                <p class="text-text-secondary leading-relaxed">We use enterprise-grade security measures to protect your data from unauthorized access, alteration, disclosure, or destruction. This includes MFA, encryption at rest, and secure API protocols.</p>
            </section>
        </div>
    </main>
</x-layouts.landing>
