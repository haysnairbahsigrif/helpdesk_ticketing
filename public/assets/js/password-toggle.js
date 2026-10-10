document.querySelectorAll('[data-password-toggle]').forEach(
    function (tombol) {
        const targetId = tombol.getAttribute('aria-controls');
        const kolom = document.getElementById(targetId);
        const ikon = tombol.querySelector('[data-password-icon]');

        if (!(kolom instanceof HTMLInputElement)) {
            return;
        }

        function perbaruiTombol() {
            const terlihat = kolom.type === 'text';

            const label = terlihat ?
                'Sembunyikan password' :
                'Tampilkan password';

            tombol.setAttribute('aria-label', label);
            tombol.setAttribute('title', label);

            if (ikon instanceof HTMLImageElement) {
                ikon.src = terlihat ?
                    '/assets/icons/eye-line.svg' :
                    '/assets/icons/eye-off-line.svg';
            } else {
                tombol.textContent = terlihat ?
                    'Sembunyikan' :
                    'Tampilkan';
            }
        }

        tombol.addEventListener('click', function () {
            kolom.type = kolom.type === 'password' ?
                'text' :
                'password';

            perbaruiTombol();
        });

        perbaruiTombol();
        tombol.hidden = false;
    }
);