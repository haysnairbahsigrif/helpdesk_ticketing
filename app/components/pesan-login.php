<?php

/** @var string $pesanLogin */
/** @var string $jenisPesanLogin */

$labelPesanLogin = match ($jenisPesanLogin) {
    'success' => 'Berhasil',
    'error' => 'Perlu diperiksa',
    default => 'Informasi',
};

$rolePesanLogin = $jenisPesanLogin === 'error'
    ? 'alert'
    : 'status';
?>

<?php if ($pesanLogin !== ''): ?>
    <div
        class="notification login-notification notification--<?php
        echo htmlspecialchars($jenisPesanLogin, ENT_QUOTES, 'UTF-8');
        ?>"
        role="<?php
        echo htmlspecialchars($rolePesanLogin, ENT_QUOTES, 'UTF-8');
        ?>">

        <strong class="notification-title">
            <?php
            echo htmlspecialchars($labelPesanLogin, ENT_QUOTES, 'UTF-8');
            ?>
        </strong>

        <p class="notification-message">
            <?php
            echo htmlspecialchars($pesanLogin, ENT_QUOTES, 'UTF-8');
            ?>
        </p>
    </div>
<?php endif; ?>