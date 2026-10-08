<?php

/** @var array $user */
?>

<section class="panel">
    <h2>Edit data user</h2>

    <p class="form-description">
        Perbarui identitas akun pengguna.
    </p>

    <form action="/actions/edit-user.php" method="post">
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
            <label for="nama">Nama lengkap</label>
            <input
                type="text"
                id="nama"
                name="nama"
                maxlength="100"
                value="<?php
                        echo htmlspecialchars(
                            $user['nama'],
                            ENT_QUOTES,
                            'UTF-8'
                        );
                        ?>"
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
                value="<?php
                        echo htmlspecialchars(
                            $user['username'],
                            ENT_QUOTES,
                            'UTF-8'
                        );
                        ?>"
                required>

            <small id="petunjuk-username">
                Gunakan 3–50 karakter: huruf kecil, angka, titik,
                garis bawah, atau tanda hubung.
                Username ini digunakan untuk login.
            </small>
        </div>

        <div class="form-group">
            <label for="email">Email (opsional)</label>
            <input
                type="email"
                id="email"
                name="email"
                maxlength="150"
                value="<?php
                        echo htmlspecialchars(
                            $user['email'] ?? '',
                            ENT_QUOTES,
                            'UTF-8'
                        );
                        ?>">
        </div>

        <button type="submit" class="button-primary">
            Simpan perubahan
        </button>
    </form>
</section>