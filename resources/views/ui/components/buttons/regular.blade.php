@php
  $type = $type ?? 'button';
  $variant = $variant ?? 'primary'; // primary, secondary, success, danger, warning
  $text = $text ?? 'Button';
  $icon = $icon ?? 'fa-solid fa-plus';
  $id = $id ?? 'btn_' . uniqid();
  $isLoading = $isLoading ?? false;
  $loadingText = $loadingText ?? 'Loading...';
  $onClick = $onClick ?? '';
  $class = $class ?? '';
  $fullWidth = $fullWidth ?? false;
@endphp

<button
  type="{{ $type }}"
  id="{{ $id }}"
  {{ $attributes ?? '' }}
  class="flowexa-btn flowexa-btn-{{ $variant }} {{ $isLoading ? 'flowexa-btn-loading' : '' }} {{ $class }} {{ $fullWidth ? 'flowexa-btn-full' : '' }}"
  @if($onClick) onclick="{{ $onClick }}" @endif
  {{ $isLoading ? 'disabled' : '' }}
  @if($fullWidth)
    style="width: 100%;"
  @endif
>
  <span class="flowexa-btn-content">
    @if($icon)
      <i class="{{ $icon }} flowexa-btn-icon flowexa-btn-icon-normal"></i>
    @endif
    <i class="fa-solid fa-circle-notch fa-spin flowexa-btn-icon flowexa-btn-icon-spinner"></i>

    <span class="flowexa-btn-text-normal">{{ $slot->isEmpty() ? $text : $slot }}</span>
    <span class="flowexa-btn-text-loading">{{ $loadingText }}</span>
  </span>
</button>

<script>
if (typeof toggleflowexaBtnLoading !== 'function') {
  function toggleflowexaBtnLoading(btnId, isLoading) {
    const btn = document.getElementById(btnId);
    if (!btn) return;

    if (isLoading) {
      btn.classList.add('flowexa-btn-loading');
      btn.setAttribute('disabled', 'true');
    } else {
      btn.classList.remove('flowexa-btn-loading');
      btn.removeAttribute('disabled');
    }
  }
}
</script>

<style>
.flowexa-btn {
  border: 2px solid transparent;
  border-radius: 0.75em;
  cursor: pointer;
  padding: 0.6em 1.2em;
  transition: all 0.2s ease-in-out;
  font-size: 15px;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-family: inherit;
  outline: none;
  position: relative;
  overflow: hidden;
}

.flowexa-btn-content {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5em;
}

.flowexa-btn-icon {
  font-size: 1.1em;
}

.flowexa-btn-icon-spinner,
.flowexa-btn-text-loading {
  display: none;
}

/* Loading State Styles */
.flowexa-btn-loading {
  cursor: not-allowed;
  opacity: 0.85;
}

.flowexa-btn-loading .flowexa-btn-icon-normal,
.flowexa-btn-loading .flowexa-btn-text-normal {
  display: none;
}

.flowexa-btn-loading .flowexa-btn-icon-spinner,
.flowexa-btn-loading .flowexa-btn-text-loading {
  display: inline-block;
}

/* Primary Variant */
.flowexa-btn-primary {
  background-color: var(--primary);
  border-color: var(--primary);
  color: #ffffff;
}
.flowexa-btn-primary:hover:not(:disabled) {
  background-color: var(--primary-hover);
  border-color: var(--primary-hover);
  transform: translateY(-1px);
}

/* Secondary Variant */
.flowexa-btn-secondary {
  background-color: var(--secondary);
  border-color: var(--secondary);
  color: #ffffff;
}
.flowexa-btn-secondary:hover:not(:disabled) {
  background-color: var(--secondary-hover);
  border-color: var(--secondary-hover);
  transform: translateY(-1px);
}

/* Success Variant */
.flowexa-btn-success {
  background-color: var(--success);
  border-color: var(--success);
  color: #ffffff;
}
.flowexa-btn-success:hover:not(:disabled) {
  filter: brightness(0.9);
  transform: translateY(-1px);
}

/* Danger Variant */
.flowexa-btn-danger {
  background-color: var(--danger);
  border-color: var(--danger);
  color: #ffffff;
}
.flowexa-btn-danger:hover:not(:disabled) {
  filter: brightness(0.9);
  transform: translateY(-1px);
}

/* Warning Variant */
.flowexa-btn-warning {
  background-color: var(--warning);
  border-color: var(--warning);
  color: #0F172A;
}
.flowexa-btn-warning:hover:not(:disabled) {
  filter: brightness(0.9);
  transform: translateY(-1px);
}
</style>
