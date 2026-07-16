(() => {
    'use strict';

    document.documentElement.setAttribute('data-bs-theme', 'light');

    try {
        localStorage.removeItem('theme');
        localStorage.removeItem('color-mode');
    } catch (error) {
        // The website still works when storage is unavailable.
    }
})();
