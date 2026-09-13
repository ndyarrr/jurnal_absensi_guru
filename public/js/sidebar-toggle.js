/**
 * Sidebar Toggle & Mobile Responsiveness Handler
 * Includes instant pre-rendering check in <head> to eliminate FOUC / flash of uncollapsed sidebar on page load.
 */

// Immediate execution (before DOM paint) if script is in <head>
(function() {
    try {
        var savedState = localStorage.getItem('sidebar_state');
        var isDesktop = window.innerWidth > 992;
        if (isDesktop && savedState === 'collapsed') {
            document.documentElement.classList.add('sidebar-collapsed-init');
        }
    } catch(e) {}
})();

function isMobileViewport() {
    return window.innerWidth <= 992;
}

function setMobileSidebarOpen(isOpen) {
    document.body.classList.toggle('sidebar-mobile-open', isOpen);
    document.body.style.overflow = isOpen ? 'hidden' : '';
}

function initSidebarToggle() {
    const savedState = localStorage.getItem('sidebar_state');

    if (isMobileViewport()) {
        document.body.classList.remove('sidebar-collapsed');
        setMobileSidebarOpen(false);
    } else {
        setMobileSidebarOpen(false);
        if (savedState === 'collapsed') {
            document.body.classList.add('sidebar-collapsed');
        } else {
            document.body.classList.remove('sidebar-collapsed');
        }
    }

    // Remove pre-init class after DOM body state is synchronized
    setTimeout(function() {
        document.documentElement.classList.remove('sidebar-collapsed-init');
    }, 50);
}

function toggleSidebar() {
    if (isMobileViewport()) {
        document.body.classList.remove('sidebar-collapsed');
        setMobileSidebarOpen(!document.body.classList.contains('sidebar-mobile-open'));
    } else {
        setMobileSidebarOpen(false);
        document.body.classList.toggle('sidebar-collapsed');
        const isCollapsed = document.body.classList.contains('sidebar-collapsed');
        localStorage.setItem('sidebar_state', isCollapsed ? 'collapsed' : 'expanded');
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initSidebarToggle);
} else {
    initSidebarToggle();
}

window.addEventListener('resize', function() {
    if (!isMobileViewport()) {
        setMobileSidebarOpen(false);
    }
});

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape' && document.body.classList.contains('sidebar-mobile-open')) {
        setMobileSidebarOpen(false);
    }
});

document.addEventListener('click', function(event) {
    if (!document.body.classList.contains('sidebar-mobile-open')) {
        return;
    }

    const link = event.target.closest && event.target.closest('.dash-sidebar a');
    if (link) {
        setMobileSidebarOpen(false);
    }
});

function toggleSubmenu(id) {
    const el = document.getElementById(id);
    if (!el) return;

    const isHidden = !el.style.display || el.style.display === 'none';
    if (isHidden) {
        el.style.display = 'flex';
        el.classList.add('is-open');
    } else {
        el.style.display = 'none';
        el.classList.remove('is-open');
    }
}