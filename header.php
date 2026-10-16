
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1">
    <title><?= e($judul ?? 'Kantin Kampus') ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="header">
    <h1>Pemesanan Kantin Kampus</h1>
    <nav>
        <a href="index.php">Daftar Menu</a>
        <a href="keranjang.php">Keranjang</a>
    </nav>
</header>

<main class="container">
<?php if (!empty($_SESSION['flash'])): ?>
    <div class="pesan">
        <?= e($_SESSION['flash']) ?>
    </div>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>
