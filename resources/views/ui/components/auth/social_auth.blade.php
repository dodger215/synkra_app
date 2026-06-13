<div class="synkra-social-auth">
    <div class="synkra-divider">
        <span class="synkra-divider-line"></span>
        <span class="synkra-divider-text">or continue with</span>
        <span class="synkra-divider-line"></span>
    </div>

    <div class="synkra-social-buttons">
        <a href="{{ Route::has('auth.google') ? route('auth.google') : '#' }}" class="synkra-social-btn">
            <svg width="18" height="18" viewBox="0 0 48 48" aria-hidden="true" class="synkra-social-icon"><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"></path><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"></path><path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"></path><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"></path><path fill="none" d="M0 0h48v48H0z"></path></svg>
            <span>Google</span>
        </a>
        <a href="#" class="synkra-social-btn">
            <svg width="18" height="18" viewBox="0 0 21 21" aria-hidden="true" class="synkra-social-icon"><path fill="#f25022" d="M1 1h9v9H1z"/><path fill="#00a4ef" d="M1 11h9v9H1z"/><path fill="#7fba00" d="M11 1h9v9h-9z"/><path fill="#ffb900" d="M11 11h9v9h-9z"/></svg>
            <span>Microsoft</span>
        </a>
    </div>
</div>

<style>
.synkra-divider {
    display: flex;
    align-items: center;
    text-align: center;
    margin: 1.5rem 0;
}

.synkra-divider-line {
    flex: 1;
    height: 1px;
    background-color: var(--border);
}

.synkra-divider-text {
    padding: 0 1rem;
    font-size: 0.85rem;
    color: var(--text-secondary);
    font-weight: 500;
}

.synkra-social-buttons {
    display: flex;
    gap: 0.75rem;
    justify-content: center;
    flex-wrap: nowrap;
}

.synkra-social-btn {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.625rem;
    border: 1px solid var(--border);
    border-radius: 8px;
    background-color: var(--surface);
    color: var(--text-primary);
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 600;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.synkra-social-btn:hover {
    background-color: var(--surface-secondary);
    border-color: var(--primary);
    transform: translateY(-2px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
}

.synkra-social-icon {
    width: 1.1rem;
    height: 1.1rem;
    flex-shrink: 0;
}
</style>
