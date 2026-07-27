<x-layouts.app title="POS Orders">
    <x-ui.grid>
        <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 1200px; margin: 0 auto;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem;">
                <div>
                    <h1 style="color: var(--headings); margin: 0 0 0.5rem 0;">POS Orders</h1>
                    <p style="color: var(--text-secondary); margin: 0;">View and manage all transactions processed through the Point of Sale.</p>
                </div>
                <a href="{{ route('product_service.pos.index') }}" class="flowexa-btn flowexa-btn-primary" style="background: var(--primary); border: none; color: white; padding: 0.6rem 1.25rem; border-radius: 8px; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-cash-register"></i> Open POS
                </a>
            </div>

            @if(session('success'))
                <x-ui.alert type="success" title="Success" message="{{ session('success') }}" style="margin-bottom: 1.5rem;" />
            @endif

            <div class="flowexa-card" style="background: var(--surface); border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); border: 1px solid var(--border); overflow: hidden; padding: 1.5rem;">
                @if($orders->isEmpty())
                    <div style="text-align: center; padding: 4rem 2rem;">
                        <div style="width: 64px; height: 64px; background: var(--surface-secondary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem auto; font-size: 1.5rem; color: var(--text-secondary);">
                            <i class="fa-solid fa-receipt"></i>
                        </div>
                        <h3 style="margin: 0 0 0.5rem 0; color: var(--text-primary);">No Orders Found</h3>
                        <p style="color: var(--text-secondary); margin: 0 0 1.5rem 0; font-size: 0.95rem;">No POS transactions have been recorded yet.</p>
                    </div>
                @else
                    @php
                        $headers = ['Order #', 'Date', 'Customer', 'Cashier', 'Total', 'Payment', 'Actions'];
                        $rows = $orders->map(function ($order) {
                            $statusColor = $order->payment_method === 'cash' ? '#16a34a' : '#2563eb';
                            $paymentMethodHtml = new \Illuminate\Support\HtmlString(
                                '<span style="display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:99px;font-size:0.75rem;font-weight:700;text-transform:uppercase;background:' . $statusColor . '15;color:' . $statusColor . ';">'
                                . '<i class="fa-solid ' . ($order->payment_method === 'cash' ? 'fa-money-bill-wave' : 'fa-credit-card') . '"></i> '
                                . e(ucfirst($order->payment_method))
                                . '</span>'
                            );

                            $actions = new \Illuminate\Support\HtmlString(
                                '<div class="flowexa-table-actions">'
                                . '<a href="' . e(route('product_service.pos.order.show', $order->id)) . '" class="flowexa-table-action-btn" title="View Details"><i class="fa-solid fa-eye"></i></a>'
                                . '<form action="' . e(route('product_service.pos.device.print-receipt', $order->id)) . '" method="POST" target="_blank" style="margin:0;display:inline;">'
                                . csrf_field()
                                . '<button type="submit" class="flowexa-table-action-btn" title="Print Receipt"><i class="fa-solid fa-print"></i></button>'
                                . '</form></div>'
                            );

                            return [
                                new \Illuminate\Support\HtmlString('<strong style="color:var(--headings);">' . e($order->order_number) . '</strong>'),
                                $order->completed_at->format('M d, Y H:i'),
                                $order->customer ? $order->customer->name : new \Illuminate\Support\HtmlString('<span style="color:var(--text-secondary);font-style:italic;">Walk-in Customer</span>'),
                                $order->session->cashier->name,
                                new \Illuminate\Support\HtmlString('<strong style="color:var(--headings);">GH₵ ' . number_format($order->total_amount, 2) . '</strong>'),
                                $paymentMethodHtml,
                                $actions,
                            ];
                        })->all();
                    @endphp
                    <x-ui.table :headers="$headers" :rows="$rows" />

                    <div style="margin-top: 1.5rem;">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>
        </div>
    </x-ui.grid>
</x-layouts.app>
