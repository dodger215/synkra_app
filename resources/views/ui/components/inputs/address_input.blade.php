@php
  $value = $value ?? 'flowexa.io/workspace';
  $prefix = $prefix ?? 'https://';
  $id = $id ?? 'url_' . uniqid();
  $class = $class ?? '';
@endphp

<div class="flowexa-url-input-container {{ $class }}">
  <span class="flowexa-url-prefix">{{ $prefix }}</span>
  <input id="{{ $id }}" class="flowexa-url-field" value="{{ $value }}" readonly />
  <button type="button" class="flowexa-url-copy-btn" onclick="copyUrlToClipboard('{{ $id }}', this)" title="Copy Link">
    <i class="fa-regular fa-copy"></i>
    <span class="flowexa-url-tooltip">Copy</span>
  </button>
</div>

<script>
if (typeof copyUrlToClipboard !== 'function') {
  function copyUrlToClipboard(inputId, buttonEl) {
    const input = document.getElementById(inputId);
    input.select();
    input.setSelectionRange(0, 99999);

    navigator.clipboard.writeText(input.value).then(() => {
      const tooltip = buttonEl.querySelector('.flowexa-url-tooltip');
      const icon = buttonEl.querySelector('i');

      tooltip.textContent = 'Copied!';
      icon.className = 'fa-solid fa-check';

      setTimeout(() => {
        tooltip.textContent = 'Copy';
        icon.className = 'fa-regular fa-copy';
      }, 2000);
    });
  }
}
</script>

<style>
.flowexa-url-input-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03);
  width: 100%;
  max-width: 320px;
  height: 40px;
  border-radius: 8px;
  position: relative;
  border: 1px solid var(--border);
  background-color: var(--surface);
  overflow: hidden;
}

.flowexa-url-prefix {
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--text-secondary);
  font-size: 0.825rem;
  height: 100%;
  font-weight: 600;
  padding: 0 10px;
  background-color: var(--surface-secondary);
  border-right: 1px solid var(--border);
  user-select: none;
}

.flowexa-url-field {
  flex: 1;
  outline: none;
  font-weight: 500;
  border: none;
  padding: 0 10px;
  height: 100%;
  background-color: transparent;
  color: var(--text-primary);
  font-size: 0.85rem;
}

.flowexa-url-copy-btn {
  background-color: transparent;
  border: none;
  font-size: 0.95rem;
  color: var(--text-secondary);
  height: 100%;
  width: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  position: relative;
  transition: all 0.2s;
  border-left: 1px solid var(--border);
}

.flowexa-url-copy-btn:hover {
  background-color: var(--surface-secondary);
  color: var(--primary);
}

.flowexa-url-tooltip {
  position: absolute;
  top: -36px;
  right: 50%;
  transform: translateX(50%);
  opacity: 0;
  background-color: var(--text-primary);
  color: var(--background);
  font-size: 0.75rem;
  font-weight: 600;
  padding: 4px 8px;
  border-radius: 4px;
  pointer-events: none;
  transition: opacity 0.2s, top 0.2s;
  white-space: nowrap;
  box-shadow: 0 2px 5px rgba(0,0,0,0.15);
  z-index: 10;
}

.flowexa-url-tooltip::before {
  position: absolute;
  content: "";
  width: 6px;
  height: 6px;
  background-color: var(--text-primary);
  transform: rotate(45deg) translateX(-50%);
  bottom: -4px;
  left: 50%;
}

.flowexa-url-copy-btn:hover .flowexa-url-tooltip {
  opacity: 1;
  top: -40px;
}
</style>
