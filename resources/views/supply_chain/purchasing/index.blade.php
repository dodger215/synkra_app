<x-layouts.app title="Purchase Orders">
    <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 1200px; margin: 0 auto;">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem;">
            <div>
                <h1 style="color: var(--headings); margin: 0 0 0.5rem 0; font-weight: 800;">Purchase Orders</h1>
                <p style="color: var(--text-secondary); margin: 0;">Manage procurement and track orders from suppliers.</p>
            </div>
            <div>
                <a href="{{ route('supply_chain.purchasing.create') }}" class="flowexa-btn" style="background: var(--primary); border: none; color: white; padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 15px rgba(249,115,22,0.2);">
                    <i class="fa-solid fa-cart-plus"></i> Create PO
                </a>
            </div>
        </div>

        @if(session('success'))
            <div style="background: rgba(34, 197, 94, 0.1); color: #166534; padding: 1rem; border-radius: 12px; margin-bottom: 2rem; border: 1px solid rgba(34, 197, 94, 0.2); font-weight: 600;">
                <i class="fa-solid fa-circle-check" style="margin-right: 8px;"></i> {{ session('success') }}
            </div>
        @endif

        <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); padding: 1.5rem; overflow: hidden;">
            @if($purchaseOrders->isEmpty())
                <div style="text-align: center; padding: 4rem 0;">
                    <div style="width: 64px; height: 64px; background: var(--surface-secondary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--text-secondary); margin: 0 auto 1.5rem;">
                        <i class="fa-solid fa-file-invoice-dollar" style="font-size: 1.5rem;"></i>
                    </div>
                    <h3 style="color: var(--headings); margin-bottom: 0.5rem;">No purchase orders</h3>
                    <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">Create your first purchase order to restock inventory.</p>
                    <a href="{{ route('supply_chain.purchasing.create') }}" class="flowexa-btn" style="background: var(--surface-secondary); border: 1px solid var(--border); color: var(--text-primary); padding: 0.6rem 1.25rem; border-radius: 10px; font-weight: 600; text-decoration: none; display: inline-block;">
                        Create Purchase Order
                    </a>
                </div>
            @else
                @php
                    $headers = ['PO Number', 'Supplier', 'Order Date', 'Status', 'Total', 'Actions'];
                    $rows = $purchaseOrders->map(function($po) {
                        return [
                            $po->po_number,
                            $po->supplier->company_name ?? 'N/A',
                            $po->order_date ? \Carbon\Carbon::parse($po->order_date)->format('M d, Y') : 'N/A',
                            new \Illuminate\Support\HtmlString('<span class="flowexa-badge-pill" style="background: var(--surface-secondary); color: var(--text-primary); text-transform: uppercase;">' . ($po->status ?? 'Draft') . '</span>'),
                            '₵' . number_format($po->total_amount, 2),
                            new \Illuminate\Support\HtmlString('
                                <div style="display: flex; gap: 10px;">
                                    <a href="' . route('supply_chain.purchasing.show', $po->id) . '" style="color: var(--text-secondary);"><i class="fa-solid fa-eye"></i></a>
                                    <a href="' . route('supply_chain.purchasing.edit', $po->id) . '" style="color: var(--primary);"><i class="fa-solid fa-pen-to-square"></i></a>
                                    <form action="' . route('supply_chain.purchasing.destroy', $po->id) . '" method="POST" style="display:inline;" onsubmit="return confirm(\'Are you sure?\')">
                                        ' . csrf_field() . '
                                        ' . method_field('DELETE') . '
                                        <button type="submit" style="background:none; border:none; color: var(--danger); cursor:pointer; padding:0;"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </div>
                            ')
                        ];
                    })->toArray();
                @endphp
                <x-ui.table :headers="$headers" :rows="$rows" />
            @endif
        </div>
    </div>
</x-layouts.app>
