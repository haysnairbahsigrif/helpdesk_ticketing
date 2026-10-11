<?php

/** @var string $pencarian */
/** @var string $status */
?>

<section class="panel">

    <h2>Daftar user</h2>

    <?php if ($gagalMemuatUser): ?>

        <p role="alert">Daftar user belum dapat dimuat.</p>

    <?php elseif (empty($daftarUser)): ?>

        <p>
            <?php
            echo $pencarian !== '' || $status !== ''
                ? 'Tidak ada user yang cocok dengan pencarian dan filter.'
                : 'Belum ada akun user.';
            ?>
        </p>

    <?php else: ?>

        <div class="user-table-wrapper">
            <table class="user-table">
                <thead>
                    <tr>
                        <th scope="col">Nama</th>
                        <th scope="col">Username</th>
                        <th scope="col">Email</th>
                        <th scope="col">Status akun</th>
                        <th scope="col">Tindakan</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($daftarUser as $user): ?>
                        <tr>
                            <td data-label="Nama">
                                <?php
                                echo htmlspecialchars(
                                    $user['nama'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>
                            </td>

                            <td data-label="Username">
                                <?php
                                echo htmlspecialchars(
                                    $user['username'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>
                            </td>

                            <td data-label="Email">
                                <?php
                                echo htmlspecialchars(
                                    $user['email'] ?? 'Belum diisi',
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>
                            </td>

                            <td data-label="Status akun">
                                <?php require __DIR__ . '/form-status-user.php'; ?>
                            </td>

                            <td data-label="Tindakan">
                                <div class="user-action-links">
                                    <a
                                        class="user-action-button user-action-button--edit"
                                        href="/admin/edit-user.php?id=<?php echo (int) $user['id']; ?>">
                                        Edit
                                    </a>

                                    <a
                                        class="user-action-button user-action-button--reset"
                                        href="/admin/reset-password.php?id=<?php echo (int) $user['id']; ?>">
                                        Reset password
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    <?php endif; ?>

    <?php if (!$gagalMemuatUser && !empty($daftarUser)): ?>
        <?php require __DIR__ . '/pagination-user.php'; ?>
    <?php endif; ?>
</section>