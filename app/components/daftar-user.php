<section class="panel">
    <h2>Daftar user</h2>

    <?php if ($gagalMemuatUser): ?>

        <p role="alert">Daftar user belum dapat dimuat.</p>

    <?php elseif (empty($daftarUser)): ?>

        <p>Belum ada akun user.</p>

    <?php else: ?>

        <div class="user-table-wrapper">
            <table class="user-table">
                <thead>
                    <tr>
                        <th scope="col">Nama</th>
                        <th scope="col">Username</th>
                        <th scope="col">Email</th>
                        <th scope="col">Status akun</th>
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
                                <?php
                                echo (int) $user['is_active'] === 1
                                    ? 'Aktif'
                                    : 'Nonaktif';
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    <?php endif; ?>
</section>