@php
  $name = $name ?? 'file';
  $id = $id ?? 'file_' . uniqid();
  $text = $text ?? 'Click to upload file';
  $accept = $accept ?? '*';
  $icon = $icon ?? 'fa-solid fa-cloud-arrow-up';
  $isLoading = $isLoading ?? false;
  $class = $class ?? '';
@endphp

<label class="synkra-file-upload {{ $isLoading ? 'synkra-file-loading' : '' }} {{ $class }}" for="{{ $id }}" id="label_{{ $id }}">
  <div class="synkra-file-icon">
    <i class="{{ $icon }} synkra-icon-normal"></i>
    <i class="fa-solid fa-circle-notch fa-spin synkra-icon-spinner"></i>
  </div>
  <div class="synkra-file-text">
    <span class="synkra-file-placeholder">{{ $text }}</span>
    <span class="synkra-file-name" style="display: none; word-break: break-all; font-weight: 600; color: var(--primary);"></span>
    <span class="synkra-file-loading-text" style="display: none;">Uploading...</span>
  </div>
  <input type="file" name="{{ $name }}" id="{{ $id }}" accept="{{ $accept }}" onchange="handleSynkraFileChange(this)" {{ $isLoading ? 'disabled' : '' }}>
</label>

<script>
if (typeof handleSynkraFileChange !== 'function') {
  function handleSynkraFileChange(input) {
    const label = document.getElementById('label_' + input.id);
    if (!label) return;
    
    const placeholder = label.querySelector('.synkra-file-placeholder');
    const nameDisplay = label.querySelector('.synkra-file-name');
    
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

  function toggleSynkraFileUploadLoading(inputId, isLoading) {
    const label = document.getElementById('label_' + inputId);
    const input = document.getElementById(inputId);
    if (!label || !input) return;
    
    if (isLoading) {
      label.classList.add('synkra-file-loading');
      input.setAttribute('disabled', 'true');
    } else {
      label.classList.remove('synkra-file-loading');
      input.removeAttribute('disabled');
    }
  }
}
</script>

<style>
.synkra-file-upload {
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

.synkra-file-upload:hover:not(.synkra-file-loading) {
  border-color: var(--primary);
  background-color: var(--surface);
  color: var(--text-primary);
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
}

.synkra-file-icon {
  font-size: 2.5rem;
  color: var(--primary);
  transition: transform 0.3s ease;
}

.synkra-file-upload:hover:not(.synkra-file-loading) .synkra-file-icon {
  transform: translateY(-5px);
}

.synkra-file-text span {
  font-weight: 500;
  font-size: 0.9rem;
}

.synkra-file-upload input {
  display: none;
}

/* Loading State */
.synkra-icon-spinner,
.synkra-file-loading-text {
  display: none;
}

.synkra-file-loading {
  cursor: not-allowed;
  opacity: 0.8;
  border-color: var(--primary);
}

.synkra-file-loading .synkra-icon-normal,
.synkra-file-loading .synkra-file-placeholder,
.synkra-file-loading .synkra-file-name {
  display: none !important;
}

.synkra-file-loading .synkra-icon-spinner,
.synkra-file-loading .synkra-file-loading-text {
  display: block;
}
</style>
