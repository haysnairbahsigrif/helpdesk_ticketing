<?php

/** @var array $daftarTiket */

$tampilkanTanggalSelesai = ($tampilan ?? 'aktif') === 'riwayat';

$labelTanggal = $tampilkanTanggalSelesai
    ? 'Tanggal selesai'
    : 'Tanggal dibuat';
?>

<div class="ticket-table-wrapper">
    <table class="ticket-user-table ticket-admin-table">
        <caption class="ticket-table-caption">
            Daftar tiket seluruh pengguna
        </caption>

        <thead>
            <tr>
                <th scope="col">Nomor tiket</th>
                <th scope="col">Pelapor</th>
                <th scope="col">Prioritas</th>
                <th scope="col">Status</th>
                <th scope="col">
                    <?php echo htmlspecialchars($labelTanggal, ENT_QUOTES, 'UTF-8'); ?>
                </th>
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

                    <td data-label="Pelapor">
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
                                    $tiket['departemen_pelapor'] ?? 'Departemen belum tercatat',
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

                    <td data-label="<?php
                                    echo htmlspecialchars($labelTanggal, ENT_QUOTES, 'UTF-8');
                                    ?>">
                        <?php
                        $tanggalTiket = $tampilkanTanggalSelesai
                            ? ($tiket['completed_at'] ?? null)
                            : ($tiket['created_at'] ?? null);

                        echo htmlspecialchars(
                            explode(',', formatTanggalTiket($tanggalTiket), 2)[0],
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
                            Detail
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