(() => {
    const buttons = document.querySelectorAll('[data-ticket-dialog]');

    if (
        buttons.length === 0
        || typeof HTMLDialogElement === 'undefined'
        || typeof HTMLDialogElement.prototype.showModal !== 'function'
    ) {
        return;
    }

    buttons.forEach(function (button) {
        const dialogId = button.getAttribute('data-ticket-dialog');
        const dialog = document.getElementById(dialogId);

        if (!(dialog instanceof HTMLDialogElement)) {
            return;
        }

        // Tampilkan tombol setelah modalnya ditemukan.
        button.hidden = false;

        const row = button.closest('[data-ticket-row]');

        if (row) {
            row.classList.add('ticket-detail-ready');
        }

        button.addEventListener('click', function () {
            if (dialog.open) {
                return;
            }

            dialog.showModal();

            document.documentElement.classList.add(
                'ticket-dialog-open'
            );
        });

        const closeButtons = dialog.querySelectorAll(
            '[data-close-ticket-dialog]'
        );

        closeButtons.forEach(function (closeButton) {
            closeButton.addEventListener('click', function () {
                dialog.close();
            });
        });

        // Berlaku juga saat ditutup menggunakan Escape.
        dialog.addEventListener('close', function () {
            document.documentElement.classList.remove(
                'ticket-dialog-open'
            );

            button.focus({ preventScroll: true });
        });
    });
})();