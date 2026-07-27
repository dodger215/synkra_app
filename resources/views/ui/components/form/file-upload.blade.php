@php
  $name = $name ?? 'file';
  $id = $id ?? 'file_' . uniqid();
  $text = $text ?? 'Click to upload file';
  $accept = $accept ?? '*';
  $icon = $icon ?? 'fa-solid fa-cloud-arrow-up';
  $isLoading = $isLoading ?? false;
  $class = $class ?? '';
@endphp

<label class="flowexa-file-upload {{ $isLoading ? 'flowexa-file-loading' : '' }} {{ $class }}" for="{{ $id }}" id="label_{{ $id }}">
  <div class="flowexa-file-icon">
    <i class="{{ $icon }} flowexa-icon-normal"></i>
    <i class="fa-solid fa-circle-notch fa-spin flowexa-icon-spinner"></i>
  </div>
  <div class="flowexa-file-text">
    <span class="flowexa-file-placeholder">{{ $text }}</span>
    <span class="flowexa-file-name" style="display: none; word-break: break-all; font-weight: 600; color: var(--primary);"></span>
    <span class="flowexa-file-loading-text" style="display: none;">Uploading...</span>
  </div>
  <input type="file" name="{{ $name }}" id="{{ $id }}" accept="{{ $accept }}" onchange="handleflowexaFileChange(this)" {{ $isLoading ? 'disabled' : '' }}>
</label>

<script>
if (typeof handleflowexaFileChange !== 'function') {
  function handleflowexaFileChange(input) {
    const label = document.getElementById('label_' + input.id);
    if (!label) return;

    const placeholder = label.querySelector('.flowexa-file-placeholder');
    const nameDisplay = label.querySelector('.flowexa-file-name');

    if (input.files && input.files.length > 0) {
      placeholder.style.display = 'none';
      nameDisplay.style.display = 'block';
      nameDisplay.textContent = input.files[0].name;

      // Emit event
      input.dispatchEvent(new CustomEvent('file-selected', { bubbles: true, detail: { file: input.files[0] }}));
    } else {
      placeholder.style.display = 'block';
      nameDisplay.style.display = 'none';
      nameDisplay.textContent = '';
    }
  }

  function toggleflowexaFileUploadLoading(inputId, isLoading) {
    const label = document.getElementById('label_' + inputId);
    const input = document.getElementById(inputId);
    if (!label || !input) return;

    if (isLoading) {
      label.classList.add('flowexa-file-loading');
      input.setAttribute('disabled', 'true');
    } else {
      label.classList.remove('flowexa-file-loading');
      input.removeAttribute('disabled');
    }
  }
}
</script>

<style>
.flowexa-file-upload {
  height: 180px;
  width: 100%;
  max-width: 320px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 15px;
  cursor: pointer;
  border: 2px dashed var(--border);
  background-color: var(--surface-secondary);
  padding: 1.5rem;
  border-radius: 12px;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
  transition: all 0.3s ease;
  color: var(--text-secondary);
  text-align: center;
  position: relative;
  overflow: hidden;
}

.flowexa-file-upload:hover:not(.flowexa-file-loading) {
  border-color: var(--primary);
  background-color: var(--surface);
  color: var(--text-primary);
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
}

.flowexa-file-icon {
  font-size: 2.5rem;
  color: var(--primary);
  transition: transform 0.3s ease;
}

.flowexa-file-upload:hover:not(.flowexa-file-loading) .flowexa-file-icon {
  transform: translateY(-5px);
}

.flowexa-file-text span {
  font-weight: 500;
  font-size: 0.9rem;
}

.flowexa-file-upload input {
  display: none;
}

/* Loading State */
.flowexa-icon-spinner,
.flowexa-file-loading-text {
  display: none;
}

.flowexa-file-loading {
  cursor: not-allowed;
  opacity: 0.8;
  border-color: var(--primary);
}

.flowexa-file-loading .flowexa-icon-normal,
.flowexa-file-loading .flowexa-file-placeholder,
.flowexa-file-loading .flowexa-file-name {
  display: none !important;
}

.flowexa-file-loading .flowexa-icon-spinner,
.flowexa-file-loading .flowexa-file-loading-text {
  display: block;
}
</style>
