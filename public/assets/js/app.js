document.addEventListener('submit', function (event) {
    const form = event.target;

    if (!(form instanceof HTMLFormElement)) {
        return;
    }

    const pesan = form.dataset.confirm;

    // Formulir tanpa pesan konfirmasi berjalan seperti biasa.
    if (!pesan) {
        return;
    }

    const disetujui = window.confirm(pesan);

    if (!disetujui) {
        event.preventDefault();
    }
});