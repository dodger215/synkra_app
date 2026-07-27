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

<div class="flowexa-select-group {{ $class }}" id="group_{{ $id }}">
  <label for="{{ $id }}" class="flowexa-select-label">
    {{ $label }}
    @if($required)
      <span class="flowexa-select-required">*</span>
    @endif
  </label>
  <div class="flowexa-select-container {{ $isLoading ? 'flowexa-select-loading' : '' }}">
    <select
      name="{{ $name }}"
      id="{{ $id }}"
      {{ $required }}
      {{ $isLoading ? 'disabled' : '' }}
      class="flowexa-select-field"
      @if($onChange) onchange="{{ $onChange }}" @endif
    >
      @foreach($options as $option)
        <option value="{{ $option['value'] }}" {{ $selected == $option['value'] ? 'selected' : '' }}>
          {{ $option['label'] }}
        </option>
      @endforeach
    </select>
    <span class="flowexa-select-arrow flowexa-select-arrow-normal">
      <i class="fa-solid fa-chevron-down"></i>
    </span>
    <span class="flowexa-select-arrow flowexa-select-arrow-spinner">
      <i class="fa-solid fa-circle-notch fa-spin"></i>
    </span>
  </div>
</div>

<script>
if (typeof toggleflowexaSelectLoading !== 'function') {
  function toggleflowexaSelectLoading(selectId, isLoading) {
    const group = document.getElementById('group_' + selectId);
    const select = document.getElementById(selectId);
    if (!group || !select) return;

    const container = group.querySelector('.flowexa-select-container');
    if (isLoading) {
      container.classList.add('flowexa-select-loading');
      select.setAttribute('disabled', 'true');
    } else {
      container.classList.remove('flowexa-select-loading');
      select.removeAttribute('disabled');
    }
  }
}
</script>

<style>
.flowexa-select-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  width: 100%;
  max-width: 320px;
  margin-bottom: 1rem;
}

.flowexa-select-label {
  font-size: 0.875rem;
  font-weight: 600;
  color: var(--headings);
  display: flex;
  align-items: center;
  gap: 2px;
}

.flowexa-select-required {
  color: var(--danger);
}

.flowexa-select-container {
  position: relative;
  display: flex;
  align-items: center;
  width: 100%;
}

.flowexa-select-field {
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

.flowexa-select-field:focus {
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.15);
}

.flowexa-select-arrow {
  position: absolute;
  right: 0.85rem;
  color: var(--text-secondary);
  font-size: 0.75rem;
  pointer-events: none;
  display: flex;
  align-items: center;
  justify-content: center;
}

.flowexa-select-arrow-spinner {
  display: none;
  color: var(--primary);
}

/* Loading State */
.flowexa-select-loading .flowexa-select-field {
  cursor: not-allowed;
  opacity: 0.7;
}

.flowexa-select-loading .flowexa-select-arrow-normal {
  display: none;
}

.flowexa-select-loading .flowexa-select-arrow-spinner {
  display: flex;
}
</style>
