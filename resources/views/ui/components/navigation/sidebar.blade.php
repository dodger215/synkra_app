@php

  $menu = $sidebarMenu ?? [];
@endphp

<div class="synkra-sidebar-overlay" id="synkraSidebarOverlay" onclick="toggleMobileSidebar()"></div>
<aside class="synkra-sidebar" id="synkraSidebar">
  <div class="synkra-sidebar-header">
    <div class="synkra-sidebar-logo">
      <i class="fa-solid fa-cube text-primary"></i>
      <span class="synkra-sidebar-brand">Synkra</span>
    </div>
    <!-- The hamburger controls the view of the slide to show text with icon or icon with no text -->
    <x-ui.hamburger id="sidebarToggleBtn" onChange="toggleSynkraSidebar(this.checked)" />
  </div>

  <nav class="synkra-sidebar-nav">
    @foreach($menu as $item)
      @if(isset($item['subitems']))
        <div class="synkra-sidebar-group">
          <div class="synkra-sidebar-link" onclick="toggleSubmenu(this)">
            <div class="synkra-sidebar-link-content">
              <i class="{{ $item['icon'] }}"></i>
              <span class="synkra-sidebar-text">{{ $item['label'] }}</span>
            </div>
            <i class="fa-solid fa-chevron-down synkra-sidebar-chevron"></i>
          </div>
          <div class="synkra-sidebar-submenu">
            @foreach($item['subitems'] as $sub)
              <a href="{{ $sub['url'] }}" class="synkra-sidebar-sublink">
                <span class="synkra-sidebar-text">{{ $sub['label'] }}</span>
              </a>
            @endforeach
          </div>
        </div>
      @else
        <a href="{{ $item['url'] }}" class="synkra-sidebar-link">
          <div class="synkra-sidebar-link-content">
            <i class="{{ $item['icon'] }}"></i>
            <span class="synkra-sidebar-text">{{ $item['label'] }}</span>
          </div>
        </a>
      @endif
    @endforeach
  </nav>

  <div class="synkra-sidebar-footer">
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
      @csrf
    </form>
    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="synkra-sidebar-link">
      <div class="synkra-sidebar-link-content">
        <i class="fa-solid fa-arrow-right-from-bracket"></i>
        <span class="synkra-sidebar-text">Logout</span>
      </div>
    </a>
  </div>
</aside>

<script>
if (typeof toggleSynkraSidebar !== 'function') {
  function toggleSynkraSidebar(isCollapsed) {
    const sidebar = document.getElementById('synkraSidebar');
    const mainContent = document.getElementById('synkraMainContent');
    if (isCollapsed) {
      sidebar.classList.add('collapsed');
      if (mainContent) mainContent.classList.add('expanded');
    } else {
      sidebar.classList.remove('collapsed');
      if (mainContent) mainContent.classList.remove('expanded');
    }
  }

  function toggleMobileSidebar() {
    const sidebar = document.getElementById('synkraSidebar');
    const overlay = document.getElementById('synkraSidebarOverlay');
    if (sidebar) sidebar.classList.toggle('mobile-open');
    if (overlay) overlay.classList.toggle('mobile-open');
  }

  function toggleSubmenu(el) {
    const sidebar = document.getElementById('synkraSidebar');
    // If sidebar is collapsed, open it before showing submenu
    if (sidebar.classList.contains('collapsed')) {
      const toggleBtn = document.getElementById('sidebarToggleBtn');
      if(toggleBtn) {
        toggleBtn.checked = false;
        toggleSynkraSidebar(false);
      }
    }
    const group = el.closest('.synkra-sidebar-group');
    group.classList.toggle('open');
  }
}
</script>

<style>
.synkra-sidebar {
  width: 260px;
  background-color: var(--surface);
  border: none;
  height: calc(100vh - 32px);
  position: fixed;
  top: 16px;
  left: 16px;
  border-radius: 20px;
  display: flex;
  flex-direction: column;
  transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  z-index: 50;
  box-shadow: 0 10px 40px -10px rgba(0,0,0,0.08); /* Deep premium shadow */
}

.synkra-sidebar.collapsed {
  width: 80px;
}

