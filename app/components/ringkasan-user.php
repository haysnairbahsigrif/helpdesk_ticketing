<?php

/** @var array<string, int> $ringkasanUser */

$kartuUser = [
    [
        'label' => 'Total Tiket',
        'jumlah' => $ringkasanUser['total'],
        'url' => '/user/',
    ],
    [
        'label' => 'Baru',
        'jumlah' => $ringkasanUser['Baru'],
        'url' => '/user/?status=Baru',
    ],
    [
        'label' => 'Diproses',
        'jumlah' => $ringkasanUser['Diproses'],
        'url' => '/user/?status=Diproses',
    ],
    [
        'label' => 'Selesai',
        'jumlah' => $ringkasanUser['Selesai'],
        'url' => '/user/?status=Selesai',
    ],
];
?>

<section aria-label="Ringkasan tiket Anda">
    <div class="user-summary">
        <?php foreach ($kartuUser as $kartu): ?>
            <a
                class="user-summary-card"
                href="<?php
                        echo htmlspecialchars(
                            $kartu['url'],
                            ENT_QUOTES,
                            'UTF-8'
                        );
                        ?>">

                <h2 class="user-summary-label">
                    <?php
                    echo htmlspecialchars(
                        $kartu['label'],
                        ENT_QUOTES,
                        'UTF-8'
                    );
                    ?>
                </h2>

                <p class="user-summary-value">
                    <?php
                    echo number_format(
                        $kartu['jumlah'],
                        0,
                        ',',
                        '.'
                    );
                    ?>
                </p>

                <span class="user-summary-link">
                    Lihat tiket <span class="remix-icon remix-icon--right" aria-hidden="true"></span>
                </span>
            </a>
        <?php endforeach; ?>
    </div>
</section>