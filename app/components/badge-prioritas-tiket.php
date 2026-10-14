<?php

/** @var array $tiket */

$prioritasTiket = $tiket['prioritas'] ?? null;

$kelasPrioritas = match ($prioritasTiket) {
    'Rendah' => 'low',
    'Sedang' => 'medium',
    'Tinggi' => 'high',
    default => 'unknown',
};
?>

<span class="ticket-priority ticket-priority--<?php echo $kelasPrioritas; ?>">
    <?php
    echo htmlspecialchars(
        $prioritasTiket ?? 'Belum ditentukan',
        ENT_QUOTES,
        'UTF-8'
    );
    ?>
</span>