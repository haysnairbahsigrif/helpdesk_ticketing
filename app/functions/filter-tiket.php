<?php

function normalisasiPilihanFilter(
    mixed $masukan,
    array $pilihanDiizinkan
): array {
    // Parameter yang tidak dipilih menghasilkan daftar kosong.
    if ($masukan === '') {
        return [];
    }

    // Tetap menerima tautan lama, misalnya ?status=Baru.
    if (is_string($masukan)) {
        $masukan = [$masukan];
    }

    if (!is_array($masukan)) {
        throw new InvalidArgumentException(
            'Bentuk parameter filter tidak valid.'
        );
    }

    if (count($masukan) > count($pilihanDiizinkan)) {
        throw new InvalidArgumentException(
            'Jumlah pilihan filter tidak valid.'
        );
    }

    foreach ($masukan as $nilai) {
        if (
            !is_string($nilai)
            || !in_array($nilai, $pilihanDiizinkan, true)
        ) {
            throw new InvalidArgumentException(
                'Pilihan filter tidak valid.'
            );
        }
    }

    // Hilangkan pilihan yang berulang dan rapikan indeks array.
    return array_values(array_unique($masukan));
}