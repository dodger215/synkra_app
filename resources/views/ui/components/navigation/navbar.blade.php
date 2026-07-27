@php
  $user = auth()->user() ?? (object)['name' => 'John Doe', 'email' => 'john@example.com'];
  $hasNotifications = session('has_notifications', false);
  $notificationCount = session('notification_count', 0);
  $hasMessages = session('has_messages', false);
  $messageCount = session('message_count', 0);
@endphp

<header class="flowexa-navbar">
  <div class="flowexa-navbar-left">
    <!-- Mobile Hamburger Toggle -->
    <button class="flowexa-mobile-toggle-btn" onclick="toggleMobileSidebar()" aria-label="Toggle Menu">
      <i class="fa-solid fa-bars"></i>
    </button>

    <div class="flowexa-desktop-only">
      <x-ui.breadcrumb />
    </div>
  </div>

  <div class="flowexa-navbar-center flowexa-desktop-only">
    <div class="flowexa-navbar-search-wrapper">
      <x-ui.search placeholder="Search workspace, orders, items..." />
    </div>
  </div>

  <div class="flowexa-navbar-right">

    <x-ui.dropdown id="notificationsDropdown">
        <x-slot:trigger>
            <button class="flowexa-navbar-icon-btn">
                <i class="fa-regular fa-bell"></i>
                @if($hasNotifications)
                    <x-ui.badge count="{{ $notificationCount }}" type="danger" />
                @endif
            </button>
        </x-slot:trigger>
        <x-slot:header>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <strong style="color: var(--headings); font-size: 0.95rem;">Notifications</strong>
                <span style="color: var(--primary); font-size: 0.8rem; cursor: pointer;">Mark all read</span>
            </div>
        </x-slot:header>
        <div style="padding: 1.5rem 1rem; text-align: center; color: var(--text-secondary); font-size: 0.85rem;">
            <i class="fa-solid fa-bell-slash" style="display: block; font-size: 1.5rem; margin-bottom: 0.5rem; opacity: 0.5;"></i>
            No new notifications
        </div>
    </x-ui.dropdown>

    <x-ui.dropdown id="messagesDropdown">
        <x-slot:trigger>
            <button class="flowexa-navbar-icon-btn">
                <i class="fa-regular fa-envelope"></i>
                @if($hasMessages)
                    <x-ui.badge count="{{ $messageCount }}" type="danger" />
                @endif
            </button>
        </x-slot:trigger>
        <x-slot:header>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <strong style="color: var(--headings); font-size: 0.95rem;">Messages</strong>
                <span style="color: var(--primary); font-size: 0.8rem; cursor: pointer;"><i class="fa-solid fa-pen-to-square"></i> New</span>
            </div>
        </x-slot:header>
        <div style="padding: 1.5rem 1rem; text-align: center; color: var(--text-secondary); font-size: 0.85rem;">
            <i class="fa-solid fa-inbox" style="display: block; font-size: 1.5rem; margin-bottom: 0.5rem; opacity: 0.5;"></i>
            Your inbox is empty
        </div>
    </x-ui.dropdown>

    {{-- Settings Icon --}}
    <a href="{{ route('settings.index') }}" id="navSettingsLink"
       title="Settings"
       style="display:flex;align-items:center;justify-content:center;color:var(--text-secondary);font-size:1.2rem;text-decoration:none;transition:color .2s;position:relative;"
       onmouseover="this.style.color='var(--primary)'"
       onmouseout="this.style.color='var(--text-secondary)'"
       @if(request()->routeIs('settings.*')) style="color:var(--primary)!important;" @endif>
        <i class="fa-solid fa-gear" @if(request()->routeIs('settings.*')) style="animation:spin-slow 4s linear infinite;" @endif></i>
    </a>

    <x-ui.dropdown id="profileDropdown">
        <x-slot:trigger>
            <div class="flowexa-navbar-profile">
                <div class="flowexa-avatar">{{ substr($user->name, 0, 1) }}</div>
                <div class="flowexa-profile-info">
                    <span class="flowexa-profile-name">{{ $user->name }}</span>
                    <span class="flowexa-profile-role">{{ auth()->check() ? ucfirst(auth()->user()->role->value ?? 'Guest') : 'Guest' }}</span>
                </div>
                <i class="fa-solid fa-chevron-down flowexa-profile-chevron"></i>
            </div>
        </x-slot:trigger>
        <x-slot:header>
            <strong style="color: var(--headings); font-size: 0.95rem;">{{ $user->name }}</strong>
            <span style="color: var(--text-secondary); font-size: 0.8rem; margin-top: 2px; display: block;">{{ $user->email }}</span>
        </x-slot:header>

        <a href="{{ route('settings.profile.edit') }}" class="flowexa-ui-dropdown-item">
            <i class="fa-solid fa-user" style="width: 16px;"></i> Profile Settings
        </a>
        <a href="{{ route('settings.workspace.edit') }}" class="flowexa-ui-dropdown-item">
            <i class="fa-solid fa-building" style="width: 16px;"></i> Shop Settings
        </a>
        <a href="{{ route('settings.subaccounts.index') }}" class="flowexa-ui-dropdown-item">
            <i class="fa-solid fa-credit-card" style="width: 16px;"></i> Account Settings
        </a>
        <div class="flowexa-ui-dropdown-divider"></div>
        <form method="POST" action="{{ route('logout') }}" id="logoutForm" style="margin: 0;">
            @csrf
            <button type="submit" class="flowexa-ui-dropdown-item danger">
                <i class="fa-solid fa-right-from-bracket" style="width: 16px;"></i> Sign Out
            </button>
        </form>
    </x-ui.dropdown>
  </div>
