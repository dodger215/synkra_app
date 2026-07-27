<x-layouts.app title="Edit Category">
    <x-ui.grid>
        <div class="flowexa-dashboard-container" style="padding:2rem;max-width:700px;margin:0 auto;">
            <div style="margin-bottom:1.5rem;">
                <a href="{{ route('product_service.categories.show', $category->id) }}" style="color:var(--text-secondary);text-decoration:none;font-size:.9rem;display:inline-flex;align-items:center;gap:.5rem;margin-bottom:.75rem;">
                    <i class="fa-solid fa-arrow-left"></i> Back to Category
                </a>
                <h1 style="color:var(--headings);margin:0 0 .25rem 0;">Edit Category</h1>
                <p style="color:var(--text-secondary);margin:0;">Update category name and description.</p>
            </div>

            @if($errors->any())
                <x-ui.alert type="danger" title="Validation Error" :message="$errors->first()" style="margin-bottom:1.5rem;" />
            @endif

            <div class="flowexa-card" style="background:var(--surface);border-radius:16px;padding:1.5rem;border:1px solid var(--border);">
                <form action="{{ route('product_service.categories.update', $category->id) }}" method="POST">
                    @csrf @method('PUT')

                    <div style="display:flex;flex-direction:column;gap:1.1rem;">
                        <x-ui.input name="name" label="Category Name" value="{{ old('name', $category->name) }}" required />
                        <div>
                            <label style="font-size:.85rem;font-weight:600;color:var(--headings);display:block;margin-bottom:.4rem;">Description</label>
                            <textarea name="description" rows="4" style="width:100%;padding:.7rem 1rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:8px;color:var(--text-primary);font-size:.95rem;font-family:inherit;outline:none;">{{ old('description', $category->description) }}</textarea>
                        </div>
                    </div>

                    <div style="margin-top:1.5rem;display:flex;justify-content:flex-end;gap:1rem;border-top:1px solid var(--border);padding-top:1.5rem;">
                        <a href="{{ route('product_service.categories.show', $category->id) }}" style="background:transparent;border:1px solid var(--border);color:var(--text-primary);padding:.75rem 1.5rem;border-radius:8px;font-weight:600;text-decoration:none;">Cancel</a>
                        <button type="submit" style="background:var(--primary);border:none;color:white;padding:.75rem 1.5rem;border-radius:8px;font-weight:600;cursor:pointer;">
                            <i class="fa-solid fa-floppy-disk"></i> Update Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </x-ui.grid>
</x-layouts.app>
