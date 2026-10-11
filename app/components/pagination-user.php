<?php

/** @var int $halaman */
/** @var int $totalHalaman */
/** @var int $totalUser */
/** @var string $pencarian */
/** @var string $status */

$parameterDasar = [
    'q' => $pencarian,
    'status' => $status,
];

$buatUrlHalaman = static function (int $nomor) use ($parameterDasar): string {
    return '/admin/users.php?' . http_build_query(
        array_merge(
            $parameterDasar,
            ['page' => $nomor]
        )
    );
};

// Tampilkan semua nomor jika jumlah halaman sedikit.
if ($totalHalaman <= 7) {
    $nomorHalaman = range(1, $totalHalaman);
} else {
    // Selalu tampilkan halaman pertama, terakhir,
// dan nomor di sekitar halaman aktif.
    $nomorHalaman = [1, $totalHalaman];

    $awal = max(2, $halaman - 1);
    $akhir = min($totalHalaman - 1, $halaman + 1);

    for ($nomor = $awal; $nomor <= $akhir; $nomor++) {
        $nomorHalaman[] = $nomor;
    }

    $nomorHalaman = array_unique($nomorHalaman);
    sort($nomorHalaman);
}
?>

<nav class="user-pagination" aria-label="Halaman daftar user">
    <p>
        <?php echo $totalUser; ?> akun ditemukan.
        Halaman <?php echo $halaman; ?>
        dari <?php echo $totalHalaman; ?>.
    </p>

    <?php if ($totalHalaman > 1): ?>
        <div class="user-pagination-links">
            <?php if ($halaman > 1): ?>
                <a
                    href="<?php
                    echo htmlspecialchars(
                        $buatUrlHalaman($halaman - 1),
                        ENT_QUOTES,
                        'UTF-8'
                    );
                    ?>"
                    aria-label="Halaman sebelumnya"
                    rel="prev">
                    <span
                        class="pagination-icon pagination-icon--previous"
                        aria-hidden="true"></span>
                    <span class="pagination-link-text">Sebelumnya</span>
                </a>
            <?php endif; ?>

            <?php $nomorSebelumnya = 0; ?>

            <?php foreach ($nomorHalaman as $nomor): ?>
                <?php if ($nomorSebelumnya > 0 && $nomor - $nomorSebelumnya > 1): ?>
                    <span class="pagination-ellipsis" aria-hidden="true">
                        …
                    </span>
                <?php endif; ?>

                <?php if ($nomor === $halaman): ?>
                    <span
                        class="pagination-current"
                        aria-current="page"
                        aria-label="Halaman <?php echo $nomor; ?>, saat ini">
                        <?php echo $nomor; ?>
                    </span>
                <?php else: ?>
                    <a
                        href="<?php
                        echo htmlspecialchars(
                            $buatUrlHalaman($nomor),
                            ENT_QUOTES,
                            'UTF-8'
                        );
                        ?>"
                        aria-label="Ke halaman <?php echo $nomor; ?>">
                        <?php echo $nomor; ?>
                    </a>
                <?php endif; ?>

                <?php $nomorSebelumnya = $nomor; ?>
            <?php endforeach; ?>

            <?php if ($halaman < $totalHalaman): ?>
                <a
                    href="<?php
                    echo htmlspecialchars(
                        $buatUrlHalaman($halaman + 1),
                        ENT_QUOTES,
                        'UTF-8'
                    );
                    ?>"
                    aria-label="Halaman berikutnya"
                    rel="next">
                    <span class="pagination-link-text">Berikutnya</span>
                    <span
                        class="pagination-icon pagination-icon--next"
                        aria-hidden="true"></span>
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</nav>