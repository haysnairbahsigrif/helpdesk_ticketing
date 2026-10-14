<?php

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('Script hanya boleh dijalankan melalui terminal.');
}

require __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/functions/nomor-tiket.php';

try {
    $pdo->beginTransaction();

    $statement = $pdo->query(
        'SELECT id, created_at
         FROM tickets
         WHERE nomor_tiket IS NULL
         ORDER BY id'
    );

    $tiketLama = $statement->fetchAll(PDO::FETCH_ASSOC);

    $update = $pdo->prepare(
        'UPDATE tickets
         SET nomor_tiket = :nomor_tiket,
             updated_at = updated_at
         WHERE id = :id
           AND nomor_tiket IS NULL'
    );

    $jumlahDiubah = 0;

    foreach ($tiketLama as $tiket) {
        $waktu = DateTimeImmutable::createFromFormat(
            '!Y-m-d H:i:s',
            $tiket['created_at'],
            new DateTimeZone('Asia/Jakarta')
        );

        if (
            $waktu === false
            || $waktu->format('Y-m-d H:i:s') !== $tiket['created_at']
        ) {
            throw new RuntimeException(
                'Tanggal tiket #' . $tiket['id'] . ' tidak valid.'
            );
        }

        for ($percobaan = 1; $percobaan <= 5; $percobaan++) {
            try {
                $update->execute([
                    'nomor_tiket' => buatNomorTiket($waktu),
                    'id' => (int) $tiket['id'],
                ]);

                $jumlahDiubah += $update->rowCount();
                break;
            } catch (PDOException $error) {
                $kodeMySQL = (int) ($error->errorInfo[1] ?? 0);
                $pesanMySQL = (string) ($error->errorInfo[2] ?? '');

                $nomorDuplikat = $kodeMySQL === 1062
                    && str_contains(
                        $pesanMySQL,
                        'uq_tickets_nomor_tiket'
                    );

                if (!$nomorDuplikat || $percobaan === 5) {
                    throw $error;
                }
            }
        }
    }

    $pdo->commit();

    echo "Selesai. {$jumlahDiubah} tiket mendapatkan nomor.\n";
} catch (Throwable $error) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    fwrite(
        STDERR,
        "Pengisian dibatalkan: " . $error->getMessage() . PHP_EOL
    );

    exit(1);
}