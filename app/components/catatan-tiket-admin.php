<?php

/** @var array $tiket */
/** @var array $catatanPerTiket */
/** @var bool $gagalMemuatCatatan */

$idTiketCatatan = (int) $tiket['id'];
$daftarCatatanTiket = $catatanPerTiket[$idTiketCatatan] ?? [];
$catatanLama = $_SESSION['input_catatan_tiket'][$idTiketCatatan] ?? '';

if (!is_string($catatanLama)) {
    $catatanLama = '';
}

unset($_SESSION['input_catatan_tiket'][$idTiketCatatan]);
?>

<section class="ticket-dialog-handling ticket-notes">
    <h3>Catatan penanganan</h3>
    <p class="ticket-notes-hint">Internal — hanya terlihat oleh admin.</p>

    <?php if ($gagalMemuatCatatan): ?>
        <p role="alert">
            Catatan belum dapat dimuat. Silakan muat ulang halaman.
        </p>
    <?php elseif ($daftarCatatanTiket === []): ?>
        <p>Belum ada catatan penanganan.</p>
    <?php else: ?>
        <ol class="ticket-notes-list">
            <?php foreach ($daftarCatatanTiket as $catatanTiket): ?>
                <li class="ticket-note">
                    <p class="ticket-note-text"><?php
                        echo htmlspecialchars(
                            $catatanTiket['catatan'],
                            ENT_QUOTES | ENT_SUBSTITUTE,
                            'UTF-8'
                        );
                    ?></p>

                    <p class="ticket-note-meta">
                        <?php
                        echo htmlspecialchars(
                            $catatanTiket['nama_admin']
                                ?? 'Admin tidak tersedia',
                            ENT_QUOTES | ENT_SUBSTITUTE,
                            'UTF-8'
                        );
                        ?>
                        ·
                        <?php
                        echo htmlspecialchars(
                            formatTanggalTiket(
                                $catatanTiket['created_at'],
                                true
                            ),
                            ENT_QUOTES,
                            'UTF-8'
                        );
                        ?>
                    </p>
                </li>
            <?php endforeach; ?>
        </ol>
    <?php endif; ?>

    <?php if ($tiket['status'] === 'Diproses'): ?>
        <form
            class="ticket-note-form"
            action="/actions/tambah-catatan-tiket.php"
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
                value="<?php echo $idTiketCatatan; ?>">

            <?php require __DIR__ . '/parameter-filter-tiket.php'; ?>

            <div class="form-group">
                <label for="catatan-<?php echo $idTiketCatatan; ?>">
                    Tambah catatan
                </label>

                <textarea
                    id="catatan-<?php echo $idTiketCatatan; ?>"
                    name="catatan"
                    rows="3"
                    maxlength="5000"
                    placeholder="Tulis pemeriksaan atau tindakan yang dilakukan."
                    aria-describedby="petunjuk-catatan-<?php echo $idTiketCatatan; ?>"
                    required><?php
                    echo htmlspecialchars(
                        $catatanLama,
                        ENT_QUOTES | ENT_SUBSTITUTE,
                        'UTF-8'
                    );
                ?></textarea>

                <small id="petunjuk-catatan-<?php echo $idTiketCatatan; ?>">
                    Maksimal 5.000 karakter.
                </small>
            </div>

            <button type="submit" class="button-primary">
                Simpan catatan
            </button>
        </form>
    <?php endif; ?>
</section>