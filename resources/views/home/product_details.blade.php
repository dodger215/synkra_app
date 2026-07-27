<x-layouts.shop>
    <x-slot:title>{{ $product->name }} | {{ $tenant->name }}</x-slot:title>

    <div id="product-page-wrapper" class="transition-colors duration-1000 ease-in-out min-h-screen">
        <div class="max-w-7xl mx-auto px-4 md:px-8 py-8 md:py-12">
            <!-- Breadcrumbs & Follow -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <nav class="flex text-[10px] font-bold uppercase tracking-widest text-on-surface-variant/60 gap-2">
                    <a href="{{ route('home.index') }}" class="hover:text-primary transition-colors">MARKET</a>
                    <span>/</span>
                    <a href="{{ route('home.shop', $tenant) }}" class="hover:text-primary transition-colors">{{ $tenant->name }}</a>
                    <span>/</span>
                    <span class="text-on-surface">{{ $product->name }}</span>
                </nav>
                <button
                    onclick="toggleFollow(event, '{{ $tenant->getRouteKey() }}', '{{ $tenant->id }}')"
                    class="follow-btn-{{ $tenant->id }} px-6 py-2 rounded-full font-bold text-[10px] uppercase tracking-widest transition-all shadow-sm active:scale-95 {{ Auth::guard('customer')->check() && Auth::guard('customer')->user()->followedTenants()->where('tenant_id', $tenant->id)->exists() ? 'bg-primary text-on-primary' : 'bg-surface-container text-on-surface hover:bg-primary hover:text-on-primary' }}"
                >
                    {{ Auth::guard('customer')->check() && Auth::guard('customer')->user()->followedTenants()->where('tenant_id', $tenant->id)->exists() ? 'Following ' . $tenant->name : 'Follow ' . $tenant->name }}
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-20 items-start">
                <!-- Product Images (Left side on PC: 7 columns) -->
                <div class="lg:col-span-7 flex flex-col md:flex-row gap-8">
                    <!-- Thumbnails - Vertical on Desktop -->
                    @if(count($product->images ?? []) > 1)
                        <div class="order-2 md:order-1 flex md:flex-col gap-4 overflow-x-auto md:overflow-y-auto no-scrollbar md:h-[500px] py-1">
                            @foreach($product->images as $index => $image)
                                <div onclick="changeMainImage('{{ $product->imageUrl($index) }}', this)"
                                     class="thumbnail-wrapper flex-shrink-0 w-20 h-20 rounded-2xl overflow-hidden cursor-pointer border-2 transition-all duration-300 {{ $index === 0 ? 'border-primary shadow-lg shadow-primary/20' : 'border-transparent bg-white/50 backdrop-blur-sm' }}">
                                    <img src="{{ $product->imageUrl($index) }}" class="w-full h-full object-cover mix-blend-multiply" crossorigin="anonymous" onload="setThumbnailBg(this)">
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Main Image Display -->
                    <div class="order-1 md:order-2 flex-1 relative aspect-square md:aspect-[4/5] rounded-[2rem] overflow-hidden transition-all duration-700 ease-in-out group bg-white shadow-2xl border border-white/20" id="product-image-container">
                        <div class="absolute inset-0 flex items-center justify-center p-8 md:p-12">
                            <img id="main-product-image"
                                 src="{{ $product->imageUrl(0) ?? 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=1999&auto=format&fit=crop' }}"
                                 class="max-w-full max-h-full object-contain transition-all duration-700 group-hover:scale-105 mix-blend-multiply"
                                 alt="{{ $product->name }}"
                                 crossorigin="anonymous"
                                 onload="updatePageColor(this)">
                        </div>

                        <!-- Floating Actions (Mobile/Hover) -->
                        <button
                            onclick="toggleLike(event, '{{ $product->id }}')"
                            class="absolute top-6 right-6 w-12 h-12 rounded-full bg-white/90 backdrop-blur-xl shadow-xl flex items-center justify-center transition-all active:scale-90 z-20 like-btn-{{ $product->id }} {{ Auth::guard('customer')->check() && Auth::guard('customer')->user()->likedProducts()->where('product_id', $product->id)->exists() ? 'text-error' : 'text-on-surface' }}"
                        >
                            <span class="material-symbols-outlined text-2xl !leading-none" style="font-variation-settings: 'FILL' {{ Auth::guard('customer')->check() && Auth::guard('customer')->user()->likedProducts()->where('product_id', $product->id)->exists() ? '1' : '0' }}, 'wght' 400, 'GRAD' 0, 'opsz' 24;">favorite</span>
                        </button>
                    </div>
                </div>

                <!-- Product Info (Right side on PC: 5 columns) -->
                <div class="lg:col-span-5 space-y-10">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-black uppercase tracking-[0.2em] text-on-surface-variant/60">{{ $product->category?->name ?? 'Essentials' }}</span>
                            <div class="flex items-center gap-1.5 bg-white/40 backdrop-blur-md px-3 py-1 rounded-full border border-white/20">
                                <span class="material-symbols-outlined text-sm text-amber-400" style="font-variation-settings: 'FILL' 1;">star</span>
                                <span class="font-black text-xs">{{ number_format($product->reviews->avg('rating') ?? 4.5, 1) }}</span>
                                <span class="text-on-surface-variant/40 text-[10px] font-black">({{ $product->reviews->count() }})</span>
                            </div>
                        </div>
                        <h1 class="text-4xl md:text-5xl font-black text-on-surface leading-[1.1] uppercase tracking-tight">{{ $product->name }}</h1>
                    </div>

                    <div class="flex items-baseline gap-3">
                        <span class="text-[10px] font-black text-on-surface-variant/40 uppercase tracking-widest">Price:</span>
                        <span class="text-4xl font-black text-on-surface tracking-tighter">
                            {{ $tenant->settings['currency'] ?? 'GHS' }} {{ number_format($product->unit_price, 2) }}
                        </span>
                    </div>

                    <!-- Attributes / Variations -->
                    @if($product->attributes)
                        <div class="space-y-8">
                            @foreach($product->attributes as $key => $value)
                                <div class="space-y-4">
                                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-on-surface-variant/60">{{ $key }}:</p>
                                    <div class="flex flex-wrap gap-4">
                                        @php
                                            $options = explode(',', $value);
                                        @endphp
                                        @foreach($options as $option)
                                            <button class="px-7 py-3 rounded-2xl border-2 border-outline/30 hover:border-primary transition-all font-black text-xs uppercase tracking-widest min-w-[4rem] focus:border-primary focus:bg-primary/5 bg-white/30 backdrop-blur-sm">
                                                {{ trim($option) }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Quantity & Actions -->
                    <div class="space-y-6 pt-4">
                        <div class="flex items-center justify-between">
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-on-surface-variant/60">Quantity:</p>
                            <div class="flex items-center bg-white/40 backdrop-blur-md rounded-2xl p-1.5 shadow-inner border border-white/20">
                                <button onclick="changeQty(-1)" class="w-12 h-12 flex items-center justify-center hover:bg-white rounded-xl transition-all text-on-surface active:scale-90">
                                    <span class="material-symbols-outlined text-xl">remove</span>
                                </button>
                                <input type="number" id="qty" value="1" min="1" class="w-14 text-center bg-transparent border-none focus:ring-0 font-black text-on-surface text-xl">
                                <button onclick="changeQty(1)" class="w-12 h-12 flex items-center justify-center hover:bg-white rounded-xl transition-all text-on-surface active:scale-90">
                                    <span class="material-symbols-outlined text-xl">add</span>
                                </button>
                            </div>
                        </div>

                        <button class="w-full bg-on-surface text-surface h-20 rounded-[2.5rem] font-black uppercase tracking-[0.3em] hover:scale-[1.01] active:scale-[0.98] transition-all shadow-2xl flex items-center justify-center gap-4 add-to-cart-btn text-sm" data-id="{{ $product->id }}">
                            ADD TO CART
                            <span class="material-symbols-outlined">shopping_bag</span>
                        </button>
                    </div>

                    <!-- Description Snippet -->
                    <div class="pt-10 border-t border-white/20">
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-on-surface-variant/60 mb-6">Description</p>
                        <p class="text-on-surface-variant leading-relaxed text-sm font-medium">
                            {{ Str::limit($product->description, 250) }}
                            @if(strlen($product->description) > 250)
                                <button onclick="switchTab('description'); document.getElementById('details-section').scrollIntoView({behavior: 'smooth'})" class="text-primary font-black hover:underline ml-2 uppercase text-[10px] tracking-widest">Read more</button>
                            @endif
                        </p>
                    </div>
                </div>
            </div>


            <!-- Details Tabs Section -->
            <div id="details-section" class="mt-24">
                <div class="flex border-b border-outline/30 gap-10 overflow-x-auto no-scrollbar">
                    <button onclick="switchTab('description')" class="pb-4 border-b-2 border-primary text-on-surface font-black text-xs uppercase tracking-widest tab-btn-description">Description</button>
                    <button onclick="switchTab('reviews')" class="pb-4 border-b-2 border-transparent text-on-surface-variant/50 font-black text-xs uppercase tracking-widest hover:text-on-surface transition-colors tab-btn-reviews">Reviews ({{ $reviews->count() }})</button>
                </div>

                <div class="py-12">
                    <div id="tab-content">
                        <div id="description" class="tab-pane max-w-4xl">
                            <div class="prose prose-sm prose-primary max-w-none text-on-surface-variant leading-loose">
                                {!! nl2br(e($product->description)) !!}
                            </div>
                        </div>

                        <div id="reviews" class="tab-pane hidden">
                            <div class="flex items-center justify-between mb-10">
                                <h3 class="font-black text-xl uppercase tracking-tight text-on-surface">Customer Reviews</h3>
                                @auth('customer')
                                    <button onclick="document.getElementById('review-form').classList.toggle('hidden')" class="bg-primary/10 text-primary px-6 py-2 rounded-full font-bold text-[10px] uppercase tracking-widest hover:bg-primary hover:text-on-primary transition-all">Write a Review</button>
                                @endauth
                            </div>

                            @auth('customer')
                                <form id="review-form" action="{{ route('home.customer.product.review', $product) }}" method="POST" class="hidden mb-12 p-8 bg-white/30 backdrop-blur-xl rounded-[2rem] border border-outline/20 shadow-sm">
                                    @csrf
                                    <div class="space-y-6">
                                        <div>
                                            <label class="block text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-4">Overall Rating</label>
                                            <div class="flex gap-3 rating-input">
                                                @for($i=1; $i<=5; $i++)
                                                    <button type="button" onclick="setRating({{ $i }})" class="text-on-surface-variant/30 hover:text-amber-400 transition-colors">
                                                        <span class="material-symbols-outlined text-3xl star-icon-{{ $i }}">star</span>
                                                    </button>
                                                @endfor
                                            </div>
                                            <input type="hidden" name="rating" id="rating-value" required>
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-4">Your Experience</label>
                                            <textarea name="comment" rows="4" class="w-full bg-white/50 border border-outline/20 rounded-2xl p-4 text-sm focus:ring-4 focus:ring-primary/5 focus:border-primary outline-none text-on-surface transition-all placeholder:text-on-surface-variant/30" placeholder="Tell us more about the product..."></textarea>
                                        </div>
                                        <button type="submit" class="bg-primary text-on-primary px-10 py-4 rounded-2xl font-black uppercase tracking-widest hover:shadow-xl hover:shadow-primary/20 transition-all">Submit Review</button>
                                    </div>
                                </form>
                            @endauth

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                @forelse($reviews as $review)
                                    <div class="p-6 bg-white/30 backdrop-blur-sm rounded-3xl border border-outline/20 shadow-sm space-y-4">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-2xl bg-primary/10 flex items-center justify-center font-bold text-primary text-xs">
                                                    {{ substr($review->customer->first_name, 0, 1) }}{{ substr($review->customer->last_name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <p class="font-bold text-sm text-on-surface">{{ $review->customer->first_name }} {{ $review->customer->last_name }}</p>
                                                    <div class="flex text-amber-400">
                                                        @for($i=0; $i<5; $i++)
                                                            <span class="material-symbols-outlined text-[10px]" style="font-variation-settings: 'FILL' {{ $i < $review->rating ? 1 : 0 }};">star</span>
                                                        @endfor
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="text-[10px] text-on-surface-variant/50 font-bold uppercase">{{ $review->created_at->format('M d, Y') }}</span>
                                        </div>
                                        <p class="text-on-surface-variant text-sm leading-relaxed italic">"{{ $review->comment }}"</p>
                                    </div>
                                @empty
                                    <div class="col-span-full py-20 text-center space-y-4">
                                        <span class="material-symbols-outlined text-5xl text-on-surface-variant/20">rate_review</span>
                                        <p class="text-on-surface-variant/50 text-sm font-bold uppercase tracking-widest">No reviews yet. Be the first to share your thoughts!</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            @if($relatedProducts->count() > 0)
                <div class="mt-32">
                    <div class="flex items-end justify-between mb-10">
                        <div>
                            <p class="text-[10px] font-black text-primary uppercase tracking-[0.3em] mb-2">Curated for you</p>
                            <h2 class="text-3xl font-black text-on-surface uppercase tracking-tight">You May Also Like</h2>
                        </div>
                        <a href="{{ route('home.shop', $tenant) }}" class="text-xs font-black uppercase tracking-widest hover:text-primary transition-colors flex items-center gap-2">
                            View All <span class="material-symbols-outlined text-sm">arrow_forward</span>
                        </a>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 lg:gap-10">
                        @foreach($relatedProducts as $related)
                            <a href="{{ route('home.product.details', ['tenant' => $tenant, 'product' => $related]) }}" class="group block">
                                <div class="relative aspect-[4/5] overflow-hidden rounded-[2rem] bg-white/30 backdrop-blur-sm mb-6 transition-all duration-500 group-hover:shadow-2xl group-hover:shadow-primary/10">
                                    <img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                         src="{{ $related->imageUrl() ?? 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=1999&auto=format&fit=crop' }}"
                                         alt="{{ $related->name }}"/>
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                </div>
                                <div class="space-y-1">
                                    <h4 class="text-sm font-bold truncate text-on-surface uppercase tracking-tight">{{ $related->name }}</h4>
                                    <p class="font-black text-primary text-base">{{ $tenant->settings['currency'] ?? 'GHS' }} {{ number_format($related->unit_price, 2) }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        function updatePageColor(img) {
            const canvas = document.createElement('canvas');
            const context = canvas.getContext('2d');
            canvas.width = 50;
            canvas.height = 50;

            try {
                context.drawImage(img, 0, 0, 50, 50);
                const data = context.getImageData(0, 0, 50, 50).data;

                let r = 0, g = 0, b = 0, count = 0;
                for (let i = 0; i < data.length; i += 4) {
                    const red = data[i];
                    const green = data[i+1];
                    const blue = data[i+2];
                    const alpha = data[i+3];

                    if (alpha < 50) continue;
                    if (red > 240 && green > 240 && blue > 240) continue; // Ignore white

                    r += red;
                    g += green;
                    b += blue;
                    count++;
                }

                if (count > 0) {
                    r = Math.floor(r / count);
                    g = Math.floor(g / count);
                    b = Math.floor(b / count);

                    const isDark = document.documentElement.classList.contains('dark');
                    const alpha = isDark ? 0.4 : 0.2;
                    const color = `rgba(${r}, ${g}, ${b}, ${alpha})`;
                    document.getElementById('product-page-wrapper').style.backgroundColor = color;
                }
            } catch (e) {
                console.warn('Could not extract color:', e);
            }
        }

        function setThumbnailBg(img) {
            const canvas = document.createElement('canvas');
            const context = canvas.getContext('2d');
            canvas.width = 20;
            canvas.height = 20;

            try {
                context.drawImage(img, 0, 0, 20, 20);
                const data = context.getImageData(0, 0, 20, 20).data;

                let r = 0, g = 0, b = 0, count = 0;
                for (let i = 0; i < data.length; i += 4) {
                    if (data[i] > 245 && data[i+1] > 245 && data[i+2] > 245) continue;
                    r += data[i]; g += data[i+1]; b += data[i+2]; count++;
                }

                if (count > 0) {
                    img.parentElement.style.backgroundColor = `rgba(${r/count}, ${g/count}, ${b/count}, 0.15)`;
                }
            } catch (e) {}
        }

        function changeMainImage(src, element) {
            const mainImg = document.getElementById('main-product-image');
            if (mainImg.src === src) return;

            // Fade out
            mainImg.style.opacity = '0';
            mainImg.style.transform = 'scale(0.9) translateY(10px)';

            setTimeout(() => {
                mainImg.src = src;
                const tempImg = new Image();
                tempImg.crossOrigin = "Anonymous";
                tempImg.onload = () => {
                    updatePageColor(tempImg);
                    mainImg.style.opacity = '1';
                    mainImg.style.transform = 'scale(1) translateY(0)';
                };
                tempImg.src = src;
            }, 300);

            // Update active border
            document.querySelectorAll('.thumbnail-wrapper').forEach(el => {
                el.classList.remove('border-primary', 'shadow-lg', 'shadow-primary/20');
                el.classList.add('border-transparent');
            });
            element.classList.remove('border-transparent');
            element.classList.add('border-primary', 'shadow-lg', 'shadow-primary/20');
        }

        function switchTab(tabId) {
            document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.add('hidden'));
            document.getElementById(tabId).classList.remove('hidden');

            document.querySelectorAll('#details-section button').forEach(btn => {
                btn.classList.remove('border-primary', 'text-on-surface');
                btn.classList.add('border-transparent', 'text-on-surface-variant/50');
            });

            const activeBtn = document.querySelector(`.tab-btn-${tabId}`);
            activeBtn.classList.add('border-primary', 'text-on-surface');
            activeBtn.classList.remove('border-transparent', 'text-on-surface-variant/50');
        }

        function setRating(rating) {
            document.getElementById('rating-value').value = rating;
            for(let i = 1; i <= 5; i++) {
                const icon = document.querySelector(`.star-icon-${i}`);
                if (i <= rating) {
                    icon.style.fontVariationSettings = "'FILL' 1";
                    icon.classList.add('text-amber-400');
                    icon.classList.remove('text-on-surface-variant/30');
                } else {
                    icon.style.fontVariationSettings = "'FILL' 0";
                    icon.classList.remove('text-amber-400');
                    icon.classList.add('text-on-surface-variant/30');
                }
            }
        }

        async function toggleLike(event, productId) {
            event.stopPropagation();
            @if(!Auth::guard('customer')->check())
                window.location.href = "{{ route('home.customer.login') }}";
                return;
            @endif

            try {
                const response = await fetch(`/customer/product/${productId}/like`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();
                if (data.status) {
                    const btn = document.querySelector(`.like-btn-${productId}`);
                    const icon = btn.querySelector('.material-symbols-outlined');
                    if (data.status === 'liked') {
                        btn.classList.add('text-error');
                        btn.classList.remove('text-on-surface/40');
                        icon.style.fontVariationSettings = "'FILL' 1";
                        showToast('Added to wishlist');
                    } else {
                        btn.classList.remove('text-error');
                        btn.classList.add('text-on-surface/40');
                        icon.style.fontVariationSettings = "'FILL' 0";
                        showToast('Removed from wishlist', 'info');
                    }
                }
            } catch (error) {
                console.error('Error toggling like:', error);
            }
        }

        async function toggleFollow(event, tenantRouteKey, tenantId) {
            event.stopPropagation();
            @if(!Auth::guard('customer')->check())
                window.location.href = "{{ route('home.customer.login') }}";
                return;
            @endif

            try {
                const response = await fetch(`/customer/tenant/${tenantId}/follow`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();
                if (data.status) {
                    const btn = document.querySelector(`.follow-btn-${tenantId}`);
                    if (data.status === 'followed') {
                        btn.classList.add('bg-primary', 'text-on-primary');
                        btn.classList.remove('bg-surface-container', 'text-on-surface');
                        btn.innerText = 'Following {{ $tenant->name }}';
                        showToast('You are now following {{ $tenant->name }}');
                    } else {
                        btn.classList.remove('bg-primary', 'text-on-primary');
                        btn.classList.add('bg-surface-container', 'text-on-surface');
                        btn.innerText = 'Follow {{ $tenant->name }}';
                        showToast('Unfollowed {{ $tenant->name }}', 'info');
                    }
                }
            } catch (error) {
                console.error('Error toggling follow:', error);
            }
        }

        function changeQty(delta) {
            const input = document.getElementById('qty');
            const newVal = parseInt(input.value) + delta;
            if (newVal >= 1) input.value = newVal;
        }

        document.querySelector('.add-to-cart-btn').addEventListener('click', async function() {
            const productId = this.dataset.id;
            const quantity = document.getElementById('qty').value;
            const tenantId = "{{ $tenant->id }}";

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
                        quantity: quantity
                    })
                });
                const data = await response.json();
                if (data.success) {
                    showToast('Product added to cart!');
                    if (window.updateCartCount) window.updateCartCount();
                }
            } catch (error) {
                console.error('Error adding to cart:', error);
            }
        });
    </script>
    @endpush
</x-layouts.shop>
