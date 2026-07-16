(function () {
    const STORAGE_KEY = 'bh_cookie_preferences_v1';

    function getBanner() {
        return document.getElementById('bh-cookie-banner');
    }

    function getSettings() {
        return document.getElementById('bh-cookie-settings');
    }

    function getAnalyticsCheckbox() {
        return document.getElementById('bh-cookie-analytics');
    }

    function savePreferences(data) {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
    }

    function getPreferences() {
        try {
            const raw = localStorage.getItem(STORAGE_KEY);
            return raw ? JSON.parse(raw) : null;
        } catch (e) {
            return null;
        }
    }

    function hideBanner() {
        const banner = getBanner();
        if (banner) {
            banner.hidden = true;
        }
    }

    function showBanner() {
        const banner = getBanner();
        if (banner) {
            banner.hidden = false;
        }
    }

    function openSettings() {
        const settings = getSettings();
        if (settings) {
            settings.hidden = false;
        }
    }

    function closeSettings() {
        const settings = getSettings();
        if (settings) {
            settings.hidden = true;
        }
    }

    function init() {
        const banner = getBanner();
        if (!banner) {
            return;
        }

        const existing = getPreferences();
        if (!existing) {
            showBanner();
        } else {
            hideBanner();
        }

        const analyticsCheckbox = getAnalyticsCheckbox();
        if (existing && analyticsCheckbox) {
            analyticsCheckbox.checked = !!existing.analytics;
        }

        banner.addEventListener('click', function (event) {
            const target = event.target.closest('[data-cookie-action]');
            if (!target) {
                return;
            }

            const action = target.getAttribute('data-cookie-action');

            if (action === 'necessary') {
                savePreferences({
                    necessary: true,
                    analytics: false
                });
                hideBanner();
                return;
            }

            if (action === 'accept') {
                savePreferences({
                    necessary: true,
                    analytics: true
                });
                hideBanner();
                return;
            }

            if (action === 'settings') {
                openSettings();
                return;
            }

            if (action === 'close-settings') {
                closeSettings();
                return;
            }

            if (action === 'save-necessary') {
                savePreferences({
                    necessary: true,
                    analytics: false
                });
                hideBanner();
                closeSettings();
                return;
            }

            if (action === 'save-preferences') {
                savePreferences({
                    necessary: true,
                    analytics: analyticsCheckbox ? analyticsCheckbox.checked : false
                });
                hideBanner();
                closeSettings();
            }
        });
    }

    document.addEventListener('DOMContentLoaded', init);
})();
