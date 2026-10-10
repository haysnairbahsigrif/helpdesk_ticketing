<?php

/** @var string $pesanLoginInline */
$pesanLoginInline = $pesanLoginInline ?? '';
?>

<section class="panel login-panel">
    <h2 id="judul-login">Masuk ke Helpdesk</h2>

    <p class="form-description">
        Gunakan akun yang diberikan oleh admin.
    </p>

    <form
        action="/actions/login.php"
        method="post"
        aria-labelledby="judul-login">
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
            <label for="username">Username</label>

            <input
                type="text"
                id="username"
                name="username"
                maxlength="50"
                autocomplete="username"
                autocapitalize="none"
                spellcheck="false"
                required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>

            <div class="password-field">
                <input
                    type="password"
                    id="password"
                    name="password"
                    autocomplete="current-password"
                    <?php if ($pesanLoginInline !== ''): ?>
                    aria-describedby="pesan-login-inline"
                    <?php endif; ?>
                    required>

                <button
                    type="button"
                    class="password-toggle"
                    data-password-toggle
                    aria-controls="password"
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

        <?php require __DIR__ . '/pesan-login-inline.php'; ?>

        <button type="submit" class="button-primary login-button">
            Masuk
        </button>
    </form>
</section>