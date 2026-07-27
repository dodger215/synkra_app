<x-layouts.app title="Product Supplier List">
    <div class="flowexa-dashboard-container" style="padding: 2rem;">
        <div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: flex-end;">
            <div>
                <h1 style="color: var(--headings); margin: 0; font-weight: 800;">My Supply Catalog</h1>
                <p style="color: var(--text-secondary); margin: 0.25rem 0 0 0;">Manage the products you supply to other shops in the flowexa network.</p>
            </div>
            <a href="{{ route('supply_chain.import_stocks.index') }}" class="flowexa-btn" style="background: var(--primary); color: white; padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-plus"></i> Add Products to Catalog
            </a>
        </div>

        <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: var(--surface-secondary); border-bottom: 1px solid var(--border);">
                        <th style="text-align: left; padding: 1.25rem 1.5rem; font-size: 0.85rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px;">Product</th>
                        <th style="text-align: left; padding: 1.25rem 1.5rem; font-size: 0.85rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px;">SKU</th>
                        <th style="text-align: left; padding: 1.25rem 1.5rem; font-size: 0.85rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px;">Supply Price</th>
                        <th style="text-align: left; padding: 1.25rem 1.5rem; font-size: 0.85rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px;">Order Limits</th>
                        <th style="text-align: left; padding: 1.25rem 1.5rem; font-size: 0.85rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px;">Inventory Buffer</th>
                        <th style="text-align: left; padding: 1.25rem 1.5rem; font-size: 0.85rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 1.25rem 1.5rem;">
                                <div style="display: flex; align-items: center; gap: 1rem;">
                                    <div style="width: 48px; height: 48px; border-radius: 8px; background: var(--surface-secondary); overflow: hidden; border: 1px solid var(--border); flex-shrink: 0;">
                                        @if($product->images && count($product->images) > 0)
                                            <img src="{{ $product->imageUrl(0) }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: var(--text-secondary); opacity: 0.5;">
                                                <i class="fa-solid fa-image"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div style="font-weight: 700; color: var(--headings);">{{ $product->name }}</div>
                                </div>
                            </td>
                            <td style="padding: 1.25rem 1.5rem; color: var(--text-primary);">
                                {{ $product->sku }}
                            </td>
                            <td style="padding: 1.25rem 1.5rem; color: var(--text-primary);">
                                <strong style="color: var(--primary);">₵{{ number_format($product->supply_price, 2) }}</strong>
                            </td>
                            <td style="padding: 1.25rem 1.5rem; color: var(--text-primary); font-size: 0.9rem;">
                                Min: {{ number_format($product->supply_min_order) }}<br>
                                Max: {{ $product->supply_max_order ? number_format($product->supply_max_order) : 'Unlimited' }}
                            </td>
                            <td style="padding: 1.25rem 1.5rem; color: var(--text-primary);">
                                {{ $product->supply_buffer_percent }}%
                            </td>
                            <td style="padding: 1.25rem 1.5rem;">
                                <span style="background: rgba(34, 197, 94, 0.1); color: #22c55e; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 700;">Live in Network</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding: 3rem; text-align: center; color: var(--text-secondary);">
                                <i class="fa-solid fa-box-open" style="font-size: 2rem; margin-bottom: 1rem; display: block; opacity: 0.3;"></i>
                                No products found in your supply catalog.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>
