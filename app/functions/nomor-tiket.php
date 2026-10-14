<?php

function buatNomorTiket(?DateTimeImmutable $waktu = null): string
{
    $waktu ??= new DateTimeImmutable(
        'now',
        new DateTimeZone('Asia/Jakarta')
    );

    $karakter = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    $kodeAcak = '';
    $indeksTerakhir = strlen($karakter) - 1;

    for ($i = 0; $i < 6; $i++) {
        $kodeAcak .= $karakter[random_int(0, $indeksTerakhir)];
    }

    return $waktu->format('ymd') . $kodeAcak;
}