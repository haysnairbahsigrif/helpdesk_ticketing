<?php

/** @var array{id: int, status: string} $tiket */

$statusTujuanTiket = match ($tiket['status']) {
    'Baru' => 'Diproses',
    'Diproses' => 'Selesai',
    default => null,
};

$labelTindakanTiket = $tiket['status'] === 'Baru'
    ? 'Mulai proses'
    : 'Tandai selesai';
?>

<?php if ($statusTujuanTiket !== null): ?>
    <form
        class="status-form"
        action="/actions/ubah-status.php"
        method="post">

        <input
            type="hidden"
            name="csrf_token"
            value="<?php
                    echo htmlspecialchars(
                        $_SESSION['csrf_token'],
                        ENT_QUOTES,
                        'UTF-8'
                    );
                    ?>">

        <input
            type="hidden"
            name="tiket_id"
            value="<?php echo (int) $tiket['id']; ?>">

        <input
            type="hidden"
            name="status"
            value="<?php
                    echo htmlspecialchars(
                        $statusTujuanTiket,
                        ENT_QUOTES,
                        'UTF-8'
                    );
                    ?>">

        <input
            type="hidden"
            name="q"
            value="<?php
                    echo htmlspecialchars(
                        $pencarian ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    );
                    ?>">

        <input
            type="hidden"
            name="filter_status"
            value="<?php
                    echo htmlspecialchars(
                        $status ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    );
                    ?>">

        <input
            type="hidden"
            name="page"
            value="<?php echo (int) ($halaman ?? 1); ?>">

        <input
            type="hidden"
            name="view"
            value="<?php
                    echo htmlspecialchars(
                        $tampilan ?? 'aktif',
                        ENT_QUOTES,
                        'UTF-8'
                    );
                    ?>">

        <button type="submit" class="button-primary">
            <?php
            echo htmlspecialchars(
                $labelTindakanTiket,
                ENT_QUOTES,
                'UTF-8'
            );
            ?>
        </button>
    </form>

<?php elseif ($tiket['status'] === 'Selesai'): ?>
    <p class="ticket-date">
        Penanganan tiket telah selesai.
    </p>
<?php endif; ?>