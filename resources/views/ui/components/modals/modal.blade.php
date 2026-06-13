@php
  $id = $id ?? 'modal_' . uniqid();
  $triggerId = $triggerId ?? 'trigger_' . uniqid();
  $title = $title ?? 'Modal Title';
  $class = $class ?? '';
@endphp

<div class="synkra-modal-wrapper {{ $class }}">
  <!-- Trigger Button -->
  <button type="button" class="synkra-btn synkra-btn-primary" id="{{ $triggerId }}" onclick="openSynkraModal('{{ $id }}')">
    Open Modal
  </button>

  <!-- Modal Container -->
  <div id="{{ $id }}" class="synkra-modal" role="dialog" aria-modal="true" aria-labelledby="{{ $id }}_title">
    <div class="synkra-modal-backdrop" onclick="closeSynkraModal('{{ $id }}')"></div>
    <div class="synkra-modal-container">
      <div class="synkra-modal-header">
        <h3 id="{{ $id }}_title" class="synkra-modal-title">{{ $title }}</h3>
        <button type="button" class="synkra-modal-close" onclick="closeSynkraModal('{{ $id }}')" aria-label="Close modal">
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>
      <div class="synkra-modal-body">
        {!! $slot ?? ($content ?? '<p>Modal body content goes here...</p>') !!}
      </div>
      <div class="synkra-modal-footer">
        @if(isset($footer))
          {{ $footer }}
        @else
          <button type="button" class="synkra-btn synkra-btn-secondary" onclick="closeSynkraModal('{{ $id }}')">Cancel</button>
          <button type="button" class="synkra-btn synkra-btn-primary" onclick="closeSynkraModal('{{ $id }}')">Confirm</button>
        @endif
      </div>
    </div>
  </div>
</div>

<script>
if (typeof openSynkraModal !== 'function') {
  function openSynkraModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.add('synkra-modal-open');
    document.body.style.overflow = 'hidden';
  }
}
if (typeof closeSynkraModal !== 'function') {
  function closeSynkraModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.remove('synkra-modal-open');
    document.body.style.overflow = '';
  }
}
</script>

<style>
.synkra-modal-wrapper {
  display: inline-block;
}

.synkra-modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  z-index: 1000;
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  visibility: hidden;
  transition: all 0.3s ease;
}

.synkra-modal-backdrop {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.4);
  backdrop-filter: blur(5px);
  -webkit-backdrop-filter: blur(5px);
}

.synkra-modal-container {
  position: relative;
  background-color: var(--surface);
  border: 1px solid rgba(255, 255, 255, 0.05);
  border-radius: 24px;
  width: 90%;
  max-width: 480px;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25), 0 0 0 1px rgba(0,0,0,0.03);
  display: flex;
  flex-direction: column;
  transform: scale(0.95) translateY(-20px);
  transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
  overflow: hidden;
  color: var(--body-text);
  z-index: 1001;
}

.synkra-modal-open {
  opacity: 1;
  visibility: visible;
}

.synkra-modal-open .synkra-modal-container {
  transform: scale(1) translateY(0);
}

.synkra-modal-header {
  padding: 1.5rem 1.5rem 0.5rem 1.5rem;
  border-bottom: none;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.synkra-modal-title {
  font-size: 1.15rem;
  font-weight: 700;
  color: var(--headings);
  margin: 0;
}

.synkra-modal-close {
  background: transparent;
  border: none;
  font-size: 1.1rem;
  color: var(--text-secondary);
  cursor: pointer;
  width: 32px;
  height: 32px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s;
}

.synkra-modal-close:hover {
  background-color: var(--surface-secondary);
  color: var(--text-primary);
}

.synkra-modal-body {
  padding: 1.5rem;
  font-size: 0.9rem;
  line-height: 1.5;
  overflow-y: auto;
  max-height: 60vh;
}

.synkra-modal-footer {
  padding: 0.5rem 1.5rem 1.5rem 1.5rem;
  border-top: none;
  background-color: transparent;
  display: flex;
  justify-content: flex-end;
  gap: 0.75rem;
}
</style>
