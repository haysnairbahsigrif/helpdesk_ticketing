<?php

/** @var array $daftarDepartemen */
/** @var bool $gagalMemuatDepartemen */
?>

<section class="panel">
    <h2>Tambah user</h2>

    <p class="form-description">
        Buat akun pengguna layanan IT Support.
    </p>

    <form action="/actions/tambah-user.php" method="post">
        <input
            type="hidden"
            name="csrf_token"
            value="<?php
                    echo htmlspecialchars(
                        $_SESSION['csrf_token'],
                        ENT_QUOTES,
                        'UTF-8'
                    );
                    ?>">
        <div class="form-group">
            <label for="nama">Nama lengkap</label>
            <input
                type="text"
                id="nama"
                name="nama"
                maxlength="100"
                required>
        </div>

        <div class="form-group">
            <label for="username">Username</label>
            <input
                type="text"
                id="username"
                name="username"
                minlength="3"
                maxlength="50"
                pattern="[a-z0-9._-]{3,50}"
                autocapitalize="none"
                spellcheck="false"
                aria-describedby="petunjuk-username"
                required>
            <small id="petunjuk-username">
                Gunakan 3–50 karakter: huruf kecil, angka, titik,
                garis bawah, atau tanda hubung.
            </small>
        </div>

        <div class="form-group">
            <label for="email">Email (opsional)</label>
            <input
                type="email"
                id="email"
                name="email"
                maxlength="150">
        </div>

        <div class="form-group">
            <label for="password_sementara">Password sementara</label>
            <div class="password-field">
                <input
                    type="password"
                    id="password_sementara"
                    name="password_sementara"
                    minlength="12"
                    autocomplete="new-password"
                    aria-describedby="petunjuk-password"
                    required>

                <button
                    type="button"
                    class="password-toggle"
                    data-password-toggle
                    aria-controls="password_sementara"
                    aria-label="Tampilkan password"
                    title="Tampilkan password"
                    hidden>
                    <img
                        src="/assets/icons/eye-off-line.svg"
                        data-password-icon
                        width="20"
                        height="20"
                        alt="">
                </button>
            </div>
            <small id="petunjuk-password">
                Minimal 12 karakter dan maksimal 72 byte.
                Gunakan kombinasi huruf, angka, dan simbol.
            </small>
        </div>

        <div class="form-group">
            <label for="department_id">Departemen</label>

            <select
                id="department_id"
                name="department_id"
                aria-describedby="petunjuk-Departemen"
                required
                <?php if ($gagalMemuatDepartemen || $daftarDepartemen === []): ?>
                disabled
                <?php endif; ?>>
                <option value="">Pilih Departemen</option>

                <?php foreach ($daftarDepartemen as $Departemen): ?>
                    <option value="<?php echo (int) $Departemen['id']; ?>">
                        <?php
                        echo htmlspecialchars(
                            $Departemen['nama'],
                            ENT_QUOTES,
                            'UTF-8'
                        );
                        ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <small id="petunjuk-Departemen">
                Departemen ini otomatis dicatat ketika user membuat tiket.
            </small>

            <?php if ($gagalMemuatDepartemen): ?>
                <p role="alert">
                    Daftar Departemen belum dapat dimuat. Silakan muat ulang halaman.
                </p>
            <?php elseif ($daftarDepartemen === []): ?>
                <p role="status">
                    Belum ada Departemen yang tersedia.
                </p>
            <?php endif; ?>
        </div>

        <button
            type="submit"
            class="button-primary"
            <?php if ($gagalMemuatDepartemen || $daftarDepartemen === []): ?>
            disabled
            <?php endif; ?>>
            Buat akun
        </button>
    </form>
</section>