@php
  $user = auth()->user() ?? (object)['name' => 'John Doe', 'email' => 'john@example.com'];
  $hasNotifications = session('has_notifications', false);
  $notificationCount = session('notification_count', 0);
  $hasMessages = session('has_messages', false);
  $messageCount = session('message_count', 0);
@endphp

<header class="synkra-navbar">
  <div class="synkra-navbar-left">
    <!-- Mobile Hamburger Toggle -->
    <button class="synkra-mobile-toggle-btn" onclick="toggleMobileSidebar()" aria-label="Toggle Menu">
      <i class="fa-solid fa-bars"></i>
    </button>
    
    <div class="synkra-desktop-only">
      <x-ui.breadcrumb />
    </div>
  </div>

  <div class="synkra-navbar-center synkra-desktop-only">
    <div class="synkra-navbar-search-wrapper">
      <x-ui.search placeholder="Search workspace, orders, items..." />
    </div>
  </div>
  
  <div class="synkra-navbar-right">
    
    <x-ui.dropdown id="notificationsDropdown">
        <x-slot:trigger>
            <button class="synkra-navbar-icon-btn">
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
            <button class="synkra-navbar-icon-btn">
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
    
    <x-ui.dropdown id="profileDropdown">
        <x-slot:trigger>
            <div class="synkra-navbar-profile">
                <div class="synkra-avatar">{{ substr($user->name, 0, 1) }}</div>
                <div class="synkra-profile-info">
                    <span class="synkra-profile-name">{{ $user->name }}</span>
                    <span class="synkra-profile-role">{{ auth()->check() ? ucfirst(auth()->user()->role->value ?? 'Guest') : 'Guest' }}</span>
                </div>
                <i class="fa-solid fa-chevron-down synkra-profile-chevron"></i>
            </div>
        </x-slot:trigger>
        <x-slot:header>
            <strong style="color: var(--headings); font-size: 0.95rem;">{{ $user->name }}</strong>
            <span style="color: var(--text-secondary); font-size: 0.8rem; margin-top: 2px; display: block;">{{ $user->email }}</span>
        </x-slot:header>
        
        <a href="{{ route('settings.profile.edit') }}" class="synkra-ui-dropdown-item">
            <i class="fa-solid fa-user" style="width: 16px;"></i> Profile Settings
        </a>
        <a href="{{ route('settings.workspace.edit') }}" class="synkra-ui-dropdown-item">
            <i class="fa-solid fa-building" style="width: 16px;"></i> Workspace Settings
        </a>
        <a href="{{ route('settings.subaccounts.index') }}" class="synkra-ui-dropdown-item">
            <i class="fa-solid fa-credit-card" style="width: 16px;"></i> Billing & Payouts
        </a>
        <div class="synkra-ui-dropdown-divider"></div>
        <form method="POST" action="{{ route('logout') }}" id="logoutForm" style="margin: 0;">
            @csrf
            <button type="submit" class="synkra-ui-dropdown-item danger">
                <i class="fa-solid fa-right-from-bracket" style="width: 16px;"></i> Sign Out
            </button>
        </form>
    </x-ui.dropdown>
  </div>
</header>

<style>
.synkra-navbar {
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
  z-index: 40;
  box-shadow: 0 8px 30px -4px rgba(0,0,0,0.06); /* Soft modern shadow */
}

/* Use JS to inject RGB variables for surface if needed, or rely on pure var(--surface) if it supports transparency */
[data-theme="dark"] .synkra-navbar {
  background-color: rgba(17, 17, 17, 0.85);
}
:root, [data-theme="light"] .synkra-navbar {
  background-color: rgba(255, 255, 255, 0.85);
}

.synkra-navbar-left {
  display: flex;
  align-items: center;
  gap: 1.5rem;
  flex: 1;
}

.synkra-navbar-center {
  flex: 1;
  display: flex;
  justify-content: center;
}

.synkra-navbar-search-wrapper {
  width: 100%;
  max-width: 450px;
}

.synkra-navbar-right {
  display: flex;
  align-items: center;
  justify-content: flex-end;
  gap: 1.5rem;
  flex: 1;
}

.synkra-navbar-icon-btn {
  background: transparent;
  border: none;
  color: var(--text-secondary);
  font-size: 1.25rem;
  cursor: pointer;
  position: relative;
  transition: color 0.2s;
  padding: 0;
}

.synkra-navbar-icon-btn:hover {
  color: var(--primary);
}

.synkra-navbar-badge {
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

.synkra-navbar-profile {
  display: flex;
  align-items: center;
  gap: 12px;
  cursor: pointer;
  padding-left: 1.5rem;
  border-left: 1px solid var(--border);
  transition: opacity 0.2s;
}

.synkra-navbar-profile:hover {
  opacity: 0.8;
}

.synkra-avatar {
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

.synkra-profile-info {
  display: flex;
  flex-direction: column;
}

.synkra-profile-name {
  font-size: 0.9rem;
  font-weight: 600;
  color: var(--headings);
}

.synkra-profile-role {
  font-size: 0.75rem;
  color: var(--text-secondary);
}

.synkra-profile-chevron {
  font-size: 0.7rem;
  color: var(--text-secondary);
  margin-left: 5px;
}

.synkra-mobile-toggle-btn {
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
  .synkra-navbar { 
    padding: 0 1rem; 
    margin: 16px; 
  }
  .synkra-mobile-toggle-btn { display: block; }
  .synkra-desktop-only { display: none; }
  .synkra-profile-info { display: none; }
  .synkra-navbar-profile { padding-left: 0; border-left: none; gap: 0; }
  .synkra-navbar-right { gap: 1rem; }
}

</style>
