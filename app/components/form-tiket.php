<?php

/** @var string|null $DepartemenUser */
/** @var bool $gagalMemuatDepartemen */

$keluhanLama = $_SESSION['input_tiket']['keluhan'] ?? '';

if (!is_string($keluhanLama)) {
    $keluhanLama = '';
}

$urgensiLama = $_SESSION['input_tiket']['urgensi'] ?? '';

if (
    !is_string($urgensiLama)
    || !in_array($urgensiLama, ['', 'Rendah', 'Sedang', 'Tinggi'], true)
) {
    $urgensiLama = '';
}

// Input sementara hanya digunakan untuk satu kali tampilan.
unset($_SESSION['input_tiket']);
?>

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

    <?php if ($gagalMemuatDepartemen): ?>
        <p class="form-description" role="alert">
            Departemen belum dapat dimuat. Silakan muat ulang halaman.
        </p>
    <?php else: ?>
        <p class="form-description">
            <strong>Departemen:</strong>
            <?php
            echo htmlspecialchars(
                $DepartemenUser ?? 'Belum ditentukan. Hubungi admin.',
                ENT_QUOTES,
                'UTF-8'
            );
            ?>
        </p>
    <?php endif; ?>

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
                    ?>">

        <div class="form-group">
            <label for="keluhan">Keluhan</label>

            <textarea
                id="keluhan"
                name="keluhan"
                rows="5"
                maxlength="5000"
                placeholder="Jelaskan kendala yang Anda alami."
                aria-describedby="petunjuk-keluhan"
                required><?php
                            echo htmlspecialchars(
                                $keluhanLama,
                                ENT_QUOTES | ENT_SUBSTITUTE,
                                'UTF-8'
                            );
                            ?></textarea>

            <small id="petunjuk-keluhan">
                Maksimal 5.000 karakter.
                <span id="jumlah-karakter-keluhan"></span>
            </small>
        </div>

        <div class="form-group">
            <label for="urgensi">Urgensi kendala</label>

            <select
                id="urgensi"
                name="urgensi"
                aria-describedby="petunjuk-urgensi"
                required>
                <option value="" <?php echo $urgensiLama === '' ? 'selected' : ''; ?>>
                    Pilih urgensi
                </option>

                <?php foreach (['Rendah', 'Sedang', 'Tinggi'] as $pilihan): ?>
                    <option
                        value="<?php echo $pilihan; ?>"
                        <?php echo $urgensiLama === $pilihan ? 'selected' : ''; ?>>
                        <?php echo $pilihan; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <div id="petunjuk-urgensi" class="form-description">
                <p><strong>Rendah:</strong> pekerjaan masih bisa berjalan.</p>
                <p><strong>Sedang:</strong> sebagian pekerjaan terhambat.</p>
                <p>
                    <strong>Tinggi:</strong> pekerjaan utama berhenti
                    dan tidak ada alternatif sementara.
                </p>
            </div>
        </div>

        <button type="submit" class="button-primary">
            Tambah tiket
        </button>
    </form>
</section>