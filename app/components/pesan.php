<?php

$kodePesan = $_GET['pesan'] ?? '';

$daftarPesan = [
    'berhasil' => 'Tiket berhasil ditambahkan.',
    'wajib-diisi' => 'Nama dan keluhan wajib diisi.',
    'nama-terlalu-panjang' => 'Nama tidak valid atau melebihi 100 karakter.',
    'data-tidak-valid' => 'Data formulir tidak valid.',
    'gagal' => 'Tiket belum tersimpan. Silakan coba lagi.',
];

$pesan = is_string($kodePesan)
    ? ($daftarPesan[$kodePesan] ?? '')
    : '';
?>

<?php if ($pesan !== ''): ?>
    <p class="notification" role="status">
        <?php echo htmlspecialchars($pesan, ENT_QUOTES, 'UTF-8'); ?>
    </p>
<?php endif; ?>