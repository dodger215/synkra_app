@php
  $name = $name ?? 'checkbox';
  $id = $id ?? 'checkbox_' . uniqid();
  $label = $label ?? '';
  $value = $value ?? '1';
  $checked = ($checked ?? false) ? 'checked' : '';
  $isLoading = $isLoading ?? false;
  $onChange = $onChange ?? '';
  $class = $class ?? '';
@endphp

<label class="synkra-checkbox-wrapper {{ $isLoading ? 'synkra-checkbox-loading' : '' }} {{ $class }}" for="{{ $id }}" id="group_{{ $id }}">
  <input 
    type="checkbox" 
    name="{{ $name }}" 
    id="{{ $id }}" 
    value="{{ $value }}"
    {{ $checked }} 
    {{ $isLoading ? 'disabled' : '' }}
    class="synkra-checkbox-input"
    @if($onChange) onchange="{{ $onChange }}" @endif
  >
  <span class="synkra-checkbox-custom">
    <i class="fa-solid fa-check synkra-checkbox-icon"></i>
  </span>
  @if($label)
    <span class="synkra-checkbox-label">{{ $label }}</span>
  @endif
</label>

<script>
if (typeof toggleSynkraCheckboxLoading !== 'function') {
  function toggleSynkraCheckboxLoading(checkboxId, isLoading) {
    const group = document.getElementById('group_' + checkboxId);
    const input = document.getElementById(checkboxId);
    if (!group || !input) return;
    
    if (isLoading) {
      group.classList.add('synkra-checkbox-loading');
      input.setAttribute('disabled', 'true');
    } else {
      group.classList.remove('synkra-checkbox-loading');
      input.removeAttribute('disabled');
    }
  }
}
</script>

<style>
.synkra-checkbox-wrapper {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  cursor: pointer;
  user-select: none;
  font-size: 0.9rem;
  color: var(--body-text);
  margin-bottom: 0.5rem;
  transition: opacity 0.2s;
}

.synkra-checkbox-loading {
  opacity: 0.6;
  cursor: not-allowed;
}

.synkra-checkbox-input {
  display: none;
}

.synkra-checkbox-custom {
  width: 20px;
  height: 20px;
  border: 2px solid var(--border);
  border-radius: 6px;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: var(--inputs-bg);
  transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.synkra-checkbox-icon {
  font-size: 0.75rem;
  color: #ffffff;
  opacity: 0;
  transform: scale(0.5);
  transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.synkra-checkbox-input:checked + .synkra-checkbox-custom {
  background-color: var(--primary);
  border-color: var(--primary);
}

.synkra-checkbox-input:checked + .synkra-checkbox-custom .synkra-checkbox-icon {
  opacity: 1;
  transform: scale(1);
}

.synkra-checkbox-wrapper:not(.synkra-checkbox-loading):hover .synkra-checkbox-custom {
  border-color: var(--primary);
}

.synkra-checkbox-label {
  font-weight: 500;
}
</style>