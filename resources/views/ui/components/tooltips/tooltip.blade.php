@php
  $text = $text ?? 'Hover me';
  $tooltip = $tooltip ?? 'Tooltip info';
  $icon = $icon ?? null;
  $class = $class ?? '';
@endphp

<div class="flowexa-tooltip-container {{ $class }}">
  <button type="button" class="flowexa-tooltip-trigger">
    @if($icon)
      <i class="{{ $icon }}"></i>
    @endif
    <span>{{ $text }}</span>
  </button>
  <div role="tooltip" class="flowexa-tooltip">
    {!! $tooltip !!}
  </div>
</div>

<style>
.flowexa-tooltip-container {
  position: relative;
  display: inline-block;
}

.flowexa-tooltip-trigger {
  background: var(--surface-secondary);
  color: var(--text-primary);
  border: 1px solid var(--border);
  padding: 8px 16px;
  border-radius: 8px;
  cursor: pointer;
  font-size: 0.875rem;
  font-weight: 600;
  transition: all 0.2s ease;
  display: inline-flex;
  align-items: center;
  gap: 6px;
}

.flowexa-tooltip-trigger:hover {
  background: var(--surface);
  border-color: var(--primary);
  transform: translateY(-1px);
}

.flowexa-tooltip {
  position: absolute;
  bottom: calc(100% + 10px);
  left: 50%;
  transform: translateX(-50%) translateY(5px);
  padding: 8px 12px;
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: 8px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
  color: var(--text-primary);
  font-size: 0.8rem;
  white-space: nowrap;
  opacity: 0;
  visibility: hidden;
  transition: all 0.2s ease;
  z-index: 50;
  pointer-events: none;
}

.flowexa-tooltip::after {
  content: "";
  position: absolute;
  top: 100%;
  left: 50%;
  transform: translateX(-50%);
  border-width: 6px;
  border-style: solid;
  border-color: var(--border) transparent transparent transparent;
}

.flowexa-tooltip-container:hover .flowexa-tooltip {
  opacity: 1;
  visibility: visible;
  transform: translateX(-50%) translateY(0);
}
</style>
