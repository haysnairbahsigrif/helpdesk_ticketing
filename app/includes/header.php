<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Helpdesk Firgi</title>

    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/components.css">

    <?php if (isset($cssHalaman)): ?>
        <link
            rel="stylesheet"
            href="<?php
                    echo htmlspecialchars(
                        $cssHalaman,
                        ENT_QUOTES,
                        'UTF-8'
                    );
                    ?>">
    <?php endif; ?>
</head>

<body>
    <header class="site-header">
        <div class="container">
            <h1 class="site-title">Helpdesk Firgi</h1>
            <p class="site-description">Pencatatan tiket IT Support</p>
        </div>
    </header>