@php
  $variant = $variant ?? 'primary'; // primary, secondary, success, warning, danger, info, neutral
  $text = $text ?? 'Badge';
  $pill = $pill ?? false;
  $icon = $icon ?? null;
  $class = $class ?? '';
@endphp

<span class="flowexa-badge flowexa-badge-{{ $variant }} {{ $pill ? 'flowexa-badge-pill' : '' }} {{ $class }}">
  @if($icon)
    <i class="{{ $icon }} flowexa-badge-icon"></i>
  @endif
  <span>{{ $slot->isEmpty() ? $text : $slot }}</span>
</span>

<style>
.flowexa-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.25rem;
  padding: 0.25rem 0.6rem;
  font-size: 0.75rem;
  font-weight: 700;
  border-radius: 4px;
  line-height: 1;
  width: fit-content;
}

.flowexa-badge-pill {
  border-radius: 9999px;
}

.flowexa-badge-icon {
  font-size: 0.8em;
}

/* Primary Badge */
.flowexa-badge-primary {
  background-color: rgba(249, 115, 22, 0.1);
  color: var(--primary);
}

/* Secondary Badge */
.flowexa-badge-secondary {
  background-color: rgba(20, 184, 166, 0.1);
  color: var(--secondary);
}

/* Success Badge */
.flowexa-badge-success {
  background-color: rgba(34, 197, 94, 0.1);
  color: #166534;
}
[data-theme="dark"] .flowexa-badge-success {
  color: #4ade80;
}

/* Warning Badge */
.flowexa-badge-warning {
  background-color: rgba(245, 158, 11, 0.1);
  color: #9a3412;
}
[data-theme="dark"] .flowexa-badge-warning {
  color: #fbbf24;
}

/* Danger Badge */
.flowexa-badge-danger {
  background-color: rgba(239, 68, 68, 0.1);
  color: #991b1b;
}
[data-theme="dark"] .flowexa-badge-danger {
  color: #f87171;
}

/* Info Badge */
.flowexa-badge-info {
  background-color: rgba(14, 165, 233, 0.1);
  color: #0369a1;
}
[data-theme="dark"] .flowexa-badge-info {
  color: #38bdf8;
}

/* Neutral Badge */
.flowexa-badge-neutral {
  background-color: var(--surface-secondary);
  color: var(--text-secondary);
  border: 1px solid var(--border);
}
</style>
