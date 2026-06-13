@php
  $title = $title ?? 'Connection Lost';
  $message = $message ?? 'It looks like you are offline. Please check your internet connection.';
  $class = $class ?? '';
@endphp

<div class="synkra-offline-wrapper {{ $class }}" id="synkraOfflineState">
  <div class="synkra-offline-icon-container">
    <i class="fa-solid fa-wifi synkra-offline-icon"></i>
    <div class="synkra-offline-slash"></div>
  </div>
  <h2 class="synkra-offline-title">{{ $title }}</h2>
  <p class="synkra-offline-message">{{ $message }}</p>
  <button class="synkra-offline-retry" onclick="window.location.reload()">
    <i class="fa-solid fa-rotate-right"></i> Try Again
  </button>
</div>

<script>
window.addEventListener('online', () => {
  const offlineWrapper = document.getElementById('synkraOfflineState');
  if (offlineWrapper) offlineWrapper.style.display = 'none';
});
window.addEventListener('offline', () => {
  const offlineWrapper = document.getElementById('synkraOfflineState');
  if (offlineWrapper) offlineWrapper.style.display = 'flex';
});
</script>

<style>
.synkra-offline-wrapper {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 3rem 2rem;
  text-align: center;
  background: var(--surface-secondary);
  border-radius: 16px;
  border: 1px dashed var(--danger);
  max-width: 400px;
  margin: 0 auto;
}

.synkra-offline-icon-container {
  position: relative;
  font-size: 3.5rem;
  color: var(--text-secondary);
  margin-bottom: 1.5rem;
  width: 80px;
  height: 80px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.synkra-offline-icon {
  animation: synkra-wifi-pulse 2s infinite alternate;
}

.synkra-offline-slash {
  position: absolute;
  top: 50%;
  left: 50%;
  width: 100%;
  height: 6px;
  background-color: var(--danger);
  border-radius: 3px;
  transform: translate(-50%, -50%) rotate(-45deg);
  border: 3px solid var(--surface-secondary);
}

.synkra-offline-title {
  font-size: 1.5rem;
  font-weight: 700;
  color: var(--headings);
  margin: 0 0 0.5rem 0;
}

.synkra-offline-message {
  color: var(--text-secondary);
  font-size: 0.95rem;
  margin: 0 0 1.5rem 0;
}

.synkra-offline-retry {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: var(--surface);
  border: 1px solid var(--border);
  color: var(--text-primary);
  padding: 8px 16px;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
}

.synkra-offline-retry:hover {
  background: var(--text-primary);
  color: var(--background);
  border-color: var(--text-primary);
}

.synkra-offline-retry:hover i {
  animation: synkra-spin 1s linear infinite;
}

@keyframes synkra-wifi-pulse {
  0% { opacity: 0.4; transform: scale(0.9); }
  100% { opacity: 0.8; transform: scale(1); }
}

@keyframes synkra-spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
