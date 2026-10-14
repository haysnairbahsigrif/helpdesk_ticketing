<?php

/** @var array $daftarTiket */
?>

<div class="ticket-table-wrapper">
    <table class="ticket-user-table">
        <caption class="ticket-table-caption">
            Daftar tiket seluruh pengguna
        </caption>

        <thead>
            <tr>
                <th scope="col">Nomor tiket</th>
                <th scope="col">Keluhan</th>
                <th scope="col">Pelapor / Departemen</th>
                <th scope="col">Prioritas</th>
                <th scope="col">Status</th>
                <th scope="col">Tanggal dibuat</th>
                <th scope="col">Detail</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($daftarTiket as $tiket): ?>
                <tr data-ticket-row>
                    <th scope="row" data-label="Nomor tiket">
                        <?php
                        echo htmlspecialchars(
                            $tiket['nomor_tiket'] ?? ('#' . $tiket['id']),
                            ENT_QUOTES,
                            'UTF-8'
                        );
                        ?>
                    </th>

                    <td data-label="Keluhan">
                        <p class="ticket-table-complaint"><?php
                                                            echo htmlspecialchars(
                                                                $tiket['keluhan'],
                                                                ENT_QUOTES,
                                                                'UTF-8'
                                                            );
                                                            ?></p>
                    </td>

                    <td data-label="Pelapor / Departemen">
                        <div class="ticket-reporter">
                            <strong>
                                <?php
                                echo htmlspecialchars(
                                    $tiket['nama_pengguna'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>
                            </strong>

                            <span>
                                <?php
                                echo htmlspecialchars(
                                    $tiket['Departemen_pelapor'] ?? 'Departemen belum tercatat',
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>
                            </span>
                        </div>
                    </td>

                    <td data-label="Prioritas">
                        <?php require __DIR__ . '/badge-prioritas-tiket.php'; ?>
                    </td>

                    <td data-label="Status">
                        <?php require __DIR__ . '/badge-status-tiket.php'; ?>
                    </td>

                    <td data-label="Tanggal dibuat">
                        <?php
                        echo htmlspecialchars(
                            formatTanggalTiket($tiket['created_at']),
                            ENT_QUOTES,
                            'UTF-8'
                        );
                        ?>
                    </td>

                    <td data-label="Detail">
                        <button
                            type="button"
                            class="ticket-detail-button"
                            data-ticket-dialog="detail-admin-<?php echo (int) $tiket['id']; ?>"
                            aria-haspopup="dialog"
                            aria-controls="detail-admin-<?php echo (int) $tiket['id']; ?>"
                            aria-label="<?php
                                        echo htmlspecialchars(
                                            'Lihat detail tiket '
                                                . ($tiket['nomor_tiket'] ?? $tiket['id']),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        );
                                        ?>"
                            hidden>
                            Lihat detail
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php foreach ($daftarTiket as $tiket): ?>
    <?php require __DIR__ . '/modal-detail-tiket-admin.php'; ?>
<?php endforeach; ?>