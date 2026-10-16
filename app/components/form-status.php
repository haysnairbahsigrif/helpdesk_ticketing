<?php

/** @var array{id: int, status: string} $tiket */

$idTiketStatus = (int) $tiket['id'];

$statusTujuanTiket = match ($tiket['status']) {
    'Baru' => 'Diproses',
    'Diproses' => 'Selesai',
    default => null,
};

$labelTindakanTiket = $tiket['status'] === 'Baru'
    ? 'Mulai proses'
    : 'Tandai selesai';

$solusiLama = $_SESSION['input_solusi_tiket'][$idTiketStatus] ?? '';

if (!is_string($solusiLama)) {
    $solusiLama = '';
}

// Input sementara digunakan untuk satu kali tampilan.
unset($_SESSION['input_solusi_tiket'][$idTiketStatus]);
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
            value="<?php echo $idTiketStatus; ?>">

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

        <?php require __DIR__ . '/parameter-filter-tiket.php'; ?>

        <?php if ($tiket['status'] === 'Diproses'): ?>
            <div class="form-group">
                <label for="solusi-<?php echo $idTiketStatus; ?>">
                    Solusi penyelesaian
                </label>

                <textarea
                    id="solusi-<?php echo $idTiketStatus; ?>"
                    name="solusi"
                    rows="4"
                    maxlength="5000"
                    aria-describedby="petunjuk-solusi-<?php echo $idTiketStatus; ?>"
                    placeholder="Jelaskan tindakan perbaikan dan hasil pengujiannya."
                    required><?php
                    echo htmlspecialchars(
                        $solusiLama,
                        ENT_QUOTES | ENT_SUBSTITUTE,
                        'UTF-8'
                    );
                    ?></textarea>

                <small id="petunjuk-solusi-<?php echo $idTiketStatus; ?>">
                    Wajib diisi sebelum tiket diselesaikan.
                    Maksimal 5.000 karakter.
                    Jangan sertakan password atau informasi rahasia.
                </small>
            </div>
        <?php endif; ?>

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