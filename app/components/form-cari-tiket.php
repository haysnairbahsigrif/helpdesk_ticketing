<?php

/** @var string $pencarian */
/** @var string $status */
/** @var string $tampilan */

$adaFilterStatus = $tampilan !== 'riwayat' && $status !== '';

$urlResetFilter = '/admin/tiket.php?' . http_build_query([
    'view' => $tampilan,
]);

// Hapus status saja, tetapi pertahankan pencarian.
$urlHapusStatus = '/admin/tiket.php?' . http_build_query([
    'view' => $tampilan,
    'q' => $pencarian,
]);

$pilihanStatus = $tampilan === 'aktif'
    ? ['Baru', 'Diproses']
    : ['Baru', 'Diproses', 'Selesai'];
?>

<section class="panel ticket-filter-panel">
    <h2>Cari tiket</h2>

    <form
        class="ticket-filter-form"
        action="/admin/tiket.php"
        method="get"
        data-ticket-filter>

        <input
            type="hidden"
            name="view"
            value="<?php
            echo htmlspecialchars($tampilan, ENT_QUOTES, 'UTF-8');
            ?>">

        <div class="ticket-search-toolbar">
            <div class="ticket-search-field">
                <label class="ticket-filter-sr-only" for="pencarian-tiket">
                    Cari nomor tiket, nama pelapor, atau keluhan
                </label>

                <button
                    type="submit"
                    class="ticket-search-submit"
                    aria-label="Cari tiket"
                    title="Cari tiket">
                    <svg
                        width="22"
                        height="22"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        aria-hidden="true">
                        <circle cx="10.5" cy="10.5" r="6.5"></circle>
                        <path d="m16 16 5 5"></path>
                    </svg>
                </button>

                <input
                    type="search"
                    id="pencarian-tiket"
                    name="q"
                    maxlength="100"
                    placeholder="Nomor tiket, nama pelapor, atau keluhan"
                    value="<?php
                    echo htmlspecialchars($pencarian, ENT_QUOTES, 'UTF-8');
                    ?>">
            </div>

            <?php if ($tampilan !== 'riwayat'): ?>
                <button
                    type="button"
                    class="ticket-filter-toggle"
                    data-filter-toggle
                    aria-controls="panel-filter-tiket"
                    aria-expanded="true"
                    aria-label="Filter status tiket"
                    hidden>
                    <svg
                        width="22"
                        height="22"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        aria-hidden="true">
                        <path d="M3 6h18M3 12h18M3 18h18"></path>
                        <circle cx="8" cy="6" r="2" fill="currentColor"></circle>
                        <circle cx="16" cy="12" r="2" fill="currentColor"></circle>
                        <circle cx="10" cy="18" r="2" fill="currentColor"></circle>
                    </svg>

                    <span class="ticket-filter-toggle-text">Filter</span>

                    <?php if ($adaFilterStatus): ?>
                        <span class="ticket-filter-count">1</span>
                    <?php endif; ?>
                </button>
            <?php endif; ?>
        </div>

        <?php if ($tampilan !== 'riwayat'): ?>
            <div class="ticket-filter-options" id="panel-filter-tiket">
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

                        <?php foreach ($pilihanStatus as $pilihan): ?>
                            <option
                                value="<?php echo $pilihan; ?>"
                                <?php echo $status === $pilihan ? 'selected' : ''; ?>>
                                <?php echo $pilihan; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="ticket-filter-actions">
                    <button type="submit" class="button-primary">
                        Terapkan
                    </button>

                    <a href="<?php
                    echo htmlspecialchars($urlResetFilter, ENT_QUOTES, 'UTF-8');
                    ?>">
                        Reset semua
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($adaFilterStatus): ?>
            <div class="ticket-active-filters">
                <a
                    class="ticket-filter-chip"
                    href="<?php
                    echo htmlspecialchars($urlHapusStatus, ENT_QUOTES, 'UTF-8');
                    ?>"
                    aria-label="<?php
                    echo htmlspecialchars(
                        'Hapus filter status ' . $status,
                        ENT_QUOTES,
                        'UTF-8'
                    );
                    ?>">
                    Status:
                    <?php echo htmlspecialchars($status, ENT_QUOTES, 'UTF-8'); ?>
                    <span aria-hidden="true">×</span>
                </a>
            </div>
        <?php endif; ?>

        <?php if ($tampilan === 'riwayat' && $pencarian !== ''): ?>
            <a href="<?php
            echo htmlspecialchars($urlResetFilter, ENT_QUOTES, 'UTF-8');
            ?>">
                Hapus pencarian
            </a>
        <?php endif; ?>
    </form>
</section>