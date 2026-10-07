<section class="panel">
    <h2>Tambah tiket</h2>

    <p class="form-description">
        Pelapor:
        <?php
        echo htmlspecialchars(
            $_SESSION['akun']['nama'],
            ENT_QUOTES,
            'UTF-8'
        );
        ?>
    </p>

    <form action="/actions/tambah-tiket.php" method="post">
        <input
            type="hidden"
            name="csrf_token"
            value="<?php
                echo htmlspecialchars(
                    $_SESSION['csrf_token'],
                    ENT_QUOTES,
                    'UTF-8'
                );
            ?>"
        >

        <div class="form-group">
            <label for="keluhan">Keluhan</label>

            <textarea
                id="keluhan"
                name="keluhan"
                rows="5"
                maxlength="5000"
                placeholder="Jelaskan kendala yang Anda alami."
                required
            ></textarea>
        </div>

        <button type="submit" class="button-primary">
            Tambah tiket
        </button>
    </form>
</section>