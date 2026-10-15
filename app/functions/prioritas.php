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

function ambilRiwayatPrioritasAdmin(
    PDO $pdo,
    array $daftarIdTiket
): array {
    if ($daftarIdTiket === []) {
        return [];
    }

    foreach ($daftarIdTiket as $idTiket) {
        if (!is_int($idTiket) || $idTiket < 1) {
            throw new InvalidArgumentException(
                'ID tiket harus berupa bilangan bulat positif.'
            );
        }
    }

    $daftarIdTiket = array_values(array_unique($daftarIdTiket));

    // Buat satu placeholder untuk setiap ID tiket.
    $placeholder = implode(
        ', ',
        array_fill(0, count($daftarIdTiket), '?')
    );

    $statement = $pdo->prepare(
        'SELECT
            riwayat.id,
            riwayat.ticket_id,
            riwayat.prioritas_lama,
            riwayat.prioritas_baru,
            riwayat.alasan,
            riwayat.created_at,
            users.nama AS nama_admin
         FROM ticket_priority_history AS riwayat
         LEFT JOIN users ON riwayat.admin_id = users.id
         WHERE riwayat.ticket_id IN (' . $placeholder . ')
         ORDER BY riwayat.id DESC'
    );

    foreach ($daftarIdTiket as $posisi => $idTiket) {
        $statement->bindValue(
            $posisi + 1,
            $idTiket,
            PDO::PARAM_INT
        );
    }

    $statement->execute();

    $riwayatPerTiket = [];

    foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $riwayat) {
        $idTiket = (int) $riwayat['ticket_id'];
        $riwayatPerTiket[$idTiket][] = $riwayat;
    }

    return $riwayatPerTiket;
}