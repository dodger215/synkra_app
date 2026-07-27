<x-layouts.shop>
    <x-slot:title>Checkout | {{ $tenant->name }}</x-slot:title>

    @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/maplibre-gl@4.0.2/dist/maplibre-gl.css" />
    <style>
        #map { height: 400px; width: 100%; border-radius: 12px; }
        .delivery-card.active { border-color: var(--primary); background-color: rgba(249, 115, 22, 0.05); }
        .maplibregl-ctrl-logo, .maplibregl-ctrl-attrib { display: none !important; }
    </style>
    @endpush

    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop py-stack-lg">
        <h1 class="font-display-lg text-headline-md font-bold text-primary mb-8 uppercase">CHECKOUT</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-gutter">
            <!-- Order Summary -->
            <div class="lg:col-span-1 order-last lg:order-first">
                <div class="bg-surface-container-lowest p-6 rounded-xl border border-surface-container sticky top-40">
                    <h2 class="font-bold text-xs uppercase tracking-widest text-on-surface-variant mb-6">ORDER SUMMARY</h2>

                    <div class="space-y-4 mb-6">
                        @foreach($cart->items as $item)
                            <div class="flex justify-between gap-4">
                                <div class="flex gap-3">
                                    <div class="w-12 h-16 bg-surface-container rounded-lg overflow-hidden shrink-0">
                                        <img src="{{ $item['image'] ?? '' }}" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <p class="font-bold text-sm">{{ $item['name'] }}</p>
                                        <p class="text-xs text-on-surface-variant">Qty: {{ $item['quantity'] }}</p>
                                    </div>
                                </div>
                                <p class="font-bold text-sm">{{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-surface-container pt-4 space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-on-surface-variant">Subtotal</span>
                            <span class="font-bold text-on-surface">{{ number_format($cart->total_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-on-surface-variant">Delivery Fee</span>
                            <span class="font-bold text-primary" id="display-delivery-fee">Select delivery</span>
                        </div>
                        <div class="flex justify-between text-lg font-bold pt-4 border-t border-surface-container">
                            <span class="text-on-surface">Total</span>
                            <span class="text-primary" id="display-total-amount">{{ number_format($cart->total_amount, 2) }} {{ $tenant->settings['currency'] ?? 'GHS' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Checkout Form -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Contact Info -->
                <div class="bg-surface-container-lowest p-8 rounded-xl border border-surface-container">
                    <h2 class="font-bold text-xs uppercase tracking-widest text-on-surface-variant mb-6">CONTACT INFORMATION</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-on-surface-variant mb-2 uppercase">Email Address</label>
                            <input type="email" id="customer_email" value="{{ Auth::guard('customer')->user()->email ?? '' }}"
                                class="w-full bg-surface-container border-outline rounded-lg py-3 px-4 focus:ring-2 focus:ring-primary/20 text-on-surface placeholder:text-on-surface-variant/50">
                        </div>
                    </div>
                </div>

                <!-- Fulfillment Method -->
                <div class="bg-surface-container-lowest p-8 rounded-xl border border-surface-container">
                    <h2 class="font-bold text-xs uppercase tracking-widest text-on-surface-variant mb-6">FULFILLMENT METHOD</h2>
                    <div class="grid grid-cols-2 gap-4 mb-8">
                        <button onclick="setFulfillment('pickup')" id="btn-pickup" class="flex flex-col items-center gap-3 p-6 border-2 border-primary bg-primary/5 rounded-2xl transition-all">
                            <span class="material-symbols-outlined text-3xl text-primary">store</span>
                            <span class="font-bold text-sm">Pickup</span>
                        </button>
                        <button onclick="setFulfillment('delivery')" id="btn-delivery" class="flex flex-col items-center gap-3 p-6 border-2 border-outline rounded-2xl transition-all opacity-60">
                            <span class="material-symbols-outlined text-3xl">local_shipping</span>
                            <div class="text-center">
                                <span class="font-bold text-sm block">Delivery</span>
                                <span class="text-[10px] uppercase tracking-tighter text-primary font-black">Coming Soon</span>
                            </div>
                        </button>
                    </div>

                    <!-- Pickup Section -->
                    <div id="section-pickup" class="space-y-6">
                        <div class="bg-surface-container p-6 rounded-2xl border border-outline">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="font-bold text-on-surface">Shop Location</h3>
                                    <p class="text-sm text-on-surface-variant">{{ $tenant->address }}, {{ $tenant->city }}</p>
                                    @if($tenant->landmark)
                                        <p class="text-[10px] text-primary uppercase font-bold mt-1">Landmark: {{ $tenant->landmark }}</p>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-black text-primary" id="pickup-distance">-- km</p>
                                    <p class="text-[10px] text-on-surface-variant uppercase font-bold" id="pickup-time">Calculating time...</p>
                                </div>
                            </div>
                            <div id="map" class="mt-4 shadow-inner"></div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-on-surface-variant mb-2 uppercase">Your Phone Number</label>
                                <input type="text" id="pickup-phone" value="{{ Auth::guard('customer')->user()->phone ?? '' }}"
                                    class="w-full bg-surface-container border-outline rounded-lg py-3 px-4 text-on-surface">
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Section (Disabled) -->
                    <div id="section-delivery" class="hidden space-y-6">
                        <div class="p-12 text-center bg-surface-container rounded-3xl border-2 border-dashed border-outline">
                            <div class="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-6">
                                <span class="material-symbols-outlined text-4xl text-primary">local_shipping</span>
                            </div>
                            <h3 class="font-display-md text-xl font-bold text-on-surface mb-2">Delivery is Coming Soon!</h3>
                            <p class="text-on-surface-variant max-w-sm mx-auto">We're working on integrating Bolt and Yango delivery services. For now, please use our convenient pickup option.</p>
                        </div>
                    </div>
                </div>

                <!-- Payment -->
                <div class="bg-surface-container-lowest p-8 rounded-xl border border-surface-container">
                    <h2 class="font-bold text-xs uppercase tracking-widest text-on-surface-variant mb-6">PAYMENT METHOD</h2>
                    <div class="space-y-4">
                        <label class="flex items-center gap-4 p-4 border border-primary/50 bg-primary/5 rounded-xl cursor-pointer hover:bg-primary/10 transition-colors group">
                            <div class="relative flex items-center justify-center">
                                <input type="radio" checked class="peer appearance-none w-5 h-5 border-2 border-outline rounded-full checked:border-primary transition-all">
                                <div class="absolute w-2.5 h-2.5 rounded-full bg-primary scale-0 peer-checked:scale-100 transition-transform"></div>
                            </div>
                            <div>
                                <p class="font-bold text-sm text-on-surface group-hover:text-primary transition-colors">Pay with Paystack (MoMo, Cards, Bank Transfer)</p>
                                <p class="text-xs text-on-surface-variant">Secure, encrypted payment processing.</p>
                            </div>
                        </label>
                    </div>

                    <button id="pay-button" class="w-full mt-8 py-4 bg-primary text-on-primary rounded-xl font-black uppercase tracking-widest hover:scale-[1.01] active:scale-95 transition-all shadow-xl shadow-primary/20 disabled:opacity-50 disabled:cursor-not-allowed">
                        COMPLETE ORDER
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Location Modal -->
    <div id="location-modal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeLocationModal()"></div>
        <div class="relative w-full max-w-2xl bg-surface border border-outline rounded-3xl overflow-hidden shadow-2xl">
            <div class="p-6 border-b border-outline flex justify-between items-center bg-surface-container">
                <h3 class="font-bold text-lg text-on-surface">Select Delivery Location</h3>
                <button onclick="closeLocationModal()" class="material-symbols-outlined text-on-surface-variant hover:text-white transition-colors">close</button>
            </div>
            <div class="p-4 bg-surface-container-low border-b border-outline">
                <div class="flex gap-2">
                    <input type="text" id="map-search" placeholder="Search for a place..." class="flex-1 bg-surface border-outline rounded-lg py-2 px-4 text-sm text-on-surface">
                    <button onclick="searchLocation()" class="bg-primary text-on-primary px-4 rounded-lg font-bold text-sm">SEARCH</button>
                </div>
            </div>
            <div id="map"></div>
            <div class="p-6 bg-surface-container flex justify-between items-center">
                <p class="text-xs text-on-surface-variant max-w-[60%]" id="selected-coords">Move the marker to your exact location</p>
                <button onclick="confirmLocation()" class="bg-primary text-on-primary px-8 py-3 rounded-xl font-bold hover:scale-105 transition-all shadow-lg shadow-primary/20">CONFIRM</button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/maplibre-gl@4.0.2/dist/maplibre-gl.js"></script>
    <script>
        let map, userMarker, shopMarker;
        const subtotal = {{ $cart->total_amount }};
        let fulfillmentMethod = 'pickup';

        const apiKey = '7a8ebe56abcf44e0be0c71f31d2fdfd7';

        // Shop Location from Tenant
        const shopLat = {{ $tenant->latitude ?? 5.6037 }};
        const shopLng = {{ $tenant->longitude ?? -0.1870 }};

        function setFulfillment(method) {
            fulfillmentMethod = method;

            // UI Toggle
            const pickupBtn = document.getElementById('btn-pickup');
            const deliveryBtn = document.getElementById('btn-delivery');
            const pickupSection = document.getElementById('section-pickup');
            const deliverySection = document.getElementById('section-delivery');

            if (method === 'pickup') {
                pickupBtn.classList.add('border-primary', 'bg-primary/5');
                pickupBtn.classList.remove('border-outline', 'opacity-60');
                deliveryBtn.classList.add('border-outline', 'opacity-60');
                deliveryBtn.classList.remove('border-primary', 'bg-primary/5');

                pickupSection.classList.remove('hidden');
                deliverySection.classList.add('hidden');

                // Recalculate Summary
                document.getElementById('display-delivery-fee').innerText = '0.00 GHS';
                document.getElementById('display-total-amount').innerText = `${subtotal.toFixed(2)} GHS`;
            } else {
                deliveryBtn.classList.add('border-primary', 'bg-primary/5');
                deliveryBtn.classList.remove('border-outline', 'opacity-60');
                pickupBtn.classList.add('border-outline', 'opacity-60');
                pickupBtn.classList.remove('border-primary', 'bg-primary/5');

                deliverySection.classList.remove('hidden');
                pickupSection.classList.add('hidden');

                document.getElementById('display-delivery-fee').innerText = '--';
            }
        }

        function initMap() {
            map = new maplibregl.Map({
                container: 'map',
                style: `https://maps.geoapify.com/v1/styles/osm-bright/style.json?apiKey=${apiKey}`,
                center: [shopLng, shopLat],
                zoom: 13
            });

            // Add Shop Marker
            const shopEl = document.createElement('div');
            shopEl.className = 'shop-marker';
            shopEl.innerHTML = `<img src="https://cdn-icons-png.flaticon.com/512/609/609803.png" style="width: 32px; height: 32px;">`;

            shopMarker = new maplibregl.Marker(shopEl)
                .setLngLat([shopLng, shopLat])
                .setPopup(new maplibregl.Popup().setHTML("<h4>{{ $tenant->name }}</h4>"))
                .addTo(map);

            // Try to get user location
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(position => {
                    const userLat = position.coords.latitude;
                    const userLng = position.coords.longitude;

                    userMarker = new maplibregl.Marker()
                        .setLngLat([userLng, userLat])
                        .setPopup(new maplibregl.Popup().setHTML("Your Location"))
                        .addTo(map);

                    calculateRoute(userLat, userLng);
                });
            }
        }

        async function calculateRoute(lat, lng) {
            try {
                const response = await fetch("{{ route('home.checkout.pickup_distance', $tenant) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ latitude: lat, longitude: lng })
                });

                const data = await response.json();
                if (data.success) {
                    document.getElementById('pickup-distance').innerText = `${data.distance} km`;
                    document.getElementById('pickup-time').innerText = `Approx. ${data.time} mins drive`;

                    // Draw Route
                    if (map.getSource('route')) {
                        map.getSource('route').setData(data.geometry);
                    } else {
                        map.addSource('route', {
                            'type': 'geojson',
                            'data': data.geometry
                        });
                        map.addLayer({
                            'id': 'route',
                            'type': 'line',
                            'source': 'route',
                            'layout': {
                                'line-join': 'round',
                                'line-cap': 'round'
                            },
                            'paint': {
                                'line-color': '#f97316',
                                'line-width': 5,
                                'line-opacity': 0.7
                            }
                        });
                    }

                    // Fit map to show both points
                    const bounds = new maplibregl.LngLatBounds()
                        .extend([lng, lat])
                        .extend([shopLng, shopLat]);

                    map.fitBounds(bounds, { padding: 50 });
                }
            } catch (error) {
                console.error('Route calculation error:', error);
            }
        }

        document.addEventListener('DOMContentLoaded', initMap);

        document.getElementById('pay-button').addEventListener('click', async function() {
            if (fulfillmentMethod === 'delivery') {
                showToast('Delivery is currently coming soon. Please select Pickup.', 'warning');
                return;
            }

            const email = document.getElementById('customer_email').value;
            const phone = document.getElementById('pickup-phone').value;

            if (!email || !phone) {
                showToast('Please fill in your email and phone number', 'warning');
                return;
            }

            const button = this;
            button.disabled = true;
            button.innerText = 'REDIRECTING TO PAYMENT...';

            try {
                const response = await fetch("{{ route('home.checkout.process', $tenant) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        email: email,
                        cart_id: "{{ $cart->id }}",
                        delivery_type: 'pickup',
                        delivery_fee: 0,
                        shipping_details: {
                            address: "{{ $tenant->address }}, {{ $tenant->city }}",
                            phone: phone,
                            lat: shopLat,
                            lng: shopLng
                        }
                    })
                });

                const data = await response.json();
                if (data.success) {
                    window.location.href = data.authorization_url;
                } else {
                    showToast(data.message || 'Payment initialization failed.', 'danger');
                    button.disabled = false;
                    button.innerText = 'COMPLETE ORDER';
                }
            } catch (error) {
                console.error(error);
                showToast('An error occurred. Please try again.', 'danger');
                button.disabled = false;
                button.innerText = 'COMPLETE ORDER';
            }
        });
    </script>
    @endpush
</x-layouts.shop>
