@php
  $class = $class ?? '';
@endphp

<div class="flowexa-grid-wrapper {{ $class }}">
  <div class="flowexa-grid-background"></div>
  <div class="flowexa-grid-content">
    {!! $slot ?? ($content ?? '') !!}
  </div>
</div>

<style>
.flowexa-grid-wrapper {
  position: relative;
  width: 100%;
  min-height: 100%;
  background-color: var(--background);
  overflow: hidden;
}

.flowexa-grid-background {
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  z-index: 0;
  background-image: linear-gradient(to right, var(--border) 1px, transparent 1px),
    linear-gradient(to bottom, var(--border) 1px, transparent 1px);
  background-size: 24px 24px;
  -webkit-mask-image: radial-gradient(
    ellipse 80% 60% at 50% 0%,
    #000 40%,
    transparent 100%
  );
  mask-image: radial-gradient(
    ellipse 80% 60% at 50% 0%,
    #000 40%,
    transparent 100%
  );
  opacity: 0.6;
  pointer-events: none;
}

.flowexa-grid-content {
  position: relative;
  z-index: 1;
  width: 100%;
  height: 100%;
}
</style>
