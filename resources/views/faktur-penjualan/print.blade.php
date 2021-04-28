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
    <style>@page { size: A5 landscape }</style>
    <style>
        * {
            font-size: 8.2pt;
        }

        table {
            width: 100%;
            border: thin solid black;
            border-collapse: collapse;
        }

        table td.numeric, table th.numeric {
            text-align: end;
        }

        table td, table th {
            text-align: center;
            border: thin solid black;
            padding: 0.3rem;
        }
    </style>
</head>

<body class="A5 landscape">

@foreach ($itemFakturPenjualanPages as $pageIndex => $itemFakturPenjualans)
    <section class="sheet" style="padding: 3mm">
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
                <th class="numeric"> Quantity </th>
                <th class="numeric"> Harga Satuan </th>
                <th class="numeric"> TD% </th>
                <th class="numeric"> CD% </th>
                <th class="numeric"> Jumlah Harga Rp. </th>
            </tr>
            </thead>
            <tbody>
            @foreach ($itemFakturPenjualans as $itemFakturPenjualan)
                @foreach ($itemFakturPenjualan->mutasiStocks as $mutasiStock)
                    <tr>
                        <td> {{ $itemFakturPenjualan->produk->kode  }} </td>
                        <td> {{ $itemFakturPenjualan->produk->nama  }} </td>
                        <td> {{ $mutasiStock->stock->kode_batch  }} </td>
                        <td> {{ \App\Support\Formatter::dayMonthYear($mutasiStock->stock->expired_at) }} </td>
                        <td> BOX </td>
                        <td class="numeric"> {{ \App\Support\Formatter::quantity(abs($mutasiStock->jumlah)) }} </td>
                        <td class="numeric"> {{ \App\Support\Formatter::currency($itemFakturPenjualan->harga_satuan) }} </td>
                        <td class="numeric"> {{ \App\Support\Formatter::percentage($itemFakturPenjualan->diskon)  }} </td>
                        <td class="numeric"> {{ \App\Support\Formatter::percentage($fakturPenjualan->diskon) }} </td>
                        <td class="numeric"> {{ \App\Support\Formatter::currency(bcmul($itemFakturPenjualan->harga_satuan, abs($mutasiStock->jumlah))) }} </td>
                    </tr>
                @endforeach
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <td>  </td>  {{-- KODE --}}
                <td>  </td>  {{-- Nama Barang --}}
                <td>  </td>  {{-- No. Batch --}}
                <td>  </td>  {{-- E.D. --}}
                <td>  </td>  {{-- Satuan --}}
                <td>  </td>  {{-- Quantity --}}
                <td>  </td>  {{-- Harga Satuan --}}
                <td class="numeric"> {{ \App\Support\Formatter::currency($totalDiskonCd) }} </td> {{-- TD% --}}
                <td class="numeric"> {{ \App\Support\Formatter::currency($totalDiskonTd) }} </td> {{-- CD% --}}
                <td class="numeric"> {{ \App\Support\Formatter::currency($total) }} </td> {{-- Jumlah Harga Rp. --}}
            </tr>
            </tfoot>
        </table>
    </section>
@endforeach

</body>
</html>