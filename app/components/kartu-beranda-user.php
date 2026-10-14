<?php

/** @var int $jumlahTiketAktif */
/** @var int $jumlahTiketSelesai */
/** @var bool $gagalMemuatRingkasan */
?>

<div class="user-home-grid">
    <section class="user-home-card" aria-labelledby="judul-kartu-tambah">
        <div class="user-home-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" focusable="false">
                <path d="M4 4h16v5a3 3 0 0 0 0 6v5H4v-5a3 3 0 0 0 0-6Z"/>
                <path d="M12 8v8M8 12h8"/>
            </svg>
        </div>

        <h2 id="judul-kartu-tambah">Tambah Tiket</h2>

        <p class="user-home-description">
            Laporkan kendala IT yang Anda alami.
        </p>

        <p class="user-home-note">
            Jelaskan kendala agar tim IT dapat membantu Anda.
        </p>

        <a
            class="user-home-button user-home-button--primary"
            href="/user/tambah-tiket.php"
        >
            <span aria-hidden="true">＋</span>
            Buat tiket baru
        </a>
    </section>

    <section class="user-home-card" aria-labelledby="judul-kartu-tiket">
        <div class="user-home-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" focusable="false">
                <path d="M4 4h16v5a3 3 0 0 0 0 6v5H4v-5a3 3 0 0 0 0-6Z"/>
                <path d="M8 9h1m3 0h4M8 15h1m3 0h4"/>
            </svg>
        </div>

        <h2 id="judul-kartu-tiket">Tiket Saya</h2>

        <p class="user-home-description">
            Pantau perkembangan laporan yang sudah Anda kirim.
        </p>

        <?php if ($gagalMemuatRingkasan): ?>
            <p class="user-home-note" role="alert">
                Jumlah tiket belum dapat dimuat. Silakan muat ulang halaman.
            </p>
        <?php else: ?>
            <div class="user-home-badges">
                <span class="user-home-badge user-home-badge--active">
                    <?php echo number_format($jumlahTiketAktif, 0, ',', '.'); ?>
                    tiket aktif
                </span>

                <span class="user-home-badge user-home-badge--done">
                    <?php echo number_format($jumlahTiketSelesai, 0, ',', '.'); ?>
                    selesai
                </span>
            </div>
        <?php endif; ?>

        <a
            class="user-home-button user-home-button--outline"
            href="/user/tiket.php?view=semua"
        >
            Lihat tiket saya
            <span aria-hidden="true">→</span>
        </a>
    </section>
</div>