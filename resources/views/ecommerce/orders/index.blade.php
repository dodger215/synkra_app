<x-layouts.app title="Store Orders - {{ $store->store_name }}">
    <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 1200px; margin: 0 auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <nav style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem;">
                    <a href="{{ route('ecommerce.stores.index') }}" style="color: inherit; text-decoration: none;">Ecommerce</a>
                    <i class="fa-solid fa-chevron-right" style="font-size: 0.75rem;"></i>
                    <a href="{{ route('ecommerce.stores.show', $store->id) }}" style="color: inherit; text-decoration: none;">{{ $store->store_name }}</a>
                    <i class="fa-solid fa-chevron-right" style="font-size: 0.75rem;"></i>
                    <span style="color: var(--primary); font-weight: 600;">Orders</span>
                </nav>
                <h1 style="color: var(--headings); margin: 0; font-weight: 800;">Orders</h1>
            </div>
        </div>

        @if(session('success'))
            <x-ui.alert type="success" title="Success" message="{{ session('success') }}" style="margin-bottom: 1.5rem;" />
        @endif

        <div class="flowexa-card" style="background: var(--surface); border-radius: 16px; border: 1px solid var(--border); overflow: hidden;">
            @if($orders->isEmpty())
                <div style="text-align: center; padding: 4rem 2rem;">
                    <div style="width: 64px; height: 64px; background: var(--surface-secondary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem auto; font-size: 1.5rem; color: var(--text-secondary);">
                        <i class="fa-solid fa-bag-shopping"></i>
                    </div>
                    <h3 style="margin: 0 0 0.5rem 0; color: var(--text-primary);">No Orders Found</h3>
                    <p style="color: var(--text-secondary); margin: 0;">Orders from your online store will appear here.</p>
                </div>
            @else
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; text-align: left;">
                        <thead>
                            <tr style="border-bottom: 1px solid var(--border); background: var(--surface-secondary);">
                                <th style="padding: 1rem 1.5rem; font-weight: 600; color: var(--text-secondary); font-size: 0.85rem; text-transform: uppercase;">Order #</th>
                                <th style="padding: 1rem 1.5rem; font-weight: 600; color: var(--text-secondary); font-size: 0.85rem; text-transform: uppercase;">Customer</th>
                                <th style="padding: 1rem 1.5rem; font-weight: 600; color: var(--text-secondary); font-size: 0.85rem; text-transform: uppercase;">Date</th>
                                <th style="padding: 1rem 1.5rem; font-weight: 600; color: var(--text-secondary); font-size: 0.85rem; text-transform: uppercase;">Total</th>
                                <th style="padding: 1rem 1.5rem; font-weight: 600; color: var(--text-secondary); font-size: 0.85rem; text-transform: uppercase;">Payment</th>
                                <th style="padding: 1rem 1.5rem; font-weight: 600; color: var(--text-secondary); font-size: 0.85rem; text-transform: uppercase;">Fulfillment</th>
                                <th style="padding: 1rem 1.5rem; font-weight: 600; color: var(--text-secondary); font-size: 0.85rem; text-transform: uppercase;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr style="border-bottom: 1px solid var(--border); transition: background 0.2s;" onmouseover="this.style.background='var(--surface-secondary)'" onmouseout="this.style.background='transparent'">
                                    <td style="padding: 1rem 1.5rem; font-weight: 600; color: var(--text-primary);">#{{ $order->order_number ?? substr($order->id, 0, 8) }}</td>
                                    <td style="padding: 1rem 1.5rem;">
                                        <div style="font-weight: 500; color: var(--text-primary);">{{ $order->customer->name ?? 'Guest' }}</div>
                                        <div style="font-size: 0.75rem; color: var(--text-secondary);">{{ $order->customer->email ?? '' }}</div>
                                    </td>
                                    <td style="padding: 1rem 1.5rem; color: var(--text-secondary);">{{ $order->created_at->format('M d, Y') }}</td>
                                    <td style="padding: 1rem 1.5rem; font-weight: 600; color: var(--text-primary);">{{ number_format($order->total_amount, 2) }} {{ $store->currency ?? 'GH₵' }}</td>
                                    <td style="padding: 1rem 1.5rem;">
                                        <span style="padding: 0.25rem 0.75rem; border-radius: 99px; font-size: 0.75rem; font-weight: 600;
                                            @if($order->payment_status === 'paid') background: #dcfce7; color: #166534; @else background: #fee2e2; color: #991b1b; @endif">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                    </td>
                                    <td style="padding: 1rem 1.5rem;">
                                        <span style="padding: 0.25rem 0.75rem; border-radius: 99px; font-size: 0.75rem; font-weight: 600;
                                            @if($order->fulfillment_status === 'fulfilled') background: #dcfce7; color: #166534;
                                            @elseif($order->fulfillment_status === 'processing') background: #fef9c3; color: #854d0e;
                                            @else background: #f3f4f6; color: #374151; @endif">
                                            {{ ucfirst($order->fulfillment_status) }}
                                        </span>
                                    </td>
                                    <td style="padding: 1rem 1.5rem;">
                                        <a href="{{ route('ecommerce.orders.show', [$store->id, $order->id]) }}" style="color: var(--primary); text-decoration: none; font-weight: 600;">View Details</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--border);">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
