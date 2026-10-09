const menuToggle = document.querySelector('.admin-menu-toggle');
const sidebar = document.querySelector('.admin-sidebar');
const overlay = document.querySelector('.admin-overlay');

if (menuToggle && sidebar && overlay) {
    const mobileScreen = window.matchMedia('(max-width: 900px)');

    function setSidebarOpen(isOpen) {
        document.body.classList.toggle('sidebar-open', isOpen);
        menuToggle.setAttribute('aria-expanded', String(isOpen));
        overlay.hidden = !isOpen;

        if (isOpen) {
            const firstLink = sidebar.querySelector('a');
            firstLink?.focus();
        }
    }

    function closeSidebar() {
        setSidebarOpen(false);
        menuToggle.focus();
    }

    menuToggle.addEventListener('click', function () {
        const isOpen = document.body.classList.contains('sidebar-open');
        setSidebarOpen(!isOpen);
    });

    overlay.addEventListener('click', closeSidebar);

    document.addEventListener('keydown', function (event) {
        const isOpen = document.body.classList.contains('sidebar-open');

        if (!isOpen) {
            return;
        }

        if (event.key === 'Escape') {
            closeSidebar();
        }

        // Jaga fokus keyboard di dalam menu saat sidebar terbuka.
        if (event.key === 'Tab') {
            const links = sidebar.querySelectorAll('a[href]');
            const firstLink = links[0];
            const lastLink = links[links.length - 1];

            if (event.shiftKey && document.activeElement === firstLink) {
                event.preventDefault();
                lastLink.focus();
            } else if (!event.shiftKey && document.activeElement === lastLink) {
                event.preventDefault();
                firstLink.focus();
            }
        }
    });

    sidebar.addEventListener('click', function (event) {
        if (event.target.closest('a') && mobileScreen.matches) {
            closeSidebar();
        }
    });

    mobileScreen.addEventListener('change', function () {
        const focusWasInSidebar = sidebar.contains(document.activeElement);
        setSidebarOpen(false);

        if (mobileScreen.matches && focusWasInSidebar) {
            menuToggle.focus();
        }
    });
}