<?php

/** @var string $pesanLoginInline */
?>

<?php if ($pesanLoginInline !== ''): ?>
    <p
        id="pesan-login-inline"
        class="login-inline-error"
        role="alert">
        <?php
        echo htmlspecialchars(
            $pesanLoginInline,
            ENT_QUOTES,
            'UTF-8'
        );
        ?>
    </p>
<?php endif; ?>