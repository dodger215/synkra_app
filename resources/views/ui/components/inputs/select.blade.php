@php
  $name = $name ?? 'select_field';
  $id = $id ?? 'select_' . uniqid();
  $label = $label ?? 'Select Option';
  $options = $options ?? [
    ['value' => '1', 'label' => 'Option 1'],
    ['value' => '2', 'label' => 'Option 2'],
    ['value' => '3', 'label' => 'Option 3'],
  ];
  $selected = $selected ?? '';
  $required = ($required ?? false) ? 'required' : '';
  $isLoading = $isLoading ?? false;
  $onChange = $onChange ?? '';
  $class = $class ?? '';
@endphp

<div class="synkra-select-group {{ $class }}" id="group_{{ $id }}">
  <label for="{{ $id }}" class="synkra-select-label">
    {{ $label }}
    @if($required)
      <span class="synkra-select-required">*</span>
    @endif
  </label>
  <div class="synkra-select-container {{ $isLoading ? 'synkra-select-loading' : '' }}">
    <select
      name="{{ $name }}"
      id="{{ $id }}"
      {{ $required }}
      {{ $isLoading ? 'disabled' : '' }}
      class="synkra-select-field"
      @if($onChange) onchange="{{ $onChange }}" @endif
    >
      @foreach($options as $option)
        <option value="{{ $option['value'] }}" {{ $selected == $option['value'] ? 'selected' : '' }}>
          {{ $option['label'] }}
        </option>
      @endforeach
    </select>
    <span class="synkra-select-arrow synkra-select-arrow-normal">
      <i class="fa-solid fa-chevron-down"></i>
    </span>
    <span class="synkra-select-arrow synkra-select-arrow-spinner">
      <i class="fa-solid fa-circle-notch fa-spin"></i>
    </span>
  </div>
</div>

<script>
if (typeof toggleSynkraSelectLoading !== 'function') {
  function toggleSynkraSelectLoading(selectId, isLoading) {
    const group = document.getElementById('group_' + selectId);
    const select = document.getElementById(selectId);
    if (!group || !select) return;
    
    const container = group.querySelector('.synkra-select-container');
    if (isLoading) {
      container.classList.add('synkra-select-loading');
      select.setAttribute('disabled', 'true');
    } else {
      container.classList.remove('synkra-select-loading');
      select.removeAttribute('disabled');
    }
  }
}
</script>

<style>
.synkra-select-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  width: 100%;
  max-width: 320px;
  margin-bottom: 1rem;
}

.synkra-select-label {
  font-size: 0.875rem;
  font-weight: 600;
  color: var(--headings);
  display: flex;
  align-items: center;
  gap: 2px;
}

.synkra-select-required {
  color: var(--danger);
}

.synkra-select-container {
  position: relative;
  display: flex;
  align-items: center;
  width: 100%;
}

.synkra-select-field {
  width: 100%;
  border-radius: 8px;
  padding: 0.625rem 2.25rem 0.625rem 0.75rem;
  font-size: 0.9rem;
  border: 1px solid var(--border);
  background-color: var(--inputs-bg);
  color: var(--text-primary);
  outline: none;
  font-family: inherit;
  appearance: none;
  -webkit-appearance: none;
  -moz-appearance: none;
  transition: border-color 0.2s, box-shadow 0.2s, opacity 0.2s;
  cursor: pointer;
}

.synkra-select-field:focus {
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.15);
}

.synkra-select-arrow {
  position: absolute;
  right: 0.85rem;
  color: var(--text-secondary);
  font-size: 0.75rem;
  pointer-events: none;
  display: flex;
  align-items: center;
  justify-content: center;
}

.synkra-select-arrow-spinner {
  display: none;
  color: var(--primary);
}

/* Loading State */
.synkra-select-loading .synkra-select-field {
  cursor: not-allowed;
  opacity: 0.7;
}

.synkra-select-loading .synkra-select-arrow-normal {
  display: none;
}

.synkra-select-loading .synkra-select-arrow-spinner {
  display: flex;
}
</style>
