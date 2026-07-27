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

<label class="flowexa-checkbox-wrapper {{ $isLoading ? 'flowexa-checkbox-loading' : '' }} {{ $class }}" for="{{ $id }}" id="group_{{ $id }}">
  <input
    type="checkbox"
    name="{{ $name }}"
    id="{{ $id }}"
    value="{{ $value }}"
    {{ $checked }}
    {{ $isLoading ? 'disabled' : '' }}
    class="flowexa-checkbox-input"
    @if($onChange) onchange="{{ $onChange }}" @endif
  >
  <span class="flowexa-checkbox-custom">
    <i class="fa-solid fa-check flowexa-checkbox-icon"></i>
  </span>
  @if($label)
    <span class="flowexa-checkbox-label">{{ $label }}</span>
  @endif
</label>

<script>
if (typeof toggleflowexaCheckboxLoading !== 'function') {
  function toggleflowexaCheckboxLoading(checkboxId, isLoading) {
    const group = document.getElementById('group_' + checkboxId);
    const input = document.getElementById(checkboxId);
    if (!group || !input) return;

    if (isLoading) {
      group.classList.add('flowexa-checkbox-loading');
      input.setAttribute('disabled', 'true');
    } else {
      group.classList.remove('flowexa-checkbox-loading');
      input.removeAttribute('disabled');
    }
  }
}
</script>

<style>
.flowexa-checkbox-wrapper {
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

.flowexa-checkbox-loading {
  opacity: 0.6;
  cursor: not-allowed;
}

.flowexa-checkbox-input {
  display: none;
}

.flowexa-checkbox-custom {
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

.flowexa-checkbox-icon {
  font-size: 0.75rem;
  color: #ffffff;
  opacity: 0;
  transform: scale(0.5);
  transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.flowexa-checkbox-input:checked + .flowexa-checkbox-custom {
  background-color: var(--primary);
  border-color: var(--primary);
}

.flowexa-checkbox-input:checked + .flowexa-checkbox-custom .flowexa-checkbox-icon {
  opacity: 1;
  transform: scale(1);
}

.flowexa-checkbox-wrapper:not(.flowexa-checkbox-loading):hover .flowexa-checkbox-custom {
  border-color: var(--primary);
}

.flowexa-checkbox-label {
  font-weight: 500;
}
</style>
