@php
  $name = $name ?? 'menu_open';
  $id = $id ?? 'menu_' . uniqid();
  $checked = ($checked ?? false) ? 'checked' : '';
  $onChange = $onChange ?? '';
  $class = $class ?? '';
@endphp

<label class="flowexa-hamburger {{ $class }}" for="{{ $id }}">
  <input
    type="checkbox"
    name="{{ $name }}"
    id="{{ $id }}"
    {{ $checked }}
    @if($onChange) onchange="{{ $onChange }}" @endif
  >
  <svg viewBox="0 0 32 32">
    <path class="line line-top-bottom" d="M27 10 13 10C10.8 10 9 8.2 9 6 9 3.5 10.8 2 13 2 15.2 2 17 3.8 17 6L17 26C17 28.2 18.8 30 21 30 23.2 30 25 28.2 25 26 25 23.8 23.2 22 21 22L7 22"></path>
    <path class="line" d="M7 16 27 16"></path>
  </svg>
</label>

<style>
.flowexa-hamburger {
  cursor: pointer;
  display: inline-block;
  width: 36px;
  height: 36px;
}

.flowexa-hamburger input {
  display: none;
}

.flowexa-hamburger svg {
  width: 100%;
  height: 100%;
  transition: transform 600ms cubic-bezier(0.4, 0, 0.2, 1);
}

.flowexa-hamburger .line {
  fill: none;
  stroke: var(--text-primary);
  stroke-linecap: round;
  stroke-linejoin: round;
  stroke-width: 3;
  transition: stroke-dasharray 600ms cubic-bezier(0.4, 0, 0.2, 1),
              stroke-dashoffset 600ms cubic-bezier(0.4, 0, 0.2, 1);
}

.flowexa-hamburger .line-top-bottom {
  stroke-dasharray: 12 63;
}

.flowexa-hamburger input:checked + svg {
  transform: rotate(-45deg);
}

.flowexa-hamburger input:checked + svg .line-top-bottom {
  stroke-dasharray: 20 300;
  stroke-dashoffset: -32.42;
}
</style>
