<x-layouts.app title="Audit Logs | Reporting">
    <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 1200px; margin: 0 auto;">
        <div style="margin-bottom: 2.5rem;">
            <h1 style="color: var(--headings); margin: 0; font-size: 1.75rem; font-weight: 800;">System Audit Logs</h1>
            <p style="color: var(--text-secondary); margin-top: 0.5rem;">Security and activity trail for your workspace.</p>
        </div>

        <div class="flowexa-card" style="background: var(--surface); border: 1px solid var(--border); border-radius: 24px; overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="background: var(--surface-secondary); border-bottom: 1px solid var(--border);">
                        <th style="padding: 1rem 1.5rem; font-size: 0.75rem; font-weight: 800; color: var(--text-secondary); text-transform: uppercase;">Timestamp</th>
                        <th style="padding: 1rem 1.5rem; font-size: 0.75rem; font-weight: 800; color: var(--text-secondary); text-transform: uppercase;">User</th>
                        <th style="padding: 1rem 1.5rem; font-size: 0.75rem; font-weight: 800; color: var(--text-secondary); text-transform: uppercase;">Action</th>
                        <th style="padding: 1rem 1.5rem; font-size: 0.75rem; font-weight: 800; color: var(--text-secondary); text-transform: uppercase;">IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="4" style="padding: 4rem; text-align: center; color: var(--text-secondary);">
                            <i class="fa-solid fa-shield-halved" style="font-size: 2.5rem; opacity: 0.1; display: block; margin-bottom: 1rem;"></i>
                            Security logs will appear here as they are generated.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>
