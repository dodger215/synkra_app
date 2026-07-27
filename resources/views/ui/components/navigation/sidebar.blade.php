@php
  $menu = $sidebarMenu ?? [];
  $currentPath = trim(request()->path(), '/');
@endphp

<div class="flowexa-sidebar-overlay" id="flowexaSidebarOverlay" onclick="toggleMobileSidebar()"></div>
<aside class="flowexa-sidebar" id="flowexaSidebar">
  <div class="flowexa-sidebar-header">
    <div class="flowexa-sidebar-logo">
      <i class="fa-solid fa-cube text-primary"></i>
      <span class="flowexa-sidebar-brand">flowexa</span>
    </div>
    <!-- The hamburger controls the view of the slide to show text with icon or icon with no text -->
    <x-ui.hamburger id="sidebarToggleBtn" onChange="toggleflowexaSidebar(this.checked)" />
  </div>

  <nav class="flowexa-sidebar-nav">
    @foreach($menu as $item)
      @if(isset($item['subitems']))
        <div class="flowexa-sidebar-group">
          <div class="flowexa-sidebar-link" onclick="toggleSubmenu(this)">
            <div class="flowexa-sidebar-link-content">
              <span class="flowexa-sidebar-icon-wrap">
                <i class="{{ $item['icon'] }}"></i>
                @if(!empty($item['indicator']['count']))
                  @php
                    $indicatorTooltip = collect([
                        !empty($item['indicator']['critical']) ? $item['indicator']['critical'] . ' critical' : null,
                        !empty($item['indicator']['low']) ? $item['indicator']['low'] . ' low' : null,
                        !empty($item['indicator']['active_alerts']) ? $item['indicator']['active_alerts'] . ' active' : null,
                    ])->filter()->implode(', ');
                  @endphp
                  <span class="flowexa-sidebar-indicator-dot flowexa-sidebar-indicator-dot-{{ $item['indicator']['variant'] ?? 'info' }}" title="{{ $indicatorTooltip ?: $item['indicator']['count'] . ' alert(s)' }}"></span>
                @endif
              </span>
              <span class="flowexa-sidebar-text">{{ $item['label'] }}</span>
              @if(!empty($item['indicator']))
                @include('ui.components.navigation.sidebar-indicator', ['indicator' => $item['indicator']])
              @endif
            </div>
            <i class="fa-solid fa-chevron-down flowexa-sidebar-chevron"></i>
          </div>
          <div class="flowexa-sidebar-submenu">
            @foreach($item['subitems'] as $sub)
              @php
                $subPath = trim(parse_url($sub['url'], PHP_URL_PATH) ?? '', '/');
                $isActive = $subPath !== '' && $subPath !== '#' && ($currentPath === $subPath || str_starts_with($currentPath, $subPath . '/'));
              @endphp
              <a href="{{ $sub['url'] }}" class="flowexa-sidebar-sublink {{ $isActive ? 'flowexa-sidebar-sublink-active' : '' }}">
                <span class="flowexa-sidebar-text">{{ $sub['label'] }}</span>
                @if(!empty($sub['indicator']))
                  @include('ui.components.navigation.sidebar-indicator', ['indicator' => $sub['indicator']])
                @endif
              </a>
            @endforeach
          </div>
        </div>
      @else
        <a href="{{ $item['url'] }}" class="flowexa-sidebar-link">
          <div class="flowexa-sidebar-link-content">
            <i class="{{ $item['icon'] }}"></i>
            <span class="flowexa-sidebar-text">{{ $item['label'] }}</span>
          </div>
        </a>
      @endif
    @endforeach
  </nav>

  <div class="flowexa-sidebar-footer">
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
      @csrf
    </form>
    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flowexa-sidebar-link">
      <div class="flowexa-sidebar-link-content">
        <i class="fa-solid fa-arrow-right-from-bracket"></i>
        <span class="flowexa-sidebar-text">Logout</span>
      </div>
    </a>
  </div>
</aside>

<script>
if (typeof toggleflowexaSidebar !== 'function') {
  function toggleflowexaSidebar(isCollapsed) {
    const sidebar = document.getElementById('flowexaSidebar');
    const mainContent = document.getElementById('flowexaMainContent');
    if (isCollapsed) {
      sidebar.classList.add('collapsed');
      if (mainContent) mainContent.classList.add('expanded');
    } else {
      sidebar.classList.remove('collapsed');
      if (mainContent) mainContent.classList.remove('expanded');
    }
  }

  function toggleMobileSidebar() {
    const sidebar = document.getElementById('flowexaSidebar');
    const overlay = document.getElementById('flowexaSidebarOverlay');
    if (sidebar) sidebar.classList.toggle('mobile-open');
    if (overlay) overlay.classList.toggle('mobile-open');
  }

  function toggleSubmenu(el) {
    const sidebar = document.getElementById('flowexaSidebar');
    // If sidebar is collapsed, open it before showing submenu
    if (sidebar.classList.contains('collapsed')) {
      const toggleBtn = document.getElementById('sidebarToggleBtn');
      if(toggleBtn) {
        toggleBtn.checked = false;
        toggleflowexaSidebar(false);
      }
    }
    const group = el.closest('.flowexa-sidebar-group');
    group.classList.toggle('open');
  }
}
</script>

