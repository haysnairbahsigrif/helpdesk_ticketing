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
            <input
                type="password"
                id="password_sementara"
                name="password_sementara"
                minlength="12"
                autocomplete="new-password"
                aria-describedby="petunjuk-password"
                required>
            <small id="petunjuk-password">
                Minimal 12 karakter dan maksimal 72 byte.
                Gunakan kombinasi huruf, angka, dan simbol.
            </small>
        </div>

        <button type="submit" class="button-primary">
            Buat akun
        </button>
    </form>
</section>