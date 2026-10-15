document.querySelectorAll('[data-ticket-filter]').forEach(function (form) {
    const toggle = form.querySelector('[data-filter-toggle]');

    if (!toggle) {
        return;
    }

    const panelId = toggle.getAttribute('aria-controls');
    const panel = document.getElementById(panelId);

    if (!panel) {
        return;
    }

    function setFilterOpen(isOpen) {
        panel.hidden = !isOpen;
        toggle.setAttribute('aria-expanded', String(isOpen));
    }

    // Saat JavaScript tersedia, mulai dengan panel tertutup.
    setFilterOpen(false);
    toggle.hidden = false;

    toggle.addEventListener('click', function () {
        setFilterOpen(panel.hidden);
    });

    panel.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            event.preventDefault();
            setFilterOpen(false);
            toggle.focus();
        }
    });
});