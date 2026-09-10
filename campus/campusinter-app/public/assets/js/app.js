/**
 * CAMPUS INTER - Application principale
 * Point d'entrée JavaScript
 */

document.addEventListener('DOMContentLoaded', () => {
    // Initialiser les composants communs
    CIApp.init();
});

const CIApp = {
    init() {
        this.initSmoothScroll();
        this.initTooltips();
        this.initMobileMenu();
    },

    // Mobile menu toggle
    initMobileMenu() {
        const toggle = document.getElementById('ci-menu-toggle');
        const nav = document.getElementById('ci-header-nav');
        const overlay = document.getElementById('ci-header-overlay');

        if (!toggle || !nav) return;

        const close = () => {
            nav.classList.remove('ci-header__nav--open');
            overlay.classList.remove('ci-header__overlay--visible');
            toggle.setAttribute('aria-expanded', 'false');
            document.body.style.overflow = '';
        };

        toggle.addEventListener('click', () => {
            const isOpen = nav.classList.contains('ci-header__nav--open');
            if (isOpen) {
                close();
            } else {
                nav.classList.add('ci-header__nav--open');
                overlay.classList.add('ci-header__overlay--visible');
                toggle.setAttribute('aria-expanded', 'true');
                document.body.style.overflow = 'hidden';
            }
        });

        if (overlay) {
            overlay.addEventListener('click', close);
        }

        // Close on Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && nav.classList.contains('ci-header__nav--open')) {
                close();
            }
        });

        // Close on resize to desktop
        const mq = window.matchMedia('(min-width: 1024px)');
        mq.addEventListener('change', (e) => {
            if (e.matches) close();
        });
    },

    // Smooth scroll pour les ancres
    initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', (e) => {
                const href = anchor.getAttribute('href');
                if (href === '#') return;

                const target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    },

    // Tooltips simples
    initTooltips() {
        document.querySelectorAll('[data-ci-tooltip]').forEach(el => {
            el.style.position = 'relative';
            el.style.cursor = 'help';

            el.addEventListener('mouseenter', () => {
                const tooltip = document.createElement('div');
                tooltip.className = 'ci-tooltip';
                tooltip.textContent = el.dataset.ciTooltip;
                tooltip.style.cssText = `
                    position: absolute;
                    bottom: 100%;
                    left: 50%;
                    transform: translateX(-50%);
                    background: var(--ci-gray-900);
                    color: var(--ci-white);
                    padding: 4px 8px;
                    border-radius: 4px;
                    font-size: 0.75rem;
                    white-space: nowrap;
                    z-index: 100;
                    pointer-events: none;
                `;
                el.appendChild(tooltip);
            });

            el.addEventListener('mouseleave', () => {
                const tooltip = el.querySelector('.ci-tooltip');
                if (tooltip) tooltip.remove();
            });
        });
    },
};
