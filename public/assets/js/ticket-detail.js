(() => {
    const buttons = document.querySelectorAll('[data-ticket-dialog]');

    if (
        typeof HTMLDialogElement === 'undefined'
        || typeof HTMLDialogElement.prototype.showModal !== 'function'
    ) {
        return;
    }

    const url = new URL(window.location.href);
    const tiketUntukDibuka = url.searchParams.get('buka_tiket');

    // Hapus penanda agar refresh tidak membuka modal berulang kali.
    if (url.searchParams.has('buka_tiket')) {
        url.searchParams.delete('buka_tiket');

        window.history.replaceState(
            window.history.state,
            '',
            url.pathname + url.search + url.hash
        );
    }

    let bukaModalOtomatis = null;

    buttons.forEach(function (button) {
        const dialogId = button.getAttribute('data-ticket-dialog');

        if (!dialogId) {
            return;
        }

        const dialog = document.getElementById(dialogId);

        if (!(dialog instanceof HTMLDialogElement)) {
            return;
        }

        button.hidden = false;

        const row = button.closest('[data-ticket-row]');

        if (row) {
            row.classList.add('ticket-detail-ready');
        }

        function bukaModal() {
            if (dialog.open) {
                return;
            }

            dialog.showModal();

            document.documentElement.classList.add(
                'ticket-dialog-open'
            );
        }

        button.addEventListener('click', bukaModal);

        dialog.querySelectorAll('[data-close-ticket-dialog]')
            .forEach(function (closeButton) {
                closeButton.addEventListener('click', function () {
                    dialog.close();
                });
            });

        // Berlaku juga ketika modal ditutup menggunakan Escape.
        dialog.addEventListener('close', function () {
            document.documentElement.classList.remove(
                'ticket-dialog-open'
            );

            button.focus({ preventScroll: true });
        });

        if (
            tiketUntukDibuka !== null
            && /^[1-9]\d*$/.test(tiketUntukDibuka)
            && dialogId === 'detail-admin-' + tiketUntukDibuka
        ) {
            bukaModalOtomatis = function () {
                // Tampilkan hasil tindakan di dalam modal agar terbaca.
                const notification = document.querySelector(
                    '.admin-content > .notification'
                );
                const body = dialog.querySelector('.ticket-dialog-body');

                if (notification && body) {
                    body.prepend(notification);
                }

                bukaModal();
            };
        }
    });

    if (bukaModalOtomatis) {
        bukaModalOtomatis();
    }
})();