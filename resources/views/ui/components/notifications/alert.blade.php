@php
  $type = $type ?? 'info'; // success, info, warning, danger
  $title = $title ?? null;
  $message = $message ?? 'This is a notification message.';
  $dismissible = $dismissible ?? true;
  $id = 'alert_' . uniqid();
  $class = $class ?? '';
  
  $icons = [
    'success' => 'fa-solid fa-circle-check',
    'info' => 'fa-solid fa-circle-info',
    'warning' => 'fa-solid fa-triangle-exclamation',
    'danger' => 'fa-solid fa-circle-xmark',
  ];
  
  $icon = $icons[$type] ?? 'fa-solid fa-circle-info';
@endphp

<div id="{{ $id }}" class="synkra-alert synkra-alert-{{ $type }} {{ $class }}" role="alert">
  <div class="synkra-alert-icon">
    <i class="{{ $icon }}"></i>
  </div>
  <div class="synkra-alert-content">
    @if($title)
      <h4 class="synkra-alert-title">{{ $title }}</h4>
    @endif
    <p class="synkra-alert-message">{{ $message }}</p>
  </div>
  @if($dismissible)
    <button type="button" class="synkra-alert-close" onclick="document.getElementById('{{ $id }}').remove()" aria-label="Close">
      <i class="fa-solid fa-xmark"></i>
    </button>
  @endif
</div>

<style>
.synkra-alert {
  display: flex;
  align-items: flex-start;
  gap: 0.75rem;
  padding: 1rem;
  border-radius: 10px;
  border-width: 1px;
  border-style: solid;
  max-width: 450px;
  margin-bottom: 1rem;
  position: relative;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
  animation: synkra-slide-in 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes synkra-slide-in {
  from {
    transform: translateY(-10px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

.synkra-alert-icon {
  font-size: 1.25rem;
  display: flex;
  align-items: center;
  margin-top: 0.1rem;
}

.synkra-alert-content {
  flex: 1;
}

.synkra-alert-title {
  font-size: 0.95rem;
  font-weight: 700;
  margin: 0 0 0.25rem 0;
}

.synkra-alert-message {
  font-size: 0.85rem;
  margin: 0;
  line-height: 1.4;
}

.synkra-alert-close {
  background: transparent;
  border: none;
  cursor: pointer;
  padding: 0.25rem;
  font-size: 0.9rem;
  opacity: 0.6;
  transition: opacity 0.2s;
  display: flex;
  align-items: center;
  justify-content: center;
  color: inherit;
}

.synkra-alert-close:hover {
  opacity: 1;
}

/* Success Variant */
.synkra-alert-success {
  background-color: rgba(34, 197, 94, 0.1);
  border-color: rgba(34, 197, 94, 0.2);
  color: #15803d;
}
[data-theme="dark"] .synkra-alert-success {
  background-color: rgba(34, 197, 94, 0.15);
  border-color: rgba(34, 197, 94, 0.3);
  color: #4ade80;
}

/* Info Variant */
.synkra-alert-info {
  background-color: rgba(20, 184, 166, 0.1);
  border-color: rgba(20, 184, 166, 0.2);
  color: #0f766e;
}
[data-theme="dark"] .synkra-alert-info {
  background-color: rgba(20, 184, 166, 0.15);
  border-color: rgba(20, 184, 166, 0.3);
  color: #2dd4bf;
}

/* Warning Variant */
.synkra-alert-warning {
  background-color: rgba(245, 158, 11, 0.1);
  border-color: rgba(245, 158, 11, 0.2);
  color: #b45309;
}
[data-theme="dark"] .synkra-alert-warning {
  background-color: rgba(245, 158, 11, 0.15);
  border-color: rgba(245, 158, 11, 0.3);
  color: #fbbf24;
}

/* Danger Variant */
.synkra-alert-danger {
  background-color: rgba(239, 68, 68, 0.1);
  border-color: rgba(239, 68, 68, 0.2);
  color: #b91c1c;
}
[data-theme="dark"] .synkra-alert-danger {
  background-color: rgba(239, 68, 68, 0.15);
  border-color: rgba(239, 68, 68, 0.3);
  color: #f87171;
}
</style>
