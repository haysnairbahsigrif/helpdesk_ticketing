<?php

/** @var array $user */

$statusTujuan = (int) $user['is_active'] === 1 ? 0 : 1;

$pesanKonfirmasi = $statusTujuan === 0
    ? 'Nonaktifkan akun ' . $user['username']
    . '? Pengguna akan kehilangan akses dan sesi lamanya dibatalkan.'
    : 'Aktifkan kembali akun ' . $user['username'] . '?';
?>

<form
    class="user-status-form"
    action="/actions/ubah-status-user.php"
    method="post"
    data-confirm="<?php
                    echo htmlspecialchars(
                        $pesanKonfirmasi,
                        ENT_QUOTES,
                        'UTF-8'
                    );
                    ?>">

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

    <input
        type="hidden"
        name="is_active"
        value="<?php echo $statusTujuan; ?>">

    <button type="submit" class="button-primary">
        <?php
        echo $statusTujuan === 0
            ? 'Nonaktifkan'
            : 'Aktifkan';
        ?>
    </button>
</form>