<x-layouts.app title="Order #{{ $order->order_number ?? substr($order->id, 0, 8) }}">
    <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 1200px; margin: 0 auto;">
        <nav style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 1.5rem;">
            <a href="{{ route('ecommerce.stores.index') }}" style="color: inherit; text-decoration: none;">Ecommerce</a>
            <i class="fa-solid fa-chevron-right" style="font-size: 0.75rem;"></i>
            <a href="{{ route('ecommerce.stores.show', $store->id) }}" style="color: inherit; text-decoration: none;">{{ $store->store_name }}</a>
            <i class="fa-solid fa-chevron-right" style="font-size: 0.75rem;"></i>
            <a href="{{ route('ecommerce.orders.index', $store->id) }}" style="color: inherit; text-decoration: none;">Orders</a>
            <i class="fa-solid fa-chevron-right" style="font-size: 0.75rem;"></i>
            <span style="color: var(--primary); font-weight: 600;">#{{ $order->order_number ?? substr($order->id, 0, 8) }}</span>
        </nav>

        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem; gap: 2rem;">
            <div>
                <h1 style="color: var(--headings); margin: 0; font-weight: 800; font-size: 2rem;">Order #{{ $order->order_number ?? substr($order->id, 0, 8) }}</h1>
                <p style="color: var(--text-secondary); margin-top: 0.5rem;">Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
            </div>
            <div style="display: flex; gap: 1rem;">
                <form action="{{ route('ecommerce.orders.update-status', [$store->id, $order->id]) }}" method="POST" style="display: flex; gap: 1rem; align-items: center;">
                    @csrf
                    <select name="fulfillment_status" class="flowexa-select" style="min-width: 150px;">
                        <option value="pending" {{ $order->fulfillment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ $order->fulfillment_status === 'processing' ? 'selected' : '' }}>Processing</option>
                        @if($order->delivery_type === 'pickup')
                            <option value="ready_for_pickup" {{ $order->fulfillment_status === 'ready_for_pickup' ? 'selected' : '' }}>Ready for Pickup</option>
                            <option value="customer_arrived" {{ $order->fulfillment_status === 'customer_arrived' ? 'selected' : '' }}>Customer Arrived</option>
                            <option value="collected" {{ $order->fulfillment_status === 'collected' ? 'selected' : '' }}>Collected</option>
                        @else
                            <option value="shipped" {{ $order->fulfillment_status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="fulfilled" {{ $order->fulfillment_status === 'fulfilled' ? 'selected' : '' }}>Fulfilled</option>
                        @endif
                        <option value="cancelled" {{ $order->fulfillment_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    <button type="submit" class="flowexa-btn-primary">Update Status</button>
                </form>
            </div>
        </div>

        @if(session('success'))
            <x-ui.alert type="success" title="Success" message="{{ session('success') }}" style="margin-bottom: 1.5rem;" />
        @endif

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
            <div style="display: flex; flex-direction: column; gap: 2rem;">
                <!-- Order Items -->
                <div class="flowexa-card" style="background: var(--surface); border-radius: 16px; border: 1px solid var(--border); overflow: hidden;">
                    <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border); font-weight: 700; color: var(--headings);">Items</div>
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="text-align: left; background: var(--surface-secondary); font-size: 0.85rem; color: var(--text-secondary);">
                                <th style="padding: 1rem 1.5rem;">Product</th>
                                <th style="padding: 1rem 1.5rem;">Price</th>
                                <th style="padding: 1rem 1.5rem;">Quantity</th>
                                <th style="padding: 1rem 1.5rem; text-align: right;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr style="border-bottom: 1px solid var(--border);">
                                    <td style="padding: 1rem 1.5rem;">
                                        <div style="display: flex; align-items: center; gap: 1rem;">
                                            @if($item->product && $item->product->images && count($item->product->images) > 0)
                                                <img src="{{ asset('storage/' . $item->product->images[0]) }}" style="width: 48px; height: 48px; border-radius: 8px; object-fit: cover; border: 1px solid var(--border);">
                                            @else
                                                <div style="width: 48px; height: 48px; border-radius: 8px; background: var(--surface-secondary); display: flex; align-items: center; justify-content: center; color: var(--text-secondary);">
                                                    <i class="fa-solid fa-image"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div style="font-weight: 600; color: var(--text-primary);">{{ $item->product->name ?? 'Unknown Product' }}</div>
                                                <div style="font-size: 0.75rem; color: var(--text-secondary);">SKU: {{ $item->product->sku ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding: 1rem 1.5rem; color: var(--text-primary);">{{ number_format($item->unit_price, 2) }} {{ $store->currency ?? 'GH₵' }}</td>
                                    <td style="padding: 1rem 1.5rem; color: var(--text-primary);">{{ $item->quantity }}</td>
                                    <td style="padding: 1rem 1.5rem; text-align: right; font-weight: 600; color: var(--text-primary);">{{ number_format($item->total_price, 2) }} {{ $store->currency ?? 'GH₵' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div style="padding: 1.5rem; background: var(--surface-secondary);">
                        <div style="display: flex; flex-direction: column; gap: 0.75rem; align-items: flex-end;">
                            <div style="display: flex; justify-content: space-between; width: 250px;">
                                <span style="color: var(--text-secondary);">Subtotal</span>
                                <span style="color: var(--text-primary); font-weight: 500;">{{ number_format($order->subtotal, 2) }} {{ $store->currency ?? 'GH₵' }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; width: 250px;">
                                <span style="color: var(--text-secondary);">Tax</span>
                                <span style="color: var(--text-primary); font-weight: 500;">{{ number_format($order->tax_amount, 2) }} {{ $store->currency ?? 'GH₵' }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; width: 250px;">
                                <span style="color: var(--text-secondary);">Shipping</span>
                                <span style="color: var(--text-primary); font-weight: 500;">{{ number_format($order->shipping_cost, 2) }} {{ $store->currency ?? 'GH₵' }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; width: 250px; border-top: 2px solid var(--border); padding-top: 0.75rem; margin-top: 0.25rem;">
                                <span style="color: var(--headings); font-weight: 700; font-size: 1.1rem;">Total</span>
                                <span style="color: var(--primary); font-weight: 800; font-size: 1.1rem;">{{ number_format($order->total_amount, 2) }} {{ $store->currency ?? 'GH₵' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 2rem;">
                <!-- Customer Info -->
                <div class="flowexa-card" style="background: var(--surface); border-radius: 16px; border: 1px solid var(--border); overflow: hidden; padding: 1.5rem;">
                    <h3 style="margin: 0 0 1rem 0; font-size: 1.1rem; color: var(--headings);">Customer</h3>
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                        <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                            {{ strtoupper(substr($order->customer->name ?? 'G', 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-weight: 600; color: var(--text-primary);">{{ $order->customer->name ?? 'Guest Customer' }}</div>
                            <div style="font-size: 0.875rem; color: var(--text-secondary);">{{ $order->customer->email ?? 'No email provided' }}</div>
                        </div>
                    </div>

                    <div style="border-top: 1px solid var(--border); padding-top: 1rem; margin-top: 1rem;">
                        <div style="font-weight: 600; color: var(--text-primary); font-size: 0.875rem; margin-bottom: 0.5rem;">Shipping Address</div>
                        <p style="font-size: 0.875rem; color: var(--text-secondary); line-height: 1.6; margin: 0;">
                            @if(is_array($order->shipping_address))
                                {{ $order->shipping_address['address'] ?? '' }}<br>
                                {{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }} {{ $order->shipping_address['zip'] ?? '' }}<br>
                                {{ $order->shipping_address['country'] ?? '' }}
                            @else
                                {{ $order->shipping_address ?? 'No shipping address provided' }}
                            @endif
                        </p>
                    </div>

                    <div style="border-top: 1px solid var(--border); padding-top: 1rem; margin-top: 1rem;">
                        <div style="font-weight: 600; color: var(--text-primary); font-size: 0.875rem; margin-bottom: 0.5rem;">Payment Info</div>
                        <div style="display: flex; justify-content: space-between; font-size: 0.875rem; margin-bottom: 0.25rem;">
                            <span style="color: var(--text-secondary);">Status</span>
                            <span style="font-weight: 600; @if($order->payment_status === 'paid') color: #166534; @else color: #991b1b; @endif">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 0.875rem;">
                            <span style="color: var(--text-secondary);">Method</span>
                            <span style="color: var(--text-primary); font-weight: 500;">{{ strtoupper($order->payment_method ?? 'N/A') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Internal Notes -->
                <div class="flowexa-card" style="background: var(--surface); border-radius: 16px; border: 1px solid var(--border); overflow: hidden; padding: 1.5rem;">
                    <h3 style="margin: 0 0 1rem 0; font-size: 1.1rem; color: var(--headings);">Notes</h3>
                    <p style="font-size: 0.875rem; color: var(--text-secondary); margin: 0;">
                        {{ $order->customer_notes ?? 'No customer notes for this order.' }}
                    </p>
                    @if($order->admin_notes)
                        <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border);">
                            <div style="font-weight: 600; color: var(--text-primary); font-size: 0.875rem; margin-bottom: 0.5rem;">Admin Notes</div>
                            <p style="font-size: 0.875rem; color: var(--text-secondary); margin: 0;">{{ $order->admin_notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
