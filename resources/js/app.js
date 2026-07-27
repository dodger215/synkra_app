

document.addEventListener('DOMContentLoaded', () => {
    // Use the same key as the settings page, fallback to server-side session preference
    const savedTheme = localStorage.getItem('appearance') ||
                       document.documentElement.getAttribute('data-theme-preference') ||
                       'system';

    applyTheme(savedTheme);

    // Listen for system theme changes in real-time
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
        const currentTheme = localStorage.getItem('appearance') ||
                             document.documentElement.getAttribute('data-theme-preference') ||
                             'system';
        if (currentTheme === 'system') {
            applyTheme('system');
        }
    });
});

window.applyTheme = function(theme) {
    const htmlEl = document.documentElement;
    let isDark = false;

    if (theme === 'system') {
        isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    } else {
        isDark = theme === 'dark';
    }

    htmlEl.setAttribute('data-theme', isDark ? 'dark' : 'light');
    htmlEl.setAttribute('data-theme-preference', theme);

    // Update the UI Showcase toggle button text if it exists
    const themeText = document.getElementById('themeText');
    const themeIcon = document.querySelector('#themeToggler i');
    if (themeText && themeIcon) {
        themeText.textContent = isDark ? 'Light Mode' : 'Dark Mode';
        themeIcon.className = isDark ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
    }
}

window.toggleTheme = function() {
    const htmlEl = document.documentElement;
    const currentTheme = htmlEl.getAttribute('data-theme');

    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    localStorage.setItem('appearance', newTheme); // Use the same key
    applyTheme(newTheme);

    // Also sync with the server
    fetch('/settings/theme', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
        },
        body: JSON.stringify({ theme: newTheme }),
    }).catch(() => {});
}
