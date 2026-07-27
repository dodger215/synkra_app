@props(['customers' => []])

<div class="pos-cart-section">
    <div class="cart-header">
        <h3 style="margin: 0; font-size: 0.95rem; color: var(--headings); font-weight: 700;">Current Order</h3>
        <button type="button" onclick="clearCart()" style="background: none; border: none; color: var(--danger); font-size: 0.75rem; font-weight: 600; cursor: pointer;">Clear All</button>
    </div>

    <div style="padding: 1rem 1.25rem 0.5rem;">
        <label style="display: block; font-size: 0.65rem; font-weight: 800; color: var(--text-secondary); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.03em;">Customer</label>
        <select id="customerSelect" class="flowexa-btn" style="width: 100%; text-align: left; background: var(--surface-secondary); border: 1px solid var(--border); padding: 0.6rem; border-radius: 10px; color: var(--text-primary); cursor: pointer; font-size: 0.85rem; font-weight: 600;">
            <option value="">Walk-in Customer</option>
            @foreach($customers as $customer)
                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="cart-items" id="cartItems">
        <div style="text-align: center; color: var(--text-secondary); padding-top: 4rem;">
            <i class="fa-solid fa-shopping-basket" style="font-size: 2.5rem; display: block; margin-bottom: 1rem; opacity: 0.2;"></i>
            <p style="font-weight: 600; font-size: 0.85rem;">Cart is empty</p>
        </div>
    </div>

    <div class="cart-summary">
        {{-- Toggleable Details --}}
        <div id="summaryDetails" style="display: none; flex-direction: column; gap: 0.4rem; margin-bottom: 0.75rem; padding-bottom: 0.75rem; border-bottom: 1px dashed var(--border);">
            <div class="summary-row">
                <span>Subtotal</span>
                <span id="summarySubtotal">GH₵ 0.00</span>
            </div>
            <div class="summary-row" style="color: var(--danger);">
                <span>Discount</span>
                <span id="summaryDiscount">-GH₵ 0.00</span>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center;">
            <button type="button" onclick="toggleSummaryDetails()" style="background: var(--surface); border: 1px solid var(--border); border-radius: 6px; padding: 4px 8px; font-size: 0.7rem; font-weight: 700; color: var(--text-secondary); cursor: pointer; display: flex; align-items: center; gap: 4px;">
                <i class="fa-solid fa-chevron-up" id="summaryToggleIcon"></i> Details
            </button>
            <div class="summary-row total" style="margin: 0; padding: 0; border: none; font-size: 1.2rem;">
                <span id="summaryTotal">GH₵ 0.00</span>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.6rem; margin-top: 1rem;">
            <button type="button" class="payment-method-btn active" onclick="setPaymentMethod('cash')" id="cashBtn">
                <i class="fa-solid fa-money-bill-wave"></i> Cash
            </button>
            <button type="button" class="payment-method-btn" onclick="setPaymentMethod('card')" id="cardBtn">
                <i class="fa-solid fa-credit-card"></i> Card
            </button>
        </div>

        <button type="button" class="checkout-btn" style="margin-top: 0.85rem;" onclick="processCheckout()">
            <i class="fa-solid fa-check-circle"></i> Complete Order
        </button>
    </div>
</div>

<script>
    function toggleSummaryDetails() {
        const details = document.getElementById('summaryDetails');
        const icon = document.getElementById('summaryToggleIcon');
        if (details.style.display === 'none') {
            details.style.display = 'flex';
            icon.classList.replace('fa-chevron-up', 'fa-chevron-down');
        } else {
            details.style.display = 'none';
            icon.classList.replace('fa-chevron-down', 'fa-chevron-up');
        }
    }
</script>

<style>
    .pos-cart-section {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 20px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
    }

    .cart-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--divider);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .cart-items {
        flex-grow: 1;
        overflow-y: auto;
        padding: 1rem 1.25rem;
        display: flex;
        flex-direction: column;
        gap: 0.85rem;
    }

    .cart-item {
        display: flex;
        gap: 0.75rem;
        padding-bottom: 0.85rem;
        border-bottom: 1px solid var(--divider);
        animation: cartSlideIn 0.2s ease-out;
    }

    @keyframes cartSlideIn {
        from { opacity: 0; transform: translateX(5px); }
        to { opacity: 1; transform: translateX(0); }
    }

    .cart-item-info { flex-grow: 1; }
    .cart-item-name { font-weight: 700; font-size: 0.85rem; color: var(--headings); margin-bottom: 0.15rem; }
    .cart-item-price { font-size: 0.75rem; color: var(--text-secondary); }

    .cart-item-qty {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        background: var(--surface-secondary);
        padding: 0.25rem 0.5rem;
        border-radius: 8px;
        margin-top: 0.4rem;
    }
    .qty-btn {
        border: none;
        background: var(--surface);
        color: var(--text-primary);
        cursor: pointer;
        width: 22px;
        height: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 5px;
        font-size: 0.65rem;
        transition: all 0.2s;
        border: 1px solid var(--border);
    }
    .qty-btn:hover { background: var(--primary); color: white; border-color: var(--primary); }

    .cart-summary {
        padding: 1.25rem;
        background: var(--surface-secondary);
        border-top: 1px solid var(--border);
        display: flex;
        flex-direction: column;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        color: var(--text-secondary);
        font-size: 0.8rem;
        font-weight: 600;
    }
    .summary-row.total {
        color: var(--headings);
        font-weight: 800;
        font-size: 1.25rem;
    }

    .payment-method-btn {
        padding: 0.75rem;
        border: 1.5px solid var(--border);
        background: var(--surface);
        color: var(--text-secondary);
        border-radius: 12px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        transition: all 0.2s;
        font-size: 0.8rem;
    }
    .payment-method-btn.active {
        border-color: var(--primary);
        background: rgba(249,115,22,0.08);
        color: var(--primary);
    }

    .checkout-btn {
        width: 100%;
        background: var(--primary);
        color: white;
        border: none;
        padding: 1rem;
        border-radius: 14px;
        font-weight: 800;
        font-size: 1rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.6rem;
        transition: all 0.2s;
        box-shadow: 0 4px 10px rgba(249,115,22,0.15);
    }
    .checkout-btn:hover {
        background: var(--primary-hover);
        transform: translateY(-1px);
        box-shadow: 0 6px 15px rgba(249,115,22,0.25);
    }
</style>
