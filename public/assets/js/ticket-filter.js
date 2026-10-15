document.querySelectorAll('[data-ticket-filter]').forEach(function (form) {
    const dropdowns = form.querySelectorAll('[data-filter-dropdown]');

    dropdowns.forEach(function (dropdown) {
        const counter = dropdown.querySelector('[data-filter-count]');
        const summary = dropdown.querySelector('summary');

        // Perbarui jumlah pilihan tanpa memindahkan fokus.
        dropdown.addEventListener('change', function () {
            const jumlah = dropdown.querySelectorAll(
                'input[type="checkbox"]:checked'
            ).length;

            if (counter) {
                counter.textContent = jumlah > 0 ? `(${jumlah})` : '';
            }
        });

        // Tutup menggunakan Escape.
        dropdown.addEventListener('keydown', function (event) {
            if (event.key !== 'Escape' || !dropdown.open) {
                return;
            }

            event.preventDefault();
            dropdown.open = false;

            if (summary) {
                summary.focus();
            }
        });
    });
});