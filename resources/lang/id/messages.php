<?php

return [
    'product' => [
        'delete' => ['failure' => "Gagal menghapus data: Produk ini masih terdapat di faktur pembelian / penjualan ataupun retur pembelian  / penjualan.",],
    ],

    'supplier' => [
        'delete' => ['failure' => "Gagal menghapus data: Pemasok ini masih terdapat di faktur pembelian.",],
    ],

    'customer' => [
        'delete' => ['failure' => "Gagal menghapus data: Pelanggan ini masih terdapat di faktur penjualan.",],
    ],

    'create' => [
        'success' => 'Data berhasil ditambahkan.',
        'failure' => 'Data gagal ditambahkan.',
    ],

    'update' => [
        'success' => 'Data berhasil diperbarui.',
        'failure' => 'Data gagal diperbarui.',
    ],

    'delete' => [
        'success' => 'Data berhasil dihapus.',
        'failure' => 'Data gagal dihapus.',
    ]
];
