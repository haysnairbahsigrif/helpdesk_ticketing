<?php

/** @var array{status: string} $tiket */

$kelasBadgeStatus = match ($tiket['status']) {
    'Baru' => 'ticket-status--baru',
    'Diproses' => 'ticket-status--diproses',
    'Selesai' => 'ticket-status--selesai',
    default => 'ticket-status--unknown',
};
?>

<span class="ticket-status <?php
echo htmlspecialchars($kelasBadgeStatus, ENT_QUOTES, 'UTF-8');
?>">
    <?php
    echo htmlspecialchars($tiket['status'], ENT_QUOTES, 'UTF-8');
    ?>
</span>