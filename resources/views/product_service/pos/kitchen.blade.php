<x-layouts.app title="Kitchen Display System">
    <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 1400px; margin: 0 auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h1 style="color: var(--headings); margin: 0; font-size: 1.75rem; font-weight: 800;">Kitchen Display System</h1>
                <p style="color: var(--text-secondary); margin-top: 0.25rem;">Live order tracking and preparation queue.</p>
            </div>
            <div style="display: flex; gap: 0.5rem; background: var(--surface); padding: 0.5rem; border-radius: 12px; border: 1px solid var(--border);">
                <button class="flowexa-btn" style="padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; background: var(--primary); color: white; border: none;">Active ({{ $orders->count() }})</button>
                <button class="flowexa-btn" style="padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; background: transparent; color: var(--text-secondary); border: none;">Completed</button>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem;">
            @forelse($orders as $order)
                <div class="flowexa-card" style="background: var(--surface); border: 1px solid var(--border); border-radius: 20px; overflow: hidden; display: flex; flex-direction: column;">
                    <div style="padding: 1.25rem; background: var(--surface-secondary); border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <span style="font-weight: 800; color: var(--headings); font-size: 1rem;">#{{ substr($order->id, 0, 6) }}</span>
                            <div style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 2px;">{{ $order->created_at->diffForHumans() }}</div>
                        </div>
                        <span style="background: rgba(249, 115, 22, 0.1); color: var(--primary); padding: 0.25rem 0.6rem; border-radius: 6px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase;">
                            {{ $order->order_type }}
                        </span>
                    </div>
                    <div style="padding: 1.25rem; flex: 1;">
                        <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.75rem;">
                            @foreach($order->items as $item)
                                <li style="display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem;">
                                    <div style="display: flex; gap: 0.75rem;">
                                        <span style="font-weight: 800; color: var(--primary); background: rgba(249, 115, 22, 0.1); width: 24px; height: 24px; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; flex-shrink: 0;">{{ (int)$item->quantity }}</span>
                                        <span style="font-weight: 600; color: var(--headings); line-height: 1.4;">{{ $item->product->name }}</span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div style="padding: 1rem; background: var(--surface-secondary); border-top: 1px solid var(--border);">
                        <form action="{{ route('product_service.pos.kitchen.complete', $order->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="flowexa-btn-primary" style="width: 100%; border: none; padding: 0.75rem; border-radius: 12px; font-weight: 700;">
                                <i class="fa-solid fa-check-double"></i> Mark as Ready
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; padding: 6rem 2rem; text-align: center; background: var(--surface); border: 1px dashed var(--border); border-radius: 32px;">
                    <i class="fa-solid fa-utensils" style="font-size: 4rem; color: var(--text-muted); opacity: 0.15; margin-bottom: 1.5rem; display: block;"></i>
                    <h2 style="color: var(--headings); font-weight: 800; margin-bottom: 0.5rem;">Kitchen is quiet</h2>
                    <p style="color: var(--text-secondary);">Waiting for incoming orders to prepare...</p>
                </div>
            @endforelse
        </div>
    </div>
</x-layouts.app>
