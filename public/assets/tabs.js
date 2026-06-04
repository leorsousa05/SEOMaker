(function () {
    'use strict';

    function initTabs(container) {
        const nav = container.querySelector('.tabs-nav');
        if (!nav) return;

        const panels = container.querySelectorAll('.tabs-panel');
        const hiddenInput = container.querySelector('input[name="active_tab"]');
        const items = nav.querySelectorAll('.tabs-nav-item');

        function activate(tab) {
            panels.forEach(function (p) {
                p.classList.toggle('active', p.dataset.tab === tab);
            });
            items.forEach(function (b) {
                b.classList.toggle('active', b.dataset.tab === tab);
            });
            if (hiddenInput) hiddenInput.value = tab;
            try {
                history.replaceState(null, '', '#' + tab);
            } catch (e) {}
        }

        nav.addEventListener('click', function (e) {
            const btn = e.target.closest('[data-tab]');
            if (!btn) return;
            activate(btn.dataset.tab);
        });

        const hash = location.hash.slice(1);
        if (hash) {
            const btn = nav.querySelector('[data-tab="' + hash + '"]');
            if (btn) activate(hash);
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-tabs]').forEach(initTabs);
    });

    window.Tabs = { init: initTabs };
})();
