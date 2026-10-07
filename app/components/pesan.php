<?php

$kodePesan = $_GET['pesan'] ?? '';

$daftarPesan = [
    'berhasil' => 'Tiket berhasil ditambahkan.',
    'wajib-diisi' => 'Nama dan keluhan wajib diisi.',
    'nama-terlalu-panjang' => 'Nama tidak valid atau melebihi 100 karakter.',
    'data-tidak-valid' => 'Data formulir tidak valid.',
    'gagal' => 'Tiket belum tersimpan. Silakan coba lagi.',
    'status-berhasil' => 'Status tiket berhasil disimpan.',
    'status-gagal' => 'Status belum dapat disimpan. Silakan coba lagi.',
    'status-tidak-valid' => 'ID tiket atau status tidak valid.',
    'tiket-tidak-ditemukan' => 'Tiket tidak ditemukan.',
    'user-berhasil' => 'Akun user berhasil dibuat.',
    'user-duplikat' => 'Username atau email sudah digunakan.',
    'user-gagal' => 'Akun belum dapat disimpan. Silakan coba lagi.',
    'user-tidak-valid' => 'Periksa nama, format username, email, dan panjang password.',
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