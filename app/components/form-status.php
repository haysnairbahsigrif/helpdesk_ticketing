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
        ?>"
    >

    <input
        type="hidden"
        name="tiket_id"
        value="<?php echo (int) $tiket['id']; ?>"
    >

    <div class="form-group">
        <label for="status-<?php echo (int) $tiket['id']; ?>">
            Ubah status
        </label>

        <select
            id="status-<?php echo (int) $tiket['id']; ?>"
            name="status"
            required
        >
            <?php foreach (['Baru', 'Diproses', 'Selesai'] as $pilihan): ?>
                <option
                    value="<?php echo $pilihan; ?>"
                    <?php echo $tiket['status'] === $pilihan ? 'selected' : ''; ?>
                >
                    <?php echo $pilihan; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <button type="submit" class="button-primary">
        Simpan status
    </button>
</form>