<x-layouts.shop>
    <x-slot:title>Track Order #{{ substr($order->id, 0, 8) }}</x-slot:title>

    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop py-stack-lg">
        <div class="max-w-3xl mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-6">
                <div>
                    <h1 class="font-display-lg text-headline-md font-bold text-primary uppercase tracking-tighter">ORDER TRACKING</h1>
                    <p class="text-on-surface-variant font-medium">Order #{{ substr($order->order_number, 4) }} • {{ $order->created_at->format('M d, Y') }}</p>
                </div>
                <div class="bg-primary/10 border border-primary/20 px-6 py-2 rounded-full">
                    <span class="text-primary font-black uppercase tracking-widest text-xs">{{ str_replace('_', ' ', $order->fulfillment_status ?? $order->status) }}</span>
                </div>
            </div>

            <!-- Tracking Timeline -->
            <div class="bg-surface-container-lowest border border-surface-container rounded-3xl p-8 mb-8 shadow-xl">
                <div class="space-y-12">
                    @php
                        if (($order->delivery_type ?? 'pickup') === 'pickup') {
                            $statuses = [
                                'paid' => ['label' => 'Order Confirmed', 'icon' => 'check_circle', 'desc' => 'We\'ve received your payment.'],
                                'ready_for_pickup' => ['label' => 'Ready for Pickup', 'icon' => 'inventory_2', 'desc' => 'Your items are packed and waiting.'],
                                'customer_arrived' => ['label' => 'Arrived at Shop', 'icon' => 'location_on', 'desc' => 'You have reached the pickup location.'],
                                'collected' => ['label' => 'Collected', 'icon' => 'task_alt', 'desc' => 'Order successfully picked up.'],
                            ];
                        } else {
                            $statuses = [
                                'paid' => ['label' => 'Order Confirmed', 'icon' => 'check_circle', 'desc' => 'We\'ve received your payment.'],
                                'searching_courier' => ['label' => 'Searching Courier', 'icon' => 'search', 'desc' => 'Finding the nearest delivery partner.'],
                                'courier_assigned' => ['label' => 'Courier Assigned', 'icon' => 'person', 'desc' => 'A courier is on the way to pick up.'],
                                'picked_up' => ['label' => 'Picked Up', 'icon' => 'local_shipping', 'desc' => 'Your order is on the way!'],
                                'delivered' => ['label' => 'Delivered', 'icon' => 'task_alt', 'desc' => 'Order successfully received.'],
                            ];
                        }

                        $currentStatus = $order->fulfillment_status ?? $order->status;
                        $reachedCurrent = false;
                    @endphp

                    @foreach($statuses as $key => $info)
                        <div class="flex gap-6 relative">
                            @if(!$loop->last)
                                <div class="absolute left-[17px] top-10 bottom-[-40px] w-0.5 {{ $reachedCurrent ? 'bg-outline' : 'bg-primary' }}"></div>
                            @endif

                            <div class="w-9 h-9 rounded-full flex items-center justify-center shrink-0 z-10 {{ $reachedCurrent ? 'bg-surface-container text-on-surface-variant border border-outline' : 'bg-primary text-on-primary shadow-lg shadow-primary/30' }}">
                                <span class="material-symbols-outlined text-xl">{{ $info['icon'] }}</span>
                            </div>

                            <div>
                                <h4 class="font-bold text-on-surface {{ $reachedCurrent ? 'opacity-50' : '' }}">{{ $info['label'] }}</h4>
                                <p class="text-sm text-on-surface-variant {{ $reachedCurrent ? 'opacity-50' : '' }}">{{ $info['desc'] }}</p>
                            </div>
                        </div>
                        @if($key === $currentStatus)
                            @php $reachedCurrent = true; @endphp
                        @endif
                    @endforeach
                </div>
            </div>

            @if($order->delivery_type === 'pickup' && $order->fulfillment_status !== 'collected')
                <div id="arrival-section" class="mb-8">
                    @if($order->fulfillment_status === 'customer_arrived')
                         <button onclick="confirmCollection()" class="w-full flex items-center justify-center gap-2 py-4 bg-primary text-on-primary rounded-2xl font-black uppercase tracking-widest hover:scale-[1.01] transition-all shadow-xl shadow-primary/20">
                            <span class="material-symbols-outlined">done_all</span>
                            I HAVE COLLECTED MY ORDER
                        </button>
                    @else
                        <button id="btn-arrived" onclick="detectArrival()" class="w-full flex items-center justify-center gap-2 py-4 bg-on-surface text-surface rounded-2xl font-black uppercase tracking-widest hover:bg-primary hover:text-on-primary transition-all shadow-xl">
                            <span class="material-symbols-outlined">location_on</span>
                            I HAVE ARRIVED AT THE SHOP
                        </button>
                    @endif
                </div>
            @endif

            <!-- Fulfillment Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-surface-container-lowest border border-surface-container rounded-3xl p-8">
                    <h3 class="font-bold text-xs uppercase tracking-widest text-on-surface-variant mb-6">
                        {{ $order->delivery_type === 'pickup' ? 'Shop Contact' : 'Courier Info' }}
                    </h3>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-surface-container rounded-full flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined text-2xl">{{ $order->delivery_type === 'pickup' ? 'store' : 'electric_moped' }}</span>
                        </div>
                        <div>
                            <p class="font-bold text-on-surface">{{ $order->delivery_type === 'pickup' ? $tenant->name : ($delivery->courier_name ?? 'Assigning soon...') }}</p>
                            <p class="text-sm text-on-surface-variant">{{ $order->delivery_type === 'pickup' ? 'Order ready for collection' : ($delivery->courier_phone ?? 'Provider: ' . ucfirst($delivery->provider ?? '')) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-surface-container-lowest border border-surface-container rounded-3xl p-8">
                    <h3 class="font-bold text-xs uppercase tracking-widest text-on-surface-variant mb-6">
                         {{ $order->delivery_type === 'pickup' ? 'Pickup Location' : 'Delivery Address' }}
                    </h3>
                    <div class="flex gap-3">
                        <span class="material-symbols-outlined text-primary">location_on</span>
                        <p class="text-sm text-on-surface font-medium leading-relaxed">
                            {{ $order->delivery_type === 'pickup' ? ($tenant->address . ', ' . $tenant->city) : ($order->shipping_address['address'] ?? '') }}
                        </p>
                    </div>
                </div>
            </div>

            @if($order->delivery_type === 'delivery' && isset($delivery->tracking_url))
                <div class="mt-8">
                    <a href="{{ $delivery->tracking_url }}" target="_blank" class="w-full flex items-center justify-center gap-2 py-4 bg-on-surface text-surface rounded-2xl font-black uppercase tracking-widest hover:bg-primary hover:text-on-primary transition-all shadow-xl">
                        <span class="material-symbols-outlined">map</span>
                        Live Map Tracking
                    </a>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        const shopLat = {{ $tenant->latitude ?? 0 }};
        const shopLng = {{ $tenant->longitude ?? 0 }};
        const arrivalRadius = 0.5; // km

        function getDistance(lat1, lon1, lat2, lon2) {
            const R = 6371;
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                      Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                      Math.sin(dLon / 2) * Math.sin(dLon / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            return R * c;
        }

        async function detectArrival() {
            if (!navigator.geolocation) {
                alert("Geolocation is not supported by your browser.");
                return;
            }

            const btn = document.getElementById('btn-arrived');
            btn.disabled = true;
            btn.innerText = "Checking location...";

            navigator.geolocation.getCurrentPosition(async position => {
                const distance = getDistance(
                    position.coords.latitude,
                    position.coords.longitude,
                    shopLat,
                    shopLng
                );

                if (distance <= arrivalRadius) {
                    // Confirm arrival
                    try {
                        const response = await fetch("{{ route('home.order.arrived', ['tenant' => $tenant, 'order' => $order->id]) }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                        if (response.ok) {
                            location.reload();
                        }
                    } catch (e) {
                        console.error(e);
                        btn.disabled = false;
                        btn.innerText = "I HAVE ARRIVED AT THE SHOP";
                    }
                } else {
                    alert(`You seem to be ${distance.toFixed(2)} km away. Please ensure you are at the shop before confirming arrival.`);
                    btn.disabled = false;
                    btn.innerText = "I HAVE ARRIVED AT THE SHOP";
                }
            }, error => {
                alert("Could not get your location. Please enable location services.");
                btn.disabled = false;
                btn.innerText = "I HAVE ARRIVED AT THE SHOP";
            });
        }

        async function confirmCollection() {
             try {
                const response = await fetch("{{ route('home.order.collected', ['tenant' => $tenant, 'order' => $order->id]) }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                if (response.ok) {
                    location.reload();
                }
            } catch (e) {
                console.error(e);
            }
        }
    </script>
    @endpush
        </div>
    </div>
</x-layouts.shop>
