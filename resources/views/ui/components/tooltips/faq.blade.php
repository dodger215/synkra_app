@php
  $tooltip = $tooltip ?? 'Help & FAQ';
  $icon = $icon ?? 'fa-solid fa-question';
  $url = $url ?? '#';
  $class = $class ?? '';
@endphp

<a href="{{ $url }}" class="synkra-faq-btn-wrapper {{ $class }}" aria-label="{{ $tooltip }}">
  <div class="synkra-faq-btn">
    <i class="{{ $icon }}"></i>
    <span class="synkra-faq-tooltip">{{ $tooltip }}</span>
  </div>
</a>

<style>
.synkra-faq-btn-wrapper {
  text-decoration: none;
  display: inline-block;
}

.synkra-faq-btn {
  width: 44px;
  height: 44px;
  border-radius: 50%;
  background: var(--primary);
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  box-shadow: 0 4px 10px rgba(249, 115, 22, 0.3);
  position: relative;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  color: #ffffff;
  font-size: 1.15rem;
}

.synkra-faq-btn:hover {
  background: var(--primary-hover);
  transform: scale(1.1);
  box-shadow: 0 6px 14px rgba(234, 88, 12, 0.4);
}

.synkra-faq-btn i {
  transition: transform 0.3s ease;
}

.synkra-faq-btn:hover i {
  animation: synkra-jello 0.8s both;
}

@keyframes synkra-jello {
  0% { transform: scale3d(1, 1, 1); }
  30% { transform: scale3d(0.75, 1.25, 1); }
  40% { transform: scale3d(1.25, 0.75, 1); }
  50% { transform: scale3d(0.85, 1.15, 1); }
  65% { transform: scale3d(1.05, 0.95, 1); }
  75% { transform: scale3d(0.95, 1.05, 1); }
  100% { transform: scale3d(1, 1, 1); }
}

.synkra-faq-tooltip {
  position: absolute;
  bottom: calc(100% + 12px);
  right: 50%;
  transform: translateX(50%);
  opacity: 0;
  visibility: hidden;
  background-color: var(--text-primary);
  color: var(--background);
  font-size: 0.75rem;
  font-weight: 600;
  padding: 6px 10px;
  border-radius: 6px;
  white-space: nowrap;
  box-shadow: 0 4px 6px rgba(0,0,0,0.15);
  transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.synkra-faq-tooltip::after {
  content: "";
  position: absolute;
  top: 100%;
  left: 50%;
  transform: translateX(-50%);
  border-width: 5px;
  border-style: solid;
  border-color: var(--text-primary) transparent transparent transparent;
}

.synkra-faq-btn:hover .synkra-faq-tooltip {
  opacity: 1;
  visibility: visible;
  bottom: calc(100% + 8px);
}
</style>
