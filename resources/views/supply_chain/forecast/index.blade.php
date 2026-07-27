<x-layouts.app title="Demand Forecast">
    <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 1200px; margin: 0 auto;">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem;">
            <div>
                <h1 style="color: var(--headings); margin: 0 0 0.5rem 0; font-weight: 800;">Supply Chain Intelligence</h1>
                <p style="color: var(--text-secondary); margin: 0;">AI-driven demand forecasting and inventory alerts.</p>
            </div>
            <div>
                <form action="{{ route('supply_chain.forecast.generate') }}" method="POST">
                    @csrf
                    <button type="submit" class="flowexa-btn" style="background: var(--primary); border: none; color: white; padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 15px rgba(249,115,22,0.2);">
                        <i class="fa-solid fa-wand-sparkles"></i> Run AI Forecast
                    </button>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div style="background: rgba(34, 197, 94, 0.1); color: #166534; padding: 1rem; border-radius: 12px; margin-bottom: 2rem; border: 1px solid rgba(34, 197, 94, 0.2); font-weight: 600;">
                <i class="fa-solid fa-circle-check" style="margin-right: 8px;"></i> {{ session('success') }}
            </div>
        @endif

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
            {{-- Forecast Results --}}
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); padding: 1.5rem;">
                    <h3 style="color: var(--headings); margin: 0 0 1.5rem 0; font-size: 1.1rem; font-weight: 700;">Demand Projections</h3>

                    @if($forecasts->isEmpty())
                        <div style="text-align: center; padding: 4rem 0;">
                            <i class="fa-solid fa-chart-line" style="font-size: 2.5rem; color: var(--text-secondary); opacity: 0.2; margin-bottom: 1rem;"></i>
                            <p style="color: var(--text-secondary);">No forecast data available. Click "Run AI Forecast" to generate projections.</p>
                        </div>
                    @else
                        @php
                            $headers = ['Product', 'Projected Demand', 'Period', 'Confidence'];
                            $rows = $forecasts->map(function($f) {
                                return [
                                    $f->product->name ?? 'N/A',
                                    $f->forecasted_quantity,
                                    $f->forecast_period ?? 'Next 30 Days',
                                    new \Illuminate\Support\HtmlString('<div style="width: 100px; height: 8px; background: var(--surface-secondary); border-radius: 4px; overflow: hidden;"><div style="width: ' . ($f->confidence_score * 100) . '%; height: 100%; background: var(--success);"></div></div>')
                                ];
                            })->toArray();
                        @endphp
                        <x-ui.table :headers="$headers" :rows="$rows" />
                    @endif
                </div>
            </div>

            {{-- Reorder Alerts --}}
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); padding: 1.5rem;">
                    <h3 style="color: var(--headings); margin: 0 0 1.5rem 0; font-size: 1.1rem; font-weight: 700;">Critical Reorder Alerts</h3>

                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        @forelse($alerts as $alert)
                            <div style="background: rgba(239, 68, 68, 0.05); border: 1px solid rgba(239, 68, 68, 0.1); padding: 1rem; border-radius: 16px;">
                                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                                    <span style="font-weight: 700; color: var(--headings);">{{ $alert->product->name ?? 'Unknown Product' }}</span>
                                    <span style="font-size: 0.7rem; font-weight: 800; color: var(--danger); text-transform: uppercase;">Low Stock</span>
                                </div>
                                <div style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 1rem;">
                                    Current Stock: <strong style="color: var(--danger);">{{ $alert->current_stock }}</strong> / Threshold: {{ $alert->reorder_point }}
                                </div>
                                <div style="display: flex; gap: 0.75rem;">
                                    <a href="{{ route('supply_chain.purchasing.create', ['product_id' => $alert->product_id]) }}" class="flowexa-btn" style="flex: 1; background: var(--surface); border: 1px solid var(--border); color: var(--text-primary); padding: 0.5rem; border-radius: 8px; font-size: 0.75rem; font-weight: 700; text-decoration: none; text-align: center;">Order Now</a>
                                    <form action="{{ route('supply_chain.forecast.resolve', $alert->id) }}" method="POST" style="flex: 1;">
                                        @csrf
                                        <button type="submit" class="flowexa-btn" style="width: 100%; background: transparent; border: 1px solid var(--border); color: var(--text-secondary); padding: 0.5rem; border-radius: 8px; font-size: 0.75rem; font-weight: 700; cursor: pointer;">Dismiss</button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div style="text-align: center; padding: 2rem 0;">
                                <div style="width: 48px; height: 48px; background: rgba(34, 197, 94, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--success); margin: 0 auto 1rem;">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                                <p style="font-size: 0.85rem; color: var(--text-secondary);">No active stock alerts.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
