<x-layouts.app title="Ecommerce Stores">
    <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 1200px; margin: 0 auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h1 style="color: var(--headings); margin: 0 0 0.5rem 0; font-weight: 800;">Ecommerce Stores</h1>
                <p style="color: var(--text-secondary); margin: 0;">Manage your online presence and sales channels.</p>
            </div>
            <a href="{{ route('ecommerce.stores.create') }}" class="flowexa-btn-primary" style="background: var(--primary); color: white; padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fa-solid fa-plus"></i> Create Store
            </a>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
            @foreach($stores as $store)
                <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); padding: 1.5rem; display: flex; flex-direction: column; transition: all 0.2s ease; position: relative;">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                        <div style="width: 48px; height: 48px; background: {{ $store->primary_color }}20; color: {{ $store->primary_color }}; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <i class="fa-solid fa-store" style="font-size: 1.25rem;"></i>
                        </div>
                        <div>
                            <h3 style="color: var(--headings); margin: 0; font-weight: 700; font-size: 1.1rem;">{{ $store->store_name }}</h3>
                            <span style="font-size: 0.8rem; color: var(--text-secondary);">{{ $store->domain ?? 'No domain set' }}</span>
                        </div>
                    </div>

                    <div style="margin-bottom: 1.5rem; display: flex; gap: 0.5rem;">
                        <span style="background: {{ $store->is_published ? 'rgba(34, 197, 94, 0.1)' : 'rgba(148, 163, 184, 0.1)' }}; color: {{ $store->is_published ? '#22c55e' : '#64748b' }}; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 700;">
                            {{ $store->is_published ? 'Published' : 'Draft' }}
                        </span>
                        <span style="background: var(--surface-secondary); color: var(--text-secondary); padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 700;">
                            {{ $store->currency }}
                        </span>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.5rem; margin-top: auto;">
                        <a href="{{ route('ecommerce.stores.show', $store->id) }}" class="flowexa-btn" title="Dashboard" style="background: var(--surface-secondary); border: 1px solid var(--border); color: var(--text-primary); padding: 0.6rem; border-radius: 10px; font-weight: 600; text-decoration: none; text-align: center; font-size: 0.85rem;">
                            <i class="fa-solid fa-chart-line"></i>
                        </a>
                        <a href="{{ route('ecommerce.pages.index', $store->id) }}" class="flowexa-btn" title="Pages" style="background: var(--surface-secondary); border: 1px solid var(--border); color: var(--text-primary); padding: 0.6rem; border-radius: 10px; font-weight: 600; text-decoration: none; text-align: center; font-size: 0.85rem;">
                            <i class="fa-solid fa-file-lines"></i>
                        </a>
                        <a href="{{ route('ecommerce.stores.edit', $store->id) }}" class="flowexa-btn" title="Settings" style="background: var(--surface-secondary); border: 1px solid var(--border); color: var(--text-primary); padding: 0.6rem; border-radius: 10px; font-weight: 600; text-decoration: none; text-align: center; font-size: 0.85rem;">
                            <i class="fa-solid fa-gear"></i>
                        </a>
                    </div>
                </div>
            @endforeach

            @if($stores->isEmpty())
                <div style="grid-column: 1 / -1; text-align: center; padding: 4rem 2rem; background: var(--surface); border-radius: 24px; border: 2px dashed var(--border);">
                    <div style="width: 64px; height: 64px; background: rgba(249, 115, 22, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--primary); margin: 0 auto 1.5rem auto;">
                        <i class="fa-solid fa-store" style="font-size: 1.5rem;"></i>
                    </div>
                    <h3 style="color: var(--headings); margin-bottom: 0.5rem; font-weight: 700;">No stores found</h3>
                    <p style="color: var(--text-secondary); max-width: 400px; margin: 0 auto 1.5rem auto;">Create your first ecommerce store to start selling your products online.</p>
                    <a href="{{ route('ecommerce.stores.create') }}" class="flowexa-btn-primary" style="background: var(--primary); color: white; padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
                        <i class="fa-solid fa-plus"></i> Get Started
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
