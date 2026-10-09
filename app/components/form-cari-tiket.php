<?php

/** @var string $pencarian */
/** @var string $status */

$pilihanStatus = ['Baru', 'Diproses', 'Selesai'];
?>

<section class="panel ticket-filter-panel">
    <h2>Cari tiket</h2>

    <form
        class="ticket-filter-form"
        action="/admin/tiket.php"
        method="get">

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
                echo htmlspecialchars(
                    $pencarian,
                    ENT_QUOTES,
                    'UTF-8'
                );
                ?>">
        </div>

        <div class="form-group">
            <label for="filter-status-tiket">Status tiket</label>

            <select id="filter-status-tiket" name="status">
                <option value="">Semua status</option>

                <?php foreach ($pilihanStatus as $pilihan): ?>
                    <option
                        value="<?php
                        echo htmlspecialchars(
                            $pilihan,
                            ENT_QUOTES,
                            'UTF-8'
                        );
                        ?>"
                        <?php if ($status === $pilihan): ?>
                            selected
                        <?php endif; ?>>
                        <?php
                        echo htmlspecialchars(
                            $pilihan,
                            ENT_QUOTES,
                            'UTF-8'
                        );
                        ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="ticket-filter-actions">
            <button type="submit" class="button-primary">
                Terapkan
            </button>

            <a href="/admin/tiket.php">Tampilkan semua</a>
        </div>
    </form>
</section>