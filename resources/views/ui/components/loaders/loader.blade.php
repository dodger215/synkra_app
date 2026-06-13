<div id="synkra-page-loader" class="synkra-page-loader-overlay">
  <span class="loader"></span>
</div>

<style>
.synkra-page-loader-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background-color: var(--background);
  z-index: 99999;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: opacity 0.5s ease, visibility 0.5s ease;
}

.synkra-page-loader-overlay.hidden {
  opacity: 0;
  visibility: hidden;
}

.loader {
  display: block;
  width: 84px;
  height: 84px;
  position: relative;
}

.loader:before , .loader:after {
  content: "";
  position: absolute;
  left: 50%;
  bottom: 0;
  width: 64px;
  height: 64px;
  border-radius: 50%;
  background: var(--primary);
  transform: translate(-50% , -100%)  scale(0);
  animation: push_401 1s infinite linear;
}

.loader:after {
  animation-delay: 0.5s;
}

@keyframes push_401 {
  0% , 50% {
    transform: translate(-50% , 0%)  scale(1)
  }

  100% {
    transform: translate(-50%, -100%) scale(0)
  }
}    
</style> 

<script>
  window.addEventListener('load', function() {
      const loader = document.getElementById('synkra-page-loader');
      if (loader) {
          loader.classList.add('hidden');
          setTimeout(() => {
              loader.style.display = 'none';
          }, 500);
      }
  });
</script>