// Theme logic for Synkra

document.addEventListener('DOMContentLoaded', () => {
    // Check local storage for a saved theme, or fallback to 'system'
    const savedTheme = localStorage.getItem('synkra_theme') || 'system';
    
    applyTheme(savedTheme);

    // Listen for system theme changes in real-time
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
        if (localStorage.getItem('synkra_theme') === 'system' || !localStorage.getItem('synkra_theme')) {
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
    localStorage.setItem('synkra_theme', newTheme);
    applyTheme(newTheme);
}
