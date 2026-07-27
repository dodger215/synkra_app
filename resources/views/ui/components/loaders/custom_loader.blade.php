@php
  $logo = $logo ?? '<i class="fa-solid fa-cube"></i>';
  $text = $text ?? 'Loading Workspace...';
  $class = $class ?? '';
@endphp

<div class="flowexa-custom-loader-wrapper {{ $class }}">
  <div class="flowexa-custom-loader">
    <div class="flowexa-loader-circle-border"></div>
    <div class="flowexa-loader-logo-container">
      {!! $logo !!}
    </div>
  </div>
  @if($text)
    <div class="flowexa-loader-text">{{ $text }}</div>
  @endif
</div>

<style>
.flowexa-custom-loader-wrapper {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 1.5rem;
  padding: 3rem;
}

.flowexa-custom-loader {
  position: relative;
  width: 80px;
  height: 80px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.flowexa-loader-circle-border {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  border-radius: 50%;
  border: 3px solid transparent;
  border-top-color: var(--primary);
  border-right-color: var(--secondary);
  animation: flowexa-spin-border 1.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) infinite;
}

.flowexa-loader-circle-border::after {
  content: '';
  position: absolute;
  top: -3px;
  left: -3px;
  width: 100%;
  height: 100%;
  border-radius: 50%;
  border: 3px solid transparent;
  border-bottom-color: var(--primary);
  opacity: 0.5;
  animation: flowexa-spin-border-reverse 2.5s linear infinite;
}

.flowexa-loader-logo-container {
  font-size: 2rem;
  color: var(--primary);
  animation: flowexa-zoom-pulse 1.2s ease-in-out infinite alternate;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 2;
}

.flowexa-loader-text {
  font-weight: 600;
  color: var(--text-secondary);
  font-size: 0.95rem;
  letter-spacing: 1px;
  animation: flowexa-pulse-opacity 1.5s ease-in-out infinite alternate;
}

@keyframes flowexa-spin-border {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

@keyframes flowexa-spin-border-reverse {
  0% { transform: rotate(360deg); }
  100% { transform: rotate(-360deg); }
}

@keyframes flowexa-zoom-pulse {
  0% { transform: scale(0.85); filter: drop-shadow(0 0 0 rgba(249, 115, 22, 0)); }
  100% { transform: scale(1.15); filter: drop-shadow(0 0 10px rgba(249, 115, 22, 0.4)); }
}

@keyframes flowexa-pulse-opacity {
  0% { opacity: 0.4; }
  100% { opacity: 1; }
}
</style>
