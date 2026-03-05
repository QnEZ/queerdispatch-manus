/**
 * QueerDispatch Style Switcher — v2 (Fixed)
 *
 * ROOT CAUSE OF BUG:
 *   The old code swapped a single <link> href to load different CSS files.
 *   Each theme CSS file used :root { } for variables, which has the same
 *   specificity as the base :root in style.css — so whichever loaded last
 *   won. The result was always Anarchist (the default).
 *
 * FIX:
 *   1. All 7 theme CSS files are now loaded simultaneously in <head> by PHP.
 *   2. Each theme file scopes ALL its rules to html[data-style="X"] { ... }
 *      which has higher specificity than :root, so it always wins.
 *   3. Switching a style only requires updating data-style on <html>.
 *   4. No stylesheet swapping, no load delays, no FOUC.
 *
 * @package QueerDispatch
 */

(function () {
    'use strict';

    var COOKIE_NAME  = 'queerdispatch_style';
    var COOKIE_DAYS  = 365;
    var DEFAULT_STYLE = 'anarchist';

    var THEME_URL = (typeof queerdispatchData !== 'undefined') ? queerdispatchData.themeUrl : '';
    var AJAX_URL  = (typeof queerdispatchData !== 'undefined') ? queerdispatchData.ajaxUrl  : '';
    var NONCE     = (typeof queerdispatchData !== 'undefined') ? queerdispatchData.nonce    : '';

    var styles = {
        'anarchist':           { name: 'Anarchist',           desc: 'Black & red punk zine',       emoji: '✊' },
        'goth':                { name: 'Goth',                desc: 'Dark academia, ornate',        emoji: '🦇' },
        'witchy':              { name: 'Witchy',              desc: 'Mystical tarot oracle',        emoji: '🔮' },
        'pastel-rainbow-goth': { name: 'Pastel Rainbow Goth', desc: 'Kawaii-goth cotton candy',     emoji: '🌈' },
        'cyberpunk':           { name: 'Cyberpunk Queer',     desc: 'Neon glitch, digital resist',  emoji: '⚡' },
        'cottagecore':         { name: 'Cottagecore Queer',   desc: 'Cozy nature, floral warmth',   emoji: '🌿' },
        'riot-grrrl':          { name: 'Riot Grrrl',          desc: 'Hot pink feminist punk',       emoji: '🎸' }
    };

    /* ─── Cookie helpers ─────────────────────────────────────────────────── */
    function setCookie(name, value, days) {
        var expires = '';
        if (days) {
            var d = new Date();
            d.setTime(d.getTime() + days * 24 * 60 * 60 * 1000);
            expires = '; expires=' + d.toUTCString();
        }
        document.cookie = name + '=' + encodeURIComponent(value) + expires + '; path=/; SameSite=Lax';
    }

    function getCookie(name) {
        var nameEQ = name + '=';
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i].trim();
            if (c.indexOf(nameEQ) === 0) {
                return decodeURIComponent(c.substring(nameEQ.length));
            }
        }
        return null;
    }

    /* ─── Core: apply a style ────────────────────────────────────────────── */
    function applyStyle(styleName) {
        if (!styles[styleName]) {
            console.warn('QueerDispatch: Unknown style "' + styleName + '"');
            styleName = DEFAULT_STYLE;
        }

        // THE KEY FIX: just update data-style on <html>.
        // All theme CSS files are already loaded; each one is scoped to
        // html[data-style="X"] so only the matching file's rules apply.
        document.documentElement.setAttribute('data-style', styleName);

        // Also set on body for any legacy selectors
        if (document.body) {
            document.body.setAttribute('data-style', styleName);
            // Keep body class in sync
            var cls = document.body.className.split(' ').filter(function (c) {
                return c.indexOf('style-') !== 0;
            });
            cls.push('style-' + styleName);
            document.body.className = cls.join(' ').trim();
        }

        // Persist
        setCookie(COOKIE_NAME, styleName, COOKIE_DAYS);
        try { localStorage.setItem('qd_style', styleName); } catch (e) {}

        // Notify server (for logged-in users / server-side cookie)
        if (AJAX_URL && NONCE) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', AJAX_URL, true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send(
                'action=queerdispatch_save_style' +
                '&style=' + encodeURIComponent(styleName) +
                '&nonce=' + encodeURIComponent(NONCE)
            );
        }

        // Update switcher UI
        updateSwitcherUI(styleName);

        // Screen reader announcement
        var style = styles[styleName];
        var announcer = document.getElementById('qd-style-announcer');
        if (!announcer) {
            announcer = document.createElement('div');
            announcer.id = 'qd-style-announcer';
            announcer.setAttribute('aria-live', 'polite');
            announcer.setAttribute('aria-atomic', 'true');
            announcer.style.cssText = 'position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;';
            document.body.appendChild(announcer);
        }
        announcer.textContent = 'Theme changed to ' + style.name + ': ' + style.desc;
    }

    function updateSwitcherUI(activeStyle) {
        var options = document.querySelectorAll('.style-option');
        for (var i = 0; i < options.length; i++) {
            var opt = options[i];
            var id  = opt.getAttribute('data-style');
            var isActive = (id === activeStyle);
            opt.classList.toggle('active', isActive);
            opt.setAttribute('aria-pressed', String(isActive));
            opt.setAttribute('aria-selected', String(isActive));
        }
    }

    /* ─── Panel open/close ───────────────────────────────────────────────── */
    function openPanel() {
        var panel  = document.getElementById('style-switcher-panel');
        var toggle = document.getElementById('style-switcher-toggle');
        if (!panel || !toggle) return;
        panel.classList.add('is-open');
        toggle.setAttribute('aria-expanded', 'true');
        var active = panel.querySelector('.style-option.active') || panel.querySelector('.style-option');
        if (active) active.focus();
    }

    function closePanel() {
        var panel  = document.getElementById('style-switcher-panel');
        var toggle = document.getElementById('style-switcher-toggle');
        if (!panel || !toggle) return;
        panel.classList.remove('is-open');
        toggle.setAttribute('aria-expanded', 'false');
    }

    function togglePanel() {
        var panel = document.getElementById('style-switcher-panel');
        if (panel && panel.classList.contains('is-open')) {
            closePanel();
        } else {
            openPanel();
        }
    }

    /* ─── Keyboard navigation ────────────────────────────────────────────── */
    function handleKeyDown(e) {
        var panel = document.getElementById('style-switcher-panel');
        if (!panel || !panel.classList.contains('is-open')) return;

        var options = Array.prototype.slice.call(panel.querySelectorAll('.style-option'));
        var idx = options.indexOf(document.activeElement);

        switch (e.key) {
            case 'Escape':
                closePanel();
                var t = document.getElementById('style-switcher-toggle');
                if (t) t.focus();
                break;
            case 'ArrowDown':
                e.preventDefault();
                options[(idx + 1) % options.length].focus();
                break;
            case 'ArrowUp':
                e.preventDefault();
                options[(idx - 1 + options.length) % options.length].focus();
                break;
            case 'Home':
                e.preventDefault();
                options[0].focus();
                break;
            case 'End':
                e.preventDefault();
                options[options.length - 1].focus();
                break;
        }
    }

    /* ─── Init ───────────────────────────────────────────────────────────── */
    function init() {
        // Toggle button
        var toggle = document.getElementById('style-switcher-toggle');
        if (toggle) {
            toggle.addEventListener('click', function (e) {
                e.stopPropagation();
                togglePanel();
            });
        }

        // Style option buttons — use data-style attribute (not data-style-id)
        var options = document.querySelectorAll('.style-option');
        for (var i = 0; i < options.length; i++) {
            options[i].addEventListener('click', (function (opt) {
                return function () {
                    var styleName = opt.getAttribute('data-style');
                    if (styleName) {
                        applyStyle(styleName);
                        closePanel();
                        var t = document.getElementById('style-switcher-toggle');
                        if (t) t.focus();
                    }
                };
            })(options[i]));
        }

        // Close on outside click
        document.addEventListener('click', function (e) {
            var switcher = document.getElementById('style-switcher');
            if (switcher && !switcher.contains(e.target)) {
                closePanel();
            }
        });

        // Keyboard navigation
        document.addEventListener('keydown', handleKeyDown);

        // Apply the saved style (cookie takes precedence over PHP-rendered default)
        var saved = getCookie(COOKIE_NAME);
        if (!saved) {
            try { saved = localStorage.getItem('qd_style'); } catch (e) {}
        }
        if (!saved) {
            saved = document.documentElement.getAttribute('data-style') || DEFAULT_STYLE;
        }
        applyStyle(saved);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Public API
    window.QueerDispatchStyleSwitcher = {
        applyStyle:       applyStyle,
        getCurrentStyle:  function () { return document.documentElement.getAttribute('data-style') || DEFAULT_STYLE; },
        styles:           styles
    };

})();
