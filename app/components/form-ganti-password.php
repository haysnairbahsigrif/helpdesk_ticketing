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

            <div class="password-field">
                <input
                    type="password"
                    id="password_lama"
                    name="password_lama"
                    autocomplete="current-password"
                    required>

                <button
                    type="button"
                    class="password-toggle"
                    data-password-toggle
                    aria-controls="password_lama"
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
        </div>

        <div class="form-group">
            <label for="password_baru">Password baru</label>

            <div class="password-field">
                <input
                    type="password"
                    id="password_baru"
                    name="password_baru"
                    minlength="12"
                    autocomplete="new-password"
                    aria-describedby="petunjuk-password-baru"
                    required>

                <button
                    type="button"
                    class="password-toggle"
                    data-password-toggle
                    aria-controls="password_baru"
                    aria-label="Tampilkan password"
                    title="Tampilkan password"
                    hidden>
                    <img
                        src="/assets/icons/eye-line.svg"
                        data-password-icon
                        width="20"
                        height="20"
                        alt="">
                </button>
            </div>

            <small id="petunjuk-password-baru">
                Minimal 12 karakter, maksimal 72 byte,
                dan berbeda dari password saat ini.
            </small>
        </div>

        <div class="form-group">
            <label for="konfirmasi_password">
                Ulangi password baru
            </label>

            <div class="password-field">
                <input
                    type="password"
                    id="konfirmasi_password"
                    name="konfirmasi_password"
                    minlength="12"
                    autocomplete="new-password"
                    required>

                <button
                    type="button"
                    class="password-toggle"
                    data-password-toggle
                    aria-controls="konfirmasi_password"
                    aria-label="Tampilkan password"
                    title="Tampilkan password"
                    hidden>
                    <img
                        src="/assets/icons/eye-line.svg"
                        data-password-icon
                        width="20"
                        height="20"
                        alt="">
                </button>
            </div>
        </div>

        <button type="submit" class="button-primary">
            Simpan password
        </button>
    </form>
</section>