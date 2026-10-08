<?php

/** @var string $pencarian */
/** @var string $status */
?>

<form class="user-search" action="/admin/users.php" method="get">
    <div class="form-group">
        <label for="cari-user">Cari user</label>

        <input
            type="search"
            id="cari-user"
            name="q"
            maxlength="100"
            placeholder="Nama, username, atau email"
            value="<?php
                    echo htmlspecialchars(
                        $pencarian,
                        ENT_QUOTES,
                        'UTF-8'
                    );
                    ?>">
    </div>

    <div class="form-group">
        <label for="status-user">Status akun</label>

        <select id="status-user" name="status">
            <option value="" <?php echo $status === '' ? 'selected' : ''; ?>>
                Semua status
            </option>

            <option
                value="aktif"
                <?php echo $status === 'aktif' ? 'selected' : ''; ?>>
                Aktif
            </option>

            <option
                value="nonaktif"
                <?php echo $status === 'nonaktif' ? 'selected' : ''; ?>>
                Nonaktif
            </option>
        </select>
    </div>

    <div class="user-search-actions">
        <button type="submit" class="button-primary">
            Terapkan
        </button>

        <a href="/admin/users.php">Tampilkan semua</a>
    </div>
</form>