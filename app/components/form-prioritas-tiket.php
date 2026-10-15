<?php

/** @var array $tiket */

$idTiketPrioritas = (int) $tiket['id'];
$prioritasSekarang = $tiket['prioritas'] ?? '';
?>

<?php if (in_array($tiket['status'], ['Baru', 'Diproses'], true)): ?>
    <form action="/actions/ubah-prioritas.php" method="post">
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
            value="<?php echo $idTiketPrioritas; ?>">

        <input
            type="hidden"
            name="q"
            value="<?php
            echo htmlspecialchars($pencarian ?? '', ENT_QUOTES, 'UTF-8');
            ?>">

        <input
            type="hidden"
            name="filter_status"
            value="<?php
            echo htmlspecialchars($status ?? '', ENT_QUOTES, 'UTF-8');
            ?>">

        <input
            type="hidden"
            name="view"
            value="<?php
            echo htmlspecialchars($tampilan ?? 'aktif', ENT_QUOTES, 'UTF-8');
            ?>">

        <input
            type="hidden"
            name="page"
            value="<?php echo (int) ($halaman ?? 1); ?>">

        <div class="form-group">
            <label for="prioritas-<?php echo $idTiketPrioritas; ?>">
                Prioritas penanganan
            </label>

            <select
                id="prioritas-<?php echo $idTiketPrioritas; ?>"
                name="prioritas"
                required>
                <option
                    value=""
                    disabled
                    <?php echo $prioritasSekarang === '' ? 'selected' : ''; ?>>
                    Pilih prioritas
                </option>

                <?php foreach (['Rendah', 'Sedang', 'Tinggi'] as $pilihanPrioritas): ?>
                    <option
                        value="<?php echo $pilihanPrioritas; ?>"
                        <?php
                        echo $prioritasSekarang === $pilihanPrioritas
                            ? 'selected'
                            : '';
                        ?>>
                        <?php echo $pilihanPrioritas; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="alasan-prioritas-<?php echo $idTiketPrioritas; ?>">
                Alasan perubahan
            </label>

            <textarea
                id="alasan-prioritas-<?php echo $idTiketPrioritas; ?>"
                name="alasan"
                rows="3"
                maxlength="1000"
                aria-describedby="petunjuk-prioritas-<?php echo $idTiketPrioritas; ?>"
                placeholder="Contoh: kendala menghentikan pekerjaan seluruh departemen."
                required></textarea>

            <small id="petunjuk-prioritas-<?php echo $idTiketPrioritas; ?>">
                Maksimal 1.000 karakter. Alasan akan dicatat dalam riwayat.
            </small>
        </div>

        <button type="submit" class="button-primary">
            Simpan prioritas
        </button>
    </form>
<?php else: ?>
    <p>Prioritas tiket yang sudah selesai tidak dapat diubah.</p>
<?php endif; ?>