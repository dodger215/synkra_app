<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page->title ?? $page->page_name }} | {{ $store->store_name }}</title>
    <meta name="description" content="{{ $page->meta_description }}">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: {{ $store->primary_color }};
            --secondary: {{ $store->secondary_color }};
        }

        {!! $page->content['theme_css'] ?? '' !!}

        body {
            margin: 0;
            padding: 0;
            font-family: 'Instrument Sans', sans-serif;
            background: #ffffff;
            color: #0f172a;
            overflow-x: hidden;
        }

        .storefront-wrapper {
            position: relative;
            width: 100%;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .page-content {
            position: relative;
            width: 100%;
            max-width: 100%;
            min-height: 800px;
        }

        @media (max-width: 1000px) {
            .page-content { width: 100%; }
        }

        .canvas-el {
            position: absolute;
            box-sizing: border-box;
        }

        .flowexa-btn-primary {
            background: var(--primary);
            color: white;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: opacity 0.2s;
        }
        .flowexa-btn-primary:hover { opacity: 0.9; }

        /* Component Styles */
        .product-grid {
            display: grid;
            gap: 20px;
        }
        .product-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.2s;
        }
        .product-card:hover { transform: translateY(-4px); }
        .product-image {
            aspect-ratio: 1/1;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #cbd5e1;
        }
        .product-info { padding: 12px; }
        .product-title { font-weight: 700; font-size: 0.95rem; margin-bottom: 4px; }
        .product-price { color: var(--primary); font-weight: 800; font-size: 1rem; }

        .footer-section {
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
        }

        /* Cart & Checkout Modal Styles */
        .flowexa-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(4px);
            z-index: 2000;
            display: none;
            align-items: center;
            justify-content: center;
        }
        .flowexa-modal {
            background: white;
            width: 90%;
            max-width: 500px;
            border-radius: 24px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            max-height: 90vh;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        .modal-header {
            padding: 24px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .modal-body {
            padding: 24px;
            overflow-y: auto;
        }
        .modal-footer {
            padding: 24px;
            border-top: 1px solid #f1f5f9;
        }
        .cart-item {
            display: flex;
            gap: 16px;
            margin-bottom: 16px;
            padding-bottom: 16px;
            border-bottom: 1px solid #f8fafc;
        }
        .qty-ctrl {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #f1f5f9;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: 700;
        }
    </style>
</head>
<body>

    @php
        $content = $page->content ?? [];
        $templateHtml = $content['template_html'] ?? null;

        // Replace placeholders in content
        $contentStr = json_encode($content);
        $contentStr = str_replace('[[store_name]]', $store->store_name, $contentStr);
        $content = json_decode($contentStr, true);

        $elements = is_array($content) && isset($content[0]) ? $content : ($content['elements'] ?? []);
        $footer = $content['footer'] ?? null;
    @endphp

    <div class="storefront-wrapper">
        <main class="page-content" id="main-content">
            @if($templateHtml)
                {{-- Rendered from a real design template --}}
                {!! $templateHtml !!}
            @else
            @foreach($elements as $index => $el)
                <div class="canvas-el" style="
                    @foreach($el['styles'] ?? [] as $prop => $val)
                        {{ Str::kebab($prop) }}: {{ $val }}{{ is_numeric($val) && in_array($prop, ['top', 'left']) ? 'px' : '' }};
                    @endforeach
                    z-index: {{ $index + 1 }};
                ">
                    @if($el['type'] === 'navbar')
                        @php
                            $isMinimal = ($el['content']['style'] ?? '') === 'minimal';
                            $customer = auth()->guard('customer')->user();
                        @endphp
                        <div style="display:flex; align-items:center; justify-content:space-between; width:100%;">
                            <div style="font-size:1.5rem; font-weight:900; letter-spacing:-1px;">{{ $el['content']['logo'] }}</div>
                            <div style="display:flex; gap:30px; {{ $isMinimal ? 'background:rgba(255,255,255,0.1); padding:8px 24px; border-radius:20px; backdrop-filter:blur(10px);' : '' }}">
                                @foreach($store->pages()->where('is_published', true)->orderBy('sort_order')->get() as $p)
                                    <a href="{{ route('storefront.page', [$store->id, $p->id]) }}" style="font-size:0.9rem; font-weight:600; cursor:pointer; text-decoration:none; color:inherit;">{{ $p->page_name }}</a>
                                @endforeach
                            </div>
                            <div style="display:flex; gap:20px; align-items:center;">
                                @if(($el['content']['style'] ?? '') === 'retail')
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                @endif

                                <div class="dropdown">
                                    @if($customer)
                                        <a href="#" class="dropdown-toggle" style="color:inherit; text-decoration:none; display:flex; align-items:center; gap:8px;">
                                            <i class="fa-regular fa-user"></i>
                                            <span style="font-size:0.8rem; font-weight:600;">{{ $customer->first_name }}</span>
                                        </a>
                                    @else
                                        <a href="{{ route('storefront.login', $store->id) }}" style="color:inherit; text-decoration:none;"><i class="fa-regular fa-user"></i></a>
                                    @endif
                                </div>

                                <div style="position:relative; cursor:pointer;" onclick="toggleCart()">
                                    <i class="fa-solid fa-shopping-bag"></i>
                                    <span id="cart-count-badge" style="position:absolute; top:-8px; right:-8px; background:var(--primary); color:white; font-size:0.6rem; width:16px; height:16px; border-radius:50%; display:flex; align-items:center; justify-content:center;">0</span>
                                </div>
                            </div>
                        </div>
                    @elseif($el['type'] === 'filter_sidebar')
                        <div style="width:100%;">
                            <div style="margin-bottom:30px;">
                                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
                                    <h4 style="font-size:0.8rem; text-transform:uppercase; margin:0; color:#94a3b8;">Categories</h4>
                                    @if(request('category') || request('search'))
                                        <a href="{{ request()->url() }}" style="font-size:0.7rem; color:var(--primary); text-decoration:none; font-weight:700;">Clear All</a>
                                    @endif
                                </div>
                                @forelse($categories as $cat)
                                    <a href="{{ request()->fullUrlWithQuery(['category' => $cat->id]) }}" style="display:flex; align-items:center; justify-content:space-between; margin-bottom:12px; font-weight:600; font-size:0.9rem; text-decoration:none; color:{{ request('category') == $cat->id ? 'var(--primary)' : 'inherit' }};">
                                        <span>{{ $cat->name }}</span>
                                        <span style="opacity:0.3; font-weight:400;">{{ $cat->products_count ?? 0 }}</span>
                                    </a>
                                @empty
                                    @foreach($el['content']['categories'] ?? ['Electronics', 'Fashion', 'Home'] as $cat)
                                        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:12px; font-weight:600; font-size:0.9rem;">
                                            <span>{{ $cat }}</span>
                                            <span style="opacity:0.3; font-weight:400;">{{ rand(5, 20) }}</span>
                                        </div>
                                    @endforeach
                                @endforelse
                            </div>
                            <div>
                                <h4 style="font-size:0.8rem; text-transform:uppercase; margin-bottom:15px; color:#94a3b8;">Price Range</h4>
                                <form action="{{ request()->url() }}" method="GET">
                                    @if(request('category'))
                                        <input type="hidden" name="category" value="{{ request('category') }}">
                                    @endif
                                    @if(request('search'))
                                        <input type="hidden" name="search" value="{{ request('search') }}">
                                    @endif
                                    <div style="display:flex; align-items:center; gap:10px; margin-bottom:10px;">
                                        <input type="number" name="min_price" value="{{ request('min_price', 0) }}" min="0" max="{{ $maxStorePrice }}" style="width:100%; padding:8px; border:1px solid #e2e8f0; border-radius:6px; font-size:0.85rem;" placeholder="Min">
                                        <span>-</span>
                                        <input type="number" name="max_price" value="{{ request('max_price', $maxStorePrice) }}" min="0" max="{{ $maxStorePrice }}" style="width:100%; padding:8px; border:1px solid #e2e8f0; border-radius:6px; font-size:0.85rem;" placeholder="Max">
                                    </div>
                                    <button type="submit" style="width:100%; padding:8px; background:#f1f5f9; border:none; border-radius:6px; font-weight:700; font-size:0.8rem; cursor:pointer; color:var(--text-dark);">Apply</button>
                                </form>
                            </div>
                        </div>
                    @elseif($el['type'] === 'cart_sidebar')
                        <div style="width:100%; background:white; border:1px solid #eee; border-radius:16px; overflow:hidden; box-shadow:0 10px 25px rgba(0,0,0,0.05);">
                            <div style="padding:20px; border-bottom:1px solid #eee; display:flex; justify-content:space-between; align-items:center;">
                                <h4 style="margin:0;">{{ $el['content']['title'] }}</h4>
                                <span style="font-size:0.8rem; background:#f1f5f9; padding:2px 8px; border-radius:10px; font-weight:700;">2 Items</span>
                            </div>
                            <div style="padding:20px;">
                                <div style="display:flex; gap:15px; margin-bottom:20px;">
                                    <div style="width:60px; height:60px; background:#f8fafc; border-radius:8px;"></div>
                                    <div style="flex:1;">
                                        <div style="font-weight:700; font-size:0.85rem;">Premium Sneakers</div>
                                        <div style="font-size:0.75rem; color:#64748b;">Size: 42, Color: White</div>
                                        <div style="margin-top:5px; font-weight:800;">{{ $store->currency ?? 'GH₵' }} 199.00</div>
                                    </div>
                                </div>
                            </div>
                            <div style="padding:20px; background:#f8fafc;">
                                <div style="display:flex; justify-content:space-between; margin-bottom:15px; font-weight:700;">
                                    <span>Total</span>
                                    <span>{{ $store->currency ?? 'GH₵' }} 199.00</span>
                                </div>
                                <button class="flowexa-btn-primary" style="width:100%; border:none;">Checkout Now</button>
                            </div>
                        </div>
                    @elseif($el['type'] === 'heading')
                        <h2 style="margin:0; font-size:inherit; color:inherit; font-family:inherit; text-align:inherit; font-weight:inherit; line-height:inherit; letter-spacing:inherit;">{{ $el['content'] }}</h2>
                    @elseif($el['type'] === 'button')
                        <a href="#" class="flowexa-btn-primary" style="background:inherit; color:inherit; border-radius:inherit; padding:inherit; width:100%; border:none; text-align:center; font-family:inherit; font-weight:inherit; font-size:inherit; letter-spacing:inherit;">{{ $el['content'] }}</a>
                    @elseif($el['type'] === 'image')
                        <img src="{{ $el['content'] }}" style="max-width:100%; border-radius:inherit; display:block; height:inherit; object-fit:inherit;">
                    @elseif($el['type'] === 'divider')
                        <hr style="border:0; border-top:1px solid #e2e8f0; width:100%;">
                    @elseif($el['type'] === 'product_grid')
                        <div>
                            @if(!empty($el['content']['title']))
                                <h3 style="margin-bottom:20px; font-size:1.2rem;">{{ $el['content']['title'] }}</h3>
                            @endif
                            <div class="product-grid" style="grid-template-columns: repeat({{ $el['content']['columns'] ?? 4 }}, 1fr);">
                                @php
                                    $displayProducts = $products->take($el['content']['limit'] ?? 4);
                                @endphp
                                @forelse($displayProducts as $product)
                                    <div class="product-card" data-id="{{ $product->id }}">
                                        <div class="product-image">
                                            @if($product->images && isset($product->images[0]))
                                                <img src="{{ $product->imageUrl() }}" style="width:100%; height:100%; object-fit:cover;">
                                            @else
                                                <i class="fa-solid fa-image fa-2x"></i>
                                            @endif
                                        </div>
                                        <div class="product-info">
                                            <div class="product-title">{{ $product->name }}</div>
                                            <div class="product-price">{{ $store->currency ?? 'GH₵' }} {{ number_format($product->unit_price, 2) }}</div>
                                        </div>
                                    </div>
                                @empty
                                    @for($i=0; $i<($el['content']['limit'] ?? 4); $i++)
                                        <div class="product-card">
                                            <div class="product-image"><i class="fa-solid fa-image fa-2x"></i></div>
                                            <div class="product-info">
                                                <div class="product-title">Sample Product {{ $i+1 }}</div>
                                                <div class="product-price">{{ $store->currency ?? 'GH₵' }} 99.00</div>
                                            </div>
                                        </div>
                                    @endfor
                                @endforelse
                            </div>
                        </div>
                    @elseif($el['type'] === 'product_showcase')
                        @php
                            $isRight = ($el['content']['layout'] ?? 'left') === 'right';
                            $showcaseProduct = null;
                            if(!empty($el['content']['product_id'])) {
                                $showcaseProduct = $products->where('id', $el['content']['product_id'])->first();
                            }
                            if(!$showcaseProduct) {
                                $showcaseProduct = $products->first();
                            }
                        @endphp
                        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:40px; align-items:center; width:100%;">
                            @if($isRight)
                                <div>
                                    <div style="height:20px; width:40%; background:#f1f5f9; border-radius:4px; margin-bottom:15px; color:var(--primary); font-weight:800; font-size:0.8rem;">FEATURED</div>
                                    <h2 style="margin-bottom:20px;">{{ $showcaseProduct->name ?? 'Featured Product' }}</h2>
                                    <p style="color:#64748b; line-height:1.6; margin-bottom:30px;">{{ $showcaseProduct->description ?? 'This is a detailed description of the featured product showcasing its best qualities and features.' }}</p>
                                    <div style="font-size:1.5rem; font-weight:800; margin-bottom:20px;">{{ $store->currency ?? 'GH₵' }} {{ $showcaseProduct ? number_format($showcaseProduct->unit_price, 2) : '99.00' }}</div>
                                    <button class="flowexa-btn-primary">Buy Now</button>
                                </div>
                                <div style="aspect-ratio:1/1; background:#f8fafc; border-radius:12px; display:flex; align-items:center; justify-content:center; color:#cbd5e1; overflow:hidden;">
                                    @if($showcaseProduct && $showcaseProduct->images)
                                        <img src="{{ $showcaseProduct->imageUrl() }}" style="width:100%; height:100%; object-fit:cover;">
                                    @else
                                        <i class="fa-solid fa-image fa-4x"></i>
                                    @endif
                                </div>
                            @else
                                <div style="aspect-ratio:1/1; background:#f8fafc; border-radius:12px; display:flex; align-items:center; justify-content:center; color:#cbd5e1; overflow:hidden;">
                                    @if($showcaseProduct && $showcaseProduct->images)
                                        <img src="{{ $showcaseProduct->imageUrl() }}" style="width:100%; height:100%; object-fit:cover;">
                                    @else
                                        <i class="fa-solid fa-image fa-4x"></i>
                                    @endif
                                </div>
                                <div>
                                    <div style="height:20px; width:40%; background:#f1f5f9; border-radius:4px; margin-bottom:15px; color:var(--primary); font-weight:800; font-size:0.8rem;">FEATURED</div>
                                    <h2 style="margin-bottom:20px;">{{ $showcaseProduct->name ?? 'Featured Product' }}</h2>
                                    <p style="color:#64748b; line-height:1.6; margin-bottom:30px;">{{ $showcaseProduct->description ?? 'This is a detailed description of the featured product showcasing its best qualities and features.' }}</p>
                                    <div style="font-size:1.5rem; font-weight:800; margin-bottom:20px;">{{ $store->currency ?? 'GH₵' }} {{ $showcaseProduct ? number_format($showcaseProduct->unit_price, 2) : '99.00' }}</div>
                                    <button class="flowexa-btn-primary">Buy Now</button>
                                </div>
                            @endif
                        </div>
                    @elseif($el['type'] === 'cart_button')
                        <div style="display:inline-flex; align-items:center; gap:10px; background:white; border:1px solid #e2e8f0; border-radius:inherit; padding:10px 20px; color:#1e293b; font-weight:700;">
                            <i class="fa-solid fa-shopping-cart"></i>
                            <span>Cart (0)</span>
                        </div>
                    @elseif($el['type'] === 'category_list')
                        <div style="display:flex; gap:15px; overflow:auto; padding:10px 0;">
                            <a href="{{ request()->url() . (request('search') ? '?search='.request('search') : '') }}" style="text-decoration:none; white-space:nowrap; padding:6px 16px; background:{{ !request('category') ? 'var(--primary)' : '#f1f5f9' }}; color:{{ !request('category') ? 'white' : '#0f172a' }}; border-radius:20px; font-size:0.85rem; font-weight:600;">All</a>
                            @forelse($categories as $cat)
                                <a href="{{ request()->fullUrlWithQuery(['category' => $cat->id]) }}" style="text-decoration:none; white-space:nowrap; padding:6px 16px; background:{{ request('category') == $cat->id ? 'var(--primary)' : '#f1f5f9' }}; color:{{ request('category') == $cat->id ? 'white' : '#0f172a' }}; border-radius:20px; font-size:0.85rem; font-weight:600;">{{ $cat->name }}</a>
                            @empty
                                @foreach(['Electronics', 'Fashion', 'Home & Garden', 'Beauty', 'Sports'] as $cat)
                                    <span style="white-space:nowrap; padding:6px 16px; background:#f1f5f9; border-radius:20px; font-size:0.85rem; font-weight:600;">{{ $cat }}</span>
                                @endforeach
                            @endforelse
                        </div>
                    @elseif($el['type'] === 'search_bar')
                        <form action="{{ request()->url() }}" method="GET" style="position:relative; width:100%;">
                            @if(request('category'))
                                <input type="hidden" name="category" value="{{ request('category') }}">
                            @endif
                            <i class="fa-solid fa-magnifying-glass" style="position:absolute; left:15px; top:50%; transform:translateY(-50%); color:#94a3b8;"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ $el['content'] }}" style="width:100%; padding:12px 12px 12px 45px; border:1px solid #e2e8f0; border-radius:inherit; outline:none;">
                        </form>
                    @elseif($el['type'] === 'html_block')
                        {!! $el['content'] !!}
                    @else
                        {!! $el['content'] !!}
                    @endif
                </div>
            @endforeach
            @endif
        </main>

        @if($footer)
            <footer class="footer-section" style="
                @foreach($footer['styles'] ?? [] as $prop => $val)
                    {{ Str::kebab($prop) }}: {{ $val }};
                @endforeach
            ">
                @if($footer['template'] === 'simple')
                    <div style="text-align:center;">{{ $footer['content']['copyright'] ?? '' }}</div>
                @elseif($footer['template'] === 'standard')
                    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:40px;">
                        <div>
                            <h4 style="margin-bottom:15px; color:inherit;">{{ $footer['content']['aboutTitle'] ?? $store->store_name }}</h4>
                            <p style="opacity:0.8; line-height:1.6;">{{ $footer['content']['aboutText'] ?? '' }}</p>
                        </div>
                        @if(isset($footer['content']['links']) && count($footer['content']['links']) > 0)
                        <div>
                            <h4 style="margin-bottom:15px; color:inherit;">Quick Links</h4>
                            <ul style="list-style:none; padding:0; opacity:0.8;">
                                @foreach($footer['content']['links'] as $link)
                                    <li style="margin-bottom:8px;"><a href="{{ $link['url'] }}" style="color:inherit; text-decoration:none;">{{ $link['label'] }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                    <div style="margin-top:40px; padding-top:20px; border-top:1px solid rgba(255,255,255,0.1); text-align:center; opacity:0.6; font-size:0.8rem;">
                        {{ $footer['content']['copyright'] ?? '' }}
                    </div>
                @elseif($footer['template'] === 'minimal')
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <span style="font-weight:700;">{{ $store->store_name }}</span>
                        <div style="display:flex; gap:20px; opacity:0.8;">
                            @foreach($footer['content']['links'] ?? [] as $link)
                                <span><a href="{{ $link['url'] }}" style="color:inherit; text-decoration:none;">{{ $link['label'] }}</a></span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </footer>
        @endif
    </div>

    <!-- Cart Modal -->
    <div id="cart-modal" class="flowexa-modal-overlay" onclick="if(event.target === this) toggleCart()">
        <div class="flowexa-modal">
            <div class="modal-header">
                <h3 style="margin:0;">Your Bag</h3>
                <button onclick="toggleCart()" style="background:none; border:none; cursor:pointer;"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body" id="cart-items-list">
                <!-- Items injected by JS -->
            </div>
            <div class="modal-footer">
                <div style="display:flex; justify-content:space-between; margin-bottom:20px; font-weight:800; font-size:1.1rem;">
                    <span>Subtotal</span>
                    <span id="cart-subtotal">{{ $store->currency ?? 'GH₵' }} 0.00</span>
                </div>
                <button onclick="showCheckout()" class="flowexa-btn-primary" style="width:100%;">Checkout Now</button>
            </div>
        </div>
    </div>

    <!-- Checkout Modal -->
    <div id="checkout-modal" class="flowexa-modal-overlay" onclick="if(event.target === this) toggleCheckout()">
        <div class="flowexa-modal">
            <div class="modal-header">
                <button onclick="showCart()" style="background:none; border:none; cursor:pointer;"><i class="fa-solid fa-arrow-left"></i></button>
                <h3 style="margin:0;">Secure Checkout</h3>
                <button onclick="toggleCheckout()" style="background:none; border:none; cursor:pointer;"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <div style="margin-bottom:24px;">
                    <h4 style="font-size:0.8rem; text-transform:uppercase; color:#94a3b8; margin-bottom:12px;">Contact Info</h4>
                    <input type="email" placeholder="Email Address" style="width:100%; padding:12px; border:1px solid #e2e8f0; border-radius:12px; margin-bottom:12px;">
                </div>
                <div style="margin-bottom:24px;">
                    <h4 style="font-size:0.8rem; text-transform:uppercase; color:#94a3b8; margin-bottom:12px;">Payment Method</h4>
                    <div style="border:2px solid var(--primary); padding:16px; border-radius:12px; display:flex; justify-content:space-between; align-items:center;">
                        <div style="display:flex; align-items:center; gap:12px;">
                            <i class="fa-solid fa-credit-card"></i>
                            <span style="font-weight:700;">Credit / Debit Card</span>
                        </div>
                        <i class="fa-solid fa-circle-check" style="color:var(--primary);"></i>
                    </div>
                </div>
                <div style="background:#f8fafc; padding:16px; border-radius:12px;">
                    <div style="display:flex; justify-content:space-between; margin-bottom:8px; font-size:0.9rem;">
                        <span>Shipping</span>
                        <span style="font-weight:700;">FREE</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; font-weight:800; font-size:1.1rem;">
                        <span>Total</span>
                        <span id="checkout-total">GH₵ 0.00</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button onclick="processPayment()" id="pay-btn" class="flowexa-btn-primary" style="width:100%;">Pay Now</button>
            </div>
        </div>
    </div>

    <script>
        // Store Data
        const storeCurrency = '{{ $store->currency ?? 'GH₵' }}';
        let cart = [];

        // Global cart functions
        window.toggleCart = function() {
            const modal = document.getElementById('cart-modal');
            modal.style.display = modal.style.display === 'flex' ? 'none' : 'flex';
            renderCart();
        }

        window.toggleCheckout = function() {
            const modal = document.getElementById('checkout-modal');
            modal.style.display = modal.style.display === 'flex' ? 'none' : 'flex';
        }

        window.showCheckout = function() {
            if(cart.length === 0) return alert('Your bag is empty!');
            document.getElementById('cart-modal').style.display = 'none';
            document.getElementById('checkout-modal').style.display = 'flex';
            document.getElementById('checkout-total').innerText = document.getElementById('cart-subtotal').innerText;
        }

        window.showCart = function() {
            document.getElementById('checkout-modal').style.display = 'none';
            document.getElementById('cart-modal').style.display = 'flex';
        }

        window.addToCart = function(item) {
            cart.push(item);
            updateCartCount();
            toggleCart();
        }

        window.removeFromCart = function(idx) {
            cart.splice(idx, 1);
            updateCartCount();
            renderCart();
        }

        window.processPayment = function() {
            const btn = document.getElementById('pay-btn');
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Processing...';
            btn.disabled = true;

            const email = document.querySelector('input[type="email"]').value;
            if(!email) {
                alert('Please enter your email');
                btn.disabled = false;
                btn.innerHTML = 'Pay Now';
                return;
            }

            fetch('{{ route('storefront.checkout', $store->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    email: email,
                    items: cart.map(item => ({ id: item.id, qty: item.qty }))
                })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    btn.innerHTML = '<i class="fa-solid fa-check"></i> Success!';
                    setTimeout(() => {
                        alert(data.message);
                        cart = [];
                        updateCartCount();
                        toggleCheckout();
                        btn.innerHTML = 'Pay Now';
                        btn.disabled = false;
                    }, 1000);
                } else {
                    alert(data.message || 'Error processing payment');
                    btn.disabled = false;
                    btn.innerHTML = 'Pay Now';
                }
            })
            .catch(err => {
                console.error(err);
                alert('An error occurred. Please try again.');
                btn.disabled = false;
                btn.innerHTML = 'Pay Now';
            });
        }

        function updateCartCount() {
            document.querySelectorAll('#cart-count-badge, #cart-count').forEach(el => {
                el.innerText = cart.length;
            });
        }

        function renderCart() {
            const list = document.getElementById('cart-items-list');
            if(cart.length === 0) {
                list.innerHTML = '<div style="text-align:center; padding:40px; color:#64748b;"><i class="fa-solid fa-shopping-bag fa-3x" style="margin-bottom:16px; opacity:0.2;"></i><p>Your bag is empty</p></div>';
                document.getElementById('cart-subtotal').innerText = storeCurrency + '0.00';
                return;
            }

            let subtotal = 0;
            list.innerHTML = cart.map((item, idx) => {
                subtotal += item.price;
                return `
                    <div class="cart-item">
                        <div style="width:70px; height:70px; background:#f8fafc; border-radius:12px; flex-shrink:0;"></div>
                        <div style="flex:1;">
                            <div style="font-weight:700; font-size:1rem; margin-bottom:4px;">${item.name}</div>
                            <div style="font-weight:800; color:var(--primary);">${storeCurrency}${item.price.toFixed(2)}</div>
                        </div>
                        <div class="qty-ctrl">
                            <span onclick="removeFromCart(${idx})" style="cursor:pointer;"><i class="fa-solid fa-minus"></i></span>
                            <span>${item.qty}</span>
                            <span style="cursor:pointer;"><i class="fa-solid fa-plus"></i></span>
                        </div>
                    </div>
                `;
            }).join('');
            document.getElementById('cart-subtotal').innerText = storeCurrency + subtotal.toFixed(2);
        }

        // Dynamic Elements Setup
        document.addEventListener('DOMContentLoaded', () => {
            // Intercept cart icon clicks
            document.querySelectorAll('.fa-shopping-bag, .fa-shopping-cart, [data-type="cart_button"]').forEach(el => {
                const parent = el.closest('div, a');
                if(parent) {
                    parent.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        toggleCart();
                    });
                }
            });

            // Attach add to cart to product cards and buttons
            document.querySelectorAll('.flowexa-btn-primary, .product-card').forEach(el => {
                if(el.closest('.flowexa-modal')) return;

                el.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();

                    let name = 'Product Item';
                    let price = 99.00;

                    const card = el.closest('.product-card');
                    let id = el.dataset.id;
                    if(card) {
                        name = card.querySelector('.product-title')?.innerText || name;
                        const priceText = card.querySelector('.product-price')?.innerText || '';
                        price = parseFloat(priceText.replace(/[^0-9.]/g, '')) || price;
                        id = card.dataset.id || id;
                    }

                    if(!id) {
                        // For sample products in builder
                        id = Math.random().toString(36).substr(2, 9);
                    }

                    addToCart({
                        id: id,
                        name: name,
                        price: price,
                        qty: 1
                    });
                });
            });
        });

        // Adjust main content height based on elements
        function updateHeight() {
            const elements = document.querySelectorAll('.canvas-el');
            let maxBottom = 800;
            elements.forEach(el => {
                const bottom = el.offsetTop + el.offsetHeight;
                if (bottom > maxBottom) maxBottom = bottom;
            });
            const mainContent = document.getElementById('main-content');
            if(mainContent) mainContent.style.height = (maxBottom + 40) + 'px';
        }

        window.addEventListener('load', updateHeight);
        window.addEventListener('resize', updateHeight);

        document.querySelectorAll('img').forEach(img => {
            img.onload = updateHeight;
        });
    </script>
</body>
</html>
