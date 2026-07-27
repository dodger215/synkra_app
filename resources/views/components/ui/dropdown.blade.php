@props(['id', 'width' => '260px', 'header' => null])

<div class="flowexa-ui-dropdown-wrapper" onclick="toggleflowexaUiDropdown(event, '{{ $id }}')">
    {{ $trigger }}

    <div class="flowexa-ui-dropdown-content" id="{{ $id }}" style="width: {{ $width }};">
        @if($header)
            <div class="flowexa-ui-dropdown-header">
                {{ $header }}
            </div>
            <div class="flowexa-ui-dropdown-divider"></div>
        @endif

        <div class="flowexa-ui-dropdown-body">
            {{ $slot }}
        </div>
    </div>
</div>

@once
<style>
.flowexa-ui-dropdown-wrapper {
  position: relative;
  display: inline-block;
}

.flowexa-ui-dropdown-content {
  position: absolute;
  top: calc(100% + 15px);
  right: 0;
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: 12px;
  box-shadow: 0 10px 40px -10px rgba(0,0,0,0.15);
  display: flex;
  flex-direction: column;
  padding: 0.5rem;
  opacity: 0;
  visibility: hidden;
  transform: translateY(-10px);
  transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
  z-index: 100;
  cursor: default;
}

.flowexa-ui-dropdown-content.active {
  opacity: 1;
  visibility: visible;
  transform: translateY(0);
}

.flowexa-ui-dropdown-header {
  padding: 0.5rem;
  display: flex;
  flex-direction: column;
}

.flowexa-ui-dropdown-divider {
  height: 1px;
  background: var(--border);
  margin: 0.5rem 0;
}

.flowexa-ui-dropdown-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 0.75rem 1rem;
  color: var(--text-primary);
  text-decoration: none;
  border-radius: 8px;
  font-size: 0.9rem;
  font-weight: 500;
  transition: all 0.2s;
  background: transparent;
  border: none;
  text-align: left;
  cursor: pointer;
  font-family: inherit;
  width: 100%;
}

.flowexa-ui-dropdown-item:hover {
  background: var(--surface-secondary);
  color: var(--primary);
}

.flowexa-ui-dropdown-item.danger:hover {
  color: var(--danger);
  background: rgba(239, 68, 68, 0.1);
}
</style>

<script>
function toggleflowexaUiDropdown(e, dropdownId) {
    e.stopPropagation();

    document.querySelectorAll('.flowexa-ui-dropdown-content').forEach(el => {
        if (el.id !== dropdownId) el.classList.remove('active');
    });

    const dropdown = document.getElementById(dropdownId);
    if (dropdown) {
        dropdown.classList.toggle('active');
    }
}

document.addEventListener('click', function(e) {
    const isClickInsideDropdown = e.target.closest('.flowexa-ui-dropdown-content');
    if (!isClickInsideDropdown) {
        document.querySelectorAll('.flowexa-ui-dropdown-content').forEach(el => {
            el.classList.remove('active');
        });
    }
});
</script>
@endonce
