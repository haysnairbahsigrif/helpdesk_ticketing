<?php

/** @var int $halaman */
/** @var int $totalHalaman */
/** @var string $pencarian */
/** @var string $status */

$parameterHalaman = [
    'q' => $pencarian,
    'status' => $status,
];
?>

<?php if ($totalHalaman > 1): ?>
    <nav class="user-pagination" aria-label="Halaman tiket saya">
        <p>
            Halaman <?php echo $halaman; ?>
            dari <?php echo $totalHalaman; ?>
        </p>

        <div class="user-pagination-links">
            <?php if ($halaman > 1): ?>
                <a href="<?php
                echo htmlspecialchars(
                    '/user/?' . http_build_query(
                        array_merge(
                            $parameterHalaman,
                            ['page' => $halaman - 1]
                        )
                    ),
                    ENT_QUOTES,
                    'UTF-8'
                );
                ?>" rel="prev">
                    Sebelumnya
                </a>
            <?php endif; ?>

            <?php if ($halaman < $totalHalaman): ?>
                <a href="<?php
                echo htmlspecialchars(
                    '/user/?' . http_build_query(
                        array_merge(
                            $parameterHalaman,
                            ['page' => $halaman + 1]
                        )
                    ),
                    ENT_QUOTES,
                    'UTF-8'
                );
                ?>" rel="next">
                    Berikutnya
                </a>
            <?php endif; ?>
        </div>
    </nav>
<?php endif; ?>