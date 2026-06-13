<div class="synkra-auth-container">
    <div class="synkra-auth-card-wrapper">
        <div class="synkra-auth-card-header">
            @if(isset($header))
                {{ $header }}
            @else
                <div class="synkra-auth-logo">
                    <i class="fa-solid fa-cube text-primary"></i>
                    <span class="synkra-auth-brand">Synkra</span>
                </div>
            @endif
        </div>

        <div class="synkra-auth-card-body">
            {{ $slot }}
        </div>
    </div>
</div>

<style>
.synkra-auth-container {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    padding: 1rem;
    width: 100%;
}

.synkra-auth-card-wrapper {
    background-color: var(--surface);
    border: transparent;
    border-radius: 26px;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.10), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
    width: 100%;
    max-width: 440px;
    overflow: hidden;
    position: relative;
    z-index: 10;
}

.synkra-auth-card-header {
    padding: 2.5rem 2rem 0.5rem;
    text-align: center;
}

.synkra-auth-card-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0 0 0.5rem 0;
}

.synkra-auth-card-subtitle {
    font-size: 0.95rem;
    color: var(--text-secondary);
    margin: 0;
}

.synkra-auth-logo {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    font-size: 1.75rem;
    font-weight: 800;
    color: var(--text-primary);
}

.synkra-auth-logo i {
    color: var(--primary);
    font-size: 2rem;
}

.synkra-auth-card-body {
    padding: 2rem;
}

.synkra-form-group {
    margin-bottom: 1.25rem;
}

.synkra-form-error {
    color: var(--danger);
    font-size: 0.85rem;
    margin-top: 0.35rem;
    display: block;
    font-weight: 500;
}
</style>
