<?php

/** @var string $pencarian */
/** @var array<int, string> $status */
/** @var array<int, string> $filterPrioritas */

$kelompokFilterTiket = [
    'status' => [
        'label' => 'Status',
        'pilihan' => [
            'Baru' => 'Baru',
            'Diproses' => 'Diproses',
            'Selesai' => 'Selesai',
        ],
        'terpilih' => $status,
    ],
    'prioritas' => [
        'label' => 'Prioritas',
        'pilihan' => [
            'Rendah' => 'Rendah',
            'Sedang' => 'Sedang',
            'Tinggi' => 'Tinggi',
            'belum-ditentukan' => 'Belum ditentukan',
        ],
        'terpilih' => $filterPrioritas,
    ],
];

$buatUrlHapusFilter = static function (
    string $kelompok,
    string $nilai
) use ($pencarian, $status, $filterPrioritas): string {
    $parameter = [
        'q' => $pencarian,
        'status' => $status,
        'prioritas' => $filterPrioritas,
    ];

    $parameter[$kelompok] = array_values(
        array_diff($parameter[$kelompok], [$nilai])
    );

    return '/admin/tiket.php?' . http_build_query($parameter);
};
?>

<form
    class="ticket-toolbar-form"
    action="/admin/tiket.php"
    method="get"
    data-ticket-filter>
    <div class="ticket-toolbar">
        <div class="ticket-toolbar-search">
            <label class="ticket-toolbar-sr-only" for="pencarian-tiket">
                Cari nomor tiket, nama pelapor, atau keluhan
            </label>

            <span class="remix-icon remix-icon--search" aria-hidden="true"></span>

            <input
                type="search"
                id="pencarian-tiket"
                name="q"
                maxlength="100"
                placeholder="Nomor tiket, pelapor, atau keluhan"
                value="<?php
                        echo htmlspecialchars($pencarian, ENT_QUOTES, 'UTF-8');
                        ?>">
        </div>

        <?php foreach ($kelompokFilterTiket as $namaFilter => $kelompok): ?>
            <details class="ticket-dropdown" data-filter-dropdown>
                <summary>
                    <span>
                        <?php
                        echo htmlspecialchars(
                            $kelompok['label'],
                            ENT_QUOTES,
                            'UTF-8'
                        );
                        ?>

                        <span data-filter-count>
                            <?php
                            $jumlahPilihan = count($kelompok['terpilih']);
                            echo $jumlahPilihan > 0
                                ? '(' . $jumlahPilihan . ')'
                                : '';
                            ?>
                        </span>
                    </span>

                    <span class="ticket-dropdown-arrow" aria-hidden="true"></span>
                </summary>

                <div class="ticket-dropdown-panel">
                    <fieldset>
                        <legend class="ticket-toolbar-sr-only">
                            <?php
                            echo htmlspecialchars(
                                'Pilih ' . $kelompok['label'],
                                ENT_QUOTES,
                                'UTF-8'
                            );
                            ?>
                        </legend>

                        <?php foreach ($kelompok['pilihan'] as $nilai => $label): ?>
                            <label class="ticket-dropdown-choice">
                                <input
                                    type="checkbox"
                                    name="<?php echo $namaFilter; ?>[]"
                                    value="<?php
                                            echo htmlspecialchars($nilai, ENT_QUOTES, 'UTF-8');
                                            ?>"
                                    <?php
                                    echo in_array(
                                        $nilai,
                                        $kelompok['terpilih'],
                                        true
                                    ) ? 'checked' : '';
                                    ?>>

                                <span><?php
                                        echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8');
                                        ?></span>
                            </label>
                        <?php endforeach; ?>
                    </fieldset>
                </div>
            </details>
        <?php endforeach; ?>

        <button type="submit" class="button-primary ticket-toolbar-apply">
            Terapkan
        </button>

        <a class="ticket-toolbar-reset" href="/admin/tiket.php">
            Reset filter
        </a>
    </div>

    <?php if ($status !== [] || $filterPrioritas !== []): ?>
        <div class="ticket-toolbar-chips" aria-label="Filter yang diterapkan">
            <?php foreach ($kelompokFilterTiket as $namaFilter => $kelompok): ?>
                <?php foreach ($kelompok['terpilih'] as $nilai): ?>
                    <?php
                    $labelChip = $kelompok['label'] . ': '
                        . $kelompok['pilihan'][$nilai];
                    ?>

                    <a
                        class="ticket-toolbar-chip"
                        href="<?php
                                echo htmlspecialchars(
                                    $buatUrlHapusFilter($namaFilter, $nilai),
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>"
                        aria-label="<?php
                                    echo htmlspecialchars(
                                        'Hapus filter ' . $labelChip,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    );
                                    ?>">
                        <?php echo htmlspecialchars($labelChip, ENT_QUOTES, 'UTF-8'); ?>
                        <span class="remix-icon remix-icon--close" aria-hidden="true"></span>
                    </a>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</form>