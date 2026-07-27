<x-layouts.landing title="Terms of Service - {{ config('app.name', 'flowexa') }}">
    <main class="max-w-4xl mx-auto px-4 py-32 bg-background">
        <div class="mb-12">
            <h1 class="text-5xl font-extrabold mb-4 text-headings">Terms of <span class="text-brand-primary">Service</span></h1>
            <p class="text-lg text-text-secondary">Last updated: {{ date('F d, Y') }}</p>
        </div>

        <div class="space-y-8 text-body-text">
            <section class="policy-section">
                <div class="section-header">
                    <span class="section-number">1</span>
                    <h2 class="text-2xl font-bold text-headings">Acceptance of Terms</h2>
                </div>
                <p class="text-text-secondary leading-relaxed">By accessing and using flowexa, you agree to be bound by these Terms of Service and all applicable laws and regulations. If you do not agree with any of these terms, you are prohibited from using or accessing this site.</p>
            </section>

            <section class="policy-section">
                <div class="section-header">
                    <span class="section-number">2</span>
                    <h2 class="text-2xl font-bold text-headings">Use License</h2>
                </div>
                <p class="text-text-secondary leading-relaxed">Permission is granted to temporarily download one copy of the materials (information or software) on flowexa's website for personal, non-commercial transitory viewing only.</p>
            </section>

            <section class="policy-section">
                <div class="section-header">
                    <span class="section-number">3</span>
                    <h2 class="text-2xl font-bold text-headings">Disclaimer</h2>
                </div>
                <p class="text-text-secondary leading-relaxed">The materials on flowexa's website are provided on an 'as is' basis. flowexa makes no warranties, expressed or implied, and hereby disclaims and negates all other warranties including, without limitation, implied warranties or conditions of merchantability, fitness for a particular purpose, or non-infringement of intellectual property or other violation of rights.</p>
            </section>

            <section class="policy-section">
                <div class="section-header">
                    <span class="section-number">4</span>
                    <h2 class="text-2xl font-bold text-headings">Limitations</h2>
                </div>
                <p class="text-text-secondary leading-relaxed">In no event shall flowexa or its suppliers be liable for any damages (including, without limitation, damages for loss of data or profit, or due to business interruption) arising out of the use or inability to use the materials on flowexa's website.</p>
            </section>
        </div>
    </main>
</x-layouts.landing>
