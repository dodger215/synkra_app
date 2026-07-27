<x-layouts.app title="Restaurant Tables">
    <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 1200px; margin: 0 auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h1 style="color: var(--headings); margin: 0; font-size: 1.75rem; font-weight: 800;">Table Management</h1>
                <p style="color: var(--text-secondary); margin-top: 0.25rem;">Monitor and manage your restaurant floor layout.</p>
            </div>
            <button class="flowexa-btn-primary" style="padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700;" onclick="openflowexaModal('addTableModal')">
                <i class="fa-solid fa-plus"></i> Add New Table
            </button>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 1.5rem;">
            @forelse($tables as $table)
                <div class="flowexa-card" style="background: var(--surface); border: 1px solid var(--border); border-radius: 24px; padding: 1.5rem; text-align: center; transition: all 0.2s;" onmouseover="this.style.borderColor='var(--primary)';" onmouseout="this.style.borderColor='var(--border)';">
                    <div style="width: 64px; height: 64px; background: {{ $table->status === 'available' ? 'rgba(34, 197, 94, 0.1)' : 'rgba(239, 68, 68, 0.1)' }}; color: {{ $table->status === 'available' ? '#22c55e' : '#ef4444' }}; border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem; font-size: 1.5rem;">
                        <i class="fa-solid fa-chair"></i>
                    </div>
                    <h3 style="margin: 0; color: var(--headings); font-size: 1.1rem; font-weight: 800;">{{ $table->name }}</h3>
                    <p style="color: var(--text-secondary); font-size: 0.85rem; margin: 0.5rem 0 1rem;">Capacity: {{ $table->capacity }} people</p>
                    <span style="display: inline-block; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; background: {{ $table->status === 'available' ? 'rgba(34, 197, 94, 0.1)' : 'rgba(239, 68, 68, 0.1)' }}; color: {{ $table->status === 'available' ? '#22c55e' : '#ef4444' }};">
                        {{ ucfirst($table->status) }}
                    </span>
                </div>
            @empty
                <div style="grid-column: 1 / -1; padding: 4rem; text-align: center; background: var(--surface); border: 1px dashed var(--border); border-radius: 24px;">
                    <i class="fa-solid fa-chair" style="font-size: 3rem; color: var(--text-muted); opacity: 0.2; margin-bottom: 1rem; display: block;"></i>
                    <h3 style="color: var(--headings); font-weight: 700;">No tables found</h3>
                    <p style="color: var(--text-secondary);">Start by adding your restaurant floor layout.</p>
                </div>
            @endforelse
        </div>
    </div>

    <x-ui.modal id="addTableModal" title="Add New Table">
        <form action="{{ route('product_service.pos.tables.store') }}" method="POST" id="addTableForm">
            @csrf
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                <x-ui.input name="name" label="Table Name / Number" placeholder="e.g. Table 01" required />
                <x-ui.input name="capacity" label="Capacity (Seats)" type="number" value="4" min="1" required />
            </div>
        </form>
        <x-slot:footer>
            <button type="button" class="flowexa-btn" onclick="closeflowexaModal('addTableModal')" style="padding: 0.75rem 1.5rem; background: var(--surface-secondary); border: 1px solid var(--border); border-radius: 12px; cursor: pointer; font-weight: 600;">Cancel</button>
            <button type="button" class="flowexa-btn-primary" onclick="document.getElementById('addTableForm').submit()" style="padding: 0.75rem 1.5rem; border: none; border-radius: 12px; font-weight: 700; cursor: pointer;">
                <i class="fa-solid fa-save"></i> Save Table
            </button>
        </x-slot:footer>
    </x-ui.modal>
</x-layouts.app>
