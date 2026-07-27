<x-layouts.app title="Create Ecommerce Store">
    <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 800px; margin: 0 auto;">
        <div style="margin-bottom: 2rem;">
            <a href="{{ route('ecommerce.stores.index') }}" style="color: var(--text-secondary); text-decoration: none; display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem; font-weight: 600;">
                <i class="fa-solid fa-arrow-left"></i> Back to Stores
            </a>
            <h1 style="color: var(--headings); margin: 0 0 0.5rem 0; font-weight: 800;">Create New Store</h1>
            <p style="color: var(--text-secondary); margin: 0;">Configure your new online storefront.</p>
        </div>

        <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); padding: 2rem;">
            <form action="{{ route('ecommerce.stores.store') }}" method="POST">
                @csrf

                <div style="margin-bottom: 1.5rem;">
                    <label for="store_name" style="display: block; color: var(--text-primary); font-weight: 700; margin-bottom: 0.5rem;">Store Name</label>
                    <input type="text" name="store_name" id="store_name" required placeholder="e.g. My Amazing Shop" style="width: 100%; padding: 0.75rem 1rem; border-radius: 12px; border: 1px solid var(--border); background: var(--surface-secondary); color: var(--text-primary); outline: none;" value="{{ old('store_name') }}">
                    @error('store_name')
                        <p style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label for="domain" style="display: block; color: var(--text-primary); font-weight: 700; margin-bottom: 0.5rem;">Domain (Optional)</label>
                    <div style="display: flex; align-items: center;">
                        <input type="text" name="domain" id="domain" placeholder="mystore" style="flex: 1; padding: 0.75rem 1rem; border-radius: 12px 0 0 12px; border: 1px solid var(--border); background: var(--surface-secondary); color: var(--text-primary); outline: none;" value="{{ old('domain') }}">
                        <span style="background: var(--surface-secondary); border: 1px solid var(--border); border-left: none; padding: 0.75rem 1rem; border-radius: 0 12px 12px 0; color: var(--text-secondary); font-weight: 600;">.flowexa.com</span>
                    </div>
                    @error('domain')
                        <p style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div>
                        <label for="currency" style="display: block; color: var(--text-primary); font-weight: 700; margin-bottom: 0.5rem;">Currency</label>
                        <select name="currency" id="currency" required style="width: 100%; padding: 0.75rem 1rem; border-radius: 12px; border: 1px solid var(--border); background: var(--surface-secondary); color: var(--text-primary); outline: none;">
                            <option value="GHS" {{ old('currency', 'GHS') == 'GHS' ? 'selected' : '' }}>GHS (GH₵)</option>
                            <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD ($)</option>
                            <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                            <option value="GBP" {{ old('currency') == 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                            <option value="NGN" {{ old('currency') == 'NGN' ? 'selected' : '' }}>NGN (₦)</option>
                        </select>
                        @error('currency')
                            <p style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="primary_color" style="display: block; color: var(--text-primary); font-weight: 700; margin-bottom: 0.5rem;">Brand Color</label>
                        <div style="display: flex; gap: 0.5rem;">
                            <input type="color" name="primary_color" id="primary_color" value="{{ old('primary_color', '#f97316') }}" style="width: 48px; height: 48px; padding: 2px; border: 1px solid var(--border); border-radius: 12px; cursor: pointer; background: var(--surface-secondary);">
                            <input type="text" name="primary_color_hex" id="primary_color_hex" value="{{ old('primary_color', '#f97316') }}" style="flex: 1; padding: 0.75rem 1rem; border-radius: 12px; border: 1px solid var(--border); background: var(--surface-secondary); color: var(--text-primary); outline: none;" oninput="document.getElementById('primary_color').value = this.value">
                        </div>
                    </div>
                </div>

                <div style="margin-top: 2rem; display: flex; justify-content: flex-end;">
                    <button type="submit" class="flowexa-btn-primary" style="background: var(--primary); color: white; border: none; padding: 0.75rem 2rem; border-radius: 12px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fa-solid fa-plus"></i> Create Store
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('primary_color').addEventListener('input', function() {
            document.getElementById('primary_color_hex').value = this.value;
        });
    </script>
</x-layouts.app>
