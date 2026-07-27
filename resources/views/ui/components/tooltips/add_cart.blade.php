@php
  $price = $price ?? '$20.00';
  $text = $text ?? 'Add To Cart';
  $icon = $icon ?? 'fa-solid fa-cart-plus';
  $class = $class ?? '';
@endphp

<button type="button" class="flowexa-cart-btn {{ $class }}" data-tooltip="PRICE {{ $price }}">
  <div class="flowexa-cart-btn-wrapper">
    <div class="flowexa-cart-text">{{ $text }}</div>
    <span class="flowexa-cart-icon">
      <i class="{{ $icon }}"></i>
    </span>
  </div>
</button>

<style>
.flowexa-cart-btn {
  --width: 140px;
  --height: 42px;
  --tooltip-height: 35px;
  --tooltip-width: 110px;
  --gap-between-tooltip-to-button: 18px;
  width: var(--width);
  height: var(--height);
  background: var(--primary);
  border: none;
  cursor: pointer;
  position: relative;
  text-align: center;
  border-radius: 8px;
  font-family: inherit;
  transition: background 0.3s, transform 0.2s;
  box-shadow: 0 4px 6px rgba(0,0,0,0.05);
}

.flowexa-cart-btn::before {
  position: absolute;
  content: attr(data-tooltip);
  width: var(--tooltip-width);
  height: var(--tooltip-height);
  background-color: var(--text-primary);
  font-size: 0.8rem;
  font-weight: 700;
  color: var(--background);
  border-radius: 6px;
  line-height: var(--tooltip-height);
  bottom: calc(var(--height) + var(--gap-between-tooltip-to-button) + 10px);
  left: calc(50% - var(--tooltip-width) / 2);
  white-space: nowrap;
  box-shadow: 0 10px 15px rgba(0,0,0,0.1);
}

.flowexa-cart-btn::after {
  position: absolute;
  content: "";
  width: 0;
  height: 0;
  border: 8px solid transparent;
  border-top-color: var(--text-primary);
  left: calc(50% - 8px);
  bottom: calc(100% + var(--gap-between-tooltip-to-button) - 8px);
}

.flowexa-cart-btn::after,
.flowexa-cart-btn::before {
  opacity: 0;
  visibility: hidden;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  pointer-events: none;
}

.flowexa-cart-btn-wrapper {
  overflow: hidden;
  position: absolute;
  width: 100%;
  height: 100%;
  left: 0;
  top: 0;
  color: #fff;
}

.flowexa-cart-text {
  display: flex;
  align-items: center;
  justify-content: center;
  position: absolute;
  width: 100%;
  height: 100%;
  left: 0;
  top: 0;
  font-weight: 600;
  font-size: 0.9rem;
  transition: top 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.flowexa-cart-icon {
  position: absolute;
  width: 100%;
  height: 100%;
  left: 0;
  top: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.2rem;
  transition: top 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.flowexa-cart-btn:hover {
  background: var(--primary-hover);
  transform: translateY(-1px);
}

.flowexa-cart-btn:hover .flowexa-cart-text {
  top: -100%;
}

.flowexa-cart-btn:hover .flowexa-cart-icon {
  top: 0;
}

.flowexa-cart-btn:hover::before,
.flowexa-cart-btn:hover::after {
  opacity: 1;
  visibility: visible;
}

.flowexa-cart-btn:hover::after {
  bottom: calc(100% + var(--gap-between-tooltip-to-button) - 16px);
}

.flowexa-cart-btn:hover::before {
  bottom: calc(var(--height) + var(--gap-between-tooltip-to-button) - 4px);
}
</style>
