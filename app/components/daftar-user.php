<?php

/** @var string $pencarian */
/** @var string $status */
/** @var bool $gagalMemuatUser */
/** @var array $daftarUser */
/** @var int $totalUser */
?>

<section class="panel users-list-panel" aria-labelledby="judul-daftar-user">
    <div class="users-list-heading">
        <div>
            <h2 id="judul-daftar-user">Akun pengguna</h2>
            <?php if (!$gagalMemuatUser): ?>
                <p>
                    <?php echo number_format($totalUser, 0, ',', '.'); ?>
                    akun ditemukan.
                </p>
            <?php endif; ?>
        </div>

        <a
            class="button-primary users-add-button"
            href="/admin/tambah-user.php">
            <img
                src="/assets/icons/add-line.svg"
                class="users-add-icon"
                width="20"
                height="20"
                alt="">
            <span>Tambah user</span>
        </a>
    </div>

    <?php require __DIR__ . '/form-cari-user.php'; ?>

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
                        <th scope="col">Departemen</th>
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

                            <td data-label="Departemen">
                                <?php
                                echo htmlspecialchars(
                                    $user['nama_departemen'] ?? 'Belum ditentukan',
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