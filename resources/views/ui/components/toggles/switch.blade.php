@php
  $name = $name ?? 'switch';
  $id = $id ?? 'switch_' . uniqid();
  $label = $label ?? '';
  $checked = ($checked ?? false) ? 'checked' : '';
  $isLoading = $isLoading ?? false;
  $onChange = $onChange ?? '';
  $class = $class ?? '';
@endphp

<label class="synkra-switch-wrapper {{ $isLoading ? 'synkra-switch-loading' : '' }} {{ $class }}" for="{{ $id }}" id="group_{{ $id }}">
  <input 
    type="checkbox" 
    name="{{ $name }}" 
    id="{{ $id }}" 
    {{ $checked }} 
    {{ $isLoading ? 'disabled' : '' }}
    class="synkra-switch-input"
    @if($onChange) onchange="{{ $onChange }}" @endif
  >
  <span class="synkra-switch-slider"></span>
  @if($label)
    <span class="synkra-switch-label">{{ $label }}</span>
  @endif
</label>

<script>
if (typeof toggleSynkraSwitchLoading !== 'function') {
  function toggleSynkraSwitchLoading(switchId, isLoading) {
    const group = document.getElementById('group_' + switchId);
    const input = document.getElementById(switchId);
    if (!group || !input) return;
    
    if (isLoading) {
      group.classList.add('synkra-switch-loading');
      input.setAttribute('disabled', 'true');
    } else {
      group.classList.remove('synkra-switch-loading');
      input.removeAttribute('disabled');
    }
  }
}
</script>

<style>
.synkra-switch-wrapper {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  cursor: pointer;
  user-select: none;
  font-size: 0.9rem;
  color: var(--body-text);
  margin-bottom: 0.5rem;
  transition: opacity 0.2s;
}

.synkra-switch-loading {
  opacity: 0.6;
  cursor: not-allowed;
}

.synkra-switch-input {
  display: none;
}

.synkra-switch-slider {
  display: inline-block;
  width: 44px;
  height: 24px;
  background-color: var(--border);
  border-radius: 99px;
  position: relative;
  transition: background-color 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.synkra-switch-slider::after {
  content: "";
  display: block;
  width: 18px;
  height: 18px;
  background-color: #ffffff;
  border-radius: 50%;
  position: absolute;
  top: 3px;
  left: 3px;
  transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), width 0.2s;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
}

.synkra-switch-input:checked + .synkra-switch-slider {
  background-color: var(--primary);
}

.synkra-switch-input:checked + .synkra-switch-slider::after {
  transform: translateX(20px);
}

.synkra-switch-input:active:not(:disabled) + .synkra-switch-slider::after {
  width: 24px;
}

.synkra-switch-input:checked:active:not(:disabled) + .synkra-switch-slider::after {
  transform: translateX(14px);
}

.synkra-switch-label {
  font-weight: 500;
}
</style>