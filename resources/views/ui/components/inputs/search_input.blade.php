@php
  $name = $name ?? 'search';
  $id = $id ?? 'search_' . uniqid();
  $placeholder = $placeholder ?? 'Search...';
  $value = $value ?? '';
  $isLoading = $isLoading ?? false;
  $onInput = $onInput ?? '';
  $class = $class ?? '';
@endphp

<div class="flowexa-search-wrapper {{ $isLoading ? 'flowexa-search-loading' : '' }} {{ $class }}" id="group_{{ $id }}">
  <span class="flowexa-search-icon flowexa-search-icon-normal">
    <i class="fa-solid fa-magnifying-glass"></i>
  </span>
  <span class="flowexa-search-icon flowexa-search-icon-spinner" style="display: none;">
    <i class="fa-solid fa-circle-notch fa-spin"></i>
  </span>
  <input
    type="search"
    name="{{ $name }}"
    id="{{ $id }}"
    value="{{ $value }}"
    placeholder="{{ $placeholder }}"
    {{ $isLoading ? 'readonly' : '' }}
    class="flowexa-search-field"
    @if($onInput) oninput="{{ $onInput }}" @endif
  />
</div>

<script>
if (typeof toggleflowexaSearchLoading !== 'function') {
  function toggleflowexaSearchLoading(searchId, isLoading) {
    const group = document.getElementById('group_' + searchId);
    const input = document.getElementById(searchId);
    if (!group || !input) return;

    if (isLoading) {
      group.classList.add('flowexa-search-loading');
      input.setAttribute('readonly', 'true');
    } else {
      group.classList.remove('flowexa-search-loading');
      input.removeAttribute('readonly');
    }
  }
}
</script>

<style>
.flowexa-search-wrapper {
  position: relative;
  display: flex;
  align-items: center;
  width: 100%;
  max-width: 320px;
}

.flowexa-search-field {
  width: 100%;
  border-radius: 9999px;
  padding: 0.625rem 0.75rem 0.625rem 2.5rem;
  font-size: 0.9rem;
  border: 1px solid var(--border);
  background-color: var(--inputs-bg);
  color: var(--text-primary);
  outline: none;
  font-family: inherit;
  transition: all 0.2s ease-in-out;
}

.flowexa-search-field::placeholder {
  color: var(--text-secondary);
  opacity: 0.7;
}

.flowexa-search-field:focus:not([readonly]) {
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.15);
  background-color: var(--surface);
}

.flowexa-search-icon {
  position: absolute;
  left: 0.9rem;
  color: var(--text-secondary);
  font-size: 0.9rem;
  pointer-events: none;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Loading State */
.flowexa-search-loading .flowexa-search-icon-normal {
  display: none;
}

.flowexa-search-loading .flowexa-search-icon-spinner {
  display: flex !important;
  color: var(--primary);
}

.flowexa-search-loading .flowexa-search-field {
  opacity: 0.7;
}
</style>
