/**
 * QueerDispatch Navigation
 * Mobile menu toggle and dropdown handling
 *
 * @package QueerDispatch
 */

(function () {
    'use strict';

    function init() {
        var menuToggle = document.getElementById('menu-toggle');
        var primaryMenu = document.getElementById('primary-menu');

        if (!menuToggle || !primaryMenu) return;

        // Toggle mobile menu
        menuToggle.addEventListener('click', function () {
            var isOpen = primaryMenu.classList.contains('is-open');
            primaryMenu.classList.toggle('is-open', !isOpen);
            menuToggle.setAttribute('aria-expanded', String(!isOpen));
        });

        // Handle dropdown submenus on touch devices
        var menuItems = primaryMenu.querySelectorAll('.menu-item-has-children > a');
        menuItems.forEach(function (link) {
            link.addEventListener('click', function (e) {
                if (window.innerWidth <= 768) {
                    var parent = this.parentElement;
                    var subMenu = parent.querySelector('.sub-menu');
                    if (subMenu) {
                        e.preventDefault();
                        var isExpanded = subMenu.style.display === 'block';
                        subMenu.style.display = isExpanded ? 'none' : 'block';
                        this.setAttribute('aria-expanded', String(!isExpanded));
                    }
                }
            });
        });

        // Close menu on outside click
        document.addEventListener('click', function (e) {
            if (!primaryMenu.contains(e.target) && !menuToggle.contains(e.target)) {
                primaryMenu.classList.remove('is-open');
                menuToggle.setAttribute('aria-expanded', 'false');
            }
        });

        // Close menu on Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                primaryMenu.classList.remove('is-open');
                menuToggle.setAttribute('aria-expanded', 'false');
                menuToggle.focus();
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
