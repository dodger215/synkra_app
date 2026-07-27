<x-layouts.shop>
    <x-slot:title>Your Shopping Cart</x-slot:title>

    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop py-stack-lg">
        <h1 class="font-display-lg text-headline-md font-bold text-primary mb-12 uppercase tracking-tighter">YOUR SHOPPING CART</h1>

        @if(!$cart || empty($cart->items))
            <div class="text-center py-20 bg-surface-container-low rounded-3xl">
                <span class="material-symbols-outlined text-6xl text-on-surface-variant mb-4">shopping_basket</span>
                <p class="text-xl text-on-surface-variant mb-8">Your cart is currently empty.</p>
                <a href="{{ route('home.index') }}" class="bg-primary text-on-primary px-10 py-4 rounded-xl font-bold hover:opacity-90 transition-all">
                    START SHOPPING
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Items List -->
                <div class="lg:col-span-2 space-y-6">
                    @foreach($cart->items as $index => $item)
                        <div class="flex flex-col md:flex-row gap-6 p-6 bg-surface-container-lowest rounded-2xl border border-surface-container hover:shadow-md transition-shadow">
                            <div class="w-full md:w-32 aspect-[3/4] bg-surface-container rounded-xl overflow-hidden shrink-0">
                                <img src="{{ $item['image'] ?? '' }}" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 flex flex-col justify-between">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="font-bold text-lg mb-1 uppercase">{{ $item['name'] }}</h3>
                                        <p class="text-sm text-on-surface-variant">Vendor: {{ $cart->tenant->name ?? 'Marketplace' }}</p>
                                    </div>
                                    <p class="font-bold text-lg">{{ number_format($item['price'], 2) }}</p>
                                </div>
                                <div class="flex justify-between items-end mt-4">
                                    <div class="flex items-center border border-outline rounded-lg bg-surface-container-low">
                                        <button onclick="updateQty('{{ $item['product_id'] }}', {{ $item['quantity'] - 1 }})" class="p-2 hover:text-primary transition-colors" {{ $item['quantity'] <= 1 ? 'disabled' : '' }}>
                                            <span class="material-symbols-outlined text-sm">remove</span>
                                        </button>
                                        <span class="px-4 font-bold">{{ $item['quantity'] }}</span>
                                        <button onclick="updateQty('{{ $item['product_id'] }}', {{ $item['quantity'] + 1 }})" class="p-2 hover:text-primary transition-colors">
                                            <span class="material-symbols-outlined text-sm">add</span>
                                        </button>
                                    </div>
                                    <button onclick="removeItem('{{ $item['product_id'] }}')" class="text-error text-sm font-bold flex items-center hover:underline">
                                        <span class="material-symbols-outlined text-sm mr-1">delete</span> REMOVE
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-surface-container-lowest p-8 rounded-2xl border border-surface-container sticky top-40">
                        <h2 class="font-bold text-xs uppercase tracking-widest text-on-surface-variant mb-6 border-b border-surface-container pb-4">ORDER SUMMARY</h2>
                        <div class="space-y-4 mb-8">
                            <div class="flex justify-between">
                                <span class="text-on-surface-variant">Subtotal</span>
                                <span class="font-bold text-on-surface">{{ number_format($cart->total_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-on-surface-variant">Estimated Shipping</span>
                                <span class="font-bold text-secondary">FREE</span>
                            </div>
                            <div class="flex justify-between text-xl font-bold pt-6 border-t border-surface-container">
                                <span class="text-on-surface">TOTAL</span>
                                <span class="text-primary">{{ number_format($cart->total_amount, 2) }}</span>
                            </div>
                        </div>

                        <a href="{{ route('home.checkout.show', $cart->tenant) }}" class="w-full block text-center py-4 bg-primary text-on-primary rounded-xl font-bold hover:opacity-90 active:scale-95 transition-all shadow-lg shadow-primary/20">
                            PROCEED TO CHECKOUT
                        </a>

                        <div class="mt-8 pt-8 border-t border-surface-container space-y-4">
                            <div class="flex items-center gap-3 text-xs text-on-surface-variant">
                                <span class="material-symbols-outlined text-secondary">verified_user</span>
                                Secure checkout powered by Paystack
                            </div>
                            <div class="flex items-center gap-3 text-xs text-on-surface-variant">
                                <span class="material-symbols-outlined text-secondary">local_shipping</span>
                                Carbon-neutral global delivery
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
    <script>
        async function updateQty(productId, newQty) {
            if (newQty < 1) return;

            try {
                const response = await fetch("{{ route('home.cart.update') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        tenant_id: "{{ $cart->tenant_id ?? '' }}",
                        quantity: newQty
                    })
                });
                const data = await response.json();
                if (data.success) {
                    window.location.reload();
                }
            } catch (error) {
                console.error('Error updating quantity:', error);
            }
        }

        async function removeItem(productId) {
            if (!confirm('Remove this item from your cart?')) return;

            try {
                const response = await fetch("{{ route('home.cart.remove') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        tenant_id: "{{ $cart->tenant_id ?? '' }}"
                    })
                });
                const data = await response.json();
                if (data.success) {
                    window.location.reload();
                }
            } catch (error) {
                console.error('Error removing item:', error);
            }
        }
    </script>
    @endpush
</x-layouts.shop>
