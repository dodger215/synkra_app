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
  class="synkra-btn synkra-btn-{{ $variant }} {{ $isLoading ? 'synkra-btn-loading' : '' }} {{ $class }} {{ $fullWidth ? 'synkra-btn-full' : '' }}"
  @if($onClick) onclick="{{ $onClick }}" @endif
  {{ $isLoading ? 'disabled' : '' }}
  @if($fullWidth)
    style="width: 100%;"
  @endif
>
  <span class="synkra-btn-content">
    @if($icon)
      <i class="{{ $icon }} synkra-btn-icon synkra-btn-icon-normal"></i>
    @endif
    <i class="fa-solid fa-circle-notch fa-spin synkra-btn-icon synkra-btn-icon-spinner"></i>
    
    <span class="synkra-btn-text-normal">{{ $slot->isEmpty() ? $text : $slot }}</span>
    <span class="synkra-btn-text-loading">{{ $loadingText }}</span>
  </span>
</button>

<script>
if (typeof toggleSynkraBtnLoading !== 'function') {
  function toggleSynkraBtnLoading(btnId, isLoading) {
    const btn = document.getElementById(btnId);
    if (!btn) return;
    
    if (isLoading) {
      btn.classList.add('synkra-btn-loading');
      btn.setAttribute('disabled', 'true');
    } else {
      btn.classList.remove('synkra-btn-loading');
      btn.removeAttribute('disabled');
    }
  }
}
</script>

<style>
.synkra-btn {
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

.synkra-btn-content {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5em;
}

.synkra-btn-icon {
  font-size: 1.1em;
}

.synkra-btn-icon-spinner,
.synkra-btn-text-loading {
  display: none;
}

/* Loading State Styles */
.synkra-btn-loading {
  cursor: not-allowed;
  opacity: 0.85;
}

.synkra-btn-loading .synkra-btn-icon-normal,
.synkra-btn-loading .synkra-btn-text-normal {
  display: none;
}

.synkra-btn-loading .synkra-btn-icon-spinner,
.synkra-btn-loading .synkra-btn-text-loading {
  display: inline-block;
}

/* Primary Variant */
.synkra-btn-primary {
  background-color: var(--primary);
  border-color: var(--primary);
  color: #ffffff;
}
.synkra-btn-primary:hover:not(:disabled) {
  background-color: var(--primary-hover);
  border-color: var(--primary-hover);
  transform: translateY(-1px);
}

/* Secondary Variant */
.synkra-btn-secondary {
  background-color: var(--secondary);
  border-color: var(--secondary);
  color: #ffffff;
}
.synkra-btn-secondary:hover:not(:disabled) {
  background-color: var(--secondary-hover);
  border-color: var(--secondary-hover);
  transform: translateY(-1px);
}

/* Success Variant */
.synkra-btn-success {
  background-color: var(--success);
  border-color: var(--success);
  color: #ffffff;
}
.synkra-btn-success:hover:not(:disabled) {
  filter: brightness(0.9);
  transform: translateY(-1px);
}

/* Danger Variant */
.synkra-btn-danger {
  background-color: var(--danger);
  border-color: var(--danger);
  color: #ffffff;
}
.synkra-btn-danger:hover:not(:disabled) {
  filter: brightness(0.9);
  transform: translateY(-1px);
}

/* Warning Variant */
.synkra-btn-warning {
  background-color: var(--warning);
  border-color: var(--warning);
  color: #0F172A;
}
.synkra-btn-warning:hover:not(:disabled) {
  filter: brightness(0.9);
  transform: translateY(-1px);
}
</style>
