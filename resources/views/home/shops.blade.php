<x-layouts.shop>
    <x-slot:title>Explore All Shops | Flowexa Marketplace</x-slot:title>
    <!-- Hero Header -->
    <section class="bg-surface py-24 px-margin-mobile md:px-margin-desktop">
        <div class="max-w-container-max mx-auto text-center space-y-4">
            <h1 class="text-4xl md:text-6xl font-bold text-on-surface uppercase tracking-tighter">Verified Vendors</h1>
            <p class="text-on-surface-variant text-lg max-w-2xl mx-auto">Discover high-quality brands delivering exceptional products directly to you.</p>
        </div>
    </section>

    <!-- Shops Grid -->
    <section class="py-stack-lg px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto bg-surface border-t border-outline min-h-screen">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($tenants as $tenant)
                <div class="group relative overflow-hidden rounded-[2.5rem] bg-surface-container border border-outline hover:border-primary transition-all duration-500 hover:shadow-2xl shadow-primary/5">
                    <div class="h-64 bg-cover bg-center group-hover:scale-105 transition-transform duration-700" style="background-image: url('{{ $tenant->settings['banner_url'] ?? 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?q=80&w=2070&auto=format&fit=crop' }}')"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-surface via-surface/40 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 w-full p-10 space-y-4">
                        <div class="w-20 h-20 rounded-2xl border-2 border-on-surface/20 overflow-hidden shadow-2xl bg-surface transform -rotate-3 group-hover:rotate-0 transition-transform duration-500">
                            <img src="{{ $tenant->settings['logo_url'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($tenant->name) }}" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h3 class="font-bold text-2xl text-on-surface uppercase tracking-tighter">{{ $tenant->name }}</h3>
                            <p class="text-on-surface-variant text-sm line-clamp-2 max-w-xs">{{ $tenant->settings['description'] ?? 'Exclusive collections.' }}</p>
                        </div>
                        <a href="{{ route('home.shop', $tenant) }}" class="inline-flex items-center gap-2 bg-on-surface text-surface px-6 py-3 rounded-xl font-bold hover:bg-primary hover:text-on-primary transition-all transform hover:translate-x-2">
                            Explore Store <span class="material-symbols-outlined text-sm">arrow_forward</span>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center">
                    <p class="text-on-surface-variant">No shops available at the moment.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-16">
            {{ $tenants->links() }}
        </div>
    </section>

</x-layouts.shop>
