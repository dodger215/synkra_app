<x-layouts.app title="Store Settings - {{ $store->store_name }}">
    <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 800px; margin: 0 auto;">
        <div style="margin-bottom: 2rem;">
            <a href="{{ route('ecommerce.stores.show', $store->id) }}" style="color: var(--text-secondary); text-decoration: none; display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem; font-weight: 600;">
                <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
            </a>
            <h1 style="color: var(--headings); margin: 0 0 0.5rem 0; font-weight: 800;">Store Settings</h1>
            <p style="color: var(--text-secondary); margin: 0;">Configure your storefront details and appearance.</p>
        </div>

        <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); padding: 2rem;">
            <form action="{{ route('ecommerce.stores.update', $store->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div style="margin-bottom: 1.5rem;">
                    <label for="store_name" style="display: block; color: var(--text-primary); font-weight: 700; margin-bottom: 0.5rem;">Store Name</label>
                    <input type="text" name="store_name" id="store_name" required style="width: 100%; padding: 0.75rem 1rem; border-radius: 12px; border: 1px solid var(--border); background: var(--surface-secondary); color: var(--text-primary); outline: none;" value="{{ old('store_name', $store->store_name) }}">
                    @error('store_name')
                        <p style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label for="domain" style="display: block; color: var(--text-primary); font-weight: 700; margin-bottom: 0.5rem;">Subdomain</label>
                    <div style="display: flex; align-items: center;">
                        <input type="text" name="domain" id="domain" style="flex: 1; padding: 0.75rem 1rem; border-radius: 12px 0 0 12px; border: 1px solid var(--border); background: var(--surface-secondary); color: var(--text-primary); outline: none;" value="{{ old('domain', $store->domain) }}">
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
                            <option value="GHS" {{ old('currency', $store->currency) == 'GHS' ? 'selected' : '' }}>GHS (GH₵)</option>
                            <option value="USD" {{ old('currency', $store->currency) == 'USD' ? 'selected' : '' }}>USD ($)</option>
                            <option value="EUR" {{ old('currency', $store->currency) == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                            <option value="GBP" {{ old('currency', $store->currency) == 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                            <option value="NGN" {{ old('currency', $store->currency) == 'NGN' ? 'selected' : '' }}>NGN (₦)</option>
                        </select>
                    </div>
                    <div>
                        <label for="primary_color" style="display: block; color: var(--text-primary); font-weight: 700; margin-bottom: 0.5rem;">Primary Color</label>
                        <div style="display: flex; gap: 0.5rem;">
                            <input type="color" name="primary_color" id="primary_color" value="{{ old('primary_color', $store->primary_color) }}" style="width: 48px; height: 48px; padding: 2px; border: 1px solid var(--border); border-radius: 12px; cursor: pointer; background: var(--surface-secondary);">
                            <input type="text" id="primary_color_hex" value="{{ old('primary_color', $store->primary_color) }}" style="flex: 1; padding: 0.75rem 1rem; border-radius: 12px; border: 1px solid var(--border); background: var(--surface-secondary); color: var(--text-primary); outline: none;" oninput="document.getElementById('primary_color').value = this.value">
                        </div>
                    </div>
                </div>

                <div style="margin-bottom: 2rem;">
                    <label style="display: block; color: var(--text-primary); font-weight: 700; margin-bottom: 0.5rem;">Status</label>
                    <div style="display: flex; gap: 1.5rem;">
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                            <input type="radio" name="is_published" value="1" {{ old('is_published', $store->is_published) ? 'checked' : '' }}>
                            <span style="font-weight: 600; color: var(--text-primary);">Published</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                            <input type="radio" name="is_published" value="0" {{ !old('is_published', $store->is_published) ? 'checked' : '' }}>
                            <span style="font-weight: 600; color: var(--text-primary);">Draft</span>
                        </label>
                    </div>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 3rem; border-top: 1px solid var(--border); padding-top: 2rem;">
                    <button type="button" class="flowexa-btn" style="color: #ef4444; background: transparent; border: none; font-weight: 700; cursor: pointer;" onclick="if(confirm('Are you sure you want to permanently delete this store? All pages and data will be lost.')) document.getElementById('delete-store-form').submit();">
                        <i class="fa-solid fa-trash-can"></i> Delete Store
                    </button>
                    <button type="submit" class="flowexa-btn-primary" style="background: var(--primary); color: white; border: none; padding: 0.75rem 2rem; border-radius: 12px; font-weight: 700; cursor: pointer;">
                        Save Changes
                    </button>
                </div>
            </form>

            <form id="delete-store-form" action="{{ route('ecommerce.stores.destroy', $store->id) }}" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>

    <script>
        document.getElementById('primary_color').addEventListener('input', function() {
            document.getElementById('primary_color_hex').value = this.value;
        });
    </script>
</x-layouts.app>
