<x-layouts.app title="Product Categories">
    <x-ui.grid>
        <div class="synkra-dashboard-container" style="padding:2rem;max-width:1200px;margin:0 auto;">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:2rem;">
                <div>
                    <h1 style="color:var(--headings);margin:0 0 .5rem 0;">Product Categories</h1>
                    <p style="color:var(--text-secondary);margin:0;">Organise your product catalog into categories for filtering and reporting.</p>
                </div>
                <button type="button" onclick="openSynkraModal('addCategoryModal')"
                        class="synkra-btn synkra-btn-primary"
                        style="background:var(--primary);border:none;color:white;cursor:pointer;padding:.6rem 1.25rem;border-radius:8px;font-weight:600;display:flex;align-items:center;gap:8px;">
                    <i class="fa-solid fa-plus"></i> Add Category
                </button>
            </div>

            @if(session('success'))
                <x-ui.alert type="success" title="Success" :message="session('success')" style="margin-bottom:2rem;" />
            @endif
            @if($errors->any())
                <x-ui.alert type="danger" title="Error" :message="$errors->first()" style="margin-bottom:2rem;" />
            @endif

            <div class="synkra-card" style="background:var(--surface);border-radius:16px;border:1px solid var(--border);overflow:hidden;">
                @if($categories->isEmpty())
                    <div style="text-align:center;padding:4rem 2rem;">
                        <div style="width:64px;height:64px;background:var(--surface-secondary);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem auto;font-size:1.5rem;color:var(--text-secondary);">
                            <i class="fa-solid fa-tags"></i>
                        </div>
                        <h3 style="margin:0 0 .5rem 0;color:var(--text-primary);">No Categories Yet</h3>
                        <p style="color:var(--text-secondary);margin:0 0 1.5rem 0;font-size:.95rem;">Create your first category to start organising products.</p>
                        <button type="button" onclick="openSynkraModal('addCategoryModal')"
                                style="background:var(--primary);border:none;color:white;cursor:pointer;padding:.6rem 1.5rem;border-radius:8px;font-weight:600;">
                            Add First Category
                        </button>
                    </div>
                @else
                    @php
                        $headers = ['Name', 'Description', 'Products', 'Actions'];
                        $rows = $categories->map(function ($category) {
                            $actions = new \Illuminate\Support\HtmlString(
                                '<div class="synkra-table-actions" style="padding-right: 3px;">'
                                . '<a href="' . e(route('product_service.categories.edit', $category->id)) . '" class="synkra-table-action-btn" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>'
                                . '<a href="' . e(route('product_service.categories.show', $category->id)) . '" class="synkra-table-action-btn" title="View"><i class="fa-solid fa-eye"></i></a>'
                                . '<form action="' . e(route('product_service.categories.destroy', $category->id)) . '" method="POST" onsubmit="return confirm(\'Delete this category?\');" style="margin:0;display:inline;">'
                                . csrf_field() . method_field('DELETE')
                                . '<button type="submit" class="synkra-table-action-btn" style="color:var(--danger);" title="Delete"><i class="fa-solid fa-trash"></i></button>'
                                . '</form></div>'
                            );

                            return [
                                new \Illuminate\Support\HtmlString('<a href="' . e(route('product_service.categories.show', $category->id)) . '" style="color:var(--headings);font-weight:600;text-decoration:none;">' . e($category->name) . '</a>'),
                                $category->description ? \Illuminate\Support\Str::limit($category->description, 60) : '—',
                                (string) $category->products_count,
                                $actions,
                            ];
                        })->all();
                    @endphp
                    <x-ui.table :headers="$headers" :rows="$rows" />
                @endif
            </div>
        </div>

        <style>#addCategoryModal-trigger-btn { display:none !important; }</style>
        <x-ui.modal id="addCategoryModal" triggerId="addCategoryModal-trigger-btn" title="Add Category">
            <form id="addCategoryForm" action="{{ route('product_service.categories.store') }}" method="POST">
                @csrf
                <div style="display:flex;flex-direction:column;gap:1rem;">
                    <x-ui.input name="name" label="Category Name" placeholder="e.g. Electronics" required />
                    <div>
                        <label style="font-size:.85rem;font-weight:500;color:var(--text-primary);display:block;margin-bottom:.4rem;">Description</label>
                        <textarea name="description" rows="3" style="width:100%;padding:.75rem 1rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:8px;color:var(--text-primary);font-size:.95rem;font-family:inherit;outline:none;" placeholder="Optional description"></textarea>
                    </div>
                </div>
            </form>
            <x-slot:footer>
                <button type="button" style="background:transparent;border:1px solid var(--border);color:var(--text-secondary);cursor:pointer;padding:.5rem 1rem;border-radius:8px;" onclick="closeSynkraModal('addCategoryModal')">Cancel</button>
                <button type="button" style="background:var(--primary);border:none;color:white;cursor:pointer;padding:.5rem 1rem;border-radius:8px;font-weight:600;" onclick="document.getElementById('addCategoryForm').submit()">Save Category</button>
            </x-slot:footer>
        </x-ui.modal>
    </x-ui.grid>
</x-layouts.app>
