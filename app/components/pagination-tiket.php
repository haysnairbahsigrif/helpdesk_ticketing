<?php

/** @var int $halaman */
/** @var int $totalHalaman */
/** @var string $pencarian */
/** @var string|array $status */
/** @var array $filterPrioritas */

$parameterHalaman = [
    'q' => $pencarian,
    'status' => $status,
    'prioritas' => $filterPrioritas ?? [],
];

$buatUrlTiket = static function (int $nomor) use ($parameterHalaman): string {
    return '/admin/tiket.php?' . http_build_query(
        array_merge(
            $parameterHalaman,
            ['page' => $nomor]
        )
    );
};

if ($totalHalaman <= 7) {
    $nomorHalamanTiket = range(1, $totalHalaman);
} else {
    $nomorHalamanTiket = [1, $totalHalaman];

    $awal = max(2, $halaman - 1);
    $akhir = min($totalHalaman - 1, $halaman + 1);

    for ($nomor = $awal; $nomor <= $akhir; $nomor++) {
        $nomorHalamanTiket[] = $nomor;
    }

    $nomorHalamanTiket = array_unique($nomorHalamanTiket);
    sort($nomorHalamanTiket);
}
?>

<?php if ($totalHalaman > 1): ?>
    <nav class="ticket-pagination" aria-label="Halaman daftar tiket">
        <p>
            Halaman <?php echo $halaman; ?>
            dari <?php echo $totalHalaman; ?>
        </p>

        <div class="ticket-pagination-links">
            <?php if ($halaman > 1): ?>
                <a
                    href="<?php
                            echo htmlspecialchars(
                                $buatUrlTiket($halaman - 1),
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

            <?php $nomorTiketSebelumnya = 0; ?>

            <?php foreach ($nomorHalamanTiket as $nomor): ?>
                <?php if (
                    $nomorTiketSebelumnya > 0
                    && $nomor - $nomorTiketSebelumnya > 1
                ): ?>
                    <span class="ticket-pagination-ellipsis" aria-hidden="true">
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
                                    $buatUrlTiket($nomor),
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>"
                        aria-label="Ke halaman <?php echo $nomor; ?>">
                        <?php echo $nomor; ?>
                    </a>
                <?php endif; ?>

                <?php $nomorTiketSebelumnya = $nomor; ?>
            <?php endforeach; ?>

            <?php if ($halaman < $totalHalaman): ?>
                <a
                    href="<?php
                            echo htmlspecialchars(
                                $buatUrlTiket($halaman + 1),
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
    </nav>
<?php endif; ?>