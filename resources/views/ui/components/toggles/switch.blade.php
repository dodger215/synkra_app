@php
  $name = $name ?? 'switch';
  $id = $id ?? 'switch_' . uniqid();
  $label = $label ?? '';
  $value = $value ?? '1';
  $checked = ($checked ?? false) ? 'checked' : '';
  $isLoading = $isLoading ?? false;
  $onChange = $onChange ?? '';
  $class = $class ?? '';
@endphp

<label class="flowexa-switch-wrapper {{ $isLoading ? 'flowexa-switch-loading' : '' }} {{ $class }}" for="{{ $id }}" id="group_{{ $id }}">
  <input
    type="checkbox"
    name="{{ $name }}"
    id="{{ $id }}"
    value="{{ $value }}"
    {{ $checked }}
    {{ $isLoading ? 'disabled' : '' }}
    class="flowexa-switch-input"
    @if($onChange) onchange="{{ $onChange }}" @endif
  >
  <span class="flowexa-switch-slider"></span>
  @if($label)
    <span class="flowexa-switch-label">{{ $label }}</span>
  @endif
</label>

<script>
if (typeof toggleflowexaSwitchLoading !== 'function') {
  function toggleflowexaSwitchLoading(switchId, isLoading) {
    const group = document.getElementById('group_' + switchId);
    const input = document.getElementById(switchId);
    if (!group || !input) return;

    if (isLoading) {
      group.classList.add('flowexa-switch-loading');
      input.setAttribute('disabled', 'true');
    } else {
      group.classList.remove('flowexa-switch-loading');
      input.removeAttribute('disabled');
    }
  }
}
</script>

<style>
.flowexa-switch-wrapper {
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

.flowexa-switch-loading {
  opacity: 0.6;
  cursor: not-allowed;
}

.flowexa-switch-input {
  display: none;
}

.flowexa-switch-slider {
  display: inline-block;
  width: 44px;
  height: 24px;
  background-color: var(--border);
  border-radius: 99px;
  position: relative;
  transition: background-color 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.flowexa-switch-slider::after {
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

.flowexa-switch-input:checked + .flowexa-switch-slider {
  background-color: var(--primary);
}

.flowexa-switch-input:checked + .flowexa-switch-slider::after {
  transform: translateX(20px);
}

.flowexa-switch-input:active:not(:disabled) + .flowexa-switch-slider::after {
  width: 24px;
}

.flowexa-switch-input:checked:active:not(:disabled) + .flowexa-switch-slider::after {
  transform: translateX(14px);
}

.flowexa-switch-label {
  font-weight: 500;
}
</style>