</header>

<style>
.flowexa-navbar {
  height: 70px;
  background-color: rgba(var(--surface-rgb, 255, 255, 255), 0.85); /* Fallback */
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  border: none;
  border-radius: 20px;
  margin: 16px 2rem 0 1rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 1.5rem;
  position: sticky;
  top: 16px;
  z-index: 20;
  box-shadow: 0 8px 30px -4px rgba(0,0,0,0.06); /* Soft modern shadow */
}

/* Use JS to inject RGB variables for surface if needed, or rely on pure var(--surface) if it supports transparency */
[data-theme="dark"] .flowexa-navbar {
  background-color: rgba(17, 17, 17, 0.85);
}
:root, [data-theme="light"] .flowexa-navbar {
  background-color: rgba(255, 255, 255, 0.85);
}

.flowexa-navbar-left {
  display: flex;
  align-items: center;
  gap: 1.5rem;
  flex: 1;
}

.flowexa-navbar-center {
  flex: 1;
  display: flex;
  justify-content: center;
}

.flowexa-navbar-search-wrapper {
  width: 100%;
  max-width: 450px;
}

.flowexa-navbar-right {
  display: flex;
  align-items: center;
  justify-content: flex-end;
  gap: 1.5rem;
  flex: 1;
}

.flowexa-navbar-icon-btn {
  background: transparent;
  border: none;
  color: var(--text-secondary);
  font-size: 1.25rem;
  cursor: pointer;
  position: relative;
  transition: color 0.2s;
  padding: 0;
}

.flowexa-navbar-icon-btn:hover {
  color: var(--primary);
}

.flowexa-navbar-badge {
  position: absolute;
  top: -5px;
  right: -5px;
  background-color: var(--danger);
  color: white;
  font-size: 0.65rem;
  font-weight: bold;
  padding: 2px 5px;
  border-radius: 10px;
  border: 2px solid var(--surface);
}

.flowexa-navbar-profile {
  display: flex;
  align-items: center;
  gap: 12px;
  cursor: pointer;
  padding-left: 1.5rem;
  border-left: 1px solid var(--border);
  transition: opacity 0.2s;
}

.flowexa-navbar-profile:hover {
  opacity: 0.8;
}

.flowexa-avatar {
  width: 36px;
  height: 36px;
  background-color: var(--primary);
  color: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: 1rem;
}

.flowexa-profile-info {
  display: flex;
  flex-direction: column;
}

.flowexa-profile-name {
  font-size: 0.9rem;
  font-weight: 600;
  color: var(--headings);
}

.flowexa-profile-role {
  font-size: 0.75rem;
  color: var(--text-secondary);
}

.flowexa-profile-chevron {
  font-size: 0.7rem;
  color: var(--text-secondary);
  margin-left: 5px;
}

.flowexa-mobile-toggle-btn {
  display: none;
  background: transparent;
  border: none;
  color: var(--text-primary);
  font-size: 1.25rem;
  cursor: pointer;
  padding: 0.5rem;
  margin-right: 10px;
}

@media (max-width: 768px) {
  .flowexa-navbar {
    padding: 0 1rem;
    margin: 16px;
  }
  .flowexa-mobile-toggle-btn { display: block; }
  .flowexa-desktop-only { display: none; }
  .flowexa-profile-info { display: none; }
  .flowexa-navbar-profile { padding-left: 0; border-left: none; gap: 0; }
  .flowexa-navbar-right { gap: 1rem; }
}

@keyframes spin-slow {
  from { transform: rotate(0deg); }
  to   { transform: rotate(360deg); }
}

#navSettingsLink { outline: none; }

</style>
