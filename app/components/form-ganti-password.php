<section class="panel">
    <h2>Ganti password</h2>

    <p class="form-description">
        Masukkan password saat ini dan tentukan password baru.
    </p>

    <form action="/actions/ganti-password.php" method="post">
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
            <label for="password_lama">Password saat ini</label>
            <input
                type="password"
                id="password_lama"
                name="password_lama"
                autocomplete="current-password"
                required>
        </div>

        <div class="form-group">
            <label for="password_baru">Password baru</label>
            <input
                type="password"
                id="password_baru"
                name="password_baru"
                minlength="12"
                autocomplete="new-password"
                aria-describedby="petunjuk-password-baru"
                required>

            <small id="petunjuk-password-baru">
                Minimal 12 karakter, maksimal 72 byte,
                dan berbeda dari password saat ini.
            </small>
        </div>

        <div class="form-group">
            <label for="konfirmasi_password">
                Ulangi password baru
            </label>
            <input
                type="password"
                id="konfirmasi_password"
                name="konfirmasi_password"
                minlength="12"
                autocomplete="new-password"
                required>
        </div>

        <button type="submit" class="button-primary">
            Simpan password
        </button>
    </form>
</section>