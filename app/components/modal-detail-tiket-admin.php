<?php

/** @var array $tiket */
?>

<dialog
    class="ticket-detail-dialog"
    id="detail-admin-<?php echo (int) $tiket['id']; ?>"
    aria-labelledby="judul-detail-admin-<?php echo (int) $tiket['id']; ?>">
    <div class="ticket-dialog-header">
        <div>
            <p class="ticket-dialog-eyebrow">Detail tiket</p>

            <h2 id="judul-detail-admin-<?php echo (int) $tiket['id']; ?>">
                <?php
                echo htmlspecialchars(
                    $tiket['nomor_tiket'] ?? ('Tiket #' . $tiket['id']),
                    ENT_QUOTES,
                    'UTF-8'
                );
                ?>
            </h2>
        </div>

        <button
            type="button"
            class="ticket-dialog-close"
            data-close-ticket-dialog
            aria-label="Tutup detail tiket"
            autofocus>
            <span class="remix-icon remix-icon--close" aria-hidden="true"></span>
        </button>
    </div>

    <div class="ticket-dialog-body">
        <dl class="ticket-dialog-meta">
            <div>
                <dt>Pelapor</dt>
                <dd>
                    <?php
                    echo htmlspecialchars(
                        $tiket['nama_pengguna'],
                        ENT_QUOTES,
                        'UTF-8'
                    );
                    ?>
                </dd>
            </div>

            <div>
                <dt>Departemen saat pelaporan</dt>
                <dd>
                    <?php
                    echo htmlspecialchars(
                        $tiket['departemen_pelapor'] ?? 'Belum tercatat',
                        ENT_QUOTES,
                        'UTF-8'
                    );
                    ?>
                </dd>
            </div>

            <div>
                <dt>Email akun</dt>
                <dd>
                    <?php
                    echo htmlspecialchars(
                        $tiket['email_pemilik'] ?: 'Tidak tersedia',
                        ENT_QUOTES,
                        'UTF-8'
                    );
                    ?>
                </dd>
            </div>

            <div>
                <dt>Tanggal dibuat</dt>
                <dd>
                    <?php
                    echo htmlspecialchars(
                        formatTanggalTiket($tiket['created_at'], true),
                        ENT_QUOTES,
                        'UTF-8'
                    );
                    ?>
                </dd>
            </div>

            <?php if ($tiket['status'] === 'Selesai'): ?>
                <div>
                    <dt>Tanggal selesai</dt>
                    <dd>
                        <?php
                        echo htmlspecialchars(
                            formatTanggalTiket(
                                $tiket['completed_at'] ?? null,
                                true
                            ),
                            ENT_QUOTES,
                            'UTF-8'
                        );
                        ?>
                    </dd>
                </div>
            <?php endif; ?>

            <div>
                <dt>Status</dt>
                <dd>
                    <?php require __DIR__ . '/badge-status-tiket.php'; ?>
                </dd>
            </div>

            <div>
                <dt>Urgensi pilihan user</dt>
                <dd>
                    <?php
                    echo htmlspecialchars(
                        $tiket['urgensi'] ?? 'Belum tercatat',
                        ENT_QUOTES,
                        'UTF-8'
                    );
                    ?>
                </dd>
            </div>

            <div>
                <dt>Prioritas penanganan</dt>
                <dd>
                    <?php require __DIR__ . '/badge-prioritas-tiket.php'; ?>
                </dd>
            </div>
        </dl>

        <section class="ticket-dialog-complaint">
            <h3>Keluhan</h3>

            <p><?php
                echo htmlspecialchars(
                    $tiket['keluhan'],
                    ENT_QUOTES,
                    'UTF-8'
                );
                ?></p>
        </section>

        <?php if ($tiket['status'] === 'Selesai'): ?>
            <?php require __DIR__ . '/solusi-tiket.php'; ?>
        <?php else: ?>
            <section class="ticket-dialog-handling">
                <h3>
                    <?php
                    echo $tiket['status'] === 'Diproses'
                        ? 'Selesaikan tiket'
                        : 'Penanganan tiket';
                    ?>
                </h3>

                <?php require __DIR__ . '/form-status.php'; ?>
            </section>
        <?php endif; ?>

        <?php if (in_array($tiket['status'], ['Baru', 'Diproses'], true)): ?>
            <section class="ticket-dialog-handling">
                <h3>Ubah prioritas</h3>

                <p class="form-description">
                    Sesuaikan prioritas berdasarkan dampak kendala.
                    Urgensi pilihan user tetap tersimpan.
                </p>

                <?php require __DIR__ . '/form-prioritas-tiket.php'; ?>
            </section>
        <?php endif; ?>

        <?php require __DIR__ . '/riwayat-prioritas-tiket.php'; ?>
    </div>

    <div class="ticket-dialog-footer">
        <button
            type="button"
            class="ticket-detail-button"
            data-close-ticket-dialog>
            Tutup
        </button>
    </div>
</dialog>