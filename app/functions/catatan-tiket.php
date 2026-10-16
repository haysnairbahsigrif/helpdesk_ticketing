<?php

function tambahCatatanTiket(
    PDO $pdo,
    int $tiketId,
    int $adminId,
    string $catatan
): bool {
    $catatan = trim($catatan);
    $panjang = preg_match_all('/./us', $catatan);

    if (
        $tiketId < 1
        || $adminId < 1
        || $catatan === ''
        || $panjang === false
        || $panjang > 5000
    ) {
        return false;
    }

    $pdo->beginTransaction();

    try {
        // Kunci tiket selama pemeriksaan dan penyimpanan.
        // Tiket tidak bisa diselesaikan bersamaan dengan proses ini.
        $cek = $pdo->prepare(
            'SELECT status FROM tickets WHERE id = :id FOR UPDATE'
        );
        $cek->execute(['id' => $tiketId]);

        if ($cek->fetchColumn() !== 'Diproses') {
            $pdo->rollBack();
            return false;
        }

        $statement = $pdo->prepare(
            'INSERT INTO ticket_notes (ticket_id, admin_id, catatan)
             SELECT :ticket_id, users.id, :catatan
             FROM users
             WHERE users.id = :admin_id
               AND users.role = \'admin\'
               AND users.is_active = 1'
        );

        $statement->execute([
            'ticket_id' => $tiketId,
            'catatan' => $catatan,
            'admin_id' => $adminId,
        ]);

        $berhasil = $statement->rowCount() === 1;

        $pdo->commit();

        return $berhasil;
    } catch (Throwable $error) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        throw $error;
    }
}

function ambilCatatanTiketAdmin(PDO $pdo, array $daftarIdTiket): array
{
    $daftarIdTiket = array_values(array_unique(array_filter(
        array_map('intval', $daftarIdTiket),
        static fn(int $id): bool => $id > 0
    )));

    if ($daftarIdTiket === []) {
        return [];
    }

    $placeholder = implode(
        ', ',
        array_fill(0, count($daftarIdTiket), '?')
    );

    $statement = $pdo->prepare(
        'SELECT
            ticket_notes.id,
            ticket_notes.ticket_id,
            ticket_notes.catatan,
            ticket_notes.created_at,
            users.nama AS nama_admin
         FROM ticket_notes
         LEFT JOIN users ON ticket_notes.admin_id = users.id
         WHERE ticket_notes.ticket_id IN (' . $placeholder . ')
         ORDER BY ticket_notes.created_at DESC, ticket_notes.id DESC'
    );

    foreach ($daftarIdTiket as $posisi => $id) {
        $statement->bindValue($posisi + 1, $id, PDO::PARAM_INT);
    }

    $statement->execute();

    $hasil = [];

    foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $catatan) {
        $hasil[(int) $catatan['ticket_id']][] = $catatan;
    }

    return $hasil;
}