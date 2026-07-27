<x-layouts.app title="CRM Analytics">
    <div class="flowexa-dashboard-container" style="padding: 2rem;">
        <div style="margin-bottom: 2rem;">
            <h1 style="color: var(--headings); margin: 0; font-weight: 800;">CRM Insights</h1>
            <p style="color: var(--text-secondary); margin: 0.25rem 0 0 0;">Analyze customer growth, behavior, and value distribution.</p>
        </div>

        {{-- Top Metrics --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            <div class="flowexa-card" style="background: var(--surface); padding: 1.5rem; border-radius: 20px; border: 1px solid var(--border);">
                <div style="color: var(--text-secondary); font-size: 0.75rem; font-weight: 700; text-transform: uppercase; margin-bottom: 0.5rem;">Total Customers</div>
                <div style="color: var(--headings); font-size: 2rem; font-weight: 800;">{{ number_format($totalCustomers) }}</div>
            </div>
            <div class="flowexa-card" style="background: var(--surface); padding: 1.5rem; border-radius: 20px; border: 1px solid var(--border);">
                <div style="color: var(--text-secondary); font-size: 0.75rem; font-weight: 700; text-transform: uppercase; margin-bottom: 0.5rem;">Total Customer Value</div>
                <div style="color: var(--primary); font-size: 2rem; font-weight: 800;">₵{{ number_format($totalSpent, 2) }}</div>
            </div>
            <div class="flowexa-card" style="background: var(--surface); padding: 1.5rem; border-radius: 20px; border: 1px solid var(--border);">
                <div style="color: var(--text-secondary); font-size: 0.75rem; font-weight: 700; text-transform: uppercase; margin-bottom: 0.5rem;">Avg Value per Cust.</div>
                <div style="color: var(--headings); font-size: 2rem; font-weight: 800;">₵{{ $totalCustomers > 0 ? number_format($totalSpent / $totalCustomers, 2) : '0.00' }}</div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            {{-- Top Customers --}}
            <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); overflow: hidden;">
                <h3 style="padding: 1.5rem; margin: 0; color: var(--headings); font-size: 1.1rem; font-weight: 700; border-bottom: 1px solid var(--border);">High Value Customers</h3>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: var(--surface-secondary); border-bottom: 1px solid var(--border);">
                            <th style="text-align: left; padding: 1rem 1.5rem; font-size: 0.8rem; color: var(--text-secondary);">Customer</th>
                            <th style="text-align: right; padding: 1rem 1.5rem; font-size: 0.8rem; color: var(--text-secondary);">Total Spent</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topCustomers as $cust)
                            <tr style="border-bottom: 1px solid var(--border);">
                                <td style="padding: 1rem 1.5rem; font-weight: 600; color: var(--headings);">{{ $cust->first_name }} {{ $cust->last_name }}</td>
                                <td style="padding: 1rem 1.5rem; text-align: right; font-weight: 700; color: var(--primary);">₵{{ number_format($cust->total_spent, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Tier Distribution --}}
            <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); padding: 1.5rem;">
                <h3 style="margin: 0 0 1.5rem 0; color: var(--headings); font-size: 1.1rem; font-weight: 700;">Loyalty Tier Distribution</h3>
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    @foreach($customerTiers as $tier)
                        @php
                            $percentage = ($totalCustomers > 0) ? ($tier->count / $totalCustomers) * 100 : 0;
                        @endphp
                        <div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 0.9rem;">
                                <span style="font-weight: 600; color: var(--headings);">{{ ucfirst($tier->tier) }}</span>
                                <span style="color: var(--text-secondary);">{{ $tier->count }} customers ({{ round($percentage) }}%)</span>
                            </div>
                            <div style="width: 100%; height: 8px; background: var(--surface-secondary); border-radius: 4px; overflow: hidden;">
                                <div style="width: {{ $percentage }}%; height: 100%; background: var(--primary);"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