<style>
.flowexa-sidebar {
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

.flowexa-sidebar.collapsed {
  width: 80px;
}

.flowexa-sidebar-overlay {
  display: none;
  position: fixed;
  top: 0; left: 0; width: 100vw; height: 100vh;
  background-color: rgba(0,0,0,0.5);
  backdrop-filter: blur(2px);
  -webkit-backdrop-filter: blur(2px);
  z-index: 45;
}

@media (max-width: 768px) {
  .flowexa-sidebar {
    transform: translateX(-100%);
    width: 260px !important;
  }
  .flowexa-sidebar.mobile-open {
    transform: translateX(0);
  }
  .flowexa-sidebar-overlay.mobile-open {
    display: block;
  }
  /* Hide the desktop collapse hamburger on mobile */
  .flowexa-sidebar-header > *:nth-child(2) {
    display: none;
  }
}

.flowexa-sidebar-header {
  height: 70px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 20px;
  border-bottom: none;
}

.flowexa-sidebar-logo {
  display: flex;
  align-items: center;
  gap: 12px;
  font-size: 1.25rem;
  font-weight: 800;
  color: var(--headings);
  overflow: hidden;
  white-space: nowrap;
}

.flowexa-sidebar-logo i {
  color: var(--primary);
  font-size: 1.5rem;
  flex-shrink: 0;
}

.flowexa-sidebar.collapsed .flowexa-sidebar-brand {
  opacity: 0;
  display: none;
}

.flowexa-sidebar.collapsed .flowexa-sidebar-header {
  justify-content: center;
  padding: 0;
}
.flowexa-sidebar.collapsed .flowexa-sidebar-logo {
  display: none;
}

.flowexa-sidebar-nav {
  padding: 1.5rem 1rem;
  flex-grow: 1;
  overflow-y: auto;
  overflow-x: hidden;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

/* Hide scrollbar for clean look */
.flowexa-sidebar-nav::-webkit-scrollbar {
  width: 4px;
}
.flowexa-sidebar-nav::-webkit-scrollbar-thumb {
  background: var(--border);
  border-radius: 4px;
}

.flowexa-sidebar-link {
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

.flowexa-sidebar-link-content {
  display: flex;
  align-items: center;
  gap: 14px;
  flex: 1;
  min-width: 0;
}

.flowexa-sidebar-icon-wrap {
  position: relative;
  width: 24px;
  height: 24px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.flowexa-sidebar-indicator-dot {
  position: absolute;
  top: -2px;
  right: -4px;
  width: 8px;
  height: 8px;
  border-radius: 50%;
  border: 2px solid var(--surface);
}

.flowexa-sidebar-indicator-dot-danger { background: #ef4444; }
.flowexa-sidebar-indicator-dot-warning { background: #f59e0b; }
.flowexa-sidebar-indicator-dot-info { background: #3b82f6; }

.flowexa-sidebar-item-badge {
  margin-left: auto;
  flex-shrink: 0;
}

.flowexa-sidebar-indicator-group {
  display: inline-flex;
  align-items: center;
  gap: .35rem;
  margin-left: auto;
  flex-shrink: 0;
}

.flowexa-sidebar-indicator-numbers {
  display: inline-flex;
  align-items: center;
  gap: .2rem;
}

.flowexa-sidebar-indicator-num {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 16px;
  height: 16px;
  padding: 0 4px;
  border-radius: 999px;
  font-size: .62rem;
  font-weight: 700;
  line-height: 1;
}

.flowexa-sidebar-indicator-num-danger {
  background: rgba(239, 68, 68, .15);
  color: #dc2626;
}

.flowexa-sidebar-indicator-num-warning {
  background: rgba(245, 158, 11, .15);
  color: #d97706;
}

.flowexa-sidebar-indicator-num-info {
  background: rgba(59, 130, 246, .12);
  color: #2563eb;
}

.flowexa-sidebar.collapsed .flowexa-sidebar-item-badge,
.flowexa-sidebar.collapsed .flowexa-sidebar-indicator-numbers {
  display: none;
}

.flowexa-sidebar.collapsed .flowexa-sidebar-indicator-dot {
  display: block;
}

.flowexa-sidebar-link i {
  font-size: 1.1rem;
  width: 24px;
  text-align: center;
  flex-shrink: 0;
  transition: color 0.2s ease, transform 0.2s ease;
}

.flowexa-sidebar-link:hover {
  background-color: var(--surface-secondary);
  color: var(--text-primary);
}

.flowexa-sidebar-link:hover i {
  color: var(--primary);
  transform: scale(1.1);
}

.flowexa-sidebar.collapsed .flowexa-sidebar-text,
.flowexa-sidebar.collapsed .flowexa-sidebar-chevron {
  display: none;
}

.flowexa-sidebar.collapsed .flowexa-sidebar-link {
  justify-content: center;
  padding: 12px 0;
}
.flowexa-sidebar.collapsed .flowexa-sidebar-link-content {
  justify-content: center;
}

.flowexa-sidebar-submenu {
  display: none;
  flex-direction: column;
  padding-left: 45px;
  gap: 5px;
  margin-top: 5px;
}

.flowexa-sidebar-group.open .flowexa-sidebar-submenu {
  display: flex;
}

.flowexa-sidebar-group.open .flowexa-sidebar-chevron {
  transform: rotate(180deg);
}

.flowexa-sidebar-chevron {
  font-size: 0.8rem !important;
  transition: transform 0.3s;
}

.flowexa-sidebar-sublink {
  color: var(--text-secondary);
  text-decoration: none;
  padding: 8px;
  font-size: 0.9rem;
  border-radius: 6px;
  transition: all 0.2s;
  white-space: nowrap;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: .5rem;
}

.flowexa-sidebar-sublink:hover {
  color: var(--primary);
  background: var(--surface-secondary);
}

.flowexa-sidebar-sublink-active {
  color: var(--primary) !important;
  background: rgba(249, 115, 22, 0.08);
  font-weight: 600;
}

.flowexa-sidebar.collapsed .flowexa-sidebar-submenu {
  display: none !important;
}

.flowexa-sidebar-footer {
  padding: 1rem;
  border-top: none;
}
</style>
