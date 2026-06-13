@php
  $title = $title ?? 'Premium Feature';
  $badgeText = $badgeText ?? 'Premium';
  $description = $description ?? 'Access exclusive tools and advanced features.';
  $icon = $icon ?? 'fa-solid fa-crown';
  $class = $class ?? '';
@endphp

<div class="synkra-premium-container {{ $class }}">
  <div class="synkra-premium-card">
    <div class="synkra-premium-ribbon">
      <span>{{ $badgeText }}</span>
    </div>
    <div class="synkra-premium-content">
      <div class="synkra-premium-icon-wrapper">
        <i class="{{ $icon }}"></i>
      </div>
      <h3 class="synkra-premium-title">{{ $title }}</h3>
      <p class="synkra-premium-desc">{{ $description }}</p>
      {!! $slot ?? '' !!}
    </div>
  </div>
</div>

<style>
.synkra-premium-container {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 100%;
}

.synkra-premium-card {
  width: 100%;
  max-width: 280px;
  min-height: 300px;
  border-radius: 20px;
  background: linear-gradient(135deg, var(--surface) 0%, var(--surface-secondary) 100%);
  border: 1px solid var(--border);
  position: relative;
  box-shadow: 0 15px 30px rgba(0, 0, 0, 0.08);
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  overflow: hidden;
  padding: 2.5rem 1.5rem 1.5rem 1.5rem;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.synkra-premium-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 20px 40px rgba(249, 115, 22, 0.12);
  border-color: var(--primary);
}

.synkra-premium-ribbon {
  position: absolute;
  top: 0;
  left: 0;
  width: 120px;
  height: 120px;
  overflow: hidden;
  pointer-events: none;
}

.synkra-premium-ribbon span {
  position: absolute;
  display: block;
  width: 160px;
  padding: 8px 0;
  background: linear-gradient(45deg, var(--primary) 0%, var(--primary-hover) 100%);
  box-shadow: 0 5px 10px rgba(0,0,0,0.1);
  color: #fff;
  font-weight: 700;
  font-size: 11px;
  text-transform: uppercase;
  text-align: center;
  right: -25px;
  top: 30px;
  transform: rotate(-45deg);
}

.synkra-premium-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  height: 100%;
  color: var(--body-text);
}

.synkra-premium-icon-wrapper {
  width: 54px;
  height: 54px;
  border-radius: 50%;
  background-color: rgba(249, 115, 22, 0.1);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.35rem;
  color: var(--primary);
  margin-bottom: 1.5rem;
  transition: all 0.3s ease;
}

.synkra-premium-card:hover .synkra-premium-icon-wrapper {
  background-color: var(--primary);
  color: #ffffff;
  transform: scale(1.08);
}

.synkra-premium-title {
  font-size: 1.15rem;
  font-weight: 700;
  color: var(--headings);
  margin-bottom: 0.75rem;
  margin-top: 0;
}

.synkra-premium-desc {
  font-size: 0.825rem;
  color: var(--text-secondary);
  line-height: 1.5;
  margin-bottom: 0;
}
</style>