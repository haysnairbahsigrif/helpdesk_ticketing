<?php

/** @var string $pencarian */
/** @var string $status */
/** @var string $tampilan */

$urlResetTiketUser = '/user/tiket.php?' . http_build_query([
    'view' => $tampilan,
]);

$pilihanStatusUser = $tampilan === 'aktif'
    ? ['Baru', 'Diproses']
    : ['Baru', 'Diproses', 'Selesai'];
?>

<form class="user-ticket-filter" action="/user/tiket.php" method="get">
    <input
        type="hidden"
        name="view"
        value="<?php
                echo htmlspecialchars($tampilan, ENT_QUOTES, 'UTF-8');
                ?>">

    <div class="form-group">
        <label for="cari-keluhan-user">
            Cari nomor tiket atau keluhan
        </label>

        <input
            type="search"
            id="cari-keluhan-user"
            name="q"
            maxlength="100"
            placeholder="Nomor tiket atau contoh: printer"
            value="<?php
                    echo htmlspecialchars($pencarian, ENT_QUOTES, 'UTF-8');
                    ?>">
    </div>

    <?php if ($tampilan !== 'riwayat'): ?>
        <div class="form-group">
            <label for="filter-status-user">Status tiket</label>

            <select id="filter-status-user" name="status">
                <option
                    value=""
                    <?php echo $status === '' ? 'selected' : ''; ?>>
                    <?php
                    echo $tampilan === 'aktif'
                        ? 'Semua status aktif'
                        : 'Semua status';
                    ?>
                </option>

                <?php foreach ($pilihanStatusUser as $pilihan): ?>
                    <option
                        value="<?php
                                echo htmlspecialchars($pilihan, ENT_QUOTES, 'UTF-8');
                                ?>"
                        <?php echo $status === $pilihan ? 'selected' : ''; ?>>
                        <?php
                        echo htmlspecialchars($pilihan, ENT_QUOTES, 'UTF-8');
                        ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    <?php endif; ?>

    <div class="user-ticket-filter-actions">
        <button type="submit" class="button-primary">
            Terapkan
        </button>

        <a href="<?php
                    echo htmlspecialchars($urlResetTiketUser, ENT_QUOTES, 'UTF-8');
                    ?>">
            Reset filter
        </a>
    </div>
</form>