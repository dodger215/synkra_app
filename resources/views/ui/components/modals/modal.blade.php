@php
  $id = $id ?? 'modal_' . uniqid();
  $triggerId = $triggerId ?? 'trigger_' . uniqid();
  $title = $title ?? 'Modal Title';
  $class = $class ?? '';
@endphp

<div class="flowexa-modal-wrapper {{ $class }}">
  <!-- Trigger Button -->
  <button type="button" class="flowexa-btn flowexa-btn-primary" id="{{ $triggerId }}" onclick="openflowexaModal('{{ $id }}')">
    Open Modal
  </button>

  <!-- Modal Container -->
  <div id="{{ $id }}" class="flowexa-modal" role="dialog" aria-modal="true" aria-labelledby="{{ $id }}_title">
    <div class="flowexa-modal-backdrop" onclick="closeflowexaModal('{{ $id }}')"></div>
    <div class="flowexa-modal-container">
      <div class="flowexa-modal-header">
        <h3 id="{{ $id }}_title" class="flowexa-modal-title">{{ $title }}</h3>
        <button type="button" class="flowexa-modal-close" onclick="closeflowexaModal('{{ $id }}')" aria-label="Close modal">
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>
      <div class="flowexa-modal-body">
        {!! $slot ?? ($content ?? '<p>Modal body content goes here...</p>') !!}
      </div>
      <div class="flowexa-modal-footer">
        @if(isset($footer))
          {{ $footer }}
        @else
          <button type="button" class="flowexa-btn flowexa-btn-secondary" onclick="closeflowexaModal('{{ $id }}')">Cancel</button>
          <button type="button" class="flowexa-btn flowexa-btn-primary" onclick="closeflowexaModal('{{ $id }}')">Confirm</button>
        @endif
      </div>
    </div>
  </div>
</div>

<script>
if (typeof openflowexaModal !== 'function') {
  function openflowexaModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.add('flowexa-modal-open');
    document.body.style.overflow = 'hidden';
  }
}
if (typeof closeflowexaModal !== 'function') {
  function closeflowexaModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.remove('flowexa-modal-open');
    document.body.style.overflow = '';
  }
}
</script>

<style>
.flowexa-modal-wrapper {
  display: inline-block;
  z-index: 60;
}

.flowexa-modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;

  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  visibility: hidden;
  transition: all 0.3s ease;
  z-index: 60;
}

.flowexa-modal-backdrop {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.4);
  backdrop-filter: blur(5px);
  -webkit-backdrop-filter: blur(5px);
  z-index: 60;
}

.flowexa-modal-container {
  position: relative;
  background-color: var(--surface);
  border: 1px solid rgba(255, 255, 255, 0.05);
  border-radius: 24px;
  width: 90%;
  max-width: 480px;
  top: 5%;
  scale: 0.95;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25), 0 0 0 1px rgba(0,0,0,0.03);
  display: flex;
  flex-direction: column;
  transform: scale(0.95) translateY(-20px);
  transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
  overflow: hidden;
  color: var(--body-text);
  z-index: 1001;
}

.flowexa-modal-open {
  opacity: 1;
  visibility: visible;
}

.flowexa-modal-open .flowexa-modal-container {
  transform: scale(1) translateY(0);
}

.flowexa-modal-header {
  padding: 1.5rem 1.5rem 0.5rem 1.5rem;
  border-bottom: none;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.flowexa-modal-title {
  font-size: 1.15rem;
  font-weight: 700;
  color: var(--headings);
  margin: 0;
}

.flowexa-modal-close {
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

.flowexa-modal-close:hover {
  background-color: var(--surface-secondary);
  color: var(--text-primary);
}

.flowexa-modal-body {
  padding: 1.5rem;
  font-size: 0.9rem;
  line-height: 1.5;
  overflow-y: auto;
  max-height: 60vh;
}

.flowexa-modal-footer {
  padding: 0.5rem 1.5rem 1.5rem 1.5rem;
  border-top: none;
  background-color: transparent;
  display: flex;
  justify-content: flex-end;
  gap: 0.75rem;
}
</style>
