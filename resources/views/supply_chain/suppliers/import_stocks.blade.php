<x-layouts.app title="Import Stocks for Supply">
    <div class="flowexa-dashboard-container" style="padding: 2rem;">
        <div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: flex-end;">
            <div>
                <h1 style="color: var(--headings); margin: 0; font-weight: 800;">Stock Import for Network</h1>
                <p style="color: var(--text-secondary); margin: 0.25rem 0 0 0;">Synchronize your local inventory with the network supply chain.</p>
            </div>
        </div>

        @if(session('success'))
            <x-ui.alert type="success" title="Success" message="{{ session('success') }}" style="margin-bottom: 2rem;" />
        @endif

        @if($errors->any())
            <x-ui.alert type="danger" title="Error" message="Please fix the errors below." style="margin-bottom: 2rem;">
                <ul style="margin-top: 0.5rem; margin-bottom: 0;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-ui.alert>
        @endif

        <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: var(--surface-secondary); border-bottom: 1px solid var(--border);">
                        <th style="padding: 1.25rem 1.5rem; text-align: left; font-size: 0.85rem; color: var(--text-secondary); text-transform: uppercase;">Product</th>
                        <th style="padding: 1.25rem 1.5rem; text-align: left; font-size: 0.85rem; color: var(--text-secondary); text-transform: uppercase;">Current Stock</th>
                        <th style="padding: 1.25rem 1.5rem; text-align: left; font-size: 0.85rem; color: var(--text-secondary); text-transform: uppercase;">Network Status</th>
                        <th style="padding: 1.25rem 1.5rem; text-align: left; font-size: 0.85rem; color: var(--text-secondary); text-transform: uppercase;">Supply Config</th>
                        <th style="padding: 1.25rem 1.5rem; text-align: right; font-size: 0.85rem; color: var(--text-secondary); text-transform: uppercase;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                        @forelse($products as $product)
                            @php
                                $totalStock = $product->stockBalances->sum('quantity_on_hand');
                            @endphp
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
                                        <div>
                                            <div style="font-weight: 700; color: var(--headings);">{{ $product->name }}</div>
                                            <div style="font-size: 0.8rem; color: var(--text-secondary);">{{ $product->sku }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 1.25rem 1.5rem; color: var(--text-primary);">
                                    {{ number_format($totalStock) }} {{ $product->unit_type ?? 'units' }}
                                </td>
                                <td style="padding: 1.25rem 1.5rem;">
                                    @if($product->is_network_available)
                                        <span style="background: rgba(34, 197, 94, 0.1); color: #22c55e; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 700;">In Network</span>
                                    @else
                                        <span style="background: var(--surface-secondary); color: var(--text-secondary); padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 700;">Local Only</span>
                                    @endif
                                </td>
                                <td style="padding: 1.25rem 1.5rem; font-size: 0.85rem; color: var(--text-secondary);">
                                    @if($product->is_network_available)
                                        Price: ₵{{ number_format($product->supply_price, 2) }}<br>
                                        Min: {{ number_format($product->supply_min_order) }} | Buffer: {{ $product->supply_buffer_percent }}%
                                    @else
                                        -
                                    @endif
                                </td>
                            <td style="padding: 1.25rem 1.5rem; text-align: right;">
                                <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                    <button type="button"
                                            onclick="openSupplyModal({{ json_encode([
                                                'id' => $product->id,
                                                'name' => $product->name,
                                                'sku' => $product->sku,
                                                'stock' => $totalStock,
                                                'unit_price' => $product->unit_price,
                                                'supply_price' => $product->supply_price ?? ($product->unit_price * 0.9),
                                                'supply_min_order' => $product->supply_min_order ?? 1,
                                                'supply_max_order' => $product->supply_max_order,
                                                'supply_buffer_percent' => $product->supply_buffer_percent ?? 20,
                                                'is_edit' => $product->is_network_available
                                            ]) }})"
                                            class="flowexa-btn"
                                            style="background: {{ $product->is_network_available ? 'rgba(59, 130, 246, 0.1)' : 'var(--primary)' }}; color: {{ $product->is_network_available ? 'var(--primary)' : 'white' }}; border: none; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; cursor: pointer;">
                                        {{ $product->is_network_available ? 'Edit Supply' : 'Import to Supply' }}
                                    </button>

                                    @if($product->is_network_available)
                                        <form action="{{ route('supply_chain.import_stocks.remove', $product->id) }}" method="POST" onsubmit="return confirm('Remove this product from the flowexa Network?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="flowexa-btn" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; border: none; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; cursor: pointer;">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding: 3rem; text-align: center; color: var(--text-secondary);">
                                No products found in your inventory.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Supply Modal --}}
    <div id="supplyModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; padding: 1rem;">
        <div style="background: var(--surface); border-radius: 24px; max-width: 500px; width: 100%; border: 1px solid var(--border); box-shadow: 0 20px 50px rgba(0,0,0,0.2); overflow: hidden;">
            <div style="padding: 1.5rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
                <h3 id="modalTitle" style="margin: 0; color: var(--headings); font-weight: 800;">Import to Supply</h3>
                <button onclick="closeSupplyModal()" style="background: none; border: none; color: var(--text-secondary); cursor: pointer; font-size: 1.25rem;"><i class="fa-solid fa-xmark"></i></button>
            </div>

            <form action="{{ route('supply_chain.import_stocks.process') }}" method="POST" style="padding: 1.5rem;">
                @csrf
                <input type="hidden" name="product_id" id="modal_product_id">

                <div style="margin-bottom: 1.5rem;">
                    <div id="modal_product_name" style="font-weight: 700; color: var(--headings); font-size: 1.1rem;"></div>
                    <div id="modal_product_sku" style="font-size: 0.85rem; color: var(--text-secondary);"></div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                    <div>
                        <label style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.5rem;">Network Supply Price (₵)</label>
                        <input type="number" step="0.01" name="supply_price" id="modal_supply_price" required style="width: 100%; padding: 0.75rem; border-radius: 12px; border: 1px solid var(--border); background: var(--surface-secondary); color: var(--text-primary); outline: none;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.5rem;">Supply Buffer (%)</label>
                        <input type="number" name="supply_buffer_percent" id="modal_buffer" oninput="calculateMaxOrder()" required style="width: 100%; padding: 0.75rem; border-radius: 12px; border: 1px solid var(--border); background: var(--surface-secondary); color: var(--text-primary); outline: none;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                    <div>
                        <label style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.5rem;">Min Order Qty</label>
                        <input type="number" name="supply_min_order" id="modal_min_order" required style="width: 100%; padding: 0.75rem; border-radius: 12px; border: 1px solid var(--border); background: var(--surface-secondary); color: var(--text-primary); outline: none;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.5rem;">Max Available (Auto)</label>
                        <input type="number" name="supply_max_order" id="modal_max_order" readonly style="width: 100%; padding: 0.75rem; border-radius: 12px; border: 1px solid var(--border); background: rgba(0,0,0,0.05); color: var(--text-secondary); outline: none;">
                    </div>
                </div>

                <div style="background: rgba(59, 130, 246, 0.05); padding: 1rem; border-radius: 12px; border: 1px dashed var(--primary); margin-bottom: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                        <span style="font-size: 0.85rem; color: var(--text-secondary);">Total Local Stock:</span>
                        <strong id="modal_local_stock" style="color: var(--headings);">0</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="font-size: 0.85rem; color: var(--text-secondary);">Reserved for Network:</span>
                        <strong id="modal_reserved" style="color: var(--primary);">0</strong>
                    </div>
                </div>

                <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                    <button type="button" onclick="closeSupplyModal()" style="background: var(--surface-secondary); border: 1px solid var(--border); color: var(--text-primary); padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; cursor: pointer;">Cancel</button>
                    <button type="submit" class="flowexa-btn" style="background: var(--primary); border: none; color: white; padding: 0.75rem 2rem; border-radius: 12px; font-weight: 700; cursor: pointer;">
                        Save Supply Config
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentProduct = null;

        function openSupplyModal(data) {
            currentProduct = data;
            document.getElementById('supplyModal').style.display = 'flex';
            document.getElementById('modalTitle').innerText = data.is_edit ? 'Edit Supply Configuration' : 'Import to Network Supply';
            document.getElementById('modal_product_id').value = data.id;
            document.getElementById('modal_product_name').innerText = data.name;
            document.getElementById('modal_product_sku').innerText = 'SKU: ' + data.sku;
            document.getElementById('modal_local_stock').innerText = data.stock + ' units';

            document.getElementById('modal_supply_price').value = parseFloat(data.supply_price).toFixed(2);
            document.getElementById('modal_buffer').value = data.supply_buffer_percent;
            document.getElementById('modal_min_order').value = data.supply_min_order;

            calculateMaxOrder();
        }

        function closeSupplyModal() {
            document.getElementById('supplyModal').style.display = 'none';
        }

        function calculateMaxOrder() {
            if (!currentProduct) return;

            const stock = currentProduct.stock;
            const buffer = document.getElementById('modal_buffer').value || 0;

            // Max available for network = Total Stock * (1 - Buffer%)
            const maxOrder = Math.max(0, Math.floor(stock * (1 - buffer / 100)));
            const reserved = stock - maxOrder;

            document.getElementById('modal_max_order').value = maxOrder;
            document.getElementById('modal_reserved').innerText = reserved + ' units';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('supplyModal');
            if (event.target == modal) {
                closeSupplyModal();
            }
        }
    </script>
</x-layouts.app>
