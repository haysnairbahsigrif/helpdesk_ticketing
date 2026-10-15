<?php

// Mendukung status tunggal saat ini dan array checkbox nantinya.
$statusUntukDikirim = $status ?? [];

if (is_string($statusUntukDikirim)) {
    $statusUntukDikirim = $statusUntukDikirim === ''
        ? []
        : [$statusUntukDikirim];
}

$prioritasUntukDikirim = $filterPrioritas ?? [];
?>

<input
    type="hidden"
    name="q"
    value="<?php
    echo htmlspecialchars($pencarian ?? '', ENT_QUOTES, 'UTF-8');
    ?>">

<input
    type="hidden"
    name="page"
    value="<?php echo (int) ($halaman ?? 1); ?>">

<?php foreach ($statusUntukDikirim as $nilaiStatus): ?>
    <input
        type="hidden"
        name="filter_status[]"
        value="<?php
        echo htmlspecialchars($nilaiStatus, ENT_QUOTES, 'UTF-8');
        ?>">
<?php endforeach; ?>

<?php foreach ($prioritasUntukDikirim as $nilaiPrioritas): ?>
    <input
        type="hidden"
        name="filter_prioritas[]"
        value="<?php
        echo htmlspecialchars($nilaiPrioritas, ENT_QUOTES, 'UTF-8');
        ?>">
<?php endforeach; ?>