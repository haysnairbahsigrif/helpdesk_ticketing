<?php

/** @var array{id: int, status: string} $tiket */
?>

<form class="status-form" action="/actions/ubah-status.php" method="post">
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
        name="tiket_id"
        value="<?php echo (int) $tiket['id']; ?>">

    <input
        type="hidden"
        name="q"
        value="<?php
                echo htmlspecialchars(
                    $pencarian ?? '',
                    ENT_QUOTES,
                    'UTF-8'
                );
                ?>">

    <input
        type="hidden"
        name="filter_status"
        value="<?php
                echo htmlspecialchars(
                    $status ?? '',
                    ENT_QUOTES,
                    'UTF-8'
                );
                ?>">

    <input
        type="hidden"
        name="page"
        value="<?php echo (int) ($halaman ?? 1); ?>">

    <div class="form-group">
        <label for="status-<?php echo (int) $tiket['id']; ?>">
            Ubah status
        </label>

        <select
            id="status-<?php echo (int) $tiket['id']; ?>"
            name="status"
            required>
            <?php foreach (['Baru', 'Diproses', 'Selesai'] as $pilihan): ?>
                <option
                    value="<?php echo $pilihan; ?>"
                    <?php echo $tiket['status'] === $pilihan ? 'selected' : ''; ?>>
                    <?php echo $pilihan; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <button type="submit" class="button-primary">
        Simpan status
    </button>
</form>