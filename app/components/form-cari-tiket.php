<?php

/** @var string $pencarian */
/** @var string $status */
/** @var string $tampilan */

$urlResetFilter = '/admin/tiket.php?' . http_build_query([
    'view' => $tampilan,
]);
?>

<section class="panel ticket-filter-panel">
    <h2>Cari tiket</h2>

    <form
        class="ticket-filter-form"
        action="/admin/tiket.php"
        method="get">

        <input
            type="hidden"
            name="view"
            value="<?php
                    echo htmlspecialchars($tampilan, ENT_QUOTES, 'UTF-8');
                    ?>">

        <div class="form-group">
            <label for="pencarian-tiket">
                Nama pengguna atau keluhan
            </label>

            <input
                type="search"
                id="pencarian-tiket"
                name="q"
                maxlength="100"
                placeholder="Contoh: printer"
                value="<?php
                        echo htmlspecialchars($pencarian, ENT_QUOTES, 'UTF-8');
                        ?>">
        </div>

        <?php if ($tampilan !== 'riwayat'): ?>
            <div class="form-group">
                <label for="filter-status-tiket">Status tiket</label>

                <select id="filter-status-tiket" name="status">
                    <option value="" <?php echo $status === '' ? 'selected' : ''; ?>>
                        <?php
                        echo $tampilan === 'aktif'
                            ? 'Semua status aktif'
                            : 'Semua status';
                        ?>
                    </option>

                    <?php
                    $pilihanStatus = $tampilan === 'aktif'
                        ? ['Baru', 'Diproses']
                        : ['Baru', 'Diproses', 'Selesai'];
                    ?>

                    <?php foreach ($pilihanStatus as $pilihan): ?>
                        <option
                            value="<?php echo htmlspecialchars($pilihan, ENT_QUOTES, 'UTF-8'); ?>"
                            <?php echo $status === $pilihan ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($pilihan, ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php endif; ?>

        <div class="ticket-filter-actions">
            <button type="submit" class="button-primary">
                Terapkan
            </button>

            <a href="<?php
                        echo htmlspecialchars($urlResetFilter, ENT_QUOTES, 'UTF-8');
                        ?>">
                Reset filter
            </a>
        </div>
    </form>
</section>