<x-layouts.app title="Bill of Materials">
    <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 1200px; margin: 0 auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h1 style="color: var(--headings); margin: 0; font-size: 1.75rem; font-weight: 800;">Bill of Materials (BOM)</h1>
                <p style="color: var(--text-secondary); margin-top: 0.25rem;">Define recipe and component lists for manufactured products.</p>
            </div>
            <button class="flowexa-btn-primary" style="padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700;" onclick="openflowexaModal('createBomModal')">
                <i class="fa-solid fa-plus"></i> Create New BOM
            </button>
        </div>

        <div class="flowexa-card" style="background: var(--surface); border: 1px solid var(--border); border-radius: 24px; overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="background: var(--surface-secondary); border-bottom: 1px solid var(--border);">
                        <th style="padding: 1rem 1.5rem; font-size: 0.75rem; font-weight: 800; color: var(--text-secondary); text-transform: uppercase;">Final Product</th>
                        <th style="padding: 1rem 1.5rem; font-size: 0.75rem; font-weight: 800; color: var(--text-secondary); text-transform: uppercase;">Standard Qty</th>
                        <th style="padding: 1rem 1.5rem; font-size: 0.75rem; font-weight: 800; color: var(--text-secondary); text-transform: uppercase;">Components</th>
                        <th style="padding: 1rem 1.5rem; font-size: 0.75rem; font-weight: 800; color: var(--text-secondary); text-transform: uppercase;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($boms as $bom)
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 1.25rem 1.5rem; font-weight: 700; color: var(--headings);">{{ $bom->product->name }}</td>
                            <td style="padding: 1.25rem 1.5rem;">{{ $bom->quantity }} {{ $bom->product->unit_of_measure }}</td>
                            <td style="padding: 1.25rem 1.5rem;"><span class="flowexa-badge" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">{{ $bom->items_count ?? 0 }} Items</span></td>
                            <td style="padding: 1.25rem 1.5rem;"><button style="background: none; border: none; color: var(--primary); cursor: pointer;"><i class="fa-solid fa-eye"></i></button></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="padding: 4rem; text-align: center; color: var(--text-secondary);">
                                <i class="fa-solid fa-boxes-stacked" style="font-size: 2.5rem; opacity: 0.1; display: block; margin-bottom: 1rem;"></i>
                                No Bill of Materials defined yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <x-ui.modal id="createBomModal" title="Create Bill of Materials" size="lg">
        <form action="{{ route('product_service.stocks.bom.store') }}" method="POST" id="createBomForm">
            @csrf
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 800; color: var(--text-secondary); margin-bottom: 0.5rem; text-transform: uppercase;">Finished Product</label>
                    <select name="product_id" class="flowexa-btn" style="width: 100%; text-align: left; background: var(--surface-secondary); border: 1px solid var(--border); padding: 0.75rem; border-radius: 12px; color: var(--text-primary); cursor: pointer; appearance: none; font-weight: 600;">
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->sku }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-ui.input label="Yield Quantity" name="quantity" type="number" value="1" step="0.0001" required />
                </div>
            </div>

            <div style="margin-bottom: 1rem; display: flex; justify-content: space-between; align-items: center;">
                <h4 style="margin: 0; color: var(--headings); font-weight: 800; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.05em;">Components / Ingredients</h4>
                <button type="button" class="flowexa-btn" onclick="addComponentRow()" style="padding: 0.4rem 0.8rem; font-size: 0.75rem; border: 1px dashed var(--primary); color: var(--primary); border-radius: 8px; font-weight: 700;">
                    <i class="fa-solid fa-plus"></i> Add Item
                </button>
            </div>

            <div id="components-list" style="display: flex; flex-direction: column; gap: 0.75rem;">
                <div class="component-row" style="display: grid; grid-template-columns: 2fr 1fr 40px; gap: 1rem; align-items: flex-end;">
                    <div>
                        <select name="components[0][id]" class="flowexa-btn" style="width: 100%; text-align: left; background: var(--surface-secondary); border: 1px solid var(--border); padding: 0.6rem; border-radius: 8px; color: var(--text-primary); font-size: 0.85rem;">
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <input type="number" name="components[0][qty]" placeholder="Qty" step="0.0001" style="width: 100%; padding: 0.6rem; background: var(--surface-secondary); border: 1px solid var(--border); border-radius: 8px; font-size: 0.85rem; color: var(--text-primary);">
                    </div>
                    <button type="button" style="background: none; border: none; color: var(--danger); opacity: 0.3; cursor: not-allowed;"><i class="fa-solid fa-trash"></i></button>
                </div>
            </div>
        </form>
        <x-slot:footer>
            <button type="button" class="flowexa-btn" onclick="closeflowexaModal('createBomModal')" style="padding: 0.75rem 1.5rem; background: var(--surface-secondary); border: 1px solid var(--border); border-radius: 12px; cursor: pointer; font-weight: 600;">Cancel</button>
            <button type="button" class="flowexa-btn-primary" onclick="document.getElementById('createBomForm').submit()" style="padding: 0.75rem 1.5rem; border: none; border-radius: 12px; font-weight: 700; cursor: pointer;">
                <i class="fa-solid fa-save"></i> Save BOM
            </button>
        </x-slot:footer>
    </x-ui.modal>

    <script>
        let rowCount = 1;
        function addComponentRow() {
            const list = document.getElementById('components-list');
            const row = document.createElement('div');
            row.className = 'component-row';
            row.style = 'display: grid; grid-template-columns: 2fr 1fr 40px; gap: 1rem; align-items: flex-end;';
            row.innerHTML = `
                <div>
                    <select name="components[${rowCount}][id]" class="flowexa-btn" style="width: 100%; text-align: left; background: var(--surface-secondary); border: 1px solid var(--border); padding: 0.6rem; border-radius: 8px; color: var(--text-primary); font-size: 0.85rem;">
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <input type="number" name="components[${rowCount}][qty]" placeholder="Qty" step="0.0001" style="width: 100%; padding: 0.6rem; background: var(--surface-secondary); border: 1px solid var(--border); border-radius: 8px; font-size: 0.85rem; color: var(--text-primary);">
                </div>
                <button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; color: var(--danger); cursor: pointer;"><i class="fa-solid fa-trash"></i></button>
            `;
            list.appendChild(row);
            rowCount++;
        }
    </script>
</x-layouts.app>
