<x-layouts.app title="Point of Sale">
    <style>
        .pos-container {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 1.5rem;
            height: calc(100vh - 140px);
            overflow: hidden;
            position: relative;
        }

        /* Products Side */
        .pos-products-section {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            overflow: hidden;
        }

        .pos-categories-nav {
            display: flex;
            gap: 0.75rem;
            overflow-x: auto;
            padding-bottom: 0.5rem;
            scrollbar-width: none;
        }
        .pos-categories-nav::-webkit-scrollbar { display: none; }

        .category-chip {
            padding: 0.7rem 1.4rem;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            color: var(--text-secondary);
            font-weight: 600;
            white-space: nowrap;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.85rem;
        }
        .category-chip:hover { border-color: var(--primary); color: var(--primary); }
        .category-chip.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
            box-shadow: 0 4px 10px rgba(249,115,22,0.2);
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 1.25rem;
            overflow-y: auto;
            padding-right: 0.5rem;
            padding-bottom: 2rem;
        }

        .product-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 1.25rem;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            gap: 1rem;
            text-align: center;
            position: relative;
        }
        .product-card:hover {
            border-color: var(--primary);
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.06);
        }

        .product-card-img {
            width: 100%;
            aspect-ratio: 1;
            background: var(--surface-secondary);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            color: var(--text-secondary);
            overflow: hidden;
            border: 1px solid var(--divider);
        }

        .product-card-name {
            font-weight: 700;
            color: var(--headings);
            font-size: 0.95rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height: 2.6rem;
            line-height: 1.3;
        }

        .product-card-price {
            color: var(--primary);
            font-weight: 800;
            font-size: 1.2rem;
        }

        /* Session Overlay */
        .session-overlay {
            position: absolute;
            inset: 0;
            background: rgba(var(--background-rgb, 255, 255, 255), 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        /* Terminal Simulator */
        .terminal-sim {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 350px;
            background: #000;
            color: #4ade80;
            font-family: 'JetBrains Mono', 'Courier New', monospace;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
            display: none;
            flex-direction: column;
            z-index: 1000;
            border: 1px solid #333;
            overflow: hidden;
        }
        .terminal-header {
            background: #1a1a1a;
            padding: 10px 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #eee;
            font-size: 0.8rem;
            border-bottom: 1px solid #333;
        }
        .terminal-body {
            padding: 1.25rem;
            height: 380px;
            overflow-y: auto;
            font-size: 0.85rem;
            line-height: 1.5;
            scrollbar-width: thin;
            scrollbar-color: #333 #000;
        }

        @media (max-width: 1200px) {
            .pos-container { grid-template-columns: 1fr; overflow-y: auto; height: auto; }
            .pos-products-section { height: 600px; }
        }
    </style>

    <div class="flowexa-dashboard-container" style="padding: 1.5rem; height: 100vh; overflow: hidden;">

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h1 style="color: var(--headings); margin: 0; font-size: 1.75rem; font-weight: 800;">Point of Sale</h1>
                <p style="color: var(--text-secondary); margin: 0.25rem 0 0; font-size: 0.95rem;">
                    @if($activeSession)
                        Connected to <span style="color: var(--primary); font-weight: 700;">{{ $activeSession->device ? $activeSession->device->device_name : 'Manual Terminal' }}</span>
                        <span style="margin: 0 8px; opacity: 0.3;">|</span>
                        Session <span style="color: var(--headings); font-weight: 700;">#{{ substr($activeSession->id, 0, 6) }}</span>
                    @else
                        System ready. Please initialize a new session to begin.
                    @endif
                </p>
            </div>
            <div style="display: flex; gap: 0.75rem;">
                @if(in_array('pos.tables', $enabledFeatures))
                    <button type="button" class="flowexa-btn" onclick="openflowexaModal('tablesModal')" style="background: var(--surface); border: 1px solid var(--border); color: var(--text-primary); padding: 0.6rem 1.2rem; border-radius: 10px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-chair" style="font-size: 0.9rem;"></i> Tables
                    </button>
                @endif
                <button type="button" class="flowexa-btn" onclick="toggleTerminal()" style="background: var(--surface); border: 1px solid var(--border); color: var(--text-primary); padding: 0.6rem 1.2rem; border-radius: 10px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-terminal" style="font-size: 0.9rem;"></i> Console
                </button>
                <div style="width: 1px; background: var(--border); margin: 0 4px;"></div>
                <a href="{{ route('product_service.pos.sessions') }}" class="flowexa-btn" style="background: var(--surface); border: 1px solid var(--border); color: var(--text-primary); padding: 0.6rem 1.2rem; border-radius: 10px; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-history"></i> Log
                </a>
                @if($activeSession)
                    <button type="button" class="flowexa-btn" onclick="openflowexaModal('closeSessionModal')" style="background: var(--danger); border: none; color: white; padding: 0.6rem 1.2rem; border-radius: 10px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(239,68,68,0.2);">
                        <i class="fa-solid fa-power-off"></i> End Session
                    </button>
                @endif
            </div>
        </div>

        @if(!$activeSession)
            <div class="session-overlay">
                <div class="flowexa-card" style="width: 100%; max-width: 480px; padding: 3rem; background: var(--surface); border: 1px solid var(--border); border-radius: 32px; box-shadow: 0 30px 60px rgba(0,0,0,0.12); text-align: center; animation: modalUp 0.4s cubic-bezier(0.16, 1, 0.3, 1);">
                    <div style="width: 100px; height: 100px; background: rgba(249,115,22,0.1); border-radius: 30px; display: flex; align-items: center; justify-content: center; margin: 0 auto 2rem; font-size: 2.5rem; color: var(--primary);">
                        <i class="fa-solid fa-cash-register"></i>
                    </div>
                    <h2 style="color: var(--headings); margin: 0 0 0.75rem; font-size: 1.75rem; font-weight: 800;">Initialize Terminal</h2>
                    <p style="color: var(--text-secondary); margin-bottom: 2.5rem; font-size: 1rem; line-height: 1.6;">Welcome back! Choose your hardware configuration and set your starting float.</p>

                    <form action="{{ route('product_service.pos.session.open') }}" method="POST">
                        @csrf
                        <div style="text-align: left; margin-bottom: 1.75rem;">
                            <label style="display: block; font-size: 0.8rem; font-weight: 800; color: var(--text-secondary); margin-bottom: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Hardware Interface</label>
                            <select name="pos_device_id" class="flowexa-btn" style="width: 100%; text-align: left; background: var(--surface-secondary); border: 1px solid var(--border); padding: 1rem; border-radius: 14px; color: var(--text-primary); cursor: pointer; appearance: none; font-weight: 600; font-size: 1rem;">
                                <option value="">Manual Mode (Virtual Printer)</option>
                                @foreach($devices as $device)
                                    <option value="{{ $device->id }}">{{ $device->device_name }} — {{ ucfirst($device->status) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div style="text-align: left; margin-bottom: 2.5rem;">
                            <x-ui.input label="Opening Float (Cash)" name="opening_balance" type="number" step="0.01" value="0.00" required="true" />
                        </div>
                        <button type="submit" class="checkout-btn">
                            <i class="fa-solid fa-bolt"></i> Launch Terminal
                        </button>
                    </form>
                </div>
            </div>
            <style>@keyframes modalUp { from { opacity: 0; transform: translateY(40px); } to { opacity: 1; transform: translateY(0); } }</style>
        @endif

        <div class="pos-container">
            {{-- Products Section --}}
            <div class="pos-products-section">
                <div style="display: flex; gap: 1rem; align-items: center;">
                    <div style="flex-grow: 1;">
                        <x-ui.search placeholder="Scan barcode or type to search..." id="productSearch" oninput="filterProducts(this.value)" />
                    </div>
                </div>

                <div class="pos-categories-nav">
                    <div class="category-chip active" onclick="filterByCategory('all')">All Departments</div>
                    @foreach($categories as $category)
                        <div class="category-chip" onclick="filterByCategory('{{ $category->id }}')">{{ $category->name }}</div>
                    @endforeach
                </div>

                <div class="products-grid" id="productsGrid">
                    @foreach($products as $product)
                        <div class="product-card" data-category="{{ $product->category_id }}" data-name="{{ strtolower($product->name) }}" onclick="addToCart({{ json_encode($product) }})">
                            <div class="product-card-img">
                                @if($product->imageUrl())
                                    <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <i class="fa-solid fa-box-open" style="opacity: 0.4;"></i>
                                @endif
                            </div>
                            <div class="product-card-name">{{ $product->name }}</div>
                            <div class="product-card-price">GH₵ {{ number_format($product->unit_price, 2) }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Cart Component --}}
            <x-ui.pos.cart :customers="$customers" />
        </div>
    </div>

    {{-- Terminal Simulator --}}
    <div class="terminal-sim" id="terminalSim">
        <div class="terminal-header">
            <span style="display: flex; align-items: center; gap: 8px;">
                <span style="width: 8px; height: 8px; border-radius: 50%; background: #4ade80; display: inline-block; animation: pulse 2s infinite;"></span>
                flowexa TERMINAL V2.4
            </span>
            <i class="fa-solid fa-xmark" style="cursor: pointer; opacity: 0.6; hover: opacity: 1;" onclick="toggleTerminal()"></i>
        </div>
        <div class="terminal-body" id="terminalOutput">
            <span style="color: #888;">[SYS] System Kernel Booting...</span><br>
            <span style="color: #888;">[SYS] UI Components Loaded.</span><br>
            @if($activeSession)
                <span style="color: #fbbf24;">[SESSION] Active: #{{ substr($activeSession->id, 0, 8) }}</span><br>
                <span style="color: #60a5fa;">[HARDWARE] {{ $activeSession->device ? $activeSession->device->device_name : 'VIRTUAL_DEVICE_ENABLED' }}</span><br>
                <span style="color: #4ade80;">[SYS] Ready for input.</span><br>
            @endif
        </div>
    </div>
    <style>@keyframes pulse { 0% { opacity: 0.4; } 50% { opacity: 1; } 100% { opacity: 0.4; } }</style>

    @if($activeSession)
        <x-ui.modal id="closeSessionModal" title="Close Register">
            <form action="{{ route('product_service.pos.session.close') }}" method="POST" id="closeSessionForm">
                @csrf
                <div style="background: var(--surface-secondary); padding: 1.5rem; border-radius: 16px; margin-bottom: 1.5rem; border: 1px solid var(--border);">
                    <div class="summary-row" style="margin-bottom: 0.5rem;"><span>Opening Float:</span> <span style="font-weight: 700;">GH₵ {{ number_format($activeSession->opening_balance, 2) }}</span></div>
                    <div class="summary-row" style="margin-bottom: 0.5rem;"><span>Cash Transactions:</span> <span style="font-weight: 700; color: var(--success);">+GH₵ {{ number_format($activeSession->cash_sales, 2) }}</span></div>
                    <div class="summary-row" style="margin-bottom: 0.5rem;"><span>Digital Payments:</span> <span style="font-weight: 700; color: #2563eb;">+GH₵ {{ number_format($activeSession->card_sales, 2) }}</span></div>
                    <div style="height: 1px; background: var(--border); margin: 0.75rem 0;"></div>
                    <div class="summary-row" style="font-size: 1.1rem; color: var(--headings); font-weight: 800;">
                        <span>Expected Drawer Cash:</span>
                        <span>GH₵ {{ number_format($activeSession->expected_cash, 2) }}</span>
                    </div>
                </div>
                <x-ui.input label="Actual Counted Cash" name="actual_cash" type="number" step="0.01" placeholder="0.00" required="true" />
                <p style="margin-top: 1rem; font-size: 0.8rem; color: var(--text-secondary); line-height: 1.5;">
                    * Closing the session will reconcile all sales for the current period and sync inventory movements.
                </p>
            </form>
            <x-slot:footer>
                <button type="button" class="flowexa-btn" onclick="closeflowexaModal('closeSessionModal')" style="padding: 0.75rem 1.5rem; background: var(--surface-secondary); border: 1px solid var(--border); border-radius: 12px; cursor: pointer; font-weight: 600;">Cancel</button>
                <button type="button" class="flowexa-btn" onclick="document.getElementById('closeSessionForm').submit()" style="padding: 0.75rem 1.5rem; background: var(--danger); color: white; border: none; border-radius: 12px; font-weight: 700; cursor: pointer; box-shadow: 0 4px 12px rgba(239,68,68,0.2);">Verify & Reconcile</button>
            </x-slot:footer>
        </x-ui.modal>
    @endif

    @if(in_array('pos.tables', $enabledFeatures))
        <x-ui.modal id="tablesModal" title="Restaurant Tables" size="lg">
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 1rem; padding: 1rem;">
                @forelse($tables as $table)
                    <div style="background: {{ $table->status === 'available' ? 'var(--surface-secondary)' : 'rgba(239,68,68,0.1)' }}; border: 1px solid {{ $table->status === 'available' ? 'var(--border)' : 'var(--danger)' }}; padding: 1.5rem; border-radius: 16px; text-align: center; cursor: pointer;">
                        <i class="fa-solid fa-chair" style="font-size: 1.5rem; margin-bottom: 0.5rem; color: {{ $table->status === 'available' ? 'var(--text-secondary)' : 'var(--danger)' }};"></i>
                        <div style="font-weight: 700; color: var(--headings);">{{ $table->name }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-secondary);">Cap: {{ $table->capacity }}</div>
                    </div>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 2rem; color: var(--text-secondary);">
                        No tables configured. Add tables in settings.
                    </div>
                @endforelse
            </div>
        </x-ui.modal>
    @endif

    {{-- Hidden Checkout Form --}}
    <form id="checkoutForm" action="{{ route('product_service.pos.checkout') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="customer_id" id="formCustomerId">
        <input type="hidden" name="payment_method" id="formPaymentMethod" value="cash">
        <input type="hidden" name="paid_amount" id="formPaidAmount">
        <div id="formItems"></div>
    </form>

    <script>
        let cart = [];
        let paymentMethod = 'cash';

        function terminalLog(msg, color = '#4ade80') {
            const output = document.getElementById('terminalOutput');
            const time = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit', second:'2-digit'});
            output.innerHTML += `<span style="color: #666;">[${time}]</span> <span style="color: ${color};">${msg}</span><br>`;
            output.scrollTop = output.scrollHeight;
        }

        function toggleTerminal() {
            const el = document.getElementById('terminalSim');
            el.style.display = el.style.display === 'flex' ? 'none' : 'flex';
        }

        function filterByCategory(categoryId) {
            document.querySelectorAll('.category-chip').forEach(chip => {
                const isActive = (categoryId === 'all' && chip.textContent.toLowerCase().includes('all')) ||
                                 (categoryId !== 'all' && chip.onclick.toString().includes(categoryId));
                chip.classList.toggle('active', isActive);
            });

            document.querySelectorAll('.product-card').forEach(card => {
                if (categoryId === 'all' || card.dataset.category == categoryId) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        function filterProducts(query) {
            query = query.toLowerCase();
            document.querySelectorAll('.product-card').forEach(card => {
                const matches = card.dataset.name.includes(query);
                card.style.display = matches ? 'flex' : 'none';
            });
        }

        function addToCart(product) {
            const existing = cart.find(item => item.id === product.id);
            if (existing) {
                existing.quantity++;
            } else {
                cart.push({
                    id: product.id,
                    name: product.name,
                    price: parseFloat(product.unit_price),
                    quantity: 1
                });
            }
            terminalLog(`+ Add to basket: ${product.name} @ GH₵ ${product.unit_price}`, '#4ade80');
            renderCart();
        }

        function updateQty(productId, delta) {
            const item = cart.find(i => i.id === productId);
            if (item) {
                item.quantity += delta;
                if (item.quantity <= 0) {
                    terminalLog(`- Removed: ${item.name}`, '#f87171');
                    cart = cart.filter(i => i.id !== productId);
                } else {
                    terminalLog(`~ Update Qty: ${item.name} x${item.quantity}`, '#60a5fa');
                }
                renderCart();
            }
        }

        function clearCart() {
            if(cart.length > 0) {
                cart = [];
                terminalLog('! Manual Reset: Transaction cleared', '#f87171');
                renderCart();
            }
        }

        function renderCart() {
            const container = document.getElementById('cartItems');
            if (cart.length === 0) {
                container.innerHTML = `<div style="text-align: center; color: var(--text-secondary); padding-top: 4rem;">
                    <i class="fa-solid fa-shopping-basket" style="font-size: 2.5rem; display: block; margin-bottom: 1rem; opacity: 0.2;"></i>
                    <p style="font-weight: 600; font-size: 0.85rem;">Cart is empty</p>
                </div>`;
                updateSummary(0);
                return;
            }

            let html = '';
            let total = 0;
            cart.forEach(item => {
                const lineTotal = item.price * item.quantity;
                total += lineTotal;
                html += `
                    <div class="cart-item">
                        <div class="cart-item-info">
                            <div class="cart-item-name">${item.name}</div>
                            <div class="cart-item-price">GH₵ ${item.price.toFixed(2)} / unit</div>
                            <div class="cart-item-qty">
                                <button type="button" class="qty-btn" onclick="updateQty('${item.id}', -1)"><i class="fa-solid fa-minus"></i></button>
                                <span style="font-weight: 800; font-size: 0.85rem; color: var(--headings); min-width: 18px; text-align: center;">${item.quantity}</span>
                                <button type="button" class="qty-btn" onclick="updateQty('${item.id}', 1)"><i class="fa-solid fa-plus"></i></button>
                            </div>
                        </div>
                        <div style="text-align: right; display: flex; flex-direction: column; justify-content: flex-end;">
                            <div style="font-weight: 800; font-size: 1rem; color: var(--headings);">GH₵ ${lineTotal.toFixed(2)}</div>
                        </div>
                    </div>
                `;
            });
            container.innerHTML = html;
            updateSummary(total);
        }

        function updateSummary(total) {
            document.getElementById('summarySubtotal').textContent = `GH₵ ${total.toFixed(2)}`;
            document.getElementById('summaryDiscount').textContent = `-GH₵ 0.00`;
            document.getElementById('summaryTotal').textContent = `GH₵ ${total.toFixed(2)}`;
        }

        function setPaymentMethod(method) {
            paymentMethod = method;
            terminalLog(`Method Update: ${method.toUpperCase()}`, '#a855f7');
            document.querySelectorAll('.payment-method-btn').forEach(btn => btn.classList.remove('active'));
            document.getElementById(`${method}Btn`).classList.add('active');
        }

        function processCheckout() {
            if (cart.length === 0) {
                alert('Cart is empty!');
                return;
            }

            const total = cart.reduce((acc, item) => acc + (item.price * item.quantity), 0);

            terminalLog('>> Initiating checkout flow...', '#fb923c');
            terminalLog(`>> Total due: GH₵ ${total.toFixed(2)}`);
            terminalLog(`>> Gateway: ${paymentMethod.toUpperCase()}`);

            @if($activeSession && $activeSession->device)
                terminalLog('>> Contacting hardware controller...');
                setTimeout(() => terminalLog(`>> Printing ESC/POS payload to IP: ${'{{ $activeSession->device->ip_address }}'}`), 600);
            @else
                terminalLog('>> Manual operation: Printing to virtual driver.');
            @endif

            setTimeout(() => {
                terminalLog('>> Success! Record synced to ledger.', '#4ade80');

                // Fill hidden form
                document.getElementById('formCustomerId').value = document.getElementById('customerSelect').value;
                document.getElementById('formPaymentMethod').value = paymentMethod;
                document.getElementById('formPaidAmount').value = total;

                const itemsContainer = document.getElementById('formItems');
                itemsContainer.innerHTML = '';
                cart.forEach((item, index) => {
                    itemsContainer.innerHTML += `
                        <input type="hidden" name="items[${index}][product_id]" value="${item.id}">
                        <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}">
                        <input type="hidden" name="items[${index}][unit_price]" value="${item.price}">
                    `;
                });

                document.getElementById('checkoutForm').submit();
            }, 1000);
        }
    </script>
</x-layouts.app>
