<x-layouts.app title="Edit Supplier">
    <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 800px; margin: 0 auto;">
        <div style="margin-bottom: 2rem;">
            <a href="{{ route('supply_chain.suppliers.index') }}" style="color: var(--text-secondary); text-decoration: none; font-size: 0.9rem; font-weight: 600; display: flex; align-items: center; gap: 8px; margin-bottom: 1rem;">
                <i class="fa-solid fa-arrow-left"></i> Back to Suppliers
            </a>
            <h1 style="color: var(--headings); margin: 0; font-weight: 800;">Edit Supplier</h1>
            <p style="color: var(--text-secondary); margin: 0.25rem 0 0 0;">Updating details for {{ $supplier->company_name }}</p>
        </div>

        <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); padding: 2rem;">
            <form action="{{ route('supply_chain.suppliers.update', $supplier->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <x-ui.input name="supplier_code" label="Supplier Code" placeholder="e.g. SUP-001" required value="{{ old('supplier_code', $supplier->supplier_code) }}" />
                    <x-ui.input name="company_name" label="Company Name" placeholder="e.g. Acme Corp" required value="{{ old('company_name', $supplier->company_name) }}" />
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <x-ui.input name="contact_person" label="Contact Person" placeholder="Name of contact" value="{{ old('contact_person', $supplier->contact_person) }}" />
                    <x-ui.input name="email" type="email" label="Email Address" placeholder="vendor@example.com" value="{{ old('email', $supplier->email) }}" />
                </div>

                <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <x-ui.input name="phone" label="Phone Number" placeholder="+1 (555) 000-0000" value="{{ old('phone', $supplier->phone) }}" />
                </div>

                <div style="margin-bottom: 2rem;">
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: var(--headings); margin-bottom: 0.5rem;">Business Address</label>
                    <textarea name="address" rows="3" style="width: 100%; border-radius: 12px; border: 1px solid var(--border); background: var(--surface-secondary); color: var(--text-primary); padding: 0.75rem; font-family: inherit; resize: vertical;" placeholder="Full street address...">{{ old('address', $supplier->address) }}</textarea>
                </div>

                <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                    <a href="{{ route('supply_chain.suppliers.index') }}" class="flowexa-btn" style="background: var(--surface-secondary); border: 1px solid var(--border); color: var(--text-primary); padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; text-decoration: none;">Cancel</a>
                    <button type="submit" class="flowexa-btn" style="background: var(--primary); border: none; color: white; padding: 0.75rem 2rem; border-radius: 12px; font-weight: 700; cursor: pointer; box-shadow: 0 4px 15px rgba(249,115,22,0.2);">
                        Update Supplier
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .flowexa-input-group { max-width: none !important; }
    </style>
</x-layouts.app>
