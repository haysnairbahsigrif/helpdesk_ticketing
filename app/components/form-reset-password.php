<?php

/** @var array{id: int, nama: string, username: string, is_active: int} $user */
?>

<section class="panel">
    <h2>Reset password user</h2>

    <p>
        <strong>Nama:</strong>
        <?php
        echo htmlspecialchars($user['nama'], ENT_QUOTES, 'UTF-8');
        ?>
    </p>

    <p class="form-description">
        <strong>Username:</strong>
        <?php
        echo htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8');
        ?>
    </p>

    <form
        action="/actions/reset-password.php"
        method="post"
        data-confirm="Reset password akun yang ditampilkan? Password lama tidak berlaku dan sesi pengguna akan dibatalkan.">
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

        <input
            type="hidden"
            name="user_id"
            value="<?php echo (int) $user['id']; ?>">
        <div class="form-group">
            <label for="password_sementara">Password sementara baru</label>

            <div class="password-field">
                <input
                    type="password"
                    id="password_sementara"
                    name="password_sementara"
                    minlength="12"
                    autocomplete="new-password"
                    aria-describedby="petunjuk-reset"
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
                        src="/assets/icons/eye-line.svg"
                        data-password-icon
                        width="20"
                        height="20"
                        alt="">
                </button>
            </div>

            <small id="petunjuk-reset">
                Minimal 12 karakter dan maksimal 72 byte.
            </small>
        </div>

        <div class="form-group">
            <label for="konfirmasi_password">
                Ulangi password sementara
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
                        src="/assets/icons/eye-off-line.svg"
                        data-password-icon
                        width="20"
                        height="20"
                        alt="">
                </button>
            </div>
        </div>

        <p class="form-description">
            Setelah reset berhasil, sesi lama tidak berlaku
            dan pengguna wajib mengganti password saat login.
        </p>

        <button type="submit" class="button-primary">
            Reset password
        </button>
    </form>
</section>