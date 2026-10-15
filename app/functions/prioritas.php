<?php

function ubahPrioritasTiket(
    PDO $pdo,
    int $tiketId,
    int $adminId,
    string $prioritasBaru,
    string $alasan
): string {
    $pilihanPrioritas = ['Rendah', 'Sedang', 'Tinggi'];
    $alasan = trim($alasan);
    $panjangAlasan = preg_match_all('/./us', $alasan);

    if (
        $tiketId < 1
        || $adminId < 1
        || !in_array($prioritasBaru, $pilihanPrioritas, true)
        || $alasan === ''
        || $panjangAlasan === false
        || $panjangAlasan > 1000
    ) {
        return 'prioritas-tidak-valid';
    }

    $pdo->beginTransaction();

    try {
        // Kunci tiket selama perubahan berlangsung.
        $statement = $pdo->prepare(
            'SELECT status, prioritas
             FROM tickets
             WHERE id = :id
             FOR UPDATE'
        );

        $statement->execute(['id' => $tiketId]);
        $tiket = $statement->fetch(PDO::FETCH_ASSOC);

        if ($tiket === false) {
            $pdo->rollBack();
            return 'tiket-tidak-ditemukan';
        }

        if (!in_array($tiket['status'], ['Baru', 'Diproses'], true)) {
            $pdo->rollBack();
            return 'prioritas-tiket-selesai';
        }

        if ($tiket['prioritas'] === $prioritasBaru) {
            $pdo->rollBack();
            return 'prioritas-tidak-berubah';
        }

        // Pastikan pencatat perubahan adalah admin aktif.
        $cekAdmin = $pdo->prepare(
            'SELECT id
             FROM users
             WHERE id = :id
               AND role = :role
               AND is_active = 1'
        );

        $cekAdmin->execute([
            'id' => $adminId,
            'role' => 'admin',
        ]);

        if ($cekAdmin->fetchColumn() === false) {
            $pdo->rollBack();
            return 'prioritas-admin-tidak-valid';
        }

        // Perbarui prioritas tanpa mengubah urgensi pilihan user.
        $ubah = $pdo->prepare(
            'UPDATE tickets
             SET prioritas = :prioritas
             WHERE id = :id'
        );

        $ubah->execute([
            'prioritas' => $prioritasBaru,
            'id' => $tiketId,
        ]);

        // Simpan catatan perubahan.
        $riwayat = $pdo->prepare(
            'INSERT INTO ticket_priority_history
                (
                    ticket_id,
                    admin_id,
                    prioritas_lama,
                    prioritas_baru,
                    alasan
                )
             VALUES
                (
                    :ticket_id,
                    :admin_id,
                    :prioritas_lama,
                    :prioritas_baru,
                    :alasan
                )'
        );

        $riwayat->execute([
            'ticket_id' => $tiketId,
            'admin_id' => $adminId,
            'prioritas_lama' => $tiket['prioritas'],
            'prioritas_baru' => $prioritasBaru,
            'alasan' => $alasan,
        ]);

        $pdo->commit();

        return 'prioritas-berhasil';
    } catch (Throwable $error) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        throw $error;
    }
}