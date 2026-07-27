<x-layouts.app title="Add Supplier">
    <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 800px; margin: 0 auto;">
        <div style="margin-bottom: 2rem;">
            <a href="{{ route('supply_chain.suppliers.index') }}" style="color: var(--text-secondary); text-decoration: none; font-size: 0.9rem; font-weight: 600; display: flex; align-items: center; gap: 8px; margin-bottom: 1rem;">
                <i class="fa-solid fa-arrow-left"></i> Back to Suppliers
            </a>
            <h1 style="color: var(--headings); margin: 0; font-weight: 800;">Add New Supplier</h1>
            <p style="color: var(--text-secondary); margin: 0.25rem 0 0 0;">Register a new vendor in your supply chain.</p>
        </div>

        <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); padding: 2rem;">
            <form action="{{ route('supply_chain.suppliers.store') }}" method="POST">
                @csrf

                <div style="margin-bottom: 2rem;">
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: var(--headings); margin-bottom: 1rem;">Supplier Source</label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <label style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; border: 1px solid var(--border); border-radius: 12px; background: var(--surface-secondary); cursor: pointer;">
                            <input type="radio" name="supplier_type" value="manual" checked onclick="toggleSupplierType('manual')">
                            <div>
                                <strong style="display: block; font-size: 0.95rem;">Manual Entry</strong>
                                <span style="font-size: 0.8rem; color: var(--text-secondary);">Register a vendor manually.</span>
                            </div>
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; border: 1px solid var(--border); border-radius: 12px; background: var(--surface-secondary); cursor: pointer;">
                            <input type="radio" name="supplier_type" value="network" onclick="toggleSupplierType('network')">
                            <div>
                                <strong style="display: block; font-size: 0.95rem;">flowexa Network</strong>
                                <span style="font-size: 0.8rem; color: var(--text-secondary);">Connect with a shop on flowexa.</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div id="network_fields" style="display: none; margin-bottom: 2rem; padding: 1.5rem; border-radius: 16px; background: rgba(59, 130, 246, 0.05); border: 1px dashed var(--primary);">
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: var(--headings); margin-bottom: 0.5rem;">Select flowexa Shop</label>
                    <select name="supplier_tenant_id" onchange="autofillFromNetwork(this)" style="width: 100%; padding: 0.75rem; border-radius: 12px; border: 1px solid var(--border); background: var(--surface); color: var(--text-primary); outline: none;">
                        <option value="">-- Choose a shop --</option>
                        @foreach($networkSuppliers as $shop)
                            @php
                                $primaryUser = $shop->users->first();
                            @endphp
                            <option value="{{ $shop->id }}"
                                    data-name="{{ $shop->name }}"
                                    data-contact="{{ $primaryUser?->name }}"
                                    data-email="{{ $primaryUser?->email }}"
                                    data-phone="{{ $primaryUser?->phone_number }}"
                                    data-subdomain="{{ $shop->subdomain }}">
                                {{ $shop->name }} ({{ $shop->subdomain ?? 'no-subdomain' }})
                            </option>
                        @endforeach
                    </select>
                    <p style="margin-top: 0.5rem; font-size: 0.8rem; color: var(--text-secondary);">A connection request will be sent to this shop for approval.</p>
                </div>

                <div id="common_fields">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                        <x-ui.input name="supplier_code" label="Supplier Code" placeholder="e.g. SUP-001" required value="{{ old('supplier_code') }}" />
                        <div id="manual_name_field">
                            <x-ui.input name="company_name" label="Company Name" placeholder="e.g. Acme Corp" required value="{{ old('company_name') }}" />
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                        <x-ui.input name="contact_person" label="Contact Person" placeholder="Name of contact" value="{{ old('contact_person') }}" />
                        <x-ui.input name="email" type="email" label="Email Address" placeholder="vendor@example.com" value="{{ old('email') }}" />
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                        <x-ui.input name="phone" label="Phone Number" placeholder="+1 (555) 000-0000" value="{{ old('phone') }}" />
                    </div>

                    <div style="margin-bottom: 2rem;">
                        <label style="display: block; font-size: 0.875rem; font-weight: 600; color: var(--headings); margin-bottom: 0.5rem;">Business Address</label>
                        <textarea name="address" rows="3" style="width: 100%; border-radius: 12px; border: 1px solid var(--border); background: var(--surface-secondary); color: var(--text-primary); padding: 0.75rem; font-family: inherit; resize: vertical;" placeholder="Full street address...">{{ old('address') }}</textarea>
                    </div>
                </div>

                <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                    <a href="{{ route('supply_chain.suppliers.index') }}" class="flowexa-btn" style="background: var(--surface-secondary); border: 1px solid var(--border); color: var(--text-primary); padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; text-decoration: none;">Cancel</a>
                    <button type="submit" class="flowexa-btn" style="background: var(--primary); border: none; color: white; padding: 0.75rem 2rem; border-radius: 12px; font-weight: 700; cursor: pointer; box-shadow: 0 4px 15px rgba(249,115,22,0.2);">
                        Create Supplier
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleSupplierType(type) {
            const networkFields = document.getElementById('network_fields');
            const manualNameField = document.getElementById('manual_name_field');
            const companyNameInput = document.querySelector('input[name="company_name"]');

            if (type === 'network') {
                networkFields.style.display = 'block';
                manualNameField.style.display = 'none';
                companyNameInput.required = false;
            } else {
                networkFields.style.display = 'none';
                manualNameField.style.display = 'block';
                companyNameInput.required = true;
                // Clear fields when switching back to manual?
                // Maybe not, user might have typed something.
            }
        }

        function autofillFromNetwork(select) {
            const option = select.options[select.selectedIndex];
            if (!option.value) return;

            const name = option.getAttribute('data-name');
            const contact = option.getAttribute('data-contact');
            const email = option.getAttribute('data-email');
            const phone = option.getAttribute('data-phone');
            const subdomain = option.getAttribute('data-subdomain');

            // Fill inputs
            const companyNameInput = document.querySelector('input[name="company_name"]');
            const contactPersonInput = document.querySelector('input[name="contact_person"]');
            const emailInput = document.querySelector('input[name="email"]');
            const phoneInput = document.querySelector('input[name="phone"]');
            const supplierCodeInput = document.querySelector('input[name="supplier_code"]');

            if (companyNameInput) companyNameInput.value = name;
            if (contactPersonInput) contactPersonInput.value = contact || '';
            if (emailInput) emailInput.value = email || '';
            if (phoneInput) phoneInput.value = phone || '';

            // Generate Supplier Code
            if (supplierCodeInput) {
                const prefix = 'SUP-';
                const base = subdomain || name.substring(0, 3).toUpperCase();
                const random = Math.floor(1000 + Math.random() * 9000);
                supplierCodeInput.value = prefix + base.toUpperCase().replace(/[^A-Z0-9]/g, '') + '-' + random;
            }
        }
    </script>

    <style>
        .flowexa-input-group { max-width: none !important; }
    </style>
</x-layouts.app>
