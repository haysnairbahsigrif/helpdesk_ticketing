<?php

/** @var int $halaman */
/** @var int $totalHalaman */
/** @var string $pencarian */
/** @var string $status */
/** @var string $tampilan */

$parameterHalaman = [
    'view' => $tampilan,
    'q' => $pencarian,
    'status' => $status,
];

$buatUrlTiketUser = static function (int $nomor) use ($parameterHalaman): string {
    return '/user/tiket.php?' . http_build_query(
        array_merge(
            $parameterHalaman,
            ['page' => $nomor]
        )
    );
};

if ($totalHalaman <= 7) {
    $nomorHalamanUser = range(1, $totalHalaman);
} else {
    $nomorHalamanUser = [1, $totalHalaman];

    $awal = max(2, $halaman - 1);
    $akhir = min($totalHalaman - 1, $halaman + 1);

    for ($nomor = $awal; $nomor <= $akhir; $nomor++) {
        $nomorHalamanUser[] = $nomor;
    }

    $nomorHalamanUser = array_unique($nomorHalamanUser);
    sort($nomorHalamanUser);
}
?>

<?php if ($totalHalaman > 1): ?>
    <nav class="user-pagination" aria-label="Halaman tiket saya">
        <p>
            Halaman <?php echo $halaman; ?>
            dari <?php echo $totalHalaman; ?>
        </p>

        <div class="user-pagination-links">
            <?php if ($halaman > 1): ?>
                <a
                    href="<?php
                    echo htmlspecialchars(
                        $buatUrlTiketUser($halaman - 1),
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

            <?php $nomorUserSebelumnya = 0; ?>

            <?php foreach ($nomorHalamanUser as $nomor): ?>
                <?php if (
                    $nomorUserSebelumnya > 0
                    && $nomor - $nomorUserSebelumnya > 1
                ): ?>
                    <span
                        class="user-pagination-ellipsis"
                        aria-hidden="true">
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
                            $buatUrlTiketUser($nomor),
                            ENT_QUOTES,
                            'UTF-8'
                        );
                        ?>"
                        aria-label="Ke halaman <?php echo $nomor; ?>">
                        <?php echo $nomor; ?>
                    </a>
                <?php endif; ?>

                <?php $nomorUserSebelumnya = $nomor; ?>
            <?php endforeach; ?>

            <?php if ($halaman < $totalHalaman): ?>
                <a
                    href="<?php
                    echo htmlspecialchars(
                        $buatUrlTiketUser($halaman + 1),
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