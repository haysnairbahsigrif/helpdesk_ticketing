<section class="panel ticket-list">
    <h2>Tiket saya</h2>

    <?php if ($gagalMemuatTiket): ?>

        <p role="alert">
            Daftar tiket belum dapat dimuat. Silakan coba lagi.
        </p>

    <?php elseif (empty($daftarTiket)): ?>

        <p>Anda belum memiliki tiket.</p>

    <?php else: ?>

        <?php foreach ($daftarTiket as $tiket): ?>
            <article class="ticket-item">
                <h3>Tiket #<?php echo (int) $tiket['id']; ?></h3>

                <p class="ticket-complaint"><?php
                    echo htmlspecialchars(
                        $tiket['keluhan'],
                        ENT_QUOTES,
                        'UTF-8'
                    );
                ?></p>

                <p>
                    <strong>Status:</strong>
                    <?php
                    echo htmlspecialchars(
                        $tiket['status'],
                        ENT_QUOTES,
                        'UTF-8'
                    );
                    ?>
                </p>

                <p class="ticket-date">
                    Dibuat:
                    <?php
                    echo htmlspecialchars(
                        $tiket['created_at'],
                        ENT_QUOTES,
                        'UTF-8'
                    );
                    ?>
                </p>
            </article>
        <?php endforeach; ?>

    <?php endif; ?>
</section>