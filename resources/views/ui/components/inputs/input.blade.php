@php
  $name = $name ?? 'input_field';
  $id = $id ?? 'input_' . uniqid();
  $label = $label ?? 'Input Label';
  $type = $type ?? 'text';
  $placeholder = $placeholder ?? '';
  $value = $value ?? '';
  $description = $description ?? null;
  $icon = $icon ?? null;
  $required = ($required ?? false) ? 'required' : '';
  $isLoading = $isLoading ?? false;
  $onInput = $onInput ?? '';
  $class = $class ?? '';
  $fullWidth = $fullWidth ?? false;
@endphp

<div class="flowexa-input-group {{ $class }}" id="group_{{ $id }}">
  <label for="{{ $id }}" class="flowexa-input-label">
    {{ $label }}
    @if($required)
      <span class="flowexa-input-required">*</span>
    @endif
  </label>
  <div class="flowexa-input-container {{ $isLoading ? 'flowexa-input-loading' : '' }}">
    @if($icon)
      <span class="flowexa-input-icon flowexa-input-icon-normal">
        <i class="{{ $icon }}"></i>
      </span>
    @endif
    <span class="flowexa-input-icon flowexa-input-icon-spinner" style="display: none;">
      <i class="fa-solid fa-circle-notch fa-spin"></i>
    </span>
    <input
      type="{{ $type }}"
      name="{{ $name }}"
      id="{{ $id }}"
      value="{{ $value }}"
      placeholder="{{ $placeholder }}"
      {{ $required }}
      {{ $isLoading ? 'readonly' : '' }}
      class="flowexa-input-field {{ ($icon || $isLoading) ? 'flowexa-input-has-icon' : '' }} {{ $type === 'password' ? 'flowexa-input-has-eye' : '' }}"
      @if($onInput) oninput="{{ $onInput }}" @endif
    />
    @if($type === 'password')
      <span class="flowexa-input-eye" onclick="toggleflowexaPasswordVisibility('{{ $id }}', this)">
        <i class="fa-regular fa-eye"></i>
      </span>
    @endif
  </div>
  @if($description)
    <p class="flowexa-input-desc">{{ $description }}</p>
  @endif
</div>

<script>
if (typeof toggleflowexaInputLoading !== 'function') {
  function toggleflowexaInputLoading(inputId, isLoading) {
    const group = document.getElementById('group_' + inputId);
    const input = document.getElementById(inputId);
    if (!group || !input) return;

    const container = group.querySelector('.flowexa-input-container');
    if (isLoading) {
      container.classList.add('flowexa-input-loading');
      input.setAttribute('readonly', 'true');
      input.classList.add('flowexa-input-has-icon');
    } else {
      container.classList.remove('flowexa-input-loading');
      input.removeAttribute('readonly');
      // Remove padding if it didn't originally have an icon
      if (!group.querySelector('.flowexa-input-icon-normal')) {
        input.classList.remove('flowexa-input-has-icon');
      }
    }
  }
}

if (typeof toggleflowexaPasswordVisibility !== 'function') {
  function toggleflowexaPasswordVisibility(inputId, eyeBtn) {
    const input = document.getElementById(inputId);
    const icon = eyeBtn.querySelector('i');
    if (!input || !icon) return;

    if (input.type === 'password') {
      input.type = 'text';
      icon.classList.remove('fa-eye');
      icon.classList.add('fa-eye-slash');
    } else {
      input.type = 'password';
      icon.classList.remove('fa-eye-slash');
      icon.classList.add('fa-eye');
    }
  }
}
</script>

<style>
.flowexa-input-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  width: 100%;
  max-width: 320px;
  margin-bottom: 1rem;
}

.flowexa-input-label {
  font-size: 0.875rem;
  font-weight: 600;
  color: var(--headings);
  display: flex;
  align-items: center;
  gap: 2px;
}

.flowexa-input-required {
  color: var(--danger);
}

.flowexa-input-container {
  position: relative;
  display: flex;
  align-items: center;
  width: 100%;
}

.flowexa-input-field {
  width: 100%;
  border-radius: 8px;
  padding: 0.625rem 0.75rem;
  font-size: 0.9rem;
  border: 1px solid var(--border);
  background-color: var(--inputs-bg);
  color: var(--text-primary);
  outline: none;
  font-family: inherit;
  transition: border-color 0.2s, box-shadow 0.2s, opacity 0.2s;
}

.flowexa-input-field::placeholder {
  color: var(--text-secondary);
  opacity: 0.7;
}

.flowexa-input-field:focus:not([readonly]) {
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.15);
}

.flowexa-input-icon {
  position: absolute;
  left: 0.75rem;
  color: var(--text-secondary);
  font-size: 0.95rem;
  pointer-events: none;
  display: flex;
  align-items: center;
  justify-content: center;
}

.flowexa-input-has-icon {
  padding-left: 2.25rem;
}

.flowexa-input-has-eye {
  padding-right: 2.5rem;
}

.flowexa-input-eye {
  position: absolute;
  right: 0.75rem;
  color: var(--text-secondary);
  font-size: 0.95rem;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: color 0.2s;
  z-index: 5;
}

.flowexa-input-eye:hover {
  color: var(--primary);
}

.flowexa-input-desc {
  font-size: 0.75rem;
  color: var(--text-secondary);
  margin: 0;
}

/* Loading State */
.flowexa-input-loading .flowexa-input-icon-normal {
  display: none;
}

.flowexa-input-loading .flowexa-input-icon-spinner {
  display: flex !important;
  color: var(--primary);
}

.flowexa-input-loading .flowexa-input-field {
  opacity: 0.7;
  cursor: wait;
}
</style>
