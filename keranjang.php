
<?php
session_start();

require_once 'data/menu.php';
require_once 'includes/fungsi.php';

$judul = 'Keranjang Belanja';

if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = [];
}

$keranjang = $_SESSION['keranjang'];
$subtotal = hitungSubtotalKeranjang($keranjang, $menus);
$diskon = hitungDiskon($subtotal);
$totalAkhir = hitungTotalAkhir($subtotal);

require 'includes/header.php';
?>

<h2>Keranjang Pesanan</h2>

<?php if (empty($keranjang)): ?>

    <p>Keranjang masih kosong.</p>
    <a href="index.php">Kembali ke Daftar Menu</a>

<?php else: ?>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nama Menu</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
            <?php foreach ($keranjang as $kode => $item): ?>
                <?php if (!isset($menus[$kode])) continue; ?>

                <?php
                $menu = $menus[$kode];
                $subtotalItem = hitungSubtotalItem(
                    $menu['harga'],
                    $item['jumlah']
                );
                ?>

                <tr>
                    <td><?= e($menu['nama']) ?></td>
                    <td><?= rupiah($menu['harga']) ?></td>
                    <td><?= e($item['jumlah']) ?></td>
                    <td><?= rupiah($subtotalItem) ?></td>

                    <td>
                        <form method="post" action="proses.php">
                            <input type="hidden" name="aksi" value="hapus">
                            <input type="hidden" name="kode"
                                   value="<?= e($kode) ?>">

                            <button type="submit" class="btn-hapus">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <section class="ringkasan">
        <p>Subtotal seluruh pesanan: <strong><?= rupiah($subtotal) ?></strong></p>
        <p>Diskon: <strong><?= rupiah($diskon) ?></strong></p>
        <p>Total pembayaran:
            <strong><?= rupiah($totalAkhir) ?></strong>
        </p>
    </section>

    <form method="post" action="proses.php"
          onsubmit="return confirm('Kosongkan seluruh keranjang?')">
        <input type="hidden" name="aksi" value="kosongkan">
        <button type="submit" class="btn-hapus">
            Kosongkan Keranjang
        </button>
    </form>

<?php endif; ?>

<?php require 'includes/footer.php'; ?>
