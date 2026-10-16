<?php

/** @var string $pencarian */
/** @var array<int, string> $pilihanStatusUser */
/** @var array<int, int> $departmentIds */
/** @var array<int, array{id: int|string, nama: string}> $daftarDepartemen */

$opsiStatusAkun = [
    'aktif' => 'Aktif',
    'nonaktif' => 'Nonaktif',
];
?>

<form
    class="user-search"
    action="/admin/users.php"
    method="get"
    aria-label="Pencarian dan filter akun">

    <div class="user-search-field">
        <label class="user-search-sr-only" for="cari-user">
            Cari nama, username, atau email
        </label>

        <img
            class="user-search-icon"
            src="/assets/icons/search-line.svg"
            width="20"
            height="20"
            alt="">

        <input
            type="search"
            id="cari-user"
            name="q"
            maxlength="100"
            placeholder="Nama, username, atau email"
            value="<?php
            echo htmlspecialchars($pencarian, ENT_QUOTES, 'UTF-8');
            ?>">
    </div>

    <details class="user-filter-menu">
        <summary aria-label="Filter akun" title="Filter akun">
            <span
                class="remix-icon remix-icon--filter"
                aria-hidden="true"></span>

            <span class="user-filter-menu-label">Filter</span>
        </summary>

        <div class="user-filter-menu-panel">
            <div class="user-filter-group">
                <fieldset>
                    <legend>Status akun</legend>

                    <?php foreach ($opsiStatusAkun as $nilaiStatus => $labelStatus): ?>
                        <label class="user-status-option">
                            <input
                                type="checkbox"
                                name="status[]"
                                value="<?php
                                echo htmlspecialchars(
                                    $nilaiStatus,
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>"
                                <?php
                                echo in_array(
                                    $nilaiStatus,
                                    $pilihanStatusUser,
                                    true
                                ) ? 'checked' : '';
                                ?>>

                            <span><?php
                                echo htmlspecialchars(
                                    $labelStatus,
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                            ?></span>
                        </label>
                    <?php endforeach; ?>
                </fieldset>
            </div>

            <div class="user-filter-group">
                <fieldset>
                    <legend>Departemen</legend>

                    <?php if ($daftarDepartemen === []): ?>
                        <p>Daftar departemen belum tersedia.</p>
                    <?php else: ?>
                        <?php foreach ($daftarDepartemen as $departemen): ?>
                            <label class="user-status-option">
                                <input
                                    type="checkbox"
                                    name="departemen[]"
                                    value="<?php
                                    echo (int) $departemen['id'];
                                    ?>"
                                    <?php
                                    echo in_array(
                                        (int) $departemen['id'],
                                        $departmentIds,
                                        true
                                    ) ? 'checked' : '';
                                    ?>>

                                <span><?php
                                    echo htmlspecialchars(
                                        $departemen['nama'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    );
                                ?></span>
                            </label>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </fieldset>
            </div>

            <div class="user-search-actions">
                <button type="submit" class="button-primary">
                    Terapkan
                </button>

                <a class="user-search-reset" href="/admin/users.php">
                    Reset filter
                </a>
            </div>
        </div>
    </details>
</form>