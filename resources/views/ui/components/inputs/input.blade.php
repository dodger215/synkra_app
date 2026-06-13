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

<div class="synkra-input-group {{ $class }}" id="group_{{ $id }}">
  <label for="{{ $id }}" class="synkra-input-label">
    {{ $label }}
    @if($required)
      <span class="synkra-input-required">*</span>
    @endif
  </label>
  <div class="synkra-input-container {{ $isLoading ? 'synkra-input-loading' : '' }}">
    @if($icon)
      <span class="synkra-input-icon synkra-input-icon-normal">
        <i class="{{ $icon }}"></i>
      </span>
    @endif
    <span class="synkra-input-icon synkra-input-icon-spinner" style="display: none;">
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
      class="synkra-input-field {{ ($icon || $isLoading) ? 'synkra-input-has-icon' : '' }} {{ $type === 'password' ? 'synkra-input-has-eye' : '' }}"
      @if($onInput) oninput="{{ $onInput }}" @endif
    />
    @if($type === 'password')
      <span class="synkra-input-eye" onclick="toggleSynkraPasswordVisibility('{{ $id }}', this)">
        <i class="fa-regular fa-eye"></i>
      </span>
    @endif
  </div>
  @if($description)
    <p class="synkra-input-desc">{{ $description }}</p>
  @endif
</div>

<script>
if (typeof toggleSynkraInputLoading !== 'function') {
  function toggleSynkraInputLoading(inputId, isLoading) {
    const group = document.getElementById('group_' + inputId);
    const input = document.getElementById(inputId);
    if (!group || !input) return;
    
    const container = group.querySelector('.synkra-input-container');
    if (isLoading) {
      container.classList.add('synkra-input-loading');
      input.setAttribute('readonly', 'true');
      input.classList.add('synkra-input-has-icon');
    } else {
      container.classList.remove('synkra-input-loading');
      input.removeAttribute('readonly');
      // Remove padding if it didn't originally have an icon
      if (!group.querySelector('.synkra-input-icon-normal')) {
        input.classList.remove('synkra-input-has-icon');
      }
    }
  }
}

if (typeof toggleSynkraPasswordVisibility !== 'function') {
  function toggleSynkraPasswordVisibility(inputId, eyeBtn) {
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
.synkra-input-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  width: 100%;
  max-width: 320px;
  margin-bottom: 1rem;
}

.synkra-input-label {
  font-size: 0.875rem;
  font-weight: 600;
  color: var(--headings);
  display: flex;
  align-items: center;
  gap: 2px;
}

.synkra-input-required {
  color: var(--danger);
}

.synkra-input-container {
  position: relative;
  display: flex;
  align-items: center;
  width: 100%;
}

.synkra-input-field {
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

.synkra-input-field::placeholder {
  color: var(--text-secondary);
  opacity: 0.7;
}

.synkra-input-field:focus:not([readonly]) {
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.15);
}

.synkra-input-icon {
  position: absolute;
  left: 0.75rem;
  color: var(--text-secondary);
  font-size: 0.95rem;
  pointer-events: none;
  display: flex;
  align-items: center;
  justify-content: center;
}

.synkra-input-has-icon {
  padding-left: 2.25rem;
}

.synkra-input-has-eye {
  padding-right: 2.5rem;
}

.synkra-input-eye {
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

.synkra-input-eye:hover {
  color: var(--primary);
}

.synkra-input-desc {
  font-size: 0.75rem;
  color: var(--text-secondary);
  margin: 0;
}

/* Loading State */
.synkra-input-loading .synkra-input-icon-normal {
  display: none;
}

.synkra-input-loading .synkra-input-icon-spinner {
  display: flex !important;
  color: var(--primary);
}

.synkra-input-loading .synkra-input-field {
  opacity: 0.7;
  cursor: wait;
}
</style>
