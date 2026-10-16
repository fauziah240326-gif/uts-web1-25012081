
<?php

function e($nilai)
{
    return htmlspecialchars(
        (string) $nilai,
        ENT_QUOTES,
        'UTF-8'
    );
}

function rupiah($angka)
{
    return 'Rp' . number_format(
        $angka,
        0,
        ',',
        '.'
    );
}

function hitungSubtotalItem($harga, $jumlah)
{
    return $harga * $jumlah;
}

function hitungSubtotalKeranjang($keranjang, $menus)
{
    $subtotal = 0;

    foreach ($keranjang as $kode => $item) {
        if (isset($menus[$kode])) {
            $subtotal += hitungSubtotalItem(
                $menus[$kode]['harga'],
                $item['jumlah']
            );
        }
    }

    return $subtotal;
}

function hitungDiskon($subtotal)
{
    return $subtotal >= 50000
        ? $subtotal * 0.10
        : 0;
}

function hitungTotalAkhir($subtotal)
{
    return $subtotal - hitungDiskon($subtotal);
}
