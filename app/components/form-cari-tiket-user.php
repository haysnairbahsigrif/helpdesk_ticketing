<?php

/** @var string $pencarian */
/** @var string $status */
?>

<form class="user-ticket-filter" action="/user/" method="get">
    <div class="form-group">
        <label for="cari-keluhan-user">Cari keluhan</label>

        <input
            type="search"
            id="cari-keluhan-user"
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
        <label for="filter-status-user">Status tiket</label>

        <select id="filter-status-user" name="status">
            <option value="">Semua status</option>

            <?php foreach (['Baru', 'Diproses', 'Selesai'] as $pilihan): ?>
                <option
                    value="<?php echo $pilihan; ?>"
                    <?php if ($status === $pilihan): ?>
                        selected
                    <?php endif; ?>>
                    <?php echo $pilihan; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="user-ticket-filter-actions">
        <button type="submit" class="button-primary">
            Terapkan
        </button>

        <a href="/user/">Tampilkan semua</a>
    </div>
</form>