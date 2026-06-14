(function () {
    'use strict';

    document.documentElement.classList.add('js-animations');

    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (prefersReducedMotion) {
        document.documentElement.classList.add('reduce-motion');
    }

    function whenReady(fn) {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', fn);
        } else {
            fn();
        }
    }

    function initScrollReveal() {
        if (prefersReducedMotion) return;

        const elements = document.querySelectorAll('[data-reveal]');
        if (!elements.length) return;

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px',
        });

        elements.forEach((el) => observer.observe(el));
    }

    function initHeroAnimations() {
        if (prefersReducedMotion) return;

        const hero = document.querySelector('.hero');
        if (!hero) return;

        hero.classList.add('hero-animated');

        const staggerItems = hero.querySelectorAll('[data-stagger]');
        staggerItems.forEach((item, index) => {
            item.style.transitionDelay = `${index * 80}ms`;
            item.classList.add('is-visible');
        });
    }

    function initNavbarScroll() {
        const navbar = document.querySelector('.navbar');
        if (!navbar) return;

        function update() {
            if (window.scrollY > 20) {
                navbar.classList.add('is-scrolled');
            } else {
                navbar.classList.remove('is-scrolled');
            }
        }

        update();
        window.addEventListener('scroll', update, { passive: true });
    }

    function initMobileMenu() {
        const toggle = document.querySelector('.navbar-toggle');
        const menu = document.querySelector('.nav-links');
        if (!toggle || !menu) return;

        toggle.addEventListener('click', () => {
            const isOpen = menu.classList.toggle('is-open');
            toggle.setAttribute('aria-expanded', String(isOpen));
        });
    }

    whenReady(() => {
        initScrollReveal();
        initHeroAnimations();
        initNavbarScroll();
        initMobileMenu();
    });
})();
