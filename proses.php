
<?php
session_start();

require_once 'data/menu.php';
require_once 'includes/fungsi.php';

if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = [];
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$aksi = (string) ($_POST['aksi'] ?? '');

function kembali($url, $pesan)
{
    $_SESSION['flash'] = $pesan;
    header('Location: ' . $url);
    exit;
}

if ($aksi === 'tambah') {
    $kode = (string) ($_POST['kode'] ?? '');
    $inputJumlah = $_POST['jumlah'] ?? '';

    // Kode harus tersedia di data menu
    if (!isset($menus[$kode])) {
        kembali('index.php', 'Kode menu tidak valid.');
    }

    // Jumlah wajib berupa bilangan bulat 1 sampai 10
    if (
        !is_scalar($inputJumlah) ||
        !preg_match('/^[0-9]+$/', (string) $inputJumlah)
    ) {
        kembali(
            'index.php',
            'Jumlah harus bilangan bulat antara 1 dan 10.'
        );
    }

    $jumlah = (int) $inputJumlah;

    if ($jumlah < 1 || $jumlah > 10) {
        kembali(
            'index.php',
            'Jumlah harus antara 1 dan 10.'
        );
    }

    // Periksa jumlah lama sebelum mengubah keranjang
    $jumlahLama = $_SESSION['keranjang'][$kode]['jumlah'] ?? 0;

    if ($jumlahLama + $jumlah > 10) {
        kembali(
            'index.php',
            'Gagal: jumlah menu tidak boleh lebih dari 10.'
        );
    }

    // Harga selalu diambil dari array server
    if (!isset($_SESSION['keranjang'][$kode])) {
        $_SESSION['keranjang'][$kode] = [
            'jumlah' => 0
        ];
    }

    $_SESSION['keranjang'][$kode]['jumlah'] += $jumlah;

    kembali(
        'keranjang.php',
        'Pesanan berhasil ditambahkan.'
    );
}

if ($aksi === 'hapus') {
    $kode = (string) ($_POST['kode'] ?? '');

    if (!isset($_SESSION['keranjang'][$kode])) {
        kembali('keranjang.php', 'Item tidak ditemukan.');
    }

    unset($_SESSION['keranjang'][$kode]);

    kembali('keranjang.php', 'Item berhasil dihapus.');
}

if ($aksi === 'kosongkan') {
    $_SESSION['keranjang'] = [];

    kembali('keranjang.php', 'Keranjang berhasil dikosongkan.');
}

if ($aksi === 'simpan_kategori') {
    $kategoriFavorit = $_POST['kategori_favorit'] ?? '';

    if (
        !is_string($kategoriFavorit) ||
        !in_array($kategoriFavorit, $kategoriTersedia, true)
    ) {
        kembali('index.php', 'Kategori favorit tidak valid.');
    }

    setcookie(
        'kategori_favorit',
        $kategoriFavorit,
        [
            'expires' => time() + (7 * 24 * 60 * 60),
            'path' => '/',
            'httponly' => true,
            'samesite' => 'Lax'
        ]
    );

    kembali('index.php', 'Kategori favorit berhasil disimpan.');
}

kembali('index.php', 'Aksi tidak dikenal.');
