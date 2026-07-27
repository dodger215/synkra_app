<x-layouts.app title="Production Orders">
    <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 1200px; margin: 0 auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h1 style="color: var(--headings); margin: 0; font-size: 1.75rem; font-weight: 800;">Production Orders</h1>
                <p style="color: var(--text-secondary); margin-top: 0.25rem;">Track manufacturing runs and work-in-progress.</p>
            </div>
            <button class="flowexa-btn-primary" style="padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700;" onclick="openflowexaModal('newProductionModal')">
                <i class="fa-solid fa-industry"></i> New Production Run
            </button>
        </div>

        <div class="flowexa-card" style="background: var(--surface); border: 1px solid var(--border); border-radius: 24px; padding: 4rem; text-align: center;">
            <div style="width: 100px; height: 100px; background: rgba(var(--primary-rgb), 0.1); border-radius: 30px; display: flex; align-items: center; justify-content: center; margin: 0 auto 2rem; font-size: 2.5rem; color: var(--primary);">
                <i class="fa-solid fa-industry"></i>
            </div>
            <h2 style="color: var(--headings); font-weight: 800; margin-bottom: 0.5rem;">No Active Production</h2>
            <p style="color: var(--text-secondary); max-width: 400px; margin: 0 auto 2rem;">Ready to start manufacturing? Create a production order to track components and labor costs.</p>
        </div>
    </div>

    <x-ui.modal id="newProductionModal" title="Start Production Run">
        <form action="{{ route('product_service.stocks.production.store') }}" method="POST" id="newProductionForm">
            @csrf
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 800; color: var(--text-secondary); margin-bottom: 0.5rem; text-transform: uppercase;">Select BOM (Recipe)</label>
                    <select name="bill_of_material_id" class="flowexa-btn" style="width: 100%; text-align: left; background: var(--surface-secondary); border: 1px solid var(--border); padding: 0.75rem; border-radius: 12px; color: var(--text-primary); cursor: pointer; appearance: none; font-weight: 600;">
                        @foreach($boms as $bom)
                            <option value="{{ $bom->id }}">{{ $bom->product->name }} (Yields {{ (int)$bom->quantity }})</option>
                        @endforeach
                    </select>
                </div>
                <x-ui.input label="Production Quantity" name="quantity" type="number" value="1" step="0.0001" required />
            </div>
        </form>
        <x-slot:footer>
            <button type="button" class="flowexa-btn" onclick="closeflowexaModal('newProductionModal')" style="padding: 0.75rem 1.5rem; background: var(--surface-secondary); border: 1px solid var(--border); border-radius: 12px; cursor: pointer; font-weight: 600;">Cancel</button>
            <button type="button" class="flowexa-btn-primary" onclick="document.getElementById('newProductionForm').submit()" style="padding: 0.75rem 1.5rem; border: none; border-radius: 12px; font-weight: 700; cursor: pointer;">
                <i class="fa-solid fa-play"></i> Start Production
            </button>
        </x-slot:footer>
    </x-ui.modal>
</x-layouts.app>
