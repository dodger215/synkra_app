<x-layouts.app title="Order #{{ $order->order_number }}">
    <x-ui.grid>
        <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 900px; margin: 0 auto;">

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <a href="{{ route('product_service.pos.orders') }}" style="text-decoration: none; color: var(--text-secondary); display: flex; align-items: center; gap: 0.5rem; font-weight: 600; font-size: 0.9rem;">
                    <i class="fa-solid fa-arrow-left"></i> Back to Orders
                </a>
                <div style="display: flex; gap: 0.75rem;">
                    <form action="{{ route('product_service.pos.device.print-receipt', $order->id) }}" method="POST" target="_blank" style="margin: 0;">
                        @csrf
                        <button type="submit" class="flowexa-btn" style="background: var(--surface); border: 1px solid var(--border); color: var(--text-primary); padding: 0.6rem 1.25rem; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-print"></i> Print Receipt
                        </button>
                    </form>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 300px; gap: 1.5rem; align-items: start;">

                {{-- Left Column: Order Items --}}
                <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                    <div class="flowexa-card" style="background: var(--surface); border-radius: 20px; border: 1px solid var(--border); padding: 1.5rem;">
                        <h2 style="color: var(--headings); font-size: 1.2rem; margin: 0 0 1.5rem;">Order Details</h2>

                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            @foreach($order->items as $item)
                                <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 1rem; border-bottom: 1px solid var(--divider);">
                                    <div style="display: flex; align-items: center; gap: 1rem;">
                                        <div style="width: 48px; height: 48px; background: var(--surface-secondary); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--text-secondary); font-size: 1.25rem;">
                                            @if($item->product->imageUrl())
                                                <img src="{{ $item->product->imageUrl() }}" alt="{{ $item->product->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 10px;">
                                            @else
                                                <i class="fa-solid fa-box"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <div style="font-weight: 700; color: var(--headings); font-size: 0.95rem;">{{ $item->product->name }}</div>
                                            <div style="color: var(--text-secondary); font-size: 0.85rem;">GH₵ {{ number_format($item->unit_price, 2) }} x {{ number_format($item->quantity, 0) }}</div>
                                        </div>
                                    </div>
                                    <div style="font-weight: 800; color: var(--headings);">GH₵ {{ number_format($item->total_price, 2) }}</div>
                                </div>
                            @endforeach
                        </div>

                        <div style="margin-top: 2rem; display: flex; flex-direction: column; gap: 0.5rem; align-items: flex-end;">
                            <div style="display: flex; justify-content: space-between; width: 240px; color: var(--text-secondary);">
                                <span>Subtotal</span>
                                <span>GH₵ {{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; width: 240px; color: var(--danger);">
                                <span>Discount</span>
                                <span>-GH₵ {{ number_format($order->discount_amount, 2) }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; width: 240px; color: var(--headings); font-weight: 800; font-size: 1.25rem; margin-top: 0.5rem; padding-top: 1rem; border-top: 1px solid var(--border);">
                                <span>Total Paid</span>
                                <span>GH₵ {{ number_format($order->paid_amount, 2) }}</span>
                            </div>
                            @if($order->change_amount > 0)
                                <div style="display: flex; justify-content: space-between; width: 240px; color: var(--text-secondary); font-size: 0.9rem;">
                                    <span>Change Given</span>
                                    <span>GH₵ {{ number_format($order->change_amount, 2) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Right Column: Information --}}
                <div style="display: flex; flex-direction: column; gap: 1.5rem;">

                    {{-- Summary Card --}}
                    <div class="flowexa-card" style="background: var(--surface); border-radius: 20px; border: 1px solid var(--border); padding: 1.5rem;">
                        <div style="text-align: center; margin-bottom: 1.5rem;">
                            <div style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-secondary); margin-bottom: 0.25rem;">Order Number</div>
                            <div style="font-size: 1.25rem; font-weight: 800; color: var(--primary);">{{ $order->order_number }}</div>
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <i class="fa-solid fa-calendar" style="color: var(--text-secondary); width: 16px;"></i>
                                <div style="font-size: 0.9rem;">
                                    <div style="color: var(--text-secondary); font-size: 0.75rem;">Date & Time</div>
                                    <div style="color: var(--headings); font-weight: 600;">{{ $order->completed_at->format('M d, Y H:i') }}</div>
                                </div>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <i class="fa-solid fa-user-tie" style="color: var(--text-secondary); width: 16px;"></i>
                                <div style="font-size: 0.9rem;">
                                    <div style="color: var(--text-secondary); font-size: 0.75rem;">Cashier</div>
                                    <div style="color: var(--headings); font-weight: 600;">{{ $order->session->cashier->name }}</div>
                                </div>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <i class="fa-solid fa-wallet" style="color: var(--text-secondary); width: 16px;"></i>
                                <div style="font-size: 0.9rem;">
                                    <div style="color: var(--text-secondary); font-size: 0.75rem;">Payment Method</div>
                                    <div style="color: var(--headings); font-weight: 600; text-transform: capitalize;">{{ $order->payment_method }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Customer Card --}}
                    <div class="flowexa-card" style="background: var(--surface); border-radius: 20px; border: 1px solid var(--border); padding: 1.5rem;">
                        <h3 style="color: var(--headings); font-size: 1rem; margin: 0 0 1rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fa-solid fa-user" style="color: var(--primary);"></i> Customer
                        </h3>
                        @if($order->customer)
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center; font-weight: 800;">
                                    {{ substr($order->customer->name, 0, 1) }}
                                </div>
                                <div>
                                    <div style="font-weight: 700; color: var(--headings);">{{ $order->customer->name }}</div>
                                    <div style="font-size: 0.8rem; color: var(--text-secondary);">{{ $order->customer->email ?? 'No email' }}</div>
                                </div>
                            </div>
                        @else
                            <div style="color: var(--text-secondary); font-size: 0.9rem; font-style: italic;">Walk-in Customer</div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </x-ui.grid>
</x-layouts.app>
