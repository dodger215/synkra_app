@php
  $name = $name ?? 'switch';
  $id = $id ?? 'switch_' . uniqid();
  $checked = ($checked ?? false) ? 'checked' : '';
  $label = $label ?? '';
  $isLoading = $isLoading ?? false;
  $onChange = $onChange ?? '';
  $class = $class ?? '';
@endphp

<label class="flowexa-switch-adv-wrapper {{ $isLoading ? 'flowexa-switch-adv-loading' : '' }} {{ $class }}" for="{{ $id }}" id="group_{{ $id }}">
  <div class="switch">
    <input
      type="checkbox"
      name="{{ $name }}"
      id="{{ $id }}"
      {{ $checked }}
      {{ $isLoading ? 'disabled' : '' }}
      @if($onChange) onchange="{{ $onChange }}" @endif
    />
    <span class="slider">
      <span class="glow"></span>
      <span class="icon-on"><i class="fa-solid fa-check"></i></span>
      <span class="icon-off"><i class="fa-solid fa-xmark"></i></span>
    </span>
  </div>
  @if($label)
    <span class="flowexa-switch-adv-label">{{ $label }}</span>
  @endif
</label>

<script>
if (typeof toggleflowexaAdvSwitchLoading !== 'function') {
  function toggleflowexaAdvSwitchLoading(switchId, isLoading) {
    const group = document.getElementById('group_' + switchId);
    const input = document.getElementById(switchId);
    if (!group || !input) return;

    if (isLoading) {
      group.classList.add('flowexa-switch-adv-loading');
      input.setAttribute('disabled', 'true');
    } else {
      group.classList.remove('flowexa-switch-adv-loading');
      input.removeAttribute('disabled');
    }
  }
}
</script>

<style>
.flowexa-switch-adv-wrapper {
  display: inline-flex;
  align-items: center;
  gap: 12px;
  cursor: pointer;
  user-select: none;
  font-size: 0.9rem;
  color: var(--body-text);
  transition: opacity 0.2s;
}

.flowexa-switch-adv-loading {
  opacity: 0.6;
  cursor: not-allowed;
}

.flowexa-switch-adv-loading .switch .slider {
  cursor: not-allowed;
}

.flowexa-switch-adv-label {
  font-weight: 500;
}

.switch {
  --button-width: 4em;
  --button-height: 2.2em;
  --circle-diameter: 1.8em;
  --circle-offset: 3px;
  --button-hue: 25; /* Matches orange primary color */
  --button-saturation: 95%;
  --button-lightness: 53%;

  font-size: 13px;
  position: relative;
  display: inline-block;
  width: var(--button-width);
  height: var(--button-height);
  outline: none;
  filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.08));
}

.flowexa-switch-adv-wrapper:not(.flowexa-switch-adv-loading) .switch:focus-within {
  outline: 2px solid var(--primary);
  outline-offset: 3px;
  border-radius: var(--button-height);
}

.switch input {
  position: absolute;
  width: 1px;
  height: 1px;
  padding: 0;
  margin: -1px;
  overflow: hidden;
  clip: rect(0, 0, 0, 0);
  white-space: nowrap;
  border: 0;
}

.slider {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: var(--border);
  border-radius: var(--button-height);
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  cursor: pointer;
  user-select: none;
  -webkit-user-select: none;
  overflow: hidden;
}

.slider::after {
  content: "";
  position: absolute;
  inset: 0;
  background: linear-gradient(
    180deg,
    rgba(255, 255, 255, 0.08) 0%,
    transparent 50%,
    rgba(0, 0, 0, 0.05) 100%
  );
  border-radius: var(--button-height);
  pointer-events: none;
}

.slider::before {
  content: "";
  position: absolute;
  top: 50%;
  left: var(--circle-offset);
  width: var(--circle-diameter);
  height: var(--circle-diameter);
  background: #ffffff;
  border-radius: 50%;
  transform: translateY(-50%);
  transition:
    left 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55),
    width 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55),
    box-shadow 0.4s ease,
    transform 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
  box-shadow:
    0 2px 8px rgba(0, 0, 0, 0.15),
    0 1px 3px rgba(0, 0, 0, 0.1);
  will-change: left, width, transform;
  z-index: 2;
}

.slider .glow {
  position: absolute;
  top: 50%;
  left: calc(var(--circle-diameter) / 2 + var(--circle-offset));
  width: 0;
  height: 0;
  background: var(--primary);
  border-radius: 50%;
  transform: translate(-50%, -50%);
  transition: all 0.5s ease;
  opacity: 0;
  z-index: 1;
}

.flowexa-switch-adv-wrapper:not(.flowexa-switch-adv-loading) .slider:hover::before {
  transform: translateY(-50%) scale(1.05);
}

.switch input:checked + .slider {
  background: var(--primary);
}

.switch input:checked + .slider .glow {
  width: 100%;
  height: 100%;
  opacity: 0.3;
}

.switch input:checked + .slider::before {
  left: calc(100% - var(--circle-diameter) - var(--circle-offset));
}

.switch input:active:not(:disabled) + .slider::before {
  width: calc(var(--button-width) - 2 * var(--circle-offset));
  border-radius: var(--button-height);
}

.switch input:active:not(:checked):not(:disabled) + .slider::before {
  left: var(--circle-offset);
  width: calc(var(--button-width) - 2 * var(--circle-offset));
}

.switch input:checked:active:not(:disabled) + .slider::before {
  left: var(--circle-offset);
  width: calc(var(--button-width) - 2 * var(--circle-offset));
}

.switch .icon-on,
.switch .icon-off {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  font-size: calc(var(--button-height) * 0.4);
  z-index: 1;
  opacity: 0;
  transition: opacity 0.3s ease;
  pointer-events: none;
  color: white;
}

.switch .icon-on {
  left: 10px;
}

.switch .icon-off {
  right: 10px;
  color: var(--text-secondary);
}

.switch input:checked + .slider .icon-on {
  opacity: 1;
}

.switch input:not(:checked) + .slider .icon-off {
  opacity: 1;
}
</style>
