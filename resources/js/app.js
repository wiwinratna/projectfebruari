import './bootstrap';

// Mobile Sidebar Toggle
document.addEventListener('DOMContentLoaded', function () {
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebarClose = document.getElementById('sidebar-close');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');

    // Debug log
    console.log('Sidebar elements:', { sidebarToggle, sidebarClose, sidebar, overlay });

    // Toggle sidebar on hamburger click
    if (sidebarToggle && sidebar && overlay) {
        sidebarToggle.addEventListener('click', function (e) {
            e.preventDefault();
            console.log('Hamburger clicked');

            if (sidebar.classList.contains('show')) {
                closeSidebar();
            } else {
                // Show overlay first
                overlay.classList.add('show');

                // Small delay to ensure overlay is rendered before sidebar animation
                setTimeout(() => {
                    sidebar.classList.add('show');
                }, 10);

                document.body.style.overflow = 'hidden';
            }
        });
    }

    // Close sidebar functions
    function closeSidebar() {
        console.log('Closing sidebar');
        if (sidebar) {
            // Add closing animation class
            sidebar.classList.add('closing');
            sidebar.classList.remove('show');

            // Remove closing class after animation completes
            setTimeout(() => {
                sidebar.classList.remove('closing');
            }, 300);
        }

        // Delay overlay removal to allow sidebar animation to complete
        setTimeout(() => {
            if (overlay) overlay.classList.remove('show');
        }, 300);

        document.body.style.overflow = '';
    }

    // Close sidebar on close button click
    if (sidebarClose) {
        sidebarClose.addEventListener('click', function (e) {
            e.preventDefault();
            closeSidebar();
        });
    }

    // Close sidebar on overlay click
    if (overlay) {
        overlay.addEventListener('click', closeSidebar);
    }

    // Close sidebar on escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeSidebar();
        }
    });

    // Handle window resize
    window.addEventListener('resize', function () {
        if (window.innerWidth >= 1024) {
            closeSidebar();
        }
    });

    // Flash alerts auto-hide
    const flashMessages = document.querySelectorAll('.flash-message');
    const flashContainer = document.getElementById('flash-container');

    const hideFlash = (element) => {
        if (!element) return;
        element.classList.add('opacity-0', 'translate-x-4', 'pointer-events-none');
        setTimeout(() => {
            element.remove();
            if (flashContainer && flashContainer.childElementCount === 0) {
                flashContainer.remove();
            }
        }, 300);
    };

    flashMessages.forEach((message) => {
        const timeout = parseInt(message.dataset.timeout || '4000', 10);
        setTimeout(() => hideFlash(message), timeout);

        const closeBtn = message.querySelector('[data-flash-close]');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => hideFlash(message));
        }
    });
});
