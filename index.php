
<?php
session_start();

require_once 'data/menu.php';
require_once 'includes/fungsi.php';

$judul = 'Daftar Menu';

if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = [];
}

// Menentukan kategori: GET lebih diutamakan daripada cookie
$kategori = $_COOKIE['kategori_favorit'] ?? '';

if (isset($_GET['kategori'])) {
    $kategori = (string) $_GET['kategori'];
}

$pesanKategori = '';

if ($kategori !== '' &&
    !in_array($kategori, $kategoriTersedia, true)) {
    $pesanKategori = 'Kategori tidak valid.';
    $kategori = '';
}

$pencarian = trim((string) ($_GET['cari'] ?? ''));

require 'includes/header.php';
?>

<h2>Daftar Menu Kantin</h2>

<form method="get" action="index.php" class="form">
    <label for="cari">Cari nama menu:</label>
    <input type="text"
           id="cari"
           name="cari"
           value="<?= e($pencarian) ?>"
           placeholder="Contoh: Nasi Goreng">
    <button type="submit">Cari</button>
    <a href="index.php">Reset</a>
</form>

<?php if ($pesanKategori !== ''): ?>
    <p class="error"><?= e($pesanKategori) ?></p>
<?php endif; ?>

<form method="get" action="index.php" class="form">
    <label for="kategori">Filter kategori:</label>

    <select name="kategori" id="kategori">
        <option value="">Semua Kategori</option>

        <?php foreach ($kategoriTersedia as $k): ?>
            <option value="<?= e($k) ?>"
                <?= $kategori === $k ? 'selected' : '' ?>>
                <?= e($k) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit">Tampilkan</button>
</form>

<form method="post" action="proses.php" class="form">
    <input type="hidden" name="aksi" value="simpan_kategori">

    <label for="kategori_favorit">
        Simpan kategori favorit selama 7 hari:
    </label>

    <select name="kategori_favorit" id="kategori_favorit" required>
        <?php foreach ($kategoriTersedia as $k): ?>
            <option value="<?= e($k) ?>"
                <?= $kategori === $k ? 'selected' : '' ?>>
                <?= e($k) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit">Simpan Favorit</button>
</form>

<p>
    Kategori aktif:
    <strong><?= e($kategori !== '' ? $kategori : 'Semua Kategori') ?></strong>
</p>

<div class="menu-grid">
<?php
$jumlahHasil = 0;

foreach ($menus as $kode => $menu):
    if (
        $kategori !== '' &&
        $menu['kategori'] !== $kategori
    ) {
        continue;
    }

    if (
        $pencarian !== '' &&
        stripos($menu['nama'], $pencarian) === false
    ) {
        continue;
    }

    $jumlahHasil++;
?>
    <article class="menu-card">
        <h3><?= e($menu['nama']) ?></h3>

        <p>Kode: <?= e($kode) ?></p>
        <p>Kategori: <?= e($menu['kategori']) ?></p>
        <p>Harga: <?= rupiah($menu['harga']) ?></p>

        <form method="post" action="proses.php">
            <input type="hidden" name="aksi" value="tambah">
            <input type="hidden" name="kode" value="<?= e($kode) ?>">

            <label for="jumlah-<?= e($kode) ?>">Jumlah (1–10):</label>

            <input type="number"
                   id="jumlah-<?= e($kode) ?>"
                   name="jumlah"
                   min="1"
                   max="10"
                   step="1"
                   required>

            <button type="submit">Tambah ke Keranjang</button>
        </form>
    </article>
<?php endforeach; ?>
</div>

<?php if ($jumlahHasil === 0): ?>
    <p class="error">
        Menu tidak ditemukan. Silakan coba kata kunci lain.
    </p>
<?php endif; ?>

<?php require 'includes/footer.php'; ?>
