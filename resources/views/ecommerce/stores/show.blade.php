<x-layouts.app title="{{ $store->store_name }} - Dashboard">
    <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 1200px; margin: 0 auto;">
        {{-- Store Header --}}
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem;">
            <div style="display: flex; align-items: center; gap: 1.5rem;">
                <div style="width: 64px; height: 64px; background: {{ $store->primary_color }}20; color: {{ $store->primary_color }}; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                    <i class="fa-solid fa-store"></i>
                </div>
                <div>
                    <h1 style="color: var(--headings); margin: 0; font-weight: 800; font-size: 1.75rem;">{{ $store->store_name }}</h1>
                    <div style="display: flex; align-items: center; gap: 1rem; margin-top: 0.25rem;">
                        <span style="color: var(--text-secondary); font-size: 0.9rem; font-weight: 500;">
                            <i class="fa-solid fa-globe"></i> {{ $store->domain ? $store->domain . '.flowexa.com' : 'No domain set' }}
                        </span>
                        <span style="background: {{ $store->is_published ? 'rgba(34, 197, 94, 0.1)' : 'rgba(148, 163, 184, 0.1)' }}; color: {{ $store->is_published ? '#22c55e' : '#64748b' }}; padding: 0.2rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 700;">
                            {{ $store->is_published ? 'Live' : 'Draft' }}
                        </span>
                    </div>
                </div>
            </div>
            <div style="display: flex; gap: 0.75rem;">
                <a href="{{ route('ecommerce.stores.edit', $store->id) }}" class="flowexa-btn" style="background: var(--surface); border: 1px solid var(--border); color: var(--text-primary); padding: 0.75rem 1.25rem; border-radius: 12px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fa-solid fa-gear"></i> Settings
                </a>
                @php
                    $homePage = $store->pages()->where('page_type', 'home')->first();
                @endphp
                <a href="{{ $homePage ? route('ecommerce.pages.show', [$store->id, $homePage->id]) : '#' }}" target="_blank" class="flowexa-btn-primary" style="background: var(--primary); color: white; padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 0.5rem; {{ !$homePage ? 'pointer-events:none; opacity:0.5;' : '' }}">
                    <i class="fa-solid fa-eye"></i> View Live Site
                </a>
            </div>
        </div>

        {{-- Stats Grid --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
            <div class="flowexa-card" style="background: var(--surface); border-radius: 20px; border: 1px solid var(--border); padding: 1.5rem;">
                <div style="color: var(--text-secondary); font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Total Sales</div>
                <div style="font-size: 1.75rem; font-weight: 800; color: var(--headings);">{{ $store->currency }} 0.00</div>
                <div style="margin-top: 0.5rem; font-size: 0.8rem; color: #22c55e; font-weight: 600;">
                    <i class="fa-solid fa-arrow-trend-up"></i> 0% from last month
                </div>
            </div>
            <div class="flowexa-card" style="background: var(--surface); border-radius: 20px; border: 1px solid var(--border); padding: 1.5rem;">
                <div style="color: var(--text-secondary); font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Orders</div>
                <div style="font-size: 1.75rem; font-weight: 800; color: var(--headings);">0</div>
                <div style="margin-top: 0.5rem; font-size: 0.8rem; color: var(--text-secondary); font-weight: 600;">
                    No pending orders
                </div>
            </div>
            <div class="flowexa-card" style="background: var(--surface); border-radius: 20px; border: 1px solid var(--border); padding: 1.5rem;">
                <div style="color: var(--text-secondary); font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Pages</div>
                <div style="font-size: 1.75rem; font-weight: 800; color: var(--headings);">{{ $store->pages()->count() }}</div>
                <div style="margin-top: 0.5rem;">
                    <a href="{{ route('ecommerce.pages.index', $store->id) }}" style="font-size: 0.8rem; color: var(--primary); font-weight: 700; text-decoration: none;">Manage Pages <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>
        </div>

        {{-- Quick Actions & Recent Activity --}}
        <div style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 2rem;">
            {{-- Quick Actions --}}
            <div>
                <h3 style="color: var(--headings); font-weight: 800; margin-bottom: 1.25rem;">Quick Actions</h3>
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <a href="{{ route('ecommerce.pages.create', $store->id) }}" style="text-decoration: none;">
                        <div class="action-card">
                            <div class="action-icon" style="background: #eff6ff; color: #3b82f6;"><i class="fa-solid fa-plus"></i></div>
                            <div class="action-info">
                                <div class="action-title">Create New Page</div>
                                <div class="action-desc">Add a new page to your site</div>
                            </div>
                        </div>
                    </a>
                    @php
                        $firstPage = $store->pages()->first();
                    @endphp
                    <a href="{{ $firstPage ? route('ecommerce.pages.builder', [$store->id, $firstPage->id]) : '#' }}" style="text-decoration: none; {{ !$firstPage ? 'pointer-events:none; opacity:0.5;' : '' }}">
                        <div class="action-card">
                            <div class="action-icon" style="background: #f0fdf4; color: #22c55e;"><i class="fa-solid fa-pen-ruler"></i></div>
                            <div class="action-info">
                                <div class="action-title">Open Page Builder</div>
                                <div class="action-desc">Edit your store design</div>
                            </div>
                        </div>
                    </a>
                    <a href="#" style="text-decoration: none;">
                        <div class="action-card">
                            <div class="action-icon" style="background: #fff7ed; color: #f97316;"><i class="fa-solid fa-tags"></i></div>
                            <div class="action-info">
                                <div class="action-title">Assign Products</div>
                                <div class="action-desc">Manage products in this store</div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Recent Pages --}}
            <div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                    <h3 style="color: var(--headings); font-weight: 800; margin: 0;">Recent Pages</h3>
                    <a href="{{ route('ecommerce.pages.index', $store->id) }}" style="color: var(--primary); font-size: 0.85rem; font-weight: 700; text-decoration: none;">View All</a>
                </div>
                <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); overflow: hidden;">
                    @forelse($store->pages()->orderBy('updated_at', 'desc')->take(5)->get() as $page)
                        <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <div style="font-weight: 700; color: var(--headings);">{{ $page->page_name }}</div>
                                <div style="font-size: 0.8rem; color: var(--text-secondary);">/{{ $page->slug }} • Modified {{ $page->updated_at->diffForHumans() }}</div>
                            </div>
                            <a href="{{ route('ecommerce.pages.builder', [$store->id, $page->id]) }}" style="width: 32px; height: 32px; border-radius: 8px; background: var(--surface-secondary); color: var(--text-primary); display: flex; align-items: center; justify-content: center; text-decoration: none; border: 1px solid var(--border);">
                                <i class="fa-solid fa-chevron-right"></i>
                            </a>
                        </div>
                    @empty
                        <div style="padding: 3rem 2rem; text-align: center; color: var(--text-secondary);">
                            <i class="fa-solid fa-file-circle-plus" style="font-size: 2rem; opacity: 0.2; margin-bottom: 1rem; display: block;"></i>
                            No pages created yet.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <style>
        .action-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1.25rem;
            display: flex;
            align-items: center;
            gap: 1.25rem;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .action-card:hover {
            border-color: var(--primary);
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .action-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }
        .action-title {
            font-weight: 700;
            color: var(--headings);
            font-size: 0.95rem;
        }
        .action-desc {
            font-size: 0.8rem;
            color: var(--text-secondary);
        }
    </style>
</x-layouts.app>
