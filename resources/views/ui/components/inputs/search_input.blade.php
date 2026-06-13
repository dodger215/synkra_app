@php
  $name = $name ?? 'search';
  $id = $id ?? 'search_' . uniqid();
  $placeholder = $placeholder ?? 'Search...';
  $value = $value ?? '';
  $isLoading = $isLoading ?? false;
  $onInput = $onInput ?? '';
  $class = $class ?? '';
@endphp

<div class="synkra-search-wrapper {{ $isLoading ? 'synkra-search-loading' : '' }} {{ $class }}" id="group_{{ $id }}">
  <span class="synkra-search-icon synkra-search-icon-normal">
    <i class="fa-solid fa-magnifying-glass"></i>
  </span>
  <span class="synkra-search-icon synkra-search-icon-spinner" style="display: none;">
    <i class="fa-solid fa-circle-notch fa-spin"></i>
  </span>
  <input
    type="search"
    name="{{ $name }}"
    id="{{ $id }}"
    value="{{ $value }}"
    placeholder="{{ $placeholder }}"
    {{ $isLoading ? 'readonly' : '' }}
    class="synkra-search-field"
    @if($onInput) oninput="{{ $onInput }}" @endif
  />
</div>

<script>
if (typeof toggleSynkraSearchLoading !== 'function') {
  function toggleSynkraSearchLoading(searchId, isLoading) {
    const group = document.getElementById('group_' + searchId);
    const input = document.getElementById(searchId);
    if (!group || !input) return;
    
    if (isLoading) {
      group.classList.add('synkra-search-loading');
      input.setAttribute('readonly', 'true');
    } else {
      group.classList.remove('synkra-search-loading');
      input.removeAttribute('readonly');
    }
  }
}
</script>

<style>
.synkra-search-wrapper {
  position: relative;
  display: flex;
  align-items: center;
  width: 100%;
  max-width: 320px;
}

.synkra-search-field {
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

.synkra-search-field::placeholder {
  color: var(--text-secondary);
  opacity: 0.7;
}

.synkra-search-field:focus:not([readonly]) {
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.15);
  background-color: var(--surface);
}

.synkra-search-icon {
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
.synkra-search-loading .synkra-search-icon-normal {
  display: none;
}

.synkra-search-loading .synkra-search-icon-spinner {
  display: flex !important;
  color: var(--primary);
}

.synkra-search-loading .synkra-search-field {
  opacity: 0.7;
}
</style>