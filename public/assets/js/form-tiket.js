const kolomKeluhan = document.querySelector('#keluhan');
const penghitungKeluhan = document.querySelector(
    '#jumlah-karakter-keluhan'
);

if (kolomKeluhan && penghitungKeluhan) {
    function perbaruiJumlahKarakter() {
        const jumlah = Array.from(kolomKeluhan.value).length;
        const melewatiBatas = jumlah > 5000;

        penghitungKeluhan.textContent =
            `Terisi ${jumlah.toLocaleString('id-ID')} karakter.` +
            (melewatiBatas ? ' Kurangi teks sebelum mengirim.' : '');

        penghitungKeluhan.classList.toggle(
            'keluhan-melebihi-batas',
            melewatiBatas
        );
    }

    kolomKeluhan.addEventListener('input', perbaruiJumlahKarakter);

    // Hitung juga teks yang sudah ada saat halaman dibuka.
    perbaruiJumlahKarakter();
}