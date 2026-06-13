@php
  $title = $title ?? null;
  $subtitle = $subtitle ?? null;
  $class = $class ?? '';
@endphp

<div class="synkra-card {{ $class }}">
  @if($title || $subtitle)
    <div class="synkra-card-header">
      @if($title)
        <h3 class="synkra-card-title">{{ $title }}</h3>
      @endif
      @if($subtitle)
        <p class="synkra-card-subtitle">{{ $subtitle }}</p>
      @endif
    </div>
  @endif
  <div class="synkra-card-body">
    {!! $slot ?? ($content ?? 'Card content goes here...') !!}
  </div>
</div>

<style>
.synkra-card {
  background-color: var(--surface);
  border: 1px solid var(--border);
  border-radius: 1rem;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
  padding: 1.5rem;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  color: var(--body-text);
}

.synkra-card-header {
  margin-bottom: 1rem;
  border-bottom: 1px solid var(--divider);
  padding-bottom: 0.75rem;
}

.synkra-card-title {
  font-size: 1.15rem;
  font-weight: 600;
  color: var(--headings);
  margin: 0;
}

.synkra-card-subtitle {
  font-size: 0.825rem;
  color: var(--text-secondary);
  margin: 0.25rem 0 0 0;
}

.synkra-card-body {
  font-size: 0.9rem;
  line-height: 1.5;
}
</style>
