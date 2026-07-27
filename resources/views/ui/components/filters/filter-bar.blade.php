@php
  $id = $id ?? 'filter_' . uniqid();
  $searchPlaceholder = $searchPlaceholder ?? 'Search...';
  $searchName = $searchName ?? 'search';
  $filters = $filters ?? [];   // [ ['name'=>'status','label'=>'Status','options'=>[['value'=>'','label'=>'All'],…]] ]
  $class = $class ?? '';
@endphp

<div class="flowexa-filter-bar {{ $class }}" id="{{ $id }}">
  {{-- Search --}}
  <div class="flowexa-filter-search">
    <span class="flowexa-filter-search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
    <input type="search" name="{{ $searchName }}" placeholder="{{ $searchPlaceholder }}" class="flowexa-filter-search-input" oninput="flowexaFilterTable(this)">
  </div>

  {{-- Dropdown filters --}}
  @foreach($filters as $filter)
    <select name="{{ $filter['name'] }}" class="flowexa-filter-select" onchange="flowexaFilterTable(this)">
      @foreach($filter['options'] as $option)
        <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
      @endforeach
    </select>
  @endforeach

  {{-- Slot for extra buttons (import/export etc.) --}}
  @if(!$slot->isEmpty())
    <div class="flowexa-filter-actions">
      {{ $slot }}
    </div>
  @endif
</div>

<script>
if (typeof flowexaFilterTable !== 'function') {
  function flowexaFilterTable(trigger) {
    const bar = trigger.closest('.flowexa-filter-bar');
    if (!bar) return;
    const tableContainer = bar.parentElement.querySelector('.flowexa-table-container, .flowexa-table');
    if (!tableContainer) return;
    const table = tableContainer.tagName === 'TABLE' ? tableContainer : tableContainer.querySelector('table');
    if (!table) return;

    const searchInput = bar.querySelector('input[type="search"]');
    const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
    const selects = bar.querySelectorAll('.flowexa-filter-select');

    const filterValues = {};
    selects.forEach(sel => { filterValues[sel.name] = sel.value.toLowerCase(); });

    const rows = table.querySelectorAll('tbody tr');
    rows.forEach(row => {
      const text = row.textContent.toLowerCase();
      let showBySearch = !searchTerm || text.includes(searchTerm);

      let showByFilters = true;
      for (const [name, val] of Object.entries(filterValues)) {
        if (!val) continue;
        const cells = row.querySelectorAll('td');
        let found = false;
        cells.forEach(cell => { if (cell.textContent.toLowerCase().includes(val)) found = true; });
        if (!found) showByFilters = false;
      }

      row.style.display = (showBySearch && showByFilters) ? '' : 'none';
    });
  }
}
</script>

<style>
.flowexa-filter-bar {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  flex-wrap: wrap;
  margin-bottom: 1.25rem;
}

.flowexa-filter-search {
  position: relative;
  flex: 1;
  min-width: 200px;
  max-width: 360px;
}

.flowexa-filter-search-icon {
  position: absolute;
  left: 0.85rem;
  top: 50%;
  transform: translateY(-50%);
  color: var(--text-secondary);
  font-size: 0.85rem;
  pointer-events: none;
}

.flowexa-filter-search-input {
  width: 100%;
  padding: 0.6rem 0.75rem 0.6rem 2.4rem;
  border: 1px solid var(--border);
  border-radius: 8px;
  background: var(--inputs-bg, var(--surface-secondary));
  color: var(--text-primary);
  font-size: 0.875rem;
  font-family: inherit;
  outline: none;
  transition: border-color 0.2s, box-shadow 0.2s;
}

.flowexa-filter-search-input:focus {
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.12);
}

.flowexa-filter-select {
  padding: 0.6rem 0.75rem;
  border: 1px solid var(--border);
  border-radius: 8px;
  background: var(--inputs-bg, var(--surface-secondary));
  color: var(--text-primary);
  font-size: 0.85rem;
  font-family: inherit;
  outline: none;
  cursor: pointer;
  min-width: 130px;
  transition: border-color 0.2s;
}

.flowexa-filter-select:focus {
  border-color: var(--primary);
}

.flowexa-filter-actions {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin-left: auto;
}

.flowexa-filter-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  padding: 0.55rem 1rem;
  border: 1px solid var(--border);
  border-radius: 8px;
  background: var(--surface-secondary);
  color: var(--text-primary);
  font-size: 0.85rem;
  font-weight: 600;
  font-family: inherit;
  cursor: pointer;
  transition: all 0.2s;
  text-decoration: none;
}

.flowexa-filter-btn:hover {
  border-color: var(--primary);
  color: var(--primary);
}

.flowexa-filter-btn i {
  font-size: 0.8rem;
}
</style>
