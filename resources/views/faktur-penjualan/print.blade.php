<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"
    >
    <meta http-equiv="X-UA-Compatible"
          content="ie=edge"
    >
    <title> Cetak Faktur Penjualan {{ $fakturPenjualan->getNomor() }} </title>
    <link rel="stylesheet"
          href="{{ asset("css/paper.css") }}"
    >
    <style>@page { size: A4 }</style>
    <style>
        table {
            width: 100%;
            border: thin solid black;
            border-collapse: collapse;
        }

        table td, table th {
            border: thin solid black;
            padding: 0.2rem;
        }
    </style>
</head>

<body class="A4">
<section class="sheet padding-10mm">
    <header>


    </header>

    <table>
        <thead>
            <tr>
                <th> KODE </th>
                <th> Nama Barang </th>
                <th> No. Batch </th>
                <th> E.D. </th>
                <th> Satuan </th>
                <th> Quantity </th>
                <th> Harga Satuan </th>
                <th> TD% </th>
                <th> CD% </th>
                <th> Jumlah Harga Rp. </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($fakturPenjualan->itemFakturPenjualans as $itemFakturPenjualan)
                <tr>
                    <td> {{ $itemFakturPenjualan->produk->kode }} </td>
                    <td> {{ $itemFakturPenjualan->produk->nama }} </td>
                    <td> {{ $itemFakturPenjualan->produk->nama }} </td>
                    <td> {{ $itemFakturPenjualan->produk->nama }} </td>
                    <td> {{ $itemFakturPenjualan->produk->nama }} </td>
                    <td> {{ $itemFakturPenjualan->produk->nama }} </td>
                    <td> {{ $itemFakturPenjualan->produk->nama }} </td>
                    <td> {{ $itemFakturPenjualan->produk->nama }} </td>
                    <td> {{ $itemFakturPenjualan->produk->nama }} </td>
                    <td> {{ $itemFakturPenjualan->produk->nama }} </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</section>

</body>
</html>