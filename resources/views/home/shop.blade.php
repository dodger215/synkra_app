<x-layouts.shop>
    <x-slot:title>{{ $tenant->name }}</x-slot:title>

    @push('styles')
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        @keyframes gradient-flow {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .hero-text-shadow {
            text-shadow: 0 4px 12px rgba(0,0,0,0.5);
        }

        .font-caveat {
            font-family: 'Caveat', cursive;
        }

        .shop-glass-card {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .dark .shop-glass-card {
            background: rgba(15, 15, 18, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .parallax-3d {
            transition: transform 0.2s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            will-change: transform;
        }
    </style>
    @endpush

    <div id="shop-page-wrapper" class="transition-colors duration-1000 ease-in-out min-h-screen">
        <!-- Vendor Hero Banner -->
        <section class="relative w-full h-[300px] md:h-[450px] overflow-hidden flex items-center justify-center">
            <div class="absolute inset-0 w-full h-full bg-cover bg-center" style="background-image: url('{{ $tenant->settings['banner_url'] ?? 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?q=80&w=2070&auto=format&fit=crop' }}')"></div>
            <div class="absolute inset-0 bg-black/40"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent"></div>

            <!-- Hidden img for color extraction -->
            <img src="{{ $tenant->settings['banner_url'] ?? 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?q=80&w=2070&auto=format&fit=crop' }}"
                 class="hidden"
                 crossorigin="anonymous"
                 onload="updatePageColor(this)">

            <div class="relative z-10 text-center px-margin-mobile flex flex-col items-center">
                <div class="flex items-center justify-center h-12 md:h-16 mb-2">
                    <h3 id="typing-hero-greeting" class="font-caveat text-2xl md:text-5xl text-white/90 hero-text-shadow"></h3>
                </div>
                <div class="flex items-center justify-center min-h-[4rem] md:min-h-[8rem]">
                    <h2 id="typing-hero-title" class="font-caveat text-6xl md:text-9xl font-bold text-white hero-text-shadow leading-none"></h2>
                </div>
            </div>
        </section>

        <!-- Vendor Profile Section -->
        <section class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop -mt-16 md:-mt-24 relative z-10" data-aos="fade-up">
            <div class="absolute -top-20 -right-10 w-48 h-48 opacity-40 pointer-events-none hidden lg:block">
                <img src="https://raw.githubusercontent.com/microsoft/fluentui-emoji/main/assets/Shield/3D/shield_3d.png" class="w-full h-full parallax-3d" data-speed="3">
            </div>
            <div class="shop-glass-card rounded-[3rem] p-6 md:p-10 shadow-2xl flex flex-col md:flex-row items-start md:items-end justify-between gap-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
                <div class="flex flex-col md:flex-row items-center md:items-end gap-8 w-full md:w-auto">
                    <!-- Logo -->
                    <div class="w-32 h-32 md:w-44 md:h-44 rounded-[2.5rem] overflow-hidden border-4 border-white dark:border-white/10 shadow-2xl flex-shrink-0 transform -rotate-2 hover:rotate-0 transition-transform duration-500">
                        <img class="w-full h-full object-cover" src="{{ $tenant->settings['logo_url'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($tenant->name) . '&size=200' }}" alt="{{ $tenant->name }} Logo"/>
                    </div>
                    <!-- Identity -->
                    <div class="text-center md:text-left pb-2">
                        <div class="flex items-center justify-center md:justify-start gap-2 mb-2">
                            <h1 class="font-display-lg text-headline-md font-black text-on-surface uppercase tracking-tighter">{{ $tenant->name }}</h1>
                            @if($tenant->settings['verified'] ?? false)
                                <span class="material-symbols-outlined text-primary text-xl" style="font-variation-settings: 'FILL' 1;">verified</span>
                            @endif
                        </div>
                        <div class="flex items-center justify-center md:justify-start gap-4 mb-4">
                            <div class="flex items-center">
                                <span class="material-symbols-outlined text-primary text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
                                <span class="font-black ml-1 text-on-surface text-sm">{{ $tenant->settings['rating'] ?? '4.5' }}</span>
                                <span class="text-on-surface-variant text-xs font-bold ml-1">({{ $tenant->settings['reviews_count'] ?? '0' }} Reviews)</span>
                            </div>
                            <span class="text-outline w-px h-3 bg-outline"></span>
                            <div class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-on-surface-variant text-sm">location_on</span>
                                <span class="text-on-surface-variant text-xs font-black uppercase tracking-widest">{{ $tenant->city ?? 'Marketplace' }}</span>
                            </div>
                        </div>
                        <p class="text-on-surface-variant max-w-lg text-sm leading-relaxed font-bold italic">"{{ $tenant->settings['description'] ?? 'Welcome to our store! We curate the finest products for your lifestyle.' }}"</p>
                    </div>
                </div>
                <!-- Actions -->
                <div class="flex items-center gap-4 w-full md:w-auto pb-2">
                    <button
                        onclick="toggleFollow(event, '{{ $tenant->getRouteKey() }}', '{{ $tenant->id }}')"
                        class="follow-btn-{{ $tenant->id }} flex-1 md:flex-none px-10 py-5 rounded-2xl font-black uppercase tracking-widest text-xs transition-all duration-300 flex items-center justify-center gap-3 active:scale-95 shadow-xl {{ Auth::guard('customer')->check() && Auth::guard('customer')->user()->followedTenants()->where('tenant_id', $tenant->id)->exists() ? 'bg-primary text-on-primary shadow-primary/30' : 'bg-on-surface text-surface hover:bg-primary hover:text-on-primary' }}"
                    >
                        <span class="material-symbols-outlined text-lg transition-transform duration-300">
                            {{ Auth::guard('customer')->check() && Auth::guard('customer')->user()->followedTenants()->where('tenant_id', $tenant->id)->exists() ? 'check_circle' : 'person_add' }}
                        </span>
                        <span class="btn-text">{{ Auth::guard('customer')->check() && Auth::guard('customer')->user()->followedTenants()->where('tenant_id', $tenant->id)->exists() ? 'Following' : 'Follow' }}</span>
                    </button>
                    <button class="w-14 h-14 shop-glass-card rounded-2xl flex items-center justify-center hover:bg-white dark:hover:bg-white/10 transition-all active:scale-95 group shadow-xl">
                        <span class="material-symbols-outlined text-on-surface group-hover:text-primary transition-colors">share</span>
                    </button>
                </div>
            </div>
        </section>

        <!-- Store Navigation & Search -->
        <nav class="sticky top-[72px] z-40 bg-white/40 dark:bg-black/40 backdrop-blur-xl border-b border-white/10 mt-12">
            <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop flex items-center justify-between overflow-x-auto no-scrollbar">
                <div class="flex gap-10">
                    <button onclick="switchShopTab('products')" class="shop-tab-btn py-6 border-b-2 border-primary text-primary font-black text-xs uppercase tracking-[0.2em] whitespace-nowrap" id="tab-btn-products">Products</button>
                    <button onclick="switchShopTab('reviews')" class="shop-tab-btn py-6 border-b-2 border-transparent text-on-surface-variant hover:text-primary transition-colors text-xs font-black uppercase tracking-[0.2em] whitespace-nowrap" id="tab-btn-reviews">Reviews</button>
                    <button onclick="switchShopTab('about')" class="shop-tab-btn py-6 border-b-2 border-transparent text-on-surface-variant hover:text-primary transition-colors text-xs font-black uppercase tracking-[0.2em] whitespace-nowrap" id="tab-btn-about">Our Story</button>
                </div>
                <div class="hidden md:flex items-center relative w-72 ml-8">
                    <span class="material-symbols-outlined absolute left-4 text-on-surface-variant text-lg">search</span>
                    <input type="text" placeholder="Filter these items..." class="w-full bg-white/20 dark:bg-white/5 border border-white/10 rounded-2xl py-3 pl-12 pr-4 text-xs font-bold focus:ring-2 focus:ring-primary/50 text-on-surface placeholder:text-on-surface-variant/50 backdrop-blur-md outline-none transition-all">
                </div>
            </div>
        </nav>

    <!-- Tab Content -->
    <div id="shop-products-tab" class="shop-tab-content">
        <!-- Product Catalog Section -->
        <section class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop py-stack-lg flex flex-col md:flex-row gap-gutter">
            <!-- Sidebar Filters -->
            <aside class="hidden md:block w-64 shrink-0">
                <div class="sticky top-40 space-y-8">
                    <div>
                        <h3 class="font-bold mb-4 uppercase tracking-wider text-xs text-on-surface-variant">Categories</h3>
                        <ul class="space-y-3">
                            <li class="flex items-center justify-between text-primary font-medium cursor-pointer">
                                <span>All Items</span>
                                <span class="text-xs bg-surface-container px-2 py-0.5 rounded-full text-on-surface">{{ $products->total() }}</span>
                            </li>
                            @foreach($categories as $category)
                                <li class="flex items-center justify-between text-on-surface-variant hover:text-primary cursor-pointer transition-colors">
                                    <span>{{ $category->name }}</span>
                                    <span class="text-xs">{{ $category->products_count ?? '' }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-bold mb-4 uppercase tracking-wider text-xs text-on-surface-variant">Sort By</h3>
                        <select class="w-full bg-surface-container border-outline rounded-lg text-sm py-2 focus:ring-primary text-on-surface">
                            <option>Newest First</option>
                            <option>Price: Low to High</option>
                            <option>Price: High to Low</option>
                            <option>Most Popular</option>
                        </select>
                    </div>
                </div>
            </aside>

            <!-- Product Grid -->
            <div class="flex-1">
                <div class="grid grid-cols-2 lg:grid-cols-3 gap-y-12 gap-x-8">
                    @foreach($products as $product)
                        <div class="group relative cursor-pointer bg-white dark:bg-white/5 rounded-[2.5rem] overflow-hidden border border-outline/40 hover:border-primary/40 transition-all duration-500 hover:shadow-2xl hover:-translate-y-2 p-4"
                             onclick="window.location.href='{{ route('home.product.details', ['tenant' => $tenant, 'product' => $product]) }}'"
                             data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 100 }}">

                            <div class="relative aspect-[3/4] overflow-hidden rounded-[2rem] bg-surface-container mb-6 border border-outline/20">
                                <img class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110"
                                     src="{{ $product->imageUrl() ?? 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=1999&auto=format&fit=crop' }}"
                                     alt="{{ $product->name }}"/>

                                <!-- Like Button -->
                                <button
                                    onclick="toggleLike(event, '{{ $product->id }}')"
                                    class="absolute top-4 right-4 w-12 h-12 shop-glass-card shadow-2xl rounded-full flex items-center justify-center z-20 transition-all hover:bg-primary hover:text-white like-btn-{{ $product->id }} {{ Auth::guard('customer')->check() && Auth::guard('customer')->user()->likedProducts()->where('product_id', $product->id)->exists() ? 'text-primary' : 'text-on-surface' }}"
                                >
                                    <span class="material-symbols-outlined text-xl !leading-none" style="font-variation-settings: 'FILL' {{ Auth::guard('customer')->check() && Auth::guard('customer')->user()->likedProducts()->where('product_id', $product->id)->exists() ? '1' : '0' }};">favorite</span>
                                </button>

                                <!-- Add to Cart Overlay -->
                                <div class="absolute inset-x-0 bottom-0 p-4 translate-y-full group-hover:translate-y-0 transition-transform duration-500 z-10">
                                    <button class="w-full py-4 bg-on-surface text-surface text-xs font-black uppercase tracking-widest rounded-2xl shadow-2xl hover:bg-primary transition-colors add-to-cart" data-id="{{ $product->id }}">
                                        Quick Add +
                                    </button>
                                </div>
                            </div>

                            <div class="px-2 space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-[10px] font-black text-on-surface-variant uppercase tracking-[0.2em]">{{ $product->category?->name ?? 'Essentials' }}</span>
                                    <div class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[10px] fill-1 text-primary">star</span>
                                        <span class="text-[10px] font-black text-on-surface-variant">4.9</span>
                                    </div>
                                </div>
                                <h4 class="font-display-lg text-lg font-black truncate text-on-surface uppercase tracking-tighter">{{ $product->name }}</h4>
                                <div class="flex items-baseline gap-2">
                                    <p class="font-black text-on-surface text-2xl">{{ number_format($product->unit_price, 2) }}</p>
                                    <span class="text-xs font-black text-on-surface-variant uppercase tracking-widest">GHS</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-16">
                    {{ $products->links() }}
                </div>
            </div>
        </section>
    </div>

    <div id="shop-reviews-tab" class="shop-tab-content hidden">
        <section class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop py-stack-lg">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-6">
                <h2 class="font-display-lg text-headline-md font-bold text-on-surface uppercase tracking-tighter">What customers are saying</h2>
                @auth('customer')
                    <button onclick="document.getElementById('shop-review-form').classList.toggle('hidden')" class="bg-primary text-on-primary px-6 py-3 rounded-xl font-bold hover:opacity-90 transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">edit</span>
                        WRITE A REVIEW
                    </button>
                @endauth
            </div>

            @auth('customer')
                @if($products->count() > 0)
                <div id="shop-review-form" class="hidden mb-16 p-8 bg-surface-container rounded-[2rem] border border-outline max-w-2xl mx-auto shadow-2xl">
                    <h3 class="text-xl font-bold text-on-surface mb-6 uppercase tracking-tighter">Rate your experience</h3>
                    <form action="{{ route('home.customer.product.review', $products->first()) }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-3">Your Rating</label>
                            <div class="flex gap-3 shop-rating-input">
                                @for($i=1; $i<=5; $i++)
                                    <button type="button" onclick="setShopRating({{ $i }})" class="text-on-surface-variant hover:text-primary transition-all transform hover:scale-110">
                                        <span class="material-symbols-outlined text-3xl shop-star-icon-{{ $i }}">star</span>
                                    </button>
                                @endfor
                            </div>
                            <input type="hidden" name="rating" id="shop-rating-value" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-3">Which product did you buy?</label>
                            @php
                                $productOptions = $products->map(fn($p) => ['value' => $p->id, 'label' => $p->name])->toArray();
                            @endphp
                            <x-ui.select name="product_id" :options="$productOptions" required="true" class="!max-w-full" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-3">Tell us more</label>
                            <textarea name="comment" rows="4" class="w-full bg-surface-container-highest border border-outline rounded-xl p-4 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-on-surface transition-all placeholder:text-on-surface-variant/50" placeholder="How was the quality? Service? Delivery?" required></textarea>
                        </div>
                        <button type="submit" class="w-full bg-primary text-on-primary py-4 rounded-xl font-black uppercase tracking-widest hover:scale-[1.02] active:scale-95 transition-all shadow-xl shadow-primary/20">
                            SUBMIT REVIEW
                        </button>
                    </form>
                </div>
                @endif
            @endauth

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @forelse($reviews as $review)
                    <div class="bg-surface-container-lowest border border-surface-container rounded-2xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-surface-container border border-outline flex items-center justify-center font-bold text-primary">
                                    {{ substr($review->customer->first_name, 0, 1) }}{{ substr($review->customer->last_name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-sm text-on-surface">{{ $review->customer->first_name }} {{ $review->customer->last_name }}</p>
                                    <div class="flex text-primary scale-75 origin-left">
                                        @for($i=0; $i<5; $i++)
                                            <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' {{ $i < $review->rating ? 1 : 0 }};">star</span>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                            <span class="text-[10px] text-on-surface-variant font-bold uppercase">{{ $review->created_at->format('M d, Y') }}</span>
                        </div>
                        <p class="text-on-surface-variant text-sm font-bold mb-2">Reviewing: <span class="text-primary">{{ $review->product->name }}</span></p>
                        <p class="text-on-surface-variant text-sm leading-relaxed italic">"{{ $review->comment }}"</p>
                    </div>
                @empty
                    <div class="col-span-2 text-center py-20 bg-surface-container-lowest border border-surface-container rounded-2xl">
                        <span class="material-symbols-outlined text-4xl text-outline-variant mb-2">rate_review</span>
                        <p class="text-on-surface-variant">No reviews yet for this shop.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>

    <div id="shop-about-tab" class="shop-tab-content hidden">
        <section class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop py-stack-lg">
            <div class="max-w-3xl">
                <h2 class="font-display-lg text-headline-md font-bold text-on-surface uppercase tracking-tighter mb-8">About {{ $tenant->name }}</h2>
                <p class="text-on-surface-variant leading-relaxed text-lg mb-8">
                    {{ $tenant->settings['description'] ?? 'Welcome to our store! We are dedicated to providing the best products and service to our customers.' }}
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="p-6 bg-surface-container-lowest border border-surface-container rounded-2xl">
                        <h3 class="font-bold text-sm uppercase tracking-widest mb-4">Contact Info</h3>
                        <ul class="space-y-4">
                            <li class="flex items-center gap-3 text-on-surface-variant text-sm">
                                <span class="material-symbols-outlined text-primary text-sm">location_on</span>
                                <span>{{ $tenant->settings['location'] ?? 'Marketplace' }}</span>
                            </li>
                            <li class="flex items-center gap-3 text-on-surface-variant text-sm">
                                <span class="material-symbols-outlined text-primary text-sm">mail</span>
                                <span>{{ $tenant->email ?? 'contact@' . Str::slug($tenant->name) . '.com' }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
    </div>

    </div> <!-- End Shop Page Wrapper -->

    @push('scripts')
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            AOS.init({
                duration: 800,
                once: true,
                offset: 50
            });
        });

        // Mouse Parallax Effect
        document.addEventListener('mousemove', (e) => {
            const elements = document.querySelectorAll('.parallax-3d');
            const mouseX = e.clientX;
            const mouseY = e.clientY;

            elements.forEach(el => {
                const speed = el.getAttribute('data-speed') || 2;
                const x = (window.innerWidth - mouseX * speed) / 100;
                const y = (window.innerHeight - mouseY * speed) / 100;

                el.style.transform = `translateX(${x}px) translateY(${y}px)`;
            });
        });

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
                    if (red > 240 && green > 240 && blue > 240) continue;
                    if (red < 20 && green < 20 && blue < 20) continue;

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
                    const alpha = isDark ? 0.35 : 0.12;
                    const color = `rgba(${r}, ${g}, ${b}, ${alpha})`;
                    document.getElementById('shop-page-wrapper').style.backgroundColor = color;
                }
            } catch (e) {
                console.warn('Could not extract color:', e);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const greeting = "Welcome to";
            const shopName = "{{ $tenant->name }}";
            const greetingTarget = document.getElementById('typing-hero-greeting');
            const titleTarget = document.getElementById('typing-hero-title');

            let i = 0;
            let j = 0;

            function typeGreeting() {
                if (i < greeting.length) {
                    greetingTarget.textContent += greeting.charAt(i);
                    i++;
                    setTimeout(typeGreeting, 80 + Math.random() * 40);
                } else {
                    setTimeout(typeShopName, 400);
                }
            }

            function typeShopName() {
                if (j < shopName.length) {
                    titleTarget.textContent += shopName.charAt(j);
                    j++;
                    setTimeout(typeShopName, 150 + Math.random() * 100);
                }
            }

            // Start typing after a short delay
            setTimeout(typeGreeting, 800);
        });

        function switchShopTab(tabId) {
            // Hide all tab contents
            document.querySelectorAll('.shop-tab-content').forEach(el => el.classList.add('hidden'));
            // Show the selected tab content
            document.getElementById(`shop-${tabId}-tab`).classList.remove('hidden');

            // Reset all tab buttons
            document.querySelectorAll('.shop-tab-btn').forEach(btn => {
                btn.classList.remove('border-primary', 'text-primary');
                btn.classList.add('border-transparent', 'text-on-surface-variant');
            });
            // Highlight the selected tab button
            const activeBtn = document.getElementById(`tab-btn-${tabId}`);
            activeBtn.classList.add('border-primary', 'text-primary');
            activeBtn.classList.remove('border-transparent', 'text-on-surface-variant');
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
                        btn.classList.add('text-primary');
                        icon.style.fontVariationSettings = "'FILL' 1";
                        showToast('Added to wishlist');
                    } else {
                        btn.classList.remove('text-primary');
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

            const btn = document.querySelector(`.follow-btn-${tenantId}`);
            const icon = btn.querySelector('.material-symbols-outlined');
            const text = btn.querySelector('.btn-text');

            // Animation
            btn.classList.add('scale-90', 'opacity-70');

            try {
                const response = await fetch(`/customer/tenant/${tenantRouteKey}/follow`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();

                btn.classList.remove('scale-90', 'opacity-70');

                if (data.status) {
                    if (data.status === 'followed') {
                        btn.classList.add('bg-primary', 'text-on-primary', 'shadow-lg', 'shadow-primary/25');
                        btn.classList.remove('bg-surface-container-highest', 'text-on-surface');
                        icon.innerText = 'check_circle';
                        icon.style.fontVariationSettings = "'FILL' 1";
                        icon.classList.add('scale-110');
                        text.innerText = 'Following';

                        setupUnfollowHover(btn);
                        showToast(`You are now following ${"{{ $tenant->name }}"}`);
                    } else {
                        btn.classList.remove('bg-primary', 'text-on-primary', 'shadow-lg', 'shadow-primary/25', 'bg-error', 'text-on-error');
                        btn.classList.add('bg-surface-container-highest', 'text-on-surface');
                        icon.innerText = 'person_add';
                        icon.style.fontVariationSettings = "'FILL' 0";
                        icon.classList.remove('scale-110');
                        text.innerText = 'Follow';

                        btn.onmouseenter = null;
                        btn.onmouseleave = null;

                        showToast(`Unfollowed ${"{{ $tenant->name }}"}`, 'info');
                    }
                }
            } catch (error) {
                btn.classList.remove('scale-90', 'opacity-70');
                console.error('Error toggling follow:', error);
            }
        }

        function setupUnfollowHover(btn) {
            const text = btn.querySelector('.btn-text');
            const icon = btn.querySelector('.material-symbols-outlined');

            btn.onmouseenter = () => {
                text.innerText = 'Unfollow';
                icon.innerText = 'do_not_disturb_on';
                btn.classList.add('!bg-error', '!text-on-error');
            };
            btn.onmouseleave = () => {
                text.innerText = 'Following';
                icon.innerText = 'check_circle';
                btn.classList.remove('!bg-error', '!text-on-error');
            };
        }

        // Initialize follow button hover state if already following
        @if(Auth::guard('customer')->check() && Auth::guard('customer')->user()->followedTenants()->where('tenant_id', $tenant->id)->exists())
            document.addEventListener('DOMContentLoaded', () => {
                const followBtn = document.querySelector(`.follow-btn-{{ $tenant->id }}`);
                if (followBtn) setupUnfollowHover(followBtn);
            });
        @endif

        function setShopRating(rating) {
            document.getElementById('shop-rating-value').value = rating;
            for(let i = 1; i <= 5; i++) {
                const icon = document.querySelector(`.shop-star-icon-${i}`);
                if (i <= rating) {
                    icon.style.fontVariationSettings = "'FILL' 1";
                    icon.classList.add('text-primary');
                    icon.classList.remove('text-on-surface-variant');
                } else {
                    icon.style.fontVariationSettings = "'FILL' 0";
                    icon.classList.remove('text-primary');
                    icon.classList.add('text-on-surface-variant');
                }
            }
        }

        // Add to cart functionality
        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', async (e) => {
                e.stopPropagation();
                const productId = button.dataset.id;
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
                            quantity: 1
                        })
                    });
                    const data = await response.json();
                    if (data.success) {
                        showToast('Product added to cart!');
                        updateCartCount();
                    }
                } catch (error) {
                    console.error('Error adding to cart:', error);
                }
            });
        });

        async function updateCartCount() {
            try {
                const response = await fetch("{{ route('home.cart.get') }}");
                const data = await response.json();
                const count = (data.items || []).reduce((acc, item) => acc + item.quantity, 0);
                document.querySelectorAll('.cart-count').forEach(el => el.innerText = count);
            } catch (error) {
                console.error('Error fetching cart:', error);
            }
        }

        updateCartCount();

        // Smooth fade-in for product cards on scroll
        const observerOptions = {
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('opacity-100', 'translate-y-0');
                    entry.target.classList.remove('opacity-0', 'translate-y-8');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.group.cursor-pointer').forEach(card => {
            card.classList.add('opacity-0', 'translate-y-8', 'transition-all', 'duration-700', 'ease-out');
            observer.observe(card);
        });
    </script>
    @endpush
</x-layouts.shop>
