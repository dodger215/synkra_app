@php
  $name = $name ?? 'quantity';
  $id = $id ?? 'quantity_' . uniqid();
  $label = $label ?? 'Quantity';
  $min = $min ?? 1;
  $max = $max ?? 99999;
  $value = $value ?? 1;
  $class = $class ?? '';
@endphp

<div class="synkra-qty-wrapper {{ $class }}">
  <label for="{{ $id }}" class="synkra-qty-label">{{ $label }}</label>
  <div class="synkra-qty-controls">
    <button
      type="button"
      class="synkra-qty-btn synkra-qty-decrement"
      onclick="document.getElementById('{{ $id }}').stepDown(); document.getElementById('{{ $id }}').dispatchEvent(new Event('change'))"
      aria-label="Decrease quantity"
    >
      <i class="fa-solid fa-minus"></i>
    </button>
    <input
      type="number"
      name="{{ $name }}"
      id="{{ $id }}"
      min="{{ $min }}"
      max="{{ $max }}"
      value="{{ $value }}"
      class="synkra-qty-input"
      required
    />
    <button
      type="button"
      class="synkra-qty-btn synkra-qty-increment"
      onclick="document.getElementById('{{ $id }}').stepUp(); document.getElementById('{{ $id }}').dispatchEvent(new Event('change'))"
      aria-label="Increase quantity"
    >
      <i class="fa-solid fa-plus"></i>
    </button>
  </div>
</div>

<style>
.synkra-qty-wrapper {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  width: fit-content;
}

.synkra-qty-label {
  font-size: 0.875rem;
  font-weight: 600;
  color: var(--headings);
}

.synkra-qty-controls {
  display: flex;
  align-items: center;
  border: 1px solid var(--border);
  border-radius: 8px;
  overflow: hidden;
  background-color: var(--inputs-bg);
  width: fit-content;
}

.synkra-qty-btn {
  background-color: var(--surface-secondary);
  border: none;
  color: var(--text-secondary);
  width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  font-size: 0.875rem;
  transition: all 0.2s ease;
}

.synkra-qty-btn:hover {
  background-color: var(--border);
  color: var(--text-primary);
}

.synkra-qty-btn:active {
  background-color: var(--divider);
}

.synkra-qty-input {
  width: 60px;
  height: 40px;
  border: none;
  border-left: 1px solid var(--border);
  border-right: 1px solid var(--border);
  text-align: center;
  font-size: 0.95rem;
  font-weight: 600;
  color: var(--text-primary);
  background-color: var(--inputs-bg);
  outline: none;
  -moz-appearance: textfield;
}

.synkra-qty-input::-webkit-outer-spin-button,
.synkra-qty-input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
</style>
