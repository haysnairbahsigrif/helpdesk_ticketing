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
?>

<nav class="user-pagination" aria-label="Halaman daftar user">
    <p>
        <?php echo $totalUser; ?> akun ditemukan.
        Halaman <?php echo $halaman; ?>
        dari <?php echo $totalHalaman; ?>.
    </p>

    <div class="user-pagination-links">
        <?php if ($halaman > 1): ?>
            <?php
            $urlSebelumnya = '/admin/users.php?' . http_build_query(
                array_merge(
                    $parameterDasar,
                    ['page' => $halaman - 1]
                )
            );
            ?>

            <a href="<?php
                echo htmlspecialchars($urlSebelumnya, ENT_QUOTES, 'UTF-8');
            ?>">
                Sebelumnya
            </a>
        <?php endif; ?>

        <?php if ($halaman < $totalHalaman): ?>
            <?php
            $urlBerikutnya = '/admin/users.php?' . http_build_query(
                array_merge(
                    $parameterDasar,
                    ['page' => $halaman + 1]
                )
            );
            ?>

            <a href="<?php
                echo htmlspecialchars($urlBerikutnya, ENT_QUOTES, 'UTF-8');
            ?>">
                Berikutnya
            </a>
        <?php endif; ?>
    </div>
</nav>