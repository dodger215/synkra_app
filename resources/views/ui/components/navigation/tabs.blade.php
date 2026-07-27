@php
  $tabs = $tabs ?? [
    ['id' => 'tab1', 'label' => 'Profile', 'icon' => 'fa-regular fa-user'],
    ['id' => 'tab2', 'label' => 'Settings', 'icon' => 'fa-solid fa-sliders'],
    ['id' => 'tab3', 'label' => 'Notifications', 'icon' => 'fa-regular fa-bell'],
  ];
  $activeTab = $activeTab ?? 'tab1';
  $id = $id ?? 'tabs_' . uniqid();
  $class = $class ?? '';
@endphp

<div class="flowexa-tabs-container {{ $class }}" id="{{ $id }}">
  <div class="flowexa-tabs-list" role="tablist">
    @foreach($tabs as $tab)
      <button
        type="button"
        role="tab"
        aria-selected="{{ $activeTab == $tab['id'] ? 'true' : 'false' }}"
        class="flowexa-tab-trigger {{ $activeTab == $tab['id'] ? 'flowexa-tab-active' : '' }}"
        onclick="switchflowexaTab(this, '{{ $tab['id'] }}', '{{ $id }}')"
      >
        @if(isset($tab['icon']) && $tab['icon'])
          <i class="{{ $tab['icon'] }} flowexa-tab-icon"></i>
        @endif
        <span>{{ $tab['label'] }}</span>
      </button>
    @endforeach
  </div>
</div>

<script>
if (typeof switchflowexaTab !== 'function') {
  function switchflowexaTab(buttonEl, tabId, containerId) {
    const container = document.getElementById(containerId);
    const triggers = container.querySelectorAll('.flowexa-tab-trigger');

    triggers.forEach(trigger => {
      trigger.classList.remove('flowexa-tab-active');
      trigger.setAttribute('aria-selected', 'false');
    });

    buttonEl.classList.add('flowexa-tab-active');
    buttonEl.setAttribute('aria-selected', 'true');

    // Dispatch a custom event to allow developers to listen to tab switches
    container.dispatchEvent(new CustomEvent('tab-changed', { detail: { tabId: tabId } }));
  }
}
</script>

<style>
.flowexa-tabs-container {
  width: 100%;
  border-bottom: 1px solid var(--border);
}

.flowexa-tabs-list {
  display: flex;
  gap: 1.5rem;
  overflow-x: auto;
  scrollbar-width: none; /* Firefox */
}

.flowexa-tabs-list::-webkit-scrollbar {
  display: none; /* Safari and Chrome */
}

.flowexa-tab-trigger {
  background: transparent;
  border: none;
  border-bottom: 2px solid transparent;
  padding: 0.75rem 0.25rem;
  font-size: 0.9rem;
  font-weight: 600;
  color: var(--text-secondary);
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  transition: all 0.2s ease;
  white-space: nowrap;
  outline: none;
}

.flowexa-tab-trigger:hover {
  color: var(--text-primary);
  border-bottom-color: var(--border);
}

.flowexa-tab-active {
  color: var(--primary) !important;
  border-bottom-color: var(--primary) !important;
}

.flowexa-tab-icon {
  font-size: 1rem;
}
</style>
