<x-layouts.app title="Store Pages - {{ $store->store_name }}">
    <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 1000px; margin: 0 auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <a href="{{ route('ecommerce.stores.index') }}" style="color: var(--text-secondary); text-decoration: none; font-size: 1.25rem;">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h1 style="color: var(--headings); margin: 0; font-weight: 800;">{{ $store->store_name }} Pages</h1>
                    <p style="color: var(--text-secondary); margin: 0;">Manage the pages of your online store.</p>
                </div>
            </div>
            <a href="{{ route('ecommerce.pages.create', $store->id) }}" class="flowexa-btn-primary" style="background: var(--primary); color: white; padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fa-solid fa-plus"></i> Add Page
            </a>
        </div>

        <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
            @foreach($pages as $page)
                <div class="flowexa-card" style="background: var(--surface); border-radius: 20px; border: 1px solid var(--border); overflow: hidden; display: flex; flex-direction: column; transition: transform 0.2s, box-shadow 0.2s; position: relative;">
                    <div style="height: 160px; background: var(--surface-secondary); display: flex; align-items: center; justify-content: center; position: relative; border-bottom: 1px solid var(--border);">
                        @if($page->page_type === 'home')
                            <i class="fa-solid fa-house-chimney" style="font-size: 3rem; color: var(--primary); opacity: 0.2;"></i>
                        @elseif($page->page_type === 'collection')
                            <i class="fa-solid fa-layer-group" style="font-size: 3rem; color: var(--primary); opacity: 0.2;"></i>
                        @elseif($page->page_type === 'product')
                            <i class="fa-solid fa-tag" style="font-size: 3rem; color: var(--primary); opacity: 0.2;"></i>
                        @else
                            <i class="fa-solid fa-file" style="font-size: 3rem; color: var(--primary); opacity: 0.2;"></i>
                        @endif

                        <div style="position: absolute; top: 12px; right: 12px;">
                            <span style="display: flex; align-items: center; gap: 0.4rem; background: {{ $page->is_published ? 'rgba(34, 197, 94, 0.1)' : 'rgba(100, 116, 139, 0.1)' }}; color: {{ $page->is_published ? '#22c55e' : '#64748b' }}; padding: 0.25rem 0.6rem; border-radius: 20px; font-size: 0.7rem; font-weight: 700;">
                                <i class="fa-solid fa-circle" style="font-size: 0.4rem;"></i>
                                {{ $page->is_published ? 'Published' : 'Draft' }}
                            </span>
                        </div>
                    </div>

                    <div style="padding: 1.5rem; flex: 1; display: flex; flex-direction: column;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                            <h3 style="margin: 0; color: var(--headings); font-weight: 800; font-size: 1.1rem;">{{ $page->page_name }}</h3>
                            <span style="background: var(--surface-secondary); color: var(--text-secondary); padding: 0.2rem 0.5rem; border-radius: 6px; font-size: 0.65rem; font-weight: 700; text-transform: uppercase;">{{ $page->page_type }}</span>
                        </div>
                        <p style="color: var(--text-secondary); font-size: 0.85rem; font-family: monospace; margin-bottom: 1.5rem;">/{{ $page->slug }}</p>

                        <div style="margin-top: auto; display: flex; align-items: center; justify-content: space-between; gap: 0.5rem;">
                            <div style="font-size: 0.75rem; color: var(--text-muted);">
                                Updated {{ $page->updated_at->diffForHumans() }}
                            </div>
                            <div style="display: flex; gap: 0.4rem;">
                                <a href="{{ route('ecommerce.pages.show', [$store->id, $page->id]) }}" target="_blank" title="View Page" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 10px; background: var(--surface-secondary); color: var(--text-primary); border: 1px solid var(--border); text-decoration: none; transition: all 0.2s;">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('ecommerce.pages.builder', [$store->id, $page->id]) }}" title="Open Builder" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 10px; background: var(--primary); color: white; text-decoration: none; transition: all 0.2s;">
                                    <i class="fa-solid fa-pen-ruler"></i>
                                </a>
                                <a href="{{ route('ecommerce.pages.edit', [$store->id, $page->id]) }}" title="Page Settings" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 10px; background: var(--surface-secondary); color: var(--text-primary); border: 1px solid var(--border); text-decoration: none; transition: all 0.2s;">
                                    <i class="fa-solid fa-gear"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            @if($pages->isEmpty())
                <div style="grid-column: 1 / -1; padding: 4rem 2rem; text-align: center; background: var(--surface); border: 2px dashed var(--border); border-radius: 24px;">
                    <i class="fa-solid fa-file-circle-plus" style="font-size: 3rem; color: var(--border); margin-bottom: 1rem;"></i>
                    <p style="color: var(--text-secondary); margin: 0; font-weight: 600;">No pages created for this store yet.</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
