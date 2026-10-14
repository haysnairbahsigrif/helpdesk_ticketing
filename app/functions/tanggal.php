<?php

function formatTanggalTiket(
    ?string $nilai,
    bool $bulanLengkap = false
): string {
    if ($nilai === null || $nilai === '') {
        return 'Belum tercatat';
    }

    $tanggal = DateTimeImmutable::createFromFormat(
        '!Y-m-d H:i:s',
        $nilai
    );

    if (
        $tanggal === false
        || $tanggal->format('Y-m-d H:i:s') !== $nilai
    ) {
        return 'Tanggal tidak valid';
    }

    $namaBulan = $bulanLengkap
        ? [
            1 => 'Januari', 'Februari', 'Maret', 'April',
            'Mei', 'Juni', 'Juli', 'Agustus',
            'September', 'Oktober', 'November', 'Desember',
        ]
        : [
            1 => 'Jan', 'Feb', 'Mar', 'Apr',
            'Mei', 'Jun', 'Jul', 'Agu',
            'Sep', 'Okt', 'Nov', 'Des',
        ];

    return $tanggal->format('j')
        . ' '
        . $namaBulan[(int) $tanggal->format('n')]
        . ' '
        . $tanggal->format('Y, H:i');
}