.synkra-sidebar-overlay {
  display: none;
  position: fixed;
  top: 0; left: 0; width: 100vw; height: 100vh;
  background-color: rgba(0,0,0,0.5);
  backdrop-filter: blur(2px);
  -webkit-backdrop-filter: blur(2px);
  z-index: 45;
}

@media (max-width: 768px) {
  .synkra-sidebar {
    transform: translateX(-100%);
    width: 260px !important;
  }
  .synkra-sidebar.mobile-open {
    transform: translateX(0);
  }
  .synkra-sidebar-overlay.mobile-open {
    display: block;
  }
  /* Hide the desktop collapse hamburger on mobile */
  .synkra-sidebar-header > *:nth-child(2) {
    display: none;
  }
}

.synkra-sidebar-header {
  height: 70px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 20px;
  border-bottom: none;
}

.synkra-sidebar-logo {
  display: flex;
  align-items: center;
  gap: 12px;
  font-size: 1.25rem;
  font-weight: 800;
  color: var(--headings);
  overflow: hidden;
  white-space: nowrap;
}

.synkra-sidebar-logo i {
  color: var(--primary);
  font-size: 1.5rem;
  flex-shrink: 0;
}

.synkra-sidebar.collapsed .synkra-sidebar-brand {
  opacity: 0;
  display: none;
}

.synkra-sidebar.collapsed .synkra-sidebar-header {
  justify-content: center;
  padding: 0;
}
.synkra-sidebar.collapsed .synkra-sidebar-logo {
  display: none;
}

.synkra-sidebar-nav {
  padding: 1.5rem 1rem;
  flex-grow: 1;
  overflow-y: auto;
  overflow-x: hidden;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

/* Hide scrollbar for clean look */
.synkra-sidebar-nav::-webkit-scrollbar {
  width: 4px;
}
.synkra-sidebar-nav::-webkit-scrollbar-thumb {
  background: var(--border);
  border-radius: 4px;
}

.synkra-sidebar-link {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 10px 14px;
  margin: 2px 8px; /* Modern Bubble styling */
  border-radius: 10px;
  color: var(--text-secondary);
  text-decoration: none;
  cursor: pointer;
  transition: all 0.2s ease;
  white-space: nowrap;
  font-weight: 500;
}

.synkra-sidebar-link-content {
  display: flex;
  align-items: center;
  gap: 14px;
}

.synkra-sidebar-link i {
  font-size: 1.1rem;
  width: 24px;
  text-align: center;
  flex-shrink: 0;
  transition: color 0.2s ease, transform 0.2s ease;
}

.synkra-sidebar-link:hover {
  background-color: var(--surface-secondary);
  color: var(--text-primary);
}

.synkra-sidebar-link:hover i {
  color: var(--primary);
  transform: scale(1.1);
}

.synkra-sidebar.collapsed .synkra-sidebar-text,
.synkra-sidebar.collapsed .synkra-sidebar-chevron {
  display: none;
}

.synkra-sidebar.collapsed .synkra-sidebar-link {
  justify-content: center;
  padding: 12px 0;
}
.synkra-sidebar.collapsed .synkra-sidebar-link-content {
  justify-content: center;
}

.synkra-sidebar-submenu {
  display: none;
  flex-direction: column;
  padding-left: 45px;
  gap: 5px;
  margin-top: 5px;
}

.synkra-sidebar-group.open .synkra-sidebar-submenu {
  display: flex;
}

.synkra-sidebar-group.open .synkra-sidebar-chevron {
  transform: rotate(180deg);
}

.synkra-sidebar-chevron {
  font-size: 0.8rem !important;
  transition: transform 0.3s;
}

.synkra-sidebar-sublink {
  color: var(--text-secondary);
  text-decoration: none;
  padding: 8px;
  font-size: 0.9rem;
  border-radius: 6px;
  transition: all 0.2s;
  white-space: nowrap;
}

.synkra-sidebar-sublink:hover {
  color: var(--primary);
  background: var(--surface-secondary);
}

.synkra-sidebar.collapsed .synkra-sidebar-submenu {
  display: none !important;
}

.synkra-sidebar-footer {
  padding: 1rem;
  border-top: none;
}
</style>
