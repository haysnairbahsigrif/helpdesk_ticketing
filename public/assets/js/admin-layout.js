const menuToggle = document.querySelector('.admin-menu-toggle');
const sidebar = document.querySelector('.admin-sidebar');
const overlay = document.querySelector('.admin-overlay');

/* Buka dan tutup submenu. */
document.querySelectorAll('.admin-submenu-toggle').forEach(function (button) {
    const submenuId = button.getAttribute('aria-controls');
    const submenu = submenuId
        ? document.getElementById(submenuId)
        : null;

    if (!submenu) {
        return;
    }

    button.addEventListener('click', function () {
        const isExpanded = button.getAttribute('aria-expanded') === 'true';

        button.setAttribute('aria-expanded', String(!isExpanded));
        submenu.hidden = isExpanded;
    });
});

/* Buka dan tutup sidebar pada layar kecil. */
if (menuToggle && sidebar && overlay) {
    const mobileScreen = window.matchMedia('(max-width: 900px)');

    function getFocusableElements() {
        const elements = sidebar.querySelectorAll(
            'a[href], button:not([disabled])'
        );

        return Array.from(elements).filter(function (element) {
            return !element.closest('[hidden]')
                && element.getClientRects().length > 0;
        });
    }

    function setSidebarOpen(isOpen) {
        document.body.classList.toggle('sidebar-open', isOpen);
        menuToggle.setAttribute('aria-expanded', String(isOpen));
        overlay.hidden = !isOpen;

        if (isOpen) {
            const firstElement = getFocusableElements()[0];
            firstElement?.focus();
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

        if (!isOpen || !mobileScreen.matches) {
            return;
        }

        if (event.key === 'Escape') {
            event.preventDefault();
            closeSidebar();
            return;
        }

        /* Batasi fokus pada elemen sidebar yang sedang terlihat. */
        if (event.key === 'Tab') {
            const elements = getFocusableElements();
            const firstElement = elements[0];
            const lastElement = elements[elements.length - 1];

            if (!firstElement || !lastElement) {
                return;
            }

            if (!sidebar.contains(document.activeElement)) {
                event.preventDefault();

                if (event.shiftKey) {
                    lastElement.focus();
                } else {
                    firstElement.focus();
                }

                return;
            }

            if (event.shiftKey && document.activeElement === firstElement) {
                event.preventDefault();
                lastElement.focus();
            } else if (
                !event.shiftKey
                && document.activeElement === lastElement
            ) {
                event.preventDefault();
                firstElement.focus();
            }
        }
    });

    sidebar.addEventListener('click', function (event) {
        if (
            event.target instanceof Element
            && event.target.closest('a')
            && mobileScreen.matches
        ) {
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