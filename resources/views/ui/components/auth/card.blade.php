<div class="flowexa-auth-container">
    <div class="flowexa-auth-card-wrapper">
        <div class="flowexa-auth-card-header">
            @if(isset($header))
                {{ $header }}
            @else
                <div class="flowexa-auth-logo">
                    <i class="fa-solid fa-cube text-primary"></i>
                    <span class="flowexa-auth-brand">flowexa</span>
                </div>
            @endif
        </div>

        <div class="flowexa-auth-card-body">
            {{ $slot }}
        </div>
    </div>
</div>

<style>
.flowexa-auth-container {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    padding: 1rem;
    width: 100%;
}

.flowexa-auth-card-wrapper {
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

.flowexa-auth-card-header {
    padding: 2.5rem 2rem 0.5rem;
    text-align: center;
}

.flowexa-auth-card-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0 0 0.5rem 0;
}

.flowexa-auth-card-subtitle {
    font-size: 0.95rem;
    color: var(--text-secondary);
    margin: 0;
}

.flowexa-auth-logo {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    font-size: 1.75rem;
    font-weight: 800;
    color: var(--text-primary);
}

.flowexa-auth-logo i {
    color: var(--primary);
    font-size: 2rem;
}

.flowexa-auth-card-body {
    padding: 2rem;
}

.flowexa-form-group {
    margin-bottom: 1.25rem;
}

.flowexa-form-error {
    color: var(--danger);
    font-size: 0.85rem;
    margin-top: 0.35rem;
    display: block;
    font-weight: 500;
}
</style>
