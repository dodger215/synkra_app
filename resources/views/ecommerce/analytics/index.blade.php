<x-layouts.app title="Ecommerce Analytics - {{ $store->store_name }}">
    <x-ui.grid>
    <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 1200px; margin: 0 auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <nav style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem;">
                    <a href="{{ route('ecommerce.stores.index') }}" style="color: inherit; text-decoration: none;">Ecommerce</a>
                    <i class="fa-solid fa-chevron-right" style="font-size: 0.75rem;"></i>
                    <a href="{{ route('ecommerce.stores.show', $store->id) }}" style="color: inherit; text-decoration: none;">{{ $store->store_name }}</a>
                    <i class="fa-solid fa-chevron-right" style="font-size: 0.75rem;"></i>
                    <span style="color: var(--primary); font-weight: 600;">Analytics</span>
                </nav>
                <h1 style="color: var(--headings); margin: 0; font-weight: 800;">Store Analytics</h1>
            </div>
            <div>
                <form action="{{ route('ecommerce.analytics', $store->id) }}" method="GET" id="periodForm">
                    <select name="period" class="flowexa-select" onchange="document.getElementById('periodForm').submit()">
                        <option value="7d" {{ $period === '7d' ? 'selected' : '' }}>Last 7 Days</option>
                        <option value="30d" {{ $period === '30d' ? 'selected' : '' }}>Last 30 Days</option>
                        <option value="90d" {{ $period === '90d' ? 'selected' : '' }}>Last 90 Days</option>
                        <option value="year" {{ $period === 'year' ? 'selected' : '' }}>This Year</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- Metric Cards -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            <div class="flowexa-card" style="background: var(--surface); border-radius: 16px; padding: 1.5rem; border: 1px solid var(--border);">
                <div style="color: var(--text-secondary); font-size: 0.875rem; font-weight: 600; margin-bottom: 0.5rem;">TOTAL REVENUE</div>
                <div style="font-size: 1.75rem; font-weight: 800; color: var(--primary);">{{ number_format($totalRevenue, 2) }} {{ $store->currency ?? 'GH₵' }}</div>
            </div>
            <div class="flowexa-card" style="background: var(--surface); border-radius: 16px; padding: 1.5rem; border: 1px solid var(--border);">
                <div style="color: var(--text-secondary); font-size: 0.875rem; font-weight: 600; margin-bottom: 0.5rem;">ORDERS</div>
                <div style="font-size: 1.75rem; font-weight: 800; color: var(--headings);">{{ number_format($totalOrders) }}</div>
            </div>
            <div class="flowexa-card" style="background: var(--surface); border-radius: 16px; padding: 1.5rem; border: 1px solid var(--border);">
                <div style="color: var(--text-secondary); font-size: 0.875rem; font-weight: 600; margin-bottom: 0.5rem;">AVG. ORDER VALUE</div>
                <div style="font-size: 1.75rem; font-weight: 800; color: var(--headings);">{{ number_format($averageOrderValue, 2) }} {{ $store->currency ?? 'GH₵' }}</div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
            <!-- Sales Chart placeholder -->
            <div class="flowexa-card" style="background: var(--surface); border-radius: 16px; padding: 1.5rem; border: 1px solid var(--border);">
                <h3 style="margin: 0 0 1.5rem 0; font-size: 1.1rem; color: var(--headings);">Sales Over Time</h3>
                <div style="height: 300px; display: flex; align-items: flex-end; gap: 10px; padding-bottom: 20px;">
                    @php
                        $maxVal = $salesData->max('total') ?: 1;
                    @endphp
                    @foreach($salesData as $data)
                        <div style="flex: 1; display: flex; flex-direction: column; align-items: center; gap: 5px;">
                            <div style="width: 100%; background: var(--primary); border-radius: 4px 4px 0 0; height: {{ ($data->total / $maxVal) * 100 }}%;" title="{{ $data->date }}: {{ number_format($data->total, 2) }}"></div>
                            <span style="font-size: 0.65rem; color: var(--text-secondary); writing-mode: vertical-rl; transform: rotate(180deg);">{{ Carbon\Carbon::parse($data->date)->format('M d') }}</span>
                        </div>
                    @endforeach
                    @if($salesData->isEmpty())
                        <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: var(--text-secondary);">
                            No data available for the selected period.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Top Products -->
            <div class="flowexa-card" style="background: var(--surface); border-radius: 16px; padding: 1.5rem; border: 1px solid var(--border);">
                <h3 style="margin: 0 0 1.5rem 0; font-size: 1.1rem; color: var(--headings);">Top Products</h3>
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    @forelse($topProducts as $product)
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <div style="font-weight: 600; color: var(--text-primary); font-size: 0.9rem;">{{ $product->product->name ?? 'Unknown' }}</div>
                                <div style="font-size: 0.75rem; color: var(--text-secondary);">{{ $product->total_qty }} units sold</div>
                            </div>
                            <div style="font-weight: 700; color: var(--headings);">{{ number_format($product->total_revenue, 2) }}</div>
                        </div>
                        @if(!$loop->last) <div style="height: 1px; background: var(--border);"></div> @endif
                    @empty
                        <p style="color: var(--text-secondary); font-size: 0.9rem;">No product data available.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    </x-ui.grid>
</x-layouts.app>
