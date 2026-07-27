<x-layouts.app title="Reporting & Analytics">
    <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 1200px; margin: 0 auto;">
        <div style="margin-bottom: 2.5rem;">
            <h1 style="color: var(--headings); margin: 0; font-size: 1.75rem; font-weight: 800;">Reporting & Analytics</h1>
            <p style="color: var(--text-secondary); margin-top: 0.5rem;">Overview of your business performance and logs.</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
            <a href="{{ route('reporting.dashboards') }}" style="text-decoration: none;">
                <div class="flowexa-card" style="padding: 2rem; background: var(--surface); border: 1px solid var(--border); border-radius: 20px; transition: all 0.2s;">
                    <i class="fa-solid fa-gauge-high" style="font-size: 2rem; color: var(--primary); margin-bottom: 1rem;"></i>
                    <h3 style="margin: 0; color: var(--headings); font-weight: 800;">Dashboards</h3>
                    <p style="color: var(--text-secondary); font-size: 0.85rem; margin-top: 0.5rem;">Customizable visual data summaries.</p>
                </div>
            </a>
            <a href="{{ route('reporting.reports') }}" style="text-decoration: none;">
                <div class="flowexa-card" style="padding: 2rem; background: var(--surface); border: 1px solid var(--border); border-radius: 20px; transition: all 0.2s;">
                    <i class="fa-solid fa-file-invoice" style="font-size: 2rem; color: #3b82f6; margin-bottom: 1rem;"></i>
                    <h3 style="margin: 0; color: var(--headings); font-weight: 800;">Detailed Reports</h3>
                    <p style="color: var(--text-secondary); font-size: 0.85rem; margin-top: 0.5rem;">Generate and export operational reports.</p>
                </div>
            </a>
            <a href="{{ route('reporting.kpi') }}" style="text-decoration: none;">
                <div class="flowexa-card" style="padding: 2rem; background: var(--surface); border: 1px solid var(--border); border-radius: 20px; transition: all 0.2s;">
                    <i class="fa-solid fa-chart-line" style="font-size: 2rem; color: #10b981; margin-bottom: 1rem;"></i>
                    <h3 style="margin: 0; color: var(--headings); font-weight: 800;">KPI Metrics</h3>
                    <p style="color: var(--text-secondary); font-size: 0.85rem; margin-top: 0.5rem;">Track key performance indicators.</p>
                </div>
            </a>
            <a href="{{ route('reporting.audit_logs') }}" style="text-decoration: none;">
                <div class="flowexa-card" style="padding: 2rem; background: var(--surface); border: 1px solid var(--border); border-radius: 20px; transition: all 0.2s;">
                    <i class="fa-solid fa-clipboard-list" style="font-size: 2rem; color: #6366f1; margin-bottom: 1rem;"></i>
                    <h3 style="margin: 0; color: var(--headings); font-weight: 800;">Audit Logs</h3>
                    <p style="color: var(--text-secondary); font-size: 0.85rem; margin-top: 0.5rem;">Review security and system activities.</p>
                </div>
            </a>
        </div>
    </div>
</x-layouts.app>
