<x-layouts.app title="Operational Reports | Reporting">
    <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 1200px; margin: 0 auto;">
        <div style="margin-bottom: 2.5rem;">
            <h1 style="color: var(--headings); margin: 0; font-size: 1.75rem; font-weight: 800;">Operational Reports</h1>
            <p style="color: var(--text-secondary); margin-top: 0.5rem;">Generate and export detailed activity reports.</p>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem;">
            <div class="flowexa-card" style="padding: 1.5rem; background: var(--surface); border: 1px solid var(--border); border-radius: 20px;">
                <h3 style="color: var(--headings); font-weight: 800; margin-bottom: 1.5rem;">Report Categories</h3>
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <div style="padding: 1rem; background: #fff7ed; color: var(--primary); border-radius: 12px; font-weight: 700;">Sales Reports</div>
                    <div style="padding: 1rem; border: 1px solid var(--border); border-radius: 12px; font-weight: 600; color: var(--text-secondary);">Inventory Reports</div>
                    <div style="padding: 1rem; border: 1px solid var(--border); border-radius: 12px; font-weight: 600; color: var(--text-secondary);">Customer Interaction</div>
                    <div style="padding: 1rem; border: 1px solid var(--border); border-radius: 12px; font-weight: 600; color: var(--text-secondary);">Finance & Tax</div>
                </div>
            </div>
            <div class="flowexa-card" style="padding: 4rem 2rem; background: var(--surface); border: 1px solid var(--border); border-radius: 20px; text-align: center;">
                 <i class="fa-solid fa-file-export" style="font-size: 3rem; color: var(--text-muted); opacity: 0.1; margin-bottom: 1.5rem; display: block;"></i>
                 <h3 style="color: var(--headings); font-weight: 700;">Select a category to begin</h3>
                 <p style="color: var(--text-secondary); font-size: 0.9rem;">Choose a category on the left to configure your report parameters.</p>
            </div>
        </div>
    </div>
</x-layouts.app>
