<x-layouts.shop>
    <x-slot:title>Saved Items</x-slot:title>

    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop py-stack-lg">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-6">
            <div>
                <h1 class="font-display-lg text-headline-md font-bold text-primary uppercase tracking-tighter">SAVED ITEMS</h1>
                <p class="text-on-surface-variant">Products you've liked and saved for later.</p>
            </div>
            <form action="{{ route('home.customer.logout') }}" method="POST">
                @csrf
                <button type="submit" class="px-6 py-2 border border-outline rounded-lg font-bold text-sm hover:bg-surface-container transition-colors">
                    LOGOUT
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-gutter">
            <!-- Sidebar -->
            <aside class="md:col-span-1">
                <nav class="flex flex-col gap-2">
                    <a href="{{ route('home.customer.dashboard') }}" class="px-4 py-3 {{ request()->routeIs('home.customer.dashboard') ? 'bg-primary text-on-primary' : 'text-on-surface-variant hover:bg-surface-container' }} rounded-xl font-bold text-sm transition-colors">DASHBOARD</a>
                    <a href="{{ route('home.customer.orders') }}" class="px-4 py-3 {{ request()->routeIs('home.customer.orders') ? 'bg-primary text-on-primary' : 'text-on-surface-variant hover:bg-surface-container' }} rounded-xl font-bold text-sm transition-colors">ORDER HISTORY</a>
                    <a href="{{ route('home.customer.saved_items') }}" class="px-4 py-3 {{ request()->routeIs('home.customer.saved_items') ? 'bg-primary text-on-primary' : 'text-on-surface-variant hover:bg-surface-container' }} rounded-xl font-bold text-sm transition-colors">SAVED ITEMS</a>
                    <a href="{{ route('home.customer.settings') }}" class="px-4 py-3 {{ request()->routeIs('home.customer.settings') ? 'bg-primary text-on-primary' : 'text-on-surface-variant hover:bg-surface-container' }} rounded-xl font-bold text-sm transition-colors">ACCOUNT SETTINGS</a>
                </nav>
            </aside>

            <!-- Main Content -->
            <div class="md:col-span-3">
                @if($savedItems->count() > 0)
                    <div class="grid grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($savedItems as $saved)
                            @php $product = $saved->product; @endphp
                            <div class="group relative bg-surface-container-lowest border border-surface-container rounded-2xl overflow-hidden">
                                <a href="{{ route('home.product.details', ['tenant' => $product->tenant, 'product' => $product]) }}" class="block aspect-[3/4] overflow-hidden">
                                    <img src="{{ $product->imageUrl() ?? 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=1999&auto=format&fit=crop' }}"
                                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                         alt="{{ $product->name }}">
                                </a>
                                <div class="p-4">
                                    <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest mb-1">{{ $product->category?->name }}</p>
                                    <h3 class="font-bold text-sm text-on-surface truncate mb-1">{{ $product->name }}</h3>
                                    <p class="font-bold text-primary">{{ number_format($product->unit_price, 2) }}</p>

                                    <div class="mt-4 flex gap-2">
                                        <button class="flex-1 py-2 bg-primary text-on-primary text-[10px] font-bold rounded uppercase add-to-cart" data-id="{{ $product->id }}" data-tenant="{{ $product->tenant_id }}">Add to Cart</button>
                                        <button onclick="toggleLike(event, '{{ $product->id }}')" class="px-3 py-2 border border-outline rounded text-error hover:bg-error/5 transition-colors">
                                            <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">favorite</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-8">
                        {{ $savedItems->links() }}
                    </div>
                @else
                    <div class="bg-surface-container-lowest border border-surface-container rounded-2xl p-12 text-center">
                        <span class="material-symbols-outlined text-4xl text-outline-variant mb-2">favorite</span>
                        <p class="text-sm text-on-surface-variant">You haven't saved any items yet.</p>
                        <a href="{{ route('home.index') }}" class="inline-block mt-4 text-primary font-bold text-sm hover:underline">Explore Products</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        async function toggleLike(event, productId) {
            event.preventDefault();
            try {
                const response = await fetch(`/customer/product/${productId}/like`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();
                if (data.status === 'unliked') {
                    window.location.reload(); // Refresh to remove the item from the list
                }
            } catch (error) {
                console.error('Error toggling like:', error);
            }
        }

        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', async (e) => {
                const productId = button.dataset.id;
                const tenantId = button.dataset.tenant;

                try {
                    const response = await fetch("{{ route('home.cart.add') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            tenant_id: tenantId,
                            quantity: 1
                        })
                    });
                    const data = await response.json();
                    if (data.success) {
                        alert('Product added to cart!');
                    }
                } catch (error) {
                    console.error('Error adding to cart:', error);
                }
            });
        });
    </script>
    @endpush
</x-layouts.shop>
