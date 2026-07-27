<x-layouts.app title="SCM Reports">
    <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 1000px; margin: 0 auto;">
        <div style="margin-bottom: 2rem;">
            <h1 style="color: var(--headings); margin: 0 0 0.5rem 0; font-weight: 800;">Supply Chain Reports</h1>
            <p style="color: var(--text-secondary); margin: 0;">Export your procurement and supplier data for analysis.</p>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            {{-- Suppliers Export --}}
            <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); padding: 2rem; display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div style="width: 48px; height: 48px; background: rgba(249, 115, 22, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--primary); margin-bottom: 1.5rem;">
                        <i class="fa-solid fa-truck-field" style="font-size: 1.25rem;"></i>
                    </div>
                    <h3 style="color: var(--headings); margin: 0 0 0.5rem 0; font-weight: 700;">Supplier Directory</h3>
                    <p style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 1.5rem;">Export a full list of your registered suppliers, including contact details and addresses.</p>
                </div>
                <a href="{{ route('supply_chain.reports.suppliers') }}" class="flowexa-btn" style="background: var(--surface-secondary); border: 1px solid var(--border); color: var(--text-primary); padding: 0.75rem; border-radius: 12px; font-weight: 700; text-decoration: none; text-align: center; display: block;">
                    <i class="fa-solid fa-download"></i> Export CSV
                </a>
            </div>

            {{-- POs Export --}}
            <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); padding: 2rem; display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div style="width: 48px; height: 48px; background: rgba(59, 130, 246, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #3b82f6; margin-bottom: 1.5rem;">
                        <i class="fa-solid fa-file-invoice-dollar" style="font-size: 1.25rem;"></i>
                    </div>
                    <h3 style="color: var(--headings); margin: 0 0 0.5rem 0; font-weight: 700;">Purchase History</h3>
                    <p style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 1.5rem;">Comprehensive log of all purchase orders, their statuses, totals, and delivery progress.</p>
                </div>
                <a href="{{ route('supply_chain.reports.purchase-orders') }}" class="flowexa-btn" style="background: var(--surface-secondary); border: 1px solid var(--border); color: var(--text-primary); padding: 0.75rem; border-radius: 12px; font-weight: 700; text-decoration: none; text-align: center; display: block;">
                    <i class="fa-solid fa-download"></i> Export CSV
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>
