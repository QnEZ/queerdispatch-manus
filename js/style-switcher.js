/**
 * QueerDispatch Style Switcher
 * Handles switching between theme aesthetic styles with cookie persistence
 *
 * @package QueerDispatch
 */

(function () {
    'use strict';

    // ============================================================
    // CONFIG
    // ============================================================

    var COOKIE_NAME = 'queerdispatch_style';
    var COOKIE_DAYS = 365;
    var STYLESHEET_ID = 'queerdispatch-theme-style-css';
    var THEME_URL = (typeof queerdispatchData !== 'undefined') ? queerdispatchData.themeUrl : '';
    var AJAX_URL = (typeof queerdispatchData !== 'undefined') ? queerdispatchData.ajaxUrl : '';
    var NONCE = (typeof queerdispatchData !== 'undefined') ? queerdispatchData.nonce : '';
    var SAVED_STYLE = (typeof queerdispatchData !== 'undefined') ? queerdispatchData.savedStyle : 'anarchist';

    var styles = {
        'anarchist': {
            name: 'Anarchist',
            desc: 'Black & red, punk zine',
            emoji: '✊',
            colors: ['#cc0000', '#0d0d0d']
        },
        'goth': {
            name: 'Goth',
            desc: 'Dark academia, ornate',
            emoji: '🦇',
            colors: ['#4a0e6b', '#0a0a0a']
        },
        'witchy': {
            name: 'Witchy',
            desc: 'Mystical, tarot vibes',
            emoji: '🔮',
            colors: ['#6b2fa0', '#1a0a2e']
        },
        'pastel-rainbow-goth': {
            name: 'Pastel Rainbow Goth',
            desc: 'Kawaii-goth hybrid',
            emoji: '🌈',
            colors: ['#ff9de2', '#1a0a2e']
        },
        'cyberpunk': {
            name: 'Cyberpunk Queer',
            desc: 'Neon glitch, tech noir',
            emoji: '⚡',
            colors: ['#ff00ff', '#050510']
        },
        'cottagecore': {
            name: 'Cottagecore Queer',
            desc: 'Cozy nature, floral',
            emoji: '🌿',
            colors: ['#5a7a3a', '#f5f0e8']
        },
        'riot-grrrl': {
            name: 'Riot Grrrl',
            desc: 'Hot pink, feminist punk',
            emoji: '🎸',
            colors: ['#ff0080', '#0d0d0d']
        }
    };

    // ============================================================
    // COOKIE UTILITIES
    // ============================================================

    function setCookie(name, value, days) {
        var expires = '';
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = '; expires=' + date.toUTCString();
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

    // ============================================================
    // STYLE SWITCHING
    // ============================================================

    function getCurrentStyle() {
        return getCookie(COOKIE_NAME) || SAVED_STYLE || 'anarchist';
    }

    function applyStyle(styleName) {
        if (!styles[styleName]) {
            console.warn('QueerDispatch: Unknown style "' + styleName + '"');
            return;
        }

        // Update the stylesheet link
        var existingLink = document.getElementById(STYLESHEET_ID);
        var cssUrl = THEME_URL + '/css/themes/' + styleName + '.css';

        if (existingLink) {
            // Fade transition
            existingLink.style.opacity = '0';
            existingLink.style.transition = 'opacity 0.3s ease';

            setTimeout(function () {
                existingLink.href = cssUrl;
                existingLink.style.opacity = '1';
            }, 150);
        } else {
            var link = document.createElement('link');
            link.rel = 'stylesheet';
            link.id = STYLESHEET_ID;
            link.href = cssUrl;
            document.head.appendChild(link);
        }

        // Update body data attribute
        document.documentElement.setAttribute('data-style', styleName);
        document.body.setAttribute('data-style', styleName);

        // Update body class
        var bodyClasses = document.body.className.split(' ');
        var filteredClasses = bodyClasses.filter(function (cls) {
            return cls.indexOf('style-') !== 0;
        });
        filteredClasses.push('style-' + styleName);
        document.body.className = filteredClasses.join(' ');

        // Save to cookie
        setCookie(COOKIE_NAME, styleName, COOKIE_DAYS);

        // Save via AJAX if available
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

        // Announce to screen readers
        announceStyleChange(styleName);
    }

    function updateSwitcherUI(activeStyle) {
        var options = document.querySelectorAll('.style-option');
        options.forEach(function (option) {
            var style = option.getAttribute('data-style');
            if (style === activeStyle) {
                option.classList.add('active');
                option.setAttribute('aria-pressed', 'true');
            } else {
                option.classList.remove('active');
                option.setAttribute('aria-pressed', 'false');
            }
        });
    }

    function announceStyleChange(styleName) {
        var style = styles[styleName];
        if (!style) return;

        var announcer = document.getElementById('style-announcer');
        if (!announcer) {
            announcer = document.createElement('div');
            announcer.id = 'style-announcer';
            announcer.setAttribute('aria-live', 'polite');
            announcer.setAttribute('aria-atomic', 'true');
            announcer.style.cssText = 'position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0,0,0,0);';
            document.body.appendChild(announcer);
        }
        announcer.textContent = 'Theme style changed to ' + style.name + ': ' + style.desc;
    }

    // ============================================================
    // PANEL TOGGLE
    // ============================================================

    function openPanel() {
        var panel = document.getElementById('style-switcher-panel');
        var toggle = document.getElementById('style-switcher-toggle');
        if (panel && toggle) {
            panel.classList.add('is-open');
            toggle.setAttribute('aria-expanded', 'true');
            // Focus first option
            var firstOption = panel.querySelector('.style-option');
            if (firstOption) firstOption.focus();
        }
    }

    function closePanel() {
        var panel = document.getElementById('style-switcher-panel');
        var toggle = document.getElementById('style-switcher-toggle');
        if (panel && toggle) {
            panel.classList.remove('is-open');
            toggle.setAttribute('aria-expanded', 'false');
        }
    }

    function togglePanel() {
        var panel = document.getElementById('style-switcher-panel');
        if (panel && panel.classList.contains('is-open')) {
            closePanel();
        } else {
            openPanel();
        }
    }

    // ============================================================
    // KEYBOARD NAVIGATION
    // ============================================================

    function handleKeyDown(e) {
        var panel = document.getElementById('style-switcher-panel');
        if (!panel || !panel.classList.contains('is-open')) return;

        var options = Array.from(panel.querySelectorAll('.style-option'));
        var focused = document.activeElement;
        var currentIndex = options.indexOf(focused);

        switch (e.key) {
            case 'Escape':
                closePanel();
                document.getElementById('style-switcher-toggle').focus();
                break;
            case 'ArrowDown':
                e.preventDefault();
                if (currentIndex < options.length - 1) {
                    options[currentIndex + 1].focus();
                }
                break;
            case 'ArrowUp':
                e.preventDefault();
                if (currentIndex > 0) {
                    options[currentIndex - 1].focus();
                } else {
                    document.getElementById('style-switcher-toggle').focus();
                }
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

    // ============================================================
    // INIT
    // ============================================================

    function init() {
        // Toggle button
        var toggle = document.getElementById('style-switcher-toggle');
        if (toggle) {
            toggle.addEventListener('click', function (e) {
                e.stopPropagation();
                togglePanel();
            });
        }

        // Style option buttons
        var options = document.querySelectorAll('.style-option');
        options.forEach(function (option) {
            option.addEventListener('click', function () {
                var styleName = this.getAttribute('data-style');
                if (styleName) {
                    applyStyle(styleName);
                }
            });
        });

        // Close on outside click
        document.addEventListener('click', function (e) {
            var switcher = document.getElementById('style-switcher');
            if (switcher && !switcher.contains(e.target)) {
                closePanel();
            }
        });

        // Keyboard navigation
        document.addEventListener('keydown', handleKeyDown);

        // Apply saved style on load (in case PHP cookie wasn't set yet)
        var savedStyle = getCurrentStyle();
        if (savedStyle && savedStyle !== SAVED_STYLE) {
            applyStyle(savedStyle);
        } else {
            updateSwitcherUI(savedStyle || 'anarchist');
        }

        // Preload all style CSS files for faster switching
        preloadStyles();
    }

    function preloadStyles() {
        Object.keys(styles).forEach(function (styleName) {
            var link = document.createElement('link');
            link.rel = 'prefetch';
            link.href = THEME_URL + '/css/themes/' + styleName + '.css';
            document.head.appendChild(link);
        });
    }

    // ============================================================
    // SMOOTH TRANSITION ON STYLE CHANGE
    // ============================================================

    // Add a transition overlay for smooth style switches
    function addTransitionOverlay() {
        var overlay = document.createElement('div');
        overlay.id = 'style-transition-overlay';
        overlay.style.cssText = [
            'position: fixed',
            'inset: 0',
            'background: rgba(0,0,0,0)',
            'pointer-events: none',
            'z-index: 99999',
            'transition: background 0.2s ease',
        ].join(';');
        document.body.appendChild(overlay);
        return overlay;
    }

    // ============================================================
    // DOM READY
    // ============================================================

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Expose public API
    window.QueerDispatchStyleSwitcher = {
        applyStyle: applyStyle,
        getCurrentStyle: getCurrentStyle,
        styles: styles,
    };

})();
