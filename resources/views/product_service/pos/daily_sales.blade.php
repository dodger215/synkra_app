<x-layouts.app title="Daily POS Sales">
    <x-ui.grid>
        <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 1200px; margin: 0 auto;">
            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem;">
                <div>
                    <h1 style="color: var(--headings); margin: 0 0 0.5rem 0; font-weight: 800;">Daily Sales Report</h1>
                    <p style="color: var(--text-secondary); margin: 0;">Snapshot of transactions for {{ now()->format('F d, Y') }}</p>
                </div>
                <div style="display: flex; gap: 0.75rem;">
                    <button onclick="window.print()" class="flowexa-btn" style="background: var(--surface); border: 1px solid var(--border); color: var(--text-primary); padding: 0.6rem 1.25rem; border-radius: 10px; font-weight: 600; cursor: pointer;">
                        <i class="fa-solid fa-print"></i> Print Report
                    </button>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-bottom: 2.5rem;">
                <div class="flowexa-card" style="background: var(--surface); padding: 1.5rem; border-radius: 20px; border: 1px solid var(--border);">
                    <div style="color: var(--text-secondary); font-size: 0.8rem; font-weight: 700; text-transform: uppercase; margin-bottom: 0.5rem;">Total Revenue</div>
                    <div style="color: var(--primary); font-size: 1.75rem; font-weight: 800;">GH₵ {{ number_format($stats['total_sales'], 2) }}</div>
                </div>
                <div class="flowexa-card" style="background: var(--surface); padding: 1.5rem; border-radius: 20px; border: 1px solid var(--border);">
                    <div style="color: var(--text-secondary); font-size: 0.8rem; font-weight: 700; text-transform: uppercase; margin-bottom: 0.5rem;">Orders Today</div>
                    <div style="color: var(--headings); font-size: 1.75rem; font-weight: 800;">{{ $stats['order_count'] }}</div>
                </div>
                <div class="flowexa-card" style="background: var(--surface); padding: 1.5rem; border-radius: 20px; border: 1px solid var(--border);">
                    <div style="color: var(--text-secondary); font-size: 0.8rem; font-weight: 700; text-transform: uppercase; margin-bottom: 0.5rem;">Cash Payments</div>
                    <div style="color: #16a34a; font-size: 1.75rem; font-weight: 800;">GH₵ {{ number_format($stats['cash_total'], 2) }}</div>
                </div>
                <div class="flowexa-card" style="background: var(--surface); padding: 1.5rem; border-radius: 20px; border: 1px solid var(--border);">
                    <div style="color: var(--text-secondary); font-size: 0.8rem; font-weight: 700; text-transform: uppercase; margin-bottom: 0.5rem;">Card/Digital</div>
                    <div style="color: #2563eb; font-size: 1.75rem; font-weight: 800;">GH₵ {{ number_format($stats['card_total'], 2) }}</div>
                </div>
            </div>

            <div class="flowexa-card" style="background: var(--surface); border-radius: 20px; border: 1px solid var(--border); overflow: hidden; padding: 1.5rem;">
                <h2 style="color: var(--headings); font-size: 1.1rem; font-weight: 700; margin-bottom: 1.5rem;">Recent Transactions</h2>
                @if($sales->isEmpty())
                    <div style="text-align: center; padding: 4rem 0; color: var(--text-secondary);">No sales recorded today.</div>
                @else
                    @php
                        $headers = ['Order #', 'Time', 'Payment', 'Total', 'Actions'];
                        $rows = $sales->map(function($sale) {
                            return [
                                '#' . $sale->order_number,
                                $sale->completed_at->format('H:i'),
                                ucfirst($sale->payment_method),
                                'GH₵ ' . number_format($sale->total_amount, 2),
                                new \Illuminate\Support\HtmlString('<a href="' . route('product_service.pos.order.show', $sale->id) . '" style="color:var(--primary); font-weight:600; text-decoration:none;">Details</a>')
                            ];
                        })->toArray();
                    @endphp
                    <x-ui.table :headers="$headers" :rows="$rows" />
                @endif
            </div>
        </div>
    </x-ui.grid>
</x-layouts.app>
