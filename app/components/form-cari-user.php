<?php

/** @var string $pencarian */
/** @var string $status */
/** @var array<int, string> $pilihanStatusUser */
?>

<form
    class="user-search"
    action="/admin/users.php"
    method="get"
    aria-label="Pencarian dan filter akun"
    data-ticket-filter>
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

    <details class="user-status-dropdown" data-filter-dropdown>
        <summary>
            <span>Status</span>

            <span
                class="user-status-selected-count"
                data-filter-count><?php
                                    echo count($pilihanStatusUser) > 0
                                        ? '(' . count($pilihanStatusUser) . ')'
                                        : '';
                                    ?></span>

            <img
                src="/assets/icons/arrow-down-s-line.svg"
                class="user-status-dropdown-arrow"
                width="20"
                height="20"
                alt="">
        </summary>

        <div class="user-status-dropdown-panel">
            <fieldset>
                <legend>Status akun</legend>

                <?php
                $opsiStatusAkun = [
                    'aktif' => 'Aktif',
                    'nonaktif' => 'Nonaktif',
                ];
                ?>

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
    </details>

    <div class="user-search-actions">
        <button type="submit" class="button-primary">
            Terapkan
        </button>

        <a class="user-search-reset" href="/admin/users.php">
            Reset filter
        </a>
    </div>
</form>