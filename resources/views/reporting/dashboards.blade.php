<x-layouts.app title="Dashboards | Reporting">
    <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 1200px; margin: 0 auto;">
        <div style="margin-bottom: 2.5rem;">
            <h1 style="color: var(--headings); margin: 0; font-size: 1.75rem; font-weight: 800;">Visual Dashboards</h1>
            <p style="color: var(--text-secondary); margin-top: 0.5rem;">Real-time overview of your business health.</p>
        </div>

        <div class="flowexa-card" style="padding: 4rem; background: var(--surface); border: 1px solid var(--border); border-radius: 32px; text-align: center;">
            <i class="fa-solid fa-gauge-high" style="font-size: 4rem; color: var(--text-muted); opacity: 0.2; margin-bottom: 1.5rem; display: block;"></i>
            <h2 style="color: var(--headings); font-weight: 800;">Dashboard Engine Initializing</h2>
            <p style="color: var(--text-secondary); max-width: 500px; margin: 0.5rem auto 2rem;">We are preparing your customizable dashboard widgets. Check back soon for full visual analytics.</p>
            <a href="{{ route('reporting.index') }}" class="flowexa-btn" style="background: var(--surface-secondary); color: var(--text-primary); padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; text-decoration: none;">Return to Hub</a>
        </div>
    </div>
</x-layouts.app>
