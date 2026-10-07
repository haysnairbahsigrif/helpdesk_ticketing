<form action="/actions/logout.php" method="post">
    <input
        type="hidden"
        name="csrf_token"
        value="<?php
            echo htmlspecialchars(
                $_SESSION['csrf_token'],
                ENT_QUOTES,
                'UTF-8'
            );
        ?>"
    >

    <button type="submit" class="button-primary">
        Keluar
    </button>
</form>