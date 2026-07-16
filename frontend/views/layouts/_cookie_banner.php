<?php

declare(strict_types=1);
?>
<div id="bh-cookie-banner" class="bh-cookie-banner" hidden>
    <div class="bh-cookie-card">
        <div class="bh-cookie-main">
            <div class="bh-cookie-text">
                <span class="bh-cookie-eyebrow">Privacy preferences</span>
                <h3>Your privacy matters</h3>
                <p>
                    We use necessary cookies for login, sessions and security.
                    Optional cookies are only used with your permission.
                </p>
            </div>

            <div class="bh-cookie-actions">
                <button type="button" class="bh-cookie-btn bh-cookie-btn-light" data-cookie-action="necessary">
                    Necessary only
                </button>
                <button type="button" class="bh-cookie-btn bh-cookie-btn-outline" data-cookie-action="settings">
                    Settings
                </button>
                <button type="button" class="bh-cookie-btn bh-cookie-btn-primary" data-cookie-action="accept">
                    Accept all
                </button>
            </div>
        </div>

        <div id="bh-cookie-settings" class="bh-cookie-settings" hidden>
            <div class="bh-cookie-settings-head">
                <h4>Cookie settings</h4>
                <button type="button" class="bh-cookie-close" data-cookie-action="close-settings" aria-label="Close settings">
                    &times;
                </button>
            </div>

            <div class="bh-cookie-option">
                <div class="bh-cookie-option-copy">
                    <strong>Necessary cookies</strong>
                    <p>Required for login, sessions, security, and basic site functionality.</p>
                </div>
                <label class="bh-cookie-switch">
                    <input type="checkbox" checked disabled>
                    <span class="bh-cookie-slider"></span>
                </label>
            </div>

            <div class="bh-cookie-option">
                <div class="bh-cookie-option-copy">
                    <strong>Optional analytics cookies</strong>
                    <p>Help us understand usage patterns and improve the platform experience.</p>
                </div>
                <label class="bh-cookie-switch">
                    <input type="checkbox" id="bh-cookie-analytics">
                    <span class="bh-cookie-slider"></span>
                </label>
            </div>

            <div class="bh-cookie-settings-actions">
                <button type="button" class="bh-cookie-btn bh-cookie-btn-light" data-cookie-action="save-necessary">
                    Necessary only
                </button>
                <button type="button" class="bh-cookie-btn bh-cookie-btn-primary" data-cookie-action="save-preferences">
                    Save preferences
                </button>
            </div>
        </div>
    </div>
</div>
