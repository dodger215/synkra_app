@php
  $title = $title ?? '404';
  $message = $message ?? 'Oops! The page you are looking for could not be found or has been moved.';
  $buttonText = $buttonText ?? 'Return Home';
  $buttonUrl = $buttonUrl ?? url('/');
  $class = $class ?? '';
@endphp

<div class="flowexa-404-wrapper {{ $class }}">
  <div class="flowexa-404-icon">
    <i class="fa-solid fa-ghost"></i>
    <div class="flowexa-404-shadow"></div>
  </div>
  <h1 class="flowexa-404-title">{{ $title }}</h1>
  <p class="flowexa-404-message">{{ $message }}</p>
  <a href="{{ $buttonUrl }}" class="flowexa-404-btn">
    <i class="fa-solid fa-arrow-left"></i>
    {{ $buttonText }}
  </a>
</div>

<style>
.flowexa-404-wrapper {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 4rem 2rem;
  text-align: center;
  background: var(--surface-secondary);
  border-radius: 16px;
  border: 1px solid var(--border);
  box-shadow: 0 10px 30px -10px rgba(0,0,0,0.05);
  max-width: 500px;
  margin: 0 auto;
}

.flowexa-404-icon {
  position: relative;
  font-size: 5rem;
  color: var(--primary);
  margin-bottom: 2.5rem;
  animation: flowexa-float-ghost 3s ease-in-out infinite;
}

.flowexa-404-shadow {
  position: absolute;
  bottom: -20px;
  left: 50%;
  transform: translateX(-50%);
  width: 60px;
  height: 10px;
  background: rgba(0,0,0,0.1);
  border-radius: 50%;
  filter: blur(4px);
  animation: flowexa-shadow-pulse 3s ease-in-out infinite;
}

.flowexa-404-title {
  font-size: 4.5rem;
  font-weight: 800;
  color: var(--headings);
  margin: 0 0 0.5rem 0;
  line-height: 1;
  text-shadow: 4px 4px 0px rgba(0,0,0,0.05);
}

.flowexa-404-message {
  color: var(--text-secondary);
  font-size: 1.1rem;
  margin: 0 0 2rem 0;
  max-width: 85%;
  line-height: 1.6;
}

.flowexa-404-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: var(--surface);
  border: 1px solid var(--border);
  color: var(--text-primary);
  padding: 12px 24px;
  border-radius: 99px;
  text-decoration: none;
  font-weight: 600;
  font-size: 0.95rem;
  transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.flowexa-404-btn:hover {
  background: var(--primary);
  border-color: var(--primary);
  color: white;
  transform: translateY(-3px);
  box-shadow: 0 8px 20px rgba(249, 115, 22, 0.25);
}

@keyframes flowexa-float-ghost {
  0% { transform: translateY(0px); }
  50% { transform: translateY(-20px); }
  100% { transform: translateY(0px); }
}

@keyframes flowexa-shadow-pulse {
  0% { transform: translateX(-50%) scale(1); opacity: 0.5; }
  50% { transform: translateX(-50%) scale(0.6); opacity: 0.2; }
  100% { transform: translateX(-50%) scale(1); opacity: 0.5; }
}
</style>
