<?php

/** @var array $user */
/** @var array<int, string> $pilihanStatusUser */

$akunAktif = (int) $user['is_active'] === 1;
$statusTujuan = $akunAktif ? 0 : 1;

$pesanKonfirmasi = $akunAktif
    ? 'Nonaktifkan akun ' . $user['username']
    . '? Pengguna akan kehilangan akses dan sesi lamanya dibatalkan.'
    : 'Aktifkan kembali akun ' . $user['username'] . '?';
?>

<form
    class="user-status-form"
    action="/actions/ubah-status-user.php"
    method="post"
    data-confirm="<?php
                    echo htmlspecialchars($pesanKonfirmasi, ENT_QUOTES, 'UTF-8');
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

    <?php foreach ($pilihanStatusUser as $statusFilterUser): ?>
        <input
            type="hidden"
            name="filter_status[]"
            value="<?php
                    echo htmlspecialchars(
                        $statusFilterUser,
                        ENT_QUOTES,
                        'UTF-8'
                    );
                    ?>">
    <?php endforeach; ?>

    <input
        type="hidden"
        name="page"
        value="<?php echo (int) ($halaman ?? 1); ?>">

    <div class="user-status-control">
        <button
            type="submit"
            class="user-status-switch"
            role="switch"
            aria-checked="<?php echo $akunAktif ? 'true' : 'false'; ?>"
            aria-label="<?php
                        echo htmlspecialchars(
                            'Status aktif akun ' . $user['username'],
                            ENT_QUOTES,
                            'UTF-8'
                        );
                        ?>"
            title="<?php
                    echo $akunAktif
                        ? 'Nonaktifkan akun'
                        : 'Aktifkan akun';
                    ?>">
            <span class="user-status-track" aria-hidden="true">
                <span class="user-status-thumb"></span>
            </span>
        </button>

        <span class="user-status-text">
            <?php echo $akunAktif ? 'Aktif' : 'Nonaktif'; ?>
        </span>
    </div>
</form>