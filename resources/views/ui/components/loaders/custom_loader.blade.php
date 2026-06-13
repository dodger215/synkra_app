@php
  $logo = $logo ?? '<i class="fa-solid fa-cube"></i>';
  $text = $text ?? 'Loading Workspace...';
  $class = $class ?? '';
@endphp

<div class="synkra-custom-loader-wrapper {{ $class }}">
  <div class="synkra-custom-loader">
    <div class="synkra-loader-circle-border"></div>
    <div class="synkra-loader-logo-container">
      {!! $logo !!}
    </div>
  </div>
  @if($text)
    <div class="synkra-loader-text">{{ $text }}</div>
  @endif
</div>

<style>
.synkra-custom-loader-wrapper {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 1.5rem;
  padding: 3rem;
}

.synkra-custom-loader {
  position: relative;
  width: 80px;
  height: 80px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.synkra-loader-circle-border {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  border-radius: 50%;
  border: 3px solid transparent;
  border-top-color: var(--primary);
  border-right-color: var(--secondary);
  animation: synkra-spin-border 1.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) infinite;
}

.synkra-loader-circle-border::after {
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
  animation: synkra-spin-border-reverse 2.5s linear infinite;
}

.synkra-loader-logo-container {
  font-size: 2rem;
  color: var(--primary);
  animation: synkra-zoom-pulse 1.2s ease-in-out infinite alternate;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 2;
}

.synkra-loader-text {
  font-weight: 600;
  color: var(--text-secondary);
  font-size: 0.95rem;
  letter-spacing: 1px;
  animation: synkra-pulse-opacity 1.5s ease-in-out infinite alternate;
}

@keyframes synkra-spin-border {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

@keyframes synkra-spin-border-reverse {
  0% { transform: rotate(360deg); }
  100% { transform: rotate(-360deg); }
}

@keyframes synkra-zoom-pulse {
  0% { transform: scale(0.85); filter: drop-shadow(0 0 0 rgba(249, 115, 22, 0)); }
  100% { transform: scale(1.15); filter: drop-shadow(0 0 10px rgba(249, 115, 22, 0.4)); }
}

@keyframes synkra-pulse-opacity {
  0% { opacity: 0.4; }
  100% { opacity: 1; }
}
</style>
