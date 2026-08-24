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

function initSidebarToggle() {
    const savedState = localStorage.getItem('sidebar_state');
    const isMobile = window.innerWidth <= 992;
    
    if (isMobile) {
        document.body.classList.remove('sidebar-collapsed');
    } else {
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
    const isMobile = window.innerWidth <= 992;
    if (isMobile) {
        document.body.classList.remove('sidebar-collapsed');
        document.body.classList.toggle('sidebar-mobile-open');
    } else {
        document.body.classList.remove('sidebar-mobile-open');
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
    if (window.innerWidth > 992) {
        document.body.classList.remove('sidebar-mobile-open');
    }
});
