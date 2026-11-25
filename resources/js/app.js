import * as bootstrap from 'bootstrap';

window.bootstrap = bootstrap;

// Sidebar scroll persistence (Anti-Jump)
(function () {
    function initSidebarScroll() {
        const sidebar = document.getElementById('sidebarMenuScrollable');
        if (!sidebar) return;

        const savedScroll = sessionStorage.getItem('arap_sidebar_scroll');
        if (savedScroll !== null) {
            sidebar.scrollTop = parseInt(savedScroll, 10);
        }

        sidebar.addEventListener('scroll', function () {
            sessionStorage.setItem('arap_sidebar_scroll', sidebar.scrollTop);
        }, { passive: true });

        sidebar.querySelectorAll('a, button').forEach(function (el) {
            el.addEventListener('click', function () {
                sessionStorage.setItem('arap_sidebar_scroll', sidebar.scrollTop);
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSidebarScroll);
    } else {
        initSidebarScroll();
    }
})();
