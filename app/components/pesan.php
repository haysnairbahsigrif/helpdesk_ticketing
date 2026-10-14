<?php

$kodePesan = $_GET['pesan'] ?? '';

$daftarPesan = [
    'berhasil' => 'Tiket berhasil ditambahkan.',
    'wajib-diisi' => 'Keluhan wajib diisi.',
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
    'password-lama-salah' => 'Password saat ini tidak sesuai.',
    'password-sama' => 'Password baru harus berbeda dari password saat ini.',
    'password-tidak-valid' => 'Isi password saat ini. Password baru minimal 12 karakter dan maksimal 72 byte.',
    'password-konfirmasi-salah' => 'Konfirmasi password baru tidak cocok.',
    'password-gagal' => 'Password belum dapat diganti. Silakan coba lagi.',
    'reset-berhasil' => 'Password sementara berhasil disimpan. Sesi lama user tidak berlaku.',
    'reset-tidak-valid' => 'Password sementara minimal 12 karakter dan maksimal 72 byte.',
    'reset-konfirmasi-salah' => 'Konfirmasi password sementara tidak cocok.',
    'reset-gagal' => 'Password belum dapat direset. Silakan coba lagi.',
    'reset-user-tidak-ditemukan' => 'Akun user tujuan tidak ditemukan.',
    'akun-status-berhasil' => 'Status akun berhasil diperbarui.',
    'akun-status-gagal' => 'Status akun belum dapat diperbarui. Silakan coba lagi.',
    'akun-status-tidak-berubah' => 'Akun tidak ditemukan atau statusnya sudah sesuai. Muat ulang daftar user.',
    'edit-user-berhasil' => 'Data user berhasil disimpan.',
    'edit-user-gagal' => 'Data user belum dapat disimpan. Silakan coba lagi.',
    'edit-user-tidak-valid' => 'Periksa nama, format username, dan email.',
    'edit-user-tidak-ditemukan' => 'Akun user tidak ditemukan.',
    'status-transisi-ditolak' => 'Status tidak diubah. Tiket tidak ditemukan atau statusnya sudah tidak sesuai dengan tindakan tersebut. Muat ulang daftar tiket.',
    'Departemen-belum-diatur' => 'Departemen akun belum ditentukan. Hubungi admin sebelum membuat tiket.',
    'urgensi-tidak-valid' => 'Pilih urgensi kendala: Rendah, Sedang, atau Tinggi.',
    'user-Departemen-tidak-valid' => 'Pilih Departemen yang tersedia untuk akun user.',
];

$pesan = is_string($kodePesan)
    ? ($daftarPesan[$kodePesan] ?? '')
    : '';

$kodeBerhasil = [
    'berhasil',
    'status-berhasil',
    'user-berhasil',
    'reset-berhasil',
    'akun-status-berhasil',
    'edit-user-berhasil',
];

$kodeInformasi = [
    'akun-status-tidak-berubah',
];

if (in_array($kodePesan, $kodeBerhasil, true)) {
    $jenisPesan = 'success';
    $labelPesan = 'Berhasil';
    $rolePesan = 'status';
} elseif (in_array($kodePesan, $kodeInformasi, true)) {
    $jenisPesan = 'info';
    $labelPesan = 'Informasi';
    $rolePesan = 'status';
} else {
    $jenisPesan = 'error';
    $labelPesan = 'Perlu diperiksa';
    $rolePesan = 'alert';
}
?>

<?php if ($pesan !== ''): ?>
    <div
        class="notification notification--<?php
                                            echo htmlspecialchars($jenisPesan, ENT_QUOTES, 'UTF-8');
                                            ?>"
        role="<?php
                echo htmlspecialchars($rolePesan, ENT_QUOTES, 'UTF-8');
                ?>">

        <strong class="notification-title">
            <?php
            echo htmlspecialchars($labelPesan, ENT_QUOTES, 'UTF-8');
            ?>
        </strong>

        <p class="notification-message">
            <?php
            echo htmlspecialchars($pesan, ENT_QUOTES, 'UTF-8');
            ?>
        </p>
    </div>
<?php endif; ?>